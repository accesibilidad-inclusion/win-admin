@extends('users.layout')

@section('content')
<div class="container">
	<div class="col-sm-10">
		<form action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}" method="POST" novalidate class="form">
			<div class="form-group">
				<label for="name" class="col-form-label">Nombre</label>
				<input name="name" id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" maxlength="255" value="{{ old('name', $user->name) }}">
				@if ( $errors->has('name') )
					<div class="invalid-feedback">{{ $errors->first('name') }}</div>
				@endif
			</div>
			<div class="form-group">
				<label for="email" class="col-form-label">E-mail</label>
				<input name="email" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : ''}}" value="{{ old('email', $user->email) }}">
				@if ( $errors->has('email') )
					<div class="invalid-feedback">{{ $errors->first('email') }}</div>
				@endif
			</div>
			{{--<div class="form-group">
				<label for="password" class="col-form-label">Password</label>
				<input name="password" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" aria-describedBy="password__help">
				<small id="password__help" class="small form-text text-muted">Mínimo 8 caracteres</small>
				@if ( $errors->has('password') )
					<div class="invalid-feedback">{{ $errors->first('password') }}</div>
				@endif
			</div>--}}
			@if ( ! $user->exists )
			<div class="form-group">
				La contraseña será enviada al correo electrónico indicado
			</div>
			@else
				{{ method_field('PUT') }}
			@endif
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-lg">
					{{ $user->exists ? 'Actualizar usuario' : 'Crear usuario' }}
				</button>
			</div>
			{{ csrf_field() }}
		</form>
	</div>
</div>
@endsection