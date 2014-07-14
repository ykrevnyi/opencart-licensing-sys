<h1>Module info:</h1>

<ul>
	<li>
		Module name is: <strong>{{ $module['title'] }}</strong>
	</li>

	<li>Category: {{ $module['category'] }}</li>
	<li>Last update: {{ $module['updated_at'] }}</li>
	<li>Price: {{ $module['min_price'] }}</li>
</ul>

@if (count($module['types']))
	<h4>Avalible plans:</h4>

	<ul>
		@foreach ($module['types'] as $type)
			<li>
				{{ $type['name'] }} - {{ $type['price'] }} $

				@if (isset($type['active']))
					- selected
				@endif
			</li>
		@endforeach
	</ul>
@endif