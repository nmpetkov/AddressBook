<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: pnadmin.php 61 2010-03-31 13:44:02Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 * @subpackage UI
 */


//=========================================================================
//  the main administration function
//=========================================================================
function AddressBook_admin_main()
{
    // security check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
        return LogUtil::registerPermissionError();
    }

    return AddressBook_admin_modifyconfig();
}

//=========================================================================
//  Modify the settings
//=========================================================================
function AddressBook_admin_modifyconfig()
{

    // security check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
        return LogUtil::registerPermissionError();
    }

    $Renderer = Zikula_View::getInstance('AddressBook', false);
    $Renderer->assign('preferences', ModUtil::getVar('AddressBook'));
    return $Renderer->fetch('addressbook_admin_modifyconfig.html');
}


//=========================================================================
//  Modify the categories, labels, custom fields
//=========================================================================
function AddressBook_admin_edit($args)
{
    // security check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('AddressBook');

    $ot = FormUtil::getPassedValue('ot', 'categories', 'GET');
    $id = (int)FormUtil::getPassedValue('id', 0, 'GET');

    if (!($class = Loader::loadClassFromModule('AddressBook', $ot))) {
        return z_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
    }

    $data = array();
    if ($id) {
        $object = new $class();
        $data = $object->get($id);
    } else {
        $data['id'] = 0;
    }

    $Renderer = Zikula_View::getInstance('AddressBook', false);
    $Renderer->assign($ot, $data);

    if ($ot=="customfield") {
        $new_position = DBUtil::selectFieldMax('addressbook_customfields', 'position') + 1;
        $Renderer->assign('new_position', $new_position);
    }

    $tpl = 'addressbook_admin_' . $ot . '_edit.html';

    return $Renderer->fetch($tpl);
}

//=========================================================================
//  Modify the categories, labels, custom fields
//=========================================================================
function AddressBook_admin_view()
{
    // security check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('AddressBook');

    $ot   = FormUtil::getPassedValue('ot', 'categories', 'GET');
    $sort = FormUtil::getPassedValue('sort', 'id', 'GET');
    $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
    $pagesize = ModUtil::getVar('AddressBook', 'itemsperpage', 25);

    if ($ot == "customfield")
    $sort = "cus_pos";

    $where = '';
    if (!($class = Loader::loadClassFromModule('AddressBook', $ot, true))) {
        return z_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
    }

    $objectArray = new $class();
    $data = $objectArray->get ($where, $sort, $startnum-1, $pagesize);
    $objcount = $objectArray->getCount ($where);

    $Renderer = Zikula_View::getInstance('AddressBook', false);
    $Renderer->assign('objectArray', $data);

    // Assign the information required to create the pager
    $Renderer->assign('pager', array('numitems'     => $objcount,
                                     'itemsperpage' => $pagesize));

    $tpl = 'addressbook_admin_' . $ot . '_view.html';

    return $Renderer->fetch($tpl);
}

//=========================================================================
//  Delete categories, labels, custom fields
//=========================================================================
function AddressBook_admin_delete()
{
    // Security Check
    if (!SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('AddressBook');

    $ot = FormUtil::getPassedValue('ot', 'categories', 'GETPOST');
    $id = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');
    $confirmation = (int)FormUtil::getPassedValue('confirmation', false);

    $url = ModUtil::url('AddressBook', 'admin', 'view', array('ot'=>$ot));

    // Check for existence
    if (!($class = Loader::loadClassFromModule('AddressBook', $ot))) {
        return z_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
    }

    $object = new $class();
    $data = $object->get($id);
    if (!$data) {
        LogUtil::registerError(__('Error! No such item found.', $dom), 404);
        return System::redirect($url);
    }

    // Check for confirmation.
    if (empty($confirmation)) {
        $Renderer = Zikula_View::getInstance('AddressBook', false);
        $Renderer->assign('id', $id);
        $Renderer->assign('ot', $ot);
        $Renderer->assign('object', $data);
        return $Renderer->fetch('addressbook_admin_delete.htm');
    }

    // If we get here it means that the user has confirmed the action
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError($url);
    }

    if (ModUtil::func('AddressBook', 'adminform', 'delete',
    array('id' => $id, 'ot' => $ot))) {
        // Success
        LogUtil::registerStatus (__('Done! Item deleted.', $dom));
    }

    return System::redirect($url);
}
