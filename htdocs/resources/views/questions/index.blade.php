@extends('layouts.app')

@section('sidebar')
	<nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar mt-3">
		<ul class="nav nav-pills flex-column">
			<li class="nav-item">
				<a href="{{ route('questions.index') }}" class="nav-link">Listado</a>
			</li>
			<li class="nav-item">
				<a href="{{ route('questions.create') }}" class="nav-link">Crear nueva pregunta</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	<div class="container">
		@foreach ( $questions as $question )
			<div class="card mb-3">
				<div class="card-body">
					<p class="h5 card-title">{{ $question->formulation }}</p>
					@if ( $question->category )
					<p><strong>Categoría:</strong> {{ $question->category->label }} </p>
					@endif
					@if ( count( $question->assistances ) > 0 )
					<p><strong>Áreas de Apoyo:</strong> {{ $question->assistances->implode('label', ', ') }} </p>
					@endif
						<form action="{{ route('questions.destroy', $question) }}" method="POST">
						{{ method_field('DELETE') }}
						{{ csrf_field() }}
						<a href="{{ route('questions.edit', ['question' => $question ]) }}" class="btn btn-primary">Editar</a>
						<button class="btn btn-danger">Delete</button>
					</form>
				</div>
			</div>
		@endforeach
	</div>
@endsection