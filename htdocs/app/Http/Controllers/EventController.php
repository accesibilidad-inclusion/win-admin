<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
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
        return view('events.index', [
            'events' => Event::latest()->take(10)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $event = new Event;
        $event->starts_at = new \DateTime;
        $event->ends_at = new \DateTime;
        $event->ends_at->add( new \DateInterval('P1D') );
        return view('events.create', [
            'event' => $event
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event = new Event;
        $event->label = $request->input('label');

        if ( ! empty( $request->input('starts_at') ) ) {
            $starts_at = new \DateTime( $request->input('starts_at') );
            $event->starts_at = $starts_at;
        }

        if ( ! empty( $request->input('ends_at') ) ) {
            $ends_at = new \DateTime( $request->input('ends_at') );
            $event->ends_at = $ends_at;
        }

        $event->created_by = Auth::id();

        $event->hash = str_random( 32 );

        $event->save();

        return Redirect::route('events.edit', $event, 303);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        return view('events.create', [
            'event' => $event
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
