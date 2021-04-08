<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('login', 'Auth\LoginController@login');
Route::post('login/google', 'Auth\LoginController@googleLogin');
Route::post('register', 'Auth\RegisterController@create');
Route::put('clients/{id}/password', 'ClientController@updatePassword'); //Atualizar senha do cliente

Route::post('recover-code', 'Auth\ForgotPasswordController@requestCode');
Route::post('verify-code', 'Auth\ForgotPasswordController@verifyCode');

//Reservas
Route::get('reservations/occupation', 'ReservationController@getBusyDays');
Route::post('reservations', 'ReservationController@create');

// Route::get('menu', 'CategoryController@getMenu'); //Obter cardápio
Route::get('available-menu', 'CategoryController@getMenu'); //Obter cardápio

Route::middleware('EnsureTokenIsValid')->group(function() {
    Route::put('confirm-email-user', 'ClientController@verifyUserCode'); //Método para verificar o email cadastrado
    Route::put('reservations/{id}', 'ReservationController@update'); //Atualizar reserva

    Route::post('orders', 'OrderController@create'); //Criar pedido
    Route::put('orders/{id}', 'OrderController@update'); //Atualizar pedido

    //Rotas para tela de perfil do cliente
    Route::get('clients/{id}/open-orders', 'OrderController@getClientOpenOrders'); //Método para obter os pedidos do cliente que ainda estão em aberto
    Route::get('clients/{id}/open-reservations', 'ReservationController@getClientOpenReservations'); //Obter reservas em aberto de um cliente
    Route::put('clients/{id}', 'ClientController@update'); //Atualizar informações do cliente

    //Rotas para Controller de Endereços
    Route::get('clients/{id}/addresses', 'AddressController@findByUser'); //Encontrar endereços de um cliente
    Route::post('addresses', 'AddressController@create'); //Criar um novo endereço
    Route::put('addresses/{id}', 'AddressController@update'); //Editar um endereço
    Route::delete('addresses/{id}', 'AddressController@delete'); //Deletar um endereço
});

//Rotas protegidas apenas para funcionários e administradores
Route::middleware('EnsureTokenIsValid:staff,admin')->group(function() {
    Route::get('reservations/open', 'ReservationController@getOpenReservations'); //Pegar reservas em aberto
    Route::get('reservations-statuses', 'ReservationController@getReservationStatuses'); //Pegar status das reservas

    Route::get('orders/open-orders', 'OrderController@getOpenOrders'); //Método para obter os pedidos do cliente que ainda estão em aberto
    Route::get('order-status', 'OrderController@getOrderStatuses'); //Obter status de pedidos
});

//Rotas protegidas apenas para administradores
Route::middleware('EnsureTokenIsValid:admin')->group(function() {
    Route::get('items', 'ItemController@getProductList'); //Pegar produtos para a lista
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
    Route::post('staff', 'Auth\RegisterController@createEmployee'); //Registrar funcionário
    Route::put('staff/{id}', 'StaffController@update'); //Deletar funcionário
    Route::delete('staff/{id}', 'StaffController@delete'); //Deletar funcionário

    //Status de Pedido
    Route::post('order-status', 'OrderStatusController@create'); //Criar status de pedidos
    Route::put('order-status/{id}', 'OrderStatusController@update'); //Criar status de pedidos
    Route::delete('order-status/{id}', 'OrderStatusController@delete'); //Criar status de pedidos

    //Reports
    Route::get('reports/revenue', 'DataReportController@getOrderRevenue');
    Route::get('reports/orders', 'DataReportController@getOrderAmount');
    Route::get('reports/reservations', 'DataReportController@getReservationAmount');
    Route::get('reports/items', 'DataReportController@getItemsOrdered');
    Route::get('reports/clients', 'DataReportController@getNewClients');
    Route::get('reports/deliveries', 'DataReportController@getDeliveryProportions');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
