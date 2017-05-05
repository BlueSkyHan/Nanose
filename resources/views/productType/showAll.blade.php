<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">产品</h3>
        @if(Auth::check() && Auth::user()->isAdmin())
        <a href="#" id="productType-create-button" class="pull-right"><i class='fa fa-plus' style='color:#00a65a'></i></a>
        @endif
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="col-md-12">
            @if(count($productTypes)>0)
            <div class="box-group" id="productTypes">
                @foreach($productTypes as $productType)
                <div class="panel box box-info">
                    <div class="box-header with-border">
                        @if(Auth::check() && Auth::user()->isAdmin())
                            <a href="#" class="product-create-button pull-left"><i class='fa fa-plus' style='color:#00a65a'></i></a>
                            <span>&nbsp</span>
                        @endif
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#productTypes" href="#productType-id-{{$productType->id}}" class="collapsed" aria-expanded="false">
                                {{$productType->name}}
                            </a>
                        </h4>
                        @if(Auth::check() && Auth::user()->isAdmin())
                            <a href="#" class="productType-delete-button pull-right" data-token='{{ csrf_token() }}'><i class='fa fa-times' style='color:#dd4b39'></i></a>
                        @endif
                    </div>
                    <div id="productType-id-{{$productType->id}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="box-body no-padding">
                            <div class="col-md-12">
                                <table class="products table table-hover text-center" style="color: #4e4e4e; margin-bottom: 0;">
                                    <tbody>
                                        <tr>
                                            @if(Auth::check() && Auth::user()->isAdmin())
                                            <th>
                                                <a href="#" class="productType-edit-button pull-left"><i class='fa fa-pencil' style='color:#f39c12'></i></a>
                                            </th>
                                            @endif
                                            <th>规格</th>
                                            <th>价格(元)</th>
                                            @foreach($productType->attributes()->orderBy('id')->get() as $attribute)
                                            <th class="product-attributes">{{$attribute->name}}</th>
                                            @endforeach
                                            @if(Auth::check() && Auth::user()->isAdmin())
                                            <th></th>
                                            @endif
                                        </tr>
                                        @include('product.showAll', array('products'=>$productType->products()->orderBy('price', 'DESC')->get()))
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-muted">暂无</p>
            @endif
        </div>
    </div>
    <!-- /.box-body -->
</div>

@if(Auth::check() && Auth::user()->isAdmin())
{{--product create form--}}
<div class="modal fade" id="product-create-form-modal" tabindex="-1" role="dialog" aria-labelledby="productCreateFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="box box-success">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">新规格</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('product-name', '名称') !!}
                        {!! Form::text('product-name', null, array('placeholder'=>'请输入名称', 'id'=>'product-name-create-input', 'class'=>'form-control')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('product-price', '价格') !!}
                        {!! Form::text('product-price', null, array('placeholder'=>'请输入价格', 'id'=>'product-price-create-input', 'class'=>'form-control')) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('关闭', array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')) !!}
                    {!! Form::button('添加', array('id'=>'product-create-form-button', 'class'=>'btn btn-success', 'data-token'=>csrf_token())) !!}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>
{{--end form--}}

{{--product edit form--}}
<div class="modal fade" id="product-edit-form-modal" tabindex="-1" role="dialog" aria-labelledby="productEditFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="box box-info">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">现有规格</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('product-name', '名称') !!}
                        {!! Form::text('product-name', null, array('placeholder'=>'请输入名称', 'id'=>'product-name-edit-input', 'class'=>'form-control')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('product-price', '价格') !!}
                        {!! Form::text('product-price', null, array('placeholder'=>'请输入价格', 'id'=>'product-price-edit-input', 'class'=>'form-control')) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('关闭', array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')) !!}
                    {!! Form::button('更新', array('id'=>'product-edit-form-button', 'class'=>'btn btn-info', 'data-token'=>csrf_token())) !!}
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

        $('#productType-create-button').click(function(e){
            e.preventDefault();
            $.get('productType/create', function(data){
                $('section.content').html(data);
            });
        });

        $('#productTypes').on('click', 'div div.box-header a.product-create-button', function(e){
            e.preventDefault();
            var modalBody = $('#product-create-form-modal .modal-body');
            modalBody.find(".form-group.product-productTypeId-create-input").remove();
            modalBody.find(".form-group.product-attribute-create-input").remove();
            modalBody.find(".form-group").find('input').val(null);
            modalBody.find(".form-group").removeClass('has-error').removeClass('has-success');
            modalBody.find(".form-group").find('span.help-block').remove();
            var productTypeIdInput =
                "<div class='form-group product-productTypeId-create-input'>" +
                "<input type='hidden' name='product-productTypeId' value='" +
                $(this).parent().next().prop('id').replace('productType-id-', '') +
                "' class='form-control'></div>";
            modalBody.append(productTypeIdInput);
            var productAttributes = $(this).parent().next().find('th.product-attributes');
            productAttributes.each(function(){
                var attributeName = $(this).text().trim();
                var attributeInput =
                    "<div class='form-group product-attribute-create-input'>" +
                    "<label for='product-" + attributeName + "'>" + attributeName + "</label>" +
                    "<input type='text' name='product-" + attributeName +
                    "' class='form-control' placeholder='请输入" + attributeName + "值'></div>";
                modalBody.append(attributeInput);
            });
            $('#product-create-form-modal').modal('show');
        });

        $('#productTypes').on('click', 'div div.box-body a.productType-edit-button', function(e){
            e.preventDefault();
            var productTypeId = $(this).closest('.box-body').parent().prop('id').replace('productType-id-', '');
            $.get('productType/' + productTypeId + '/edit', function(data){
                $('section.content').html(data);
            });
        });

        $('#productTypes').on('click', 'div div.box-header a.productType-delete-button', function(e){
            e.preventDefault();
            var productTypeId = $(this).parent().next().prop('id').replace('productType-id-', '');
            var token = $(this).data('token');
            $.ajax({
                url: 'productType/' + productTypeId,
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

        $(document).off('input', '#product-create-form-modal div.modal-body div.form-group input');

        $(document).on('input', '#product-create-form-modal div.modal-body div.form-group input', function(){
            var input = $(this).val();
            var inputName = $(this).prop('name').replace('product-', '');
            if(inputName == 'name'){
                inputName = '名称';
            }else if(inputName == 'price'){
                inputName = '价格';
            }
            if(input.trim() == ''){
                $(this).parent().removeClass('has-success').addClass('has-error');
                $(this).next().remove();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-success');
                $(this).next().remove();
            }
        });

        $(document).off('input', '#product-edit-form-modal div.modal-body div.form-group input');

        $(document).on('input', '#product-edit-form-modal div.modal-body div.form-group input', function(){
            var input = $(this).val();
            var inputName = $(this).prop('name').replace('product-', '');
            if(inputName == 'name'){
                inputName = '名称';
            }else if(inputName == 'price'){
                inputName = '价格';
            }else{
                inputName += '值';
            }
            if(input.trim() == ''){
                $(this).parent().removeClass('has-info').addClass('has-error');
                $(this).next().remove();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-info');
                $(this).next().remove();
            }
        });

        $('#product-create-form-button').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            $("#product-create-form-modal").find(".modal-body .form-group input").each(function(){
                if($(this).val().trim() == ''){
                    isFormValid = false;
                    var inputName = $(this).prop('name').replace('product-', '');
                    if(inputName == 'name'){
                        inputName = '名称';
                    }else if(inputName == 'price'){
                        inputName = '价格';
                    }
                    $(this).parent().removeClass('has-success').addClass('has-error');
                    $(this).next().remove();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
                }
            });
            if(!isFormValid){
                return;
            }
            var productInputs = $(this).closest('.modal-content').find(".modal-body .form-group");
            var productName = productInputs.find("input[name='product-name']").val();
            var productPrice = productInputs.find("input[name='product-price']").val();
            var attributeValues = {};
            $("#product-create-form-modal").find(".modal-body .form-group.product-attribute-create-input input").each(function(){
                var attributeName = $(this).prop('name').replace('product-', '');
                var attributeValue = $(this).val().trim();
                attributeValues[attributeName] = attributeValue;
            });
            var productTypeId = $('#product-create-form-modal').find("input[name='product-productTypeId']").val();
            var token = $(this).data('token');
            $.ajax({
                url: 'productType/' + productTypeId + '/product',
                type: 'POST',
                data: {
                    _token : token,
                    'product-name': productName,
                    'product-price': productPrice,
                    'product-attributeValues' : attributeValues
                },
                success: function(data){
                    $('#productType-id-' + productTypeId).find('.products tbody .product').remove();
                    $('#productType-id-' + productTypeId).find('.products tbody').append(data);
                    $('#product-create-form-modal').modal('hide');
                }
            });
        });

        $('#product-edit-form-button').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            $("#product-edit-form-modal").find(".modal-body .form-group input").each(function(){
                if($(this).val().trim() == ''){
                    isFormValid = false;
                    var inputName = $(this).prop('name').replace('product-', '');
                    if(inputName == 'name'){
                        inputName = '名称';
                    }else if(inputName == 'price'){
                        inputName = '价格';
                    }else{
                        inputName += "值";
                    }
                    $(this).parent().removeClass('has-info').addClass('has-error');
                    $(this).next().remove();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
                }
            });
            if(!isFormValid){
                return;
            }
            var attributeValues = {};
            $("#product-edit-form-modal").find(".modal-body .form-group.product-attribute-edit-input input").each(function(){
                var attributeName = $(this).prop('name').replace('product-', '');
                var attributeValue = $(this).val().trim();
                attributeValues[attributeName] = attributeValue;
            });
            var productTypeId = $('#product-edit-form-modal').find(".modal-body").prop('id').replace('product-productTypeId-', '');
            var productInputs = $(this).closest('.modal-content').find(".modal-body .form-group");
            var productName = productInputs.find("input[name='product-name']").val();
            var productPrice = productInputs.find("input[name='product-price']").val();
            var productId = productInputs.find("input[name='product-id']").val();
            var token = $(this).data('token');
            $.ajax({
                url: 'productType/' + productTypeId + '/product/' + productId,
                type: 'POST',
                data: {
                    _method: 'PATCH',
                    _token : token,
                    'product-name': productName,
                    'product-price': productPrice,
                    'product-attributeValues' : attributeValues
                },
                success: function(data){
                    $('#productType-id-' + productTypeId).find('.products tbody .product').remove();
                    $('#productType-id-' + productTypeId).find('.products tbody').append(data);
                    $('#product-edit-form-modal').modal('hide');
                }
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
    table th {
        border-top: none !important;
    }
</style>
@endif