<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_Api_User extends Zikula_AbstractApi
{
    //  fumction for calls by external modules
    //  returns an array of items based on the given search param
    function search ($args)
    {
        //Private Address Book mode, for users only
        if ((!UserUtil::isLoggedIn()) && (ModUtil::getVar('AddressBook', 'globalprotect')==1)) {
            return LogUtil::registerError($this->__f('This website require it\'s users to be registered to use the address book.<br />Register for free <a href="%1$s">here</a>, or <a href=\"%1$s\">log in</a> if you are already registered.', array(ModUtil::url('Users', 'user', 'view'))));
        }

        // security check
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ))) {
            return LogUtil::registerPermissionError();
        }

        $search = (isset($args['search']) ? $args['search'] : '');
        $sort = "sortname ASC";
        $ot   = "address";

        // Get user id
        if (UserUtil::isLoggedIn()) {
            $user_id = UserUtil::getVar('uid');
        } else {
            $user_id = 0;
        }

        // build the where clause
        $where = '';

        $ztable = DBUtil::getTables();
        $address_table = $ztable['addressbook_address'];
        $address_column = &$ztable['addressbook_address_column'];

        // admin always sees all records but favourites
        if (SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)) {
            $where .= "($address_column[user_id] IS NOT NULL)";
        } else {
            // global protect - users see only their own records (admin sees all)
            if (((ModUtil::getVar('AddressBook', 'globalprotect'))==1) && (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)))) {
                $where = "($address_column[user_id]=$user_id)";
            } else {
                // if private = 1, show only private records
                if ($private==1) {
                    $where = "($address_column[user_id]=$user_id AND $address_column[private] = 1)";
                } else {
                    // if private = 0, show all records
                    $where = "(($address_column[private] = 0) OR ($address_column[user_id]=$user_id AND $address_column[private] = 1))";
                }
            }
        }

        if (!($class = Loader::loadClassFromModule('AddressBook', $ot, true))) {
            return z_exit($this->__f('Error! Unable to load class [%s]', $ot));
        }

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
            return z_exit($this->$this->$this->$this->__('Error! Unable to load class [customfield]'));
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

        // get the result
        if (!($class = Loader::loadClassFromModule('AddressBook', $ot, true))) {
            return z_exit($this->__f('Error! Unable to load class [%s]', $ot));
        }

        $objectArray = new $class();
        $data = $objectArray->get($where, $sort, $startnum-1, $pagesize);

        return $data;
    }

    /**
     * Clear cache for given item. Can be called from other modules to clear an item cache.
     *
     * @param $item - the item: array with data or id of the item
     */
    function clearItemCache ($item)
    {
        if ($item && !is_array($item)) {
            if (!($class = Loader::loadClassFromModule('AddressBook', 'address'))) {
                return z_exit($this->$this->$this->$this->__('Error! Unable to load class [address]'));
            }
            $object = new $class();
            $item = $object->get($item);
        }
        if ($item) {
            // Clear View_cache
            $cache_ids = array();
            $cache_ids[] = 'display/id_'.$item['id'];
            $cache_ids[] = 'view/cat_0';
            $cache_ids[] = 'view/cat_'.$item['cat_id'];
            $view = Zikula_View::getInstance('AddressBook');
            foreach ($cache_ids as $cache_id) {
                $view->clear_cache(null, $cache_id);
            }

            // Clear Theme_cache
            $cache_ids = array();
            $cache_ids[] = 'AddressBook/user/display/id_'.$item['id']; // for given Id, according to new cache_id structure in Zikula 1.3.2.dev (1.3.3)
            //$cache_ids[] = 'homepage'; // for homepage (it can be adjustment in module settings)
            $cache_ids[] = 'AddressBook/user/view'; // view function (contacts list)
            $cache_ids[] = 'AddressBook/user/main'; // main function
            $theme = Zikula_View_Theme::getInstance();
            //if (Zikula_Core::VERSION_NUM > '1.3.2') {
            if (method_exists($theme, 'clear_cacheid_allthemes')) {
                $theme->clear_cacheid_allthemes($cache_ids);
            } else {
                // clear cache for current theme only
                foreach ($cache_ids as $cache_id) {
                    $theme->clear_cache(null, $cache_id);
                }
            }
        }
    }
}