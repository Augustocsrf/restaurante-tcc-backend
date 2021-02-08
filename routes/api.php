<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Rotas de Usuário
Route::post('login/client', 'Auth\LoginController@clientLogin');
Route::post('login/google', 'Auth\LoginController@googleLogin');
Route::post('login/staff', 'Auth\LoginController@staffLogin');
Route::post('register', 'Auth\RegisterController@create');

//Rotas para Controller de Endereços
Route::get('clients/{id}/addresses', 'AddressController@findByUser'); //Encontrar endereços de um cliente
Route::post('addresses', 'AddressController@create'); //Criar um novo endereço
Route::delete('addresses/{id}', 'AddressController@delete'); //Deletar um endereço

//Rotas para pedidos
Route::get('clients/{id}/open-orders', 'OrderController@getClientOpenOrders'); //Método para obter os pedidos do cliente que ainda estão em aberto
Route::get('orders/open-orders', 'OrderController@getOpenOrders'); //Método para obter os pedidos do cliente que ainda estão em aberto
Route::post('orders', 'OrderController@create'); //Criar pedido
Route::put('orders/{id}', 'OrderController@update'); //Atualizar pedido

//Reservas
Route::get('reservations', 'ReservationController@index');
Route::get('reservations/occupation', 'ReservationController@getBusyDays');
Route::get('clients/{id}/open-reservations', 'ReservationController@getClientOpenReservations'); //Obter reservas em aberto de um cliente
Route::post('reservations', 'ReservationController@create');

Route::get('menu', 'CategoryController@getMenu'); //Obter cardápio

Route::middleware('EnsureTokenIsValid')->group(function() {
    Route::put('reservations/{id}', 'ReservationController@update');
});

//Rotas protegidas apenas para funcionários e administradores
Route::middleware('EnsureTokenIsValid:staff,admin')->group(function() {
    Route::get('reservations/open', 'ReservationController@getOpenReservations'); //Pegar reservas em aberto
    Route::get('reservations-statuses', 'ReservationController@getReservationStatuses'); //Pegar status das reservas
});


//Rotas protegidas apenas para administradores
Route::middleware('EnsureTokenIsValid:admin')->group(function() {
    Route::get('items', 'ItemController@getProductList'); //Pegar produtos para a lista
    Route::get('items/page/{page}', 'ItemController@getProductListPage'); //Pegar um certo número de items
    Route::post('items', 'ItemController@create'); //Criar novo item
    Route::put('items/{id}', 'ItemController@update'); //Atualizar item
    Route::delete('items/{id}', 'ItemController@delete'); //Deletar item

    //Categorias
    Route::get('categories', 'CategoryController@index'); //Obter lista de categorias
    Route::post('categories', 'CategoryController@create'); //Criar categoria
    Route::put('categories/{id}', 'CategoryController@updateCategory'); //Atualizar categoria indicada
    Route::delete('categories/{id}', 'CategoryController@deleteCategory'); //Deletar categoria indicada

    //Funcionários
    Route::get('staff', 'StaffController@index'); //Obter lista de funcionários
    Route::put('staff/{id}', 'StaffController@update'); //Deletar funcionário
    Route::delete('staff/{id}', 'StaffController@delete'); //Deletar funcionário
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
