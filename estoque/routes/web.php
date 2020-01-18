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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/categorias', ['uses'=>'CategoryController@index','as'=>'category.index']);
Route::get('/categorias/add', ['uses'=>'CategoryController@add','as'=>'category.add']);
Route::post('/categorias/save', ['uses'=>'CategoryController@save','as'=>'category.save']);
//edição de uma categoria
Route::get('/categorias/edit/{id}', ['uses'=>'CategoryController@edit','as'=>'category.edit']);
//faz a atualização da caegoria
Route::post('/categorias/update/{id}', ['uses'=>'CategoryController@update','as'=>'category.update']);
//para deletar o arquivo
Route::get('/categorias/delete/{id}', ['uses'=>'CategoryController@delete','as'=>'category.delete']);
Route::put('/categorias/search', ['uses'=>'CategoryController@search','as'=>'category.search']);

//produtos
Route::get('/produtos', ['uses'=>'ProductController@index','as'=>'product.index']);
Route::get('/produtos/add', ['uses'=>'ProductController@add','as'=>'product.add']);
Route::post('/produtos/save', ['uses'=>'ProductController@save','as'=>'product.save']);
//edição de um produto
Route::get('/produtos/edit/{id}', ['uses'=>'ProductController@edit','as'=>'product.edit']);
//faz a atualização de um produto
Route::post('/produtos/update/{id}', ['uses'=>'ProductController@update','as'=>'product.update']);
//para deletar um produto
Route::get('/produtos/delete/{id}', ['uses'=>'ProductController@delete','as'=>'product.delete']);
Route::put('/produtos/search', ['uses'=>'ProductController@search','as'=>'product.search']);



