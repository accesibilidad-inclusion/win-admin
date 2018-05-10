@extends('events.layout')

@section('content')
	<div class="container">

		<div class="col-sm-10">
			<form action="{{ $institution->exists ? route('institutions.update', $institution) : route('institutions.store') }}" class="form" method="POST">
				<div class="form-group">
					<label for="institution__name">Nombre de la institución</label>
					<input id="institution__name" name="name" type="text" class="form-control form-control-lg" value="{{ old('name', $institution->name) }}">
				</div>
				<div class="form-group">
					<p>Ubicación</p>
					<div class="gmap">
						<input type="text" class="gmap__search form-control" placeholder="Buscar dirección">
						<div class="gmap__map mt-3" style="width:100%;height:400px;background:#ddd;border:1px solid #ccc;">
						</div>
						<input type="hidden" class="gmap__lat" name="geo[lat]" value="{{ $institution->lat }}">
						<input type="hidden" class="gmap__lng" name="geo[lng]" value="{{ $institution->lng }}">
						<input type="hidden" class="gmap__location" name="geo[location]" value='@json( $institution->location )'>
					</div>
				</div>
				@if ( count( $institution->addressComponents ) )
					<p class="text-muted">Información geográfica asociada al geopunto:</p>
					<table class="table table-sm table-hover">
						<thead>
							<tr>
								<th>Tipo</th>
								<th>Nombre corto</th>
								<th>Nombre largo</th>
							</tr>
						</thead>
						<tbody>
							@foreach ( $institution->addressComponents as $component )
							<tr>
								<td><code>{{ $component->type }}</code></td>
								<td>{{ $component->short_name }}</td>
								<td>{{ $component->long_name }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				@endif
				{{ csrf_field() }}
				@if ( $institution->exists )
					{{ method_field('PUT') }}
					<div class="form-group">
						<button type="submit" class="btn btn-primary btn-lg">Actualizar</button>
					</div>
				@else
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-lg">Guardar</button>
				</div>
				@endif
			</form>
		</div>
	</div>
@endsection

@section('footer_scripts')
	<script src="{{ mix('js/institutions.js') }}"></script>
	<script src="https://maps.googleapis.com/maps/api/js?v=3&amp;hl=es&amp;libraries=places&amp;key={{ env('GOOGLE_API_KEY') }}"></script>
@endsection