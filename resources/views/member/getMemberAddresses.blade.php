@if(isset($member))
    <div class="dropdown">
        <span class="member-address-top" style="color: #444444">{{$member->addresses[0]->line . ' ' . $member->addresses[0]->postcode}}</span>
        <div class="dropdown-content">
            @php
                for($i=1; $i<count($member->addresses); $i+=1){
                    echo '<span class="member-address" style="color: #444444">' . $member->addresses[$i]->line . ' ' . $member->addresses[$i]->postcode . '</span>';
                }
            @endphp
        </div>
    </div>
@endif