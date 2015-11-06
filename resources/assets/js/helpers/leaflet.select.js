
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function () {
    'use strict';

    L.Map.mergeOptions({
        selecting: false
    });

    L.Map.Select = L.Handler.extend({
        initialize: function (map) {
            this._map = map;
            this._container = map._container;
            this._pane = map._panes.overlayPane;
            
        },
        addHooks: function () {
            L.DomEvent.on(this._container, 'mousedown', this._onMouseDown, this);
            L.DomUtil.addClass(this._map._container, 'leaflet-select');
            this._container.style.cursor = 'crosshair';
        },
        removeHooks: function () {
            L.DomEvent.off(this._container, 'mousedown', this._onMouseDown);
            L.DomUtil.removeClass(this._map._container, 'leaflet-select');
            this._container.style.cursor = '';
        },
        _onMouseDown: function (e) {
            L.DomUtil.disableTextSelection();

            this._startLayerPoint = this._map.mouseEventToLayerPoint(e);

            this._box = L.DomUtil.create('div', 'leaflet-zoom-box', this._pane);
            L.DomUtil.setPosition(this._box, this._startLayerPoint);

            L.DomEvent
                    .on(document, 'mousemove', this._onMouseMove, this)
                    .on(document, 'mouseup', this._onMouseUp, this)
                    .on(document, 'keydown', this._onKeyDown, this)
                    .preventDefault(e);

            this._map.fire('selectstart');
        },
        _onMouseMove: function (e) {
            var startPoint = this._startLayerPoint,
                    box = this._box,
                    layerPoint = this._map.mouseEventToLayerPoint(e),
                    offset = layerPoint.subtract(startPoint),
                    newPos = new L.Point(
                            Math.min(layerPoint.x, startPoint.x),
                            Math.min(layerPoint.y, startPoint.y));

            L.DomUtil.setPosition(box, newPos);

            // TODO refactor: remove hardcoded 4 pixels
            box.style.width = (Math.max(0, Math.abs(offset.x) - 4)) + 'px';
            box.style.height = (Math.max(0, Math.abs(offset.y) - 4)) + 'px';
        },
        _finish: function () {
            this._pane.removeChild(this._box);

            L.DomUtil.enableTextSelection();

            L.DomEvent
                    .off(document, 'mousemove', this._onMouseMove)
                    .off(document, 'mouseup', this._onMouseUp)
                    .off(document, 'keydown', this._onKeyDown);
        },
        _onMouseUp: function (e) {

            this._finish();

            var map = this._map,
                layerPoint = map.mouseEventToLayerPoint(e);

            //if (this._startLayerPoint.equals(layerPoint)) {
            //    map.fire('unselect');
            //    return;
            //}

            var bounds = new L.LatLngBounds(
                    map.layerPointToLatLng(this._startLayerPoint),
                    map.layerPointToLatLng(layerPoint));

            map.fire('selectend', {
                ctrlKey: e.ctrlKey,
                selectBounds: bounds
            });
        },
        _onKeyDown: function (e) {
            if (e.keyCode === 27) {
                this._finish();
            }
        }
    });

    L.Map.addInitHook('addHandler', 'selecting', L.Map.Select);

    // z-index
    L.Marker.prototype.__setPos = L.Marker.prototype._setPos;
    L.Marker.prototype._setPos = function ()
    {
        L.Marker.prototype.__setPos.apply(this, arguments);
        this._zIndex = this.options.zIndexOffset;
        this._resetZIndex();
    };
})();