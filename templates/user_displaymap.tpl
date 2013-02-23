<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    //<![CDATA[
    Event.observe(window, 'load',
    function() { 
        var mapOptions = { zoom: {{$preferences.google_zoom|varprepfordisplay}}, 
            center: new google.maps.LatLng({{$address.geodata|varprepfordisplay}}), 
            mapTypeId: google.maps.MapTypeId.ROADMAP }
        var map = new google.maps.Map(document.getElementById("googlemap{{$address.id|varprepfordisplay}}"), mapOptions);
        var marker = new google.maps.Marker({ position: new google.maps.LatLng({{$address.geodata|varprepfordisplay}}), map: map, 
            title:"{{gt text="Address location"}}" });
    }, false);
    //]]>
</script>
