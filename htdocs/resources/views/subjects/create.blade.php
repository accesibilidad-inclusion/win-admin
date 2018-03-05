@extends('subjects.layout')

@section('content')
	<div class="container">
		@if ( Request::input('created') )
			<div class="alert alert-success" role="alert">El usuario ha sido creado correctamente</div>
		@elseif ( Request::input('updated') )
			<div class="alert alert-success" role="alert">El usuario ha sido editado correctamente</div>
		@endif
		<div class="col-sm-10">
			<form action="{{ $subject->exists ? route('subjects.update', $subject) : route('subjects.store') }}" method="POST" class="form" novalidate>
				<div class="row">
					<div class="col-md">
						<div class="form-group">
							<label for="subject__given-name">Nombres</label>
							<input name="given_name" id="subject__given-name" type="text" class="form-control form-control-lg" value="{{ old('given_name', $subject->given_name) }}">
						</div>
					</div>
					<div class="col-md">
						<div class="form-group">
							<label for="subject__family-name">Apellidos</label>
							<input name="family_name" id="subject__family-name" type="text" class="form-control form-control-lg" value="{{ old('family_name', $subject->family_name) }}">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="subject__personal-id">Documento de Identidad</label>
					<input name="personal_id" id="subject__personal-id" type="text" class="form-control w-25" value="{{ old('personal_id', $subject->personal_id) }}">
				</div>
				<div class="form-group">
					<p>Sexo</p>
					<div class="form-check form-check-inline">
						<label><input type="radio" name="sex" value="female"{{ old('sex', $subject->sex) == 'female' ? ' checked="checked"' : ''}}> Mujer</label>
					</div>
					<div class="form-check form-check-inline">
						<label><input type="radio" name="sex" value="male"{{ old('sex', $subject->sex) == 'male' ? ' checked="checked"' : ''}}> Hombre</label>
					</div>
				</div>
				<div class="row">
					<div class="col-sm">
						<div class="form-group">
							<p>Ocupación</p>
							<div class="form-check form-check-inline">
								<label><input name="works" value="1" type="checkbox"{{ old('works', $subject->works) ? ' checked="checked"' : '' }}> Trabaja</label>
							</div>
							<div class="form-check form-check-inline">
								<label><input name="studies" value="1" type="checkbox"{{ old('studies', $subject->studies) ? ' checked="checked"' : '' }}> Estudia</label>
							</div>
						</div>
					</div>
					<div class="col-sm">
						<div class="form-group">
							<label for="subject__studies-at">Lugar de estudios</label>
							<input id="subject__studies-at" name="studies_at" type="text" class="form-control" value="{{ old('studies_at', $subject->studies_at) }}">
						</div>
					</div>
				</div>
				<div class="form-group">
					<p>Discapacidad</p>
					@foreach ( $impairments as $impairment )
					<div class="form-check form-check-inline">
						<label><input type="checkbox" name="impairments[]" value="{{ $impairment->id }}"{{ in_array( $impairment->id, old('impairments', $subject->impairments->pluck('id')->toArray() ) ) ? ' checked="checked"' : '' }}> {{ $impairment->label }}</label>
					</div>
					@endforeach
				</div>
				@if ( $subject->exists )
				{{ method_field('PUT') }}
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-large">Actualizar</button>
				</div>
				@else
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-large">Guardar</button>
				</div>
				@endif
				{{ csrf_field() }}
			</form>
		</div>
	</div>
@endsection