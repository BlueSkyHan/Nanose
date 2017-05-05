@if(isset($member))
    {!! Form::select('salesOrder_phone_id', array(''=>'请选择联系电话') + $member->phones()->pluck('number', 'id')->all(), isset($memberPhone) ? $memberPhone : null, array('class'=>'salesOrder-phone-select form-control input-sm')) !!}
@endif

<style>
    select {
        text-align-last: center;
    }
</style>