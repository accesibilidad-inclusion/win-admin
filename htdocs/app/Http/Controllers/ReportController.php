<?php

namespace App\Http\Controllers;

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

        $results = null;
        if ( ! empty( $params ) ) {
            $results = $this->buildReportResults( $params );
        }

        return view('reports.show', [
            'impairments' => $impairments,
            'ages'        => $age_ranges,
            'results'     => $results,
            'request'     => $request
        ]);
    }
    private function generateExport( array $params )
    {
        $segment            = $this->getSegment( $params );
        $answers_by_subject = $segment->answers->groupBy('subject_id');
        $rows = [];
        foreach ( $answers_by_subject as $subject_id => $answers ) {
            $subject = $segment->subjects->find($subject_id);
            foreach ( $subject->makeHidden([
                'id',
                'personal_id',
                'given_name',
                'family_name',
                'consent_at',
                'works_at',
                'studies_at',
                'last_connection_at',
                'deleted_at',
                'created_at',
                'updated_at'
            ])->toArray() as $key => $val ) {
                $rows[ $subject_id ][ $key ] = $val;
            }
            foreach ( $answers as $answer ) {
                $rows[ $subject_id ][ $answer->question->id ] = $answer->option->value;
            }
        }
        $fp = fopen('php://temp/maxmemory'. 16*1024*1024, 'r+');
        $headers = array_keys( current( $rows ) );
        array_unshift( $headers, 'subject' );

        // @todo exportación a XLS
        // $writer = WriterFactory::create( Type::XLSX );
        // $writer->openToBrowser( 'export-'. time().'.xlsx' );
        // $writer->addRow( $headers );
        // foreach ( $rows as $subject_id => $answers ) {
        //     \array_unshift( $answers, $subject_id );
        //     $writer->addRow( $answers );
        // }
        // $writer->close();

        \fputcsv( $fp, $headers );
        foreach ( $rows as $subject_id => $answers ) {
            \array_unshift( $answers, $subject_id );
            \fputcsv( $fp, $answers );
        }
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
