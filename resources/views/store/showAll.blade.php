<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">门店</h3>
        <a href="#" id="store-create-button" class="pull-right"><i class='fa fa-plus' style='color:#00a65a'></i></a>
    </div>
    <!-- /.box-header -->
    @if(count($stores)>0)
    <div class="box-body table-responsive no-padding">
        <div class="col-md-12">
            <table id="stores" class="table table-hover text-center" style="color: #4e4e4e;">
                <tbody>
                <tr>
                    <th></th>
                    <th>名称</th>
                    <th>联系电话</th>
                    <th>地址</th>
                    <th>订单</th>
                    <th>仓库</th>
                    <th>员工</th>
                    <th></th>
                </tr>
                @foreach($stores as $store)
                <tr id="store-{{$store->id}}">
                    <td>
                        <a href="#" class="store-edit-button pull-left"><i class='fa fa-pencil' style='color:#f39c12'></i></a>
                    </td>
                    <td>{{$store->name}}</td>
                    <td>{{$store->phone->number}}</td>
                    <td>{{$store->address->district->city->province->name == $store->address->district->city->name ?
                    $store->address->district->city->name . " " . $store->address->district->name . " " . $store->address->line :
                    $store->address->district->city->province->name . " " . $store->address->district->city->name . " " . $store->address->district->name . " " . $store->address->line}}</td>
                    <td><a href="#" class="store-salesOrders-button"><i class='fa fa-file-text fa-lg' style='color:#3c8dbc'></i></a></td>
                    <td><a href="#" class="store-warehouses-button"><i class='fa fa-database fa-lg' style='color:#3c8dbc'></i></a></td>
                    <td><a href="#" class="store-employees-button"><i class='fa fa-users fa-lg' style='color:#3c8dbc'></i></a></td>
                    <td>
                        <a href="#" class="store-delete-button pull-right" data-token='{{ csrf_token() }}'><i class='fa fa-times' style='color:#dd4b39'></i></a>
                    </td>
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

<script>
    $(function(){
        var dateRangePicker = $('.daterangepicker');
        if(dateRangePicker.length){
            dateRangePicker.remove();
        }

        $(document).off('input', 'input');

        $(document).off('change', 'select');

        $('#store-create-button').click(function(e){
            e.preventDefault();
            $.get('store/create', function(data){
                $('section.content').html(data);
            });
        });

        $('.store-edit-button').click(function(e){
            e.preventDefault();
            var storeId = $(this).closest('tr').prop('id').replace('store-', '');
            $.get('store/' + storeId + '/edit', function(data){
                $('section.content').html(data);
            });
        });

        $('.store-delete-button').click(function(e){
            e.preventDefault();
            var storeId = $(this).closest('tr').prop('id').replace('store-', '');
            var token = $(this).data('token');
            $.ajax({
                url: 'store/' + storeId,
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

        $('.store-salesOrders-button').click(function(e){
            e.preventDefault();
            var storeId = $(this).closest('tr').prop('id').replace('store-', '');
            $.get('store/' + storeId + '/salesOrder', function(data){
                $('section.content').html(data);
            });
        });

        $('.store-warehouses-button').click(function(e){
            e.preventDefault();
            var storeId = $(this).closest('tr').prop('id').replace('store-', '');
            $.get('store/' + storeId + '/warehouse', function(data){
                $('section.content').html(data);
            });
        });

        $('.store-employees-button').click(function(e){
            e.preventDefault();
            var storeId = $(this).closest('tr').prop('id').replace('store-', '');
            $.get('store/' + storeId + '/employee', function(data){
                $('section.content').html(data);
            });
        });
    });
</script>

<style>
    table th {
        border-top: none !important;
    }
</style>