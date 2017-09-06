<?php
use App\User;

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
           return Redirect::to('login')->withInput()->with('message', array('type' => 'danger', 'content' => 'E-mail or password error'));
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

Route::get('register',function ()
{
    return view('users.create');
});

Route::post('register',array('before'=>'csrf',function()
{
    $rules=array(
        'email'=>'required|email|unique:users,email',
        'nickname'=>'required|min:3|unique:users,nickname',
        'password'=>'required|min:4|confirmed',
    );
    $validator=Validator::make(Input::all(),$rules);
    if($validator->passes())
    {
        $user=User::create(Input::only('email','password','nickname'));
        $user->password=\Illuminate\Support\Facades\Hash::make(Input::get('password'));
        if($user->save())
        {
            return Redirect::to('login')->with('message',array('type'=>'success','content'=>'Register successfully,please login'));
        }else{
            return Redirect::to('register')->withInput()->with('message',array('type'=>'danger','content'=>'Register failed'));
        }
    }else{
        return Redirect::to('register')->withInput()->withErrors($validator);
    }
}));

Route::get('user/{id}/edit',array('before'=>'auth','as'=>'user.edit',function($id)
{
    if(Auth::user()->is_admin or Auth::id()==$id){
        return view('users.edit')->with('user',User::find($id));
    }else{
        return Redirect::to('/');
    }
}));


Route::put('user/{id}', array('before' => 'auth|csrf', function($id)
{
    if (Auth::user()->is_admin or (Auth::id() == $id)) {
        $user = User::find($id);
        $rules = array(
            'password' => 'required_with:old_password|min:4|confirmed',
            'old_password' => 'min:4',
        );
        if (!(Input::get('nickname') == $user->nickname))
        {
            $rules['nickname'] = 'required|min:3||unique:users,nickname';
        }
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes())
        {
            if (!(Input::get('old_password') == '')) {
                if (!Hash::check(Input::get('old_password'), $user->password)) {
                    return Redirect::route('user.edit', $id)->with('user', $user)->with('message', array('type' => 'danger', 'content' => 'Old password error'));
                } else {
                    $user->password = Hash::make(Input::get('password'));
                }
            }
            $user->nickname = Input::get('nickname');
            $user->save();
            return Redirect::route('user.edit', $id)->with('user', $user)->with('message', array('type' => 'success', 'content' => 'Modify successfully'));
        } else {
            return Redirect::route('user.edit', $id)->withInput()->with('user', $user)->withErrors($validator);
        }
    } else {
        return Redirect::to('/');
    }
}));