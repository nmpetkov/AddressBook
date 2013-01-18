<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: pnuser.php 69 2010-04-01 13:30:14Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 * @subpackage UI
 */

function AddressBook_user_main()
{
    // security check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ))) {
        return LogUtil::registerPermissionError();
    }
    // do nothing
    return System::redirect(ModUtil::url('AddressBook', 'user', 'view'));
}


function AddressBook_user_edit()
{
    $dom = ZLanguage::getModuleDomain('AddressBook');

    $ot         = FormUtil::getPassedValue('ot', 'address', 'GET');
    $duplicate  = FormUtil::getPassedValue('duplicate', 0, 'GET');
    $id         = (int) FormUtil::getPassedValue('id', 0, 'GET');
    $startnum   = FormUtil::getPassedValue('startnum', 1, 'GET');
    $letter     = FormUtil::getPassedValue('letter', 0);
    $sort       = FormUtil::getPassedValue('sort', 'sortname ASC');
    $search     = FormUtil::getPassedValue('search', 0);
    $category   = FormUtil::getPassedValue('category', 0);
    $returnid   = FormUtil::getPassedValue('returnid', 0, 'GET');

    if (!($class = Loader::loadClassFromModule('AddressBook', 'address'))) {
        return z_exit(__('Error! Unable to load class [address]', $dom));
    }

    // Get user id
    if (UserUtil::isLoggedIn()) {
        $user_id = UserUtil::getVar('uid');
    } else {
        $user_id = 0;
    }

    $data = array();
    if ($id) {
        $object = new $class();
        $data = $object->get($id);
        if ($duplicate) {
            $data['id'] = 0;
            LogUtil::registerStatus (__('Be careful. This is a duplicate!', $dom));
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
    if (!($cus_class = Loader::loadClassFromModule('AddressBook', 'customfield', true))) {
        return z_exit(__('Error! Unable to load class [customfield]', $dom));
    }
    $cus_Array = new $cus_class();
    $customfields = $cus_Array->get ($cus_where, $cus_sort);

    // load the category registry util
    if (!Loader::loadClass('CategoryRegistryUtil')) {
        z_exit(__f('Error! Unable to load class [%s]', 'CategoryRegistryUtil', $dom));
    }
    $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('AddressBook', 'addressbook_address');

    $Renderer = Zikula_View::getInstance('AddressBook', false); // caching is false
    $Renderer->setCaching(false); // not suitable for cachin
    $Renderer->assign('catregistry',  $catregistry);
    $Renderer->assign('address',      $data);
    $Renderer->assign('ot',           $ot);
    $Renderer->assign('user_id',      $user_id);
    $Renderer->assign('customfields', $customfields);
    $Renderer->assign('startnum',     $startnum);
    $Renderer->assign('letter',       $letter);
    $Renderer->assign('category',     $category);
    $Renderer->assign('private',      $private);
    $Renderer->assign('sort',         $sort);
    $Renderer->assign('search',       $search);
    $Renderer->assign('returnid',     $returnid);
    $Renderer->assign('preferences',  ModUtil::getVar('AddressBook'));

    return $Renderer->fetch('addressbook_user_edit.html');
}

function AddressBook_user_display()
{
    // security check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ))) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('AddressBook');

    $ot = FormUtil::getPassedValue('ot', 'address', 'GET');
    $id = (int)FormUtil::getPassedValue('id', 0, 'GET');
    $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
    $letter   = FormUtil::getPassedValue('letter', 0);
    $sort     = FormUtil::getPassedValue('sort', 'sortname ASC');
    $search   = FormUtil::getPassedValue('search', 0);
    $category = FormUtil::getPassedValue('category', 0);
    $private  = FormUtil::getPassedValue('private', 0);

    if (!$id) {
        return z_exit(__f('Error! Invalid id [%s] received.', $id, $dom));
    }

    // Get user id
    if (UserUtil::isLoggedIn()) {
        $user_id = UserUtil::getVar('uid');
    } else {
        $user_id = 0;
    }
    
    $Renderer = Zikula_View::getInstance('AddressBook');
    $Renderer->setCacheId('display|id_'.$id . '|uid_'.$user_id);
    $template = 'addressbook_user_display.html';
    if ($Renderer->is_cached($template)) {
        return $Renderer->fetch($template);
    }
    

    // get the details
    if (!($class = Loader::loadClassFromModule('AddressBook', 'address'))) {
        return z_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
    }
    $object = new $class();
    $data = $object->get($id);

    // get the custom fields
    $cus_where = "";
    $cus_sort = "cus_pos ASC";
    if (!($cus_class = Loader::loadClassFromModule('AddressBook', 'customfield', true))) {
        return z_exit(__('Error! Unable to load class [customfield]', $dom));
    }
    $cus_Array = new $cus_class();
    $customfields = $cus_Array->get ($cus_where, $cus_sort);

    DBUtil::incrementObjectFieldByID('addressbook_address', 'counter', $id, 'id'); // count clicks

    $Renderer->assign('address', $data);
    $Renderer->assign('customfields', $customfields);
    $Renderer->assign('user_id', $user_id);
    $Renderer->assign('ot', $ot);

    // assign the criteria from the view modus
    $Renderer->assign('startnum',   $startnum);
    $Renderer->assign('letter',     $letter);
    $Renderer->assign('category',   $category);
    $Renderer->assign('private',    $private);
    $Renderer->assign('sort',       $sort);
    $Renderer->assign('search',     $search);

    // favourite?
    if (!($tclass = Loader::loadClassFromModule('AddressBook', 'favourite',true)))
    return z_exit(__('Error! Unable to load class [favourite]', $dom));

    $where = "fav_adr_id=$id AND fav_user_id=$user_id";
    $fav = new $tclass();
    $favData  = $fav->getWhere ($where);

    if ($favData)
    $Renderer->assign ('isFavourite', 1);

    unset ($fav);
    unset ($favData);
    unset ($where);

    // Google Maps
    $Renderer->assign('preferences', ModUtil::getVar('AddressBook'));
    $Renderer->assign('lang',        ZLanguage::getLanguageCode());

    return $Renderer->fetch($template);

}

function AddressBook_user_view()
{

    //Private Address Book mode, for users only
    if ((!UserUtil::isLoggedIn()) && (ModUtil::getVar('AddressBook', 'globalprotect')==1)) {
        return LogUtil::registerError(__f('This website require it\'s users to be registered to use the address book.<br />Register for free <a href="%1$s">here</a>, or <a href=\"%1$s\">log in</a> if you are already registered.', array(ModUtil::url('Users', 'user', 'view')), $dom));
    }

    // security check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ))) {
        return LogUtil::registerPermissionError();
    }

    $ot   = FormUtil::getPassedValue('ot', 'address', 'GET');
    $startnum = FormUtil::getPassedValue('startnum', 1, 'GET');
    $pagesize = ModUtil::getVar('AddressBook', 'itemsperpage', 30);
    $letter   = FormUtil::getPassedValue('letter', 0);
    $sort     = FormUtil::getPassedValue('sort', 'sortname ASC');
    $search   = FormUtil::getPassedValue('search', 0);
    $category = FormUtil::getPassedValue('category', 0);
    $private  = FormUtil::getPassedValue('private', 0);

    if (empty($sort)) {
        $sort = "sortname ASC";
    }

    // Get user id
    if (UserUtil::isLoggedIn()) {
        $user_id = UserUtil::getVar('uid');
    } else {
        $user_id = 0;
    }

    $Renderer = Zikula_View::getInstance('AddressBook');
    $Renderer->setCacheId('view|cat_'.$category . 
        '|ot'.$ot.'_stnum'.$startnum.'_itpg'.$pagesize.'_let'.$letter.'_sort'.$sort.'_prv'.$private.'_srch'.$search.
        '|uid_'.$user_id);
    $template = 'addressbook_user_view.html';
    if ($Renderer->is_cached($template)) {
        return $Renderer->fetch($template);
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

    if (!($class = Loader::loadClassFromModule('AddressBook', $ot, true))) {
        return z_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
    }

    // filter for category

    if ($category) {
        $where .= " AND $address_column[cat_id] = $category";
    }

    // filter for search
    if ($search) {
        LogUtil::registerStatus (__('Current search term: ', $dom).$search);

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
        if (!($class_cus = Loader::loadClassFromModule('AddressBook', 'customfield', true))) {
            return z_exit(__('Error! Unable to load class [customfield]', $dom));
        }
        $cus_Array = new $class_cus();
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
    if (!($class = Loader::loadClassFromModule('AddressBook', $ot, true))) {
        return z_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
    }

    $objectArray = new $class();
    $data = $objectArray->get ($where, $sort, $startnum-1, $pagesize);
    $objcount = $objectArray->getCount ($where);

    // load the category registry util
    if (!Loader::loadClass('CategoryRegistryUtil')) {
        z_exit(__f('Error! Unable to load class [%s]', 'CategoryRegistryUtil', $dom));
    }
    $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('AddressBook', 'addressbook_address');

    $Renderer->assign('catregistry',   $catregistry);
    $Renderer->assign('ot',            $ot);
    $Renderer->assign('objectArray',   $data);
    $Renderer->assign('startnum',      $startnum);
    $Renderer->assign('letter',        $letter);
    $Renderer->assign('category',      $category);
    $Renderer->assign('private',       $private);
    $Renderer->assign('search',        $search);
    $Renderer->assign('globalprotect', ModUtil::getVar('AddressBook', 'globalprotect'));
    $Renderer->assign('pager',         array('numitems'     => $objcount,
                                             'itemsperpage' => $pagesize));

    return $Renderer->fetch($template);
}

function AddressBook_user_delete()
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
    $sort     = FormUtil::getPassedValue('sort', 'sortname ASC');
    $search   = FormUtil::getPassedValue('search', 0);
    $category = FormUtil::getPassedValue('category', 0);
    $private  = FormUtil::getPassedValue('private', 0);

    // create the output object
    $Renderer = Zikula_View::getInstance('AddressBook', false);

    $Renderer->assign('id', $id);
    $Renderer->assign('ot', $ot);

    // assign the criteria from the view modus
    $Renderer->assign('startnum',   $startnum);
    $Renderer->assign('letter',     $letter);
    $Renderer->assign('category',   $category);
    $Renderer->assign('private',    $private);
    $Renderer->assign('sort',       $sort);
    $Renderer->assign('search',     $search);

    // return output
    return $Renderer->fetch('addressbook_user_delete.html');
}

function AddressBook_user_change_company()
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
    $sort     = FormUtil::getPassedValue('sort', 'sortname ASC');
    $search   = FormUtil::getPassedValue('search', 0);
    $category = FormUtil::getPassedValue('category', 0);
    $private  = FormUtil::getPassedValue('private', 0);

    // create the output object
    $Renderer = Zikula_View::getInstance('AddressBook', false);

    $Renderer->assign('id', $id);
    $Renderer->assign('ot', $ot);

    // assign the criteria from the view modus
    $Renderer->assign('startnum',   $startnum);
    $Renderer->assign('letter',     $letter);
    $Renderer->assign('category',   $category);
    $Renderer->assign('private',    $private);
    $Renderer->assign('sort',       $sort);
    $Renderer->assign('search',     $search);

    // return output
    return $Renderer->fetch('addressbook_user_change_company.html');
}

function AddressBook_user_getajaxcompanies()
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

function AddressBook_user_simpledisplay($args)
{
    // security check
    if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ))) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('AddressBook');

    $ot       = FormUtil::getPassedValue('ot', (isset($args['ot'])) ? $args['ot'] : 'address', 'GET');
    $id       = (int) FormUtil::getPassedValue('id', (isset($args['id'])) ? $args['id'] : null, 'GET');
    $category = FormUtil::getPassedValue('category', 0);
    $private  = FormUtil::getPassedValue('private', 0);

    unset($args);

    if (!$id) {
        return z_exit(__f('Error! Invalid id [%s] received.', $id, $dom));
    }

    // get the details
    if (!($class = Loader::loadClassFromModule('AddressBook', 'address'))) {
        return z_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
    }
    $object = new $class();
    $data = $object->get($id);

    // get the custom fields
    $cus_where = "";
    $cus_sort = "cus_pos ASC";
    if (!($cus_class = Loader::loadClassFromModule('AddressBook', 'customfield', true))) {
        return z_exit(__('Error! Unable to load class [customfield]', $dom));
    }
    $cus_Array = new $cus_class();
    $customfields = $cus_Array->get ($cus_where, $cus_sort);

    $Renderer = Zikula_View::getInstance('AddressBook', false);
    $Renderer->assign('address', $data);
    $Renderer->assign('customfields', $customfields);
    $Renderer->assign('ot', $ot);
    $Renderer->assign('category', $category);
    $Renderer->assign('private', $private);
    $Renderer->assign('preferences', ModUtil::getVar('AddressBook'));
    $Renderer->assign('lang',        ZLanguage::getLanguageCode());

    return $Renderer->fetch('addressbook_user_simpledisplay.html');
}
