<?php

namespace App\Http\Controllers;

use App\Address;
use App\City;
use App\District;
use App\Product;
use App\ProductType;
use App\Province;
use App\Store;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($store_id)
    {
        $warehouses = Warehouse::where('store_id', $store_id)->orderBy('created_at', 'DESC')->get();
        return view('warehouse.showAll', compact('warehouses', 'store_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($store_id)
    {
        $provinces = Province::orderBy('province_id')->pluck('name', 'province_id')->all();
        return view('warehouse.create', compact('provinces', 'store_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $store_id)
    {
        $input = $request->all();
        $store = Store::findOrFail($store_id);
        if(!$store->warehouses()->where('name', $input['name'])->first()){
            $warehouse = new Warehouse;
            $warehouse->store_id = $store_id;
            $warehouse->name = $input['name'];
            $warehouse->save();
            $warehouse->address()->create(['district_id'=>$input['district_id'], 'line'=>$input['line'], 'postcode'=>$input['postcode']]);
        }
        return $this->index($store_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($store_id, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $address = $warehouse->address;
        $provinces = Province::orderBy('province_id')->pluck('name', 'province_id')->all();
        $cities = City::where('province_id', $address->district->city->province->province_id)->orderBy('city_id')->pluck('name', 'city_id')->all();
        $districts = District::where('city_id', $address->district->city->city_id)->orderBy('district_id')->pluck('name', 'district_id')->all();
        return view('warehouse.edit', compact('warehouse', 'address', 'provinces', 'cities', 'districts', 'store_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $store_id, $id)
    {
        $input = $request->all();
        $store = Store::findOrFail($store_id);
        $warehouse = Warehouse::findOrFail($id);
        if($warehouse->name == $input['name'] || !$store->warehouses()->where('name', $input['name'])->first()){
            $warehouse->name = $input['name'];
            $warehouse->save();
            $warehouse->address()->update(['district_id'=>$input['district_id'], 'line'=>$input['line'], 'postcode'=>$input['postcode']]);
        }
        return $this->index($store_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($store_id, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->address->delete();
        $warehouse->delete();
        return $this->index($store_id);
    }

    public function productTypes($store_id, $id){
        $warehouse = Warehouse::findOrFail($id);
        $productTypes = ProductType::orderBy('created_at')->get();
        $warehouseProductTypes = array();
        foreach($productTypes as $productType){
            if($warehouse->products()->where('product_type_id', $productType->id)->first()){
                $warehouseProductTypes[$productType->id] = $productType->name;
            }
        }
        return view('warehouse.productTypes', compact('warehouseProductTypes'));
    }

    public function products($store_id, $id, $productType_id){
        $warehouse = Warehouse::findOrFail($id);
        $warehouseProducts = $warehouse->products()->where('product_type_id', $productType_id)->pluck('name', 'product_id')->all();
        return view('warehouse.products', compact('warehouseProducts'));
    }

    public function productBatches($store_id, $id, $productType_id, $product_id){
        $warehouseProductBatches = DB::table('product_warehouse')->where('warehouse_id', $id)->where('product_id', $product_id)->pluck('batch_number', 'batch_number')->all();
        return view('warehouse.productBatches', compact('warehouseProductBatches'));
    }

    public function productBatchQuantity($store_id, $id, $productType_id, $product_id, $batch_number){
        $warehouseProductBatch= DB::table('product_warehouse')->where('warehouse_id', $id)->where('product_id', $product_id)->where('batch_number', $batch_number)->first();
        $warehouseProductBatchQuantity = $warehouseProductBatch->quantity;
        $productPrice = Product::findOrFail($product_id)->price;
        return view('warehouse.productBatchQuantity', compact('productPrice', 'warehouseProductBatchQuantity'));
    }

    public function giftTypes($store_id, $id){
        $warehouse = Warehouse::findOrFail($id);
        $productTypes = ProductType::orderBy('created_at')->get();
        $warehouseProductTypes = array();
        foreach($productTypes as $productType){
            if($warehouse->gifts()->where('product_type_id', $productType->id)->first()){
                $warehouseProductTypes[$productType->id] = $productType->name;
            }
        }
        return view('warehouse.giftTypes', compact('warehouseProductTypes'));
    }

    public function gifts($store_id, $id, $productType_id){
        $warehouse = Warehouse::findOrFail($id);
        $warehouseProducts = $warehouse->gifts()->where('product_type_id', $productType_id)->pluck('name', 'product_id')->all();
        return view('warehouse.gifts', compact('warehouseProducts'));
    }

    public function giftBatches($store_id, $id, $productType_id, $product_id){
        $warehouseProductBatches = DB::table('gift_warehouse')->where('warehouse_id', $id)->where('product_id', $product_id)->pluck('batch_number', 'batch_number')->all();
        return view('warehouse.giftBatches', compact('warehouseProductBatches'));
    }

    public function giftBatchQuantity($store_id, $id, $productType_id, $product_id, $batch_number){
        $warehouseProductBatch= DB::table('gift_warehouse')->where('warehouse_id', $id)->where('product_id', $product_id)->where('batch_number', $batch_number)->first();
        $warehouseProductBatchQuantity = $warehouseProductBatch->quantity;
        return view('warehouse.giftBatchQuantity', compact('warehouseProductBatchQuantity'));
    }
}
