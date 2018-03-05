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
    public function index()
    {
        return view('subjects.index', [
            'subjects' => Subject::latest()->take(10)->get()
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
        $subject->load('surveys');
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
