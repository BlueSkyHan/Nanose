@if(isset($member))
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