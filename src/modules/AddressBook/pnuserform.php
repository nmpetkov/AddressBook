<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: pnuserform.php 70 2010-04-01 14:46:28Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 * @subpackage UI
 */

function AddressBook_userform_edititem()
{
    // security check
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError($url);
    }

    $dom = ZLanguage::getModuleDomain('AddressBook');

    // get passed values
    $ot         = FormUtil::getPassedValue('ot', 'address', 'POST');
    $startnum   = FormUtil::getPassedValue('startnum', 1, 'GET');
    $letter     = FormUtil::getPassedValue('letter', 0);
    $sort       = FormUtil::getPassedValue('sort', 'sortname ASC');
    $search     = FormUtil::getPassedValue('search', 0);
    $category   = FormUtil::getPassedValue('category', 0);
    $private    = FormUtil::getPassedValue('private', 0);
    $returnid   = FormUtil::getPassedValue('returnid', 0, 'POST');

    // build standard return url
    if(!empty($returnid)) {
        $url = pnModURL('AddressBook', 'user', 'display', array('id'=>$returnid,
                                                           'ot'=>$ot,
                                                           'startnum'=>$startnum,
                                                           'letter'=>$letter,
                                                           'sort'=>$sort,
                                                           'search'=>$search,
                                                           'category'=>$category,
                                                           'private'=>$private));
    } else {
        $url = pnModURL('AddressBook', 'user', 'view', array('ot'=>$ot,
                                                           'startnum'=>$startnum,
                                                           'letter'=>$letter,
                                                           'sort'=>$sort,
                                                           'search'=>$search,
                                                           'category'=>$category,
                                                           'private'=>$private));
    }

    // load class and data
    if (!($class = Loader::loadClassFromModule('AddressBook', 'address'))) {
        return pn_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
    }
    $object = new $class();
    $data =& $object->getDataFromInput();

    // permission check
    if (pnUserLoggedIn()) {
        $user_id = pnUserGetVar('uid');
    } else {
        $user_id = 0;
    }
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_EDIT) || ($user_id >0 && $user_id == $data['user_id']))) {
        return LogUtil::registerPermissionError();
    }

    // validation
    if (!$object->validate()) {
        return pnRedirect(pnModURL('AddressBook', 'user', 'edit'));
    }

    // check for duplication request and return to the form
    if (FormUtil::getPassedValue('btn_duplicate', null, 'POST')) {
        $url = pnModURL('AddressBook', 'user', 'edit', array('ot'=>$ot,
                                                         'id' => $data['id'],
                                                         'duplicate' => 1,
                                                         'startnum'=>$startnum,
                                                         'letter'=>$letter,
                                                         'sort'=>$sort,
                                                         'search'=>$search,
                                                         'category'=>$category,
                                                         'private'=>$private));

        return pnRedirect($url);
    }

    // check for company update - part 1: get the old data
    if ($data['id']) {
        $oldObject = DBUtil::selectObjectByID('addressbook_address', $data['id']);
        if (($oldObject['company'])&&(($oldObject['company']!=$data['company'])||($oldObject['address1']!=$data['address1'])||($oldObject['address2']!=$data['address2'])||($oldObject['zip']!=$data['zip'])||($oldObject['city']!=$data['city'])||($oldObject['state']!=$data['state'])||($oldObject['country']!=$data['country'])))
        {
            $companyHasChanged = true;
            $url = pnModURL('AddressBook', 'user', 'change_company', array('ot'=>$ot,
                                                                       'id' => $data['id'],
                                                                       'oldvalue'=>$oldObject['company'],
                                                                       'startnum'=>$startnum,
                                                                       'letter'=>$letter,
                                                                       'sort'=>$sort,
                                                                       'search'=>$search,
                                                                       'category'=>$category,
                                                                       'private'=>$private));
        }
    }

    // save or update the object
    $object->save();

    // create a status message
    LogUtil::registerStatus (__('Done! The address was saved.', $dom));

    // clear the the session from FailedObjects
    FormUtil::clearValidationFailedObjects('address');

    // check for save and duplicate request and return to the form
    if (FormUtil::getPassedValue('btn_save_duplicate', null, 'POST')) {
        $url = pnModURL('AddressBook', 'user', 'edit', array('ot'=>$ot,
                                                         'id' => $data['id'],
                                                         'duplicate' => 1,
                                                         'startnum'=>$startnum,
                                                         'letter'=>$letter,
                                                         'sort'=>$sort,
                                                         'search'=>$search,
                                                         'category'=>$category,
                                                         'private'=>$private));
    }

    // return to standard return url
    return pnRedirect($url);
}

function AddressBook_userform_delete()
{
    $dom = ZLanguage::getModuleDomain('AddressBook');
    // security check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_DELETE))) {
        return LogUtil::registerPermissionError();
    }

    $ot = FormUtil::getPassedValue('ot', 'address', 'GETPOST');
    $id = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');

    $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
    $letter   = FormUtil::getPassedValue('letter', 0);
    $sort     = FormUtil::getPassedValue('sort', 'sortname ASC');
    $search   = FormUtil::getPassedValue('search', 0);
    $category = FormUtil::getPassedValue('category', 0);
    $private  = FormUtil::getPassedValue('private', 0);

    $url = pnModURL('AddressBook', 'user', 'view', array('ot'=>$ot,
                                                         'startnum'=>$startnum,
                                                         'letter'=>$letter,
                                                         'sort'=>$sort,
                                                         'search'=>$search,
                                                         'category'=>$category,
                                                         'private'=>$private));

    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError($url);
    }

    if (!($class = Loader::loadClassFromModule('AddressBook', 'address'))) {
        return pn_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
    }

    $object = new $class();
    $data = $object->get($id);

    if (!$data) {
        LogUtil::registerError(__('Error! The deletion of this address failed.', $dom));
        return pnRedirect($url);
    }

    $object->delete();
    LogUtil::registerStatus(__('Done! The deletion of this address was successful.', $dom));

    return pnRedirect($url);
}

function AddressBook_userform_change_company()
{
    $dom = ZLanguage::getModuleDomain('AddressBook');

    $ot = FormUtil::getPassedValue('ot', 'address', 'GETPOST');
    $id = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');
    $oldvalue = (int)FormUtil::getPassedValue('oldvalue', 0, 'GETPOST');
    $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
    $letter   = FormUtil::getPassedValue('letter', 0);
    $sort     = FormUtil::getPassedValue('sort', 'sortname ASC');
    $search   = FormUtil::getPassedValue('search', 0);
    $category = FormUtil::getPassedValue('category', 0);
    $private  = FormUtil::getPassedValue('private', 0);

    $url = pnModURL('AddressBook', 'user', 'view', array('ot'=>$ot,
                                                         'startnum'=>$startnum,
                                                         'letter'=>$letter,
                                                         'sort'=>$sort,
                                                         'search'=>$search,
                                                         'category'=>$category,
                                                         'private'=>$private));

    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError($url);
    }

    if (!($class = Loader::loadClassFromModule('AddressBook', 'address'))) {
        return pn_exit(__('Error! Unable to load class [address]', $dom));
    }
    $object = new $class();
    $data = $object->get($id);

    // security check
    // Get user id
    if (pnUserLoggedIn()) {
        $user_id = pnUserGetVar('uid');
    } else {
        $user_id = 0;
    }
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_EDIT) || $user_id == $data['user_id'])) {
        return LogUtil::registerPermissionError();
    }

    $obj = array('company'  => $data[company],
                 'address1' => $data[address1],
                 'address2' => $data[address2],
                 'zip'      => $data[zip],
                 'city'     => $data[city],
                 'state'    => $data[state],
                 'country'  => $data[country]);

    $res = DBUtil::updateObject($obj, 'addressbook_address', '', 'company');

    if (!$res) {
        LogUtil::registerError (__('Error! Company update failed.', $dom));
        return pnRedirect($url);
    }
    LogUtil::registerStatus (__('Done! Company update successful.', $dom));

    return pnRedirect($url);
}