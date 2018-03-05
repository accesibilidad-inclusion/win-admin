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
					<div style="width:100%;height:400px;background:#ddd;border:1px solid #ccc;">
					</div>
				</div>
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