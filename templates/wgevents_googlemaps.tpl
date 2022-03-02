<{*
/*
* You may not change or alter any portion of this comment or credits
* of supporting developers from this source code or any supporting source code
* which is considered copyrighted (c) material of the original comment or credit authors.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
* @copyright   XOOPS Project (http://xoops.org)
* @license     http://www.fsf.org/copyleft/ gpl.html GNU public license
* @author      Antiques Promotion (http://www.antiquespromotion.ca)
*/
*}>
<!--<![CDATA[-->
<script type="text/javascript">
    var GMlat = <{$GMlatitude}>;
        var GMlong = <{$GMlongitude}>;
            var GMzoom = <{$GMzoom}>;
                var points = new Array();
                var URL = '<{$xoops_url}>';
</script>
<{foreach name=GMPoints item=point from=$GMPoints}>
    <script type="text/javascript">
        points.push(new Array("<{$point.summary}>", <{$point.gmlat}>, <{$point.gmlong}>, "<{$point.location}>", "<{$point.contact}>", <{$point.startDate}>, "<{$point.event_id}>"));
    </script>
    <{/foreach}>
<!--]]>-->

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    var panorama;

    function GMInit() {
        var latlng = new google.maps.LatLng(GMlat, GMlong);
        var myOptions = {zoom: GMzoom, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP, scrollwheel: false, streetViewControl: true};
        var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        var bounds = new google.maps.LatLngBounds();
        var service = new google.maps.StreetViewService();
        panorama = map.getStreetView();

        var content = document.createElement('div');
        var title = document.createElement('div');
        var info = document.createElement('div');
        var streetview = document.createElement('div');
        var infowindow = new google.maps.InfoWindow({content: content});
        title.style.fontSize = '1.3em';
        content.appendChild(title);
        content.appendChild(info);
        content.appendChild(streetview);

        var today = new Date();
        today = today.getDate();
        for (i in points) {
            if (points[i][5] >= today)
                break;
        }
        var start = points.slice(0, i);
        var end = points.slice(i);
        points = end.concat(start);


        var iconsURL = URL + '/modules/apcal/assets/images/googlemaps/';
        for (i in points) {
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(points[i][1], points[i][2]),
                zIndex: -i,
                map: map,
                icon: iconsURL + points[i][5] + '.png',
                eventID: points[i][6],
                title: points[i][0],
                location: points[i][3],
                contact: points[i][4],
                hasPano: false
            });

            (function (m) {
                service.getPanoramaByLocation(marker.getPosition(), 50, function (data, status) {
                    if (status == google.maps.StreetViewStatus.OK) {
                        m.hasPano = true;
                    }
                })
            })(marker);

            google.maps.event.addListener(marker, "click", function () {
                title.innerHTML = '<a href="' + URL + '/modules/apcal/index.php?action=View&event_id=' + this.eventID + '">' + this.title + '</a>';
                info.innerHTML = this.location + '<br><br>' + this.contact;
                streetview.innerHTML = this.hasPano ? '<br><br>' + '<a href="" onclick="showStreetView(' + this.getPosition().lat() + ', ' + this.getPosition().lng() + ');return false;">Street View</a>' : '';

                infowindow.open(map, this);
            });

            bounds.extend(new google.maps.LatLng(points[i][1], points[i][2]));
        }

        if (!bounds.isEmpty())
            map.fitBounds(bounds);

        var loadedListener = google.maps.event.addListener(map, 'tilesloaded', function () {
            if (map.getZoom() > 11)
                map.setZoom(11);
            google.maps.event.removeListener(loadedListener);
        });
    }

    function showStreetView(lat, lng) {
        panorama.setPosition(new google.maps.LatLng(lat, lng));
        panorama.setVisible(true);

    }

    window.onload = GMInit;
</script>

<div id="map_canvas" style="width:100%; height:<{$GMheight}>;"></div>
<br>