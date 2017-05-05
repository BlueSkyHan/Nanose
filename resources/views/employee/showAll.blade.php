<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">员工</h3>
        @if(Auth::check())
        @if(Auth::user()->isAdmin())
        <a href="#" id="employee-create-button" class="pull-right"><i class='fa fa-plus' style='color:#00a65a'></i></a>
        @endif
        @endif
    </div>
    <!-- /.box-header -->
    @if(count($employees)>0)
    <div class="box-body table-responsive no-padding">
        <div class="col-md-12">
            <table id="employees" class="table table-hover text-center" style="color: #4e4e4e;">
                <tbody>
                    <tr>
                        @if(Auth::check())
                            @if(Auth::user()->isAdmin())
                                <th></th>
                            @endif
                        @endif
                        <th>头像</th>
                        @if(Auth::check())
                        @if(Auth::user()->isAdmin())
                        <th>用户名</th>
                        @endif
                        @endif
                        <th>名称</th>
                        <th>职务</th>
                        @if(Auth::check())
                        @if(Auth::user()->isAdmin())
                        <th></th>
                        @endif
                        @endif
                    </tr>
                    @foreach($employees as $employee)
                    <tr id="employee-{{$employee->id}}">
                        @if(Auth::check())
                            @if(Auth::user()->isAdmin())
                                <td>
                                    <a href="#" class="employee-edit-button pull-left"><i class='fa fa-pencil' style='color:#f39c12'></i></a>
                                </td>
                            @endif
                        @endif
                        <td>
                            <img src="{{$employee->photo_path}}" class="img-circle" style="width: 80px; height: 80px;" alt="员工头像">
                        </td>
                        @if(Auth::check())
                        @if(Auth::user()->isAdmin())
                        <td>{{$employee->user->username}}</td>
                        @endif
                        @endif
                        <td>{{$employee->name}}</td>
                        <td>{{$employee->employeeType->name}}</td>
                        @if(Auth::check())
                        @if(Auth::user()->isAdmin())
                        <td>
                            <a href="#" class="employee-delete-button pull-right"><i class='fa fa-times' style='color:#dd4b39'></i></a>
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
@if(Auth::user()->isAdmin())
<script>
$(function(){
    var dateRangePicker = $('.daterangepicker');
    if(dateRangePicker.length){
        dateRangePicker.remove();
    }

    $(document).off('input', 'input');

    $(document).off('change', 'select');

    $('#employee-create-button').click(function(e){
        e.preventDefault();
        $.get('store/' + '{{$store_id}}' + '/employee/create', function(data){
            $('section.content').html(data);
        });
    });

    $('.employee-edit-button').click(function(e){
        e.preventDefault();
        var employeeId = $(this).closest('tr').prop('id').replace('employee-', '');
        $.get('store/' + '{{$store_id}}' + '/employee/' + employeeId + '/edit', function(data){
            $('section.content').html(data);
        });
    });

    $('.employee-delete-button').click(function(e){
        e.preventDefault();
        var employeeId = $(this).closest('tr').prop('id').replace('employee-', '');
        var token = '{{csrf_token()}}';
        $.ajax({
            url: 'store/' + '{{$store_id}}' + '/employee/' + employeeId,
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
});
</script>
@endif
@endif

<style>
    table th {
        border-top: none !important;
    }
    table td {
        text-align: center !important;
        vertical-align: middle !important;
    }
</style>