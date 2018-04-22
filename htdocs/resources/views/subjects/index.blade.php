@php
use App\Subject;
@endphp
@extends('subjects.layout')

@section('content')
	<div class="container-fluid">
		<form action="{{ route('subjects.index') }}" method="GET" class="form mb-3">
			<div class="row d-flex align-items-end">
				<div class="col-12">
					<h2>Filtrar</h2>
				</div>
				<div class="col">
					<label for="name">Buscar por nombre</label>
					<input type="text" name="name" class="form-control" placeholder="Nombre o apellido" value="{{ $request->get('name') }}">
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
					</div>
				</div>
			</div>
			{{ csrf_field() }}
		</form>
		@if ( $subjects )
		<div class="mb-3">
			<div class="alert alert-info">
				Mostrando resultados de {{ $subjects->total() }} personas
			</div>
		</div>
		@endif
	</div>
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