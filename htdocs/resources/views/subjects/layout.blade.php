@extends('layouts.app')

@section('sidebar')
<nav class="col-sm-3 col-md-2 bg-light sidebar mt-3">
	<ul class="nav nav-pills flex-column">
		<li class="nav-item">
			<a href="{{ route('subjects.index') }}" class="nav-link{{ Request::routeIs('subjects.index') || Request::routeIs('subjects.show') ? ' active' : ''}}">Usuarios</a>
		</li>
		<li class="nav-item">
			<a href="{{ route('subjects.create') }}" class="nav-link{{ Request::routeIs('subjects.create') ? ' active' : ''}}">Crear nuevo usuario</a>
		</li>
	</ul>
</nav>
@endsection