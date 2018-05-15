@extends('questions.layout')

@section('content')
	<div class="container-fluid">
		<form action="{{ route('questions.index') }}" method="GET" class="form mb-3">
			<div class="row d-flex align-items-end">
				<div class="col-12">
					<h2>Filtrar</h2>
				</div>
				<div class="col">
					<label for="question__formulation">Buscar por enunciado</label>
					<input id="question__formulation" type="text" name="formulation" class="form-control" placeholder="Enunciado de la pregunta" value="{{ $request->get('formulation') }}">
				</div>
				<div class="col">
					<label for="question__dimension">Dimensión o Indicador</label>
					<select name="dimension_id" id="question__dimension" class="form-control">
						<option value=""></option>
						@foreach ( $dimensions as $dimension )
						<option value="{{ $dimension->id }}"{{ $request->get('dimension_id') == $dimension->id ? ' selected="selected"' : '' }}>{{ $dimension->parent_id == 0 ? $dimension->label : '&nbsp;&nbsp;&nbsp;&nbsp;'. $dimension->label }}</option>
						@endforeach
					</select>
				</div>
				<div class="col">
					<label for="question__category">Categoría</label>
					<select name="category_id" id="question__category" class="form-control">
						<option value=""></option>
						@foreach ( $categories as $category )
						<option value="{{ $category->id }}"{{ $request->get('category_id') == $category->id ? ' selected="selected"' : '' }}>{{ $category->label }}</option>
						@endforeach
					</select>
				</div>
				<div class="col">
					<label for="question__assistance">Área de apoyo</label>
					<select name="assistance" id="question__assistance" class="form-control">
						<option value=""></option>
						@foreach ( $assistances as $assistance )
						<option value="{{ $assistance->id }}"{{ $request->get('assistance') == $assistance->id ? ' selected="selected"' : '' }}>{{ $assistance->label }}</option>
						@endforeach
					</select>
				</div>
				<div class="col">
					<div class="btn-group" role="group">
						<button type="submit" class="btn btn-primary">Filtrar</button>
					</div>
				</div>
			</div>
		</form>
		@if ( $questions && ! empty( array_filter( $request->all() ) ) )
		<div class="mb-3">
			<div class="alert alert-info">
				Mostrando {{ $questions->total() }} preguntas
			</div>
		</div>
		@endif
	</div>
	<div class="container">
		@foreach ( $questions as $question )
			<div class="card mb-3">
				<div class="card-body">
					<p class="h5 card-title">{{ $question->id }}. {{ $question->formulation }}</p>
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