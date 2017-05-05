<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">现有员工</h3>
    </div>
    {!! Form::model($employee, ['action'=>['EmployeeController@update', $store_id, $id], 'method'=>'PATCH', 'files'=>true, 'id'=>'employee-edit-form', 'role'=>'form']) !!}
    <div class="box-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    @if(Auth::check())
                        @if(Auth::user()->isAdmin())
                            <div class="form-group">
                                {!! Form::label('username', '用户名') !!}
                                {!! Form::text('username', $employee->user->username, array('placeholder'=>'请输入用户名', 'class'=>'form-control')) !!}
                            </div>
                        @else
                            <div class="form-group">
                                {!! Form::label('username', '用户名') !!}
                                {!! Form::text('username', $employee->user->username, array('placeholder'=>'请输入用户名', 'class'=>'form-control', 'disabled'=>'')) !!}
                            </div>
                        @endif
                    @endif
                    <div class="form-group">
                        {!! Form::label('password', '密码') !!}
                        {!! Form::password('password', array('placeholder'=>'请输入密码', 'class'=>'form-control')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('confirm-password', '确认密码') !!}
                        {!! Form::password('confirm-password', array('placeholder'=>'请确认密码', 'class'=>'form-control')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('name', '姓名') !!}
                        {!! Form::text('name', null, array('placeholder'=>'请输入姓名', 'class'=>'form-control')) !!}
                    </div>
                    @if(Auth::check())
                    @if(Auth::user()->isAdmin())
                    <div class="form-group">
                        {!! Form::label('employee_type_id', '职务') !!}
                        {!! Form::select('employee_type_id', array(''=>'请选择职务') + $employeeTypes, null, array('class'=>'form-control')) !!}
                    </div>
                    @else
                    <div class="form-group">
                        {!! Form::label('employee_type_id', '职务') !!}
                        {!! Form::select('employee_type_id', array(''=>'请选择职务') + $employeeTypes, null, array('class'=>'form-control', 'disabled'=>'')) !!}
                    </div>
                    @endif
                    @endif
                    <div class="form-group">
                        {!! Form::label('photo', '照片') !!}
                        {!! Form::file('photo') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <div class="col-md-12">
            {!! Form::button('返回', array('id'=>'employee-back', 'class'=>'btn btn-default')) !!}
            {!! Form::submit('更新', array('id'=>'employee-edit', 'class'=>'btn btn-info pull-right')) !!}
        </div>
    </div>
    {!! Form::close() !!}
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

        $('input').on('input', function(){
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

        $(document).off('change', 'select');

        $('select').on('change', function(){
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

        $("input[type='file']").change(function() {
            var file = $(this).val();
            switch(file.substring(file.lastIndexOf('.') + 1).toLowerCase()){
                case 'gif': case 'jpg': case 'jpeg': case 'png':
                break;
                default:
                    $(this).val('');
                    alert("请输入gif，jpg，jpeg 或 png格式的图片");
                    break;
            }
        });

        var password = $("input[name='password']");
        var confirmPassword = $("input[name='confirm-password']");

        password.on('input', function(){
            if(confirmPassword.val().trim() != ''){
                if($(this).val().trim() != confirmPassword.val().trim()){
                    confirmPassword.parent().removeClass('has-info').addClass('has-error');
                    confirmPassword.next().remove();
                    confirmPassword.parent().append("<span class='help-block'><i class='fa fa-times-circle'> 与密码不匹配</i></span>");
                }else{
                    confirmPassword.parent().removeClass('has-error').addClass('has-info');
                    confirmPassword.next().remove();
                }
            }
        });

        confirmPassword.on('input', function(){
            if($(this).val().trim() != ''){
                if(password.val().trim() != $(this).val().trim()){
                    $(this).parent().removeClass('has-info').addClass('has-error');
                    $(this).next().remove();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> 与密码不匹配</i></span>");
                }else{
                    $(this).parent().removeClass('has-error').addClass('has-info');
                    $(this).next().remove();
                }
            }
        });

        $('#employee-back').click(function(e){
            e.preventDefault();
            $.get('store/' + '{{$store_id}}' + '/employee', function(data){
                $('section.content').html(data);
            });
        });

        $('#employee-edit').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            $("input[type='text']").each(function(){
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
            $("input[type='password']").each(function(){
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
            $('select').each(function(){
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
            if(confirmPassword.val().trim() != ''){
                if(password.val().trim() != confirmPassword.val().trim()){
                    confirmPassword.parent().removeClass('has-info').addClass('has-error');
                    confirmPassword.next().remove();
                    confirmPassword.parent().append("<span class='help-block'><i class='fa fa-times-circle'> 与密码不匹配</i></span>");
                    isFormValid = false;
                }else{
                    confirmPassword.parent().removeClass('has-error').addClass('has-info');
                    confirmPassword.next().remove();
                }
            }
            if(!isFormValid){
                return;
            }
            var formData = new FormData($('#employee-edit-form')[0]);
            formData.append("_method", "PATCH");
            formData.append("_token", "{{csrf_token()}}");
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/employee/' + '{{$id}}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data){
                    $('section.content').html(data);
                }
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

    $('input').on('input', function(){
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

    $(document).off('change', 'select');

    $('select').on('change', function(){
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

    $("input[type='file']").change(function() {
        var file = $(this).val();
        switch(file.substring(file.lastIndexOf('.') + 1).toLowerCase()){
            case 'gif': case 'jpg': case 'jpeg': case 'png':
            break;
            default:
                $(this).val('');
                alert("请输入gif，jpg，jpeg 或 png格式的图片");
                break;
        }
    });

    var password = $("input[name='password']");
    var confirmPassword = $("input[name='confirm-password']");

    password.on('input', function(){
        if(confirmPassword.val().trim() != ''){
            if($(this).val().trim() != confirmPassword.val().trim()){
                confirmPassword.parent().removeClass('has-info').addClass('has-error');
                confirmPassword.next().remove();
                confirmPassword.parent().append("<span class='help-block'><i class='fa fa-times-circle'> 与密码不匹配</i></span>");
            }else{
                confirmPassword.parent().removeClass('has-error').addClass('has-info');
                confirmPassword.next().remove();
            }
        }
    });

    confirmPassword.on('input', function(){
        if($(this).val().trim() != ''){
            if(password.val().trim() != $(this).val().trim()){
                $(this).parent().removeClass('has-info').addClass('has-error');
                $(this).next().remove();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> 与密码不匹配</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-info');
                $(this).next().remove();
            }
        }
    });

    $('#employee-back').click(function(e){
        e.preventDefault();
        $('section.content').html('');
    });
});
</script>
@endif
@endif
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
    table th {
        border-top: none !important;
    }
</style>