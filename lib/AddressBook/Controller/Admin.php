<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_Controller_Admin extends Zikula_AbstractController
{
    /**
     * Main administration function
     */
    public function main()
    {
        return $this->modifyconfig();
    }

    /**
     * Modify the settings
     */
    public function modifyconfig()
    {
        // Security check
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
            return LogUtil::registerPermissionError();
        }
        $modvars = ModUtil::getVar('AddressBook');
        
        // Default values
        if (!isset($modvars['allowprivate'])) {
            $modvars['allowprivate'] = 0;
        }
        if (!isset($modvars['abtitle'])) {
            $modvars['abtitle'] = 'Address Book';
        }
        if (!isset($modvars['custom_tab'])) {
            $modvars['custom_tab'] = '';
        }

        // Multilingual items
        $languages = ZLanguage::getInstalledLanguages();
        foreach ($languages as $language) {
            if (!isset($modvars['abtitle_'.$language])) {
                $modvars['abtitle_'.$language] = $modvars['abtitle'];
            }
            if (!isset($modvars['abmetatitle_'.$language])) {
                $modvars['abmetatitle_'.$language] = $modvars['abmetatitle'];
            }
            if (!isset($modvars['abmetadescription_'.$language])) {
                $modvars['abmetadescription_'.$language] = $modvars['abmetadescription'];
            }
            if (!isset($modvars['abmetakeyword_'.$language])) {
                $modvars['abmetakeyword_'.$language] = $modvars['abmetakeyword'];
            }
            if (!isset($modvars['custom_tab_'.$language])) {
                $modvars['custom_tab_'.$language] = $modvars['custom_tab'];
            }
        }

        // Create output object
        $this->view->assign('preferences', $modvars);
        $this->view->assign('languages', $languages);

        return $this->view->fetch('admin_modifyconfig.tpl');
    }

    /**
     * Update Config
     */
    public function updateconfig()
    {
        // Confirm the forms authorisation key
        $this->checkCsrfToken();
        // Security check
		 $this->throwForbiddenUnless(SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN));

        // retrieve the associative preferences array
        $prefs = FormUtil::getPassedValue('preferences', null, 'POST');

        $languages = ZLanguage::getInstalledLanguages();

        // now for each perference entry, set the appropriate module variable
        foreach ($languages as $language) {
            ModUtil::setVar('AddressBook', 'abtitle_'.$language, (isset($prefs['abtitle_'.$language]) ? $prefs['abtitle_'.$language] : ''));
            ModUtil::setVar('AddressBook', 'abmetatitle_'.$language, (isset($prefs['abmetatitle_'.$language]) ? $prefs['abmetatitle_'.$language] : ''));
            ModUtil::setVar('AddressBook', 'abmetadescription_'.$language, (isset($prefs['abmetadescription_'.$language]) ? $prefs['abmetadescription_'.$language] : ''));
            ModUtil::setVar('AddressBook', 'abmetakeyword_'.$language, (isset($prefs['abmetakeyword_'.$language]) ? $prefs['abmetakeyword_'.$language] : ''));
            ModUtil::setVar('AddressBook', 'custom_tab_'.$language, (isset($prefs['custom_tab_'.$language]) ? $prefs['custom_tab_'.$language] : ''));
        }
        ModUtil::setVar('AddressBook', 'globalprotect', (isset($prefs['globalprotect']) ? $prefs['globalprotect'] : 0));
        ModUtil::setVar('AddressBook', 'allowprivate', (isset($prefs['allowprivate']) ? $prefs['allowprivate'] : 0));
        ModUtil::setVar('AddressBook', 'use_prefix', (isset($prefs['use_prefix']) ? $prefs['use_prefix'] : 0));
        ModUtil::setVar('AddressBook', 'use_img', (isset($prefs['use_img']) ? $prefs['use_img'] : 0));
        ModUtil::setVar('AddressBook', 'images_dir', (isset($prefs['images_dir']) ? $prefs['images_dir'] : 'userdata/Addressbook'));
        ModUtil::setVar('AddressBook', 'images_manager', (isset($prefs['images_manager']) ? $prefs['images_manager'] : 'kcfinder'));
        // Not used in Google Maps Api v3 ModUtil::setVar('AddressBook', 'google_api_key', (isset($prefs['google_api_key']) ? $prefs['google_api_key'] : ''));
        ModUtil::setVar('AddressBook', 'google_zoom', (isset($prefs['google_zoom']) ? $prefs['google_zoom'] : 15));
        ModUtil::setVar('AddressBook', 'itemsperpage', ($prefs['itemsperpage']>1 ? $prefs['itemsperpage'] : 30));
        ModUtil::setVar('AddressBook', 'addressbooktype', (isset($prefs['addressbooktype']) ? $prefs['addressbooktype'] : 1));
        ModUtil::setVar('AddressBook', 'showabcfilter', (isset($prefs['showabcfilter']) ? $prefs['showabcfilter'] : 0));

        // redirect back to to main admin page
        LogUtil::registerStatus ($this->__('Done! Configuration saved.'));
        return System::redirect(ModUtil::url('AddressBook', 'admin', 'main'));
    }
    
    /**
     * Modify the categories, labels, custom fields
     */
    public function edit()
    {
        // Security check
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
            return LogUtil::registerPermissionError();
        }

        $ot = FormUtil::getPassedValue('ot', 'categories', 'GET');
        $id = (int)FormUtil::getPassedValue('id', 0, 'GET');

        $class = 'AddressBook_DBObject_'. ucfirst($ot);
        if (!class_exists($class)) {
            return z_exit($this->__f('Error! Unable to load class [%s]', $ot));
        }

        $data = array();
        if ($id) {
            $object = new $class();
            $data = $object->get($id);
        } else {
            $data['id'] = 0;
        }

        $this->view->assign($ot, $data);

        if ($ot=="customfield") {
            $new_position = DBUtil::selectFieldMax('addressbook_customfields', 'position') + 1;
            $this->view->assign('new_position', $new_position);
        }

        return $this->view->fetch('admin_' . $ot . '_edit.tpl');
    }

    /**
     * Update
     */
    public function update()
    {
        // Confirm the forms authorisation key
        $this->checkCsrfToken();
        // Security check
		 $this->throwForbiddenUnless(SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN));

        $ot =  FormUtil::getPassedValue('ot', 'categories', 'POST');
        $url = ModUtil::url('AddressBook', 'admin', 'view', array('ot'=>$ot));

        if (FormUtil::getPassedValue('button_cancel', null, 'POST')) {
            LogUtil::registerStatus ('Operation cancelled.');
            return System::redirect($url);
        }

        $class = 'AddressBook_DBObject_'. ucfirst($ot);
        if (!class_exists($class)) {
            return z_exit(__f('Error! Unable to load class [%s]', $ot));
        }

        $object = new $class();
        $object->getDataFromInput();
        $object->save();

        if ($ot == "customfield")
        {
            $obj = $object->getDataFromInput();
            if ($obj['type']=='dropdown')
            $obj['type']='text';
            if (isset($obj['id']) && $obj['id']) {
                $sql="ALTER TABLE addressbook_address CHANGE adr_custom_".$obj['id']." adr_custom_".$obj['id']." ".$obj['type'];
            } else {
                $cus_id = DBUtil::getInsertID('addressbook_customfields');
                $sql="ALTER TABLE addressbook_address ADD adr_custom_".$cus_id." ".$obj['type'];
            }
            DBUtil::executeSQL($sql,-1,-1,false,true);
        }

        return System::redirect($url);
    }

    /**
     * View the categories, labels, custom fields
     */
    public function view()
    {
        // Security check
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
            return LogUtil::registerPermissionError();
        }

        $ot   = FormUtil::getPassedValue('ot', 'categories', 'GET');
        $sort = FormUtil::getPassedValue('sort', 'id', 'GET');
        $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
        $pagesize = ModUtil::getVar('AddressBook', 'itemsperpage', 25);

        if ($ot == "customfield")
        $sort = "cus_pos";

        $where = '';
        $class = 'AddressBook_DBObject_'. ucfirst($ot) . 'Array';
        if (!class_exists($class)) {
            return z_exit($this->__f('Error! Unable to load class [%s]', $ot));
        }

        $objectArray = new $class();
        $data = $objectArray->get($where, $sort, $startnum-1, $pagesize);
        $objcount = $objectArray->getCount($where);

        $this->view->assign('objectArray', $data);

        // Assign the information required to create the pager
        $this->view->assign('pager', array('numitems'     => $objcount,
                                         'itemsperpage' => $pagesize));

        return $this->view->fetch('admin_' . $ot . '_view.tpl');
    }

    /**
     * Delete categories, labels, custom fields
     */
    public function delete()
    {
        // Security check
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
            return LogUtil::registerPermissionError();
        }

        $ot = FormUtil::getPassedValue('ot', 'categories', 'GETPOST');
        $id = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');
        $confirmation = (int)FormUtil::getPassedValue('confirmation', false);

        $url = ModUtil::url('AddressBook', 'admin', 'view', array('ot'=>$ot));

        // Check for existence
        $class = 'AddressBook_DBObject_'. ucfirst($ot);
        if (!class_exists($class)) {
            return z_exit($this->__f('Error! Unable to load class [%s]', $ot));
        }

        $object = new $class();
        $data = $object->get($id);
        if (!$data) {
            LogUtil::registerError($this->__('Error! No such item found.'), 404);
            return System::redirect($url);
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            $this->view->assign('id', $id);
            $this->view->assign('ot', $ot);
            $this->view->assign('object', $data);
            return $this->view->fetch('admin_delete.tpl');
        }

        // If we get here it means that the user has confirmed the action
        // Confirm the forms authorisation key
        $this->checkCsrfToken();

        if (ModUtil::apiFunc('AddressBook', 'admin', 'delete', array('id' => $id, 'ot' => $ot))) {
            // Success
            LogUtil::registerStatus($this->__('Done! Item deleted.'));
        }

        return System::redirect($url);
    }
}
