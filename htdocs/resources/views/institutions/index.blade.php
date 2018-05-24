@extends('events.layout')

@section('content')
    <div class="container">
        @foreach ( $institutions as $institution )
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $institution->name }}</h5>
                    @if ( isset($institution->location->formatted_address) )
                    <p class="text-muted">{{ $institution->location->formatted_address }}</p>
                    @endif
                    <form action="{{ route('institutions.destroy', $institution ) }}" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <a href="{{ route('institutions.edit', ['institution' => $institution ]) }}" class="btn btn-secondary">Editar</a>
                        <button class="btn btn-danger" type="submit">Eliminar</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection