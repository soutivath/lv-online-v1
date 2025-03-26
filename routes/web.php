<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Restaurant;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[Restaurant::class, 'about'])->name('about'); //ຮູບແບບສ້າງຄອນໂທເລີ
Route::get('/addnews',[Restaurant::class, 'addnews'])->name('addnews');
Route::get('/showdata',[Restaurant::class, 'showdata'])->name('showdata');
Route::post('/insert',[Restaurant::class, 'insert'])->name('insert');
Route::get('delete/{id}',[Restaurant::class, 'delete'])->name('delete');




// Route::get('/index', function () {
//     return view('index');
// });
// Route::get('/addnews', function(){
//     return view('addnews');
// });
// Route::get('/showdata', function(){
//     return view('showdata');
// });

// Route::get('/dashboard', function(){
//     return view('Admin.dashboard');
// });
//Route::get('/product', function(){
    //return "<h1>Product Page View </h1> <h2>Nana </h2> <h3>Manichan</h3>"; });
//Route::get('/index', function(){
    //return "<h1 style=color:Green;>Index Page = Information</h1>"; });
//Route::get('/pin', function(){
   //return "<h1 style='font-family:Phetsarath OT; color:pink'>ຂໍ້ມູນຂ່າວສານ</h1>"; });
//Route::get('/products/{name}', function($name){
   // return "<h1> This is product type = ${name} </h1>"; });
//Route::get('/Admin/dash/login/pin/index', function(){
    //return "<h1 style='font-family:Phetsarath OT; color:brown'> ຍິນດີຕ້ອນຮັບເຂົ້າສູ່ລະບົບ </h1>"; })->name('profile');
//Route::get('/profile', function(){
   //return "<a href=".Route('profile')."'> Profile </a>"; });

Route::fallback(function(){
    return "<h1 style='font-family:Phetsarath OT; color:orange; margin-left: 35%; margin-top:20%;'> ບໍ່ພົບໜ້າ Website ທີ່ທ່ານຄົ້ນຫາ </h1>"; 
});


