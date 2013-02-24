<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

function smarty_function_AddressShowGmap($params, &$smarty)
{
    $dom = ZLanguage::getModuleDomain('AddressBook');

    $assign = isset($params['assign']) ? $params['assign'] : null;

    $directions = '';
    if (isset($params['directions'])) {
        $directions = '<a href="http://maps.google.com/maps?f=d&daddr='.$params['lat_long'];
        if (isset($params['zoomlevel'])) {
            $directions .= '&z='.$params['zoomlevel'];
        }
        $directions .= '" target="_blank">'.__('Get directions to this location', $dom).'</a>';
    }
    if (!empty($directions)) {
        $directions = '<div>'.$directions.'</div>';
    }

    include_once('modules/AddressBook/lib/vendor/GMaps/GoogleMapV3.php');
    $map_id = 'googlemap';
    if (isset($params['mapid'])) {
        $map_id .= $params['mapid'];
    }
    $app_id = 'ZikulaAddressBook';
    $map = new GoogleMapAPI($map_id, $app_id);
    if (isset($params['maptype'])) {
        $map->setMapType($params['maptype']); // hybrid, satellite, terrain, roadmap
    }
    if (isset($params['zoomlevel'])) {
        $map->setZoomLevel($params['zoomlevel']);
    }
    $map->setTypeControlsStyle('dropdown');
    $map->setWidth(($params['width']) ? $params['width'] : '100%');
    $map->setHeight(($params['height']) ? $params['height'] : '400px');
    // handle one (center) point
    if (isset($params['lat_long'])) {
        $arrLatLong = explode(',', $params['lat_long']);
        $map->setCenterCoords($arrLatLong[1], $arrLatLong[0]);
        $map->addMarkerByCoords($arrLatLong[1], $arrLatLong[0], $params['title'], $params['html'], $params['tooltip'], $params['icon'], $params['iconshadow']);
    }
    // handle array of points
    if (isset($params['points'])) {
        foreach($params['points'] as $point) {
            $arrLatLong = explode(',', $point['lat_long']);
            $map->addMarkerByCoords($arrLatLong[1], $arrLatLong[0], $point['title'], $point['html'], $point['tooltip'], $point['icon'], $point['iconshadow']);
        }
    }
    // load the map
    $map->enableOnLoad();

    if ($assign) {
        $result = $map->getHeaderJS() . $map->getMapJS() . $directions. $map->printMap() . $map->printOnLoad();
        $smarty->assign($assign, $result);
    } else {
        PageUtil::addVar('rawtext', $map->getHeaderJS());
        PageUtil::addVar('rawtext', $map->getMapJS());
        return $directions . $map->printMap() . $map->printOnLoad();
    }
}
