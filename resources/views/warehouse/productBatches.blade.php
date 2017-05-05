<div class="form-group">
    {!! Form::label('batch_number', '批号') !!}
    {!! Form::select('batch_number', array(''=>'请选择批号') + $warehouseProductBatches, null, array('id'=>'so-batch-number-select', 'class'=>'form-control')) !!}
</div>