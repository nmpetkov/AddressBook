{*
<div id="googlemap{$address.id|varprepfordisplay}" class="map" style="width: 100%; height: 200px; resize:both;"></div>
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
*}
{capture name='markerhtml'}
{if $address.img && $preferences.use_img==1}<img src="{$baseurl|cat:$address.img}" alt="" height="50" width="70" style="float:left;margin:0 2px 0 0" />{/if}
<strong>{if $address.title}{$address.title} {/if}{if $address.fname}{$address.fname} {/if}{if $address.lname}{$address.lname} {/if}{if $address.company}{$address.company}{/if}</strong><br >
{if $address.address1}{$address.address1}<br >{/if}
{if $address.city}{$address.city} {/if}{if $address.state}{$address.state} {/if}{if $address.country}{$address.country}{/if}
{/capture}
{gt text="Address location" assign='lblTitle'}
{AddressShowGmap zoomlevel=$preferences.google_zoom lat_long=$address.geodata mapid=$address.id maptype='roadmap' title=$lblTitle html=$smarty.capture.markerhtml directions=true icon=$baseurl|cat:'modules/AddressBook/images/marker_green-dot.png' iconshadow=$baseurl|cat:'modules/AddressBook/images/marker_shadow.png'}