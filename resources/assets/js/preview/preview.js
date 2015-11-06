(function () {
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

    var w = floor.image.pixel_width,
        h = floor.image.pixel_height,
        url = '/uploads/maps/' + floor.image.file_name;

    var southWest = map.unproject([0, h], map.getMaxZoom());
    var northEast = map.unproject([w, 0], map.getMaxZoom());
    var bounds = new L.LatLngBounds(southWest, northEast);
    var center = bounds.getCenter();

    map.addLayer(L.imageOverlay(url, bounds)).fitBounds(bounds).setView(center, map.getZoom());

    var pinHtml = function () {
        return '\
            <div class="bullet" title="">\
                <div class="bullet-container">\
                    <span class="bullet-wrapper">\
                        <span class="bullet-state icons -video2">\
                        </span>\
                    </span>\
                </div>\
            </div>';
    };

    for (var i in buttons) {
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
})();