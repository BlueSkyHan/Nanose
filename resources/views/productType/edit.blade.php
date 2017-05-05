<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">现有产品</h3>
    </div>
    <!-- /.box-header -->

    <!-- form start -->
    {!! Form::model($productType, ['action'=>['ProductTypeController@update', $productType->id], 'method'=>'POST', 'id'=>'productType-edit-form', 'role'=>'form']) !!}
    <div class="box-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name', '名称') !!}
                        {!! Form::text('name', null, array('placeholder'=>'请输入名称', 'id'=>'productType-name-edit-input', 'class'=>'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('attribute-name', '属性') !!}&nbsp
                        <a id='attribute-edit-button' href="#" data-toggle='modal' data-target='#attribute-edit-form-modal'>
                            <i class="fa fa-plus" style='color:#00c0ef'></i>
                        </a>
                        <ul id="attributes" class="list-unstyled">
                            @foreach($productType->attributes as $attribute)
                                <li>
                                    <span>{{$attribute->name}}</span>&nbsp
                                    <a class='attribute-remove-button' href='#'>
                                        <i class='fa fa-times' style='color:#dd4b39'></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-4">
            {!! Form::button('返回', array('id'=>'productType-edit-form-back', 'class'=>'btn btn-default')) !!}
            {!! Form::submit('添加', array('id'=>'productType-edit-form-submit', 'class'=>'btn btn-info pull-right')) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

{{--productType attribute form--}}
<div class="modal fade" id="attribute-edit-form-modal" tabindex="-1" role="dialog" aria-labelledby="attributeFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="box box-info">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">属性</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('attribute-name', '名称') !!}
                        {!! Form::text('attribute-name', null, array('placeholder'=>'请输入名称', 'id'=>'attribute-name-edit-input', 'class'=>'form-control')) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('关闭', array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')) !!}
                    {!! Form::button('添加', array('id'=>'attribute-edit-form-button', 'class'=>'btn btn-info', 'data-dismiss'=>'modal')) !!}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>
{{--end form--}}

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

        $(document).off('change', 'select');

        var attributeNameEditInput = $("#attribute-name-edit-input");

        $("#productType-name-edit-input").on('input', function(){
            var input = $(this).val();
            if(input.trim() == ''){
                $(this).parent().removeClass('has-info').addClass('has-error');
                $(this).next().remove();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> 名称不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-info');
                $(this).next().remove();
            }
        });

        attributeNameEditInput.on('input', function(){
            var input = $(this).val();
            if(input.trim() == ''){
                $(this).parent().removeClass('has-info').addClass('has-error');
                $(this).next().remove();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> 名称不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-info');
                $(this).next().remove();
            }
        });

        $('#attribute-edit-button').click(function(){
            attributeNameEditInput.val(null);
            attributeNameEditInput.next().remove();
            attributeNameEditInput.parent().removeClass('has-info').removeClass('has-error');
        });

        $('#attribute-edit-form-button').click(function(){
            var attribute = $('#attribute-name-edit-input').val();
            if(attribute == ''){
                return;
            }
            var attributes = $("#attributes li");
            var hasAttribute = false;
            attributes.each(function(){
                if(attribute == $(this).find('span').text()){
                    hasAttribute = true;
                    return false;
                }
            });
            if(!hasAttribute){
                $('#attributes').parent().removeClass('has-error').addClass('has-info');
                $('#attributes').next().remove();
                $('#attributes').append("<li><span>" + attribute +
                    "</span>&nbsp<a class='attribute-remove-button' href='#'><i class='fa fa-times' style='color:#dd4b39'></i></a></li>");
            }
        });

        $('#attributes').on('click', 'li a.attribute-remove-button', function(){
            $(this).parent().remove();
            if($("#attributes li").length == 0){
                $("#attributes").parent().removeClass('has-info').addClass('has-error');
                $("#attributes").next().remove();
                $("#attributes").parent().append("<span class='help-block'><i class='fa fa-times-circle'> 属性不能为空</i></span>");
            }
        });

        $('#productType-edit-form-submit').click(function(e){
            e.preventDefault();
            var hasError = false;
            if($('#productType-name-edit-input').val().trim() == ''){
                $('#productType-name-edit-input').parent().removeClass('has-info').addClass('has-error');
                $('#productType-name-edit-input').next().remove();
                $('#productType-name-edit-input').parent().append("<span class='help-block'><i class='fa fa-times-circle'> 名称不能为空</i></span>");
                hasError = true;
            }
            if($("#attributes li").length == 0){
                $("#attributes").parent().removeClass('has-info').addClass('has-error');
                $('#attributes').next().remove();
                $("#attributes").parent().append("<span class='help-block'><i class='fa fa-times-circle'> 属性不能为空</i></span>");
                hasError = true;
            }
            if(hasError){
                return;
            }
            var attributes = new Array();
            $("#attributes li").each(function(){
                var attribute = $(this).find('span').text();
                attributes.push(attribute);
            });
            var token = '{{csrf_token()}}';
            $.ajax({
                url: 'productType/' + '{{$productType->id}}',
                type: 'POST',
                data: {
                    _method     : 'PATCH',
                    _token      : token,
                    name        : $('#productType-name-edit-input').val().trim(),
                    attributes  : attributes
                },
                success: function(data){
                    $('section.content').html(data);
                }
            });
        });

        $('#productType-edit-form-back').click(function(e){
            e.preventDefault();
            $.get('productType', function(data){
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