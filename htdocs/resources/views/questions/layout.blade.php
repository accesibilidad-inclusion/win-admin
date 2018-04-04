@extends('layouts.app-has-sidebar')

@section('sidebar')
<nav class="col-sm-3 col-md-2 bg-light sidebar mt-3">
	<ul class="nav nav-pills flex-column">
		<li class="nav-item">
			<a href="{{ route('questions.index') }}" class="nav-link{{ Request::routeIs('questions.index') ? ' active' : ''}}">Listado</a>
		</li>
		<li class="nav-item">
			<a href="{{ route('questions.create') }}" class="nav-link{{ Request::routeIs('questions.create') ? ' active' : ''}}">Crear nueva pregunta</a>
		</li>
		<li class="nav-item">
			<a href="{{ route('scripts.edit', 1) }}" class="nav-link{{ Request::routeIs('scripts.edit') ? ' active' : ''}}">Ordenar guión</a>
		</li>
	</ul>
</nav>
@endsection