<?php

use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PreventBackHistory;

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
// Route::group(['middleware' => 'prevent-back-history'],function(){

// 	Auth::routes();

// 	Route::get('/home', 'HomeController@index');

// });
// Route::group(['middleware' => 'prevent-back-history'],function(){
//     Auth::routes();
//     Route::get('/home', 'HomeController@index');
//   });
// 
//Route::prefix('app')->middleware([AdminCheck::class])->group(function(){
 Route::group([
        'middleware' => ['api', 'cors',AdminCheck::class],
        'prefix' => 'app',
    ], function ($router) {
    Auth::routes();
    Route::post('/create_tag','App\Http\Controllers\AdminController@addTag');
    Route::post('/create_category','App\Http\Controllers\AdminController@addCategory');
    Route::get('/get_category', 'App\Http\Controllers\AdminController@getCategory');
    Route::get('/get_tags', 'App\Http\Controllers\AdminController@getTag');
    Route::put('/update_tag', 'App\Http\Controllers\AdminController@updateTag');
    Route::delete('/delete_tag','App\Http\Controllers\AdminController@deleteTag');
    Route::delete('/delete_category','App\Http\Controllers\AdminController@deleteCategory');
    Route::post('/upload','App\Http\Controllers\AdminController@upload');
    Route::post('/upload/user/photo','App\Http\Controllers\AdminController@upload');
    Route::post('/create_tag','App\Http\Controllers\AdminController@addTag');
    Route::post('/delete_image','App\http\Controllers\AdminController@deleteImage');
    Route::put('/update_category', 'App\Http\Controllers\AdminController@updateCategory');
    Route::post('/create_user','App\Http\Controllers\AdminController@createUser');
    Route::get('/get_users', 'App\Http\Controllers\AdminController@getUsers');
   
    Route::put('/update_user', 'App\Http\Controllers\AdminController@updateUser');
    Route::delete('/delete_user','App\Http\Controllers\AdminController@deleteUser');
    Route::post('/admin_login','App\Http\Controllers\AdminController@adminLogin');
    Route::post('/create_role','App\Http\Controllers\AdminController@addRole');
    Route::put('/update_role', 'App\Http\Controllers\AdminController@updateRole');
    Route::delete('/delete_role','App\Http\Controllers\AdminController@deleteRole');
    Route::get('/get_roles', 'App\Http\Controllers\AdminController@getRole');
    Route::post('/assign_roles','App\Http\Controllers\AdminController@assignRoles');
    //Blog
    Route::post('/create-blog','App\Http\Controllers\AdminController@createBlog');
    Route::get('/blogdata','App\Http\Controllers\AdminController@blogData');
    Route::delete('/delete_blog','App\Http\Controllers\AdminController@deleteBlog');
    Route::get('/blog_single/{id}','App\Http\Controllers\AdminController@blogItem');
    Route::post('/update_blog/{id}','App\Http\Controllers\AdminController@updateblog');
    Route::post('/upload/blog/feature/photo','App\Http\Controllers\AdminController@upload');
});

Route::post('createBlog','App\Http\Controllers\AdminController@uploadEditorImage');
Route::get('/search_users', 'App\Http\Controllers\AdminController@searchUser');
Route::get('/slug','App\Http\Controllers\AdminController@slug');
Route::get('/logout','App\Http\Controllers\AdminController@logout');
Route::get('/','App\Http\Controllers\AdminController@index');
Route::any('{slug}','App\Http\Controllers\AdminController@index')->where('slug', "([A-z\d/]+)?");
// Route::get('{any}', function () {
//     return view('welcome');
// })->where('any','.*');
//Route::any('{slug}','App\Http\Controllers\AdminController@index')->where('slug', "([A-z\d/]+)?");
// Route::get('{any}', function () {
//     return view('welcome');
// })->where('any','.*');
// ->where('slug', '([A-z\d-\/_.]+)?')

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/new','App\Http\Controllers\TestController@test');

// Route::any('{slug}',function(){
//     return view('welcome');
// });
