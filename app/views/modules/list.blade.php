<h1>Module info:</h1>

<table class="table">
	<thead>
		<tr>
		<th>#</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Username</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1</td>
			<td>Mark</td>
			<td>Otto</td>
			<td>@mdo</td>
		</tr>
		<tr>
	</tbody>
</table>









<!-- 

<ul>
	<li>
		Module name is: <strong>{{ $module->name }}</strong>
	</li>

	<li>Category: {{ $module->category }}</li>
	<li>Last update: {{ $module->updated_at }}</li>
	<li>Price: {{ $module->min_price }}</li>
</ul>

@if (count($module->types))
	<h4>Avalible plans:</h4>

	<ul>
		@foreach ($module->types as $type)
			<li>
				{{ $type->name }} - {{ $type->price }} $

				@if (isset($type->active))
					- selected
				@endif
			</li>
		@endforeach
	</ul>
@endif -->