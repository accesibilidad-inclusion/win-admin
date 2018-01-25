@extends('users.layout')

@section('content')
	<div class="container">
	@if ( Request::input('created') )
		<div class="alert alert-success" role="alert">El usuario ha sido creado correctamente</div>
	@endif
	@foreach ( $users as $user )
		<div class="card mb-3">
			<div class="card-body">
				<p class="h5 card-title">{{ $user->name }}</p>
				<p><strong>E-mail:</strong> {{ $user->email }} </p>
				<form action="{{ route('users.destroy', $user) }}" method="POST">
					{{ method_field('DELETE') }}
					{{ csrf_field() }}
					<a href="{{ route('users.edit', ['user' => $user ]) }}" class="btn btn-primary">Editar</a>
					<button class="btn btn-danger">Delete</button>
				</form>
			</div>
		</div>
	@endforeach
	</div>
@endsection