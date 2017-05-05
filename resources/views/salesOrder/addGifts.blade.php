<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">新订单</h3>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('salesOrder-gifts', '赠品') !!}&nbsp
                <a id='salesOrder-addGifts-button' href="#" data-toggle='modal' data-target='#salesOrder-addGifts-form-modal'>
                    <i class="fa fa-plus" style='color:#00a65a'></i>
                </a>
                @if(isset($gifts) && count($gifts) > 0)
                    <table id='salesOrder-gifts' class='table table-hover text-center' style='color: #4e4e4e;'>
                        <tbody>
                        <tr id='table-head'>
                            <th>仓库</th><th>品牌</th><th>规格</th><th>批号</th><th>数量</th>
                        </tr>
                        @foreach($gifts as $gift)
                            <tr>
                                <td><input type='hidden' name='gift-warehouseId' value='{{$gift['warehouseId']}}' class='so-gift-warehouseId-input'>{{$gift['warehouse']}}</td>
                                <td>{{$gift['giftType']}}</td>
                                <td><input type='hidden' name='gift-giftId' value='{{$gift['giftId']}}' class='so-gift-giftId-input'>{{$gift['gift']}}</td>
                                <td>{{$gift['batchNumber']}}</td>
                                <td>
                                    <input type='text' name='gift-quantity' value='{{$gift['quantity']}}' class='so-gift-quantity-input' maxlength='3' size='3' style='text-align: center; color:#00a65a;' data-max-quantity='{{$gift['maxQuantity']}}'>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-12">
            {!! Form::button('上一步', array('id'=>'salesOrder-addGifts-form-back', 'class'=>'btn btn-default')) !!}
            {!! Form::submit('下一步', array('id'=>'salesOrder-addGifts-form-submit', 'class'=>'btn btn-success pull-right')) !!}
        </div>
    </div>
    <!-- /.box-footer -->
</div>

<div class="modal fade" id="salesOrder-addGifts-form-modal" tabindex="-1" role="dialog" aria-labelledby="salesOrder-addGiftsFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="box box-success">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">赠品</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('warehouse_id', '仓库') !!}
                        {!! Form::select('warehouse_id', array(''=>'请选择仓库') + $warehouses, null, array('id'=>'so-gift-warehouse-select', 'class'=>'form-control')) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('关闭', array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')) !!}
                    {!! Form::button('添加', array('id'=>'salesOrder-addGifts-form-button', 'class'=>'btn btn-success', 'data-dismiss'=>'modal')) !!}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(function(){
        var giftTable = "<table id='salesOrder-gifts' class='table table-hover text-center' style='color: #4e4e4e;'><tbody><tr id='table-head'><th>仓库</th><th>品牌</th><th>规格</th><th>批号</th><th>数量</th></tr></tbody></table>";

        var dateRangePicker = $('.daterangepicker');
        if(dateRangePicker.length){
            dateRangePicker.remove();
        }

        $(document).off('input', 'input');

        $(document).on('input', "input", function(){
            var input = $(this).val();
            if(input.trim() == ''){
                $(this).parent().removeClass('has-success').addClass('has-error');
                $(this).next().remove();
                var inputName = $(this).prev().text();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-success');
                $(this).next().remove();
            }
        });

        $(document).off('change', "select");

        $(document).on('change', "select", function(){
            var input = $(this).find("option:selected").val();
            $(this).parent().find('span.help-block').remove();
            if(input == ''){
                $(this).parent().removeClass('has-success').addClass('has-error');
                var inputName = $(this).parent().find('label').text();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-success');
            }
        });

        var salesOrderAddGiftsFormModal = $('#salesOrder-addGifts-form-modal');

        $('#salesOrder-addGifts-button').click(function(e){
            e.preventDefault();
            salesOrderAddGiftsFormModal.find('.modal-body .form-group').removeClass('has-error').removeClass('has-success').slice(1).remove();
            $('#so-gift-warehouse-select').val('').next().remove();
            $('#salesOrder-addGifts-form-button').hide();
        });

        $('#so-gift-warehouse-select').change(function(){
            salesOrderAddGiftsFormModal.find('.modal-body .form-group').slice(1).remove();
            $('#salesOrder-addGifts-form-button').hide();
            var self = $(this);
            var warehouseId = self.find("option:selected").val();
            if(warehouseId != ''){
                $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/warehouse/' + warehouseId + '/giftTypes', function(data){
                    salesOrderAddGiftsFormModal.find('.modal-body').append(data);
                });
            }
        });

        $(document).off('change', '#so-giftType-select');

        $(document).on('change', '#so-giftType-select', function(){
            salesOrderAddGiftsFormModal.find('.modal-body .form-group').slice(2).remove();
            $('#salesOrder-addGifts-form-button').hide();
            var self = $(this);
            var warehouseId = $('#so-gift-warehouse-select').find("option:selected").val();
            var giftTypeId = self.find("option:selected").val();
            if(giftTypeId != ''){
                $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/warehouse/' + warehouseId + '/giftTypes/' + giftTypeId + '/gifts', function(data){
                    salesOrderAddGiftsFormModal.find('.modal-body').append(data);
                });
            }
        });

        $(document).off('change', '#so-gift-select');

        $(document).on('change', '#so-gift-select', function(){
            salesOrderAddGiftsFormModal.find('.modal-body .form-group').slice(3).remove();
            $('#salesOrder-addGifts-form-button').hide();
            var self = $(this);
            var warehouseId = $('#so-gift-warehouse-select').find("option:selected").val();
            var giftTypeId = $('#so-giftType-select').find("option:selected").val();
            var giftId = self.find("option:selected").val();
            if(giftId != ''){
                $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/warehouse/' + warehouseId + '/giftTypes/' + giftTypeId + '/gifts/' + giftId + '/giftBatches', function(data){
                    salesOrderAddGiftsFormModal.find('.modal-body').append(data);
                });
            }
        });

        $(document).off('change', '#so-gift-batch-number-select');

        $(document).on('change', '#so-gift-batch-number-select', function(){
            salesOrderAddGiftsFormModal.find('.modal-body .form-group').slice(4).remove();
            $('#salesOrder-addGifts-form-button').hide();
            var self = $(this);
            var warehouseId = $('#so-gift-warehouse-select').find("option:selected").val();
            var giftTypeId = $('#so-giftType-select').find("option:selected").val();
            var giftId = $('#so-gift-select').find("option:selected").val();
            var batchNumber = self.find("option:selected").val();
            if(batchNumber != ''){
                $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/warehouse/' + warehouseId + '/giftTypes/' + giftTypeId + '/gifts/' + giftId + '/giftBatches/' + batchNumber + '/quantity', function(data){
                    salesOrderAddGiftsFormModal.find('.modal-body').append(data);
                }).done(function(){
                    $('#salesOrder-addGifts-form-button').show();
                });
            }
        });

        $(document).off('input', '.so-gift-quantity-input');

        $(document).on('input', '.so-gift-quantity-input', function(){
            var input = $(this).val().trim();
            var max = $(this).data('max-quantity');
            if(input != ''){
                if(!$.isNumeric(input) || input <= 0 || input > max){
                    if(input <= 0){
                        $(this).closest('tr').remove();
                        if($('#salesOrder-gifts').find('tr').length == 1){
                            $('#salesOrder-gifts').remove();
                        }
                    }
                    $(this).css( "color", "#dd4b39" );
                }else{
                    $(this).css( "color", "#00a65a" );
                }
            }else{
                $(this).css( "color", "" );
            }
        });

        $('#salesOrder-addGifts-form-button').click(function(e){
            e.preventDefault();
            if(!$('#so-gift-quantity-input').parent().hasClass('has-success')){
                return;
            }
            if(!$('#salesOrder-addGifts-button').next().length){
                $('#salesOrder-addGifts-button').parent().append(giftTable);
            }
            var salesOrderGifts = $('#salesOrder-gifts');
            var warehouseId     = $('#so-gift-warehouse-select').find("option:selected").val();
            var warehouse       = $('#so-gift-warehouse-select').find("option:selected").text();
            var giftType        = $('#so-giftType-select').find("option:selected").text();
            var giftId          = $('#so-gift-select').find("option:selected").val();
            var gift            = $('#so-gift-select').find("option:selected").text();
            var batchNumber     = $('#so-gift-batch-number-select').find("option:selected").text();
            var quantity        = $('#so-gift-quantity-input').val();
            var maxQuantity     = $('#so-gift-quantity-input').data('max-quantity');
            var hasDuplicate    = false;
            salesOrderGifts.find('tbody tr').each(function(){
                var tds = $(this).find('td');
                if(tds.length){
                    if(warehouse == tds.eq(0).text() && giftType == tds.eq(1).text() && gift == tds.eq(2).text() && batchNumber == tds.eq(3).text()){
                        hasDuplicate = true;
                        return false;
                    }
                }
            });
            if(!hasDuplicate){
                var row =
                    "<tr>" +
                    "<td>" + "<input type='hidden' name='gift-warehouseId' value='" + warehouseId + "' class='so-gift-warehouseId-input'>" + warehouse + "</td>" +
                    "<td>" + giftType + "</td>" +
                    "<td>" + "<input type='hidden' name='gift-giftId' value='" + giftId + "' class='so-gift-giftId-input'>" + gift + "</td>" +
                    "<td>" + batchNumber + "</td>" +
                    "<td>" + "<input type='text' name='gift-quantity' value='" + quantity + "' class='so-gift-quantity-input' maxlength='3' size='3' style='text-align: center; color:#00a65a;' data-max-quantity='" + maxQuantity + "'>" + "</td>" +
                    "</tr>";
                salesOrderGifts.find('tbody').append(row);
            }
        });

        $('#salesOrder-addGifts-form-back').click(function(e){
            e.preventDefault();
            var token = '{{csrf_token()}}';
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/salesOrder/addProducts',
                type: 'POST',
                data: {
                    _method: 'POST',
                    _token : token,
                    isNew  : 0
                },
                success: function(data){
                    $('section.content').html(data);
                }
            });
        });

        $('#salesOrder-addGifts-form-submit').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            $(".so-gift-quantity-input").each(function(){
                if($(this).css('color') != 'rgb(0, 166, 90)'){
                    isFormValid = false;
                    return false;
                }
            });
            if(!isFormValid){
                return;
            }
            var gifts = [];
            var trs = $('#salesOrder-gifts').find('tr');
            trs.each(function(){
                if($(this).find('td').length){
                    var gift = {
                        warehouseId : $(this).find('.so-gift-warehouseId-input').val(),
                        warehouse   : $(this).find('td').eq(0).text(),
                        giftType    : $(this).find('td').eq(1).text(),
                        giftId      : $(this).find('.so-gift-giftId-input').val(),
                        gift        : $(this).find('td').eq(2).text(),
                        batchNumber : $(this).find('td').eq(3).text(),
                        quantity    : $(this).find('td').eq(4).find('input').val(),
                        maxQuantity : $(this).find('td').eq(4).find('input').data('max-quantity')
                    };
                    gifts.push(gift);
                }
            });
            var token = '{{csrf_token()}}';
            $.ajax({
                url: 'store/' + {{$store_id}} + '/salesOrder/addMember',
                type: 'POST',
                data: {
                    _method: 'POST',
                    _token : token,
                    'gifts': gifts,
                },
                success: function(data){
                    $('section.content').html(data);
                }
            });
        });
    });
</script>

<style>
    .table th {
        border-top: none !important;
    }
    .table {
        margin-bottom: 0px;
    }
</style>