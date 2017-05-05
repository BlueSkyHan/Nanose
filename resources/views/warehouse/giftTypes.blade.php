<div class="form-group">
    {!! Form::label('giftType_id', '品牌') !!}
    {!! Form::select('giftType_id', array(''=>'请选择品牌') + $warehouseProductTypes, null, array('id'=>'so-giftType-select', 'class'=>'form-control')) !!}
</div>