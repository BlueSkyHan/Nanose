@if(isset($products))
@foreach($products as $product)
<tr class="product">
    @if(Auth::check() && Auth::user()->isAdmin())
        <td id="product-id-{{$product->id}}">
            <a href="#" class="product-edit-button pull-left"><i class='fa fa-pencil' style='color:#f39c12'></i></a>
        </td>
    @endif
    <td>{{$product->name}}</td>
    <td>{{$product->price}}</td>
    @php
        $productAttributeValues = $product->attributeValues()->orderBy('attribute_id')->get();
        for($i=0; $i<count($product->productType->attributes); $i+=1){
            if($i<count($productAttributeValues)){
                echo '<td class="product-attributeValues">' . $productAttributeValues[$i]->value . '</td>';
            }else{
                echo '<td></td>';
            }
        }
    @endphp
    @if(Auth::check() && Auth::user()->isAdmin())
    <td id="product-id-{{$product->id}}">
        <a href="#" class="product-delete-button pull-right" data-token='{{ csrf_token() }}'><i class='fa fa-times' style='color:#dd4b39'></i></a>
    </td>
    @endif
</tr>
@endforeach
@endif

@if(Auth::check() && Auth::user()->isAdmin())
<script>
    $(function(){
        $(document).off('click', 'tr.product td a.product-delete-button');

        $(document).on('click', 'tr.product td a.product-delete-button', function(e){
            e.preventDefault();
            var self = $(this);
            var productTypeId = self.closest('.box-body.no-padding').parent().prop('id').replace('productType-id-', '');
            var productId = $(this).parent().prop('id').replace('product-id-', '');
            var token = $(this).data('token');
            $.ajax({
                url: 'productType/' + productTypeId + '/product/' + productId,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token : token
                },
                success: function(data){
                    $('#productType-id-' + productTypeId).find('.products tbody .product').remove();
                    $('#productType-id-' + productTypeId).find('.products tbody').append(data);
                }
            });
        });

        $(document).off('click', 'tr.product td a.product-edit-button');

        $(document).on('click', 'tr.product td a.product-edit-button', function(e){
            e.preventDefault();
            var productTypeId = $(this).closest('.box-body').parent().prop('id').replace('productType-id-','');
            var modalBody = $('#product-edit-form-modal .modal-body');
            modalBody.prop('id', 'product-productTypeId-' + productTypeId);
            modalBody.find(".form-group.product-id-edit-input").remove();
            modalBody.find(".form-group.product-attribute-edit-input").remove();
            modalBody.find(".form-group").find('input').val(null);
            modalBody.find(".form-group").removeClass('has-error').removeClass('has-success').removeClass('has-info');
            modalBody.find(".form-group").find('span.help-block').remove();
            var productAttributeValues = new Array();
            $(this).closest('.product').find('td').each(function(){
                productAttributeValues.push($(this).text().trim());
            });
            modalBody.find(".form-group input").each(function(index){
                $(this).val(productAttributeValues[1+index]);
            });
            var productIdInput =
                "<div class='form-group product-id-edit-input'>" +
                "<input type='hidden' name='product-id' value='" +
                $(this).parent().prop('id').replace('product-id-', '') +
                "' class='form-control'></div>";
            modalBody.append(productIdInput);
            var productAttributes = $(this).closest('tbody').find('th.product-attributes');
            productAttributes.each(function(index){
                var attributeName = $(this).text().trim();
                var attributeValue = productAttributeValues[3+index];
                var attributeEditInput =
                    "<div class='form-group product-attribute-edit-input'>" +
                    "<label for='product-" + attributeName + "'>" + attributeName + "</label>" +
                    "<input type='text' name='product-" + attributeName +
                    "' value='" + attributeValue +
                    "' class='form-control' placeholder='请输入" + attributeName + "值'></div>";
                modalBody.append(attributeEditInput);
            });
            $('#product-edit-form-modal').modal('show');
        });
    });
</script>
@endif

<style>
    .table th {
        border-top: none !important;
    }
</style>