<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">现有订单</h3>
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
                                <button type="button" id="salesOrder-member-search-button" class="btn btn-default"><i class="fa fa-search" style='color:#00c0ef'></i></button>
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
            {!! Form::button('返回', array('id'=>'salesOrder-changeMember-form-back', 'class'=>'btn btn-default')) !!}
            {!! Form::submit('下一步', array('id'=>'salesOrder-changeMember-form-submit', 'class'=>'btn btn-info pull-right')) !!}
        </div>
    </div>
    <!-- /.box-footer -->
</div>

<script>
    $(function(){
        $('ul#salesOrder-members-pagination li a').click(function(e){
            e.preventDefault();
            $.get($(this).prop('href'), function(data){
                $('#salesOrder-members-container').html(data);
            });
        });

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
                $(this).css('border-color', '#00c0ef');
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

        $('#salesOrder-changeMember-form-back').click(function(e){
            e.preventDefault();
            $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/salesOrder', function(data){
                $('section.content').html(data);
            });
        });

        $('#salesOrder-changeMember-form-submit').click(function(e){
            e.preventDefault();
            var salesOrderMemberSelector = $("input[name='salesOrder-member-id']:checked");
            if(salesOrderMemberSelector.length &&
                salesOrderMemberSelector.closest('tr').find('td').eq(8).find('select').val() != '' &&
                salesOrderMemberSelector.closest('tr').find('td').eq(9).find('select').val() != ''){
                salesOrderMemberSelector.closest('tr').find('td').eq(8).find('select').css('border-color', '#00c0ef');
                salesOrderMemberSelector.closest('tr').find('td').eq(9).find('select').css('border-color', '#00c0ef');
                var token = '{{csrf_token()}}';
                $.ajax({
                    url: 'store/' + {{$store_id}} + '/salesOrder/' + {{$id}} + '/changeInfo',
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
                    salesOrderMemberSelector.closest('tr').find('td').eq(8).find('select').css('border-color', '#00c0ef');
                }
                if(salesOrderMemberSelector.closest('tr').find('td').eq(9).find('select').val() == ''){
                    salesOrderMemberSelector.closest('tr').find('td').eq(9).find('select').css('border-color', '#ed5736');
                }else{
                    salesOrderMemberSelector.closest('tr').find('td').eq(9).find('select').css('border-color', '#00c0ef');
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
        margin-bottom: 0;
    }
</style>