<?php

namespace App\Http\Controllers;

use App\Address;
use App\DataTables\DataTableBase;
use App\Employee;
use App\Member;
use App\Phone;
use App\SalesChannel;
use App\PaymentMethod;
use App\DeliveryMethod;
use App\SalesOrder;
use App\SalesOrderGift;
use App\SalesOrderProduct;
use App\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($store_id){
        if(Auth::user()->isAdmin()){
            if($store_id == 0){
                $hasSalesOrder = SalesOrder::count();
            }else{
                $hasSalesOrder = Store::findOrFail($store_id)->salesOrders()->count();
            }
            return view('salesOrder.showAllAdmin', compact('hasSalesOrder', 'store_id'));
        }else{
            if($store_id == 0){
                $hasSalesOrder = SalesOrder::count();
            }else if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理'){
                $hasSalesOrder = Store::findOrFail($store_id)->salesOrders()->count();
            }else{
                $hasSalesOrder = Store::findOrFail($store_id)->salesOrders()
                    ->where('employee_id', Auth::user()->employee->id)->count();
            }
            return view('salesOrder.showAll', compact('hasSalesOrder', 'store_id'));
        }
    }

    public function getSalesOrdersTable($store_id){
        if($store_id == 0){
            $hasSalesOrder = SalesOrder::count();
        }else if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理'){
            $hasSalesOrder = Store::findOrFail($store_id)->salesOrders()->count();
        }else{
            $hasSalesOrder = Store::findOrFail($store_id)->salesOrders()
                ->where('employee_id', Auth::user()->employee->id)->count();
        }
        return view('salesOrder.getSalesOrdersTable', compact('hasSalesOrder', 'store_id'));
    }

    public function addProducts(Request $request, $store_id){
        if($request->isNew){
            $request->session()->forget('products');
            $request->session()->forget('gifts');
            $request->session()->forget('member');
        }
        $store = Store::findOrFail($store_id);
        $warehouses = $store->warehouses()->orderBy('created_at')->pluck('name', 'id')->all();
        if($request->session()->has('products')){
            $products = $request->session()->get('products');
            return view('salesOrder.addProducts', compact('store_id', 'warehouses', 'products'));
        }
        return view('salesOrder.addProducts', compact('store_id', 'warehouses'));
    }

    public function addGifts(Request $request, $store_id){
        $input = $request->all();
        if(isset($input['products'])){
            $request->session()->put('products', $input['products']);
        }
        $store = Store::findOrFail($store_id);
        $warehouses = $store->warehouses()->orderBy('created_at')->pluck('name', 'id')->all();
        if($request->session()->has('gifts')){
            $gifts = $request->session()->get('gifts');
            return view('salesOrder.addGifts', compact('store_id', 'warehouses', 'gifts'));
        }
        return view('salesOrder.addGifts', compact('store_id', 'warehouses'));
    }

    public function getMembers($store_id, $name){
        $memberName = '%' . $name . '%';
        $members = Member::where('name', 'LIKE', $memberName)->orderBy('created_at', 'DESC')->paginate(10);
        if(count($members)>0){
            return view('member.getMembers', compact('store_id', 'members'));
        }
        return "<p style='color: #ed5736;'>" . $name . " 不存在" . "</p>";
    }

    public function addMember(Request $request, $store_id){
        $input = $request->all();
        if(isset($input['gifts'])){
            $request->session()->put('gifts', $input['gifts']);
        }
        if($request->session()->has('member')){
            $member = $request->session()->get('member');
            $memberId       = $member['id'];
            $memberPhone    = $member['phone'];
            $memberAddress  = $member['address'];
            $members = Member::where('id', $memberId)->orderBy('created_at', 'DESC')->paginate(4);
            return view('salesOrder.addMember', compact('store_id', 'members', 'memberId', 'memberPhone', 'memberAddress'));
        }
        return view('salesOrder.addMember', compact('store_id'));
    }

    public function addInfo(Request $request, $store_id){
        $input = $request->all();
        if(isset($input['member'])){
            $request->session()->put('member', $input['member']);
        }
        $salesChannels = SalesChannel::pluck('name', 'id')->all();
        $paymentMethods = PaymentMethod::pluck('name', 'id')->all();
        $deliveryMethods = DeliveryMethod::pluck('name', 'id')->all();
        return view('salesOrder.addInfo', compact('salesChannels', 'paymentMethods', 'deliveryMethods','store_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($store_id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $store_id)
    {
        if(Auth::check() && !Auth::user()->isAdmin() && Auth::user()->employee->store_id == $store_id){
            $input = $request->all();
            $salesOrder = new SalesOrder;
            $salesOrder->employee_id = Auth::user()->employee->id;
            $salesOrderProducts = array();
            $total = 0;
            if($request->session()->has('products')){
                $products = $request->session()->get('products');
                foreach($products as $product){
                    $salesOrderProduct                  = new SalesOrderProduct;
                    $salesOrderProduct->warehouse_id    = $product['warehouseId'];
                    $salesOrderProduct->product_id      = $product['productId'];
                    $salesOrderProduct->batch_number    = $product['batchNumber'];
                    $salesOrderProduct->product         = $product['warehouse'] . ' ' . $product['productType'] . ' ' . $product['product'] . ' ' . $product['batchNumber'];
                    $salesOrderProduct->price           = $product['price'];
                    $salesOrderProduct->quantity        = $product['quantity'];
                    $total                              += ((int)$salesOrderProduct->quantity) * ((int)$salesOrderProduct->price);
                    $salesOrderProducts[]               = $salesOrderProduct;
                }
            }
            $salesOrderGifts = array();
            if($request->session()->has('gifts')){
                $gifts = $request->session()->get('gifts');
                foreach($gifts as $gift){
                    $salesOrderGift                     = new SalesOrderGift;
                    $salesOrderGift->warehouse_id       = $gift['warehouseId'];
                    $salesOrderGift->product_id         = $gift['giftId'];
                    $salesOrderGift->batch_number       = $gift['batchNumber'];
                    $salesOrderGift->gift               = $gift['warehouse'] . ' ' . $gift['giftType'] . ' ' . $gift['gift'] . ' ' . $gift['batchNumber'];
                    $salesOrderGift->quantity           = $gift['quantity'];
                    $salesOrderGifts[]                  = $salesOrderGift;
                }
            }
            if($request->session()->has('member')){
                $member = $request->session()->get('member');
                $salesOrder->member_id  = $member['id'];
            }
            $salesOrder->sales_channel_id   = $input['info']['sales_channel_id'];
            $salesOrder->payment_method_id  = $input['info']['payment_method_id'];
            $salesOrder->delivery_method_id = $input['info']['delivery_method_id'];
            $salesOrder->transaction_date   = $input['info']['transaction_date'];
            $salesOrder->receiver           = $input['info']['receiver'];
            $salesOrder->note               = $input['info']['note'];
            $salesOrder->total              = $total;
            $salesOrder->actual_total       = isset($input['info']['actual_total']) ? $input['info']['actual_total'] : $salesOrder->total;
            $salesOrder->save();
            if($request->session()->has('member')){
                $member = $request->session()->get('member');
                $phone = Phone::findOrFail($member['phone']);
                $address = Address::findOrFail($member['address']);
                $salesOrder->phone()->create(['number' => $phone->number]);
                $salesOrder->address()->create(['district_id' => $address->district_id, 'line' => $address->line, 'postcode' => $address->postcode]);
            }
            foreach($salesOrderProducts as $sop){
                $warehouseProduct = DB::table('product_warehouse')->where('warehouse_id', $sop->warehouse_id)->where('product_id', $sop->product_id)->where('batch_number', $sop->batch_number);
                if($warehouseProduct->first()){
                    $sop->production_date = $warehouseProduct->first()->production_date;
                    if($sop->quantity >= $warehouseProduct->first()->quantity){
                        $warehouseProduct->delete();
                    }else{
                        $newQuantity = $warehouseProduct->first()->quantity - $sop->quantity;
                        $warehouseProduct->update(array('quantity' => $newQuantity));
                    }
                    $salesOrder->salesOrderProducts()->save($sop);
                }
            }
            foreach($salesOrderGifts as $sog){
                $warehouseGift = DB::table('gift_warehouse')->where('warehouse_id', $sog->warehouse_id)->where('product_id', $sog->product_id)->where('batch_number', $sog->batch_number);
                if($warehouseGift->first()){
                    $sog->production_date = $warehouseGift->first()->production_date;
                    if($sog->quantity >= $warehouseGift->first()->quantity){
                        $warehouseGift->delete();
                    }else{
                        $newQuantity = $warehouseGift->first()->quantity - $sog->quantity;
                        $warehouseGift->update(array('quantity' => $newQuantity));
                    }
                    $salesOrder->salesOrderGifts()->save($sog);
                }
            }
        }
        $request->session()->forget('products');
        $request->session()->forget('gifts');
        $request->session()->forget('member');
        return $this->index($store_id);
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
        //
    }

    public function changeMember(Request $request, $store_id, $id){
        if($request->isNew){
            $request->session()->forget('member');
        }
        if($request->session()->has('member')){
            $member = $request->session()->get('member');
            $memberId       = $member['id'];
            $memberPhone    = $member['phone'];
            $memberAddress  = $member['address'];
            $members        = Member::where('id', $memberId)->orderBy('created_at', 'DESC')->paginate(4);
            return view('salesOrder.changeMember', compact('store_id', 'members', 'memberId', 'memberPhone', 'memberAddress', 'id'));
        }
        $salesOrder         = SalesOrder::findOrFail($id);
        $member             = $salesOrder->member;
        $memberId           = $member->id;
        $memberPhone        = $member->phones()->where('number', $salesOrder->phone->number)->first()->id;
        $memberAddress      = $member->addresses()->where('district_id', $salesOrder->address->district_id)->where('line', $salesOrder->address->line)->where('postcode', $salesOrder->address->postcode)->first()->id;
        $members            = Member::where('id', $memberId)->orderBy('created_at', 'DESC')->paginate(4);
        return view('salesOrder.changeMember', compact('store_id', 'members', 'memberId', 'memberPhone', 'memberAddress', 'id'));
    }

    public function changeInfo(Request $request, $store_id, $id){
        $input = $request->all();
        if(isset($input['member'])){
            $request->session()->put('member', $input['member']);
        }
        $salesOrder = SalesOrder::findOrFail($id);
        $salesChannels = SalesChannel::pluck('name', 'id')->all();
        $paymentMethods = PaymentMethod::pluck('name', 'id')->all();
        $deliveryMethods = DeliveryMethod::pluck('name', 'id')->all();
        return view('salesOrder.changeInfo', compact('salesChannels', 'paymentMethods', 'deliveryMethods', 'store_id', 'salesOrder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $store_id, $id)
    {
        if(Auth::check() && !Auth::user()->isAdmin() && Auth::user()->employee->store_id == $store_id){
            $input = $request->all();
            $salesOrder = SalesOrder::findOrFail($id);
            if ($request->session()->has('member')) {
                $member = $request->session()->get('member');
                $salesOrder->member_id = $member['id'];
            }
            $salesOrder->sales_channel_id   = $input['info']['sales_channel_id'];
            $salesOrder->payment_method_id  = $input['info']['payment_method_id'];
            $salesOrder->delivery_method_id = $input['info']['delivery_method_id'];
            $salesOrder->transaction_date   = $input['info']['transaction_date'];
            $salesOrder->receiver           = $input['info']['receiver'];
            $salesOrder->note               = $input['info']['note'];
            $salesOrder->actual_total       = isset($input['info']['actual_total']) ? $input['info']['actual_total'] : $salesOrder->total;
            $salesOrder->save();
            if($request->session()->has('member')){
                $member = $request->session()->get('member');
                $phone = Phone::findOrFail($member['phone']);
                $address = Address::findOrFail($member['address']);
                $salesOrder->phone()->update(['number' => $phone->number]);
                $salesOrder->address()->update(['district_id' => $address->district_id, 'line' => $address->line, 'postcode' => $address->postcode]);
            }
        }
        $request->session()->forget('member');
        return $this->index($store_id);
    }


    public function cancel(Request $request, $store_id, $id){
        if(Auth::check() && !Auth::user()->isAdmin() && Auth::user()->employee->store_id == $store_id){
            $salesOrder = SalesOrder::findOrFail($id);
            $salesOrderProducts = $salesOrder->salesOrderProducts;
            foreach ($salesOrderProducts as $sop) {
                $warehouseProduct = DB::table('product_warehouse')->where('warehouse_id', $sop->warehouse_id)->where('product_id', $sop->product_id)->where('batch_number', $sop->batch_number);
                if ($warehouseProduct->first()) {
                    $newQuantity = $warehouseProduct->first()->quantity + $sop->quantity;
                    $warehouseProduct->update(array('quantity' => $newQuantity));
                } else {
                    DB::table('product_warehouse')
                        ->insert(['warehouse_id' => $sop->warehouse_id, 'product_id' => $sop->product_id, 'batch_number' => $sop->batch_number, 'production_date' => $sop->production_date, 'quantity' => $sop->quantity, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
                }
            }
            $salesOrderGifts = $salesOrder->salesOrderGifts;
            foreach ($salesOrderGifts as $sog) {
                $warehouseGift = DB::table('gift_warehouse')->where('warehouse_id', $sog->warehouse_id)->where('product_id', $sog->product_id)->where('batch_number', $sog->batch_number);
                if ($warehouseGift->first()) {
                    $newQuantity = $warehouseGift->first()->quantity + $sog->quantity;
                    $warehouseGift->update(array('quantity' => $newQuantity));
                } else {
                    DB::table('gift_warehouse')
                        ->insert(['warehouse_id' => $sog->warehouse_id, 'product_id' => $sog->product_id, 'batch_number' => $sog->batch_number, 'production_date' => $sog->production_date, 'quantity' => $sog->quantity, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
                }
            }
            $salesOrder->phone()->delete();
            $salesOrder->address()->delete();
            $salesOrder->delete();
        }
        return $this->index($store_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($store_id, $id)
    {
        if(Auth::check() && Auth::user()->isAdmin()){
            $salesOrder = SalesOrder::findOrFail($id);
            $salesOrder->phone()->delete();
            $salesOrder->address()->delete();
            $salesOrder->delete();
        }
        return $this->index($store_id);
    }

    public function getSalesChannels($store_id){
        $salesChannels = SalesChannel::pluck('name', 'name')->all();
        return view('salesOrder.getSalesChannels', compact('salesChannels'));
    }

    public function getPaymentMethods($store_id){
        $paymentMethods = PaymentMethod::pluck('name', 'name')->all();
        return view('salesOrder.getPaymentMethods', compact('paymentMethods'));
    }

    public function getDeliveryMethods($store_id){
        $deliveryMethods = DeliveryMethod::pluck('name', 'name')->all();
        return view('salesOrder.getDeliveryMethods', compact('deliveryMethods'));
    }

    public function getEmployees($store_id){
        if($store_id == 0){
            $employees = Employee::pluck('name', 'name')->all();
        }else{
            $employees = Employee::where('store_id', $store_id)->pluck('name', 'name')->all();
        }
        return view('salesOrder.getEmployees', compact('employees'));
    }

    public function getStores($store_id){
        if($store_id == 0){
            $stores = Store::pluck('name', 'name')->all();
            return view('salesOrder.getStores', compact('stores'));
        }
    }

    public function getSalesOrders(Request $request, $store_id){
        if($store_id == 0){
            $query = SalesOrder::query();
        }else{
            if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理'){
                $query = Store::findOrFail($store_id)->salesOrders();
            }else{
                $query = Store::findOrFail($store_id)->salesOrders()->where('employee_id', Auth::user()->employee->id);
            }
        }
        if($request->has('startDate') && $request->has('endDate')){
            $startDate  = Carbon::createFromFormat('Y/m/d H:i:s', $request->get('startDate') . ' ' . '00:00:00');
            $endDate    = Carbon::createFromFormat('Y/m/d H:i:s', $request->get('endDate') . ' ' . '23:59:59');
            $query->where('transaction_date', '>=', $startDate)->where('transaction_date', '<=', $endDate);
        }
        if($request->has('total')){
            if($request->has('totalComparator')){
                $query->where('total', $request->get('totalComparator'), $request->get('total'));
            }else{
                $query->where('total', $request->get('total'));
            }
        }
        if($request->has('actualTotal')){
            if($request->has('actualTotalComparator')){
                $query->where('actual_total', $request->get('actualTotalComparator'), $request->get('actualTotal'));
            }else{
                $query->where('actual_total', $request->get('actualTotal'));
            }
        }
        if($request->has('status')){
            if($request->get('status') == '准时'){
                $query->whereRaw('sales_orders.created_at >= sales_orders.transaction_date')->whereRaw('sales_orders.created_at < sales_orders.transaction_date + INTERVAL 4 DAY');
            }else if($request->get('status') == '超时'){
                $query->whereRaw('sales_orders.created_at >= sales_orders.transaction_date + INTERVAL 4 DAY');
            }
        }
        $query->with('member')->with('phone')->with('address')
            ->with('salesOrderProducts')->with('salesOrderGifts')
            ->with('salesChannel')->with('paymentMethod')->with('deliveryMethod')
            ->with('employee.store')->select('sales_orders.*');
        $dataTable =
            Datatables::of($query)->addColumn('salesOrderProducts', function(SalesOrder $salesOrder){
                $salesOrderProducts =
                    '<div class="dropdownmenu">' .
                    '<span class="salesOrder-product-top" style="color: #444444">' .
                        $salesOrder->salesOrderProducts[0]->product . ' <br>价格(元): ' .
                        $salesOrder->salesOrderProducts[0]->price . ' <br>数量: ' .
                        $salesOrder->salesOrderProducts[0]->quantity .
                        (count($salesOrder->salesOrderProducts) > 1 ? ' ' : '') .
                    '</span>' .
                    '<div class="dropdownmenu-content">';
                for($i=1; $i<count($salesOrder->salesOrderProducts); $i+=1){
                    $salesOrderProducts .=
                        '<span class="salesOrder-product" style="color: #444444">' .
                            $salesOrder->salesOrderProducts[$i]->product . ' <br>价格(元): ' .
                            $salesOrder->salesOrderProducts[$i]->price . ' <br>数量: ' .
                            $salesOrder->salesOrderProducts[$i]->quantity .
                            ($i < count($salesOrder->salesOrderProducts) - 1 ? ' ' : '') .
                        '</span>';
                }
                $salesOrderProducts .=
                    '</div>' .
                    '</div>';
                return $salesOrderProducts;
            })->addColumn('salesOrderGifts', function(SalesOrder $salesOrder){
                if(count($salesOrder->salesOrderGifts) > 0){
                    $salesOrderGifts =
                        '<div class="dropdownmenu">' .
                        '<span class="salesOrder-gift-top" style="color: #444444">' .
                            $salesOrder->salesOrderGifts[0]->gift . ' <br>数量: ' .
                            $salesOrder->salesOrderGifts[0]->quantity .
                            (count($salesOrder->salesOrderGifts) > 1 ? ' ' : '') .
                        '</span>' .
                        '<div class="dropdownmenu-content">';
                    for($i=1; $i<count($salesOrder->salesOrderGifts); $i+=1){
                        $salesOrderGifts .=
                            '<span class="salesOrder-gift" style="color: #444444">' .
                                $salesOrder->salesOrderGifts[$i]->gift . ' <br>数量: ' .
                                $salesOrder->salesOrderGifts[$i]->quantity .
                                ($i < count($salesOrder->salesOrderGifts) - 1 ? ' ' : '') .
                            '</span>';
                    }
                    $salesOrderGifts .=
                        '</div>' .
                        '</div>';
                    return $salesOrderGifts;
                }else{
                    return '无';
                }
            })->addColumn('address', function(SalesOrder $salesOrder){
                $address = $salesOrder->address->line . ' ' . $salesOrder->address->postcode;
                return $address;
            })->editColumn('note', function(SalesOrder $salesOrder){
                if(!is_null($salesOrder->note)){
                    return $salesOrder->note;
                }else{
                    return '无';
                }
            })->addColumn('status', function(SalesOrder $salesOrder){
                $transacted_at                  = Carbon::createFromFormat('Y-m-d H:i:s', $salesOrder->transaction_date . ' ' . '00:00:00');
                $created_at                     = Carbon::createFromFormat('Y-m-d H:i:s', $salesOrder->created_at);
                if($created_at >= $transacted_at && $created_at < $transacted_at->addDays(4)){
                    return '<span style="color: #00a65a;">' . '准时' . '</span>';
                }else{
                    return '<span style="color: #dd4b39;">' . '超时' . '</span>';
                }
            })->addColumn('edit', function(SalesOrder $salesOrder){
                $edit   = '<a href="#" id="edit-salesOrder-id-' . $salesOrder->id . '" class="salesOrder-edit-button"><i class="fa fa-pencil" style="color:#f39c12;"></i></a>';
                return $edit;
            })->addColumn('cancel', function(SalesOrder $salesOrder){
                $cancel = '<a href="#" id="cancel-salesOrder-id-' . $salesOrder->id . '" class="salesOrder-cancel-button"><i class="fa fa-minus" style="color:#f47983;"></i></a>';
                return $cancel;
            })->addColumn('delete', function(SalesOrder $salesOrder){
                $delete = '<a href="#" id="delete-salesOrder-id-' . $salesOrder->id . '" class="salesOrder-delete-button"><i class="fa fa-times" style="color:#dd4b39;"></i></a>';
                return $delete;
            });
        if($store_id == 0){
            $dataTable->rawColumns(['salesOrderProducts', 'salesOrderGifts', 'status']);
            $columns = ['member.name', 'receiver', 'phone.number', 'address', 'salesOrderProducts', 'salesOrderGifts', 'total', 'actual_total', 'transaction_date', 'sales_channel.name', 'payment_method.name', 'delivery_method.name', 'note', 'employee.store.name'];
        }else{
            if(Auth::user()->isAdmin()){
                $dataTable->rawColumns(['salesOrderProducts', 'salesOrderGifts', 'status', 'delete']);
                $columns = ['member.name', 'receiver', 'phone.number', 'address', 'salesOrderProducts', 'salesOrderGifts', 'total', 'actual_total', 'transaction_date', 'sales_channel.name', 'payment_method.name', 'delivery_method.name', 'note', 'employee.name', 'created_at'];
            }else if(Auth::user()->employee->employeeType->name == '门店管理'){
                $dataTable->rawColumns(['salesOrderProducts', 'salesOrderGifts', 'status', 'edit', 'cancel']);
                $columns = ['member.name', 'receiver', 'phone.number', 'address', 'salesOrderProducts', 'salesOrderGifts', 'total', 'actual_total', 'transaction_date', 'sales_channel.name', 'payment_method.name', 'delivery_method.name', 'note', 'employee.name', 'created_at'];
            }else{
                $dataTable->rawColumns(['salesOrderProducts', 'salesOrderGifts', 'edit', 'cancel']);
                $columns = ['member.name', 'receiver', 'phone.number', 'address', 'salesOrderProducts', 'salesOrderGifts', 'total', 'actual_total', 'transaction_date', 'sales_channel.name', 'payment_method.name', 'delivery_method.name', 'note', 'created_at'];
            }
        }
        $base = new DataTableBase($query, $dataTable, $columns);
        return $base->render(null);
    }
}
