@extends('questions.layout')

@section('content')
	<div class="container">
		<div class="col-sm-10">
			<form action="" method="POST" class="form">
				<div id="questions-spotlight">
					<div class="questions-spotlight__title">Buscar pregunta</div>
					<selectize v-model="options"></selectize>
					<draggable v-model="questions">
						<transition-group>
							<div class="card mb-3" v-for="element in questions" :key="element.id">
								<div class="card-body">
									<strong class="card-title">@{{ element.formulation }}</strong>
								</div>
							</div>
						</transition-group>
					</draggable>
				</div>
			</form>
		</div>
	</div>
@endsection