@if($hasSalesOrder)
    <table id="salesOrders" class="table table-bordered table-hover" style="width: 100%; color: #4e4e4e;">
        <thead>
        <tr>
            <th>会员</th>
            <th>收货人</th>
            <th>联系电话</th>
            <th>收货地址</th>
            <th>正品</th>
            <th>赠品</th>
            <th>总价</th>
            <th>实价</th>
            <th>交易日期</th>
            <th>销售渠道</th>
            <th>支付方式</th>
            <th>发货方式</th>
            <th>备注</th>
            @if(Auth::check())
                @if($store_id == 0)
                    <th>门店</th>
                @endif
            @endif
            @if(Auth::check())
                @if($store_id > 0 && (Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理'))
                    <th>员工</th>
                    <th>状态</th>
                @endif
            @endif
            @if(Auth::check())
                @if($store_id > 0 && !Auth::user()->isAdmin())
                    <th>更新</th>
                    <th>退单</th>
                @endif
            @endif
            @if(Auth::check())
                @if($store_id > 0 && Auth::user()->isAdmin())
                    <th>删除</th>
                @endif
            @endif
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            @if(Auth::check())
                @if($store_id == 0)
                    <th></th>
                @endif
            @endif
            @if(Auth::check())
                @if($store_id > 0 && (Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理'))
                    <th></th>
                    <th></th>
                @endif
            @endif
            @if(Auth::check())
                @if($store_id > 0 && !Auth::user()->isAdmin())
                    <th></th>
                    <th></th>
                @endif
            @endif
            @if(Auth::check())
                @if($store_id > 0 && Auth::user()->isAdmin())
                    <th></th>
                @endif
            @endif
        </tr>
        </tfoot>
    </table>
@else
    <p class="text-muted" style="padding-right: 15px; padding-left: 15px;">暂无</p>
@endif

@if(Auth::check())
    @if($store_id == 0)
        <script>
            $(function(){
                var dateRangePicker = $('.daterangepicker');
                if(dateRangePicker.length){
                    dateRangePicker.remove();
                }

                $(document).off('input', 'input');

                $(document).off('change', 'select');

                $(document).off('click', '.salesOrder-product-top');

                $(document).on('click', '.salesOrder-product-top', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-product');

                $(document).on('click', '.salesOrder-product', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-gift-top');

                $(document).on('click', '.salesOrder-gift-top', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-gift');

                $(document).on('click', '.salesOrder-gift', function(e){
                    e.preventDefault();
                });

                var salesOrdersTable = $('#salesOrders').DataTable({
                    serverSide: true,
                    ajax: {
                        url: 'store/{{$store_id}}/salesOrder/getSalesOrders',
                        data: function(d){
                            var dateRangeSelector   = $('input[name=date_range]');
                            if(dateRangeSelector.length && dateRangeSelector.val() != ''){
                                var dateRange       = dateRangeSelector.val().split(' - ');
                                d.startDate         = dateRange[0].trim();
                                d.endDate           = dateRange[1].trim();
                            }

                            var totalSelector = $('#total');
                            if(totalSelector.length){
                                var total    = totalSelector.val();
                                if(!isNaN(total)){
                                    d.total  = total;
                                }else if(!isNaN(total.substr(0, total.length-1))){
                                    if(total.substr(total.length-1) == '+'){
                                        d.total   = total.substr(0, total.length-1);
                                        d.totalComparator = '>=';
                                    }else if(total.substr(total.length-1) == '-'){
                                        d.total   = total.substr(0, total.length-1);
                                        d.totalComparator = '<';
                                    }
                                }
                            }

                            var actualTotalSelector = $('#actual-total');
                            if(actualTotalSelector.length){
                                var actualTotal    = actualTotalSelector.val();
                                if(!isNaN(actualTotal)){
                                    d.actualTotal  = actualTotal;
                                }else if(!isNaN(actualTotal.substr(0, actualTotal.length-1))){
                                    if(actualTotal.substr(actualTotal.length-1) == '+'){
                                        d.actualTotal   = actualTotal.substr(0, actualTotal.length-1);
                                        d.actualTotalComparator = '>=';
                                    }else if(actualTotal.substr(actualTotal.length-1) == '-'){
                                        d.actualTotal   = actualTotal.substr(0, actualTotal.length-1);
                                        d.actualTotalComparator = '<';
                                    }
                                }
                            }

                            var statusSelector = $('#status');
                            if(statusSelector.length){
                                var status = statusSelector.find('option:selected').val();
                                if(status != ''){
                                    d.status= status;
                                }
                            }
                        }
                    },
                    dom: "<'row'<'col-md-6'<'pull-left'f>><'col-md-6'<'pull-right'l>>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-12'p>>" +
                    "<'row'<'col-md-6'<'pull-left'i>><'col-md-6'<'pull-right'B>>>",
                    buttons: [
                        'csv', 'excel', 'pdf'
                    ],
                    columns: [
                        {data: 'member.name',           name: 'member.name'},
                        {data: 'receiver',              name: 'receiver'},
                        {data: 'phone.number',          name: 'phone.number', orderable: false},
                        {data: 'address',               name: 'address.line', orderable: false},
                        {data: 'salesOrderProducts',    name: 'salesOrderProducts.product', orderable: false},
                        {data: 'salesOrderGifts',       name: 'salesOrderGifts.gift', orderable: false},
                        {data: 'total',                 name: 'total'},
                        {data: 'actual_total',          name: 'actual_total'},
                        {data: 'transaction_date',      name: 'transaction_date'},
                        {data: 'sales_channel.name',    name: 'salesChannel.name'},
                        {data: 'payment_method.name',   name: 'paymentMethod.name'},
                        {data: 'delivery_method.name',  name: 'deliveryMethod.name'},
                        {data: 'note',                  name: 'note', orderable: false},
                        {data: 'employee.store.name',   name: 'employee.store.name'}
                    ],
                    order: [[8,'desc']],
                    initComplete: function(){
                        this.api().columns([0,1,2,3,8,12]).every(function(){
                            var column = this;
                            var input = '<input type="search" class="form-control input-sm" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    column.search($(this).val(), false, false, true).draw();
                                });
                        });
                        this.api().columns([6]).every(function(){
                            var column = this;
                            var input = '<input type="text" id="total" class="form-control input-sm" maxlength="6" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    salesOrdersTable.draw();
                                });
                        });
                        this.api().columns([7]).every(function(){
                            var column = this;
                            var input = '<input type="text" id="actual-total" class="form-control input-sm" maxlength="6" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    salesOrdersTable.draw();
                                });
                        });
                        this.api().columns([9,10,11,13]).every(function(){
                            var column = this;
                            var index = column[0][0];
                            var action;
                            if(index == 9){
                                action = 'getSalesChannels';
                            }else if(index == 10){
                                action = 'getPaymentMethods';
                            }else if(index == 11){
                                action = 'getDeliveryMethods';
                            }else if(index == 13){
                                action = 'getStores';
                            }
                            $.get('store/' + {{$store_id}} + '/salesOrder/' + action, function(data){
                                var select = data;
                                $(select).appendTo($(column.footer()).empty())
                                    .on('change', function(){
                                        var val = $(this).find('option:selected').val();
                                        column.search(val, false, false, true).draw();
                                    });
                            });
                        });
                    }
                });

                var dateRangeFilter =
                    '<div id="salesOrders_data_range_filter" style="color: #444444;"><label>' +
                    '<i class="fa fa-calendar fa-lg"></i>' +
                    '<input type="text" name="date_range" class="form-control input-sm" style="margin-left: 6px;">' +
                    '</label></div>';

                $('#salesOrders_filter').parent().parent().append(dateRangeFilter);

                var dateRangeSelector = $('input[name="date_range"]');

                var start = moment().subtract('1', 'months');
                var end = moment();

                dateRangeSelector.daterangepicker({
                    startDate: start,
                    endDate: end,
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY/MM/DD',
                        applyLabel: '确定',
                        cancelLabel: '清空',
                    }
                });

                dateRangeSelector.on('apply.daterangepicker', function(ev, picker){
                    $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
                    salesOrdersTable.draw();
                });

                dateRangeSelector.on('cancel.daterangepicker', function(ev, picker){
                    $(this).val('');
                    salesOrdersTable.draw();
                });

                dateRangeSelector.on('change', function(){
                    if($(this).val().trim() == ''){
                        salesOrdersTable.draw();
                    }
                });
            });
        </script>
    @elseif(Auth::user()->isAdmin())
        <script>
            $(function(){
                var dateRangePicker = $('.daterangepicker');
                if(dateRangePicker.length){
                    dateRangePicker.remove();
                }

                $(document).off('input', 'input');

                $(document).off('change', 'select');

                $(document).off('click', ".salesOrder-delete-button");

                $(document).on('click', ".salesOrder-delete-button", function(e){
                    e.preventDefault();
                    var result = confirm("确认删除?");
                    if(result){
                        var salesOrderId = $(this).prop('id').replace('delete-salesOrder-id-', '');
                        var token = '{{csrf_token()}}';
                        $.ajax({
                            url: 'store/' + {{$store_id}} + '/salesOrder/' + salesOrderId,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token : token,
                            },
                            success: function(data){
                                $('section.content').html(data);
                            }
                        });
                    }
                });

                $(document).off('click', '.salesOrder-product-top');

                $(document).on('click', '.salesOrder-product-top', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-product');

                $(document).on('click', '.salesOrder-product', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-gift-top');

                $(document).on('click', '.salesOrder-gift-top', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-gift');

                $(document).on('click', '.salesOrder-gift', function(e){
                    e.preventDefault();
                });

                var salesOrdersTable = $('#salesOrders').DataTable({
                    serverSide: true,
                    ajax: {
                        url: 'store/{{$store_id}}/salesOrder/getSalesOrders',
                        data: function(d){
                            var dateRangeSelector   = $('input[name=date_range]');
                            if(dateRangeSelector.length && dateRangeSelector.val() != ''){
                                var dateRange       = dateRangeSelector.val().split(' - ');
                                d.startDate         = dateRange[0].trim();
                                d.endDate           = dateRange[1].trim();
                            }

                            var totalSelector = $('#total');
                            if(totalSelector.length){
                                var total    = totalSelector.val();
                                if(!isNaN(total)){
                                    d.total  = total;
                                }else if(!isNaN(total.substr(0, total.length-1))){
                                    if(total.substr(total.length-1) == '+'){
                                        d.total   = total.substr(0, total.length-1);
                                        d.totalComparator = '>=';
                                    }else if(total.substr(total.length-1) == '-'){
                                        d.total   = total.substr(0, total.length-1);
                                        d.totalComparator = '<';
                                    }
                                }
                            }

                            var actualTotalSelector = $('#actual-total');
                            if(actualTotalSelector.length){
                                var actualTotal    = actualTotalSelector.val();
                                if(!isNaN(actualTotal)){
                                    d.actualTotal  = actualTotal;
                                }else if(!isNaN(actualTotal.substr(0, actualTotal.length-1))){
                                    if(actualTotal.substr(actualTotal.length-1) == '+'){
                                        d.actualTotal   = actualTotal.substr(0, actualTotal.length-1);
                                        d.actualTotalComparator = '>=';
                                    }else if(actualTotal.substr(actualTotal.length-1) == '-'){
                                        d.actualTotal   = actualTotal.substr(0, actualTotal.length-1);
                                        d.actualTotalComparator = '<';
                                    }
                                }
                            }

                            var statusSelector = $('#status');
                            if(statusSelector.length){
                                var status = statusSelector.find('option:selected').val();
                                if(status != ''){
                                    d.status= status;
                                }
                            }
                        }
                    },
                    dom: "<'row'<'col-md-6'<'pull-left'f>><'col-md-6'<'pull-right'l>>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-12'p>>" +
                    "<'row'<'col-md-6'<'pull-left'i>><'col-md-6'<'pull-right'B>>>",
                    buttons: [
                        'csv', 'excel', 'pdf'
                    ],
                    columns: [
                        {data: 'member.name',           name: 'member.name'},
                        {data: 'receiver',              name: 'receiver'},
                        {data: 'phone.number',          name: 'phone.number', orderable: false},
                        {data: 'address',               name: 'address.line', orderable: false},
                        {data: 'salesOrderProducts',    name: 'salesOrderProducts.product', orderable: false},
                        {data: 'salesOrderGifts',       name: 'salesOrderGifts.gift', orderable: false},
                        {data: 'total',                 name: 'total'},
                        {data: 'actual_total',          name: 'actual_total'},
                        {data: 'transaction_date',      name: 'transaction_date'},
                        {data: 'sales_channel.name',    name: 'salesChannel.name'},
                        {data: 'payment_method.name',   name: 'paymentMethod.name'},
                        {data: 'delivery_method.name',  name: 'deliveryMethod.name'},
                        {data: 'note',                  name: 'note', orderable: false},
                        {data: 'employee.name',         name: 'employee.name'},
                        {data: 'status',                name: 'created_at', searchable: false, orderable: false},
                        {data: 'delete',                name: 'created_at', searchable: false, orderable: false}
                    ],
                    order: [[8,'desc']],
                    initComplete: function(){
                        this.api().columns([0,1,2,3,8,12]).every(function(){
                            var column = this;
                            var input = '<input type="search" class="form-control input-sm" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    column.search($(this).val(), false, false, true).draw();
                                });
                        });
                        this.api().columns([6]).every(function(){
                            var column = this;
                            var input = '<input type="text" id="total" class="form-control input-sm" maxlength="6" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    salesOrdersTable.draw();
                                });
                        });
                        this.api().columns([7]).every(function(){
                            var column = this;
                            var input = '<input type="text" id="actual-total" class="form-control input-sm" maxlength="6" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    salesOrdersTable.draw();
                                });
                        });
                        this.api().columns([9,10,11,13]).every(function(){
                            var column = this;
                            var index = column[0][0];
                            var action;
                            if(index == 9){
                                action = 'getSalesChannels';
                            }else if(index == 10){
                                action = 'getPaymentMethods';
                            }else if(index == 11){
                                action = 'getDeliveryMethods';
                            }else{
                                action = 'getEmployees';
                            }
                            $.get('store/' + {{$store_id}} + '/salesOrder/' + action, function(data){
                                var select = data;
                                $(select).appendTo($(column.footer()).empty())
                                    .on('change', function(){
                                        var val = $(this).find('option:selected').val();
                                        column.search(val, false, false, true).draw();
                                    });
                            });
                        });
                        this.api().columns([14]).every(function(){
                            var column = this;
                            var select =
                                '<select id="status" class="form-control input-sm" name="status">' +
                                '<option value="" selected="selected"></option>' +
                                '<option value="准时">准时</option>' +
                                '<option value="超时">超时</option>' +
                                '</select>';
                            $(select).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    salesOrdersTable.draw();
                                });
                        });
                    }
                });

                var dateRangeFilter =
                    '<div id="salesOrders_data_range_filter" style="color: #444444;"><label>' +
                    '<i class="fa fa-calendar fa-lg"></i>' +
                    '<input type="text" name="date_range" class="form-control input-sm" style="margin-left: 6px;">' +
                    '</label></div>';

                $('#salesOrders_filter').parent().parent().append(dateRangeFilter);

                var dateRangeSelector = $('input[name="date_range"]');

                var start = moment().subtract('1', 'months');
                var end = moment();

                dateRangeSelector.daterangepicker({
                    startDate: start,
                    endDate: end,
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY/MM/DD',
                        applyLabel: '确定',
                        cancelLabel: '清空',
                    }
                });

                dateRangeSelector.on('apply.daterangepicker', function(ev, picker){
                    $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
                    salesOrdersTable.draw();
                });

                dateRangeSelector.on('cancel.daterangepicker', function(ev, picker){
                    $(this).val('');
                    salesOrdersTable.draw();
                });

                dateRangeSelector.on('change', function(){
                    if($(this).val().trim() == ''){
                        salesOrdersTable.draw();
                    }
                });
            });
        </script>
    @elseif(Auth::user()->employee->employeeType->name == '门店管理')
        <script>
            $(function(){
                var dateRangePicker = $('.daterangepicker');
                if(dateRangePicker.length){
                    dateRangePicker.remove();
                }

                $(document).off('input', 'input');

                $(document).off('change', 'select');

                $('#salesOrder-create-button').click(function(e){
                    e.preventDefault();
                    var token = '{{csrf_token()}}';
                    $.ajax({
                        url: 'store/' + {{$store_id}} + '/salesOrder/addProducts',
                        type: 'POST',
                        data: {
                            _method: 'POST',
                            _token : token,
                            isNew : 1,
                        },
                        success: function(data){
                            $('section.content').html(data);
                        }
                    });
                });

                $(document).off('click', ".salesOrder-edit-button");

                $(document).on('click', ".salesOrder-edit-button", function(e){
                    e.preventDefault();
                    var salesOrderId = $(this).prop('id').replace('edit-salesOrder-id-', '');
                    var token = '{{csrf_token()}}';
                    $.ajax({
                        url: 'store/' + {{$store_id}} + '/salesOrder/' + salesOrderId + '/changeMember',
                        type: 'POST',
                        data: {
                            _method: 'POST',
                            _token : token,
                            isNew : 1,
                        },
                        success: function(data){
                            $('section.content').html(data);
                        }
                    });
                });

                $(document).off('click', ".salesOrder-cancel-button");

                $(document).on('click', ".salesOrder-cancel-button", function(e){
                    e.preventDefault();
                    var result = confirm("确认退单?");
                    if(result){
                        var salesOrderId = $(this).prop('id').replace('cancel-salesOrder-id-', '');
                        var token = '{{csrf_token()}}';
                        $.ajax({
                            url: 'store/' + {{$store_id}} + '/salesOrder/' + salesOrderId + '/cancel',
                            type: 'POST',
                            data: {
                                _method: 'POST',
                                _token : token,
                            },
                            success: function(data){
                                $('section.content').html(data);
                            }
                        });
                    }
                });

                $(document).off('click', '.salesOrder-product-top');

                $(document).on('click', '.salesOrder-product-top', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-product');

                $(document).on('click', '.salesOrder-product', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-gift-top');

                $(document).on('click', '.salesOrder-gift-top', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-gift');

                $(document).on('click', '.salesOrder-gift', function(e){
                    e.preventDefault();
                });

                var salesOrdersTable = $('#salesOrders').DataTable({
                    serverSide: true,
                    ajax: {
                        url: 'store/{{$store_id}}/salesOrder/getSalesOrders',
                        data: function(d){
                            var dateRangeSelector   = $('input[name=date_range]');
                            if(dateRangeSelector.length && dateRangeSelector.val() != ''){
                                var dateRange       = dateRangeSelector.val().split(' - ');
                                d.startDate         = dateRange[0].trim();
                                d.endDate           = dateRange[1].trim();
                            }

                            var totalSelector = $('#total');
                            if(totalSelector.length){
                                var total    = totalSelector.val();
                                if(!isNaN(total)){
                                    d.total  = total;
                                }else if(!isNaN(total.substr(0, total.length-1))){
                                    if(total.substr(total.length-1) == '+'){
                                        d.total   = total.substr(0, total.length-1);
                                        d.totalComparator = '>=';
                                    }else if(total.substr(total.length-1) == '-'){
                                        d.total   = total.substr(0, total.length-1);
                                        d.totalComparator = '<';
                                    }
                                }
                            }

                            var actualTotalSelector = $('#actual-total');
                            if(actualTotalSelector.length){
                                var actualTotal    = actualTotalSelector.val();
                                if(!isNaN(actualTotal)){
                                    d.actualTotal  = actualTotal;
                                }else if(!isNaN(actualTotal.substr(0, actualTotal.length-1))){
                                    if(actualTotal.substr(actualTotal.length-1) == '+'){
                                        d.actualTotal   = actualTotal.substr(0, actualTotal.length-1);
                                        d.actualTotalComparator = '>=';
                                    }else if(actualTotal.substr(actualTotal.length-1) == '-'){
                                        d.actualTotal   = actualTotal.substr(0, actualTotal.length-1);
                                        d.actualTotalComparator = '<';
                                    }
                                }
                            }

                            var statusSelector = $('#status');
                            if(statusSelector.length){
                                var status = statusSelector.find('option:selected').val();
                                if(status != ''){
                                    d.status= status;
                                }
                            }
                        }
                    },
                    dom: "<'row'<'col-md-6'<'pull-left'f>><'col-md-6'<'pull-right'l>>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-12'p>>" +
                    "<'row'<'col-md-6'<'pull-left'i>><'col-md-6'<'pull-right'B>>>",
                    buttons: [
                        'csv', 'excel', 'pdf'
                    ],
                    columns: [
                        {data: 'member.name',           name: 'member.name'},
                        {data: 'receiver',              name: 'receiver'},
                        {data: 'phone.number',          name: 'phone.number', orderable: false},
                        {data: 'address',               name: 'address.line', orderable: false},
                        {data: 'salesOrderProducts',    name: 'salesOrderProducts.product', orderable: false},
                        {data: 'salesOrderGifts',       name: 'salesOrderGifts.gift', orderable: false},
                        {data: 'total',                 name: 'total'},
                        {data: 'actual_total',          name: 'actual_total'},
                        {data: 'transaction_date',      name: 'transaction_date'},
                        {data: 'sales_channel.name',    name: 'salesChannel.name'},
                        {data: 'payment_method.name',   name: 'paymentMethod.name'},
                        {data: 'delivery_method.name',  name: 'deliveryMethod.name'},
                        {data: 'note',                  name: 'note', orderable: false},
                        {data: 'employee.name',         name: 'employee.name'},
                        {data: 'status',                name: 'created_at', searchable: false, orderable: false},
                        {data: 'edit',                  name: 'created_at', searchable: false, orderable: false},
                        {data: 'cancel',                name: 'created_at', searchable: false, orderable: false}
                    ],
                    order: [[8,'desc']],
                    initComplete: function(){
                        this.api().columns([0,1,2,3,8,12]).every(function(){
                            var column = this;
                            var input = '<input type="search" class="form-control input-sm" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    column.search($(this).val(), false, false, true).draw();
                                });
                        });
                        this.api().columns([6]).every(function(){
                            var column = this;
                            var input = '<input type="text" id="total" class="form-control input-sm" maxlength="6" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    salesOrdersTable.draw();
                                });
                        });
                        this.api().columns([7]).every(function(){
                            var column = this;
                            var input = '<input type="text" id="actual-total" class="form-control input-sm" maxlength="6" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    salesOrdersTable.draw();
                                });
                        });
                        this.api().columns([9,10,11,13]).every(function(){
                            var column = this;
                            var index = column[0][0];
                            var action;
                            if(index == 9){
                                action = 'getSalesChannels';
                            }else if(index == 10){
                                action = 'getPaymentMethods';
                            }else if(index == 11){
                                action = 'getDeliveryMethods';
                            }else{
                                action = 'getEmployees';
                            }
                            $.get('store/' + {{$store_id}} + '/salesOrder/' + action, function(data){
                                var select = data;
                                $(select).appendTo($(column.footer()).empty())
                                    .on('change', function(){
                                        var val = $(this).find('option:selected').val();
                                        column.search(val, false, false, true).draw();
                                    });
                            });
                        });
                        this.api().columns([14]).every(function(){
                            var column = this;
                            var select =
                                '<select id="status" class="form-control input-sm" name="status">' +
                                '<option value="" selected="selected"></option>' +
                                '<option value="准时">准时</option>' +
                                '<option value="超时">超时</option>' +
                                '</select>';
                            $(select).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    salesOrdersTable.draw();
                                });
                        });
                    }
                });

                var dateRangeFilter =
                    '<div id="salesOrders_data_range_filter" style="color: #444444;"><label>' +
                    '<i class="fa fa-calendar fa-lg"></i>' +
                    '<input type="text" name="date_range" class="form-control input-sm" style="margin-left: 6px;">' +
                    '</label></div>';

                $('#salesOrders_filter').parent().parent().append(dateRangeFilter);

                var dateRangeSelector = $('input[name="date_range"]');

                var start = moment().subtract('1', 'months');
                var end = moment();

                dateRangeSelector.daterangepicker({
                    startDate: start,
                    endDate: end,
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY/MM/DD',
                        applyLabel: '确定',
                        cancelLabel: '清空',
                    }
                });

                dateRangeSelector.on('apply.daterangepicker', function(ev, picker){
                    $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
                    salesOrdersTable.draw();
                });

                dateRangeSelector.on('cancel.daterangepicker', function(ev, picker){
                    $(this).val('');
                    salesOrdersTable.draw();
                });

                dateRangeSelector.on('change', function(){
                    if($(this).val().trim() == ''){
                        salesOrdersTable.draw();
                    }
                });
            });
        </script>
    @else
        <script>
            $(function(){
                var dateRangePicker = $('.daterangepicker');
                if(dateRangePicker.length){
                    dateRangePicker.remove();
                }

                $(document).off('input', 'input');

                $(document).off('change', 'select');

                $('#salesOrder-create-button').click(function(e){
                    e.preventDefault();
                    var token = '{{csrf_token()}}';
                    $.ajax({
                        url: 'store/' + {{$store_id}} + '/salesOrder/addProducts',
                        type: 'POST',
                        data: {
                            _method: 'POST',
                            _token : token,
                            isNew : 1,
                        },
                        success: function(data){
                            $('section.content').html(data);
                        }
                    });
                });

                $(document).off('click', ".salesOrder-edit-button");

                $(document).on('click', ".salesOrder-edit-button", function(e){
                    e.preventDefault();
                    var salesOrderId = $(this).prop('id').replace('edit-salesOrder-id-', '');
                    var token = '{{csrf_token()}}';
                    $.ajax({
                        url: 'store/' + {{$store_id}} + '/salesOrder/' + salesOrderId + '/changeMember',
                        type: 'POST',
                        data: {
                            _method: 'POST',
                            _token : token,
                            isNew : 1,
                        },
                        success: function(data){
                            $('section.content').html(data);
                        }
                    });
                });

                $(document).off('click', ".salesOrder-cancel-button");

                $(document).on('click', ".salesOrder-cancel-button", function(e){
                    e.preventDefault();
                    var result = confirm("确认退单?");
                    if(result){
                        var salesOrderId = $(this).prop('id').replace('cancel-salesOrder-id-', '');
                        var token = '{{csrf_token()}}';
                        $.ajax({
                            url: 'store/' + {{$store_id}} +'/salesOrder/' + salesOrderId + '/cancel',
                            type: 'POST',
                            data: {
                                _method: 'POST',
                                _token: token,
                            },
                            success: function (data) {
                                $('section.content').html(data);
                            }
                        });
                    }
                });

                $(document).off('click', '.salesOrder-product-top');

                $(document).on('click', '.salesOrder-product-top', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-product');

                $(document).on('click', '.salesOrder-product', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-gift-top');

                $(document).on('click', '.salesOrder-gift-top', function(e){
                    e.preventDefault();
                });

                $(document).off('click', '.salesOrder-gift');

                $(document).on('click', '.salesOrder-gift', function(e){
                    e.preventDefault();
                });

                var salesOrdersTable = $('#salesOrders').DataTable({
                    serverSide: true,
                    ajax: {
                        url: 'store/{{$store_id}}/salesOrder/getSalesOrders',
                        data: function(d){
                            var dateRangeSelector   = $('input[name=date_range]');
                            if(dateRangeSelector.length && dateRangeSelector.val() != ''){
                                var dateRange       = dateRangeSelector.val().split(' - ');
                                d.startDate         = dateRange[0].trim();
                                d.endDate           = dateRange[1].trim();
                            }

                            var totalSelector = $('#total');
                            if(totalSelector.length){
                                var total    = totalSelector.val();
                                if(!isNaN(total)){
                                    d.total  = total;
                                }else if(!isNaN(total.substr(0, total.length-1))){
                                    if(total.substr(total.length-1) == '+'){
                                        d.total   = total.substr(0, total.length-1);
                                        d.totalComparator = '>=';
                                    }else if(total.substr(total.length-1) == '-'){
                                        d.total   = total.substr(0, total.length-1);
                                        d.totalComparator = '<';
                                    }
                                }
                            }

                            var actualTotalSelector = $('#actual-total');
                            if(actualTotalSelector.length){
                                var actualTotal    = actualTotalSelector.val();
                                if(!isNaN(actualTotal)){
                                    d.actualTotal  = actualTotal;
                                }else if(!isNaN(actualTotal.substr(0, actualTotal.length-1))){
                                    if(actualTotal.substr(actualTotal.length-1) == '+'){
                                        d.actualTotal   = actualTotal.substr(0, actualTotal.length-1);
                                        d.actualTotalComparator = '>=';
                                    }else if(actualTotal.substr(actualTotal.length-1) == '-'){
                                        d.actualTotal   = actualTotal.substr(0, actualTotal.length-1);
                                        d.actualTotalComparator = '<';
                                    }
                                }
                            }

                            var statusSelector = $('#status');
                            if(statusSelector.length){
                                var status = statusSelector.find('option:selected').val();
                                if(status != ''){
                                    d.status= status;
                                }
                            }
                        }
                    },
                    dom: "<'row'<'col-md-6'<'pull-left'f>><'col-md-6'<'pull-right'l>>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-12'p>>" +
                    "<'row'<'col-md-6'<'pull-left'i>><'col-md-6'<'pull-right'B>>>",
                    buttons: [
                        'csv', 'excel', 'pdf'
                    ],
                    columns: [
                        {data: 'member.name',           name: 'member.name'},
                        {data: 'receiver',              name: 'receiver'},
                        {data: 'phone.number',          name: 'phone.number', orderable: false},
                        {data: 'address',               name: 'address.line', orderable: false},
                        {data: 'salesOrderProducts',    name: 'salesOrderProducts.product', orderable: false},
                        {data: 'salesOrderGifts',       name: 'salesOrderGifts.gift', orderable: false},
                        {data: 'total',                 name: 'total'},
                        {data: 'actual_total',          name: 'actual_total'},
                        {data: 'transaction_date',      name: 'transaction_date'},
                        {data: 'sales_channel.name',    name: 'salesChannel.name'},
                        {data: 'payment_method.name',   name: 'paymentMethod.name'},
                        {data: 'delivery_method.name',  name: 'deliveryMethod.name'},
                        {data: 'note',                  name: 'note', orderable: false},
                        {data: 'edit',                  name: 'created_at', searchable: false, orderable: false},
                        {data: 'cancel',                name: 'created_at', searchable: false, orderable: false}
                    ],
                    order: [[8,'desc']],
                    initComplete: function(){
                        this.api().columns([0,1,2,3,8,12]).every(function(){
                            var column = this;
                            var input = '<input type="search" class="form-control input-sm" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    column.search($(this).val(), false, false, true).draw();
                                });
                        });
                        this.api().columns([6]).every(function(){
                            var column = this;
                            var input = '<input type="text" id="total" class="form-control input-sm" maxlength="6" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    salesOrdersTable.draw();
                                });
                        });
                        this.api().columns([7]).every(function(){
                            var column = this;
                            var input = '<input type="text" id="actual-total" class="form-control input-sm" maxlength="6" size="3">';
                            $(input).appendTo($(column.footer()).empty())
                                .on('change', function(){
                                    salesOrdersTable.draw();
                                });
                        });
                        this.api().columns([9,10,11]).every(function(){
                            var column = this;
                            var index = column[0][0];
                            var action;
                            if(index == 9){
                                action = 'getSalesChannels';
                            }else if(index == 10){
                                action = 'getPaymentMethods';
                            }else if(index == 11){
                                action = 'getDeliveryMethods';
                            }
                            $.get('store/' + {{$store_id}} + '/salesOrder/' + action, function(data){
                                var select = data;
                                $(select).appendTo($(column.footer()).empty())
                                    .on('change', function(){
                                        var val = $(this).find('option:selected').val();
                                        column.search(val, false, false, true).draw();
                                    });
                            });
                        });
                    }
                });

                var dateRangeFilter =
                    '<div id="salesOrders_data_range_filter" style="color: #444444;"><label>' +
                    '<i class="fa fa-calendar fa-lg"></i>' +
                    '<input type="text" name="date_range" class="form-control input-sm" style="margin-left: 6px;">' +
                    '</label></div>';

                $('#salesOrders_filter').parent().parent().append(dateRangeFilter);

                var dateRangeSelector = $('input[name="date_range"]');

                var start = moment().subtract('1', 'months');
                var end = moment();

                dateRangeSelector.daterangepicker({
                    startDate: start,
                    endDate: end,
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY/MM/DD',
                        applyLabel: '确定',
                        cancelLabel: '清空',
                    }
                });

                dateRangeSelector.on('apply.daterangepicker', function(ev, picker){
                    $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
                    salesOrdersTable.draw();
                });

                dateRangeSelector.on('cancel.daterangepicker', function(ev, picker){
                    $(this).val('');
                    salesOrdersTable.draw();
                });

                dateRangeSelector.on('change', function(){
                    if($(this).val().trim() == ''){
                        salesOrdersTable.draw();
                    }
                });
            });
        </script>
    @endif
@endif