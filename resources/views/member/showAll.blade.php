<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">会员</h3>
        @if(Auth::check())
            @if(!Auth::user()->isAdmin())
                <a href="#" id="member-create-button" class="pull-right"><i class='fa fa-plus' style='color:#00a65a'></i></a>
            @endif
        @endif
    </div>
    <!-- /.box-header -->
    @if($hasMember)
        <div class="box-body table-responsive" style="padding: 15px 0 !important;">
            <div class="col-md-12">
                <table id="members" class="table table-bordered table-hover" style="width: 100%; color: #4e4e4e;">
                    <thead>
                        <tr>
                            <th>姓名</th>
                            <th>性别</th>
                            <th>出生日期</th>
                            <th>健康状况</th>
                            <th>电话</th>
                            <th>地址</th>
                            <th>订单数量</th>
                            <th>累计金额(元)</th>
                            <th>入会时间</th>
                            <th>入会来源</th>
                            <th>介绍人</th>
                            @if(Auth::check())
                                @if(!Auth::user()->isAdmin())
                                    <th>更新</th>
                                @endif
                            @endif
                            @if(Auth::check())
                                @if(Auth::user()->isAdmin())
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
                            @if(Auth::check())
                                @if(!Auth::user()->isAdmin())
                                    <th></th>
                                @endif
                            @endif
                            @if(Auth::check())
                                @if(Auth::user()->isAdmin())
                                    <th></th>
                                @endif
                            @endif
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <div class="box-body">
            <div class="col-md-12">
                <p class="text-muted">暂无</p>
            </div>
        </div>
    @endif
    <!-- /.box-body -->
</div>

@if(Auth::check())
@if(Auth::user()->isAdmin())
<script>
    $(function(){
        var dateRangePicker = $('.daterangepicker');
        if(dateRangePicker.length){
            dateRangePicker.remove();
        }

        $(document).off('input', 'input');

        $(document).off('change', 'select');

        $(document).off('click', ".member-edit-button");

        $(document).off('click', ".member-gift-button");

        $(document).off('click', ".member-delete-button");

        $(document).on('click', ".member-delete-button", function(e){
            e.preventDefault();
            var memberId = $(this).prop('id').replace('delete-member-id-', '');
            var token = '{{ csrf_token() }}';
            $.ajax({
                url: 'member/' + memberId,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token : token
                },
                success: function(data){
                    $('section.content').html(data);
                }
            });
        });

        $(document).off('click', '.member-phone-top');

        $(document).on('click', '.member-phone-top', function(e){
            e.preventDefault();
        });

        $(document).off('click', '.member-phone');

        $(document).on('click', '.member-phone', function(e){
            e.preventDefault();
        });

        $(document).off('click', '.member-address-top');

        $(document).on('click', '.member-address-top', function(e){
            e.preventDefault();
        });

        $(document).off('click', '.member-address');

        $(document).on('click', '.member-address', function(e){
            e.preventDefault();
        });

        var membersTable = $('#members').DataTable({
            serverSide: true,
            ajax: {
                url: 'member/getMembers',
                data: function(d){
                    var dateRangeType       = $('input[name="date_range_type"]');
                    var dateRangeSelector   = $('input[name="date_range"]');
                    if(dateRangeType.length && dateRangeType.is(':checked')){
                        if(dateRangeSelector.length && dateRangeSelector.val() != ''){
                            var dateRange       = dateRangeSelector.val().split(' - ');
                            d.startDate         = dateRange[0].trim();
                            d.endDate           = dateRange[1].trim();
                            d.dateRangeType     = dateRangeType.filter(':checked').val();
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
                {data: 'name',              name: 'name'},
                {data: 'gender',            name: 'gender'},
                {data: 'birth_date',        name: 'birth_date'},
                {data: 'health_status',     name: 'health_status',      orderable: false},
                {data: 'phones',            name: 'phones.number',      orderable: false},
                {data: 'addresses',         name: 'addresses.line',     orderable: false},
                {data: 'salesOrdersCount',  name: 'salesOrdersCount',   searchable: false, orderable: false},
                {data: 'salesOrdersSum',    name: 'salesOrdersSum',     searchable: false, orderable: false},
                {data: 'created_at',        name: 'created_at'},
                {data: 'member_from.name',  name: 'memberFrom.name'},
                {data: 'referrer',          name: 'referrer'},
                {data: 'delete',            name: 'created_at',         searchable: false, orderable: false},
            ],
            order: [[8,'desc']],
            initComplete: function(){
                this.api().columns([0,2,3,8,10]).every(function(){
                    var column = this;
                    var input = '<input type="search" class="form-control input-sm" size="3">';
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function(){
                            column.search($(this).val(), false, false, true).draw();
                        });
                });

                this.api().columns([1]).every(function(){
                    var column = this;
                    var select =
                        '<select class="form-control input-sm">' +
                        '<option value="" selected="selected"></option>' +
                        '<option value="男">男</option>' +
                        '<option value="女">女</option>' +
                        '</select>';
                    $(select).appendTo($(column.footer()).empty())
                        .on('change', function(){
                            var val = $(this).find('option:selected').val();
                            column.search(val, false, false, true).draw();
                        });
                });

                this.api().columns([9]).every(function(){
                    var column = this;
                    $.get('member/getMemberFroms', function(data){
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
            '<div id="members_data_range_filter" style="color: #444444;">' +
            '<label>' +
                '<i class="fa fa-calendar fa-lg"></i>' +
                '<input type="text" name="date_range" class="form-control input-sm" style="margin-left: 6px;">' +
            '</label>' +
            '<label>' +
                '<span style="font-size: 12px; margin: 0 0 0 6px;">订单</span>' +
                '<input name="date_range_type" type="radio" value="salesOrders-date-range" style="margin: 0 0 0 3px;">' +
                '<span style="font-size: 12px; margin: 0 0 0 6px;">入会</span>' +
                '<input name="date_range_type" type="radio" value="members-date-range" style="margin: 0 0 0 3px;">' +
            '</label>' +
            '</div>';

        $('#members_filter').parent().parent().append(dateRangeFilter);

        var dateRangeSelector   = $('input[name="date_range"]');
        var dateRangeType       = $('input[name="date_range_type"]');

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
            membersTable.draw();
        });

        dateRangeSelector.on('cancel.daterangepicker', function(ev, picker){
            $(this).val('');
            membersTable.draw();
        });

        dateRangeSelector.on('change', function(){
            if($(this).val().trim() == ''){
                membersTable.draw();
            }
        });

        dateRangeType.on('change', function(){
            membersTable.draw();
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

        $('#member-create-button').click(function(e){
            e.preventDefault();
            $.get('member/create', function(data){
                $('section.content').html(data);
            });
        });

        $(document).off('click', ".member-edit-button");

        $(document).on('click', ".member-edit-button", function(e){
            e.preventDefault();
            var memberId = $(this).prop('id').replace('edit-member-id-', '');
            $.get('member/' + memberId + '/edit', function(data){
                $('section.content').html(data);
            });
        });

        $(document).off('click', ".member-gift-button");

        $(document).on('click', ".member-gift-button", function(e){
            e.preventDefault();
            var memberId = $(this).prop('id').replace('gift-member-id-', '');
            var token = '{{ csrf_token() }}';
            $.ajax({
                url: 'member/' + memberId + '/sendGift',
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

        $(document).off('click', '.member-phone-top');

        $(document).on('click', '.member-phone-top', function(e){
            e.preventDefault();
        });

        $(document).off('click', '.member-phone');

        $(document).on('click', '.member-phone', function(e){
            e.preventDefault();
        });

        $(document).off('click', '.member-address-top');

        $(document).on('click', '.member-address-top', function(e){
            e.preventDefault();
        });

        $(document).off('click', '.member-address');

        $(document).on('click', '.member-address', function(e){
            e.preventDefault();
        });

        var membersTable = $('#members').DataTable({
            serverSide: true,
            ajax: {
                url: 'member/getMembers',
                data: function(d){
                    var dateRangeType       = $('input[name="date_range_type"]');
                    var dateRangeSelector   = $('input[name="date_range"]');
                    if(dateRangeType.length && dateRangeType.is(':checked')){
                        if(dateRangeSelector.length && dateRangeSelector.val() != ''){
                            var dateRange       = dateRangeSelector.val().split(' - ');
                            d.startDate         = dateRange[0].trim();
                            d.endDate           = dateRange[1].trim();
                            d.dateRangeType     = dateRangeType.filter(':checked').val();
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
                {data: 'name',              name: 'name'},
                {data: 'gender',            name: 'gender'},
                {data: 'birth_date',        name: 'birth_date'},
                {data: 'health_status',     name: 'health_status',      orderable: false},
                {data: 'phones',            name: 'phones.number',      orderable: false},
                {data: 'addresses',         name: 'addresses.line',     orderable: false},
                {data: 'salesOrdersCount',  name: 'salesOrdersCount',   searchable: false, orderable: false},
                {data: 'salesOrdersSum',    name: 'salesOrdersSum',     searchable: false, orderable: false},
                {data: 'created_at',        name: 'created_at'},
                {data: 'member_from.name',  name: 'memberFrom.name'},
                {data: 'referrer',          name: 'referrer'},
                {data: 'edit',              name: 'created_at',         searchable: false, orderable: false},
            ],
            order: [[8,'desc']],
            initComplete: function(){
                this.api().columns([0,2,3,8,10]).every(function(){
                    var column = this;
                    var input = '<input type="search" class="form-control input-sm" size="3">';
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function(){
                            column.search($(this).val(), false, false, true).draw();
                        });
                });

                this.api().columns([1]).every(function(){
                    var column = this;
                    var select =
                        '<select class="form-control input-sm">' +
                        '<option value="" selected="selected"></option>' +
                        '<option value="男">男</option>' +
                        '<option value="女">女</option>' +
                        '</select>';
                    $(select).appendTo($(column.footer()).empty())
                        .on('change', function(){
                            var val = $(this).find('option:selected').val();
                            column.search(val, false, false, true).draw();
                        });
                });

                this.api().columns([9]).every(function(){
                    var column = this;
                    $.get('member/getMemberFroms', function(data){
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
            '<div id="members_data_range_filter" style="color: #444444;">' +
            '<label>' +
            '<i class="fa fa-calendar fa-lg"></i>' +
            '<input type="text" name="date_range" class="form-control input-sm" style="margin-left: 6px;">' +
            '</label>' +
            '<label>' +
            '<span style="font-size: 12px; margin: 0 0 0 6px;">订单</span>' +
            '<input name="date_range_type" type="radio" value="salesOrders-date-range" style="margin: 0 0 0 3px;">' +
            '<span style="font-size: 12px; margin: 0 0 0 6px;">入会</span>' +
            '<input name="date_range_type" type="radio" value="members-date-range" style="margin: 0 0 0 3px;">' +
            '</label>' +
            '</div>';

        $('#members_filter').parent().parent().append(dateRangeFilter);

        var dateRangeSelector   = $('input[name="date_range"]');
        var dateRangeType       = $('input[name="date_range_type"]');

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
            membersTable.draw();
        });

        dateRangeSelector.on('cancel.daterangepicker', function(ev, picker){
            $(this).val('');
            membersTable.draw();
        });

        dateRangeSelector.on('change', function(){
            if($(this).val().trim() == ''){
                membersTable.draw();
            }
        });

        dateRangeType.on('change', function(){
            membersTable.draw();
        });
    });
</script>
@endif
@endif

<style>
    #members_length label select{
        margin: 0 0 0 6px;
    }

    #members_length label{
        color: #444444;
        margin: 0;
    }

    #members_length{
        margin: 0 0 10px 0;
    }

    .dt-buttons{
        margin: 0;
        line-height: 0;
    }

    button.dt-button, div.dt-button, a.dt-button{
        font-size: 12px;
        color: #444444;
        padding: 8px 10px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        margin: 0 5px 0 0;
        z-index: 1;
    }

    #members_filter label{
        color: #444444;
        margin: 0;
    }

    #members_filter{
        margin: 0 6px 10px 0;
    }

    #members_info{
        font-size: 14px;
        color: #444444;
        padding: 0;
        margin: 0;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button,
    .dataTables_wrapper .dataTables_paginate .paginate_button:active,
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active:active,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active:hover,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover{
        display: inline;
        background: transparent !important;
        padding: 0 !important;
        border: 0 transparent !important;
        margin: 0 !important;
        z-index: 1 !important;
    }

    .pagination{
        margin: 0 !important;
    }

    .pagination li{
        margin: 0 !important;
    }

    .pagination li.active a,
    .pagination li.active a:focus,
    .pagination li.active a:hover,
    .pagination li.active span,
    .pagination li.active span:focus,
    .pagination li.active span:hover{
        font-size: 14px;
        color: #3c8dbc !important;
        background-color: transparent !important;
        padding: 0 6px !important;
        border: 0 !important;
        margin: 0 !important;
        z-index: 1 !important;
    }

    .pagination li a,
    .pagination li a:focus,
    .pagination li a:hover,
    .pagination li span,
    .pagination li span:focus,
    .pagination li span:hover{
        font-size: 14px;
        color: #4e4e4e !important;
        background-color: transparent !important;
        padding: 0 6px !important;
        border: 0 !important;
        margin: 0 !important;
        z-index: 1 !important;
    }

    #members_previous a,
    #members_previous a:focus,
    #members_previous a:hover,
    #members_previous span,
    #members_previous span:focus,
    #members_previous span:hover{
        padding: 0 6px 0 0 !important;
    }

    #members_next a,
    #members_next a:focus,
    #members_next a:hover,
    #members_next span,
    #members_next span:focus,
    #members_next span:hover{
        padding: 0 0 0 6px !important;
    }

    #members_paginate{
        color: #444444 !important;
        line-height: 0;
        float: none;
        text-align: center;
        padding: 0;
        margin: 0;
    }

    table.dataTable{
        font-size: 12px !important;
        border-bottom: 0 !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }

    table thead tr th{
        text-align: center !important;
        vertical-align: middle !important;
        padding: 13px 4px 11px 4px !important;
        border-bottom: 1px solid #f4f4f4 !important;
    }

    table tbody tr td{
        text-align: center !important;
        vertical-align: middle !important;
        padding: 4px 4px !important;
    }

    table tfoot tr th{
        text-align: center !important;
        vertical-align: middle !important;
        padding: 4px 0 0 0 !important;
        border-top: 1px solid #f4f4f4 !important;
    }

    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:after{
        top: 0;
        right: 1px;
    }

    .dropdownmenu{
        position: relative;
        display: inline-block;
        padding: 3px 6px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .dropdownmenu:hover{
        box-shadow: 0 6px 8px 0 rgba(0,0,0,0.2);
        background-color: #e5e5e5;
    }

    .dropdownmenu span{
        color: #4e4e4e !important;
        cursor: text;
    }

    .dropdownmenu-content{
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        min-width: 100%;
        box-shadow: 0 6px 8px 0 rgba(0,0,0,0.2);
        z-index: 2;
        background-color: #e5e5e5;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .dropdownmenu:hover .dropdownmenu-content{
        display: block;
    }

    .dropdownmenu-content span{
        display: block;
        color: #4e4e4e;
        padding: 3px 6px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .dropdownmenu-content span:hover{
        background-color: #99caeb;
    }
</style>