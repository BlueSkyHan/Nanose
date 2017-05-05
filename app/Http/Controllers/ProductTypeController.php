<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\Product;
use App\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productTypes = ProductType::orderBy('created_at', 'DESC')->get();
        return view('productType.showAll', compact('productTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('productType.create');
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
        if(!ProductType::where('name', $input['name'])->first()){
            $productType = new ProductType;
            $productType->name = $input['name'];
            $productType->save();
            foreach($input['attributes'] as $attributeName){
                if($attribute = Attribute::where('name', $attributeName)->first()){
                }else{
                    $attribute = new Attribute;
                    $attribute->name = $attributeName;
                }
                $productType->attributes()->save($attribute);
            }
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
        $productType = ProductType::findOrFail($id);
        return view('productType.edit', compact('productType'));
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
        $productType = ProductType::findOrFail($id);
        if($productType->name == $input['name'] || !ProductType::where('name', $input['name'])->first()){
            $productType->name = $input['name'];
            $productType->save();
            $productTypeAttributes = $productType->attributes;
            $productType->attributes()->detach();
            foreach($input['attributes'] as $attributeName){
                if($attribute = Attribute::where('name', $attributeName)->first()){
                }else{
                    $attribute = new Attribute;
                    $attribute->name = $attributeName;
                }
                $productType->attributes()->save($attribute);
            }
            foreach($productTypeAttributes as $productTypeAttribute){
                if(count($productTypeAttribute->productTypes) == 0){
                    $productTypeAttribute->delete();
                }
            }
            foreach($productType->products as $product){
                $productAttributeValues = $product->attributeValues;
                $product->attributeValues()->detach();
                foreach($productAttributeValues as $productAttributeValue){
                    if(count($productAttributeValue->products) == 0){
                        $productAttributeValue->delete();
                    }
                }
            }
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
        $productType = ProductType::findOrFail($id);
        $productTypeAttributes = $productType->attributes;
        $productType->delete();
        foreach($productTypeAttributes as $productTypeAttribute){
            if(count($productTypeAttribute->productTypes) == 0){
                $productTypeAttribute->delete();
            }
        }
        return $this->index();
    }

    public function products($productType_id){
        $products = Product::where('product_type_id', $productType_id)->pluck('name', 'id')->all();
        return view('productType.products', compact('products'));
    }
}
