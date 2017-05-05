<div class="form-group">
    {!! Form::label('productType_id', '品牌') !!}
    {!! Form::select('productType_id', array(''=>'请选择品牌') + $warehouseProductTypes, null, array('id'=>'so-productType-select', 'class'=>'form-control')) !!}
</div>