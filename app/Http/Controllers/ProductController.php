<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\AttributeValue;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($productType_id)
    {
        $products = Product::where('product_type_id', $productType_id)->orderBy('created_at', 'DESC')->get();
        return view('product.showAll', compact('products'));
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
    public function store(Request $request, $productType_id)
    {
        $input = $request->all();
        if(!Product::where('product_type_id', $productType_id)->where('name', $input['product-name'])->first()){
            $product = new Product;
            $product->name = $input['product-name'];
            $product->price = $input['product-price'];
            $product->product_type_id = $productType_id;
            $product->save();
            foreach($input['product-attributeValues'] as $name => $value){
                $attribute = Attribute::where('name', $name)->first();
                $attributeValue = new AttributeValue;
                $attributeValue->attribute_id = $attribute->id;
                $hasFound = false;
                foreach($attribute->attributeValues as $attrVal){
                    if($attrVal->value == $value){
                        $product->attributeValues()->save($attrVal);
                        $hasFound = true;
                        break;
                    }
                }
                if(!$hasFound){
                    $attributeValue->value = $value;
                    $product->attributeValues()->save($attributeValue);
                }
            }
        }
        return $this->index($productType_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($productType_id, $id)
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
    public function update(Request $request, $productType_id, $id)
    {
        $input = $request->all();
        $product = Product::findOrFail($id);
        if($product->name == $input['product-name'] || !Product::where('product_type_id', $productType_id)->where('name', $input['product-name'])->first()){
            $product->name = $input['product-name'];
            $product->price = $input['product-price'];
            $product->save();
            $productAttributeValues = $product->attributeValues;
            $product->attributeValues()->detach();
            foreach($input['product-attributeValues'] as $name => $value){
                $attribute = Attribute::where('name', $name)->first();
                $attributeValue = new AttributeValue;
                $attributeValue->attribute_id = $attribute->id;
                $hasFound = false;
                foreach($attribute->attributeValues as $attrVal){
                    if($attrVal->value == $value){
                        $product->attributeValues()->save($attrVal);
                        $hasFound = true;
                        break;
                    }
                }
                if(!$hasFound){
                    $attributeValue->value = $value;
                    $product->attributeValues()->save($attributeValue);
                }
            }
            foreach($productAttributeValues as $productAttributeValue){
                if(count($productAttributeValue->products) == 0){
                    $productAttributeValue->delete();
                }
            }
        }
        return $this->index($productType_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productType_id, $id)
    {
        $product = Product::findOrFail($id);
        $productAttributeValues = $product->attributeValues;
        $product->delete();
        foreach($productAttributeValues as $productAttributeValue){
            if(count($productAttributeValue->products) == 0){
                $productAttributeValue->delete();
            }
        }
        return $this->index($productType_id);
    }
}
