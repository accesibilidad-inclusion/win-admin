<?php

namespace App\Http\Controllers;

use App\Event;
use App\Answer;
use App\Subject;
use App\Dimension;
use App\Impairment;
use Box\Spout\Common\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Box\Spout\Writer\WriterFactory;
use MathPHP\Statistics\Descriptive;

class ReportController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
    public function show( Request $request )
    {
        $impairments = Impairment::all();
        $age_ranges  = $this->getAgeRanges();

        $params = array_filter( $request->all(), function ( $item ){
            return ! is_null( $item );
        });
        unset( $params['_token'], $params['action'] );

        if ( $request->get('action') == 'Exportar' ) {
            $this->generateExport( $params );
        }

        $events = Event::where([
            'status' => 'active'
        ])->get(['id', 'label']);

        $results = null;
        if ( ! empty( $params ) ) {
            $results = $this->buildReportResults( $params );
        }

        return view('reports.show', [
            'impairments' => $impairments,
            'ages'        => $age_ranges,
            'results'     => $results,
            'request'     => $request,
            'events'      => $events
        ]);
    }
    private function generateExport( array $params )
    {
        // $segment            = $this->getSegment( $params );
        $subjects_query = [];
        foreach ( $params as $key => $val ) {
            if ( $key == 'impairment' ) {
                continue;
            }
            $subjects_query[ $key ] = $val;
        }
        $answers = DB::table('answers')
            ->join('questions', 'answers.question_id', '=', 'questions.id')
            ->join('options', 'answers.option_id', '=', 'options.id')
            ->join('subjects', 'answers.subject_id', '=', 'subjects.id')
            ->where( $subjects_query )
            ->addSelect([
                'answers.subject_id',
                'subjects.sex',
                'subjects.birthday',
                'subjects.works',
                'subjects.studies',
                'answers.survey_id AS survey',
                'answers.question_id AS question',
                'options.value',
            ])
            ->get();
        $subject_impairments = DB::table('impairment_subject')
            ->whereIn('subject_id', $answers->pluck('subject_id')->unique() )
            ->orderBy('impairment_id', 'asc')
            ->get()
            ->groupBy('subject_id');
        $impairments = Impairment::all()->pluck('label', 'id')->toArray();
        $rows = [];
        foreach ( $answers as $answer ) {
            if ( ! isset( $rows[ $answer->survey ] ) ) {
                $rows[ $answer->survey ] = [
                    'survey'  => $answer->survey,
                    'subject' => $answer->subject_id,
                    'sex'     => $answer->sex,
                    'works'   => $answer->works,
                    'studies' => $answer->studies,
                ];
                if ( ! isset( $rows[ $answer->survey ]['impairments'] ) ) {
                    $survey_subject_impairments = $subject_impairments->get( $answer->subject_id );
                    if ( $survey_subject_impairments ) {
                        $survey_subject_impairments_ids = $survey_subject_impairments->pluck('impairment_id')->toArray();
                    } else {
                        $survey_subject_impairments_ids = [];
                    }
                    foreach ( $impairments as $key => $impairment ) {
                        $rows[ $answer->survey ]['impairments'][ $impairment ] = in_array( $key, $survey_subject_impairments_ids ) ? 1 : 0;
                    }
                }
            }
            $rows[ $answer->survey ]['questions'][ $answer->question ] = $answer->value;
        }
        // no necesito más esto
        unset( $answers, $subject_impairments, $impairments );

        $first = current( $rows );

        $headers = [];
        foreach ( $first as $key => $val ) {
            if ( is_array( $val ) ) {
                foreach ( $val as $col => $vals ) {
                    $headers[] = $col;
                }
            } else {
                $headers[] = $key;
            }
        }
        $headers = array_map(function( $input ){
            return ucwords( $input );
        }, $headers );

        // @todo exportación a XLS
        $writer = WriterFactory::create( Type::XLSX );
        $writer->openToBrowser( 'export-'. time().'.xlsx' );
        $writer->addRow( $headers );

        foreach ( $rows as $row ) {
            $survey = [];
            foreach ( $row as $key => $val ) {
                if ( is_array( $val ) ) {
                    foreach ( $val as $row_val ) {
                        $survey[] = $row_val;
                    }
                } else {
                    $survey[] = $val;
                }
            }

            $writer->addRow( $survey );

        }

        $writer->close();
        exit;

        rewind( $fp );
        // obtener contenido del archivo como un string
        $output = stream_get_contents( $fp );
        // cerrar archivo
        fclose( $fp );
        // cabeceras HTTP:
        // tipo de archivo y codificación
        header('Content-Type: text/csv; charset=utf-8');
        // forzar descarga del archivo con un nombre de archivo determinado
        header('Content-Disposition: attachment; filename=export-'. time() .'.csv' );
        // indicar tamaño del archivo
        header('Content-Length: '. strlen($output) );
        // enviar archivo
        echo $output;
        exit;
    }
    private function getSegment( array $params )
    {
        $subjects_query = [];
        foreach ( $params as $key => $val ) {
            if ( $key == 'impairment' ) {
                continue;
            }
            $subjects_query[ $key ] = $val;
        }
        $answers = DB::table('answers')
            ->join('questions', 'answers.question_id', '=', 'questions.id')
            ->join('dimensions', 'questions.dimension_id', '=', 'dimensions.id')
            ->join('options', 'answers.option_id', '=', 'options.id')
            ->join('subjects', 'answers.subject_id', '=', 'subjects.id')
            ->where( $subjects_query )
            ->groupBy(['dimensions.parent_id', 'options.value'])
            ->selectRaw('count(answers.subject_id) as q')
            ->addSelect(['options.value', 'dimensions.parent_id as dimension'])
            ->get();
        $subjects = Subject::where( $subjects_query )->count('id');
        return (object) [
            'answers'  => $answers,
            'subjects' => $subjects
        ];
    }
    private function buildReportResults( array $params )
    {
        $dimensions = Dimension::all();
        $segment    = $this->getSegment( $params );
        $values_by_dimension = [];
        foreach ( $segment->answers as $answer ) {
            $values_by_dimension[ $answer->dimension ]['by_value'][ $answer->value ] = $answer->q;
            if ( ! isset( $values_by_dimension[ $answer->dimension ]['dimension'] ) ) {
                $values_by_dimension[ $answer->dimension ]['dimension'] = $dimensions->find(  $answer->dimension );
            }
            if ( ! isset( $values_by_dimension[ $answer->dimension ]['total_answers'] ) ) {
                $values_by_dimension[ $answer->dimension ]['total_answers'] = $segment->answers->where( 'dimension', $answer->dimension )->pluck('q')->sum();
            }
            $values_by_dimension[ $answer->dimension ]['percents'][ $answer->value ] = round( $answer->q / $values_by_dimension[ $answer->dimension ]['total_answers'] * 100, 3 );
        }
        return (object) [
            'subjects_count' => $segment->subjects,
            'dimensions'     => $values_by_dimension
        ];
    }
    private function getAgeRanges()
    {
        $ages = DB::table('subjects')
            ->leftJoin('surveys', 'subjects.id', '=', 'surveys.subject_id')
            ->selectRaw('COUNT(surveys.id) as count, TIMESTAMPDIFF( YEAR, subjects.birthday, surveys.created_at ) AS age')
            ->groupBy('age')
            ->orderBy('age', 'ASC')
            ->get();
        return $ages;
    }
}
