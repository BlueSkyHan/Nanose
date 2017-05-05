<table id="salesOrder-members" class="table table-hover text-center" style="color: #4e4e4e; margin-bottom: 0; font-size: 12px;">
    <tbody>
    <tr>
        <th></th>
        <th>姓名</th>
        <th>性别</th>
        <th>出生日期</th>
        <th>健康状况</th>
        <th>入会时间</th>
        <th>入会来源</th>
        <th>介绍人</th>
        <th>联系电话</th>
        <th>收货地址</th>
    </tr>
    @foreach($members as $member)
        <tr>
            <td>{!! Form::radio('salesOrder-member-id', $member->id, isset($memberId) ? $member->id == $memberId : null, array()) !!}</td>
            <td>{{$member->name}}</td>
            <td>{{$member->gender}}</td>
            <td>{{isset($member->birth_date) ? $member->birth_date : '未知'}}</td>
            <td>{{isset($member->health_status) ? $member->health_status : '未知'}}</td>
            <td>{{$member->created_at}}</td>
            <td>{{$member->memberFrom->name}}</td>
            @if(isset($member->referrer))
                @if(substr($member->referrer, -3) == '[g]')
                    <td>{{substr($member->referrer, 0, -3)}}</td>
                @else
                    <td>{{$member->referrer}}</td>
                @endif
            @else
                <td>无</td>
            @endif
            <td>
                @if(isset($memberPhone))
                    @include('member/getMemberPhonesSelect', array('member'=>$member, 'memberPhone'=>$memberPhone))
                @else
                    <div class="dropdown">
                        <span class="member-phone-top" style="color: #444444">{{$member->phones[0]->number}}</span>
                        <div class="dropdown-content">
                            @php
                                for($i=1; $i<count($member->phones); $i+=1){
                                    echo '<span class="member-phone" style="color: #444444">' . $member->phones[$i]->number . '</span>';
                                }
                            @endphp
                        </div>
                    </div>
                @endif
            </td>
            <td>
                @if(isset($memberAddress))
                    @include('member/getMemberAddressesSelect', array('member'=>$member, 'memberAddress'=>$memberAddress))
                @else
                    <div class="dropdown">
                        <span class="member-address-top" style="color: #444444">{{$member->addresses[0]->line}}</span>
                        <div class="dropdown-content">
                            @php
                                for($i=1; $i<count($member->addresses); $i+=1){
                                    echo '<span class="member-address" style="color: #444444">' . $member->addresses[$i]->line . '</span>';
                                }
                            @endphp
                        </div>
                    </div>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="text-center" style="line-height: 0;">
    {{ $members->links('pagination.salesOrderMembers') }}
</div>

<script>
    $(function(){
        var previousMember = null;
        var currentMember = null;
        $("input[name='salesOrder-member-id']").change(function(){
            previousMember      = currentMember;
            currentMember       = $("input[name='salesOrder-member-id']:checked").closest('tr');
            previousMemberId    = previousMember ? previousMember.find('input[name="salesOrder-member-id"]').val() : null;
            currentMemberId     = $("input[name='salesOrder-member-id']:checked").val();
            if(previousMemberId){
                $.get('member/' + previousMemberId + '/getMemberPhones', function(data){
                    previousMember.find('td').eq(8).html(data);
                });
                $.get('member/' + previousMemberId + '/getMemberAddresses', function(data){
                    previousMember.find('td').eq(9).html(data);
                });
            }
            $.get('member/' + currentMemberId + '/getMemberPhonesSelect', function(data){
                currentMember.find('td').eq(8).html(data);
            });
            $.get('member/' + currentMemberId + '/getMemberAddressesSelect', function(data){
                currentMember.find('td').eq(9).html(data);
            });
        });
    });
</script>

<style>
    table tbody tr td{
        text-align: center !important;
        vertical-align: middle !important;
    }

    .dropdown{
        position: relative;
        display: inline-block;
        padding: 3px 6px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .dropdown:hover{
        box-shadow: 0 6px 8px 0 rgba(0,0,0,0.2);
        background-color: #e5e5e5;
    }

    .dropdown span{
        color: #e5e5e5;
        cursor: text;
    }

    .dropdown-content{
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        min-width: 100%;
        box-shadow: 0 6px 8px 0 rgba(0,0,0,0.2);
        z-index: 2;
        background-color: #e5e5e5;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .dropdown:hover .dropdown-content{
        display: block;
    }

    .dropdown-content span{
        display: block;
        color: #e5e5e5;
        padding: 3px 6px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }

    .dropdown-content span:hover{
        background-color: #99caeb;
    }
</style>