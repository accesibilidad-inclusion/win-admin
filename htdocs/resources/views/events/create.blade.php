@extends('events.layout')

@section('content')
	<div class="container">
		<div class="col-sm-10">
			<form action="{{ $event->exists ? route('events.update', $event ) : route('events.store') }}" class="form" novalidate="novalidate" method="POST">
				<div class="form-group">
					<label for="event__label">Nombre del evento <small class="text-muted">(opcional)</small></label>
					<input name="label" id="event__label" type="text" class="form-control" value="{{ old('label', $event->label ) }}">
				</div>
				@if ( $event->exists )
				<div class="form-group mt-4 mb-4">
					<p class="h6">Link de Invitación:</p>
					<span class="h5"><a href="https://app.apoyos.win/#/event/{{ $event->hash }}" target="_blank">https://app.apoyos.win/#/event/{{ $event->hash }}</a></span>
					<div class="row mt-2">
						{{-- @todo: Definir e implementar mecanismos para compartir --}}
						<div class="col">
							<a href="mailto:?subject={{ urlencode('Invitación a WIN') }}&amp;body={{ urlencode('Ingresa a https://app.apoyos.win/#/event/'. $event->hash) }}">
								Enviar invitación por E-mail
							</a>
						</div>
						{{-- <div class="col">Compartir por blblb</div> --}}
						{{-- <div class="col">Compartir de otra forma</div> --}}
					</div>
				</div>
				@endif
				<div class="row">
					<div class="col-md">
						<div class="form-group">
							<label for="event__starts_at">Fecha de inicio</label>
							<input id="event__starts_at" name="starts_at" type="date" class="form-control w-50" value="{{ old('starts_at', $event->starts_at->format('Y-m-d') ) }}">
						</div>
					</div>
					<div class="col-md">
						<div class="form-group">
							<label for="event__ends_at">Fecha de término</label>
							<input id="event__ends_at" name="ends_at" type="date" class="form-control w-50" value="{{ old('ends_at', $event->ends_at->format('Y-m-d') ) }}">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="institution">Institución asociada</label>
					<select name="institution_id" id="event__institution" class="form-control">
						<option value=""></option>
						@foreach ( $institutions as $institution )
						<option value="{{ $institution->id }}"{{ $event->institution && $event->institution->id == $institution->id ? ' selected="selected"' : ''}}>{{ $institution->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<p>Estado</p>
					<div class="form-check form-check-inline">
						<label for="event__status--active" class="form-check-label">
							<input type="radio" class="form-check-input" id="event__status--active" name="status" value="active"{{ ( ! $event->exists || old('status', $event->status ) == 'active' ) ? ' checked="checked"' : '' }}>
							Activo
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label for="event__status--inactive" class="form-check-label">
							<input type="radio" class="form-check-input" id="event__status--inactive" name="status" value="inactive"{{ ( ! $event->exists || old('status', $event->status ) == 'inactive' ) ? ' checked="checked"' : '' }}>
							Cerrado
						</label>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-lg">
						{{ $event->exists ? 'Actualizar' : 'Crear' }}
					</button>
				</div>
				@if ( $event->exists )
					{{ method_field('PUT') }}
				@endif
				{{ csrf_field() }}
			</form>
		</div>
	</div>
@endsection