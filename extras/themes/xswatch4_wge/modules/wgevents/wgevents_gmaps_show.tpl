<style>
    #map_wrapper_div {
        height: 400px;
    }
    #googlemap_list {
        width: 100%;
        height: <{$gmapsHeight|default:'100%'}>;
    }
</style>
<div id="map_wrapper_div">
    <div id="googlemap_list"></div>
</div>
<script type="text/javascript">
    jQuery(function($) {
    // Asynchronously Load the map API
        var script = document.createElement('script');
        script.src = "https://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize&key=<{$api_key|default:''}>";
        document.body.appendChild(script);
    });
    function initialize() {
        var map;
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            mapTypeId: 'roadmap'
        };
        // Display a map on the page
        map = new google.maps.Map(document.getElementById("googlemap_list"), mapOptions);
        map.setTilt(45);
        // Multiple Markers
        var markers = [
            <{foreach item=evmap from=$eventsMap name=eventsMap}>
                ['<{$evmap.name|default:''}>', <{$evmap.lat|default:0}>, <{$evmap.lon|default:0}>],
            <{/foreach}>
        ];
        // Info Window Content
        var infoWindowContent = [
            <{foreach item=evmap from=$eventsMap name=eventsMap}>
                ['<div class="info_content">' +
                    '<h3><{$evmap.name|default:''}></h3>' +
                    '<p><{$evmap.location|default:''}></p>' +
                    '<p><{$smarty.const._MA_WGEVENTS_EVENT_DATEFROM}>: <{$evmap.from|default:0}></p>' +
                    '<p><a class="btn btn-primary" href="<{$evmap.url|default:''}>"><{$smarty.const._MA_WGEVENTS_DETAILS}></a></p>' +
                '</div>'],
            <{/foreach}>
        ];
        // Display multiple markers on a map
        var infoWindow = new google.maps.InfoWindow(), marker, i;
        // Loop through our array of markers & place each one on the map
        for( i = 0; i < markers.length; i++ ) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: markers[i][0]
            });
            // Each marker to have an info window
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infoWindow.setContent(infoWindowContent[i][0]);
                    infoWindow.open(map, marker);
                }
            })(marker, i));
            // Automatically center the map fitting all markers on the screen
            map.fitBounds(bounds);
        }
        // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            if (this.getZoom() > 15) {
                // Change min zoom
                this.setZoom(15);
            }
            google.maps.event.removeListener(boundsListener);
        });
    }
</script>


