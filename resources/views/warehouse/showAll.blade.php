<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">仓库</h3>
        @if(Auth::check())
        @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
        <a href="#" id="warehouse-create-button" class="pull-right"><i class='fa fa-plus' style='color:#00a65a'></i></a>
        @endif
        @endif
    </div>
    <!-- /.box-header -->
    @if(count($warehouses)>0)
    <div class="box-body table-responsive no-padding">
        <div class="col-md-12">
            <table id="warehouses" class="table table-hover text-center" style="color: #4e4e4e;">
                <tbody>
                <tr>
                    @if(Auth::check())
                        @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
                            <th></th>
                        @endif
                    @endif
                    <th>名称</th>
                    <th>地址</th>
                    <th>正品</th>
                    <th>赠品</th>
                    @if(Auth::check())
                    @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
                    <th></th>
                    @endif
                    @endif
                </tr>
                @foreach($warehouses as $warehouse)
                <tr id="warehouse-{{$warehouse->id}}">
                    @if(Auth::check())
                        @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
                            <td>
                                <a href="#" class="warehouse-edit-button pull-left"><i class='fa fa-pencil' style='color:#f39c12'></i></a>
                            </td>
                        @endif
                    @endif
                    <td>{{$warehouse->name}}</td>
                    <td>{{$warehouse->address->district->city->province->name == $warehouse->address->district->city->name ?
                    $warehouse->address->district->city->name . " " . $warehouse->address->district->name . " " . $warehouse->address->line :
                    $warehouse->address->district->city->province->name . " " . $warehouse->address->district->city->name . " " . $warehouse->address->district->name . " " . $warehouse->address->line}}</td>
                    <td>
                        <a href="#" class="warehouse-products-button"><i class='fa fa-cubes fa-lg' style='color:#3c8dbc'></i></a>
                    </td>
                    <td>
                        <a href="#" class="warehouse-gifts-button"><i class='fa fa-gift fa-lg' style='color:#3c8dbc'></i></a>
                    </td>
                    @if(Auth::check())
                    @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
                    <td>
                        <a href="#" class="warehouse-delete-button pull-right" data-token='{{ csrf_token() }}'><i class='fa fa-times' style='color:#dd4b39'></i></a>
                    </td>
                    @endif
                    @endif
                </tr>
                @endforeach
                </tbody>
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
@if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
<script>
$(function(){
    var dateRangePicker = $('.daterangepicker');
    if(dateRangePicker.length){
        dateRangePicker.remove();
    }

    $(document).off('input', 'input');

    $(document).off('change', 'select');

    $('#warehouse-create-button').click(function(e){
        e.preventDefault();
        $.get('store/' + '{{$store_id}}' + '/warehouse/create', function(data){
            $('section.content').html(data);
        });
    });

    $('.warehouse-edit-button').click(function(e){
        e.preventDefault();
        var warehouseId = $(this).closest('tr').prop('id').replace('warehouse-', '');
        $.get('store/' + '{{$store_id}}' + '/warehouse/' + warehouseId + '/edit', function(data){
            $('section.content').html(data);
        });
    });

    $('.warehouse-delete-button').click(function(e){
        e.preventDefault();
        var warehouseId = $(this).closest('tr').prop('id').replace('warehouse-', '');
        var token = $(this).data('token');
        $.ajax({
            url: 'store/' + '{{$store_id}}' + '/warehouse/' + warehouseId,
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

    $('.warehouse-products-button').click(function(e){
        e.preventDefault();
        var warehouseId = $(this).closest('tr').prop('id').replace('warehouse-', '');
        $.get('store/' + '{{$store_id}}' + '/warehouse/' + warehouseId + '/product', function(data){
            $('section.content').html(data);
        });
    });

    $('.warehouse-gifts-button').click(function(e){
        e.preventDefault();
        var warehouseId = $(this).closest('tr').prop('id').replace('warehouse-', '');
        $.get('store/' + '{{$store_id}}' + '/warehouse/' + warehouseId + '/gift', function(data){
            $('section.content').html(data);
        });
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

    $('.warehouse-products-button').click(function(e){
        e.preventDefault();
        var warehouseId = $(this).closest('tr').prop('id').replace('warehouse-', '');
        $.get('store/' + '{{$store_id}}' + '/warehouse/' + warehouseId + '/product', function(data){
            $('section.content').html(data);
        });
    });

    $('.warehouse-gifts-button').click(function(e){
        e.preventDefault();
        var warehouseId = $(this).closest('tr').prop('id').replace('warehouse-', '');
        $.get('store/' + '{{$store_id}}' + '/warehouse/' + warehouseId + '/gift', function(data){
            $('section.content').html(data);
        });
    });
});
</script>
@endif
@endif

<style>
    table th {
        border-top: none !important;
    }
</style>