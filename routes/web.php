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

Route::get('/', function () {
    return view('welcome');
});


//商家列表
Route::get('shop/list','ShopController@list');
Route::get('shop/like','ShopController@like');

//登录
Route::post('member/login','MemberController@login');
Route::post('member/create','MemberController@create');
//Route::get('member/store','MemberController@store');
Route::get('member/sms','MemberController@sms');
Route::post('member/update','MemberController@update');
Route::post('member/edit','MemberController@edit');

//用户地址
//Route::resource('address','AddressController');
Route::post('address/store','AddressController@store');
Route::post('address/update','AddressController@update');
Route::get('address/index','AddressController@index');
Route::get('address/edit','AddressController@edit');

//购物车
Route::post('cart/store','CartController@store');
Route::get('cart/index','CartController@index');


//订单接口
Route::post('order/store','OrderController@store');
Route::get('order/index','OrderController@index');
Route::get('order/show','OrderController@show');

