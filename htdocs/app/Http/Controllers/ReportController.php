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
        $subjects = Subject::where( $subjects_query);
        if ( ! empty( $params['impairment'] ) ) {
            $subjects->leftJoin('impairment_subject', 'subjects.id', '=', 'impairment_subject.subject_id');
            $subjects->where('impairment_id', $params['impairment']);
        }
        $subjects = $subjects->get();
        $answers  = Answer::whereIn('subject_id', $subjects->pluck('id'))
            ->with(['question', 'option', 'aids', 'question.dimension', 'survey'])
            ->get();
        return (object) [
            'answers'  => $answers,
            'subjects' => $subjects
        ];
    }
    private function buildReportResults( array $params )
    {
        $dimensions = Dimension::all();
        $segment    = $this->getSegment( $params );
        $answers    = $segment->answers;
        $aggregated = [];
        foreach ( $answers as $answer ) {
            $dimension_id = $answer->question->dimension->parent_id === 0 ? $answer->question->dimension->parent_id : $answer->question->dimension->parent_id;
            $values_by_dimension[ $dimension_id ]['values'][] = $answer->option->value;
            $aggregated['values'][] = $answer->option->value;
        }
        $result_dimensions = [];
        foreach ( $values_by_dimension as $dimension_id => $val ) {
            $values_by_dimension[ $dimension_id ]['dimension'] = $dimensions->find( $dimension_id );
            $values_by_dimension[ $dimension_id ]['stats'] = Descriptive::describe( $val['values'] );
            $result_dimensions[] = (object) $values_by_dimension[ $dimension_id ];
        }
        $aggregated['stats'] = (object) Descriptive::describe( $aggregated['values'] );
        return (object) [
            'subjects_count' => $answers->pluck('subject_id')->unique()->count(),
            'dimensions'     => $values_by_dimension,
            'aggregated'     => (object) $aggregated,
        ];
    }
    private function getAgeRanges()
    {
        $ages = DB::table('subjects')
            ->selectRaw('COUNT(id) as count, TIMESTAMPDIFF( YEAR, birthday, CURDATE() ) AS age')
            ->groupBy('age')
            ->get();
        return $ages;
    }
}
