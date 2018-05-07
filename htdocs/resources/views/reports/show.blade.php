@php
use App\Subject;
@endphp
@extends('subjects.layout')

@section('content')
<div class="container-fluid">
	<form action="{{ route('reports.show') }}" method="GET" class="form mb-3">
		<div class="row d-flex align-items-end">
			<div class="col-12">
				<h2>Filtrar</h2>
			</div>
			<div class="col">
				<label for="age_range">Rango de edad</label>
				<select name="age_range" id="age_range" class="form-control">
					<option value=""></option>
					<option value="0-11">0-11</option>
					<option value="12-18">12-18</option>
					<option value="19-24">19-24</option>
					<option value="25-30">25-30</option>
					<option value="31-35">31-35</option>
				</select>
			</div>
			<div class="col">
				<label for="sex">Sexo</label>
				<select name="sex" id="sex" class="form-control">
					<option value=""></option>
					@foreach ( Subject::getSexes() as $key => $val )
					<option value="{{ $key }}"{{ $request->get('sex') == $key ? ' selected="selected"' : '' }}>{{ $val }}</option>
					@endforeach
				</select>
			</div>
			<div class="col">
				<label for="event">Evento</label>
				<select name="event" id="event" class="form-control"></select>
			</div>
			<div class="col">
				<label for="works">Trabaja</label>
				<select name="works" id="works" class="form-control">
					<option value=""></option>
					<option value="1"{{ $request->get('works') === '1' ? ' selected="selected"' : '' }}>Sí</option>
					<option value="0"{{ $request->get('works') === '0' ? ' selected="selected"' : '' }}>No</option>
				</select>
			</div>
			<div class="col">
				<label for="studies">Estudia</label>
				<select name="studies" id="studies" class="form-control">
					<option value=""></option>
					<option value="1"{{ $request->get('studies') === '1' ? ' selected="selected"' : '' }}>Sí</option>
					<option value="0"{{ $request->get('studies') === '0' ? ' selected="selected"' : '' }}>No</option>
				</select>
			</div>
			<div class="col">
				<label for="impairment">Discapacidad</label>
				<select name="impairment" id="impairment" class="form-control">
					<option value=""></option>
					@foreach ( $impairments as $impairment )
					<option value="{{ $impairment->id }}"{{ $request->get('impairment') == $impairment->id ? ' selected="selected"' : ''}}>{{ $impairment->label }}</option>
					@endforeach
				</select>
			</div>
			<div class="col">
				<div class="btn-group" role="group">
					<button type="submit" class="btn btn-primary">Filtrar</button>
					<input type="submit" name="action" value="Exportar" class="btn btn-outline-secondary">
				</div>
			</div>
		</div>
	</form>
	@if ( $results )
	<div class="alert{{ $results->subjects_count ? ' alert-info' : ' alert-warninig'}}">
		Mostrando datos de {{ $results->subjects_count }} usuarios
	</div>
	<table class="mb-4 table table-striped table-hover">
		<thead>
			<th>Dimensión/Valor</th>
			<th>1</th>
			<th>2</th>
			<th>3</th>
			<th>4</th>
			<th>5</th>
			<th>6</th>
		</thead>
		<tbody>
			@foreach ( $results->dimensions as $dimension )
			<tr>
				<th scope="row">{{ $dimension['dimension']->label }}</th>
				@foreach ( $dimension['percents'] as $val => $percent )
				<td>{{ $percent }}%</td>
				@endforeach
			</tr>
			@endforeach
		</tbody>
		<tfooter>
{{-- 			<tr>
				<th scope="row">Total</th>
				<td>{{ $results->aggregated->stats->mean }}</td>
				<td>{{ implode(' / ', $results->aggregated->stats->mode ) }}</td>
				<td>{{ $results->aggregated->stats->median }}</td>
				<td>{{ $results->aggregated->stats->variance }}</td>
				<td>{{ $results->aggregated->stats->sd }}</td>
			</tr> --}}
		</tfooter>
	</table>
	@endif
</div>
@endsection