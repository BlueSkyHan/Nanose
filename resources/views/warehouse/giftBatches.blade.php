<div class="form-group">
    {!! Form::label('gift_batch_number', '批号') !!}
    {!! Form::select('gift_batch_number', array(''=>'请选择批号') + $warehouseProductBatches, null, array('id'=>'so-gift-batch-number-select', 'class'=>'form-control')) !!}
</div>