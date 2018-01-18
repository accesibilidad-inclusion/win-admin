@extends('layouts.app-has-sidebar')

@section('sidebar')
	<nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar mt-3">
		<ul class="nav nav-pills flex-column">
			<li class="nav-item">
				<a href="{{ route('questions.index') }}" class="nav-link">Listado</a>
			</li>
			<li class="nav-item">
				<a href="{{ route('questions.create') }}" class="nav-link active">Crear nueva pregunta</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	<div class="container">
		<div class="col-sm-10">			
			<form action="{{ route('questions.store') }}" method="post" class="form">
				<div class="form-group">
					<label for="formulation" class="col-form-label">Enunciado</label>
					<input type="text" class="form-control form-control-lg" id="formulation" name="formulation">
				</div>
				<div class="form-group">
					<label for="needs_specification" class="form-check-label">
						<input type="checkbox" name="needs_specification" id="needs_specification" class="form-check-input">
						 ¿Necesita especificación?
					</label>
				</div>
				<div class="form-group">
					<label for="specification">Especificación</label>
					<input type="text" name="specification" id="specification" class="form-control" placeholder="¿Dónde o cuándo?">
				</div>
				<div class="form-group">
					<label for="dimension" class="col-form-label">Dimensión e Indicador</label>
					<select name="dimension" id="dimension" class="form-control">
						@foreach ( $dimensions as $dimension )
						<optgroup label="{{ $dimension['optlabel'] }}">
							@foreach ( $dimension['options'] as $key => $label )
							<option value="{{ $key }}">
								{{ $label }}
							</option>
							@endforeach
						</optgroup>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="category" class="col-form-label">Categoría</label>
					<select name="category" id="category" class="form-control">
						@foreach ( $categories as $category )
						<option value="{{ $category->id }}">{{ $category->label }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="assistance" class="col-form-label">Áreas de Apoyo</label>
					<div class="row">
						@foreach ( $assistances as $assistance )
						<div class="col-sm-3">
							<div class="form-check">
								<label class="form-check-label">
									<input class="form-check-input" type="checkbox" name="assistances[]" value="{{ $assistance->id }}">
									 {{ $assistance->label }}
								</label>
							</div>
						</div>
						@endforeach
					</div>
				</div>
				<div class="form-group">
					<label for="option_yes_1">Respuestas "Sí"</label>
					@for ( $i = 0; $i < 3; $i++ )
					<div class="form-group">
						<div class="input-group options__yes">
							<span class="answer-options input-group-addon">{{ $i+1 }}</span>
							<input id="option_yes_{{ $i+1 }}" class="form-control" name="options_yes[]" placeholder="Opción Sí {{$i+1}}" required type="text">
						</div>
					</div>
					@endfor
				</div>
				<div class="form-group">
					<label for="option_no_1">Respuestas "No"</label>
					@for ( $i = 0; $i < 3; $i++ )
					<div class="form-group">
						<div class="input-group options__no">
							<span class="answer-options input-group-addon">{{ $i+1 }}</span>
							<input id="option_no_{{ $i+1 }}" class="form-control" name="options_no[]" placeholder="Opción No {{$i+1}}" required type="text">
						</div>
					</div>
					@endfor
				</div>
				<div class="form-group">
					<input type="submit" value="Crear" class="btn btn-primary btn-lg">
				</div>
				{{ csrf_field() }}
			</form>
		</div>
	</div>
@endsection