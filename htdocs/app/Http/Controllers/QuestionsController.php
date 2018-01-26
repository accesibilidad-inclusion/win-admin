<?php

namespace App\Http\Controllers;

use App\Question;
use App\Category;
use App\Dimension;
use App\Assistance;
use Illuminate\Http\Request;
use App\Http\Requests\StoreQuestion;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Option;

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
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('questions.create', [
			'assistances' => Assistance::all(),
			'categories'  => Category::all(),
			'dimensions'  => $this->getDimensionsOptions(),
			'question'    => new Question
		]);
    }

	/**
	 * Get an array with all possible dimensions and indicators
	 * @return array Array of dimensions and indicators
	 */
	private function getDimensionsOptions()
	{
		$dimensions           = Dimension::all();
		$dimensions_by_parent = $dimensions->groupBy('parent_id');
		$dimensions_options   = [];
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
		return $dimensions_options;
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreQuestion $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestion $request)
    {
		$question = new Question;
		$question->formulation = $request->input('formulation');
		$question->needs_specification = $request->input('needs_specification');
		$question->specification = $request->input('specification');

		$dimension = Dimension::find( $request->input('dimension' ) );
		$question->dimension()->associate( $dimension );

		$category = Category::find( $request->input('category') );
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

		return Redirect::route('questions.index', ['created' => $question->id], 303);
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
		$question->load('assistances', 'options', 'dimension', 'category');
		return view('questions.create', [
			'assistances' => Assistance::all(),
			'categories'  => Category::all(),
			'dimensions'  => $this->getDimensionsOptions(),
			'question'    => $question
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreQuestion  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(StoreQuestion $request, Question $question)
    {
		$question->formulation = $request->input('formulation');
		$question->needs_specification = $request->input('needs_specification');
		$question->specification = $request->input('specification');
		$dimension = Dimension::find( $request->input('dimension' ) );
		$question->dimension()->associate( $dimension );

		$category = Category::find( $request->input('category') );
		$question->category()->associate( $category );

		$question->save();

		$question->assistances()->sync( $request->input('assistances') );

		$question->load('options');

		foreach ( ['yes', 'no'] as $type ) {
			foreach ( $request->input("options_{$type}") as $order => $label ) {
				$option = $question->options->where('type', $type)->firstWhere('order', $order);
				if ( ! $option ) {
					$option = new Option;
					$option->label = $label;
					$option->order = $order;
					$option->type  = $type;
					$option->question_id = $question->id;
				} else {
					$option->label = $label;
				}
				$option->save();
			}
		}

		return Redirect::route('questions.edit', $question, 303);
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
