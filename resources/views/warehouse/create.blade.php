<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">新仓库</h3>
    </div>
    <!-- /.box-header -->

    <!-- form start -->
    {!! Form::open(['action'=>['WarehouseController@store', $store_id], 'method'=>'POST', 'id'=>'warehouse-create-form', 'role'=>'form']) !!}
    <div class="box-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name', '名称') !!}
                        {!! Form::text('name', null, array('placeholder'=>'请输入名称', 'id'=>'warehouse-name-create-input', 'class'=>'form-control')) !!}
                    </div>
                </div>
            </div>
            @include('address.create')
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-12">
            {!! Form::button('返回', array('id'=>'warehouse-create-form-back', 'class'=>'btn btn-default')) !!}
            {!! Form::submit('添加', array('id'=>'warehouse-create-form-submit', 'class'=>'btn btn-success pull-right')) !!}
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
                $(this).parent().removeClass('has-success').addClass('has-error');
                $(this).next().remove();
                var inputName = $(this).prev().text();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-success');
                $(this).next().remove();
            }
        });

        $('#warehouse-create-form-back').click(function(e){
            e.preventDefault();
            $.get('store/' + '{{$store_id}}' + '/warehouse', function(data){
                $('section.content').html(data);
            });
        });

        $('#warehouse-create-form-submit').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            $("input[type='text']").each(function(){
                var input = $(this).val();
                if(input.trim() == ''){
                    $(this).parent().removeClass('has-success').addClass('has-error');
                    $(this).next().remove();
                    var inputName = $(this).prev().text();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
                    isFormValid = false;
                }else{
                    $(this).parent().removeClass('has-error').addClass('has-success');
                    $(this).next().remove();
                }
            });
            $("select").each(function(){
                var input = $(this).find("option:selected").val();
                $(this).parent().find('span.help-block').remove();
                if(input == ''){
                    $(this).parent().removeClass('has-success').addClass('has-error');
                    var inputName = $(this).prev().text();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
                    isFormValid = false;
                }else{
                    $(this).parent().removeClass('has-error').addClass('has-success');
                }
            });
            if(!isFormValid){
                return;
            }
            $.post('store/' + '{{$store_id}}' + '/warehouse', $('#warehouse-create-form').serialize(), function(data){
                $('section.content').html(data);
            });
        });
    });
</script>