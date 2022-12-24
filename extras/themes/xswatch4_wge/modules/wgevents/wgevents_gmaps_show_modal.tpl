<!-- Modal -->
<div class="modal fade" id="modalGMap" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><{$smarty.const._MA_WGEVENTS_EVENT_GM_SHOW}></h5>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><{$smarty.const._CLOSE}></button>
            </div>
        </div>
    </div>
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<{$api_key|default:''}>" type="text/javascript"></script>

<script type="text/javascript">
    var map;
    var geocoder;
    var marker;
    var infoWindow;
    var service;
    var myLatlng;

    function GMinit() {
        var address = document.getElementById("location").value;
        var latitude = document.getElementById("locgmlat").value;
        var longitude = document.getElementById("locgmlon").value;
        var zoom = parseInt(document.getElementById("locgmzoom").value);

        document.getElementById("address").value = address;

        var myLatlng = new google.maps.LatLng(latitude, longitude);

        map = new google.maps.Map(document.getElementById("googlemap"), {
            zoom: zoom,
            center: myLatlng,
        });

        new google.maps.Marker({
            position: myLatlng,
            map,
            title: "<{$event.name|default:''}>",
        });
    }

    $('#modalGMap').on('shown.bs.modal', function () {
        GMinit();
    })
</script>


