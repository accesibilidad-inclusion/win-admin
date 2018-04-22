<?php

namespace App\Http\Controllers;

use App\Subject;
use App\Impairment;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSubject;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectController extends Controller
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
    public function index( Request $request )
    {
        $params = array_filter( $request->all(), function ( $item ){
            return ! is_null( $item );
        });
        unset( $params['_token'], $params['impairment'], $params['page'] );

        if ( empty( $params ) ) {
            $subjects = Subject::latest()->paginate( 10 );
        } else {
            unset( $params['name'] );
            $subjects = Subject::where( $params )->orderBy('created_at', 'desc');
            if ( $request->get('name') ) {
                $subjects->where('given_name', 'like', '%'. $request->get('name') .'%');
                $subjects->orWhere('family_name', 'like', '%'. $request->get('name') .'%');
            }
            if ( $request->get('impairment') ) {
                $subjects->leftJoin('impairment_subject', 'subjects.id', '=', 'impairment_subject.subject_id');
                $subjects->where('impairment_id', $request->get('impairment'));
            }
            $subjects = $subjects->paginate( 10 );
            foreach ( $params as $key => $val ) {
                $subjects->appends( $key, $val );
            }
            if ( $request->get('impairment') ) {
                $subjects->appends('impairment', $request->get('impairment'));
            }
            if ( $request->get('name') ) {
                $subjects->appends('name', $request->get('name'));
            }
        }

        $impairments = Impairment::all();
        return view('subjects.index', [
            'request' => $request,
            'subjects' => $subjects,
            'impairments' => $impairments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subject     = new Subject;
        $impairments = Impairment::latest()->take(30)->get();
        return view('subjects.create', [
            'subject'     => $subject,
            'impairments' => $impairments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreSubject  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubject $request)
    {
        $subject = new Subject;
        $subject->given_name = $request->input('given_name');
        $subject->family_name = $request->input('family_name');
        $subject->personal_id = $request->input('personal_id');
        $subject->sex = $request->input('sex');
        $subject->works = $request->input('works');
        $subject->studies = $request->input('studies');
        $subject->studies_at = $request->input('studies_at');
        $subject->save();
        $subject->impairments()->sync( $request->input('impairments') );
        return Redirect::route('subjects.edit', ['subject' => $subject, 'created' => 'ok'], 303);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        $subject->load('surveys')->paginate( 10 );
        return view('subjects.show', [
            'subject' => $subject
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        $impairments = Impairment::latest()->take(30)->get();
        return view('subjects.create', [
            'subject'     => $subject,
            'impairments' => $impairments
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSubject $request, Subject $subject)
    {
        $subject->given_name = $request->input('given_name');
        $subject->family_name = $request->input('family_name');
        $subject->personal_id = $request->input('personal_id');
        $subject->sex = $request->input('sex');
        $subject->works = $request->input('works');
        $subject->studies = $request->input('studies');
        $subject->studies_at = $request->input('studies_at');
        $subject->save();
        $subject->impairments()->sync( $request->input('impairments') );
        return Redirect::route('subjects.edit', ['subject' => $subject, 'updated' => 'ok'], 303);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect( route('subjects.index', ['deleted' => 'ok'], 302) );
    }
}
