<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_Installer extends Zikula_AbstractInstaller
{

    /**
     * Init the module
     * @return true if init successful, false otherwise
     */
    public function install()
    {
        // create labels table
        if (!DBUtil::createTable('addressbook_labels')) {
            return false;
        }

        // insert default values
        $objArray = array();
        $objArray[] = array(
            'id'               => 1,
            'name'             => DataUtil::formatForStore($this->__('Work')));
        $objArray[] = array(
            'id'               => 2,
            'name'             => DataUtil::formatForStore($this->__('Fax')));
        $objArray[] = array(
            'id'               => 3,
            'name'             => DataUtil::formatForStore($this->__('Mobile')));
        $objArray[] = array(
            'id'               => 4,
            'name'             => DataUtil::formatForStore($this->__('Home')));
        $objArray[] = array(
            'id'               => 5,
            'name'             => DataUtil::formatForStore($this->__('Email')));
        $objArray[] = array(
            'id'               => 6,
            'name'             => DataUtil::formatForStore($this->__('URL')));
        $objArray[] = array(
            'id'               => 7,
            'name'             => DataUtil::formatForStore($this->__('Other')));

        DBUtil::insertObjectArray($objArray, 'addressbook_labels', 'id', true);

        // create custom field table
        if (!DBUtil::createTable('addressbook_customfields')) {
            return false;
        }

        // insert default values
        $objArray = array();
        $objArray[] = array(
            'id'               => 1,
            'name'             => DataUtil::formatForStore($this->__('Custom Label 1')),
            'type'             => 'varchar(60) default NULL',
            'position'         => 1);
        $objArray[] = array(
            'id'               => 2,
            'name'             => DataUtil::formatForStore($this->__('Custom Label 2')),
            'type'             => 'varchar(60) default NULL',
            'position'         => 2);
        $objArray[] = array(
            'id'               => 3,
            'name'             => DataUtil::formatForStore($this->__('Custom Label 3')),
            'type'             => 'varchar(60) default NULL',
            'position'         => 3);
        $objArray[] = array(
            'id'               => 4,
            'name'             => DataUtil::formatForStore($this->__('Custom Label 4')),
            'type'             => 'varchar(60) default NULL',
            'position'         => 4);

        DBUtil::insertObjectArray($objArray, 'addressbook_customfields', 'id', true);


        // create favourites table
        if (!DBUtil::createTable('addressbook_favourites')) {
            return false;
        }

        // finally create the address table
        if (!DBUtil::createTable('addressbook_address')) {
            return false;
        }

        // create our default categories
        if (!$this->_addressbook_createdefaultcategory()) {
            return LogUtil::registerError($this->__('Error! Creation attempt failed.'));
        }
       
        
        // Set up an initial value for a module variable.
        $this->setVar('abtitle', 'Zikula Address Book');
        $this->setVar('itemsperpage', 30);
        $this->setVar('globalprotect', 0);
        $this->setVar('custom_tab', '');
        $this->setVar('use_prefix', 0);
        $this->setVar('use_img', 0);
        // Not used in Google Maps Api v3 $this->setVar('google_api_key', '');
        $this->setVar('google_zoom', 15);
        $this->setVar('special_chars_1', 'ÄÖÜäöüß');
        $this->setVar('special_chars_2', 'AOUaous');
        $this->setVar('enablecategorization', true);

        // Register hooks
        HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());

        // Initialisation successful
        return true;
    }

    function upgrade($oldversion)
    {
        $prefix = System::getVar('prefix');
        $prefix = $prefix ? $prefix.'_' : '';

        switch($oldversion) {
            case '1.0':
                $sql = "ALTER TABLE ".$prefix."addressbook_address ADD adr_geodata VARCHAR( 180 ) NULL AFTER adr_country";
                if (!DBUtil::executeSQL($sql,-1,-1,false,true))
                return false;
                // Upgrade successfull
                // Not used in Google Maps Api v3 ModUtil::setVar('Addressbook', 'google_api_key', '');
                ModUtil::setVar('Addressbook', 'google_zoom', 15);
                return AddressBook_upgrade(1.1);
            case '1.1':
                $this->_addressbook_migratecategories();
                $this->_addressbook_migrateprefixes();
                ModUtil::delVar('Addressbook', 'name_order');
                ModUtil::delVar('Addressbook', 'zipbeforecity');
                return AddressBook_upgrade(1.2);
            case '1.2':
                ModUtil::delVar('Addressbook', 'textareawidth');
                ModUtil::delVar('Addressbook', 'dateformat');
                ModUtil::delVar('Addressbook', 'numformat');
                $this->_addressbook_upgradeto_1_3();
                return true;
            case '1.3':
            case '1.3.1':
                // drop table prefix
                if ($prefix) {
                    $connection = Doctrine_Manager::getInstance()->getConnection('default');
                    $sqlStatements = array();
                    $sqlStatements[] = 'RENAME TABLE ' . $prefix . 'addressbook_address' . " TO `addressbook_address`";
                    $sqlStatements[] = 'RENAME TABLE ' . $prefix . 'addressbook_customfields' . " TO `addressbook_customfields`";
                    $sqlStatements[] = 'RENAME TABLE ' . $prefix . 'addressbook_favourites' . " TO `addressbook_favourites`";
                    $sqlStatements[] = 'RENAME TABLE ' . $prefix . 'addressbook_labels' . " TO `addressbook_labels`";
                    foreach ($sqlStatements as $sql) {
                        $stmt = $connection->prepare($sql);
                        try {
                            $stmt->execute();
                        } catch (Exception $e) {
                        }   
                    }
                }
            case '1.3.2':
                // Register hooks
                $connection = Doctrine_Manager::getInstance()->getConnection('default');
                $sqlQueries = array();
                $sqlQueries[] = 'DELETE FROM `hook_area` WHERE `owner`="AddressBook"';
                $sqlQueries[] = 'DELETE FROM `hook_subscriber` WHERE `owner`="AddressBook"';
                foreach ($sqlQueries as $sql) {
                    $stmt = $connection->prepare($sql);
                    try {
                        $stmt->execute();
                    } catch (Exception $e) {
                    }   
                }
                HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
            case '1.3.3':
                return true;
        }
    }

    /**
     * Delete the module
     * @return true if init successful, false otherwise
     */
    public function uninstall()
    {
        DBUtil::dropTable('addressbook_address');
        DBUtil::dropTable('addressbook_labels');
        DBUtil::dropTable('addressbook_customfields');
        DBUtil::dropTable('addressbook_favourites');

        // delete module variables
        ModUtil::delVar('AddressBook');

        // Delete entries from category registry
        if (!ModUtil::dbInfoLoad('Categories')) {
            return false;
        }
        DBUtil::deleteWhere('categories_registry', "modname='AddressBook'");

        // Remove hooks
        HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());

        // deletion successful
        return true;
    }

    function _addressbook_migratecategories()
    {
        $dom = ZLanguage::getModuleDomain('AddressBook');
        $dbprefix = System::getVar('prefix');
        $dbprefix = $dbprefix ? $dbprefix.'_' : '';

        // pull old category values
        $sql = "SELECT cat_id, cat_name FROM {$dbprefix}addressbook_categories";
        $result = DBUtil::executeSQL($sql);
        $categories = array();
        for (; !$result->EOF; $result->MoveNext()) {
            $categories[] = $result->fields;
        }

        // get the language file
        $lang = ZLanguage::getLanguageCode();

        // get the category path for which we're going to insert our place holder category
        $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules');
        $adrCat    = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/AddressBook');

        if (!$adrCat) {
            $cat = new Categories_DBObject_Category();
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
            $cat = new Categories_DBObject_Category();
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

            $sql = "UPDATE {$dbprefix}addressbook_address SET adr_catid = $catid WHERE adr_catid = $category[0]";
            if (!DBUtil::executeSQL($sql)) {
                return LogUtil::registerError(__('Error! Update attempt failed.', $dom));
            }
        }

        if ($adrCat) {
            // place category registry entry
            $registry = new Categories_DBObject_Registry();
            $registry->setDataField('modname', 'AddressBook');
            $registry->setDataField('table', 'addressbook_address');
            $registry->setDataField('property', 'AddressBook');
            $registry->setDataField('category_id', $adrCat['id']);
            $registry->insert();
        }

        // now drop the category table
        $sql = "DROP TABLE ".$dbprefix."addressbook_categories";
        DBUtil::executeSQL($sql);

        return true;
    }

    function _addressbook_migrateprefixes()
    {
        $dom = ZLanguage::getModuleDomain('AddressBook');
        $dbprefix = System::getVar('prefix');
        $dbprefix = $dbprefix ? $dbprefix.'_' : '';

        // pull old prefix values
        $sql = "SELECT pre_id, pre_name FROM {$dbprefix}addressbook_prefixes";
        $result = DBUtil::executeSQL($sql);
        $prefixes = array();
        for (; !$result->EOF; $result->MoveNext()) {
            $prefixes[] = $result->fields;
        }

        // get the language file
        $lang = ZLanguage::getLanguageCode();

        // get the category path for which we're going to insert our place holder category
        $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/General');
        $foaCat  = CategoryUtil::getCategoryByPath('/__SYSTEM__/General/Form of address');

        if (!$foaCat) {
            $cat = new Categories_DBObject_Category();
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
            $cat = new Categories_DBObject_Category();
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

            $sql = "UPDATE {$dbprefix}addressbook_address SET adr_prefix = $catid WHERE adr_prefix = $prefix[0]";
            if (!DBUtil::executeSQL($sql)) {
                return LogUtil::registerError(__('Error! Update attempt failed.', $dom));
            }
        }

        // now drop the prefixes table
        $sql = "DROP TABLE ".$dbprefix."addressbook_prefixes";
        DBUtil::executeSQL($sql);

        return true;
    }

    /**
     * upgrade to 1.3
     *
     */
    function _addressbook_upgradeto_1_3()
    {        
        $oldvars = ModUtil::getVar('Addressbook');

        foreach ($oldvars as $varname => $oldvar)
        {
            ModUtil::delVar('AddressBook', $varname, $oldvar);
        }

        ModUtil::setVars('AddressBook', $oldvars);

        return true;
    }

    function _addressbook_createdefaultcategory()
    {
        $dom = ZLanguage::getModuleDomain('AddressBook');

        // get the language file
        $lang = ZLanguage::getLanguageCode();

        // get the category path for which we're going to insert our place holder category
        $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules');
        $adrCat    = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/AddressBook');

        if (!$adrCat) {
            $cat = new Categories_DBObject_Category();
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
            $cat = new Categories_DBObject_Category();
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
            $cat = new Categories_DBObject_Category();
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
            $registry = new Categories_DBObject_Registry();
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
            $cat = new Categories_DBObject_Category();
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
            $cat = new Categories_DBObject_Category();
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
            $cat = new Categories_DBObject_Category();
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
}