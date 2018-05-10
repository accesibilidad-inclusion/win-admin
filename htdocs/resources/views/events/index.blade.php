@extends('events.layout')

@section('content')
	<div class="container">
		@foreach ( $events as $event )
			<div class="card mb-3">
				<div class="card-body">
					<p class="h5 card-title">{{ $event->label }}</p>
					@if ( $event->institution )
					<p class="text-muted">Institución: {{ $event->institution->name }}</p>
					@endif
					<form action="{{ route('events.destroy', $event) }}" method="POST">
						{{ method_field('DELETE') }}
						{{ csrf_field() }}
						<a href="{{ route('events.edit', ['event' => $event ]) }}" class="btn btn-primary">Editar</a>
						<button class="btn btn-danger" type="submit">Eliminar</button>
					</form>
				</div>
			</div>
		@endforeach
	</div>
@endsection