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


Route::get('/', function()
{
    return View('index');
});

Route::get('login',function ()
{
    return View('login');
});

Route::post('login',array('before'=>'csrf',function()
{
   $rules=array(
       'email'=>'required|email',
       'password'=>'required|min:4',
       'remember_me'=>'boolean',
   );

   $validator=Validator::make(Input::all(),$rules);

   if($validator->passes())
   {
       if(Auth::attempt(array(
           'email'=>Input::get('email'),
           'password'=>Input::get('password'),
           'block'=>0),
           (boolean)Input::get('remember_me')))
       {
           return Redirect::intended('home');
       }else{
           return Redirect::to('login')->withInput()->with('message','E-mail or password error');
       }
   }else{
       return Redirect::to('login')->withInput()->withErrores($validator);
   }
}));

Route::get('home',array('before'=>'auth',function()
{
    return view('home');
}));

Route::get('logout',array('before'=>'auth',function()
{
    Auth::logout();
    return Redirect::to('/');
}));