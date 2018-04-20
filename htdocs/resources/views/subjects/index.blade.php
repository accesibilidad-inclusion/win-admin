@extends('subjects.layout')

@section('content')
	<div class="container">
	@foreach ( $subjects as $subject )
		<div class="card mb-3">
			<div class="card-body">
				<h5 class="card-title">{{ $subject->given_name }} {{ $subject->family_name }}</h5>
				<form action="{{ route('subjects.destroy', $subject) }}" method="POST">
					{{ method_field('DELETE') }}
					{{ csrf_field() }}
					<a href="{{ route('subjects.show', ['subject' => $subject ]) }}" class="btn btn-primary">Ver resultados del usuario</a>
					<a href="{{ route('subjects.edit', ['subject' => $subject ]) }}" class="btn btn-secondary">Editar</a>
					<button class="btn btn-danger" type="submit">Eliminar</button>
				</form>
			</div>
		</div>
	@endforeach
	{{ $subjects->links() }}
	</div>
@endsection