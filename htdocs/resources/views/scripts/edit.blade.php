@extends('questions.layout')

@section('content')
	<div class="container">
		<div class="col-sm-10">
			<div class="form">
				<div id="questions-spotlight">
					<div class="questions-spotlight__title">Buscar pregunta</div>
					<div class="selectize-container mb-2">
						<selectize v-model="options" v-on:selectized="addQuestion"></selectize>
					</div>
					<div class="btn-group mb-5" role="group" aria-label="Elementos">
						<button type="button" class="btn btn-secondary" v-on:click="addStage">Separación de etapa</button>
						<button type="button" class="btn btn-secondary" v-on:click="addOnboarding">Preguntas de Onboarding</button>
					</div>
					<draggable v-model="questions">
						<transition-group>
							<div :class="[ 'card', 'mb-3', element.container_class ? element.container_class : '' ]" v-for="element in questions" :key="element.id">
								<div class="card-body">
									<strong class="card-title">@{{ element.formulation }}</strong>
								</div>
							</div>
						</transition-group>
					</draggable>
				</div>
			</div>
			<form action="{{ route('scripts.update', $script ) }}" method="POST">
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-lg">
						{{ $script->exists ? 'Actualizar' : 'Crear' }}
					</button>
				</div>
				<input type="hidden" id="script__order" name="questions_order">
				{{ csrf_field() }}
				@if ( $script->exists )
					{{ method_field('PUT') }}
				@endif
			</form>
		</div>
	</div>
@endsection