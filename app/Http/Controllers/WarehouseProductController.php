<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductType;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($store_id, $warehouse_id)
    {
        $warehouse = Warehouse::findOrFail($warehouse_id);
        $productTypes = ProductType::orderBy('created_at')->pluck('name', 'id')->all();
        $warehouseProductTypes = array();
        foreach(ProductType::all() as $productType){
            if($warehouse->products()->where('product_type_id', $productType->id)->first()){
                $warehouseProductTypes[] = $productType;
            }
        }
        return view('warehouse.product.showAll', compact('warehouse', 'warehouseProductTypes', 'productTypes', 'store_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $store_id, $warehouse_id)
    {
        $input = $request->all();
        $warehouse = Warehouse::findOrFail($warehouse_id);
        if(!$warehouse->products()->where('product_id', $input['product_id'])->wherePivot('batch_number', $input['batch_number'])->first()){
            $warehouse->products()->attach($input['product_id'], array('quantity'=>$input['quantity'], 'batch_number'=>$input['batch_number'], 'production_date'=>$input['production_date']));
        }
        return $this->show($store_id, $warehouse_id, $input['product_id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($store_id, $warehouse_id, $id)
    {
        $warehouse = Warehouse::findOrFail($warehouse_id);
        $productTypes = ProductType::orderBy('created_at')->pluck('name', 'id')->all();
        $warehouseProductTypes = array();
        foreach(ProductType::all() as $productType){
            if($warehouse->products()->where('product_type_id', $productType->id)->first()){
                $warehouseProductTypes[] = $productType;
            }
        }
        return view('warehouse.product.showAll', compact('warehouse', 'warehouseProductTypes', 'productTypes', 'id', 'store_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $store_id, $warehouse_id, $id)
    {
        $input = $request->all();
        $warehouseProduct = DB::table('product_warehouse')->where('warehouse_id', $warehouse_id)->where('product_id', $id)->where('batch_number', $input['batch_number']);
        if($warehouseProduct->first()){
            if($input['quantity'] == 0){
                $warehouseProduct->delete();
            }else{
                $warehouseProduct->update(array('quantity'=>$input['quantity']));
            }
        }
        return $this->show($store_id, $warehouse_id, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($store_id, $warehouse_id, $id)
    {
        $warehouse = Warehouse::findOrFail($warehouse_id);
        $warehouse->products()->detach($id);
        return $this->show($store_id, $warehouse_id, $id);
    }

    public function destroyByProductType($store_id, $warehouse_id, $productType_id)
    {
        $warehouse = Warehouse::findOrFail($warehouse_id);
        $products = $warehouse->products()->where('product_type_id', $productType_id)->get();
        $productIds = array();
        foreach($products as $product){
            if(!in_array($product->id, $productIds)){
                $productIds[] = $product->id;
            }
        }
        $warehouse->products()->detach($productIds);
        return $this->index($store_id, $warehouse_id);
    }
}
