@extends('default')

@section('content')
<div class="box">
<h2 class="main-title">Авторизация</h2>

			<section class="authorization-block">
				@if (Auth::check())
					<span class="user-name">Привет, {{ Auth::user()->email }}! </span>
					<a href="/admin/logout" class="logout">Выйти</a>
				@else
					{{ Form::open([ 'url' => '/admin/login' ]) }}
						{{ Form::text('email', null, [ 'placeholder' => 'e-mail' , 'class' => 'form-control' ]) }}
						{{ Form::password('password', [ 'placeholder' => 'Пароль', 'class' => 'form-control']) }}
						{{ Form::submit('Войти', [ 'class' => 'login'] ) }}
						<span class="reg-block">или <a href="admin/registration">зарегистрироваться</a></span>
					{{ Form::close() }}
					
					<span class="error">
						@foreach ($errors->all() as $e)
							<li> {{ $e }} </li>
						@endforeach
					</span>

				@endif
			</section>

			<section style="clear:both"></section>
</div>
@stop