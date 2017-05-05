<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">新订单</h3>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <div class="col-md-12 form-group">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="salesOrder-member">会员</label>
                        <div class="input-group">
                            <input type="text" name="salesOrder-member" placeholder="请输入姓名" id="salesOrder-member-search-input" class="form-control">
                            <span class="input-group-btn">
                                <button type="button" id="salesOrder-member-search-button" class="btn btn-default"><i class="fa fa-search" style='color:#00a65a'></i></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="salesOrder-members-container" class="col-md-12">
                    @if(isset($members) && count($members) > 0)
                        @include('member.getMembers', array('store_id'=>$store_id, 'members'=>$members, 'memberId'=>$memberId, 'memberPhone'=>$memberPhone, 'memberAddress'=>$memberAddress))
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-12">
            {!! Form::button('上一步', array('id'=>'salesOrder-addMember-form-back', 'class'=>'btn btn-default')) !!}
            {!! Form::submit('下一步', array('id'=>'salesOrder-addMember-form-submit', 'class'=>'btn btn-success pull-right')) !!}
        </div>
    </div>
    <!-- /.box-footer -->
</div>

<script>
    $(function(){
        var dateRangePicker = $('.daterangepicker');
        if(dateRangePicker.length){
            dateRangePicker.remove();
        }

        $(document).off('input', 'input');

        $(document).off('change', "select");

        $(document).on('change', "select", function(){
            var input = $(this).find("option:selected").val();
            if(input == ''){
                $(this).css('border-color', '#ed5736');
            }else{
                $(this).css('border-color', '#00a65a');
            }
        });

        $('#salesOrder-member-search-button').click(function(e){
            e.preventDefault();
            var member = $('#salesOrder-member-search-input').val().trim();
            if(member != ''){
                $.get('store/' + '{{$store_id}}' + '/salesOrder/getMembers/' + member, function(data){
                    $('#salesOrder-members-container').html(data);
                });
            }
        });

        $('#salesOrder-addMember-form-back').click(function(e){
            e.preventDefault();
            var token = '{{csrf_token()}}';
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/salesOrder/addGifts',
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

        $('#salesOrder-addMember-form-submit').click(function(e){
            e.preventDefault();
            var salesOrderMemberSelector = $("input[name='salesOrder-member-id']:checked");
            if(salesOrderMemberSelector.length &&
                salesOrderMemberSelector.closest('tr').find('td').eq(8).find('select').val() != '' &&
                salesOrderMemberSelector.closest('tr').find('td').eq(9).find('select').val() != ''){
                salesOrderMemberSelector.closest('tr').find('td').eq(8).find('select').css('border-color', '#00a65a');
                salesOrderMemberSelector.closest('tr').find('td').eq(9).find('select').css('border-color', '#00a65a');
                var token = '{{csrf_token()}}';
                $.ajax({
                    url: 'store/' + {{$store_id}} + '/salesOrder/addInfo',
                    type: 'POST',
                    data: {
                        _method     : 'POST',
                        _token      : token,
                        member      : {
                            id      : $("input[name='salesOrder-member-id']:checked").val(),
                            phone   : salesOrderMemberSelector.closest('tr').find('td').eq(8).find('select').find("option:selected").val(),
                            address : salesOrderMemberSelector.closest('tr').find('td').eq(9).find('select').find("option:selected").val()
                        }
                    },
                    success: function(data){
                        $('section.content').html(data);
                    }
                });
            }else{
                if(salesOrderMemberSelector.closest('tr').find('td').eq(8).find('select').val() == ''){
                    salesOrderMemberSelector.closest('tr').find('td').eq(8).find('select').css('border-color', '#ed5736');
                }else{
                    salesOrderMemberSelector.closest('tr').find('td').eq(8).find('select').css('border-color', '#00a65a');
                }
                if(salesOrderMemberSelector.closest('tr').find('td').eq(9).find('select').val() == ''){
                    salesOrderMemberSelector.closest('tr').find('td').eq(9).find('select').css('border-color', '#ed5736');
                }else{
                    salesOrderMemberSelector.closest('tr').find('td').eq(9).find('select').css('border-color', '#00a65a');
                }
            }
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