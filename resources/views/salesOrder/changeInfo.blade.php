<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">现有订单</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {!! Form::model($salesOrder, ['action'=>['SalesOrderController@update', $store_id, $salesOrder->id], 'method'=>'POST', 'id'=>'salesOrder-edit-form', 'role'=>'form']) !!}
    <div class="box-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('receiver', '收货人') !!}
                        {!! Form::text('receiver', null, array('placeholder'=>'请输入收货人', 'id'=>'salesOrder-receiver-edit-input', 'class'=>'form-control non-null')) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('transaction_date', '交易日期') !!}
                        {!! Form::date('transaction_date', null, array('id'=>'salesOrder-transaction-date-edit-input', 'class'=>'form-control non-null')) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('sales_channel_id', '销售渠道') !!}
                        {!! Form::select('sales_channel_id', array(''=>'请选择销售渠道') + $salesChannels, null, array('id'=>'saleChannels', 'class'=>'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('payment_method_id', '支付方式') !!}
                        {!! Form::select('payment_method_id', array(''=>'请选择支付方式') + $paymentMethods, null, array('id'=>'paymentMethods', 'class'=>'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('delivery_method_id', '发货方式') !!}
                        {!! Form::select('delivery_method_id', array(''=>'请选择发货方式') + $deliveryMethods, null, array('id'=>'deliveryMethods', 'class'=>'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('actual_total', '实价') !!}
                        {!! Form::text('actual_total', null, array('placeholder'=>'请输入实价', 'id'=>'salesOrder-actual-total-edit-input', 'class'=>'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('note', '备注') !!}
                        {!! Form::textarea('note', null, array('placeholder'=>'请更新备注...', 'id'=>'salesOrder-note-edit-input', 'class'=>'form-control', 'rows'=>'2')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-12">
            {!! Form::button('上一步', array('id'=>'salesOrder-edit-form-back', 'class'=>'btn btn-default')) !!}
            {!! Form::submit('更新', array('id'=>'salesOrder-edit-form-submit', 'class'=>'btn btn-info pull-right')) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

<script>
    $(function(){
        var dateRangePicker = $('.daterangepicker');
        if(dateRangePicker.length){
            dateRangePicker.remove();
        }

        $(document).off('input', 'input');

        $('input.non-null').on('input', function(){
            var input = $(this).val();
            if(input.trim() == ''){
                $(this).parent().removeClass('has-info').addClass('has-error');
                $(this).next().remove();
                var inputName = $(this).prev().text();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-info');
                $(this).next().remove();
            }
        });

        $(document).off('change', "select");

        $("select").change(function(){
            var input = $(this).find("option:selected").val();
            $(this).parent().find('span.help-block').remove();
            if(input == ''){
                $(this).parent().removeClass('has-info').addClass('has-error');
                var inputName = $(this).parent().find('label').text();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-info');
            }
        });

        $('#salesOrder-edit-form-back').click(function(e){
            e.preventDefault();
            var token = '{{csrf_token()}}';
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/salesOrder/' + {{$salesOrder->id}} + '/changeMember',
                type: 'POST',
                data: {
                    _method: 'POST',
                    _token : token
                },
                success: function(data){
                    $('section.content').html(data);
                }
            });
        });

        $('#salesOrder-edit-form-submit').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            $("input.non-null").each(function(){
                var input = $(this).val();
                if(input.trim() == ''){
                    $(this).parent().removeClass('has-info').addClass('has-error');
                    $(this).next().remove();
                    var inputName = $(this).prev().text();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
                    isFormValid = false;
                }else{
                    $(this).parent().removeClass('has-error').addClass('has-info');
                    $(this).next().remove();
                }
            });
            $("select").each(function(){
                var input = $(this).find("option:selected").val();
                $(this).parent().find('span.help-block').remove();
                if(input == ''){
                    $(this).parent().removeClass('has-info').addClass('has-error');
                    var inputName = $(this).prev().text();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
                    isFormValid = false;
                }else{
                    $(this).parent().removeClass('has-error').addClass('has-info');
                }
            });
            if(!isFormValid){
                return;
            }
            var infoArray = $('#salesOrder-edit-form').serializeArray();
            var info = {};
            $.each(infoArray, function(){
                info[this.name] = this.value;
            });
            delete info['_token'];
            var token = '{{csrf_token()}}';
            $.ajax({
                url: 'store/' + {{$store_id}} + '/salesOrder/' + {{$salesOrder->id}} + '',
                type: 'POST',
                data: {
                    _method: 'PATCH',
                    _token : token,
                    'info' : info,
                },
                success: function(data){
                    $('section.content').html(data);
                }
            });
        });
    });
</script>

<style>
    .form-group.has-info label {
        color: #00c0ef;
    }
    .form-group.has-info .help-block {
        color: #00c0ef;
    }
    .form-group.has-info .form-control {
        border-color: #00c0ef;
        box-shadow: none;
    }
</style>