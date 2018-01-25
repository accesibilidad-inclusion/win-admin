@extends('questions.layout')

@section('content')
	<div class="container">
		<div class="col-sm-10">
			<form action="{{ $question->exists ? route('questions.update', $question ) : route('questions.store') }}" method="POST" class="form" novalidate>
				<div class="form-group">
					<label for="formulation" class="col-form-label">Enunciado</label>
					<input type="text" class="form-control form-control-lg{{ $errors->has('formulation') ? ' is-invalid' : '' }}" id="formulation" name="formulation" value="{{ old('formulation', $question->formulation) }}" required maxlength="255">
					@if ( $errors->has('formulation') )
						<div class="invalid-feedback">{{ $errors->first('formulation') }}</div>
					@endif
				</div>
				<div class="form-group">
					<label for="needs_specification" class="form-check-label">
						<input type="checkbox" name="needs_specification" id="needs_specification" class="form-check-input"{{ old('needs_specification', $question->needs_specification ) ? ' checked="checked"': '' }}>
						 ¿Necesita especificación?
					</label>
				</div>
				<div class="form-group">
					<label for="specification">Especificación</label>
					<input type="text" name="specification" id="specification" class="form-control{{ $errors->has('specification') ? ' is-invalid' : '' }}" placeholder="¿Dónde o cuándo?" maxlength="255" value="{{ old('specification', $question->specification) }}">
					@if ( $errors->has('specification') )
						<div class="invalid-feedback">{{ $errors->first('specification') }}</div>
					@endif
				</div>
				<div class="form-group">
					<label for="dimension" class="col-form-label">Dimensión e Indicador</label>
					<select name="dimension" id="dimension" class="form-control">
						@foreach ( $dimensions as $dimension )
						<optgroup label="{{ $dimension['optlabel'] }}">
							@foreach ( $dimension['options'] as $key => $label )
							<option value="{{ $key }}"{{ old('dimension', $question->dimension ? $question->dimension->id : null ) == $key ? ' selected="selected"' : '' }}>
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
						<option value="{{ $category->id }}"{{ old('category') == $category->id ? ' selected="selected"' : '' }}>
							{{ $category->label }}
						</option>
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
									<input class="form-check-input" type="checkbox" name="assistances[]" value="{{ $assistance->id }}"{{ in_array( $assistance->id, (array) old('assistances', $question->assistances->pluck('id')->toArray() ) ) ? ' checked="checked"' : '' }}>
									 {{ $assistance->label }}
								</label>
							</div>
						</div>
						@endforeach
					</div>
				</div>

				<div class="form-group">
					<label for="option_yes_1">Respuestas "Sí"</label>
					@for ( $i = 1; $i < 4; $i++ )
					<div class="form-group">
						<div class="input-group options__yes">
							<span class="answer-options input-group-addon">{{ $i }}</span>
							<input id="option_yes_{{ $i }}" class="form-control{{ $errors->has("options_yes.{$i}") ? ' is-invalid' : '' }}" name="options_yes[{{$i}}]" placeholder="Opción Sí {{$i}}" required type="text" maxlength="255" value="{{ old('options_yes.'. $i, $question->options->where('type', 'yes')->pluck('label')->get( $i - 1 ) )}}">
						</div>
					</div>
					@endfor
				</div>
				<div class="form-group">
					<label for="option_no_1">Respuestas "No"</label>
					@for ( $i = 1; $i < 4; $i++ )
					<div class="form-group">
						<div class="input-group options__no">
							<span class="answer-options input-group-addon">{{ $i }}</span>
							<input id="option_no_{{ $i }}" class="form-control{{ $errors->has("options_no.{$i}") ? ' is-invalid' : '' }}" name="options_no[{{$i}}]" placeholder="Opción No {{$i}}" required type="text" maxlength="255" value="{{ old('options_no.'. $i, $question->options->where('type', 'no')->pluck('label')->get( $i - 1 ) ) }}">
						</div>
					</div>
					@endfor
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-lg">
						{{ $question->exists ? 'Actualizar' : 'Crear' }}
					</button>
				</div>
				{{ csrf_field() }}
				@if ( $question->exists )
					{{ method_field('PUT') }}
				@endif
			</form>
		</div>
	</div>
@endsection