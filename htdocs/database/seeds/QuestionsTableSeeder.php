<?php

use App\Option;
use App\Question;
use App\Category;
use App\Dimension;
use Illuminate\Database\Seeder;
use App\Assistance;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$questions_path = __DIR__ . '/../../fixtures/preguntas.csv';
		$handle = fopen( $questions_path, 'r' );
		if ( $handle === false ) {
			throw new Exception('Error al leer el archivo');
		}
		$columns = [
			'indicator',
			'category',
			'formulation',
			'assistances',
			'needs_specification',
			'spec_type_1',
			'spec_type_2',
			'option_yes_1',
			'option_yes_2',
			'option_yes_3',
			'option_no_1',
			'option_no_2',
			'option_no_3'
		];
		$categories  = Category::all();
		$dimensions  = Dimension::all();
		$assistances = Assistance::all();
		$idx = 0;
		while ( ( $data = fgetcsv( $handle, 9999 ) ) !== false ) {
			$row = array_combine( $columns, $data );

			$row['assistances'] = array_filter( explode("\n", $row['assistances']) );
			$row['assistances'] = array_map('mb_strtolower', $row['assistances']);
			if ( in_array( 'vida en la comunidad', $row['assistances'] ) ) {
				$idx = array_search( 'vida en la comunidad', $row['assistances'] );
				$row['assistances'][ $idx ] = 'vida en comunidad';
			}

			$question = new Question;
			$question->formulation = $row['formulation'];
			$question->needs_specification = $row['needs_specification'] == 'Sí';
			if ( $question->needs_specification ) {
				$question->specification = 'Dónde '. strtolower( $row['formulation'][0] ) . substr( $row['formulation'], 1 );
			}
			$question->order = $idx;

			$category = $categories->first( function( $category ) use ( $row ) {
				return mb_strtolower( $category->label ) == mb_strtolower( $row['category'] );
			});
			$dimension = $dimensions->first( function( $dimension ) use ( $row ){
				return mb_strtolower( $dimension->label ) == mb_strtolower( $row['indicator'] );
			});

			$question_assistances = $assistances->filter( function( $item ) use ( $row ){
				return in_array( mb_strtolower( $item->label ), $row['assistances'] );
			} );

			$question->category()->associate( $category );
			$question->dimension()->associate( $dimension );

			$question->save();

			$question->assistances()->sync( $question_assistances );

			$options = [];
			$opt_i = 1;
			for ( $i = 1; $i < 4; $i++ ) {
				$options[] = [
					'type' => 'yes',
					'order' => $opt_i,
					'value' => 6 - ( $i - 1 ),
					'label' => 'Sí, ' . $row["option_yes_{$i}"]
				];
				$options[] = [
					'type' => 'no',
					'order' => $opt_i,
					'value' => 3 - ( $i - 1 ),
					'label' => 'No, ' . $row["option_no_{$i}"]
				];
				$opt_i++;
			}
			$question->options()->createMany( $options );

			++$idx;
		}
		// $questions = [
		// 	[
		// 		'formulation' => 'Elijo cosas en mi día a día',
		// 		'needs_specification' => false,
		// 		'specification' => '¿Dónde o cuándo eliges cosas?',
		// 		'order' => 1,
		// 		'options' => [
		// 			'yes' => [
		// 				'Sí, puedo hacerlo sólo',
		// 				'Sí, sólo si me dan la oportunidad',
		// 				'Sí, lo hago con apoyo'
		// 			],
		// 			'no' => [
		// 				'No puedo hacerlo sólo',
		// 				'No, porque no me dan la oportunidad',
		// 				'No, aunque me apoyen'
		// 			]
		// 		],
		// 		'category' => 1,
		// 		'dimension' => 1,
		// 		'assistances' => [ 1 ]
		// 	],
		// 	[
		// 		'formulation' => 'Tomo decisiones importantes para mí',
		// 		'needs_specification' => true,
		// 		'specification' => '¿Dónde o cuándo tomas decisiones?',
		// 		'options' => [
		// 			'yes' => [
		// 				'Sí, puedo hacerlo sólo',
		// 				'Sí, sólo si me dan la oportunidad',
		// 				'Sí, lo hago con apoyo'
		// 			],
		// 			'no' => [
		// 				'No puedo hacerlo sólo',
		// 				'No, porque no me dan la oportunidad',
		// 				'No, aunque me apoyen'
		// 			]
		// 		],
		// 		'category' => 1,
		// 		'dimension' => 2,
		// 		'assistances' => [ 1 ]
		// 	],
		// 	[
		// 		'formulation' => 'Hago cosas solo',
		// 		'needs_specification' => true,
		// 		'specification' => '¿Dónde o cuándo haces cosas solo?',
		// 		'options' => [
		// 			'yes' => [
		// 				'Sí, las hago sin dificultad',
		// 				'Sí, sólo si me dan la oportunidad',
		// 				'Sí, las hago con apoyo'
		// 			],
		// 			'no' => [
		// 				'No, porque es difícil',
		// 				'No, porque no me dan la oportunidad',
		// 				'No, aunque me apoyen'
		// 			]
		// 		],
		// 		'category' => 1,
		// 		'dimension' => 4,
		// 		'assistances' => [ 1 ]
		// 	]
		// ];
		// $idx = 1;
		// foreach ( $questions as $i ) {
		// 	$question = new Question;
		// 	$question->formulation = $i['formulation'];
		// 	$question->needs_specification = $i['needs_specification'];
		// 	$question->order = $idx;
		// 	$question->save();

		// 	$category = Category::find( $i['category'] );
		// 	$question->category()->associate( $category );

		// 	$dimension = Dimension::find( $i['dimension'] );
		// 	$question->dimension()->associate( $dimension );

		// 	$question->assistances()->sync( $i['assistances'] );

		// 	$options = [];
		// 	foreach ( $i['options'] as $type => $opt ) {
		// 		$opt_i = 1;
		// 		foreach ( $opt as $label ) {
		// 			$option = [
		// 				'type' => $type,
		// 				'order' => $opt_i,
		// 				'label' => $label
		// 			];
		// 			$options[] = $option;
		// 			++$opt_i;
		// 		}
		// 	}
		// 	$question->options()->createMany( $options );
		// 	++$idx;
		// }
        // DB::table('questions')->insert([
		// 	'formulation' => 'Soy como quiero',
		// 	'needs_specification' => false,
		// 	'specification' => '',
		// 	'order' => 1,
		// 	'status' => 'visible'
		// ]);
		// DB::table('questions')->insert([
		// 	'formulation' => 'Controlo algunas cosas de mi vida',
		// 	'needs_specification' => true,
		// 	'specification' => '¿Dónde o cuándo controlas cosas?',
		// 	'order' => 2,
		// 	'status' => 'visible'
		// ]);
    }
}
