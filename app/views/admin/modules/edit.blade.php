@extends('default')

@section('content')

<h1>Edit module:</h1>

	{{ Form::open([ 'url' => '/admin/modules/' ]) }}
		<ul class="edit-module">
			
			<li>
				{{ Form::label('code', 'Код') }}
				{{ Form::text('code', $module->code, [ 'class' => 'form-control' ]) }}
			</li>
			<li>
				{{ Form::label('version', 'Версия') }}
				{{ Form::text('version', $module->version, [ 'class' => 'form-control' ]) }}
			</li>
			<li>
				{{ Form::label('price', 'Цена') }}
				{{ Form::text('price', $module->price, [ 'class' => 'form-control' ]) }}
			</li>
			<li>
				{{ Form::label('downloads', 'К-во загрузок') }}
				{{ Form::text('downloads', $module->downloads, [ 'class' => 'form-control' ]) }}
			</li>
			<li>
				{{ Form::label('active', 'Активно') }}
				{{ Form::checkbox('active', $module->active) }}
			</li>
			<li>
				{{ Form::label('regular', 'Регулярный платёж') }}
				{{ Form::checkbox('regular', $module->regular_payment) }}
			</li>
			<li>
				<ul class="nav nav-tabs" role="tablist">
					<li class="active"><a href="#ru" role="tab" data-toggle="tab">Ru</a></li>
					<li><a href="#en" role="tab" data-toggle="tab">En</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane active" id="ru">
						{{ Form::label('name', 'Название') }}
						{{ Form::text('name', $module->name, [ 'class' => 'form-control' ]) }}

						{{ Form::label('description', 'Описание') }}
						{{ Form::textarea('description', $module->description) }}

						{{ Form::label('category', 'Категория') }}
						{{ Form::text('category', $module->category, [ 'class' => 'form-control' ]) }}
					</div>
					<div class="tab-pane" id="en">
						{{ Form::label('name', 'Название') }}
						{{ Form::text('name', $module->name, [ 'class' => 'form-control' ]) }}

						{{ Form::label('description', 'Описание') }}
						{{ Form::textarea('description', $module->description) }}

						{{ Form::label('category', 'Категория') }}
						{{ Form::text('category', $module->category, [ 'class' => 'form-control' ]) }}
					</div>
				</div>
				
			</li>
			<li>
				<ul id="draggablePanelList" class="list-unstyled">
				    <li class="panel panel-info">
				        <div class="panel-heading">You can drag this panel.</div>
				        <div class="panel-body">Content ...</div>
				    </li>

				    <li class="panel panel-info">
				        <div class="panel-heading">You can drag this panel too.</div>
				        <div class="panel-body">Content ...</div>
				    </li>
				</ul>
			</li>
			<li>
				<a href="{{ URL::to('/admin/modules') }}" class="back">Назад</a>
				{{ Form::submit('Сохранить', [ 'class' => 'login'] ) }}
				<a href="{{ URL::to('/admin/modules') }}" class="back login">Удалить</a>
			</li>
		</ul>
		
	{{ Form::close() }}
	



@stop