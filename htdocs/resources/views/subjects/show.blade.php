@extends('subjects.layout')

@section('content')
	@if ( count($subject->surveys) > 0 )
		<div class="container-fluid">
			<h2>{{ $subject->given_name }} {{ $subject->family_name }}</h2>
			<table class="table">
				<thead>
					<tr>
						<th>Sexo</th>
						<th>Fecha de Nacimiento</th>
						<th>Estudia</th>
						<th>Trabaja</th>
						<th>Discapacidad</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>@if ( $subject->sex == 'female' ) Mujer @elseif ( $subject->sex == 'male') Hombre @else Otro @endif</td>
						<td>{{ $subject->birthday->format('d F Y') }}</td>
						<td>@if ( $subject->studies ) Sí: {{ $subject->studies_at }}@else No @endif</td>
						<td>@if ( $subject->works ) Sí: {{ $subject->works_at }}@else No @endif</td>
						<td>{{ $subject->impairments->pluck('label')->implode(' / ') }}</td>
					</tr>
				</tbody>
			</table>
			@foreach ( $subject->surveys as $survey )
			<div class="card mb-3">
				<div class="card-body">
					<h5 class="card-title">
						Aplicación de {{ $survey->created_at->format('Y-m-d H:i:s') }}
					</h5>
					<table class="table table-striped">
						<tbody>
							<tr>
								<th scope="row">Edad al momento del examen</th>
								<td>{{ $subject->getRelativeAge( $subject->created_at )->y }} años, {{ $subject->getRelativeAge( $subject->created_at )->m }} meses</td>
							</tr>
							<tr>
								<th scope="row">Evento asociado</th>
								<td>
									Ninguno
								</td>
							</tr>
							<tr>
								<th scope="row">Puntaje general</th>
								<td>{{ $survey->results->aggregated->score }} ({{ $survey->getLevelLabel( $survey->results->aggregated->level ) }})</td>
							</tr>
							@foreach ( $survey->results->dimensions as $dimension )
							<tr>
								<th scope="row">{{ $dimension->label }}</th>
								<td>
									Puntaje: {{ $dimension->score }} (Nivel {{ $dimension->level }})
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<div class="mb-3">
						<div class="row">
						@foreach ( $survey->results->dimensions as $dimension )
							<div class="col-sm-3">
								<h6>{{ $dimension->label }}</h6>
								<div class="progress" style="height:2rem">
									<div class="progress-bar" style="background-color: @if ( $dimension->level == 'low' ) rgba(0, 123, 255, 0.3) @elseif ( $dimension->level == 'medium' ) rgba(0, 123, 255, 0.6) @else rgba(0, 123, 255, 1) @endif; width:{{ $dimension->max > 0 ? ( $dimension->score / $dimension->max ) * 100 : 0 }}%" role="progressbar" aria-valuenow="{{ $dimension->score }}" aria-valuemax="{{ $dimension->max }}" aria-valuemin="{{ $dimension->min }}" data-level="{{ $dimension->level }}">
										{{ $dimension->score }}/{{ $dimension->max }}
									</div>
								</div>
								<div class="row m-1">
									@foreach ( $dimension->answers as $answer )
									<div class="col-3" style="background-color: @if ( $answer->option->value > 4 ) rgba(0, 123, 255, 1) @elseif ( $answer->option->value > 2 ) rgba(0, 123, 255, 0.6) @else rgba(0, 123, 255, 0.3) @endif; height:1.5rem;box-shadow: inset 1px 1px 0 #fff, inset -1px -1px 0 #fff;" title="{{ $answer->question->id }}. {{ $answer->question->formulation }}"></div>
									@endforeach
								</div>
							</div>
						@endforeach
						</div>
					</div>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>ID</th>
								<th>Pregunta</th>
								<th>Respuesta</th>
								<th>Lugar</th>
								<th>Ayudas</th>
								<th>Tiempo de respuesta</th>
							</tr>
						</thead>
						<tbody>
						@foreach ( $survey->results->answers as $answer )
							<tr>
								<td>{{ $answer->question->id }}</td>
								<td>{{ $answer->question->formulation }}</td>
								<td>{{ $answer->option->value }} - {{ $answer->option->label }}</td>
								<td>{{ implode(' + ', $answer->specification ) }}</td>
								<td>{{ $answer->aids->pluck('label')->implode(' / ') }}</td>
								<td>{{ $answer->response_time }} segundos</td>
							</tr>
						@endforeach
						</tbody>
					</table>
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