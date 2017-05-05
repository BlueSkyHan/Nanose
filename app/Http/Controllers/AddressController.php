<?php

namespace App\Http\Controllers;

use App\City;
use App\District;
use App\Province;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function provinces(){
        $provinces = Province::orderBy('province_id')->pluck('name', 'province_id')->all();
        return view('address.provinces', compact('provinces'));
    }

    public function cities($province_id){
        $cities = City::where('province_id', $province_id)->orderBy('city_id')->pluck('name', 'city_id')->all();
        return view('address.cities', compact('cities'));
    }

    public function districts($city_id){
        $districts = District::where('city_id', $city_id)->orderBy('district_id')->pluck('name', 'district_id')->all();
        return view('address.districts', compact('districts'));
    }
}
