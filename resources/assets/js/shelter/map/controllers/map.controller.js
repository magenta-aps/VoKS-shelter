/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var mapController = function($rootScope, $q, $compile, $templateCache, $translate,
                                 MapConfig, MapState, MapData, State, Connections) {
        var Map = this;

        /**
         * Map instance
         *
         * @private
         *
         * @type {Object} Leaflet map instance
         */
        var _instance = null;

        /**
         * All available clients
         *
         * @type {Array}
         */
        Map.clients = Connections.getClients();

        /**
         * Filtered clients
         *
         * @type {Object}
         */
        Map.markers = {};

        /**
         * Map & leaflet options (by id attribute on map tag).
         * Map state by map group (set in options).
         *
         * Configuration can be found in app.js.
         * Angular creates new MapController for every "map" directive.
         *
         * @type {Object}
         * @type {Object}
         */
        Map.options = MapConfig.getConfig(Map.id);
        Map.state = MapState.getStates(Map.options.state);

        /**
         * ------------------------------------------------------------------
         * Map
         * ------------------------------------------------------------------
         */

        /**
         * Creates leaflet map instance
         *
         * @param {Object} container
         * @returns {Object} Leaflet instance
         */
        Map.createMap = function(container) {
            var options = Map.options,
                leaflet = options.leaflet;
            _instance = L.map(container, leaflet);

            // Create default zoom control
            if (false === options.control) {
                var control = new L.Control.Zoom({
                    zoomInTitle: $translate.instant(leaflet.zoomInTitle),
                    zoomOutTitle: $translate.instant(leaflet.zoomOutTitle)
                }).addTo(_instance);
            }

            return _instance;
        };

        /**
         * Destroys leaflet map instance
         *
         * @returns {Null}
         */
        Map.destroyMap = function() {
            _instance.remove();
            _instance = null;
            return _instance;
        };

        /**
         * Gets leaflet map instance
         *
         * @returns {Object} Leaflet instance
         */
        Map.getMap = function() {
            return _instance;
        };

        /**
         * ------------------------------------------------------------------
         * Floor
         * ------------------------------------------------------------------
         */

        /**
         * Creates floor
         *
         * @param {Object} floorId Floor identifier
         */
        Map.createFloor = function(floorId) {
            var map = Map.getMap(),
                floor = MapData.getFloor(floorId);

            // Image data
            var url = floor.image,
                width = floor.width,
                height = floor.height;

            // Map zoom
            var zoom = Map.options.leaflet.zoom;

            // South West and North East coordinates
            var sw = map.unproject([0, height], zoom),
                ne = map.unproject([width, 0], zoom);

            // Create & save bounds
            var bounds = new L.LatLngBounds(sw, ne);
            Map.state.leaflet.bounds = bounds;

            // Create layer and add to map
            var layer = L.imageOverlay(url, bounds);
            map.addLayer(layer);

            // Center floor image and fit to map container
            map.invalidateSize(false);
            map.fitBounds(bounds, {animate: false});

            // Map events (only in school plan)
            if ('map' === Map.options.state) {
                map.on('selectend', function(e) {
                    _selectMarkers(e);
                });
            }
        };

        /**
         * ------------------------------------------------------------------
         * Markers
         * ------------------------------------------------------------------
         */

        /**
         * Creates markers
         */
        Map.createMarkers = function() {
            var map = Map.getMap();

            // Current markers
            var markers = Map.markers;

            // Filtered clients
            var clients = _filterClients(),
                clientsLength = clients.length;

            // Create/update markers
            for (var clientIndex = 0; clientIndex < clientsLength; clientIndex++) {
                var client = clients[clientIndex],
                    clientMac = client.profile.mac_address,
                    marker = markers[clientMac];

                var _marker = null;
                if (undefined !== marker) {
                    _marker = _updateMarker(client, marker);
                } else {
                    _marker = _createMarker(client);
                    map.addLayer(_marker);
                    markers[clientMac] = _marker;
                }
            }

            // Remove markers
            for (var mac in markers) {
                if (undefined !== markers[mac]) {
                    var exists = false;

                    for (var clientIndex2 = 0; clientIndex2 < clientsLength; clientIndex2++) {
                        var client2 = clients[clientIndex2];
                        if (mac === client2.profile.mac_address) {
                            exists = true;
                            break;
                        }
                    }

                    if (false === exists) {
                        var marker2 = markers[mac];
                        map.removeLayer(marker2);
                        delete markers[mac];
                    }
                }
            }
        };

        /**
         * Destroys markers
         */
        Map.destroyMarkers = function() {
            var map = Map.getMap(),
                list = Map.markers;

            for (var mac in list) {
                if (undefined !== list[mac]) {
                    var marker = list[mac];
                    map.removeLayer(marker);
                }
            }

            Map.markers = {};
        };

        /**
         * Pans map to specified marker
         */
        Map.panMarker = function(macAddress) {
            var map = Map.getMap(),
                marker = Map.markers[macAddress];

            if (undefined !== marker) {
                map.panTo(marker._latlng);
            }
        };

        /**
         * Filters clients by floor and state
         * Utility
         *
         * @returns {Array}
         */
        function _filterClients() {
            var filtered = [];
            var clients = Map.clients,
                length = clients.length;

            for (var index = 0; index < length; index++) {
                var client = clients[index];

                // Filter out if wrong floor
                if (client.position.floor !== Map.state.floor) {
                    continue;
                }

                // Filter out if map group is stream and client is not in stream list
                if ('stream' === Map.options.state) {
                    if (true !== client.position.inView) {
                        continue;
                    }
                }

                filtered.push(client);
            }

            return filtered;
        }

        /**
         * Creates a marker
         */
        function _createMarker(client) {
            var map = Map.getMap(),
                zoom = Map.options.leaflet.zoom;

            // Client coordinates and position
            var x = client.position.x,
                y = client.position.y,
                position = map.unproject([x, y], zoom);

            // Create marker icon
            var icon = _createMarkerIcon(client);

            // Create marker and popup, bind popup
            var marker = L.marker(position, {icon: icon});
            marker.setZIndexOffset(2);

            if ('map' !== Map.options.state && undefined !== client.profile.id) {
                var popup = _createMarkerPopup(client);
                marker.bindPopup(popup);
            }

            // Ref. to client
            marker.client = client;

            // Marker click event
            marker.on('click', _clickMarker);

            return marker;
        }

        /**
         * Click
         */
        function _clickMarker(e) {
            if ('map' === Map.options.state) {
                var marker = e.target,
                    client = marker.client;

                if ('' !== client.profile.gcmId) {
                    client.position.selected = !client.position.selected;
                }
            }
        }

        /**
         * Updates marker
         */
        function _updateMarker(client, marker) {
            var map = Map.getMap(),
                zoom = Map.options.leaflet.zoom;

            // Client coordinates and position
            var x = client.position.x,
                y = client.position.y,
                position = map.unproject([x, y], zoom);

            // Check if position changed
            if (false === angular.equals(marker.getLatLng(), position)) {
                marker.setLatLng(position);
            }

            // Marker icon
            var icon = _createMarkerIcon(client);
            if (marker.options.icon.options.className !== icon.options.className) {
                marker.setIcon(icon);
            }

            return marker;
        }

        /**
         * Create marker icon
         *
         * @param {Object} client
         * @private
         */
        function _createMarkerIcon(client) {
            var html = '',
                className = ['bullet'];

            if ('' === client.profile.gcmId) {
                // Inactive marker
                html = '<img src="/images/map/marker-inactive.png" alt="" />';
                className.push('-inactive');
            } else {
                // Active marker
                html = $templateCache.get('marker.directive.html');

                // Called police
                if (true === client.state.calledPolice) {
                    className.push('-red');
                }

                // Triggered alarm
                if (false === client.state.calledPolice && 'client' === client.profile.type) {
                    className.push('-blue');
                }

                // Map selected
                if (State.selected.marker === client.profile.id) {
                    className.push('-active');
                }

                if ('map' === Map.options.state) {
                    // Selected for push notification
                    if (true === client.position.selected) {
                        className.push('bullet-selected');
                    }
                } else {
                    // Video icon when streaming
                    if (true === client.position.inView) {
                        className.push('-video2');
                    }
                }
            }

            return L.divIcon({
                className: className.join(' '),
                iconSize: [30, 30],
                html: html
            });
        }

        function _createMarkerPopup(client) {
            var html = $templateCache.get('popup.html'),
                options = {
                    className: 'bullet-popup',
                    closeButton: false,
                    closePopupOnClick: false
                },
                popup = L.popup(options);

            var scope = $rootScope.$new();
            scope.client = client;

            var template = angular.element(html),
                linkFn = $compile(template),
                element = linkFn(scope);

            popup.setContent(element[0]);

            return popup;
        }

        /**
         * Select markers
         *
         * @private
         * @param {Object} bounds
         */
        function _selectMarkers(e) {
            var markers = Map.markers,
                bounds = e.selectBounds,
                ctrl = e.ctrlKey;

            for (var mac in markers) {
                if (undefined !== markers[mac]) {
                    var marker = markers[mac],
                        client = marker.client;

                    if (true === ctrl) {
                        // select only those within selection bounds & gcmId
                        if (bounds.contains(marker._latlng) && '' !== client.profile.gcmId) {
                            client.position.selected = !client.position.selected;
                        }
                    } else {
                        // unselect all clients by default
                        client.position.selected = false;

                        // select only those within selection bounds & gcmId
                        if (bounds.contains(marker._latlng) && '' !== client.profile.gcmId) {
                            client.position.selected = true;
                        }
                    }
                }
            }
        }
    };

    mapController.$inject = [
        '$rootScope',
        '$q',
        '$compile',
        '$templateCache',
        '$translate',
        'MapConfig',
        'MapState',
        'MapData',

        'State',
        'Connections'
    ];
    angular.module('map.controllers').controller('Map', mapController);
})();