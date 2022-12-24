<!-- Modal -->
<div class="modal fade" id="modalCoordsPicker" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><{$smarty.const._MA_WGEVENTS_EVENT_GM_GETCOORDS}></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="googlemap" style="width: 100%; height:400px;"></div>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="labelGmlocation"><{$smarty.const._MA_WGEVENTS_EVENT_LOCATION}></span>
                    </div>
                    <input id="address" type="text" class="form-control" aria-label="<{$smarty.const._MA_WGEVENTS_EVENT_LOCATION}>" aria-describedby="labelGmlocation">
                </div>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="labelGmlat"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMLAT}></span>
                    </div>
                    <input id="lat" type="text" class="form-control" aria-label="Username" aria-describedby="labelGmlat">
                </div>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="labelGmlon"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMLON}></span>
                    </div>
                    <input id="lon" type="text" class="form-control" aria-label="Username" aria-describedby="labelGmlon">
                </div>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="labelZoom"><{$smarty.const._MA_WGEVENTS_EVENT_LOCGMZOOM}></span>
                    </div>
                    <input id="zoom" type="text" class="form-control" aria-label="Username" aria-describedby="labelZoom">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><{$smarty.const._CLOSE}></button>
                <{if $gmapsModal|default:false}>
                    <button onclick="saveCoords();" type="button" class="btn btn-primary"><{$smarty.const._MA_WGEVENTS_SAVE}></button>
                <{/if}>
            </div>
        </div>
    </div>
</div>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=<{$api_key}>" type="text/javascript"></script>

<script type="text/javascript">
    var map;
    var geocoder;
    var marker;
    var infoWindow;
    var service;

    function GMinit() {
        var address = document.formEvent.location.value;
        var latitude = document.formEvent.locgmlat.value;
        var longitude = document.formEvent.locgmlon.value;
        var zoom = parseInt(document.formEvent.locgmzoom.value);

        document.getElementById("address").value = address;

        if (zoom == 0) {zoom = 10;}
            if (latitude == 0) {
                moveCurrentPos();
            }
        var myLatlng = new google.maps.LatLng(latitude, longitude);
        var myOptions = {
            zoom: zoom,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var bounds = new google.maps.LatLngBounds();

        map = new google.maps.Map(document.getElementById("googlemap"), myOptions);
        geocoder = new google.maps.Geocoder();
        marker = new google.maps.Marker({
            position: myLatlng,
            draggable: true,
            map: map
        });
        infoWindow = new google.maps.InfoWindow();

        if (address != '') {
            moveMap(address);
            document.getElementById("address").value = address;
        }

        google.maps.event.addListener(map, 'center_changed', function () {
            marker.setPosition(map.getCenter());
            var location = marker.getPosition();
            document.getElementById("lat").value = location.lat();
            document.getElementById("lon").value = location.lng();
        });
        google.maps.event.addListener(map, 'zoom_changed', function () {
            document.getElementById("zoom").value = map.getZoom();
        });
        google.maps.event.addListener(marker, 'dragend', function () {
            map.setCenter(marker.getPosition());
        });

        document.getElementById("zoom").value = map.getZoom();
        document.getElementById("lat").value = marker.getPosition().lat();
        document.getElementById("lon").value = marker.getPosition().lng();

    }

    function moveMap(address) {
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == 'OK') {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });
            } else {
                console.log('Geocode was not successful for the following reason: ' + status);
            }
        });
    }

    function moveCurrentPos() {
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    map.setCenter(pos);
                },
                () => {
                    handleLocationError(true, infoWindow, map.getCenter());
                }
            );
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }
    }

    function saveCoords() {
        document.formEvent.location.value =  document.getElementById("address").value;
        document.formEvent.locgmlat.value = marker.getPosition().lat();
        document.formEvent.locgmlon.value = marker.getPosition().lng();
        document.formEvent.locgmzoom.value = map.getZoom();
        $('#modalCoordsPicker').modal('hide');
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(
            browserHasGeolocation
                ? "Error: The Geolocation service failed."
                : "Error: Your browser doesn't support geolocation."
        );
        infoWindow.open(map);
    }

    $('#modalCoordsPicker').on('shown.bs.modal', function () {
        GMinit();
    })
</script>


