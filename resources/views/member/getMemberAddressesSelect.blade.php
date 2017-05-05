@if(isset($member))
    {!! Form::select('salesOrder_address_id', array(''=>'请选择收货地址') + $member->addresses()->pluck('line', 'id')->all(), isset($memberAddress) ? $memberAddress : null, array('class'=>'salesOrder-address-select form-control input-sm')) !!}
@endif

<style>
    select {
        text-align-last: center;
    }
</style>