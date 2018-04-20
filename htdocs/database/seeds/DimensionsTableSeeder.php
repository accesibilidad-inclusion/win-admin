<?php

use Illuminate\Database\Seeder;

class DimensionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$dimensions = [
			'Autonomía' => [
				'Realización de Elecciones',
				'Toma de Decisiones',
				'Resolución de Problemas'
			],
			'Autorregulación' => [
				'Establecimiento de Metas',
				'Autoinstrucción',
				'Autoevaluación'
			],
			'Creencias de Control-Eficacia' => [
				'Autodefensa',
				'Locus de Control',
				'Expectativas de Logro',
				'Atribuciones de Eficacia'
			],
			'Autorrealización' => [
				'Autoconocimiento'
			]
		];
		foreach ( $dimensions as $dimension => $indicators ) {
			$dimension_id = DB::table('dimensions')->insertGetId(
				[
					'label' => $dimension
				]
			);
			foreach ( $indicators as $indicator ) {
				DB::table('dimensions')->insert([
					'parent_id' => $dimension_id,
					'label' => $indicator
				]);
			}

		}
    }
}
