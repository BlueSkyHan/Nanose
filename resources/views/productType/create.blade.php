<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">新产品</h3>
    </div>
    <!-- /.box-header -->

    <!-- form start -->
    {!! Form::open(['action'=>'ProductTypeController@store', 'method'=>'POST', 'id'=>'productType-create-form', 'role'=>'form']) !!}
    <div class="box-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name', '名称') !!}
                        {!! Form::text('name', isset($productType) ? $productType['name'] : null, array('placeholder'=>'请输入名称', 'id'=>'productType-name-create-input', 'class'=>'form-control')) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('attribute-name', '属性') !!}&nbsp
                        <a id='attribute-create-button' href="#" data-toggle='modal' data-target='#attribute-create-form-modal'>
                            <i class="fa fa-plus" style='color:#00a65a'></i>
                        </a>
                        <ul id="attributes" class="list-unstyled">
                        @if(isset($productType))
                            @foreach($productType['attributes'] as $attribute)
                            <li>
                                <span>{{$attribute}}</span>&nbsp
                                <a class='attribute-remove-button' href='#'>
                                    <i class='fa fa-times' style='color:#dd4b39'></i>
                                </a>
                            </li>
                            @endforeach
                        @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-4">
            {!! Form::button('返回', array('id'=>'productType-create-form-back', 'class'=>'btn btn-default')) !!}
            {!! Form::submit('添加', array('id'=>'productType-create-form-submit', 'class'=>'btn btn-success pull-right')) !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>

{{--productType attribute form--}}
<div class="modal fade" id="attribute-create-form-modal" tabindex="-1" role="dialog" aria-labelledby="attributeFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="box box-success">
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
                        {!! Form::text('attribute-name', null, array('placeholder'=>'请输入名称', 'id'=>'attribute-name-create-input', 'class'=>'form-control')) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('关闭', array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')) !!}
                    {!! Form::button('添加', array('id'=>'attribute-create-form-button', 'class'=>'btn btn-success', 'data-dismiss'=>'modal')) !!}
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

        var attributeNameCreateInput = $("#attribute-name-create-input");

        $("#productType-name-create-input").on('input', function(){
            var input = $(this).val();
            if(input.trim() == ''){
                $(this).parent().removeClass('has-success').addClass('has-error');
                $(this).next().remove();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> 名称不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-success');
                $(this).next().remove();
            }
        });

        attributeNameCreateInput.on('input', function(){
            var input = $(this).val();
            if(input.trim() == ''){
                $(this).parent().removeClass('has-success').addClass('has-error');
                $(this).next().remove();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> 名称不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-success');
                $(this).next().remove();
            }
        });

        $('#attribute-create-button').click(function(){
            attributeNameCreateInput.val(null);
            attributeNameCreateInput.next().remove();
            attributeNameCreateInput.parent().removeClass('has-success').removeClass('has-error');
        });

        $('#attribute-create-form-button').click(function(){
            var attribute = $('#attribute-name-create-input').val();
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
                $('#attributes').parent().removeClass('has-error').addClass('has-success');
                $('#attributes').next().remove();
                $('#attributes').append("<li><span>" + attribute +
                    "</span>&nbsp<a class='attribute-remove-button' href='#'><i class='fa fa-times' style='color:#dd4b39'></i></a></li>");
            }
        });

        $('#attributes').on('click', 'li a.attribute-remove-button', function(){
            $(this).parent().remove();
            if($("#attributes li").length == 0){
                $("#attributes").parent().removeClass('has-success').addClass('has-error');
                $("#attributes").next().remove();
                $("#attributes").parent().append("<span class='help-block'><i class='fa fa-times-circle'> 属性不能为空</i></span>");
            }
        });

        $('#productType-create-form-submit').click(function(e){
            e.preventDefault();
            var hasError = false;
            if($('#productType-name-create-input').val().trim() == ''){
                $('#productType-name-create-input').parent().removeClass('has-success').addClass('has-error');
                $('#productType-name-create-input').next().remove();
                $('#productType-name-create-input').parent().append("<span class='help-block'><i class='fa fa-times-circle'> 名称不能为空</i></span>");
                hasError = true;
            }
            if($("#attributes li").length == 0){
                $("#attributes").parent().removeClass('has-success').addClass('has-error');
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
            $.post('productType',
                $('#productType-create-form').serialize()+'&'+$.param({ 'attributes': attributes }),
                function(data){
                    $('section.content').html(data);
                }
            );
        });

        $('#productType-create-form-back').click(function(e){
            e.preventDefault();
            $.get('productType', function(data){
                $('section.content').html(data);
            });
        });
    });
</script>