@extends('subjects.layout')

@section('content')
	@if ( count($subject->surveys) > 0 )
		<div class="container">
			@foreach ( $subject->surveys as $survey )
			<div class="card mb-3">
				<div class="card-body">
					<h5 class="card-title">
						Aplicación de {{ $survey->created_at->diffForHumans() }}
					</h5>
					<form action="{{ route('subjects.destroy', $subject) }}" method="POST">
							{{ method_field('DELETE') }}
							{{ csrf_field() }}
							<a href="#" class="btn btn-primary">Ver aplicación</a>
							<a href="{{ route('subjects.edit', ['subject' => $subject ]) }}" class="btn btn-secondary">Editar</a>
							<button class="btn btn-danger" type="submit">Eliminar</button>
						</form>
					</div>
				</div>
				@endforeach
		</div>
	@else
		<div class="container">
			<div class="alert alert-info" role="alert">El usuario aún no ha contestado el cuestionario</div>
		</div>
	@endif
@endsection