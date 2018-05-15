<?php

namespace App\Http\Controllers;

use App\Script;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ScriptController extends Controller
{
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

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function show(Script $script)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function edit(Script $script)
    {
        return view('scripts.edit', ['script' => $script ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Script $script)
    {
        $ordered         = json_decode( $request->get('questions_order') );
        $questions_order = [];
        $stage   = 0;
        foreach ( $ordered as $item ) {
            if ( stripos( $item->id, 'stage' ) !== false ){
                $stage++;
                continue;
            }
            $questions_order[ $stage ][] = $item->id;
        }
        $script->questions_order = $questions_order;
        $script->save();
        return Redirect::route( 'scripts.edit', $script, 303 );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function destroy(Script $script)
    {
        //
    }
}
