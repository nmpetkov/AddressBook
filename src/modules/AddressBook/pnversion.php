<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: pnversion.php 70 2010-04-01 14:46:28Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 * @subpackage Init
 */

$dom = ZLanguage::getModuleDomain('AddressBook');

$modversion['name']             = 'AddressBook';
$modversion['oldnames']         = array('Addressbook');
$modversion['displayname']      = __('Address book', $dom);
$modversion['url']              = __('addressbook', $dom);
$modversion['version']          = '1.3.1';
$modversion['description']      = __('A name and address book (NAB) is for storing entries called contacts. Each contact entry usually consists of a few standard fields.', $dom);
$modversion['credits']          = 'docs/credits.txt';
$modversion['help']             = 'docs/help.txt';
$modversion['changelog']        = 'docs/changelog.txt';
$modversion['license']          = 'docs/license.txt';
$modversion['official']         = 0;
$modversion['author']           = 'AddressBook Development Team';
$modversion['contact']          = 'http://code.zikula.org/addressbook/';
$modversion['admin']            = 1;
$modversion['securityschema']   = array('AddressBook::' => '::');
