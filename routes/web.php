<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(array('middleware' => 'prevent-back-history'),function(){
    Auth::routes();

    Route::group(array('middleware' => 'auth'), function(){
        Route::get('/', function(){
            return view('index');
        });

        Route::get('/error', function () {
            return view('error');
        });

        Route::get('/address/provinces', 'AddressController@provinces');

        Route::get('/address/provinces/{province_id}/cities', 'AddressController@cities');

        Route::get('/address/cities/{city_id}/districts', 'AddressController@districts');

        Route::get('/productType/{productType_id}/products', 'ProductTypeController@products');

        Route::get('/member/{name}/referrees', 'MemberController@referrees');

        Route::post('/member/{id}/sendGift', 'MemberController@sendGift');

        Route::get('/member/getMembers', 'MemberController@getMembers');

        Route::get('/member/{id}/getMemberPhones', 'MemberController@getMemberPhones');

        Route::get('/member/{id}/getMemberAddresses', 'MemberController@getMemberAddresses');

        Route::get('/member/{id}/getMemberPhonesSelect', 'MemberController@getMemberPhonesSelect');

        Route::get('/member/{id}/getMemberAddressesSelect', 'MemberController@getMemberAddressesSelect');

        Route::get('/member/getMemberFroms', 'MemberController@getMemberFroms');

        Route::delete('/store/{store_id}/warehouse/{warehouse_id}/product/productType/{productType_id}', 'WarehouseProductController@destroyByProductType');

        Route::delete('/store/{store_id}/warehouse/{warehouse_id}/gift/productType/{productType_id}', 'WarehouseGiftController@destroyByProductType');

        Route::get('/store/{store_id}/warehouse/{id}/productTypes', 'WarehouseController@productTypes');

        Route::get('/store/{store_id}/warehouse/{id}/productTypes/{productType_id}/products', 'WarehouseController@products');

        Route::get('/store/{store_id}/warehouse/{id}/productTypes/{productType_id}/products/{product_id}/productBatches', 'WarehouseController@productBatches');

        Route::get('/store/{store_id}/warehouse/{id}/productTypes/{productType_id}/products/{product_id}/productBatches/{batch_number}/quantity', 'WarehouseController@productBatchQuantity');

        Route::get('/store/{store_id}/warehouse/{id}/giftTypes', 'WarehouseController@giftTypes');

        Route::get('/store/{store_id}/warehouse/{id}/giftTypes/{giftType_id}/gifts', 'WarehouseController@gifts');

        Route::get('/store/{store_id}/warehouse/{id}/giftTypes/{giftType_id}/gifts/{gift_id}/giftBatches', 'WarehouseController@giftBatches');

        Route::get('/store/{store_id}/warehouse/{id}/giftTypes/{giftType_id}/gifts/{gift_id}/giftBatches/{batch_number}/quantity', 'WarehouseController@giftBatchQuantity');

        Route::get('/store/{store_id}/salesOrder/getMembers/{name}', 'SalesOrderController@getMembers');

        Route::post('/store/{store_id}/salesOrder/addProducts', 'SalesOrderController@addProducts');

        Route::post('/store/{store_id}/salesOrder/addGifts', 'SalesOrderController@addGifts');

        Route::post('/store/{store_id}/salesOrder/addMember', 'SalesOrderController@addMember');

        Route::post('/store/{store_id}/salesOrder/addInfo', 'SalesOrderController@addInfo');

        Route::post('/store/{store_id}/salesOrder/{id}/changeMember', 'SalesOrderController@changeMember');

        Route::post('/store/{store_id}/salesOrder/{id}/changeInfo', 'SalesOrderController@changeInfo');

        Route::post('/store/{store_id}/salesOrder/{id}/cancel', 'SalesOrderController@cancel');

        Route::get('/store/{store_id}/salesOrder/getSalesOrders', 'SalesOrderController@getSalesOrders');

        Route::get('/store/{store_id}/salesOrder/getSalesOrdersTable', 'SalesOrderController@getSalesOrdersTable');

        Route::get('/store/{store_id}/salesOrder/getSalesChannels', 'SalesOrderController@getSalesChannels');

        Route::get('/store/{store_id}/salesOrder/getPaymentMethods', 'SalesOrderController@getPaymentMethods');

        Route::get('/store/{store_id}/salesOrder/getDeliveryMethods', 'SalesOrderController@getDeliveryMethods');

        Route::get('/store/{store_id}/salesOrder/getEmployees', 'SalesOrderController@getEmployees');

        Route::get('/store/{store_id}/salesOrder/getStores', 'SalesOrderController@getStores');

        Route::resource('/address', 'AddressController');

        Route::resource('/member', 'MemberController');

        Route::resource('/store', 'StoreController');

        Route::resource('/store/{store_id}/salesOrder', 'SalesOrderController');

        Route::resource('/store/{store_id}/warehouse', 'WarehouseController');

        Route::resource('/store/{store_id}/warehouse/{warehouse_id}/product', 'WarehouseProductController');

        Route::resource('/store/{store_id}/warehouse/{warehouse_id}/gift', 'WarehouseGiftController');

        Route::resource('/store/{store_id}/employee', 'EmployeeController');

        Route::resource('/productType', 'ProductTypeController');

        Route::resource('/productType/{productType_id}/product', 'ProductController');
    });
});