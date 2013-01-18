<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: pnadminform.php 61 2010-03-31 13:44:02Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 * @subpackage UI
 */

function AddressBook_adminform_modifyconfig()
{
    $dom = ZLanguage::getModuleDomain('AddressBook');

    // permission check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
        return LogUtil::registerPermissionError();
    }

    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(ModUtil::url('AddressBook', 'admin', 'main'));
    }

    // retrieve the associative preferences array
    $prefs = FormUtil::getPassedValue('preferences', null, 'POST');

    // now for each perference entry, set the appropriate module variable
    ModUtil::setVar('AddressBook', 'abtitle', (isset($prefs['abtitle']) ? $prefs['abtitle'] : ''));
    ModUtil::setVar('AddressBook', 'special_chars_1', (isset($prefs['special_chars_1']) ? $prefs['special_chars_1'] : ''));
    ModUtil::setVar('AddressBook', 'special_chars_2', (isset($prefs['special_chars_2']) ? $prefs['special_chars_2'] : ''));
    ModUtil::setVar('AddressBook', 'globalprotect', (isset($prefs['globalprotect']) ? $prefs['globalprotect'] : 0));
    ModUtil::setVar('AddressBook', 'use_prefix', (isset($prefs['use_prefix']) ? $prefs['use_prefix'] : 0));
    ModUtil::setVar('AddressBook', 'use_img', (isset($prefs['use_img']) ? $prefs['use_img'] : 0));
    ModUtil::setVar('AddressBook', 'google_api_key', (isset($prefs['google_api_key']) ? $prefs['google_api_key'] : ''));
    ModUtil::setVar('AddressBook', 'google_zoom', (isset($prefs['google_zoom']) ? $prefs['google_zoom'] : 15));
    ModUtil::setVar('AddressBook', 'itemsperpage', ($prefs['itemsperpage']>1 ? $prefs['itemsperpage'] : 30));
    ModUtil::setVar('AddressBook', 'custom_tab', (isset($prefs['custom_tab']) ? $prefs['custom_tab'] : ''));

    if (mb_strlen($prefs['special_chars_1']) != mb_strlen($prefs['special_chars_2']))
    LogUtil::registerError(__('Error! Both fields must contain the same number of characters - Special character replacement was NOT saved!', $dom));


    // redirect back to to main admin page
    LogUtil::registerStatus (__('Done! Configuration saved.', $dom));
    return System::redirect(ModUtil::url('AddressBook', 'admin', 'main'));
}

function AddressBook_adminform_edit()
{
    // security check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('AddressBook');

    $ot =  FormUtil::getPassedValue('ot', 'categories', 'POST');
    $url = ModUtil::url('AddressBook', 'admin', 'view', array('ot'=>$ot));

    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError($url);
    }

    if (FormUtil::getPassedValue('button_cancel', null, 'POST')) {
        LogUtil::registerStatus ('Operation cancelled.');
        return System::redirect($url);
    }

    if (!($class = Loader::loadClassFromModule('AddressBook', $ot))) {
        return z_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
    }

    $object = new $class();
    $object->getDataFromInput();
    $object->save();

    if ($ot == "customfield")
    {
        $obj = $object->getDataFromInput();
        if ($obj['type']=='dropdown')
        $obj['type']='text';
        if ($obj['id']) {
            $sql="ALTER TABLE addressbook_address CHANGE adr_custom_".$obj['id']." adr_custom_".$obj['id']." ".$obj['type'];
        } else {
            $cus_id = DBUtil::getInsertID('addressbook_customfields');
            $sql="ALTER TABLE addressbook_address ADD adr_custom_".$cus_id." ".$obj['type'];
        }
        DBUtil::executeSQL($sql,-1,-1,false,true);
    }

    return System::redirect($url);
}


function AddressBook_adminform_delete()
{
    // security check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('AddressBook');

    $ot = FormUtil::getPassedValue('ot', 'categories', 'GETPOST');
    $id = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');

    $url = ModUtil::url('AddressBook', 'admin', 'view', array('ot'=>$ot));

    if (!($class = Loader::loadClassFromModule('AddressBook', $ot))) {
        return z_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
    }

    $object = new $class();
    $data = $object->get($id);
    if (!$data) {
        LogUtil::registerError(__f('%1$s with ID of %2$s doesn\'\t seem to exist', array($ot, $id), $dom));
        return System::redirect($url);
    }
    $object->delete();

    if ($ot == "customfield")
    {
        $sql="ALTER TABLE addressbook_address DROP adr_custom_".$id;
        DBUtil::executeSQL($sql,-1,-1,true,true);
    }
    LogUtil::registerStatus (__('Done! Item deleted.', $dom));

    return System::redirect($url);
}
