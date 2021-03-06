<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">现有仓库</h3>
    </div>
    <!-- /.box-header -->

    <!-- form start -->
    {!! Form::model($warehouse, array('action'=>array('WarehouseController@update', $store_id, $warehouse->id), 'method'=>'PATCH', 'id'=>'warehouse-edit-form', 'role'=>'form')) !!}
    <div class="box-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name', '名称') !!}
                        {!! Form::text('name', $warehouse->name, array('placeholder'=>'请输入名称', 'id'=>'warehouse-name-edit-input', 'class'=>'form-control')) !!}
                    </div>
                </div>
            </div>
            @include('address.edit')
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-12">
            {!! Form::button('返回', array('id'=>'warehouse-edit-form-back', 'class'=>'btn btn-default')) !!}
            {!! Form::submit('更新', array('id'=>'warehouse-edit-form-submit', 'class'=>'btn btn-info pull-right')) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

<script>
    $(function(){
        var dateRangePicker = $('.daterangepicker');
        if(dateRangePicker.length){
            dateRangePicker.remove();
        }

        $(document).off('input', 'input');

        $(document).on('input', "input", function(){
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

        $('#warehouse-edit-form-back').click(function(e){
            e.preventDefault();
            $.get('store/' + '{{$store_id}}' + '/warehouse', function(data){
                $('section.content').html(data);
            });
        });

        $('#warehouse-edit-form-submit').click(function(e){
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
            $("select").each(function(){
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
            if(!isFormValid){
                return;
            }
            $.post('store/' + '{{$store_id}}' + '/warehouse/' + '{{$warehouse->id}}', $('#warehouse-edit-form').serialize(), function(data){
                $('section.content').html(data);
            });
        });
    });
</script>

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
</style>