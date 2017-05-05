<div class="form-group">
    {!! Form::label('products', '规格') !!}
    {!! Form::select('products', array(''=>'请选择规格') + $products, null, array('id'=>'product-create-select', 'class'=>'form-control')) !!}
</div>