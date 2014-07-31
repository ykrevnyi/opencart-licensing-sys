@extends('default')

@section('content')

<h2 class="main-title">Авторизация</h2>

			<section class="authorization-block">
				@if (Auth::check())
					<span class="user-name">Привет, {{ Auth::user()->email }}! </span>
					<a href="/logout" class="logout">Выйти</a>
				@else
					{{ Form::open([ 'url' => 'login' ]) }}
						{{ Form::text('email', null, [ 'placeholder' => 'e-mail' , 'class' => 'form-control' ]) }}
						{{ Form::password('password', [ 'placeholder' => 'Пароль', 'class' => 'form-control']) }}
						{{ Form::submit('Войти', [ 'class' => 'login'] ) }}
						<span class="reg-block">или <a href="/registration">зарегистрироваться</a></span>
					{{ Form::close() }}
					<span class="error">
						{{  $errors->first(); }}
					</span>

				@endif
			</section>

			<section style="clear:both"></section>
@stop