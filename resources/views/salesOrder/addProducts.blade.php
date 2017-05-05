<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">新订单</h3>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label('salesOrder-products', '销售品') !!}&nbsp
                <a id='salesOrder-addProducts-button' href="#" data-toggle='modal' data-target='#salesOrder-addProducts-form-modal'>
                    <i class="fa fa-plus" style='color:#00a65a'></i>
                </a>
                @if(isset($products) && count($products) > 0)
                    <table id='salesOrder-products' class='table table-hover text-center' style='color: #4e4e4e;'>
                        <tbody>
                            <tr id='table-head'>
                                <th>仓库</th>
                                <th>品牌</th>
                                <th>规格</th>
                                <th>批号</th>
                                <th>单价(元)</th>
                                <th>数量</th>
                            </tr>
                            @foreach($products as $product)
                            <tr>
                                <td><input type='hidden' name='product-warehouseId' value='{{$product['warehouseId']}}' class='so-product-warehouseId-input'>{{$product['warehouse']}}</td>
                                <td>{{$product['productType']}}</td>
                                <td><input type='hidden' name='product-productId' value='{{$product['productId']}}' class='so-product-productId-input'>{{$product['product']}}</td>
                                <td>{{$product['batchNumber']}}</td>
                                <td>{{$product['price']}}</td>
                                <td>
                                    <input type='text' name='product-quantity' value='{{$product['quantity']}}' class='so-product-quantity-input' maxlength='3' size='3' style='text-align: center; color:#00a65a;' data-max-quantity="{{$product['maxQuantity']}}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-12">
            {!! Form::button('返回', array('id'=>'salesOrder-addProducts-form-back', 'class'=>'btn btn-default')) !!}
            {!! Form::submit('下一步', array('id'=>'salesOrder-addProducts-form-submit', 'class'=>'btn btn-success pull-right')) !!}
        </div>
    </div>
    <!-- /.box-footer -->
</div>

<div class="modal fade" id="salesOrder-addProducts-form-modal" tabindex="-1" role="dialog" aria-labelledby="salesOrder-addProductsFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="box box-success">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">销售品</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('warehouse_id', '仓库') !!}
                        {!! Form::select('warehouse_id', array(''=>'请选择仓库') + $warehouses, null, array('id'=>'so-warehouse-select', 'class'=>'form-control')) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('关闭', array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')) !!}
                    {!! Form::button('添加', array('id'=>'salesOrder-addProducts-form-button', 'class'=>'btn btn-success', 'data-dismiss'=>'modal')) !!}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(function(){
        var productTable = "<table id='salesOrder-products' class='table table-hover text-center' style='color: #4e4e4e;'><tbody><tr id='table-head'><th>仓库</th><th>品牌</th><th>规格</th><th>批号</th><th>单价(元)</th><th>数量</th></tr></tbody></table>";

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

        $(document).off('change', "select");

        $(document).on('change', "select", function(){
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

        var salesOrderAddProductsFormModal = $('#salesOrder-addProducts-form-modal');

        $('#salesOrder-addProducts-button').click(function(e){
            e.preventDefault();
            salesOrderAddProductsFormModal.find('.modal-body .form-group').removeClass('has-error').removeClass('has-success').slice(1).remove();
            $('#so-warehouse-select').val('').next().remove();
            $('#salesOrder-addProducts-form-button').hide();
        });

        $('#so-warehouse-select').change(function(){
            salesOrderAddProductsFormModal.find('.modal-body .form-group').slice(1).remove();
            $('#salesOrder-addProducts-form-button').hide();
            var self = $(this);
            var warehouseId = self.find("option:selected").val();
            if(warehouseId != ''){
                $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/warehouse/' + warehouseId + '/productTypes', function(data){
                    salesOrderAddProductsFormModal.find('.modal-body').append(data);
                });
            }
        });

        $(document).off('change', '#so-productType-select');

        $(document).on('change', '#so-productType-select', function(){
            salesOrderAddProductsFormModal.find('.modal-body .form-group').slice(2).remove();
            $('#salesOrder-addProducts-form-button').hide();
            var self = $(this);
            var warehouseId = $('#so-warehouse-select').find("option:selected").val();
            var productTypeId = self.find("option:selected").val();
            if(productTypeId != ''){
                $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/warehouse/' + warehouseId + '/productTypes/' + productTypeId + '/products', function(data){
                    salesOrderAddProductsFormModal.find('.modal-body').append(data);
                });
            }
        });

        $(document).off('change', '#so-product-select');

        $(document).on('change', '#so-product-select', function(){
            salesOrderAddProductsFormModal.find('.modal-body .form-group').slice(3).remove();
            $('#salesOrder-addProducts-form-button').hide();
            var self = $(this);
            var warehouseId = $('#so-warehouse-select').find("option:selected").val();
            var productTypeId = $('#so-productType-select').find("option:selected").val();
            var productId = self.find("option:selected").val();
            if(productId != ''){
                $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/warehouse/' + warehouseId + '/productTypes/' + productTypeId + '/products/' + productId + '/productBatches', function(data){
                    salesOrderAddProductsFormModal.find('.modal-body').append(data);
                });
            }
        });

        $(document).off('change', '#so-batch-number-select');

        $(document).on('change', '#so-batch-number-select', function(){
            salesOrderAddProductsFormModal.find('.modal-body .form-group').slice(4).remove();
            $('#salesOrder-addProducts-form-button').hide();
            var self = $(this);
            var warehouseId = $('#so-warehouse-select').find("option:selected").val();
            var productTypeId = $('#so-productType-select').find("option:selected").val();
            var productId = $('#so-product-select').find("option:selected").val();
            var batchNumber = self.find("option:selected").val();
            if(batchNumber != ''){
                $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/warehouse/' + warehouseId + '/productTypes/' + productTypeId + '/products/' + productId + '/productBatches/' + batchNumber + '/quantity', function(data){
                    salesOrderAddProductsFormModal.find('.modal-body').append(data);
                }).done(function(){
                    $('#salesOrder-addProducts-form-button').show();
                });
            }
        });

        $(document).off('input', '.so-product-quantity-input');

        $(document).on('input', '.so-product-quantity-input', function(){
            var input = $(this).val().trim();
            var max = $(this).data('max-quantity');
            if(input != ''){
                if(!$.isNumeric(input) || input <= 0 || input > max){
                    if(input <= 0){
                        $(this).closest('tr').remove();
                        if($('#salesOrder-products').find('tr').length == 1){
                            $('#salesOrder-products').remove();
                        }
                    }
                    $(this).css( "color", "#dd4b39" );
                }else{
                    $(this).css( "color", "#00a65a" );
                }
            }else{
                $(this).css( "color", "" );
                $(this).next().remove();
            }
        });

        $('#salesOrder-addProducts-form-button').click(function(e){
            e.preventDefault();
            if(!$('#so-product-quantity-input').parent().hasClass('has-success')){
                return;
            }
            if(!$('#salesOrder-addProducts-button').next().length){
                $('#salesOrder-addProducts-button').parent().append(productTable);
            }
            var salesOrderProducts = $('#salesOrder-products');
            var warehouseId = $('#so-warehouse-select').find("option:selected").val();
            var warehouse   = $('#so-warehouse-select').find("option:selected").text();
            var productType = $('#so-productType-select').find("option:selected").text();
            var productId   = $('#so-product-select').find("option:selected").val();
            var product     = $('#so-product-select').find("option:selected").text();
            var batchNumber = $('#so-batch-number-select').find("option:selected").text();
            var price       = $('#so-product-price-input').val();
            var quantity    = $('#so-product-quantity-input').val();
            var maxQuantity = $('#so-product-quantity-input').data('max-quantity');
            var hasDuplicate = false;
            salesOrderProducts.find('tbody tr').each(function(){
                var tds = $(this).find('td');
                if(tds.length){
                    if(warehouse == tds.eq(0).text() && productType == tds.eq(1).text() && product == tds.eq(2).text() && batchNumber == tds.eq(3).text()){
                        hasDuplicate = true;
                        return false;
                    }
                }
            });
            if(!hasDuplicate){
                var row =
                    "<tr>" +
                    "<td>" + "<input type='hidden' name='product-warehouseId' value='" + warehouseId + "' class='so-product-warehouseId-input'>" + warehouse + "</td>" +
                    "<td>" + productType + "</td>" +
                    "<td>" + "<input type='hidden' name='product-productId' value='" + productId + "' class='so-product-productId-input'>" + product + "</td>" +
                    "<td>" + batchNumber + "</td>" +
                    "<td>" + price + "</td>" +
                    "<td>" +
                    "<input type='text' name='product-quantity' value='" + quantity + "' class='so-product-quantity-input' maxlength='3' size='3' style='text-align: center; color:#00a65a;' data-max-quantity='" + maxQuantity + "'>" +
                    "</td>" +
                    "</tr>";
                salesOrderProducts.find('tbody').append(row);
            }
        });

        $('#salesOrder-addProducts-form-back').click(function(e){
            e.preventDefault();
            $.get('store/' + '{{$store_id}}' + '/salesOrder', function(data){
                $('section.content').html(data);
            });
        });

        $('#salesOrder-addProducts-form-submit').click(function(e){
            e.preventDefault();
            if(!$('#salesOrder-products').length){
                return;
            }
            var isFormValid = true;
            $(".so-product-quantity-input").each(function(){
                if($(this).css('color') != 'rgb(0, 166, 90)'){
                    isFormValid = false;
                    return false;
                }
            });
            if(!isFormValid){
                return;
            }
            var products = [];
            var trs = $('#salesOrder-products').find('tr');
            trs.each(function(){
                if($(this).find('td').length){
                    var product = {
                        warehouseId : $(this).find('.so-product-warehouseId-input').val(),
                        warehouse   : $(this).find('td').eq(0).text(),
                        productType : $(this).find('td').eq(1).text(),
                        productId   : $(this).find('.so-product-productId-input').val(),
                        product     : $(this).find('td').eq(2).text(),
                        batchNumber : $(this).find('td').eq(3).text(),
                        price       : $(this).find('td').eq(4).text(),
                        quantity    : $(this).find('.so-product-quantity-input').val(),
                        maxQuantity : $(this).find('.so-product-quantity-input').data('max-quantity')
                    };
                    products.push(product);
                }
            });
            var token = '{{csrf_token()}}';
            $.ajax({
                url: 'store/' + {{$store_id}} + '/salesOrder/addGifts',
                type: 'POST',
                data: {
                    _method: 'POST',
                    _token : token,
                    'products': products,
                },
                success: function(data){
                    $('section.content').html(data);
                }
            });
        });
    });
</script>

<style>
    .table th {
        border-top: none !important;
    }
    .table {
        margin-bottom: 0px;
    }
</style>