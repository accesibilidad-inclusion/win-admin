@extends('layouts.app-has-sidebar')

@section('sidebar')
<nav class="col-sm-3 col-md-2 bg-light sidebar mt-3">
	<ul class="nav nav-pills flex-column">
		<li class="nav-item">
			<a href="{{ route('events.index') }}" class="nav-link{{ Request::routeIs('events.index') ? ' active' : ''}}">Listado</a>
		</li>
		<li class="nav-item">
			<a href="{{ route('events.create') }}" class="nav-link{{ Request::routeIs('events.create') ? ' active' : ''}}">Crear nuevo evento</a>
		</li>
		<li class="nav-item">
			<a href="{{ route('institutions.index') }}" class="nav-link{{ Request::routeIs('institutions.index') || Request::routeIs('institutions.show') ? ' active' : ''}}">Instituciones</a>
		</li>
		<li class="nav-item">
			<a href="{{ route('institutions.create') }}" class="nav-link{{ Request::routeIs('institutions.create') || Request::routeIs('institutions.edit') ? ' active' : ''}}">Crear institución</a>
		</li>
	</ul>
</nav>
@endsection