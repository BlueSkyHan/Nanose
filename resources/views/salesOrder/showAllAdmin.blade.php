<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">订单</h3>
    </div>
    <!-- /.box-header -->
    @if($hasSalesOrder)
        <div class="box-body table-responsive" style="padding: 15px 0 !important;">
            <div class="col-md-12">
                @include('salesOrder.getSalesOrdersTable', compact('hasSalesOrder', 'store_id'))
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

<style>
    #salesOrders_length label select{
        margin: 0 0 0 6px;
    }

    #salesOrders_length label{
        color: #444444;
        margin: 0;
    }

    #salesOrders_length{
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

    #salesOrders_filter label{
        color: #444444;
        margin: 0;
    }

    #salesOrders_filter{
        margin: 0 6px 10px 0;
    }

    #salesOrders_info{
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

    #salesOrders_previous a,
    #salesOrders_previous a:focus,
    #salesOrders_previous a:hover,
    #salesOrders_previous span,
    #salesOrders_previous span:focus,
    #salesOrders_previous span:hover{
        padding: 0 6px 0 0 !important;
    }

    #salesOrders_next a,
    #salesOrders_next a:focus,
    #salesOrders_next a:hover,
    #salesOrders_next span,
    #salesOrders_next span:focus,
    #salesOrders_next span:hover{
        padding: 0 0 0 6px !important;
    }

    #salesOrders_paginate{
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