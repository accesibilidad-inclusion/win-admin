@extends('questions.layout')

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
		{{ $questions->links() }}
	</div>
@endsection