<div class="form-group">
    {!! Form::label('gift_id', '规格') !!}
    {!! Form::select('gift_id', array(''=>'请选择规格') + $warehouseProducts, null, array('id'=>'so-gift-select', 'class'=>'form-control')) !!}
</div>