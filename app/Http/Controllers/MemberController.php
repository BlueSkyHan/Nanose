<?php

namespace App\Http\Controllers;

use App\DataTables\DataTableBase;
use App\DataTables\MembersDataTable;
use App\Member;
use App\MemberFrom;
use App\Province;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hasMember = Member::count();
        return view('member.showAll', compact('hasMember'));
    }

    /**
     * Display index page and process dataTable ajax request.
     *
     * @param \App\DataTables\MembersDataTable $dataTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function indexDataTableService(MembersDataTable $dataTable)
    {
        return $dataTable->render('member.showAll');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $memberFroms = MemberFrom::orderBy('created_at')->pluck('name', 'id')->all();
        $provinces = Province::orderBy('province_id')->pluck('name', 'province_id')->all();
        return view('member.create', compact('memberFroms', 'provinces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->member;
        $member = new Member;
        $member->member_from_id = $input['member_from_id'];
        $member->name = $input['name'];
        if($input['gender'] == 'M'){
            $member->gender = '男';
        }else if($input['gender'] == 'F'){
            $member->gender = '女';
        }else{
            $member->gender =  '未知';
        }
        if($input['birth_date']){
            $member->birth_date = $input['birth_date'];
        }
        if($input['referrer']){
            $member->referrer = $input['referrer'];
        }
        if($input['health_status']){
            $member->health_status = $input['health_status'];
        }
        $member->save();
        foreach($input['phones'] as $phone){
            $member->phones()->create(['number' => $phone]);
        }
        foreach($input['addresses'] as $address){
            $member->addresses()->create(['district_id' => $address['district_id'], 'line' => $address['line'], 'postcode' => $address['postcode']]);
        }
        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $memberFroms = MemberFrom::orderBy('created_at')->pluck('name', 'id')->all();
        $provinces = Province::orderBy('province_id')->pluck('name', 'province_id')->all();
        $member = Member::findOrFail($id);
        return view('member.edit', compact('memberFroms', 'provinces', 'member'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->member;
        $member = Member::findOrFail($id);
        $member->member_from_id = $input['member_from_id'];
        $member->name = $input['name'];
        if($input['gender'] == 'M'){
            $member->gender = '男';
        }else if($input['gender'] == 'F'){
            $member->gender = '女';
        }else{
            $member->gender =  '未知';
        }
        if($input['birth_date']){
            $member->birth_date = $input['birth_date'];
        }else{
            $member->birth_date = null;
        }
        if($input['referrer']){
            $member->referrer = $input['referrer'];
        }else{
            $member->referrer = null;
        }
        if($input['health_status']){
            $member->health_status = $input['health_status'];
        }else{
            $member->health_status = null;
        }
        $member->save();
        $i=0;
        for(; $i<count($input['phones']); $i+=1){
            if($i<count($member->phones)){
                $phone = $input['phones'][$i];
                $member->phones[$i]->update(['number' => $phone]);
            }else{
                break;
            }
        }
        if($i<count($member->phones)){
            for($j=$i; $j<count($member->phones); $j+=1){
                $member->phones[$j]->delete();
            }
        }
        if($i<count($input['phones'])){
            for($j=$i; $j<count($input['phones']); $j+=1){
                $phone = $input['phones'][$j];
                $member->phones()->create(['number' => $phone]);
            }
        }
        $i=0;
        for(; $i<count($input['addresses']); $i+=1){
            if($i<count($member->addresses)){
                $address = $input['addresses'][$i];
                $member->addresses[$i]->update(['district_id' => $address['district_id'], 'line' => $address['line'], 'postcode' => $address['postcode']]);
            }else{
                break;
            }
        }
        if($i<count($member->addresses)){
            for($j=$i; $j<count($member->addresses); $j+=1){
                $member->addresses[$j]->delete();
            }
        }
        if($i<count($input['addresses'])){
            for($j=$i; $j<count($input['addresses']); $j+=1){
                $address = $input['addresses'][$j];
                $member->addresses()->create(['district_id' => $address['district_id'], 'line' => $address['line'], 'postcode' => $address['postcode']]);
            }
        }
        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->phones()->delete();
        $member->addresses()->delete();
        $member->delete();
        return $this->index();
    }

    public function sendGift($id){
        $member = Member::findOrFail($id);
        $member->referrer = $member->referrer . '[g]';
        $member->save();
        return $this->index();
    }

    public function referrees($name){
        $members = Member::where('referrer', $name)->orderBy('created_at', 'DESC')->get();
        return view('member.showAll', compact('members'));
    }

    public function getMembers(Request $request){
        $query = Member::query();
        if($request->has('dateRangeType') && $request->get('dateRangeType') == 'members-date-range'){
            $startDate  = Carbon::createFromFormat('Y/m/d H:i:s', $request->get('startDate') . ' ' . '00:00:00');
            $endDate    = Carbon::createFromFormat('Y/m/d H:i:s', $request->get('endDate') . ' ' . '23:59:59');
            $query->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
        }
        $query->with('memberFrom')->with('phones')->with('addresses')->with('salesOrders')->select('members.*');
        $dataTable =
            Datatables::of($query)->editColumn('birth_date', function(Member $member){
                if(!is_null($member->birth_date)){
                    return $member->birth_date;
                }else{
                    return '未知';
                }
            })->editColumn('health_status', function(Member $member){
                if(!is_null($member->health_status)){
                    return $member->health_status;
                }else{
                    return '未知';
                }
            })->addColumn('phones', function(Member $member){
                $phones =
                    '<div class="dropdownmenu">' .
                    '<span class="member-phone-top" style="color: #444444">' . $member->phones[0]->number . (count($member->phones) > 1 ? ' ' : '') . '</span>' .
                    '<div class="dropdownmenu-content">';
                for($i=1; $i<count($member->phones); $i+=1){
                    $phones .=
                        '<span class="member-phone" style="color: #444444">' . $member->phones[$i]->number . ($i < count($member->phones)-1 ? ' ' : '') . '</span>';
                }
                $phones .=
                    '</div>' .
                    '</div>';
                return $phones;
            })->addColumn('addresses', function(Member $member){
                $addresses =
                    '<div class="dropdownmenu">' .
                    '<span class="member-address-top" style="color: #444444">' .
                    $member->addresses[0]->line . ' ' . $member->addresses[0]->postcode . (count($member->addresses) > 1 ? ' ' : '') .
                    '</span>' .
                    '<div class="dropdownmenu-content">';
                for($i=1; $i<count($member->addresses); $i+=1){
                    $addresses .=
                        '<span class="member-address" style="color: #444444">' .
                        $member->addresses[$i]->line . ' ' . $member->addresses[$i]->postcode . ($i < count($member->addresses)-1 ? ' ' : '') .
                        "</span>";
                }
                $addresses .=
                    '</div>' .
                    '</div>';
                return $addresses;
            })->addColumn('salesOrdersCount', function(Member $member) use ($request){
                if($request->has('dateRangeType') && $request->get('dateRangeType') == 'salesOrders-date-range'){
                    $startDate  = Carbon::createFromFormat('Y/m/d', $request->get('startDate'));
                    $endDate    = Carbon::createFromFormat('Y/m/d', $request->get('endDate'));
                    return $member->salesOrders()
                        ->where('transaction_date', '>=', $startDate)
                        ->where('transaction_date', '<=', $endDate)->count();
                }else{
                    return $member->salesOrders()->count();
                }
            })->addColumn('salesOrdersSum', function(Member $member) use ($request){
                if($request->has('dateRangeType') && $request->get('dateRangeType') == 'salesOrders-date-range'){
                    $startDate  = Carbon::createFromFormat('Y/m/d', $request->get('startDate'));
                    $endDate    = Carbon::createFromFormat('Y/m/d', $request->get('endDate'));
                    $salesOrders = $member->salesOrders()
                            ->where('transaction_date', '>=', $startDate)
                            ->where('transaction_date', '<=', $endDate)->get();
                }else{
                    $salesOrders = $member->salesOrders;
                }
                $sum = 0;
                foreach($salesOrders as $salesOrder){
                    $sum += $salesOrder->actual_total;
                }
                return $sum;
            })->editColumn('referrer', function(Member $member){
                if(!is_null($member->referrer)){
                    if(substr($member->referrer, -3) == '[g]'){
                        return substr($member->referrer, 0, -3) . ' <i class="fa fa-gift"></i>';
                    }else{
                        if(Auth::check() && Auth::user()->isAdmin()){
                            return $member->referrer . ' <i class="fa fa-gift" style="color:#ef7a82"></i>';
                        }
                        return $member->referrer . ' <a href="#" id="gift-member-id-' . $member->id . '" class="member-gift-button"><i class="fa fa-gift" style="color:#ef7a82"></i></a>';
                    }
                }else{
                    return '无';
                }
            })->addColumn('edit', function(Member $member){
                $edit = '<a href="#" id="edit-member-id-' . $member->id . '" class="member-edit-button"><i class="fa fa-pencil" style="color:#f39c12"></i></a>';
                return $edit;
            });
            if(Auth::check() && Auth::user()->isAdmin()){
                $dataTable->addColumn('delete', function(Member $member){
                    $delete = '<a href="#" id="delete-member-id-' . $member->id . '" class="member-delete-button"><i class="fa fa-times" style="color:#dd4b39"></i></a>';
                    return $delete;
                })->rawColumns(['phones', 'addresses', 'referrer', 'edit', 'delete']);
            }else{
                $dataTable->rawColumns(['phones', 'addresses', 'referrer', 'edit']);
            }
        $columns = ['name', 'gender', 'birth_date', 'health_status', 'phones', 'addresses', 'salesOrdersCount', 'salesOrdersSum', 'created_at', 'member_from.name', 'referrer'];
        $base = new DataTableBase($query, $dataTable, $columns);
        return $base->render(null);
    }

    public function getMemberPhones($id){
        $member = Member::findOrFail($id);
        return view('member.getMemberPhones', compact('member'));
    }

    public function getMemberAddresses($id){
        $member = Member::findOrFail($id);
        return view('member.getMemberAddresses', compact('member'));
    }

    public function getMemberPhonesSelect($id){
        $member = Member::findOrFail($id);
        return view('member.getMemberPhonesSelect', compact('member'));
    }

    public function getMemberAddressesSelect($id){
        $member = Member::findOrFail($id);
        return view('member.getMemberAddressesSelect', compact('member'));
    }

    public function getMemberFroms(){
        $memberFroms = MemberFrom::pluck('name', 'name')->all();
        return view('member.getMemberFroms', compact('memberFroms'));
    }
}