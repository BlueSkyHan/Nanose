<div class='form-group'>
    {!! Form::label('quantity', '数量') !!}
    {!! Form::text('quantity', null, array('placeholder'=>'请输入数量', 'id'=>'so-gift-quantity-input', 'class'=>'form-control', 'maxlength'=>'4', 'data-max-quantity'=>$warehouseProductBatchQuantity)) !!}
</div>

<script>
    $(function(){
        var dateRangePicker = $('.daterangepicker');
        if(dateRangePicker.length){
            dateRangePicker.remove();
        }

        $(document).off('input', 'input');

        $("#so-gift-quantity-input").on('input', function(){
            var input = $(this).val().trim();
            var max = parseInt('{{$warehouseProductBatchQuantity}}');
            if(input != ''){
                if(!$.isNumeric(input) || input <= 0 || input > max){
                    $(this).parent().removeClass('has-success').addClass('has-error');
                    $(this).next().remove();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> 必须为正数，并小于库存数量" + max + "</i></span>");
                }else{
                    $(this).parent().removeClass('has-error').addClass('has-success');
                    $(this).next().remove();
                }
            }else{
                $(this).parent().removeClass('has-success').addClass('has-error');
                $(this).next().remove();
                var inputName = $(this).prev().text();
                $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
            }
        });
    });
</script>