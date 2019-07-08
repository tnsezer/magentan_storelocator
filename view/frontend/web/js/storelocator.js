define([
    'jquery',
    'mage/translate'
], function ($, $t) {

    let map, circle, infowindow, directionsService, directionsDisplay, panorama, stores, settings;
    let markers = {};

    focusMarker = function(id) {
        marker = markers[id];
        var bounds = new google.maps.LatLngBounds();
        bounds.extend(marker.getPosition());
        map.fitBounds(bounds);
        map.setZoom(18);
        new google.maps.event.trigger( marker, 'click' );
    }
    // for store information balloon
    baloon = function(contentString, marker, title = '') {
        if (infowindow) {
            infowindow.close();
        }

        infowindow = new google.maps.InfoWindow({
            content: contentString
        });
        if(title != '') {
            marker.setTitle(title);
        }
        infowindow.open(map, marker);

        return infowindow;
    }
    // it calculate stores distance which are in radius or not
    calcDistance = function(fromLat, fromLng, toLat, toLng) {
        return google.maps.geometry.spherical.computeDistanceBetween(
            new google.maps.LatLng(fromLat, fromLng), new google.maps.LatLng(toLat, toLng));
    }
    // draw radius circle
    drawCircle = function(radius, location) {
        circle = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.35,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.15,
            map: map,
            center: location,
            radius: kmToMeters(radius)
        });

        return circle;
    }
    // create marker func
    createMarker = function(name, position) {
        marker = new google.maps.Marker({
            map: map,
            animation: google.maps.Animation.DROP,
            position: position,
            title: name
        });

        return marker;
    }
    // reads all stores and creates
    createMarkers = function() {
        // Reset all markers
        Object.values(markers).forEach(function(marker){
            marker.setMap(null);
        });
        $('.store.active').removeClass('active');
        $('.stores').show();
        markers = {};

        // create from the beginning
        stores.forEach(function(data){

            if( ($('#country').val() != "" && $('#country').val() != data.country)
                || ($('#zipcode').val() != "" && $('#zipcode').val() != data.zip)
                || ($('#storename').val() != "" && !data.name.toLowerCase().includes( $('#storename').val().toLowerCase() ))
                ||($('#city').val() != "" && !$('#city').val().toLowerCase().includes( data.city.toLowerCase() )) ){
                $('.store_' + data.id).hide();
                return
            }

            var position = JSON.parse(data.position);
            marker = createMarker(data.name, new google.maps.LatLng(position.lat, position.lng) );
            marker._id = data.id;
            markers[data.id] = marker;

            var contentString = '<div>';
            if(data.address != null){
                contentString += '<p><strong>'+$t('Address')+':</strong> '+data.address+'</p>';
            }
            if(data.phone != null){
                contentString += '<p><strong>'+$t('Phone')+':</strong> <a target="_blank" href="tel:'+data.phone+'">'+data.phone+'</a></p>';
            }
            if(data.email != null){
                contentString += '<p><strong>'+$t('Email')+':</strong> <a target="_blank" href="mailto:'+data.email+'">'+data.email+'</a></p>';
            }
            if(data.website != null){
                contentString += '<p><strong>'+$t('Website')+':</strong> <a target="_blank" href="'+data.website+'">'+data.website+'</a></p>';
            }

            contentString += '</div>';
            var title = data.name;
            marker.addListener('click', function () {
                baloon(contentString, this, title);

                $('.store.active').removeClass('active');
                $('.store_' + this._id).find('.store').addClass('active');
            });
        });
    }
    getLocation = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            //x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }
    showPosition = function(position) {
        $('#location').val( $t('current location') );
        $('#location_lat').val( position.coords.latitude );
        $('#location_lng').val( position.coords.longitude );
    }
    showError = function(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                //x.innerHTML = "User denied the request for Geolocation."
                break;
            case error.POSITION_UNAVAILABLE:
                //x.innerHTML = "Location information is unavailable."
                break;
            case error.TIMEOUT:
                //x.innerHTML = "The request to get user location timed out."
                break;
            case error.UNKNOWN_ERROR:
                //x.innerHTML = "An unknown error occurred."
                break;
        }
    }
    showStreetView = function(lat, lng) {
        panorama.setPosition(new google.maps.LatLng(lat, lng));
        panorama.setPov(/** @type {google.maps.StreetViewPov} */({
            heading: 265,
            pitch: 0
        }));
        panorama.setVisible(true);
    }
    calculateAndDisplayRoute = function(id, lat, lng) {
        var origin = $('#directions-'+id).find('.start').val();
        //if($('#directions-'+id).find('.location_lat').val() == ''){
        if(origin == ""){
            $('#directions-'+id).find('.start').focus();
            return;
        }
        directionsService.route({
            origin: origin, /*new google.maps.LatLng($('#directions-'+id).find('.location_lat').val(), $('#directions-'+id).find('.location_lng').val()),*/
            destination: new google.maps.LatLng(lat, lng),
            travelMode: $('#directions-'+id).find('.transport').val() //'DRIVING' //TRANSIT, BICYCLING, WALKING, DRIVING
        }, function(response, status) {
            if (status === 'OK') {
                directionsDisplay.setDirections(response);
                directionsDisplay.setMap(map);
                directionsDisplay.setPanel($('#directions-'+id).find('.directions-panel')[0]);
            } else {
                console.log('Directions request failed due to ' + status);
            }
        });
    }

    initMap = function () {
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: parseInt(settings.zoom) || 4,
            center: {lat: parseFloat(settings.lat) || 54.5259614, lng: parseFloat(settings.lng) || 15.255118700000025}
        });

        // direction service
        directionsService = new google.maps.DirectionsService;
        directionsDisplay = new google.maps.DirectionsRenderer;

        // street view service
        panorama = map.getStreetView();



        var input = document.getElementById('city');
        new google.maps.places.Autocomplete(input, {types: ['(cities)']});
        // Listen for the event fired when the user selects a prediction and retrieve

        // autocomplete config
        $('.location').each(function (key, input) {
            return new google.maps.places.Autocomplete(input);
        });

        createMarkers();

        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'}
            );

        //submitForm();
    }

    submitForm = function(e) {

        // reset direction
        directionsDisplay.setMap(null);

        // reset street view
        panorama.setVisible(false);
        map.setZoom(4);
        map.setCenter({lat: 54.5259614, lng: 15.255118700000025});
        createMarkers();
        if($('#city').val() != "" || $('#country').val() != "") {
            var geocoder = new google.maps.Geocoder();
            var address = $('#city').val() || $('#country option:selected').text();
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    map.fitBounds(results[0].geometry.bounds);
                    var radius = $('#radius').val();
                    if (circle) {
                        circle.setMap(null);
                    }
                    if (radius > 0) {
                        circle = drawCircle(radius, results[0].geometry.location);

                        map.fitBounds(circle.getBounds());

                        Object.values(markers).forEach(function (marker) {
                            var distance = calcDistance(
                                marker.getPosition().lat(), marker.getPosition().lng(),
                                results[0].geometry.location.lat(), results[0].geometry.location.lng()
                            );

                            if (distance > kmToMeters(radius)) {
                                $('.store_' + marker._id).hide();
                                marker.setMap(null);
                            }
                        });
                    }

                } else {
                    console.error("Could not find location: " + address);
                }
            });
        }

        if(e) {
            e.preventDefault();
        }

        return false;
    }

    milesToMeters = function(i) {
        return i*1609.344;
    }
    kmToMeters = function(i) {
        return i*1000;
    }

    return function (config) {
        console.log(config);
        if(config.key == ""){
            console.error("Please add a valid map key");
            return;
        }

        settings = config;
        stores = config.stores;
        url = "https://maps.googleapis.com/maps/api/js?key="+config.key+"&libraries=places,geometry&language="+config.language+"&callback=initMap";
        $.getScript(url);

        $('#storelocator-form').bind("submit", submitForm);
        $('ul.travel-list li').bind('click', function (e) {
            $(this).parents('.direction-popup').find('.travel.travel-active').removeClass('travel-active');
            $(this).addClass('travel-active');
            $(this).parents('.direction-popup').find('.transport').val( $(this).attr('travel-data') );
        });

        $('.direction.action').bind('click', function () {
            $('.direction-popup').hide();
            $(this).parents('.store').find('.direction-popup').toggle();
        });

        $('.street-view.action').bind('click', function () {
            showStreetView($(this).attr('data-lat'), $(this).attr('data-lng'));
        });
    }
});