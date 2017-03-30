/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

	var zoomLevel = 15;

    var bcMapController = function( $log, Connections )
    {
        var BcMap = this;

	    /**
	     * Google Map instance
	     *
	     * @type {Object}
	     * @private
	     */
        var _instance = null;

	    /**
	     * All available clients
	     *
	     * @type {Array}
	     */
	    BcMap.clients = Connections.getClients();

	    /**
	     * Markers on Google Map
	     * @type {Object}
	     */
	    BcMap.markers = {};

	    /**
	     * Creates new Google Maps instance
	     * @param {Object} element
	     * @param {Function} callback
	     */
	    BcMap.createMap = function( element, callback )
        {
	        callback = callback || function() {};

        	if ( angular.isUndefined(google) || angular.isUndefined(google.maps) )
	        {
	        	$log.error( 'Google Maps not initialized!' );
	        	return;
	        }

	        _instance = new google.maps.Map( element, {
	            zoom: zoomLevel
            });

        	$log.info( 'New Google Maps instance', _instance );

        	if ( BcMap.clients.length === 0 && navigator.geolocation)
	        {
		        navigator.geolocation.getCurrentPosition( function(position)
		        {
			        var pos = {
				        lat: position.coords.latitude,
				        lng: position.coords.longitude
			        };

		        	_instance.panTo( pos );
			        _instance.setZoom( zoomLevel );
			        google.maps.event.trigger(_instance, 'resize');
		        } );
	        }

	        callback();
        };

	    /**
	     * Destroys Google map instance
	     *
	     * @returns {Object}
	     */
	    BcMap.destroyMap = function()
	    {
		    _instance = null;
		    return _instance;
	    };

	    /**
	     * Gets leaflet map instance
	     *
	     * @returns {Object} Google Maps instance
	     */
	    BcMap.getMap = function()
	    {
		    return _instance;
	    };

	    /**
	     * ------------------------------------------------------------------
	     * Markers
	     * ------------------------------------------------------------------
	     */

	    /**
	     * Creates markers
	     */
	    BcMap.createMarkers = function()
	    {
	    	var centering_required = false;

		    // Current markers
		    var markers = BcMap.markers;

		    if ( Object.keys(markers).length === 0 )
		    {
			    centering_required = true;
		    }

		    // Filtered clients
		    var clients = BcMap.clients,
		        clientsLength = clients.length;

		    // Create/update markers
		    for ( var clientIndex = 0; clientIndex < clientsLength; clientIndex++ )
		    {
			    var client = clients[clientIndex],
			        clientMac = client.profile.mac_address;

			    if ( angular.isDefined(markers[clientMac]) )
			    {
				    BcMap.updateMarker(client);
			    }
			    else
			    {
				    BcMap.createMarker(client);
			    }
		    }

		    // Remove markers
		    for ( var mac in markers )
		    {
			    if ( angular.isDefined(markers[mac]) )
			    {
				    var exists = false;

				    for ( var clientIndex2 = 0; clientIndex2 < clientsLength; clientIndex2++ )
				    {
					    var client2 = clients[clientIndex2];
					    if ( mac === client2.profile.mac_address )
					    {
						    exists = true;
						    break;
					    }
				    }

				    if ( false === exists )
				    {
					    BcMap.destroyMarker(mac);
				    }
			    }
		    }

		    if ( centering_required )
		    {
		    	BcMap.centerBounds();
		    }
	    };

	    /**
	     * Creates a marker
	     * @param {Object} client
	     */
	    BcMap.createMarker = function( client )
	    {
		    var mac = client.profile.mac_address;

		    if ( angular.isDefined( BcMap.markers[mac]) )
		    {
		    	return BcMap.updateMarker( client );
		    }

		    if ( angular.isUndefined(client.position.lat) || angular.isUndefined(client.position.lon) )
		    {
		    	return;
		    }

		    var map = BcMap.getMap();

		    var pos = {
			    lat: client.position.lat,
			    lng: client.position.lon
		    };

		    var marker = {
		    	client: client
		    };

		    // Create marker and popup, bind popup
		    marker._instance = new google.maps.Marker( {
			    position: pos,
			    map: map
		    } );

		    BcMap.markers[mac] = marker;
	    };

	    /**
	     * Creates a marker
	     * @param {Object} client
	     */
	    BcMap.updateMarker = function( client )
	    {
		    var mac = client.profile.mac_address;

		    if ( angular.isUndefined(BcMap.markers[mac]) )
		    {
		    	return BcMap.createMarker( client );
		    }

		    if ( angular.isUndefined(client.position.lat) || angular.isUndefined(client.position.lon) )
		    {
			    return;
		    }

		    var pos = {
			    lat: client.position.lat,
			    lng: client.position.lon
		    };

		    BcMap.markers[mac]._instance.setPosition( pos );
	    };

	    /**
	     * Destroys markers
	     * @param {String} mac
	     */
	    BcMap.destroyMarker = function( mac )
	    {
		    if ( angular.isDefined(BcMap.markers[mac]) )
		    {
		    	var marker = BcMap.markers[mac];
			    marker._instance.setMap( null );
			    marker._instance = null;
			    delete BcMap.markers[mac];
		    }
	    };

	    /**
	     * Centers map to show all markers
	     */
	    BcMap.centerBounds = function()
	    {
	    	var markers = BcMap.markers;
	    	var map = BcMap.getMap();

	    	if ( Object.keys(markers).length === 0 )
	    		return;

		    var bounds = new google.maps.LatLngBounds();

		    for ( var mac in markers )
		    {
			    bounds.extend(markers[mac]._instance.getPosition());
		    }

		    map.fitBounds( bounds );
		    google.maps.event.trigger(_instance, 'resize');

	    };
    };

	bcMapController.$inject = [
		'$log',
        'Connections'
    ];
    angular.module('bc-map.controllers').controller('BcMap', bcMapController);
})();
