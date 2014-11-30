<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_Controller_User extends Zikula_AbstractController {

    public function main()
    {
        return $this->view();
    }
    
    function edit()
    {
        $ot         = FormUtil::getPassedValue('ot', 'address', 'GET');
        $duplicate  = FormUtil::getPassedValue('duplicate', 0, 'GET');
        $id         = (int) FormUtil::getPassedValue('id', 0, 'GET');
        $startnum   = FormUtil::getPassedValue('startnum', 1, 'GET');
        $letter     = FormUtil::getPassedValue('letter', 0);
        $sort       = FormUtil::getPassedValue('sort', ModUtil::getVar('AddressBook', 'addressbooktype')==1 ? 'sortname ASC' : 'sortcompany ASC');

        $search     = FormUtil::getPassedValue('search', 0);
        $category   = FormUtil::getPassedValue('category', 0);
        $returnid   = FormUtil::getPassedValue('returnid', 0, 'GET');
        $private    = FormUtil::getPassedValue('private', 0);

        // Get user id
        if (UserUtil::isLoggedIn()) {
            $user_id = UserUtil::getVar('uid');
        } else {
            $user_id = 0;
        }

        $data = array();
        if ($id) {
            $object = new AddressBook_DBObject_Address();
            $data = $object->get($id);
            if ($duplicate) {
                $data['id'] = 0;
                LogUtil::registerStatus ($this->__('Be careful. This is a duplicate!'));
            }
        }
        else {
            $data = FormUtil::getFailedValidationObjects('address');
            $data['id'] = 0;

        }

        // security check
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_EDIT) || $user_id == $data['user_id'])) {
            return LogUtil::registerPermissionError();
        }

        // get the custom fields
        $cus_where = "";
        $cus_sort = "cus_pos ASC";
        $cus_Array = new AddressBook_DBObject_CustomfieldArray();
        $customfields = $cus_Array->get ($cus_where, $cus_sort);

        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('AddressBook', 'addressbook_address');

        $this->view->setCaching(false); // not suitable for cachin
        $this->view->assign('catregistry',  $catregistry);
        $this->view->assign('address',      $data);
        $this->view->assign('ot',           $ot);
        $this->view->assign('user_id',      $user_id);
        $this->view->assign('customfields', $customfields);
        $this->view->assign('startnum',     $startnum);
        $this->view->assign('letter',       $letter);
        $this->view->assign('category',     $category);
        $this->view->assign('private',      $private);
        $this->view->assign('sort',         $sort);
        $this->view->assign('search',       $search);
        $this->view->assign('returnid',     $returnid);
        $this->view->assign('preferences',  ModUtil::getVar('AddressBook'));

        return $this->view->fetch('user_edit.tpl');
    }

    function edititem()
    {
        // Confirm the forms authorisation key
        $this->checkCsrfToken();

        // get passed values
        $ot         = FormUtil::getPassedValue('ot', 'address', 'POST');
        $startnum   = FormUtil::getPassedValue('startnum', 1, 'GET');
        $letter     = FormUtil::getPassedValue('letter', 0);
        $sort       = FormUtil::getPassedValue('sort', ModUtil::getVar('AddressBook', 'addressbooktype')==1 ? 'sortname ASC' : 'sortcompany ASC');
        $search     = FormUtil::getPassedValue('search', 0);
        $category   = FormUtil::getPassedValue('category', 0);
        $private    = FormUtil::getPassedValue('private', 0);
        $returnid   = FormUtil::getPassedValue('returnid', 0, 'POST');

        // build standard return url
        if(!empty($returnid)) {
            $url = ModUtil::url('AddressBook', 'user', 'display', array('id'=>$returnid,
                                                               'ot'=>$ot,
                                                               'startnum'=>$startnum,
                                                               'letter'=>$letter,
                                                               'sort'=>$sort,
                                                               'search'=>$search,
                                                               'category'=>$category,
                                                               'private'=>$private));
        } else {
            $url = ModUtil::url('AddressBook', 'user', 'view', array('ot'=>$ot,
                                                               'startnum'=>$startnum,
                                                               'letter'=>$letter,
                                                               'sort'=>$sort,
                                                               'search'=>$search,
                                                               'category'=>$category,
                                                               'private'=>$private));
        }

        $object = new AddressBook_DBObject_Address();
        //$data =& $object->getDataFromInput();
        $data = $object->getDataFromInput();

        // permission check
        if (UserUtil::isLoggedIn()) {
            $user_id = UserUtil::getVar('uid');
        } else {
            $user_id = 0;
        }
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_EDIT) || ($user_id >0 && $user_id == $data['user_id']))) {
            return LogUtil::registerPermissionError();
        }

        // validation
        if (!$object->validate()) {
            return System::redirect(ModUtil::url('AddressBook', 'user', 'edit'));
        }

        // check for duplication request and return to the form
        if (FormUtil::getPassedValue('btn_duplicate', null, 'POST')) {
            $url = ModUtil::url('AddressBook', 'user', 'edit', array('ot'=>$ot,
                                                             'id' => $data['id'],
                                                             'duplicate' => 1,
                                                             'startnum'=>$startnum,
                                                             'letter'=>$letter,
                                                             'sort'=>$sort,
                                                             'search'=>$search,
                                                             'category'=>$category,
                                                             'private'=>$private));

            return System::redirect($url);
        }

        // check for company update - part 1: get the old data
        if (isset($data['id']) && $data['id']) {
            $oldObject = DBUtil::selectObjectByID('addressbook_address', $data['id']);
            if (($oldObject['company'])&&(($oldObject['company']!=$data['company'])||($oldObject['address1']!=$data['address1'])||($oldObject['address2']!=$data['address2'])||($oldObject['zip']!=$data['zip'])||($oldObject['city']!=$data['city'])||($oldObject['state']!=$data['state'])||($oldObject['country']!=$data['country'])))
            {
                $companyHasChanged = true;
                $url = ModUtil::url('AddressBook', 'user', 'change_company', array('ot'=>$ot,
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
        LogUtil::registerStatus ($this->__('Done! The address was saved.'));

        // clear respective cache
        ModUtil::apiFunc('AddressBook', 'user', 'clearItemCache', $data);

        // clear the the session from FailedObjects
        FormUtil::clearValidationFailedObjects('address');

        // check for save and duplicate request and return to the form
        if (FormUtil::getPassedValue('btn_save_duplicate', null, 'POST')) {
            $url = ModUtil::url('AddressBook', 'user', 'edit', array('ot'=>$ot,
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
        return System::redirect($url);
    }

    function display()
    {
        // security check
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ))) {
            return LogUtil::registerPermissionError();
        }

        $ot = FormUtil::getPassedValue('ot', 'address', 'GET');
        $id = (int)FormUtil::getPassedValue('id', 0, 'GET');
        $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
        $letter   = FormUtil::getPassedValue('letter', 0);
        $sort     = FormUtil::getPassedValue('sort', ModUtil::getVar('AddressBook', 'addressbooktype')==1 ? 'sortname ASC' : 'sortcompany ASC');
        $search   = FormUtil::getPassedValue('search', 0);
        $category = FormUtil::getPassedValue('category', 0);
        $private  = FormUtil::getPassedValue('private', 0);

        if (!$id) {
            return z_exit($this->__f('Error! Invalid id [%s] received.', $id));
        }

        // Get user id
        if (UserUtil::isLoggedIn()) {
            $user_id = UserUtil::getVar('uid');
        } else {
            $user_id = 0;
        }
        
        $this->view->setCacheId('display|id_'.$id . '|uid_'.$user_id);
        $template = 'user_display.tpl';
        if ($this->view->is_cached($template)) {
            return $this->view->fetch($template);
        }
        
        $object = new AddressBook_DBObject_Address();
        $data = $object->get($id);

        // get the custom fields
        $cus_where = "";
        $cus_sort = "cus_pos ASC";
        $cus_Array = new AddressBook_DBObject_CustomfieldArray();
        $customfields = $cus_Array->get ($cus_where, $cus_sort);

        DBUtil::incrementObjectFieldByID('addressbook_address', 'counter', $id, 'id'); // count clicks

        $this->view->assign('address', $data);
        $this->view->assign('customfields', $customfields);
        $this->view->assign('user_id', $user_id);
        $this->view->assign('ot', $ot);

        // assign the criteria from the view modus
        $this->view->assign('startnum',   $startnum);
        $this->view->assign('letter',     $letter);
        $this->view->assign('category',   $category);
        $this->view->assign('private',    $private);
        $this->view->assign('sort',       $sort);
        $this->view->assign('search',     $search);

        $where = "fav_adr_id=$id AND fav_user_id=$user_id";
        $fav = new AddressBook_DBObject_FavouriteArray();
        $favData  = $fav->getWhere($where);

        $this->view->assign('isFavourite', $favData ? 1 : 0);

        unset ($fav);
        unset ($favData);
        unset ($where);

        // Google Maps
        $this->view->assign('preferences', ModUtil::getVar('AddressBook'));
        $this->view->assign('lang',        ZLanguage::getLanguageCode());

        return $this->view->fetch($template);
    }

    function view()
    {
        // Private Address Book mode, for users only - commented, because access for registered/unregistered users can be set in site permissions!
        if ((!UserUtil::isLoggedIn()) && (ModUtil::getVar('AddressBook', 'globalprotect')==1)) {
            return LogUtil::registerError($this->__f('This website require it\'s users to be registered to use the address book.<br />Register for free <a href="%1$s">here</a>, or <a href=\"%1$s\">log in</a> if you are already registered.', array(ModUtil::url('Users', 'user', 'view'))));
        }

        // security check
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ))) {
            return LogUtil::registerPermissionError();
        }

        $ot   = FormUtil::getPassedValue('ot', 'address', 'GET');
        $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
        $pagesize = ModUtil::getVar('AddressBook', 'itemsperpage', 30);
        $letter   = FormUtil::getPassedValue('letter', 0);
        $sort     = FormUtil::getPassedValue('sort', ModUtil::getVar('AddressBook', 'addressbooktype')==1 ? 'sortname ASC' : 'sortcompany ASC');
        $search   = FormUtil::getPassedValue('search', 0);
        $category = FormUtil::getPassedValue('category', 0);
        $private  = FormUtil::getPassedValue('private', 0);

        if (empty($sort)) {
            if (ModUtil::getVar('AddressBook', 'addressbooktype')==1) {
                $sort = "sortname ASC";
            } else {
                $sort = "sortcompany ASC";
            }
        } else {
            if (ModUtil::getVar('AddressBook', 'addressbooktype')==1) {
                if (strpos($sort, 'sortname') === false) {
                    $sort .= ", sortname ASC";
                }
            } else {
                if (strpos($sort, 'sortcompany') === false) {
                    $sort .= ", sortcompany ASC";
                }
            }
        }
        if ($ot == 'favourite') {
            $sort = '';
        }

        // Get user id
        if (UserUtil::isLoggedIn()) {
            $user_id = UserUtil::getVar('uid');
        } else {
            $user_id = 0;
        }

        $this->view->setCacheId('view|cat_'.$category . 
            '|ot'.$ot.'_stnum'.$startnum.'_itpg'.$pagesize.'_let'.$letter.'_sort'.$sort.'_prv'.$private.'_srch'.$search.
            '|uid_'.$user_id);
        $template = 'user_view.tpl';
        if ($this->view->is_cached($template)) {
            return $this->view->fetch($template);
        }

        // build the where clause
        $where = '';

        $ztable = DBUtil::getTables();
        $address_table = $ztable['addressbook_address'];
        $address_column = &$ztable['addressbook_address_column'];

        // admin always sees all records but favourites
        if (SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)) {
            if ($ot == "favourite")
            $where .= "(fav_user_id=$user_id)";
            else
            $where .= "($address_column[user_id] IS NOT NULL)";
        } else {
            // global protect - users see only their own records (admin sees all)
            if (((ModUtil::getVar('AddressBook', 'globalprotect'))==1) && (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)))) {
                if ($ot == "favourite")
                $where = "(fav_user_id=$user_id)";
                else
                $where = "($address_column[user_id]=$user_id)";
            } else {
                // if private = 1, show only private records
                if ($private==1) {
                    if ($ot == "favourite")
                    $where = "(fav_user_id=$user_id AND $address_column[private] = 1)";
                    else
                    $where = "($address_column[user_id]=$user_id AND $address_column[private] = 1)";
                } else {
                    // if private = 0, show all records
                    if ($ot == "favourite")
                    $where = "(fav_user_id=$user_id)";
                    else
                    $where = "(($address_column[private] = 0) OR ($address_column[user_id]=$user_id AND $address_column[private] = 1))";
                }
            }
        }

        // typecasting / security
        if (!is_string($letter))
        $letter = false;

        // filter for abc pager
        if (($letter) && ($ot=="address")) {
            if (($sort) && (strpos($sort,'ompany')))
            $where .= " AND $address_column[sortcompany] LIKE '" . DataUtil::formatForStore($letter) . "%'";
            else
            $where .= " AND $address_column[sortname] LIKE '" . DataUtil::formatForStore($letter) . "%'";
        }

        // filter for abc pager in favourite mode
        if (($letter) && ($ot=="favourite")) {
            if (($sort) && (strpos($sort,'ompany')))
            $fav_where = "$address_column[sortcompany] LIKE '" . DataUtil::formatForStore($letter) . "%'";
            else
            $fav_where = "$address_column[sortname] LIKE '" . DataUtil::formatForStore($letter) . "%'";

            $fav_data = DBUtil::selectFieldArray( "addressbook_address", "id", $fav_where);
            if (count($fav_data)>0) {
                $fav_list = implode(',',$fav_data);
                $where .= " AND fav_adr_id IN ($fav_list)";
            } else {
                // dummy, no records were found
                $where .= " AND fav_adr_id = 0";
            }
        }

        // filter for category
        if ($category) {
            $where .= " AND $address_column[cat_id] = $category";
        }

        // Inactive status is visible to admins only
        if ($ot=="address" && !(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
            $where .= " AND $address_column[status] = 1";
        }
        // Filter by language
        if ($ot=="address") {
            $where .= " AND ($address_column[language] = '' OR $address_column[language] = '".DataUtil::formatForStore(ZLanguage::getLanguageCode())."')";
        }

        // filter for search
        if ($search) {
            LogUtil::registerStatus ($this->__('Current search term: ').$search);

            // typecasting / security
            if (is_string($search))
            {
                $where .= " AND ($address_column[lname] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[fname] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[company] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[title] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[city] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[address1] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[address2] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[zip] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[country] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[state] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[note] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[contact_1] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[contact_2] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[contact_3] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[contact_4] LIKE '%".DataUtil::formatForStore($search)."%'
                        OR $address_column[contact_5] LIKE '%".DataUtil::formatForStore($search)."%')";
            }
            // and now the custom fields
            $cus_where = "";
            $cus_sort = "cus_pos ASC";
            $cus_Array = new AddressBook_DBObject_CustomfieldArray();
            $customfields = $cus_Array->get ($cus_where, $cus_sort);
            foreach($customfields as $cus) {
                if ((!strstr($cus['type'],'tinyint')) && (!strstr($cus['type'],'smallint')))
                {
                    $the_name = 'adr_custom_'.$cus['id'];
                    if ((strstr($cus['type'],'varchar')) || (strstr($cus['type'],'text')) || (strstr($cus['type'],'dropdown')) )
                    {
                        // typecasting / security
                        if (is_string($search))
                        {
                            $where .= " OR $the_name LIKE '%".DataUtil::formatForStore($search)."%'";
                        }
                    }
                    if (strstr($cus['type'],'int'))
                    {
                        // typecasting / security
                        if (is_int($search))
                        {
                            $where .= " OR $the_name = $search";
                        }
                    }
                    if (strstr($cus['type'],'decimal'))
                    {
                        // typecasting / security
                        if (is_numeric($search))
                        {
                            $where .= " OR $the_name = $search";
                        }
                    }
                }
            }
        }

        // get the result
        $class = 'AddressBook_DBObject_'. ucfirst($ot) . 'Array';
        if (!class_exists($class)) {
            return z_exit($this->__f('Error! Unable to load class [%s]', $ot));
        }

        $objectArray = new $class();
            //print_r($sort);
        $data = $objectArray->get ($where, $sort, $startnum-1, $pagesize);
        $objcount = $objectArray->getCount ($where);

        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('AddressBook', 'addressbook_address');

        $this->view->assign('catregistry',   $catregistry);
        $this->view->assign('ot',            $ot);
        $this->view->assign('objectArray',   $data);
        $this->view->assign('startnum',      $startnum);
        $this->view->assign('letter',        $letter);
        $this->view->assign('category',      $category);
        $this->view->assign('private',       $private);
        $this->view->assign('search',        $search);
        $this->view->assign('globalprotect', ModUtil::getVar('AddressBook', 'globalprotect'));
        $this->view->assign('preferences',   ModUtil::getVar('AddressBook'));
        $this->view->assign('pager',         array('numitems'     => $objcount,
                                                 'itemsperpage' => $pagesize));

        return $this->view->fetch($template);
    }

    function delete()
    {
        // check permissions
        if (!SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_DELETE)){
            return LogUtil::registerPermissionError();
        }

        // get arguments
        $ot = FormUtil::getPassedValue('ot', 'address', 'GETPOST');
        $id = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');
        $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
        $letter   = FormUtil::getPassedValue('letter', 0);
        $sort     = FormUtil::getPassedValue('sort', ModUtil::getVar('AddressBook', 'addressbooktype')==1 ? 'sortname ASC' : 'sortcompany ASC');
        $search   = FormUtil::getPassedValue('search', 0);
        $category = FormUtil::getPassedValue('category', 0);
        $private  = FormUtil::getPassedValue('private', 0);

        // create the output object
        $this->view->assign('id', $id);
        $this->view->assign('ot', $ot);

        // assign the criteria from the view modus
        $this->view->assign('startnum',   $startnum);
        $this->view->assign('letter',     $letter);
        $this->view->assign('category',   $category);
        $this->view->assign('private',    $private);
        $this->view->assign('sort',       $sort);
        $this->view->assign('search',     $search);

        // return output
        return $this->view->fetch('user_delete.tpl');
    }

    function deleteProcess()
    {
        // Confirm the forms authorisation key
        $this->checkCsrfToken();

        // security check
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_DELETE))) {
            return LogUtil::registerPermissionError();
        }

        $ot = FormUtil::getPassedValue('ot', 'address', 'GETPOST');
        $id = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');

        $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
        $letter   = FormUtil::getPassedValue('letter', 0);
        $sort     = FormUtil::getPassedValue('sort', ModUtil::getVar('AddressBook', 'addressbooktype')==1 ? 'sortname ASC' : 'sortcompany ASC');
        $search   = FormUtil::getPassedValue('search', 0);
        $category = FormUtil::getPassedValue('category', 0);
        $private  = FormUtil::getPassedValue('private', 0);

        $url = ModUtil::url('AddressBook', 'user', 'view', array('ot'=>$ot,
                                                             'startnum'=>$startnum,
                                                             'letter'=>$letter,
                                                             'sort'=>$sort,
                                                             'search'=>$search,
                                                             'category'=>$category,
                                                             'private'=>$private));

        $object = new AddressBook_DBObject_Address();
        $data = $object->get($id);

        if (!$data) {
            LogUtil::registerError($this->__('Error! The deletion of this address failed.'));
            return System::redirect($url);
        }

        $object->delete();
        LogUtil::registerStatus($this->__('Done! The deletion of this address was successful.'));

        // clear respective cache
        ModUtil::apiFunc('AddressBook', 'user', 'clearItemCache', $data);

        return System::redirect($url);
    }

    function change_company()
    {
        // check permissions
        /*if (!SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_EDIT)){
            return LogUtil::registerPermissionError();
        }*/

        // get arguments
        $ot = FormUtil::getPassedValue('ot', 'address', 'GETPOST');
        $id = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');
        $oldvalue = (int)FormUtil::getPassedValue('oldvalue', 0, 'GETPOST');
        $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
        $letter   = FormUtil::getPassedValue('letter', 0);
        $sort     = FormUtil::getPassedValue('sort', ModUtil::getVar('AddressBook', 'addressbooktype')==1 ? 'sortname ASC' : 'sortcompany ASC');
        $search   = FormUtil::getPassedValue('search', 0);
        $category = FormUtil::getPassedValue('category', 0);
        $private  = FormUtil::getPassedValue('private', 0);

        // create the output object
        $this->view->assign('id', $id);
        $this->view->assign('ot', $ot);

        // assign the criteria from the view modus
        $this->view->assign('startnum',   $startnum);
        $this->view->assign('letter',     $letter);
        $this->view->assign('category',   $category);
        $this->view->assign('private',    $private);
        $this->view->assign('sort',       $sort);
        $this->view->assign('search',     $search);
        $this->view->assign('oldvalue',   $oldvalue);

        // return output
        return $this->view->fetch('user_change_company.tpl');
    }

    function update_company()
    {
        // Confirm the forms authorisation key
        $this->checkCsrfToken();

        $ot = FormUtil::getPassedValue('ot', 'address', 'GETPOST');
        $id = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');
        $oldvalue = (int)FormUtil::getPassedValue('oldvalue', 0, 'GETPOST');
        $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
        $letter   = FormUtil::getPassedValue('letter', 0);
        $sort     = FormUtil::getPassedValue('sort', ModUtil::getVar('AddressBook', 'addressbooktype')==1 ? 'sortname ASC' : 'sortcompany ASC');
        $search   = FormUtil::getPassedValue('search', 0);
        $category = FormUtil::getPassedValue('category', 0);
        $private  = FormUtil::getPassedValue('private', 0);

        $url = ModUtil::url('AddressBook', 'user', 'view', array('ot'=>$ot,
                                                             'startnum'=>$startnum,
                                                             'letter'=>$letter,
                                                             'sort'=>$sort,
                                                             'search'=>$search,
                                                             'category'=>$category,
                                                             'private'=>$private));

        $object = new AddressBook_DBObject_Address();
        $data = $object->get($id);

        // security check
        // Get user id
        if (UserUtil::isLoggedIn()) {
            $user_id = UserUtil::getVar('uid');
        } else {
            $user_id = 0;
        }
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_EDIT) || $user_id == $data['user_id'])) {
            return LogUtil::registerPermissionError();
        }

        $obj = array('company'  => $data['company'],
                     'address1' => $data['address1'],
                     'address2' => $data['address2'],
                     'zip'      => $data['zip'],
                     'city'     => $data['city'],
                     'state'    => $data['state'],
                     'country'  => $data['country']);

        $res = DBUtil::updateObject($obj, 'addressbook_address', '', 'company');

        if (!$res) {
            LogUtil::registerError ($this->__('Error! Company update failed.'));
            return System::redirect($url);
        }

        // clear respective cache
        ModUtil::apiFunc('AddressBook', 'user', 'clearItemCache', $data);

        LogUtil::registerStatus ($this->__('Done! Company update successful.'));

        return System::redirect($url);
    }

    function getajaxcompanies()
    {
        $fragment = FormUtil::getPassedValue('fragment');
        // Get DB
        $dbconn = Doctrine_Manager::getInstance()->getCurrentConnection();
        $ztable = DBUtil::getTables();
        // define tables and columns
        $userstable = &$ztable['addressbook_address'];
        $userscolumn = &$ztable['addressbook_address_column'];

        $sql = "SELECT DISTINCT $userscolumn[company],
        $userscolumn[address1],
        $userscolumn[address2],
        $userscolumn[zip],
        $userscolumn[city],
        $userscolumn[state],
        $userscolumn[country]
                FROM    $userstable
                WHERE   $userscolumn[company] REGEXP '" . DataUtil::formatForStore($fragment) . "' ORDER BY $userscolumn[company]";
        $results = $dbconn->Execute($sql);

        // get the companies
        $out = '<ul>';

        while(list($company,$address1,$address2,$zip,$city,$state,$country) = $results->fields) {
            $results->MoveNext();
            $out .= '<li><a href="#">'
            .DataUtil::formatForDisplay($company).'<span style="display:none">#</span>,'.DataUtil::formatForDisplay($address1)
            .'<span style="display:none">#'
            .DataUtil::formatForDisplay($address1)
            .'#'
            .DataUtil::formatForDisplay($address2)
            .'#'
            .DataUtil::formatForDisplay($zip)
            .'#'
            .DataUtil::formatForDisplay($city)
            .'#'
            .DataUtil::formatForDisplay($state)
            .'#'
            .DataUtil::formatForDisplay($country)
            .'</span></a></li>';
        }

        $out .= '</ul>';
        echo $out;
        return true;
    }

    function simpledisplay($args)
    {
        // security check
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ))) {
            return LogUtil::registerPermissionError();
        }

        $ot       = FormUtil::getPassedValue('ot', (isset($args['ot'])) ? $args['ot'] : 'address', 'GET');
        $id       = (int) FormUtil::getPassedValue('id', (isset($args['id'])) ? $args['id'] : null, 'GET');
        $category = FormUtil::getPassedValue('category', 0);
        $private  = FormUtil::getPassedValue('private', 0);

        unset($args);

        if (!$id) {
            return z_exit($this->__f('Error! Invalid id [%s] received.', $id));
        }

        // get the details
        $object = new AddressBook_DBObject_Address();
        $data = $object->get($id);

        // get the custom fields
        $cus_where = "";
        $cus_sort = "cus_pos ASC";
        $cus_Array = new AddressBook_DBObject_CustomfieldArray();
        $customfields = $cus_Array->get ($cus_where, $cus_sort);

        $this->view->assign('address', $data);
        $this->view->assign('customfields', $customfields);
        $this->view->assign('ot', $ot);
        $this->view->assign('category', $category);
        $this->view->assign('private', $private);
        $this->view->assign('preferences', ModUtil::getVar('AddressBook'));
        $this->view->assign('lang',        ZLanguage::getLanguageCode());

        return $this->view->fetch('user_simpledisplay.tpl');
    }
}
