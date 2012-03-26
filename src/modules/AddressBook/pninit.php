<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: pninit.php 70 2010-04-01 14:46:28Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 * @subpackage Init
 */

function AddressBook_init()
{
    $dom = ZLanguage::getModuleDomain('AddressBook');
    // create labels table
    if (!DBUtil::createTable('addressbook_labels')) {
        return false;
    }

    // insert default values
    $objArray = array();
    $objArray[] = array(
        'id'               => 1,
        'name'             => DataUtil::formatForStore(__('Work', $dom)));
    $objArray[] = array(
        'id'               => 2,
        'name'             => DataUtil::formatForStore(__('Fax', $dom)));
    $objArray[] = array(
        'id'               => 3,
        'name'             => DataUtil::formatForStore(__('Mobile', $dom)));
    $objArray[] = array(
        'id'               => 4,
        'name'             => DataUtil::formatForStore(__('Home', $dom)));
    $objArray[] = array(
        'id'               => 5,
        'name'             => DataUtil::formatForStore(__('Email', $dom)));
    $objArray[] = array(
        'id'               => 6,
        'name'             => DataUtil::formatForStore(__('URL', $dom)));
    $objArray[] = array(
        'id'               => 7,
        'name'             => DataUtil::formatForStore(__('Other', $dom)));

    DBUtil::insertObjectArray($objArray, 'addressbook_labels', 'id', true);
    unset($objectArray);

    // create custom field table
    if (!DBUtil::createTable('addressbook_customfields')) {
        return false;
    }

    // insert default values
    $objArray = array();
    $objArray[] = array(
        'id'               => 1,
        'name'             => DataUtil::formatForStore(__('Custom Label 1', $dom)),
        'type'             => 'varchar(60) default NULL',
        'position'         => 1);
    $objArray[] = array(
        'id'               => 2,
        'name'             => DataUtil::formatForStore(__('Custom Label 2', $dom)),
        'type'             => 'varchar(60) default NULL',
        'position'         => 2);
    $objArray[] = array(
        'id'               => 3,
        'name'             => DataUtil::formatForStore(__('Custom Label 3', $dom)),
        'type'             => 'varchar(60) default NULL',
        'position'         => 3);
    $objArray[] = array(
        'id'               => 4,
        'name'             => DataUtil::formatForStore(__('Custom Label 4', $dom)),
        'type'             => 'varchar(60) default NULL',
        'position'         => 4);

    DBUtil::insertObjectArray($objArray, 'addressbook_customfields', 'id', true);
    unset($objectArray);


    // create favourites table
    if (!DBUtil::createTable('addressbook_favourites')) {
        return false;
    }

    // finally create the address table
    if (!DBUtil::createTable('addressbook_address')) {
        return false;
    }

    // create our default categories
    if (!_addressbook_createdefaultcategory()) {
        return LogUtil::registerError(__('Error! Creation attempt failed.', $dom));
    }


    // Set up an initial value for a module variable.
    pnModSetVar('AddressBook', 'abtitle', 'Zikula Address Book');
    pnModSetVar('AddressBook', 'itemsperpage', 30);
    pnModSetVar('AddressBook', 'globalprotect', 0);
    pnModSetVar('AddressBook', 'custom_tab', '');
    pnModSetVar('AddressBook', 'use_prefix', 0);
    pnModSetVar('AddressBook', 'use_img', 0);
    pnModSetVar('AddressBook', 'google_api_key', '');
    pnModSetVar('AddressBook', 'google_zoom', 15);
    pnModSetVar('AddressBook', 'special_chars_1', 'ÄÖÜäöüß');
    pnModSetVar('AddressBook', 'special_chars_2', 'AOUaous');
    pnModSetVar('AddressBook', 'enablecategorization', true);

    // Initialisation successful
    return true;
}

function AddressBook_upgrade($oldversion)
{

    $prefix = pnConfigGetVar('prefix');

    switch($oldversion) {
        case 1.0:
            $sql = "ALTER TABLE ".$prefix."_addressbook_address ADD adr_geodata VARCHAR( 180 ) NULL AFTER adr_country";
            if (!DBUtil::executeSQL($sql,-1,-1,false,true))
            return false;
            // Upgrade successfull
            pnModSetVar('Addressbook', 'google_api_key', '');
            pnModSetVar('Addressbook', 'google_zoom', 15);
            return AddressBook_upgrade(1.1);
        case 1.1:
            _addressbook_migratecategories();
            _addressbook_migrateprefixes();
            pnModDelVar('Addressbook', 'name_order');
            pnModDelVar('Addressbook', 'zipbeforecity');
            return AddressBook_upgrade(1.2);
        case 1.2:
            pnModDelVar('Addressbook', 'textareawidth');
            pnModDelVar('Addressbook', 'dateformat');
            pnModDelVar('Addressbook', 'numformat');
            _addressbook_upgradeto_1_3();
            return true;
        case 1.3:
            return true;
    }
}

function _addressbook_migratecategories()
{
    $dom = ZLanguage::getModuleDomain('AddressBook');
    $dbprefix = pnConfigGetVar('prefix');

    // pull old category values
    $sql = "SELECT cat_id, cat_name FROM {$dbprefix}_addressbook_categories";
    $result = DBUtil::executeSQL($sql);
    $categories = array();
    for (; !$result->EOF; $result->MoveNext()) {
        $categories[] = $result->fields;
    }

    // load necessary classes
    Loader::loadClass('CategoryUtil');
    Loader::loadClassFromModule('Categories', 'Category');
    Loader::loadClassFromModule('Categories', 'CategoryRegistry');

    // get the language file
    $lang = ZLanguage::getLanguageCode();

    // get the category path for which we're going to insert our place holder category
    $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules');
    $adrCat    = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/AddressBook');

    if (!$adrCat) {
        $cat = new PNCategory();
        $cat->setDataField('parent_id', $rootcat['id']);
        $cat->setDataField('name', 'AddressBook');
        $cat->setDataField('display_name', array($lang => __('AddressBook', $dom)));
        $cat->setDataField('display_desc', array($lang => __('Adress administration.', $dom)));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
    }

    // get the category path for which we're going to insert our upgraded News categories
    $adrCat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/AddressBook');

    // migrate our main categories
    foreach ($categories as $category) {
        $cat = new PNCategory();
        $cat->setDataField('parent_id', $adrCat['id']);
        $cat->setDataField('name', $category[1]);
        $cat->setDataField('is_leaf', 1);
        $cat->setDataField('display_name', array($lang => $category[1]));
        $cat->setDataField('display_desc', array($lang => $category[1]));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
        $catid = $cat->getDataField('id');

        $sql = "UPDATE {$dbprefix}_addressbook_address SET adr_catid = $catid WHERE adr_catid = $category[0]";
        if (!DBUtil::executeSQL($sql)) {
            return LogUtil::registerError(__('Error! Update attempt failed.', $dom));
        }
    }

    if ($adrCat) {
        // place category registry entry
        $registry = new PNCategoryRegistry();
        $registry->setDataField('modname', 'AddressBook');
        $registry->setDataField('table', 'addressbook_address');
        $registry->setDataField('property', 'AddressBook');
        $registry->setDataField('category_id', $adrCat['id']);
        $registry->insert();
    }

    // now drop the category table
    $sql = "DROP TABLE ".$dbprefix."_addressbook_categories";
    DBUtil::executeSQL($sql);

    return true;
}

function _addressbook_migrateprefixes()
{
    $dom = ZLanguage::getModuleDomain('AddressBook');
    $dbprefix = pnConfigGetVar('prefix');

    // pull old prefix values
    $sql = "SELECT pre_id, pre_name FROM {$dbprefix}_addressbook_prefixes";
    $result = DBUtil::executeSQL($sql);
    $prefixes = array();
    for (; !$result->EOF; $result->MoveNext()) {
        $prefixes[] = $result->fields;
    }

    // load necessary classes
    Loader::loadClass('CategoryUtil');
    Loader::loadClassFromModule('Categories', 'Category');
    Loader::loadClassFromModule('Categories', 'CategoryRegistry');

    // get the language file
    $lang = ZLanguage::getLanguageCode();

    // get the category path for which we're going to insert our place holder category
    $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/General');
    $foaCat  = CategoryUtil::getCategoryByPath('/__SYSTEM__/General/Form of address');

    if (!$foaCat) {
        $cat = new PNCategory();
        $cat->setDataField('parent_id', $rootcat['id']);
        $cat->setDataField('name', 'Form of address');
        $cat->setDataField('display_name', array($lang => __('Form of address', $dom)));
        $cat->setDataField('display_desc', array($lang => __('Form of address', $dom)));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
    }

    // get the category path for which we're going to insert our upgraded News categories
    $foaCat = CategoryUtil::getCategoryByPath('/__SYSTEM__/General/Form of address');

    // migrate our main categories
    foreach ($prefixes as $prefix) {
        $cat = new PNCategory();
        $cat->setDataField('parent_id', $foaCat['id']);
        $cat->setDataField('name', $prefix[1]);
        $cat->setDataField('is_leaf', 1);
        $cat->setDataField('display_name', array($lang => $prefix[1]));
        $cat->setDataField('display_desc', array($lang => $prefix[1]));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
        $catid = $cat->getDataField('id');

        $sql = "UPDATE {$dbprefix}_addressbook_address SET adr_prefix = $catid WHERE adr_prefix = $prefix[0]";
        if (!DBUtil::executeSQL($sql)) {
            return LogUtil::registerError(__('Error! Update attempt failed.', $dom));
        }
    }

    // now drop the prefixes table
    $sql = "DROP TABLE ".$dbprefix."_addressbook_prefixes";
    DBUtil::executeSQL($sql);

    return true;
}


function AddressBook_delete()
{

    DBUtil::dropTable('addressbook_address');
    DBUtil::dropTable('addressbook_labels');
    DBUtil::dropTable('addressbook_customfields');
    DBUtil::dropTable('addressbook_favourites');

    pnModDelVar ('AddressBook');

    // Delete entries from category registry
    if (!pnModDBInfoLoad('Categories')) {
        return false;
    }

    DBUtil::deleteWhere('categories_registry', "crg_modname='AddressBook'");

    // Deletion successful
    return true;
}

function _addressbook_createdefaultcategory()
{
    $dom = ZLanguage::getModuleDomain('AddressBook');
    // load necessary classes
    Loader::loadClass('CategoryUtil');
    Loader::loadClassFromModule('Categories', 'Category');
    Loader::loadClassFromModule('Categories', 'CategoryRegistry');

    // get the language file
    $lang = ZLanguage::getLanguageCode();

    // get the category path for which we're going to insert our place holder category
    $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules');
    $adrCat    = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/AddressBook');

    if (!$adrCat) {
        $cat = new PNCategory();
        $cat->setDataField('parent_id', $rootcat['id']);
        $cat->setDataField('name', 'AddressBook');
        $cat->setDataField('display_name', array($lang => __('AddressBook', $dom)));
        $cat->setDataField('display_desc', array($lang => __('Adress administration.', $dom)));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
    }

    // create the first 2 categories
    $adrCat    = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/AddressBook');
    $adrCat1    = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/AddressBook/Business');
    if (!$adrCat1) {
        $cat = new PNCategory();
        $cat->setDataField('parent_id', $adrCat['id']);
        $cat->setDataField('name', 'Business');
        $cat->setDataField('is_leaf', 1);
        $cat->setDataField('display_name', array($lang => __('Business', $dom)));
        $cat->setDataField('display_desc', array($lang => __('Business', $dom)));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
    }
    $adrCat2    = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/AddressBook/Personal');
    if (!$adrCat2) {
        $cat = new PNCategory();
        $cat->setDataField('parent_id', $adrCat['id']);
        $cat->setDataField('name', 'Personal');
        $cat->setDataField('is_leaf', 1);
        $cat->setDataField('display_name', array($lang => __('Personal', $dom)));
        $cat->setDataField('display_desc', array($lang => __('Personal', $dom)));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
    }


    if ($adrCat) {
        // place category registry entry for products (key == Products)
        $registry = new PNCategoryRegistry();
        $registry->setDataField('modname', 'AddressBook');
        $registry->setDataField('table', 'addressbook_address');
        $registry->setDataField('property', 'AddressBook');
        $registry->setDataField('category_id', $adrCat['id']);
        $registry->insert();
    }

    // now the old prefix field
    // get the category path for which we're going to insert our place holder form of address
    $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/General');
    $foaCat    = CategoryUtil::getCategoryByPath('/__SYSTEM__/General/Form of address');

    if (!$foaCat) {
        $cat = new PNCategory();
        $cat->setDataField('parent_id', $rootcat['id']);
        $cat->setDataField('name', 'Form of address');
        $cat->setDataField('display_name', array($lang => __('Form of address', $dom)));
        $cat->setDataField('display_desc', array($lang => __('Form of address', $dom)));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
    }

    // create the first 2 categories
    $foaCat    = CategoryUtil::getCategoryByPath('/__SYSTEM__/General/Form of address');
    $foaCat1    = CategoryUtil::getCategoryByPath('/__SYSTEM__/General/Form of address/Mr');
    if (!$foaCat1) {
        $cat = new PNCategory();
        $cat->setDataField('parent_id', $foaCat['id']);
        $cat->setDataField('name', 'Mr');
        $cat->setDataField('is_leaf', 1);
        $cat->setDataField('display_name', array($lang => __('Mr.', $dom)));
        $cat->setDataField('display_desc', array($lang => __('Mr.', $dom)));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
    }
    $foaCat2    = CategoryUtil::getCategoryByPath('/__SYSTEM__/General/Form of address/Mrs');
    if (!$foaCat2) {
        $cat = new PNCategory();
        $cat->setDataField('parent_id', $foaCat['id']);
        $cat->setDataField('name', 'Mrs');
        $cat->setDataField('is_leaf', 1);
        $cat->setDataField('display_name', array($lang => __('Mrs.', $dom)));
        $cat->setDataField('display_desc', array($lang => __('Mrs.', $dom)));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
    }

    return true;
}


/**
 * upgrade to 1.3
 *
 */
function _addressbook_upgradeto_1_3()
{        
    $oldvars = pnModGetVar('Addressbook');

    foreach ($oldvars as $varname => $oldvar)
    {
        pnModDelVar('AddressBook', $varname, $oldvar);
    }

    pnModSetVars('AddressBook', $oldvars);

    return true;
}