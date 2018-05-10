$(document).ready(function(){
    const lat = parseFloat( document.querySelector('.gmap__lat').value ) || -33;
    const lng = parseFloat( document.querySelector('.gmap__lng').value ) || 71;
    const center = new google.maps.LatLng( lat, lng );
    const gMap = new google.maps.Map( document.querySelector('.gmap__map'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: center,
        zoom: 15
    } );
    const input = $('.gmap__search').on('keydown', function(event){
        if ( event.keyCode == 13 ) {
            event.preventDefault();
            return false;
        }
    });

    input.css({
        'width': '80%',
        'margin': '6px 0 0'
    });

    gMap.controls[ google.maps.ControlPosition.TOP_LEFT ].push( input[0] );
    let autocomplete = new google.maps.places.Autocomplete( input[0] ),
        infowindow   = new google.maps.InfoWindow(),
        geocoder     = new google.maps.Geocoder(),
        marker       = new google.maps.Marker({
            map: gMap,
            anchorPoint: new google.maps.Point( lat, lng )
        });
    marker.setAnimation( google.maps.Marker.DROP );
    marker.setDraggable( true );
    marker.setVisible( true );
    marker.setPosition( new google.maps.LatLng( lat, lng ) );

    google.maps.event.addListener( autocomplete, 'place_changed', function(){
        let place = autocomplete.getPlace();
        if ( ! place.geometry ) {
            return;
        }
        gMap.setCenter( place.geometry.location );
        gMap.setZoom( 17 );
        marker.setDraggable( true );
        marker.setPosition( place.geometry.location );
        marker.setTitle( place.name );
        marker.setVisible( true );

        document.querySelector('.gmap__lat').value = place.geometry.location.lat();
        document.querySelector('.gmap__lng').value = place.geometry.location.lng();

        geocoder.geocode({
            location: place.geometry.location
        }, function( results, status ){
            if ( status == 'OK' ) {
                document.querySelector('.gmap__location').value = JSON.stringify( results[0] );
            }
        })
    });

});