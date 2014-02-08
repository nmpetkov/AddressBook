<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_Controller_Ajax extends Zikula_AbstractController
{
    function addfavourite()
    {
        $objectid = FormUtil::getPassedValue('objectid', null, 'POST');
        $userid   = FormUtil::getPassedValue('userid', null, 'POST');

        if (!SecurityUtil::checkPermission('AddressBook::', "::", ACCESS_COMMENT)) {
            AjaxUtil::error($this->__('Error! No authorization to access this module.'));
        }

        $obj['favadr_id'] = $objectid;
        $obj['favuser_id'] =  $userid;
        DBUtil::insertObject ($obj, 'addressbook_favourites');

        return;
    }

    function deletefavourite()
    {
        $objectid = FormUtil::getPassedValue('objectid', null, 'POST');
        $userid   = FormUtil::getPassedValue('userid', null, 'POST');

        if (!SecurityUtil::checkPermission('AddressBook::', "::", ACCESS_COMMENT)) {
            AjaxUtil::error($this->__('Error! No authorization to access this module.'));
        }

        $ztables    = DBUtil::getTables();
        $fav_column = $ztables['addressbook_favourites_column'];
        $where      = "$fav_column[favadr_id] = '" . DataUtil::formatForStore($objectid) . "' AND $fav_column[favuser_id] = '" . DataUtil::formatForStore($userid) . "'";
        DBUtil::deleteWhere ('addressbook_favourites', $where);

        return;
    }

    function change_cf_order()
    {
        if (!SecurityUtil::checkPermission('AddressBook::', "::", ACCESS_ADMIN)) {
            AjaxUtil::error($this->__('Error! No authorization to access this module.'));
        }

        $cf_list = FormUtil::getPassedValue('cf_list');

        // add new custom field positions
        $cfplacements = array();
        for ($i = 0; $i < count($cf_list); $i++)
        {
            $cfplacements[] = array('id' => $cf_list[$i][id], 'position' => $i+1);
        }
        $res = DBUtil::updateObjectArray($cfplacements, 'addressbook_customfields');
        if (!$res) {
            AjaxUtil::error($this->__('Error! Update attempt failed.'));
        }

        return array('result' => true);
    }

    function get_geodata()
    {
        if (!SecurityUtil::checkPermission('AddressBook::', "::", ACCESS_EDIT)) {
            AjaxUtil::error($this->__('Error! No authorization to access this module.'));
        }

        $val_1 = FormUtil::getPassedValue('val_1', NULL, 'GETPOST');
        $val_2 = FormUtil::getPassedValue('val_2', NULL, 'GETPOST');
        $val_3 = FormUtil::getPassedValue('val_3', NULL, 'GETPOST');
        $val_4 = FormUtil::getPassedValue('val_4', NULL, 'GETPOST');

        //GMaps test
        /*include_once('modules/AddressBook/lib/vendor/GMaps/GoogleMapAPI.class.php');
        $key = ModUtil::getVar('AddressBook', 'google_api_key');
        $map = new GoogleMapAPI();
        $map->setAPIKey($key);*/
        include_once('modules/AddressBook/lib/vendor/GMaps/GoogleMapV3.php');
        $map = new GoogleMapAPI();
        $geocode = $map->getGeocode($val_1.', '.$val_2.', '.$val_3.', '.$val_4);
        $result = $geocode['lat'].','.$geocode['lon'];

        if (FormUtil::getPassedValue('plane', NULL, 'GETPOST')) {
            return $result;
        }
        return array('data' => $result,'result' => true);
    }
}
