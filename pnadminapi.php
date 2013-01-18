<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: pnadminapi.php 61 2010-03-31 13:44:02Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 * @subpackage API
 */

function AddressBook_adminapi_getlinks()
{
    $dom = ZLanguage::getModuleDomain('AddressBook');
    $links = array();

    if (SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)) {
        $args = array();
        $args['ot'] = 'labels';
        $url = ModUtil::url('AddressBook', 'admin', 'view', $args);
        $links[] = array('url' => $url, 'text' => __('Contact labels', $dom));
    }

    if (SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)) {
        $args = array();
        $args['ot'] = 'customfield';
        $url = ModUtil::url('AddressBook', 'admin', 'view', $args);
        $links[] = array('url' => $url, 'text' => __('Custom fields', $dom));
    }

    if (SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)) {
        $args = array();
        $args['ot'] = 'address';
        $url = ModUtil::url('AddressBook', 'admin', 'modifyconfig', $args);
        $links[] = array('url' => $url, 'text' => __('Settings', $dom));
    }

    return $links;
}