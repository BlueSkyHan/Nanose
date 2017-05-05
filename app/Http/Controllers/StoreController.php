<?php

namespace App\Http\Controllers;

use App\Address;
use App\City;
use App\District;
use App\Phone;
use App\Province;
use App\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Store::orderBy('created_at', 'DESC')->get();
        return view('store.showAll', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = Province::orderBy('province_id')->pluck('name', 'province_id')->all();
        return view('store.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        if(!Store::where('name', $input['name'])->first()){
            $store = new Store;
            $store->name = $input['name'];
            $store->save();
            $store->phone()->create(['number' => $input['phone']]);
            $store->address()->create(['district_id' => $input['district_id'], 'line' => $input['line'], 'postcode' => $input['postcode']]);
        }
        return $this->index();
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
    public function edit($id)
    {
        $store = Store::findOrFail($id);
        $phone = $store->phone;
        $address = $store->address;
        $provinces = Province::orderBy('province_id')->pluck('name', 'province_id')->all();
        $cities = City::where('province_id', $address->district->city->province->province_id)->orderBy('city_id')->pluck('name', 'city_id')->all();
        $districts = District::where('city_id', $address->district->city->city_id)->orderBy('district_id')->pluck('name', 'district_id')->all();
        return view('store.edit', compact('store', 'phone', 'address', 'provinces', 'cities', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $store = Store::findOrFail($id);
        if($store->name == $input['name'] || !Store::where('name', $input['name'])->first()){
            $store->name = $input['name'];
            $store->save();
            $store->phone()->update(['number' => $input['phone']]);
            $store->address()->update(['district_id' => $input['district_id'], 'line' => $input['line'], 'postcode' => $input['postcode']]);
        }
        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->phone->delete();
        $store->address->delete();
        foreach($store->warehouses as $warehouse){
            $warehouse->address->delete();
        }
        $store->delete();
        return $this->index();
    }
}
