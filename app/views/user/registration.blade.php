@extends('default')

@section('content')

	<h2>Регистрация</h2>
	{{ Form::open() }}
		{{ Form::text('email', null, [ 'placeholder' => 'e-mail' ]) }} <br/>
		{{ Form::password('password', [ 'placeholder' => 'Пароль' ]) }} <br/>
		{{ Form::password('repeat_password', [ 'placeholder' => 'Повторите пароль' ]) }} <br/>
		{{ Form::submit('Регистрация', [ 'class' => 'login']) }}
	{{ Form::close() }}
	<a href="{{ URL::to('/') }}">Назад</a>

	<span class="error">
		{{  $errors->first(); }}
	</span>
@stop