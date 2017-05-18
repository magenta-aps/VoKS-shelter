/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var DEFAULT_ZOOM_LEVEL = 15;
	var zoomLevel = angular.isDefined( config['google-zoom-level'] ) ? config['google-zoom-level'] : DEFAULT_ZOOM_LEVEL;

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
	     * Directive scope
	     *
	     * @type {Object}
	     * @private
	     */
	    var _scope;

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
	     * @param {Object} [scope]
	     */
	    BcMap.createMap = function( element, callback, scope )
        {
	        callback = callback || function() {};
	        _scope = scope || undefined;

        	if ( angular.isUndefined(google) || angular.isUndefined(google.maps) )
	        {
	        	$log.error( 'Google Maps not initialized!' );
	        	return;
	        }

	        _instance = new google.maps.Map( element, {
	            zoom: zoomLevel
            });

        	$log.info( '[bc-map] New Google Maps instance', _instance );

        	if ( Object.keys(BcMap.clients).length === 0 )
	        {
		        getCoordinates( function(position)
		        {
			        var pos = {
				        lat: position.coords.latitude,
				        lng: position.coords.longitude
			        };

			        _instance.setCenter( pos );
			        _instance.setZoom( zoomLevel );
			        // google.maps.event.trigger( _instance, 'resize' );
			        $log.info( '[bc-map] Map repositioned', pos );
		        } );
	        }

	        callback();
        };

	    /**
	     * Destroys Google map instance
	     */
	    BcMap.destroyMap = function()
	    {
		    _instance = null;

		    $log.info( '[bc-map] Map instance destroyed' );
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
		        clientsLength = Object.keys(clients.length);

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

		    $log.info( '[bc-map] Marker created', mac, pos );

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

		    $log.info( '[bc-map] Marker updated', mac, pos );
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

			    $log.info( '[bc-map] Marker destroyed', mac );
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
		    {
			    return;
		    }

		    var bounds = new google.maps.LatLngBounds();

		    for ( var mac in markers )
		    {
			    bounds.extend(markers[mac]._instance.getPosition());
		    }

		    map.fitBounds( bounds );
		    // google.maps.event.trigger(_instance, 'resize');

		    $log.info( '[bc-map] Map centered', bounds );
	    };

	    /**
	     * Retrieves coordinates from browser (if possible)
	     * @param {Function} [successFunc]
	     * @param {Function} [errorFunc]
	     * @private
	     */
	    function getCoordinates( successFunc, errorFunc )
	    {
		    successFunc = successFunc || function( position ) {};
		    errorFunc = errorFunc || function( error ) {};

		    function isChrome()
		    {
			    var isChromium = window.chrome,
			        winNav = window.navigator,
			        vendorName = winNav.vendor,
			        isOpera = winNav.userAgent.indexOf("OPR") > -1,
			        isIEedge = winNav.userAgent.indexOf("Edge") > -1,
			        isIOSChrome = winNav.userAgent.match("CriOS");

			    if( isIOSChrome)
			    {
				    return true;
			    }
			    else if(isChromium !== null && isChromium !== undefined && vendorName === "Google Inc." && isOpera == false && isIEedge == false)
			    {
				    return true;
			    }
			    else
			    {
				    return false;
			    }
		    }

		    if ( navigator.geolocation )
		    {
			    var options = { maximumAge: Infinity, timeout: 0 };

			    if ( isChrome() )
			    {
				    options = { enableHighAccuracy: false, maximumAge: 15000, timeout: 30000 };
			    }

			    // Dummy one, which will result in a working next statement.
			    navigator.geolocation.getCurrentPosition(
			    	function ( position )
			        {
			        	$log.info( '[bc-map] Received coordinates', position );
				    	successFunc( position );
				    },
				    function( error )
				    {
					    $log.error( '[bc-map] Error retrieving coordinates', error );
					    errorFunc( error );
				    },
				    options
			    );
		    }
	    }

	    function init()
	    {
		    Connections.subscribe( 'ResetShelter', function()
		    {
		    	$log.info( '[bc-map] Event:ResetShelter' );
		    	BcMap.createMarkers();
		    });

		    Connections.subscribe( 'ClientListUpdate', function()
		    {
			    $log.info( '[bc-map] Event:ClientListUpdate' );
			    BcMap.createMarkers();
		    });

		    var process_client_update = function( client )
		    {
			    var clientMac = client.profile.mac_address;

			    // Current markers
			    var markers = BcMap.markers;

			    if ( angular.isDefined(markers[clientMac]) )
			    {
				    BcMap.updateMarker(client);
			    }
			    else
			    {
				    BcMap.createMarker(client);

				    if ( Object.keys(markers).length === 1 )
				    {
					    BcMap.centerBounds();
				    }
			    }
		    };

		    Connections.subscribe( 'ClientConnect', function( event, params )
		    {
			    $log.info( '[bc-map] Event:ClientConnect' );
			    process_client_update( params.client );
		    });

		    Connections.subscribe( 'ClientDisconnect', function( event, params )
		    {
			    $log.info( '[bc-map] Event:ClientDisconnect' );
			    var client = params.client,
			        clientMac = client.profile.mac_address;

			    BcMap.destroyMarker( clientMac );
		    });

		    Connections.subscribe( 'ClientCoordinates', function( event, params )
		    {
			    $log.info( '[bc-map] Event:ClientCoordinates' );
			    process_client_update( params.client );
		    });

	    }

	    init();
    };

	bcMapController.$inject = [
		'$log',
        'Connections'
    ];
    angular.module('bc-map.controllers').controller('BcMap', bcMapController);
})();
