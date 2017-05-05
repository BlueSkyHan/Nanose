<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">库存({{!isset($isGift) ? '正' : '赠'}})</h3>
        @if(Auth::check())
        @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
        <a href="#" id="wh-product-create-button" class="pull-right"><i class='fa fa-plus' style='color:#00a65a'></i></a>
        @endif
        @endif
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="col-md-12">
        @if(count($warehouseProductTypes)>0)
            <div class="box-group" id="warehouseProductTypes">
                @foreach($warehouseProductTypes as $productType)
                    <div class="panel box box-info">
                        <div class="box-header with-border">
                            @if(Auth::check())
                                @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
                                    <a href="#" class="wh-product-create-button pull-left"><i class='fa fa-plus' style='color:#00a65a'></i></a>
                                    <span>&nbsp&nbsp</span>
                                @endif
                            @endif
                            <h4 class="box-title">
                                @if(isset($id) && App\Product::findOrFail($id)->productType->id == $productType->id)
                                    <a data-toggle="collapse" data-parent="#warehouseProductTypes" href="#productType-id-{{$productType->id}}" class="" aria-expanded="true">
                                        {{$productType->name}}
                                    </a>
                                @else
                                    <a data-toggle="collapse" data-parent="#warehouseProductTypes" href="#productType-id-{{$productType->id}}" class="collapsed" aria-expanded="false">
                                        {{$productType->name}}
                                    </a>
                                @endif
                            </h4>
                            @if(Auth::check())
                            @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
                            <a href="#" class="wh-productType-delete-button pull-right" data-token='{{ csrf_token() }}'><i class='fa fa-times' style='color:#dd4b39'></i></a>
                            @endif
                            @endif
                        </div>
                        @if(isset($id) && (App\Product::findOrFail($id)->productType->id) == $productType->id)
                        <div id="productType-id-{{$productType->id}}" class="panel-collapse collapse in" aria-expanded="true">
                            <div class="box-body">
                                <div class="box-group" id="warehouseProducts">
                                @if(!isset($isGift))
                                    @php
                                        $productIds = array();
                                        foreach($warehouse->products()->where('product_type_id', $productType->id)->orderBy('name')->get() as $product){
                                            if(!in_array($product->id, $productIds)){
                                                $productIds[] = $product->id;
                                            }
                                        }
                                    @endphp
                                @else
                                    @php
                                        $productIds = array();
                                        foreach($warehouse->gifts()->where('product_type_id', $productType->id)->orderBy('name')->get() as $product){
                                            if(!in_array($product->id, $productIds)){
                                                $productIds[] = $product->id;
                                            }
                                        }
                                    @endphp
                                @endif

                                @foreach($productIds as $productId)
                                    <div class="panel box box-solid">
                                        <div class="box-header with-border" style="padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px;">
                                            @if(Auth::check())
                                                @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
                                                    <a href="#" class="wh-product-create-button-2 pull-left"><i class='fa fa-plus' style='color:#00a65a'></i></a>
                                                    <span>&nbsp&nbsp</span>
                                                @endif
                                            @endif
                                            <h4 class="box-title">
                                                @if(isset($id) && $productId == $id)
                                                    <a data-toggle="collapse" data-parent="#warehouseProducts" href="#product-id-{{$productId}}" aria-expanded="true" class="" style="font-size: 18px;">
                                                        {{App\Product::findOrFail($productId)->name}}
                                                    </a>
                                                @else
                                                    <a data-toggle="collapse" data-parent="#warehouseProducts" href="#product-id-{{$productId}}" aria-expanded="false" class="collapsed" style="font-size: 18px;">
                                                        {{App\Product::findOrFail($productId)->name}}
                                                    </a>
                                                @endif
                                            </h4>
                                            @if(Auth::check())
                                            @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
                                            <a href="#" class="wh-product-delete-button pull-right" data-token='{{ csrf_token() }}'><i class='fa fa-times' style='color:#dd4b39'></i></a>
                                            @endif
                                            @endif
                                        </div>
                                        @if(!isset($isGift))
                                            @if(isset($id) && $productId == $id)
                                                <div id="product-id-{{$productId}}" class="panel-collapse collapse in" aria-expanded="true">
                                                    <div class="box-body no-padding">
                                                        @include('warehouse.product.show', array('products'=>$warehouse->products()->where('product_id', $productId)->orderBy('pivot_created_at', 'DESC')->get()))
                                                    </div>
                                                </div>
                                            @else
                                                <div id="product-id-{{$productId}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                    <div class="box-body no-padding">
                                                        @include('warehouse.product.show', array('products'=>$warehouse->products()->where('product_id', $productId)->orderBy('pivot_created_at', 'DESC')->get()))
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            @if(isset($id) && $productId == $id)
                                                <div id="product-id-{{$productId}}" class="panel-collapse collapse in" aria-expanded="true">
                                                    <div class="box-body no-padding">
                                                        @include('warehouse.product.show', array('products'=>$warehouse->gifts()->where('product_id', $productId)->orderBy('pivot_created_at', 'DESC')->get()))
                                                    </div>
                                                </div>
                                            @else
                                                <div id="product-id-{{$productId}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                    <div class="box-body no-padding">
                                                        @include('warehouse.product.show', array('products'=>$warehouse->gifts()->where('product_id', $productId)->orderBy('pivot_created_at', 'DESC')->get()))
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                        @else
                        <div id="productType-id-{{$productType->id}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="box-body">
                                <div class="box-group" id="warehouseProducts">
                                    @if(!isset($isGift))
                                        @php
                                            $productIds = array();
                                            foreach($warehouse->products()->where('product_type_id', $productType->id)->orderBy('name')->get() as $product){
                                                if(!in_array($product->id, $productIds)){
                                                    $productIds[] = $product->id;
                                                }
                                            }
                                        @endphp
                                    @else
                                        @php
                                            $productIds = array();
                                            foreach($warehouse->gifts()->where('product_type_id', $productType->id)->orderBy('name')->get() as $product){
                                                if(!in_array($product->id, $productIds)){
                                                    $productIds[] = $product->id;
                                                }
                                            }
                                        @endphp
                                    @endif
                                    @foreach($productIds as $productId)
                                        <div class="panel box box-solid">
                                            <div class="box-header with-border" style="padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px;">
                                                @if(Auth::check())
                                                    @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
                                                        <a href="#" class="wh-product-create-button-2 pull-left"><i class='fa fa-plus' style='color:#00a65a'></i></a>
                                                        <span>&nbsp&nbsp</span>
                                                    @endif
                                                @endif
                                                <h4 class="box-title">
                                                    @if(isset($id) && $productId == $id)
                                                        <a data-toggle="collapse" data-parent="#warehouseProducts" href="#product-id-{{$productId}}" aria-expanded="true" class="" style="font-size: 18px;">
                                                            {{App\Product::findOrFail($productId)->name}}
                                                        </a>
                                                    @else
                                                        <a data-toggle="collapse" data-parent="#warehouseProducts" href="#product-id-{{$productId}}" aria-expanded="false" class="collapsed" style="font-size: 18px;">
                                                            {{App\Product::findOrFail($productId)->name}}
                                                        </a>
                                                    @endif
                                                </h4>
                                                @if(Auth::check())
                                                @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
                                                <a href="#" class="wh-product-delete-button pull-right" data-token='{{ csrf_token() }}'><i class='fa fa-times' style='color:#dd4b39'></i></a>
                                                @endif
                                                @endif
                                            </div>
                                            @if(!isset($isGift))
                                                @if(isset($id) && $productId == $id)
                                                    <div id="product-id-{{$productId}}" class="panel-collapse collapse in" aria-expanded="true">
                                                        <div class="box-body no-padding">
                                                            @include('warehouse.product.show', array('products'=>$warehouse->products()->where('product_id', $productId)->orderBy('pivot_created_at', 'DESC')->get()))
                                                        </div>
                                                    </div>
                                                @else
                                                    <div id="product-id-{{$productId}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                        <div class="box-body no-padding">
                                                            @include('warehouse.product.show', array('products'=>$warehouse->products()->where('product_id', $productId)->orderBy('pivot_created_at', 'DESC')->get()))
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                @if(isset($id) && $productId == $id)
                                                    <div id="product-id-{{$productId}}" class="panel-collapse collapse in" aria-expanded="true">
                                                        <div class="box-body no-padding">
                                                            @include('warehouse.product.show', array('products'=>$warehouse->gifts()->where('product_id', $productId)->orderBy('pivot_created_at', 'DESC')->get()))
                                                        </div>
                                                    </div>
                                                @else
                                                    <div id="product-id-{{$productId}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                        <div class="box-body no-padding">
                                                            @include('warehouse.product.show', array('products'=>$warehouse->gifts()->where('product_id', $productId)->orderBy('pivot_created_at', 'DESC')->get()))
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
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

@if(Auth::check())
@if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
{{--product create form--}}
<div class="modal fade" id="wh-product-create-form-modal" tabindex="-1" role="dialog" aria-labelledby="productCreateFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="box box-success">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">新库存</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    {!! Form::button('关闭', array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')) !!}
                    {!! Form::button('添加', array('data-token'=>csrf_token(), 'id'=>'wh-product-create-form-button', 'class'=>'btn btn-success')) !!}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>
{{--end form--}}

{{--product edit form--}}
<div class="modal fade" id="wh-product-edit-form-modal" tabindex="-1" role="dialog" aria-labelledby="productEditFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="box box-info">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">现有库存</h4>
                </div>
                <div class="modal-body">
                    <div>
                        {!! Form::hidden('product-id', null, array('id'=>'product-id-edit-input', 'class'=>'form-control')) !!}
                    </div>
                    <div class='form-group'>
                        {!! Form::label('product-batch-number', '批号') !!}
                        {!! Form::text('product-batch-number', null, array('id'=>'product-batch-number-edit-input', 'class'=>'form-control', 'disabled'=>'')) !!}
                    </div>
                    <div class='form-group'>
                        {!! Form::label('product-production-date', '生产日期') !!}
                        {!! Form::date('product-production-date', null, array('id'=>'product-production-date-edit-input', 'class'=>'form-control', 'disabled'=>'')) !!}
                    </div>
                    <div class='form-group'>
                        {!! Form::label('product-quantity', '数量') !!}
                        {!! Form::text('product-quantity', null, array('placeholder'=>'请输入数量', 'id'=>'product-quantity-edit-input', 'class'=>'form-control edit-input', 'maxlength'=>'4')) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('关闭', array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')) !!}
                    {!! Form::button('更新', array('data-token'=>csrf_token(), 'id'=>'wh-product-edit-form-button', 'class'=>'btn btn-info')) !!}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>
{{--end form--}}
@endif
@endif

@if(Auth::check())
@if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
@if(!isset($isGift))
<script>
    $(function(){
        var whProductCreateFormModal = $('#wh-product-create-form-modal');

        var whProductEditFormModal = $('#wh-product-edit-form-modal');

        var dateRangePicker = $('.daterangepicker');
        if(dateRangePicker.length){
            dateRangePicker.remove();
        }

        $(document).off('input', 'input');

        $(document).off('input', '.create-input');

        $(document).on('input', '.create-input', function(){
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

        $(document).off('input', '#product-quantity-create-input');

        $(document).on('input', '#product-quantity-create-input', function(){
            var input = $(this).val().trim();
            if(input != ''){
                if(!$.isNumeric(input) || input <= 0){
                    $(this).parent().removeClass('has-success').addClass('has-error');
                    $(this).next().remove();
                    var inputName = $(this).prev().text();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "必须为正数</i></span>");
                }else{
                    $(this).parent().removeClass('has-error').addClass('has-success');
                    $(this).next().remove();
                }
            }
        });

        $('.edit-input').off('input');

        $('.edit-input').on('input', function(){
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

        $('#product-quantity-edit-input').on('input', function(){
            var input = $(this).val().trim();
            if(input != ''){
                if (!$.isNumeric(input) || input < 0) {
                    $(this).parent().removeClass('has-info').addClass('has-error');
                    $(this).next().remove();
                    var inputName = $(this).prev().text();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "必须为正数或零</i></span>");
                } else {
                    $(this).parent().removeClass('has-error').addClass('has-info');
                    $(this).next().remove();
                }
            }
        });

        $(document).off('change', 'select');

        $(document).on('change', 'select', function(){
            var input = $(this).find("option:selected").val();
            $(this).parent().find('span.help-block').remove();
            if(input == ''){
                $(this).parent().removeClass('has-success').addClass('has-error');
                var inputName = $(this).parent().find('label').text();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-success');
            }
        });

        $(document).off('change', '#wh-productType-create-select');

        $(document).on('change', '#wh-productType-create-select', function(){
            var self = $(this);
            var productTypeId = self.find("option:selected").val();
            whProductCreateFormModal.find(".form-group").slice(1).remove();
            if(productTypeId != ''){
                $.get('productType/' + productTypeId + '/products', function(data){
                    whProductCreateFormModal.find('.modal-body').append(data);
                }).done(function(){
                    var batchNumberInput =
                        "<div class='form-group'>" +
                        '{!! Form::label('product-batch-number', '批号') !!}' +
                        '{!! Form::text('product-batch-number', null, array('placeholder'=>'请输入批号', 'id'=>'product-batch-number-create-input', 'class'=>'form-control create-input')) !!}' +
                        "</div>";
                    var productionDateInput =
                        "<div class='form-group'>" +
                        '{!! Form::label('product-production-date', '生产日期') !!}' +
                        '{!! Form::date('product-production-date', null, array('id'=>'product-production-date-create-input', 'class'=>'form-control create-input')) !!}' +
                        "</div>";
                    var quantityInput =
                        "<div class='form-group'>" +
                        '{!! Form::label('product-quantity', '数量') !!}' +
                        '{!! Form::text('product-quantity', null, array('placeholder'=>'请输入数量', 'id'=>'product-quantity-create-input', 'class'=>'form-control create-input', 'maxlength'=>'4')) !!}' +
                        "</div>";
                    whProductCreateFormModal.find('.modal-body').append(batchNumberInput).append(productionDateInput).append(quantityInput);
                    $('#wh-product-create-form-button').show();
                });
            }else{
                $('#wh-product-create-form-button').hide();
            }
        });

        $('#wh-product-create-button').click(function(e){
            e.preventDefault();
            whProductCreateFormModal.find('.form-group').remove();
            var productTypeSelect =
                "<div class='form-group'>" +
                '{!! Form::label('productType', '品牌') !!}' +
                '{!! Form::select('productType', array(''=>'请选择品牌') + $productTypes, null, array('id'=>'wh-productType-create-select', 'class'=>'form-control')) !!}' +
                "</div>";
            whProductCreateFormModal.find('.modal-body').append(productTypeSelect);
            whProductCreateFormModal.modal('show');
            $('#wh-product-create-form-button').hide();
        });

        $('.wh-product-create-button').click(function(e){
            e.preventDefault();
            whProductCreateFormModal.find(".form-group").remove();
            var productTypeId = $(this).closest('.box-header').next().prop('id').replace('productType-id-', '');
            $.get('productType/' + productTypeId + '/products', function(data){
                var batchNumberInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-batch-number', '批号') !!}' +
                    '{!! Form::text('product-batch-number', null, array('placeholder'=>'请输入批号', 'id'=>'product-batch-number-create-input', 'class'=>'form-control create-input')) !!}' +
                    "</div>";
                var productionDateInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-production-date', '生产日期') !!}' +
                    '{!! Form::date('product-production-date', null, array('id'=>'product-production-date-create-input', 'class'=>'form-control create-input')) !!}' +
                    "</div>";
                var quantityInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-quantity', '数量') !!}' +
                    '{!! Form::text('product-quantity', null, array('placeholder'=>'请输入数量', 'id'=>'product-quantity-create-input', 'class'=>'form-control create-input', 'maxlength'=>'4')) !!}' +
                    "</div>";
                whProductCreateFormModal.find(".modal-body").append(data).append(batchNumberInput).append(productionDateInput).append(quantityInput);
                $('#wh-product-create-form-button').show();
                whProductCreateFormModal.modal('show');
            });
        });

        $('.wh-product-create-button-2').click(function(e){
            e.preventDefault();
            var self = $(this);
            whProductCreateFormModal.find(".form-group").remove();
            var productTypeId = $(this).closest('.box-body').parent().prop('id').replace('productType-id-', '');
            $.get('productType/' + productTypeId + '/products', function(data){
                var batchNumberInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-batch-number', '批号') !!}' +
                    '{!! Form::text('product-batch-number', null, array('placeholder'=>'请输入批号', 'id'=>'product-batch-number-create-input', 'class'=>'form-control create-input')) !!}' +
                    "</div>";
                var productionDateInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-production-date', '生产日期') !!}' +
                    '{!! Form::date('product-production-date', null, array('id'=>'product-production-date-create-input', 'class'=>'form-control create-input')) !!}' +
                    "</div>";
                var quantityInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-quantity', '数量') !!}' +
                    '{!! Form::text('product-quantity', null, array('placeholder'=>'请输入数量', 'id'=>'product-quantity-create-input', 'class'=>'form-control create-input', 'maxlength'=>'4')) !!}' +
                    "</div>";
                whProductCreateFormModal.find(".modal-body").append(data).append(batchNumberInput).append(productionDateInput).append(quantityInput);
                $('#product-create-select').val(self.parent().next().prop('id').replace('product-id-','')).parent().hide();
                $('#wh-product-create-form-button').show();
                whProductCreateFormModal.modal('show');
            });
        });

        $('#wh-product-create-form-button').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            whProductCreateFormModal.find("input").each(function(){
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
            whProductCreateFormModal.find("select").each(function(){
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
            var productQuantityCreateInput = $('#product-quantity-create-input');
            var productQuantity = productQuantityCreateInput.val().trim();
            if(productQuantity != ''){
                if(!$.isNumeric(productQuantity) || productQuantity <= 0){
                    $(productQuantityCreateInput).parent().removeClass('has-success').addClass('has-error');
                    $(productQuantityCreateInput).next().remove();
                    var inputName = $(productQuantityCreateInput).prev().text();
                    $(productQuantityCreateInput).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "必须为正数</i></span>");
                    isFormValid = false;
                }else{
                    $(productQuantityCreateInput).parent().removeClass('has-error').addClass('has-success');
                    $(productQuantityCreateInput).next().remove();
                }
            }
            if(!isFormValid){
                return;
            }
            var productId = $('#product-create-select').find("option:selected").val();
            var quantity = productQuantity;
            var batchNumber = $('#product-batch-number-create-input').val();
            var productionDate = $('#product-production-date-create-input').val();
            var token = $(this).data('token');
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/warehouse/' + '{{$warehouse->id}}' + '/product',
                type: 'POST',
                data: {
                    _method: 'POST',
                    _token : token,
                    'product_id': productId,
                    'quantity' : quantity,
                    'batch_number': batchNumber,
                    'production_date': productionDate
                },
                success: function(data){
                    $('section.content').html(data);
                    whProductCreateFormModal.modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            });
        });

        $('.wh-product-quantity-edit-button').click(function(e){
            e.preventDefault();
            $('.edit-input').parent().removeClass('has-error').removeClass('has-success').removeClass('has-info');
            $('.edit-input').next().remove();
            var tr = $(this).closest('tr');
            var productId = tr.closest('.panel-collapse').prop('id').replace('product-id-', '');
            var batchNumber = tr.children().eq(0).text();
            var productionDate = tr.children().eq(1).text();
            var quantity = tr.children().eq(2).text();
            console.log(productionDate);
            whProductEditFormModal.find('#product-id-edit-input').val(productId);
            whProductEditFormModal.find('#product-batch-number-edit-input').val(batchNumber);
            whProductEditFormModal.find('#product-production-date-edit-input').val(productionDate);
            whProductEditFormModal.find('#product-quantity-edit-input').val(quantity);
            whProductEditFormModal.modal('show');
        });

        $('#wh-product-edit-form-button').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            whProductEditFormModal.find("input").each(function(){
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
            var productQuantityEditInput = $('#product-quantity-edit-input');
            var productQuantity = productQuantityEditInput.val().trim();
            if(productQuantity != ''){
                if (!$.isNumeric(productQuantity) || productQuantity < 0) {
                    $(productQuantityEditInput).parent().removeClass('has-info').addClass('has-error');
                    $(productQuantityEditInput).next().remove();
                    var inputName = $(productQuantityEditInput).prev().text();
                    $(productQuantityEditInput).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "必须为正数或零</i></span>");
                    isFormValid = false;
                } else {
                    $(productQuantityEditInput).parent().removeClass('has-error').addClass('has-info');
                    $(productQuantityEditInput).next().remove();
                }
            }
            if(!isFormValid){
                return;
            }
            var productId = whProductEditFormModal.find('#product-id-edit-input').val();
            var batchNumber = whProductEditFormModal.find('#product-batch-number-edit-input').val();
            var quantity = whProductEditFormModal.find('#product-quantity-edit-input').val();
            var token = $(this).data('token');
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/warehouse/' + '{{$warehouse->id}}' + '/product/' + productId,
                type: 'POST',
                data: {
                    _method: 'PATCH',
                    _token : token,
                    'batch_number': batchNumber,
                    'quantity' : quantity
                },
                success: function(data){
                    $('section.content').html(data);
                    whProductEditFormModal.modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            });
        });

        $('.wh-productType-delete-button').click(function(e){
            e.preventDefault();
            var productTypeId = $(this).parent().next().prop('id').replace('productType-id-', '');
            var token = $(this).data('token');
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/warehouse/' + '{{$warehouse->id}}' + '/product/productType/' + productTypeId,
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

        $('.wh-product-delete-button').click(function(e){
            e.preventDefault();
            var productId = $(this).parent().next().prop('id').replace('product-id-', '');
            var token = $(this).data('token');
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/warehouse/' + '{{$warehouse->id}}' + '/product/' + productId,
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
@else
<script>
    $(function(){
        var whProductCreateFormModal = $('#wh-product-create-form-modal');

        var whProductEditFormModal = $('#wh-product-edit-form-modal');

        var dateRangePicker = $('.daterangepicker');
        if(dateRangePicker.length){
            dateRangePicker.remove();
        }

        $(document).off('input', 'input');

        $(document).off('input', '.create-input');

        $(document).on('input', '.create-input', function(){
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

        $(document).off('input', '#product-quantity-create-input');

        $(document).on('input', '#product-quantity-create-input', function(){
            var input = $(this).val().trim();
            if(input != ''){
                if(!$.isNumeric(input) || input <= 0){
                    $(this).parent().removeClass('has-success').addClass('has-error');
                    $(this).next().remove();
                    var inputName = $(this).prev().text();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "必须为正数</i></span>");
                }else{
                    $(this).parent().removeClass('has-error').addClass('has-success');
                    $(this).next().remove();
                }
            }
        });

        $('.edit-input').off('input');

        $('.edit-input').on('input', function(){
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

        $('#product-quantity-edit-input').on('input', function(){
            var input = $(this).val().trim();
            if(input != ''){
                if (!$.isNumeric(input) || input < 0) {
                    $(this).parent().removeClass('has-info').addClass('has-error');
                    $(this).next().remove();
                    var inputName = $(this).prev().text();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "必须为正数或零</i></span>");
                } else {
                    $(this).parent().removeClass('has-error').addClass('has-info');
                    $(this).next().remove();
                }
            }
        });

        $(document).off('change', 'select');

        $(document).on('change', 'select', function(){
            var input = $(this).find("option:selected").val();
            $(this).parent().find('span.help-block').remove();
            if(input == ''){
                $(this).parent().removeClass('has-success').addClass('has-error');
                var inputName = $(this).parent().find('label').text();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
            }else{
                $(this).parent().removeClass('has-error').addClass('has-success');
            }
        });

        $(document).off('change', '#wh-productType-create-select');

        $(document).on('change', '#wh-productType-create-select', function(){
            var self = $(this);
            var productTypeId = self.find("option:selected").val();
            whProductCreateFormModal.find(".form-group").slice(1).remove();
            if(productTypeId != ''){
                $.get('productType/' + productTypeId + '/products', function(data){
                    whProductCreateFormModal.find('.modal-body').append(data);
                }).done(function(){
                    var batchNumberInput =
                        "<div class='form-group'>" +
                        '{!! Form::label('product-batch-number', '批号') !!}' +
                        '{!! Form::text('product-batch-number', null, array('placeholder'=>'请输入批号', 'id'=>'product-batch-number-create-input', 'class'=>'form-control create-input')) !!}' +
                        "</div>";
                    var productionDateInput =
                        "<div class='form-group'>" +
                        '{!! Form::label('product-production-date', '生产日期') !!}' +
                        '{!! Form::date('product-production-date', null, array('id'=>'product-production-date-create-input', 'class'=>'form-control create-input')) !!}' +
                        "</div>";
                    var quantityInput =
                        "<div class='form-group'>" +
                        '{!! Form::label('product-quantity', '数量') !!}' +
                        '{!! Form::text('product-quantity', null, array('placeholder'=>'请输入数量', 'id'=>'product-quantity-create-input', 'class'=>'form-control create-input', 'maxlength'=>'4')) !!}' +
                        "</div>";
                    whProductCreateFormModal.find('.modal-body').append(batchNumberInput).append(productionDateInput).append(quantityInput);
                    $('#wh-product-create-form-button').show();
                });
            }else{
                $('#wh-product-create-form-button').hide();
            }
        });

        $('#wh-product-create-button').click(function(e){
            e.preventDefault();
            whProductCreateFormModal.find('.form-group').remove();
            var productTypeSelect =
                "<div class='form-group'>" +
                '{!! Form::label('productType', '品牌') !!}' +
                '{!! Form::select('productType', array(''=>'请选择品牌') + $productTypes, null, array('id'=>'wh-productType-create-select', 'class'=>'form-control')) !!}' +
                "</div>";
            whProductCreateFormModal.find('.modal-body').append(productTypeSelect);
            whProductCreateFormModal.modal('show');
            $('#wh-product-create-form-button').hide();
        });

        $('.wh-product-create-button').click(function(e){
            e.preventDefault();
            whProductCreateFormModal.find(".form-group").remove();
            var productTypeId = $(this).closest('.box-header').next().prop('id').replace('productType-id-', '');
            $.get('productType/' + productTypeId + '/products', function(data){
                var batchNumberInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-batch-number', '批号') !!}' +
                    '{!! Form::text('product-batch-number', null, array('placeholder'=>'请输入批号', 'id'=>'product-batch-number-create-input', 'class'=>'form-control create-input')) !!}' +
                    "</div>";
                var productionDateInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-production-date', '生产日期') !!}' +
                    '{!! Form::date('product-production-date', null, array('id'=>'product-production-date-create-input', 'class'=>'form-control create-input')) !!}' +
                    "</div>";
                var quantityInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-quantity', '数量') !!}' +
                    '{!! Form::text('product-quantity', null, array('placeholder'=>'请输入数量', 'id'=>'product-quantity-create-input', 'class'=>'form-control create-input', 'maxlength'=>'4')) !!}' +
                    "</div>";
                whProductCreateFormModal.find(".modal-body").append(data).append(batchNumberInput).append(productionDateInput).append(quantityInput);
                $('#wh-product-create-form-button').show();
                whProductCreateFormModal.modal('show');
            });
        });

        $('.wh-product-create-button-2').click(function(e){
            e.preventDefault();
            var self = $(this);
            whProductCreateFormModal.find(".form-group").remove();
            var productTypeId = $(this).closest('.box-body').parent().prop('id').replace('productType-id-', '');
            $.get('productType/' + productTypeId + '/products', function(data){
                var batchNumberInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-batch-number', '批号') !!}' +
                    '{!! Form::text('product-batch-number', null, array('placeholder'=>'请输入批号', 'id'=>'product-batch-number-create-input', 'class'=>'form-control create-input')) !!}' +
                    "</div>";
                var productionDateInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-production-date', '生产日期') !!}' +
                    '{!! Form::date('product-production-date', null, array('id'=>'product-production-date-create-input', 'class'=>'form-control create-input')) !!}' +
                    "</div>";
                var quantityInput =
                    "<div class='form-group'>" +
                    '{!! Form::label('product-quantity', '数量') !!}' +
                    '{!! Form::text('product-quantity', null, array('placeholder'=>'请输入数量', 'id'=>'product-quantity-create-input', 'class'=>'form-control create-input', 'maxlength'=>'4')) !!}' +
                    "</div>";
                whProductCreateFormModal.find(".modal-body").append(data).append(batchNumberInput).append(productionDateInput).append(quantityInput);
                $('#product-create-select').val(self.parent().next().prop('id').replace('product-id-','')).parent().hide();
                $('#wh-product-create-form-button').show();
                whProductCreateFormModal.modal('show');
            });
        });

        $('#wh-product-create-form-button').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            whProductCreateFormModal.find("input").each(function(){
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
            whProductCreateFormModal.find("select").each(function(){
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
            var productQuantityCreateInput = $('#product-quantity-create-input');
            var productQuantity = productQuantityCreateInput.val().trim();
            if(productQuantity != ''){
                if(!$.isNumeric(productQuantity) || productQuantity <= 0){
                    $(productQuantityCreateInput).parent().removeClass('has-success').addClass('has-error');
                    $(productQuantityCreateInput).next().remove();
                    var inputName = $(productQuantityCreateInput).prev().text();
                    $(productQuantityCreateInput).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "必须为正数</i></span>");
                    isFormValid = false;
                }else{
                    $(productQuantityCreateInput).parent().removeClass('has-error').addClass('has-success');
                    $(productQuantityCreateInput).next().remove();
                }
            }
            if(!isFormValid){
                return;
            }
            var productId = $('#product-create-select').find("option:selected").val();
            var quantity = productQuantity;
            var batchNumber = $('#product-batch-number-create-input').val();
            var productionDate = $('#product-production-date-create-input').val();
            var token = $(this).data('token');
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/warehouse/' + '{{$warehouse->id}}' + '/gift',
                type: 'POST',
                data: {
                    _method: 'POST',
                    _token : token,
                    'product_id': productId,
                    'quantity' : quantity,
                    'batch_number': batchNumber,
                    'production_date': productionDate
                },
                success: function(data){
                    $('section.content').html(data);
                    whProductCreateFormModal.modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            });
        });

        $('.wh-product-quantity-edit-button').click(function(e){
            e.preventDefault();
            $('.edit-input').parent().removeClass('has-error').removeClass('has-success').removeClass('has-info');
            $('.edit-input').next().remove();
            var tr = $(this).closest('tr');
            var productId = tr.closest('.panel-collapse').prop('id').replace('product-id-', '');
            var batchNumber = tr.children().eq(0).text();
            var productionDate = tr.children().eq(1).text();
            var quantity = tr.children().eq(2).text();
            whProductEditFormModal.find('#product-id-edit-input').val(productId);
            whProductEditFormModal.find('#product-batch-number-edit-input').val(batchNumber);
            whProductEditFormModal.find('#product-production-date-edit-input').val(productionDate);
            whProductEditFormModal.find('#product-quantity-edit-input').val(quantity);
            whProductEditFormModal.modal('show');
        });

        $('#wh-product-edit-form-button').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            whProductEditFormModal.find("input").each(function(){
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
            var productQuantityEditInput = $('#product-quantity-edit-input');
            var productQuantity = productQuantityEditInput.val().trim();
            if(productQuantity != ''){
                if (!$.isNumeric(productQuantity) || productQuantity < 0) {
                    $(productQuantityEditInput).parent().removeClass('has-info').addClass('has-error');
                    $(productQuantityEditInput).next().remove();
                    var inputName = $(productQuantityEditInput).prev().text();
                    $(productQuantityEditInput).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "必须为正数或零</i></span>");
                    isFormValid = false;
                } else {
                    $(productQuantityEditInput).parent().removeClass('has-error').addClass('has-info');
                    $(productQuantityEditInput).next().remove();
                }
            }
            if(!isFormValid){
                return;
            }
            var productId = whProductEditFormModal.find('#product-id-edit-input').val();
            var batchNumber = whProductEditFormModal.find('#product-batch-number-edit-input').val();
            var quantity = whProductEditFormModal.find('#product-quantity-edit-input').val();
            var token = $(this).data('token');
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/warehouse/' + '{{$warehouse->id}}' + '/gift/' + productId,
                type: 'POST',
                data: {
                    _method: 'PATCH',
                    _token : token,
                    'batch_number': batchNumber,
                    'quantity' : quantity
                },
                success: function(data){
                    $('section.content').html(data);
                    whProductEditFormModal.modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            });
        });

        $('.wh-productType-delete-button').click(function(e){
            e.preventDefault();
            var productTypeId = $(this).parent().next().prop('id').replace('productType-id-', '');
            var token = $(this).data('token');
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/warehouse/' + '{{$warehouse->id}}' + '/gift/productType/' + productTypeId,
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

        $('.wh-product-delete-button').click(function(e){
            e.preventDefault();
            var productId = $(this).parent().next().prop('id').replace('product-id-', '');
            var token = $(this).data('token');
            $.ajax({
                url: 'store/' + '{{$store_id}}' + '/warehouse/' + '{{$warehouse->id}}' + '/gift/' + productId,
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
@endif
@endif