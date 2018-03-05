@extends('subjects.layout')

@section('content')
	@if ( count($subject->surveys) > 0 )
		@foreach ( $subject->surveys as $survey )
			@php
				var_dump( $survey );
			@endphp
		@endforeach
	@else
		<div class="container">
			<div class="alert alert-info" role="alert">El usuario aún no ha contestado el cuestionario</div>
		</div>
	@endif
@endsection