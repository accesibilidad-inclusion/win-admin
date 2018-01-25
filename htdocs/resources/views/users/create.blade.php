@extends('users.layout')

@section('content')
<div class="container">
	<div class="col-sm-10">
		<form action="{{ route('users.store') }}" method="POST" novalidate class="form">
			<div class="form-group">
				<label for="name" class="col-form-label">Nombre</label>
				<input name="name" id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" maxlength="255" value="{{ old('name') }}">
				@if ( $errors->has('name') )
					<div class="invalid-feedback">{{ $errors->first('name') }}</div>
				@endif
			</div>
			<div class="form-group">
				<label for="email" class="col-form-label">E-mail</label>
				<input name="email" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : ''}}" value="{{ old('email') }}">
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
			<div class="form-group">
				La contraseña será enviada al correo electrónico indicado
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-lg">Crear usuario</button>
			</div>
			{{ csrf_field() }}
		</form>
	</div>
</div>
@endsection