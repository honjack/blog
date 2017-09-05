@extends('layouts.default')

@section('main')
    <div class="am-g am-g-fixed">
        <div class="am-u-lg-6 am-u-md-8">
            <br/>

            {{ Form::open(array('url' => 'login', 'class' => 'am-form')) }}
            {{ Form::label('email', 'E-mail:') }}
            {{ Form::email('email', Input::old('email')) }}
            <br/>
            {{ Form::label('password', 'Password:') }}
            {{ Form::password('password') }}
            <br/>
            <label for="remember_me">
                <input id="remember_me" name="remember_me" type="checkbox" value="1">
                Remember Me
            </label>
            <br/>
            <div class="am-cf">
                {{ Form::submit('Login', array('class' => 'am-btn am-btn-primary am-btn-sm am-fl')) }}
            </div>
            {{ Form::close() }}
            <br/>
        </div>
    </div>
@stop