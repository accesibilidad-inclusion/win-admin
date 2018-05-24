<?php

namespace App\Http\Controllers;

use App\Institution;
use App\AddressComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class InstitutionController extends Controller
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
        $institutions = Institution::latest()->paginate( 10 );
        return view('institutions.index', [
            'institutions' => $institutions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('institutions.create', [
            'institution' => new Institution
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'geo.lat'      => 'numeric',
            'geo.lng'      => 'numeric',
            'geo.location' => 'json'
        ]);
        $institution             = new Institution;
        $institution->name       = $request->input('name');
        $institution->created_by = Auth::id();
        $institution->lat        = $request->input('geo.lat');
        $institution->lng        = $request->input('geo.lng');
        $institution->location   = json_decode( $request->input('geo.location') );
        $institution->save();

        $location = json_decode( $request->input('geo.location') );

        $this->saveAddressComponents( $institution, $location->address_components, collect() );

        return Redirect::route('institutions.edit', $institution, 303);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function show(Institution $institution)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function edit(Institution $institution, Request $request )
    {
        return view('institutions.create', [
            'institution' => $institution
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Institution $institution)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'geo.lat'      => 'numeric',
            'geo.lng'      => 'numeric',
            'geo.location' => 'json'
        ]);
        $institution->name       = $request->input('name');
        $institution->created_by = Auth::id();
        $institution->lat        = $request->input('geo.lat');
        $institution->lng        = $request->input('geo.lng');
        $institution->location   = json_decode( $request->input('geo.location') );
        $institution->save();

        $location = json_decode( $request->input('geo.location') );
        $this->saveAddressComponents( $institution, $location->address_components, AddressComponent::where('institution_id', $institution->id)->get() );

        return Redirect::route('institutions.edit', $institution, 303);
    }

    private function saveAddressComponents( Institution $institution, array $new_components, Collection $old_components ){
        $repeated_components = [];
        foreach ( $new_components as $component ) {
            foreach ( $component->types as $type ) {
                $was_component = $old_components->first(function( $value, $key ) use ( $type, $component ) {
                    return $value['type'] == $type && $value['short_name'] == $component->short_name;
                });
                if ( $was_component ) {
                    $repeated_components[] = $was_component->id;
                } else {
                    $new_address = new AddressComponent([
                        'institution_id' => $institution->id,
                        'type'           => $type,
                        'long_name'      => $component->long_name,
                        'short_name'     => $component->short_name
                    ]);
                    $new_address->save();
                }
            }
        }
        foreach ( $old_components as $component ) {
            if ( ! in_array( $component->id, $repeated_components ) ) {
                $component->delete();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function destroy(Institution $institution)
    {
        //
    }
}
