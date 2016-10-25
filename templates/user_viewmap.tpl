{php}
    $points = array();
    $showmap = false;
    foreach ($this->get_template_vars('objectArray') as $address) {
        if (isset($address['geodata']) && $address['geodata']) {
            $showmap = true;
            $lblTitle = '';
            if ($address['title']) $lblTitle .= $address['title'].' ';
            if ($address['fname']) $lblTitle .= $address['fname'].' ';
            if ($address['lname']) $lblTitle .= $address['lname'].' ';
            if ($address['company']) $lblTitle .= $address['company'];
            $markerhtml = '';
            if ($address['img']) $markerhtml .= '<img src="'.System::getBaseUrl().$address['img'].'" alt="" height="50" width="70" style="float:left;margin:0 2px 0 0" />';
            $markerhtml .= '<strong>'.$lblTitle.'</strong><br >';
            if ($address['address1']) $markerhtml .= $address['address1'].'<br >';
            if ($address['city']) $markerhtml .= $address['city'].' ';
            if ($address['state']) $markerhtml .= $address['state'].' ';
            if ($address['country']) $markerhtml .= $address['country'];
            $points[] = array('lat_long' => $address['geodata'], 'title' => $lblTitle, 'html' => $markerhtml, 'tooltip' => $lblTitle, 'icon' => System::getBaseUrl().'modules/AddressBook/images/marker_green-dot.png', 'iconshadow' => System::getBaseUrl().'modules/AddressBook/images/marker_shadow.png');
        }
    }
     $this->assign('points', $points);
     $this->assign('showmap', $showmap);
{/php}
{if $showmap}{AddressShowGmap api_key=$preferences.google_api_key height='500px' mapid='View' maptype='roadmap' points=$points}{/if}
