<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">现有会员</h3>
    </div>
    <div class="box-body">
        <div class="col-md-12">
            {!! Form::open(['action'=>['MemberController@update', $member->id], 'method'=>'PATCH', 'id'=>'member-edit-form', 'role'=>'form']) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name', '姓名') !!}
                        {!! Form::text('name', $member->name, array('placeholder'=>'请输入姓名', 'class'=>'form-control non-null-input')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('male', '男') !!}
                        &nbsp
                        {!! Form::radio('gender', 'M', $member->gender == '男', array()) !!}
                        &nbsp&nbsp
                        {!! Form::label('female', '女') !!}
                        &nbsp
                        {!! Form::radio('gender', 'F', $member->gender == '女', array()) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('member_from_id', '入会来源') !!}
                        {!! Form::select('member_from_id', array(''=>'请选择入会来源') + $memberFroms, $member->memberFrom->id, array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('birth_date', '出生日期') !!}
                        {!! Form::date('birth_date', isset($member->birth_date) ? $member->birth_date : null, array('class'=>'form-control')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('referrer', '介绍人') !!}
                        {!! Form::text('referrer', isset($member->referrer) ? $member->referrer : null, array('placeholder'=>'请输入姓名', 'class'=>'form-control')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('health_status', '健康状况') !!}
                        {!! Form::textarea('health_status', isset($member->health_status) ? $member->health_status : null, array('placeholder'=>'请更新健康状况...', 'class'=>'form-control', 'rows'=>'2')) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('phone', '联系电话') !!}&nbsp
                        <a id='member-phone-edit-button' href="#" data-toggle='modal' data-target='#member-phone-edit-form-modal'>
                            <i class="fa fa-plus" style='color:#00c0ef'></i>
                        </a>
                        <ul id="member-phones-edit" class="list-unstyled">
                            @if(count($member->phones)>0)
                                @foreach($member->phones as $phone)
                                    <li>
                                        <span>{{$phone->number}}</span>
                                        <a class="member-phone-remove-button" href="#"><i class="fa fa-times" style="color:#dd4b39"></i></a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <div class="form-group">
                        {!! Form::label('address', '地址') !!}&nbsp
                        <a id='member-address-edit-button' href="#" data-toggle='modal' data-target='#member-address-edit-form-modal'>
                            <i class="fa fa-plus" style='color:#00c0ef'></i>
                        </a>
                        <ul id="member-addresses-edit" class="list-unstyled">
                            @if(count($member->addresses)>0)
                                @foreach($member->addresses as $address)
                                    <li>
                                        <span id="district-id-{{$address->district->district_id}}">{{$address->line}}</span>
                                        <span>{{$address->postcode}}</span>
                                        <a class="member-address-remove-button" href="#"><i class="fa fa-times" style="color:#dd4b39"></i></a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="box-footer">
        <div class="col-md-12">
            {!! Form::button('返回', array('id'=>'member-back', 'class'=>'btn btn-default')) !!}
            {!! Form::button('更新', array('id'=>'member-edit', 'class'=>'btn btn-info pull-right')) !!}
        </div>
    </div>
</div>

<div class="modal fade" id="member-phone-edit-form-modal" tabindex="-1" role="dialog" aria-labelledby="memberPhoneEditFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="box box-info">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">联系电话</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('phone', '号码') !!}
                        {!! Form::text('phone', null, array('placeholder'=>'请输入联系电话', 'id'=>'member-phone-edit-input', 'class'=>'form-control non-null-input')) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('关闭', array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')) !!}
                    {!! Form::button('添加', array('id'=>'member-phone-edit-form-button', 'class'=>'btn btn-info')) !!}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="member-address-edit-form-modal" tabindex="-1" role="dialog" aria-labelledby="memberAddressEditFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="box box-info">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">地址</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('province_id', '省份') !!}
                        {!! Form::select('province_id', array(''=>'请选择省份') + $provinces, null, array('id'=>'provinces', 'class'=>'form-control')) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('关闭', array('class'=>'btn btn-default pull-left', 'data-dismiss'=>'modal')) !!}
                    {!! Form::button('添加', array('id'=>'member-address-edit-form-button', 'class'=>'btn btn-info')) !!}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(function(){
        var dateRangePicker = $('.daterangepicker');
        if(dateRangePicker.length){
            dateRangePicker.remove();
        }

        var genderInput = $("input[name='gender']");

        $(document).off('input', 'input');

        $(document).off('input', '.non-null-input');

        $(document).on('input', '.non-null-input', function(){
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

        genderInput.change(function(){
            $(this).parent().find('label').css('color', '#333333');
            $(this).prev().css('color', '#00c0ef');
            $(this).parent().find('span.help-block').remove();
        });

        $(document).off('change', 'select');

        $(document).on('change', 'select', function(){
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

        $('#member-phone-edit-button').click(function(e){
            e.preventDefault();
            $('#member-phone-edit-input').parent().removeClass('has-error').removeClass('has-info');
            $('#member-phone-edit-input').val(null).next().remove();
        });

        $('#member-phone-edit-form-button').click(function(e){
            e.preventDefault();
            var memberPhoneEditInput = $('#member-phone-edit-input');
            var input = memberPhoneEditInput.val().trim();
            var isFormValid = true;
            if(input == ''){
                memberPhoneEditInput.parent().removeClass('has-info').addClass('has-error');
                memberPhoneEditInput.next().remove();
                var inputName = memberPhoneEditInput.prev().text();
                memberPhoneEditInput.parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
                isFormValid = false;
            }else{
                memberPhoneEditInput.parent().removeClass('has-error').addClass('has-info');
                memberPhoneEditInput.next().remove();
            }
            if(!isFormValid){
                return;
            }
            var doesPhoneExist = false;
            $('ul#member-phones-edit li').each(function(){
                if($(this).find('span').text().trim() == input){
                    doesPhoneExist = true;
                    return false;
                }
            });
            if(!doesPhoneExist){
                var phone =
                    "<li>" +
                    "<span>" + input + "</span>&nbsp" +
                    "<a class='member-phone-remove-button' href='#'><i class='fa fa-times' style='color:#dd4b39'></i></a>" +
                    "</li>";
                $('ul#member-phones-edit').parent().removeClass('has-error').addClass('has-info');
                $('ul#member-phones-edit').next().remove();
                $('ul#member-phones-edit').append(phone);
            }
            $('#member-phone-edit-form-modal').modal('hide');
        });

        $(document).off('click', '.member-phone-remove-button');

        $(document).on('click', '.member-phone-remove-button', function(e){
            e.preventDefault();
            $(this).parent().remove();
            var memberPhones = $("ul#member-phones-edit");
            if(!memberPhones.find('li').length){
                memberPhones.parent().removeClass('has-info').addClass('has-error');
                memberPhones.next().remove();
                memberPhones.parent().append("<span class='help-block'><i class='fa fa-times-circle'> 联系电话不能为空</i></span>");
            }
        });

        $('#member-address-edit-button').click(function(e){
            e.preventDefault();
            $('#member-address-edit-form-modal').find('.form-group').slice(1).remove();
            $("select[name='province_id']").parent().removeClass('has-error').removeClass('has-info');
            $("select[name='province_id']").val('').next().remove();
            $('#member-address-edit-form-button').hide();
        });

        $("select[name='province_id']").change(function(){
            var self = $(this);
            self.closest('.modal-body').find('.form-group').slice(1).remove();
            $('#member-address-edit-form-button').hide();
            var provinceId = self.find("option:selected").val();
            if(provinceId != ''){
                $.get('address/provinces/' + provinceId + '/cities', function(data){
                    var citySelect =
                        "<div class='form-group'>" + "<label for='city_id'>城市</label>" + data + "</div>";
                    self.closest('.modal-body').append(citySelect);
                });
            }
        });

        $(document).off('change', "select[name='city_id']");

        $(document).on('change', "select[name='city_id']", function(){
            var self = $(this);
            self.closest('.modal-body').find('.form-group').slice(2).remove();
            $('#member-address-edit-form-button').hide();
            var cityId = self.find("option:selected").val();
            if(cityId != ''){
                $.get('address/cities/' + cityId + '/districts', function(data){
                    var districtSelect =
                        "<div class='form-group'>" + "<label for='district_id'>区县</label>" + data + "</div>";
                    self.closest('.modal-body').append(districtSelect);
                });
            }
        });

        $(document).off('change', "select[name='district_id']");

        $(document).on('change', "select[name='district_id']", function(){
            var self = $(this);
            self.closest('.modal-body').find('.form-group').slice(3).remove();
            $('#member-address-edit-form-button').hide();
            var districtId = self.find("option:selected").val();
            if(districtId != ''){
                var lineInput =
                    "<div class='form-group'>" +
                    "<label for='line'>详细地址</label>" +
                    "<input placeholder='请输入道路门牌号等相关信息' class='form-control non-null-input' name='line' type='text' id='line'>" +
                    "</div>";
                var postcodeInput =
                    "<div class='form-group'>" +
                    "<label for='postcode'>邮政编码</label>" +
                    "<input placeholder='请输入邮政编码' class='form-control non-null-input' name='postcode' type='text' id='postcode'>" +
                    "</div>";
                self.closest('.modal-body').append(lineInput);
                self.closest('.modal-body').append(postcodeInput);
                $('#member-address-edit-form-button').show();
            }
        });

        $('#member-address-edit-form-button').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            $('div#member-address-edit-form-modal .modal-body').find('.form-group select').each(function(){
                var input = $(this).find("option:selected").val();
                $(this).parent().find('span.help-block').remove();
                if(input == ''){
                    $(this).parent().removeClass('has-info').addClass('has-error');
                    var inputName = $(this).parent().find('label').text();
                    $(this).parent().append("<span class='help-block'><i class='fa fa-times-circle'> " + inputName + "不能为空</i></span>");
                    isFormValid = false;
                }else{
                    $(this).parent().removeClass('has-error').addClass('has-info');
                }
            });
            $('div#member-address-edit-form-modal .modal-body').find('.form-group .non-null-input').each(function(){
                var input = $(this).val().trim();
                if(input == ''){
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
            if(!isFormValid){
                return;
            }
            var provinceId      = $("select[name='province_id']").find("option:selected").val();
            var province        = $("select[name='province_id']").find("option:selected").text();
            var cityId          = $("select[name='city_id']").find("option:selected").val();
            var city            = $("select[name='city_id']").find("option:selected").text();
            var districtId      = $("select[name='district_id']").find("option:selected").val();
            var district        = $("select[name='district_id']").find("option:selected").text();
            var line            = $("input[name='line']").val();
            var postcode        = $("input[name='postcode']").val();
            var newLine         = (province == city ? '' : province + ' ') + city + ' ' + district + ' ' + line;
            var doesAddressExist = false;
            $('ul#member-addresses-edit li').each(function(){
                var address = $(this).find('span');
                if(address.eq(0).prop('id').replace('district-id-', '') == districtId && address.eq(0).text().trim() == newLine){
                    doesAddressExist = true;
                    return false;
                }
            });
            if(!doesAddressExist){
                var address =
                    "<li>" +
                    "<span id='district-id-" + districtId + "'>" + newLine + "</span>&nbsp" +
                    "<span>" + postcode + "</span>&nbsp" +
                    "<a class='member-address-remove-button' href='#'><i class='fa fa-times' style='color:#dd4b39'></i></a>" +
                    "</li>";
                $('ul#member-addresses-edit').parent().removeClass('has-error').addClass('has-info');
                $('ul#member-addresses-edit').next().remove();
                $('ul#member-addresses-edit').append(address);
            }
            $('#member-address-edit-form-modal').modal('hide');
        });

        $(document).off('click', '.member-address-remove-button');

        $(document).on('click', '.member-address-remove-button', function(e){
            e.preventDefault();
            $(this).parent().remove();
            var memberAddresses = $("ul#member-addresses-edit");
            if(!memberAddresses.find('li').length){
                memberAddresses.parent().removeClass('has-info').addClass('has-error');
                memberAddresses.next().remove();
                memberAddresses.parent().append("<span class='help-block'><i class='fa fa-times-circle'> 地址不能为空</i></span>");
            }
        });

        $('#member-back').click(function(e){
            e.preventDefault();
            $.get('member', function(data){
                $('section.content').html(data);
            });
        });

        $('#member-edit').click(function(e){
            e.preventDefault();
            var isFormValid = true;
            $('.non-null-input').each(function(){
                if($(this).prev().text().trim() == '号码'){
                    return;
                }
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
            $('select').each(function(){
                if($(this).prev().text().trim() == '省份'){
                    return;
                }
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
            if(!genderInput.is(':checked')){
                if(!genderInput.parent().find('span.help-block').length){
                    genderInput.parent().find('label').css('color', '#dd4b39');
                    genderInput.parent().append("<span class='help-block' style='color: #dd4b39;'><i class='fa fa-times-circle'> 性别不能为空</i></span>");
                }
            }else{
                $("input[name='gender']:checked").prev().css('color', '#00c0ef');
                genderInput.parent().find('span.help-block').remove();
            }
            var memberPhones = $('ul#member-phones-edit');
            if(!memberPhones.find("li").length){
                memberPhones.parent().removeClass('has-info').addClass('has-error');
                memberPhones.next().remove();
                memberPhones.parent().append("<span class='help-block'><i class='fa fa-times-circle'> 联系电话不能为空</i></span>");
                isFormValid = false;
            }else{
                memberPhones.parent().removeClass('has-error').addClass('has-info');
                memberPhones.next().remove();
            }
            var memberAddresses = $("ul#member-addresses-edit");
            if(!memberAddresses.find('li').length){
                memberAddresses.parent().removeClass('has-info').addClass('has-error');
                memberAddresses.next().remove();
                memberAddresses.parent().append("<span class='help-block'><i class='fa fa-times-circle'> 地址不能为空</i></span>");
                isFormValid = false;
            }else{
                memberAddresses.parent().removeClass('has-error').addClass('has-info');
                memberAddresses.next().remove();
            }
            if(!isFormValid){
                return;
            }
            var memberArray = $('#member-edit-form').serializeArray();
            var member = {};
            $.each(memberArray, function(){
                member[this.name] = this.value;
            });
            delete member['_token'];
            delete member['_method'];
            var phones = [];
            $('ul#member-phones-edit li').each(function(){
                var phone = $(this).find('span').text().trim();
                phones.push(phone);
            });
            var addresses = [];
            $('ul#member-addresses-edit li').each(function(){
                var address = {};
                address['district_id']  = $(this).find('span').eq(0).prop('id').replace('district-id-', '');
                address['line']         = $(this).find('span').eq(0).text().trim();
                address['postcode']     = $(this).find('span').eq(1).text().trim();
                addresses.push(address);
            });
            member['phones']    = phones;
            member['addresses'] = addresses;
            var token = '{{csrf_token()}}';
            $.ajax({
                url: 'member/' + '{{$member->id}}',
                type: 'POST',
                data: {
                    _method: 'PATCH',
                    _token : token,
                    'member' : member,
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