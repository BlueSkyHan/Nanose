<div class="form-group">
    {!! Form::label('product_id', '规格') !!}
    {!! Form::select('product_id', array(''=>'请选择规格') + $warehouseProducts, null, array('id'=>'so-product-select', 'class'=>'form-control')) !!}
</div>