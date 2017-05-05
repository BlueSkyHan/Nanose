<div class="row">
    <div class="col-md-4">
        <div class="form-group" id="provinces">
            {!! Form::label('province_id', '省份') !!}
            {!! Form::select('province_id', array(''=>'请选择省份') + $provinces, $address->district->city->province->province_id, array('class'=>'form-control')) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group" id="cities">
            {!! Form::label('city_id', '城市') !!}
            {!! Form::select('city_id', array(''=>'请选择城市') + $cities, $address->district->city->city_id, array('class'=>'form-control')) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group" id="districts">
            {!! Form::label('district_id', '区县') !!}
            {!! Form::select('district_id', array(''=>'请选择区县') + $districts, $address->district->district_id, array('class'=>'form-control')) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            {!! Form::label('line', '详细地址') !!}
            {!! Form::text('line', $address->line, array('placeholder'=>'请输入道路门牌号等相关信息', 'class'=>'form-control non-null-input')) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('postcode', '邮政编码') !!}<br>
            {!! Form::text('postcode', $address->postcode, array('placeholder'=>'请输入邮政编码', 'class'=>'form-control non-null-input')) !!}
        </div>
    </div>
</div>

<script>
    $(function(){
        $(document).off('change', "#provinces select");

        $(document).on('change', "#provinces select", function(){
            $('#cities').removeClass('has-error').removeClass('has-info');
            $('#cities').find('span.help-block').remove();
            var province_id = $(this).find("option:selected").val();
            if(province_id != ''){
                $.get('address/provinces/' + province_id + '/cities', function(data){
                    $('#cities').find("select").remove();
                    $('#cities').append(data);
                });
            }else{
                $('#cities').find("select").empty().append("<option value=''>请选择城市</option>");
            }
            $('#districts').removeClass('has-error').removeClass('has-info');
            $('#districts').find('span.help-block').remove();
            $('#districts').find("select").empty().append("<option value=''>请选择区县</option>");
        });

        $(document).off('change', "#cities select");

        $(document).on('change', "#cities select", function(){
            $('#districts').removeClass('has-error').removeClass('has-info');
            $('#districts').find('span.help-block').remove();
            var city_id = $(this).find("option:selected").val();
            if(city_id != ''){
                $.get('address/cities/' + city_id + '/districts', function(data){
                    $('#districts').find('select').remove();
                    $('#districts').append(data);
                });
            }else{
                $('#districts').find("select").empty().append("<option value=''>请选择区县</option>");
            }
        });

        $(document).off('change', "select");

        $(document).on('change', "select", function(){
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
    });
</script>