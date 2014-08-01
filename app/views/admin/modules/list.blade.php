@extends('default')

@section('content')

<h1>Module info:</h1>


<table class="table table-striped module-list-table">
	<thead>
		<tr>
			<th>ID</th>
			<th>Название</th>
			<th>Описание</th>
			<th>Версия</th>
			<th>Категория</th>
			<th>Файл</th>
		</tr>
	</thead>
	<tbody>

	@foreach ($modules as $modul)
		<tr>

			<td>{{ $modul->id }}</td>
			<td>{{ $modul->name }}</td>
			<td>{{ $modul->description }}</td>
			<td>{{ $modul->version }}</td>
			<td>{{ $modul->category }}</td>
			<td><a href="/admin/modules/download/{{$modul->code}}" target="_blank">скачать {{ $modul->code }}</a></td>
			<td><a href="/admin/modules/{{$modul->code}}/edit" class="btn btn-default">Edit</a></td>
		</tr>
	@endforeach
	</tbody>
</table>



@stop



