<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Styles -->
	<link href="{{ asset('build/app.css') }}" rel="stylesheet">
</head>
<body>
	<header>
		<nav class="navbar navbar-expand-md navbar-dark bg-dark">
			<a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name') }}</a>
			<button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			@guest
			<div class="collapse navbar-collapse" id="navbarsExampleDefault">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
				</ul>
			</ul>
			@else
			<div class="collapse navbar-collapse" id="navbarsExampleDefault">
				<ul class="navbar-nav mr-auto">
					<!--<li class="nav-item d-none d-sm-inline-block">
						<a href="{{ route('home') }}" class="nav-link{{ Request::is('home') ? ' active' : '' }}">Tablero</a>
					</li>-->
					<li class="nav-item">
						<a href="{{ route('subjects.index') }}" class="nav-link{{ Request::is('subjects*') ? ' active' : '' }}">Resultados</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('events.index') }}" class="nav-link{{ Request::is('events*', 'institutions*') ? ' active' : '' }}">Eventos</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('questions.index') }}" class="nav-link{{ Request::is('questions*') ? ' active' : ''}}">Preguntas</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('users.index')}}" class="nav-link{{ Request::is('users*') ? ' active' : '' }}">Investigadores</a>
					</li>
				</ul>
			</ul>
			<div class="dropdown mr-2">
				<button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspoup="true" aria-expanded="false">
					{{ Auth::user()->name }}
				</button>
				<div class="dropdown-menu">
					<a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
						Logout
					</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
					</form>
				</div>
			</div>
			{{--
			<form class="form-inline mt-2 mt-md-0">
				<input class="form-control mr-sm-2" placeholder="Search" aria-label="Search" type="text">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
			--}}
			@endguest
		</div>
	</nav>
</header>
<div class="container-fluid">
	<div class="row">
		@section('sidebar')
		@show
		<main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
			@yield('content')
		</main>
	</div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
{{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="../../../../assets/js/vendor/popper.min.js"></script>
<script src="../../../../dist/js/bootstrap.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

@section('footer_scripts')
@show

</body>
</html>