<?php

namespace App\Http\Controllers;

use App\Question;
use App\Assistance;
use App\Category;
use App\Dimension;
use Illuminate\Http\Request;
use App\Http\Requests\StoreQuestion;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionsController extends Controller
{

	use SoftDeletes;

	public function __construct()
	{
		$this->middleware('auth');
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return view('questions.index', [
			'questions' => Question::latest()->take(10)->get()
		]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$dimensions = Dimension::all();
		$dimensions_by_parent = $dimensions->groupBy('parent_id');
		$dimensions_options = [];
		foreach ( $dimensions_by_parent->get(0) as $val ) {
			$dimensions_options[ $val->id ] = [
				'optlabel' => $val->label,
				'options'  => []
			];
		}
		foreach ( $dimensions_options as $parent_id => $options ) {
			foreach ( $dimensions_by_parent->get( $parent_id ) as $val ) {
				$dimensions_options[ $val->parent_id ]['options'][ $val->id ] = $val->label;
			}
		}

        return view('questions.create', [
			'assistances' => Assistance::all(),
			'categories'  => Category::all(),
			'dimensions'  => $dimensions_options
		]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestion $request)
    {
		dd( $request );

		$question = new Question;
		$question->formulation = $request->input('formulation');
		$question->needs_specification = $request->input('needs_specification');
		$question->specification = $request->input('specification');

		$dimension   = Dimension::find( $request->input('dimension' ) );
		$question->dimension()->associate( $dimension );

		$category    = Category::find( $request->input('category') );
		$question->category()->associate( $category );

		$question->save();

		$question->assistances()->sync( $request->input('assistances') );

		$options_yes = [];
		$i = 1;
		foreach ( $request->input('options_yes') as $option ) {
			$options_yes[] = [
				'type'  => 'yes',
				'label' => $option,
				'order' => $i
			];
			++$i;
		}

		$question->options()->createMany( $options_yes );

		$options_no = [];
		$i = 1;
		foreach ( $request->input('options_no') as $option ) {
			$options_no[] = [
				'type'  => 'no',
				'label' => $option,
				'order' => $i
			];
			++$i;
		}
		$question->options()->createMany( $options_no );

		dd( $question );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        dd( $question, $question->assistances );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
		return redirect( route('questions.index'), 302 );
    }
}
