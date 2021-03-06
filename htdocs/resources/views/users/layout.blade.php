@extends('layouts.app')

@section('sidebar')
	<nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar mt-3">
		<ul class="nav nav-pills flex-column">
			<li class="nav-item">
				<a href="{{ route('users.index') }}" class="nav-link{{ Request::routeIs('users.index') ? ' active' : '' }}">Ver investigadores</a>
			</li>
			<li class="nav-item">
				<a href="{{ route('users.create') }}" class="nav-link{{ Request::routeIs('users.create') ? ' active' : '' }}">Crear nuevo investigador</a>
			</li>
		</ul>
	</nav>
@endsection