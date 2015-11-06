/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var mapsController = function($scope, Toast, SystemApi, $translate) {

        SystemApi.getMaps().success(function(data) {
            $scope.list = data;
        });

        angular.extend($scope, {
            list: [],
            previewMap: function(floorId) {
                SystemApi.previewMap(floorId);
            },
            syncMaps: function() {
                Toast.push('warning', $translate.instant('toast.contents.system.maps.sync'), '');

                SystemApi.syncMaps().success(function() {
                    Toast.push('success', $translate.instant('toast.contents.system.maps.sync_success'), '');
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                });
            }
        });
    };

    mapsController.$inject = ['$scope', 'Toast', 'SystemApi', '$translate'];
    angular.module('system').controller('MapsController', mapsController);

    // Preview controller
    var mapPreviewController = function($scope, Toast, SystemApi) {
        var map = L.map('map', {
            minZoom: 1,
            maxZoom: 10,
            center: [0, 0],
            zoom: 6,
            crs: L.CRS.Simple,
            attributionControl: false
            //zoomControl: false,
            //boxZoom: false
        });

        var w = floor.image.width,
            h = floor.image.height,
            url = '/uploads/maps/' + floor.image.file_name;

        var southWest = map.unproject([0, h], map.getMaxZoom());
        var northEast = map.unproject([w, 0], map.getMaxZoom());
        var bounds = new L.LatLngBounds(southWest, northEast);
        var center = bounds.getCenter();

        map.addLayer(L.imageOverlay(url, bounds)).fitBounds(bounds).setView(center, map.getZoom());

        var pinHtml = function() {
            var html = '';
            html += '<div class="bullet" title="">';
            html += '    <div class="bullet-container">';
            html += '        <span class="bullet-wrapper">';
            html += '            <span class="bullet-state icons -video2"></span>';
            html += '        </span>';
            html += '    </div>';
            html += '</div>';

            return html;
        };

        for (var i in buttons) {
            if (undefined !== buttons[i]) {
                var myIcon = L.divIcon({
                    html: pinHtml(),
                    iconSize: L.point(30, 30),
                    className: 'bullet'
                });

                var position = map.unproject([buttons[i].position.x, buttons[i].position.y], map.getMaxZoom());

                L.marker(position, {
                    icon: myIcon
                }).addTo(map);
            }
        }
    };

    mapPreviewController.$inject = ['$scope', 'Toast', 'SystemApi'];
    angular.module('system').controller('MapPreviewController', mapPreviewController);
})();