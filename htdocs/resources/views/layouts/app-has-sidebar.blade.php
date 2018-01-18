@extends('layouts.app')

@section('sidebar')
<nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar mt-3">
	<ul class="nav nav-pills flex-column">
		<li class="nav-item">
			<a class="nav-link active" href="#">Overview <span class="sr-only">(current)</span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#">Reports</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#">Analytics</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#">Export</a>
		</li>
	</ul>
</nav>
@endsection