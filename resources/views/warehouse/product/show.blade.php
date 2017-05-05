<table class="products table table-hover text-center" style="color: #4e4e4e;">
    <tbody>
    <tr>
        <th>批号</th>
        <th>生产日期</th>
        <th>数量</th>
    </tr>
    @foreach($products as $product)
    <tr>
        <td>{{$product->pivot->batch_number}}</td>
        <td>{{$product->pivot->production_date}}</td>
        @if(Auth::check())
        @if(Auth::user()->isAdmin() || Auth::user()->employee->employeeType->name == '门店管理')
        <td><a href="#" class="wh-product-quantity-edit-button" style="color: #f39c12;">{{$product->pivot->quantity}}</a></td>
        @else
        <td>{{$product->pivot->quantity}}</td>
        @endif
        @endif
    </tr>
    @endforeach
    </tbody>
</table>