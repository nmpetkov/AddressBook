<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_Api_Search extends Zikula_AbstractApi
{

    /**
     * Search plugin info
     **/
    public function info()
    {
        return array(
            'title' => 'AddressBook',
            'functions' => array('AddressBook' => 'search')
        );
    }

    /**
     * Search form component
     **/
    public function options($args)
    {
        if (SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ)) {
            $renderer = Zikula_View::getInstance('AddressBook');
            $renderer->assign('active',(isset($args['active'])&&isset($args['active']['AddressBook']))||(!isset($args['active'])));
            return $renderer->fetch('search_options.tpl');
        }

        return '';
    }

    /**
     * Search plugin main function
     **/
    public function search($args)
    {
        // Permission check
        $this->throwForbiddenUnless(
            SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ),
            LogUtil::getErrorMsgPermission()
        );

        ModUtil::dbInfoLoad('Search');
        $ztable = DBUtil::getTables();
        $addresstable = $ztable['addressbook_address'];
        $addresscolumn = $ztable['addressbook_address_column'];
        $searchTable = $ztable['search_result'];
        $searchColumn = $ztable['search_result_column'];

        $searchcols = array($addresscolumn['lname'],
        $addresscolumn['fname'],
        $addresscolumn['company'],
        $addresscolumn['city'],
        $addresscolumn['zip'],
        $addresscolumn['address1'],
        $addresscolumn['address2'],
        $addresscolumn['state'],
        $addresscolumn['country'],
        $addresscolumn['contact_1'],
        $addresscolumn['contact_2'],
        $addresscolumn['contact_3'],
        $addresscolumn['contact_4'],
        $addresscolumn['contact_5']);
        $cusfields = DBUtil::selectFieldArray('addressbook_customfields','id');

        // Get user id
        if (UserUtil::isLoggedIn()) {
            $user_id = UserUtil::getVar('uid');
        } else {
            $user_id = 0;
        }


        for ($i=0;$i<count($cusfields);$i++)
        {
            $colname = 'custom_'.$cusfields[$i];
            array_push($searchcols,$addresscolumn[$colname]);
        }

        $where = search_construct_where($args, $searchcols);

        // admin always sees all records but favourites
        if (SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)) {
            $where .= " AND ($addresscolumn[user_id] IS NOT NULL)";
        } else {
            // global protect - users see only their own records (admin sees all)
            if (((ModUtil::getVar('AddressBook', 'globalprotect'))==1) && (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)))) {
                $where .= " AND ($addresscolumn[user_id]=$user_id)";
            } else {
                // if private = 1, show only private records
                if ($private==1) {
                    $where .= " AND ($addresscolumn[user_id]=$user_id AND $addresscolumn[private] = 1)";
                } else {
                    // if private = 0, show all records
                    $where .= " AND (($addresscolumn[private] = 0) OR ($addresscolumn[user_id]=$user_id AND $addresscolumn[private] = 1))";
                }
            }
        }

        $sessionId = session_id();

        $insertSql =
"INSERT INTO $searchTable
  ($searchColumn[title],
  $searchColumn[text],
  $searchColumn[extra],
  $searchColumn[module],
  $searchColumn[created],
  $searchColumn[session])
    VALUES ";

      ModUtil::loadApi('AddressBook', 'user');

      $sort = "sortname DESC,sortcompany DESC";

      $permChecker = new addressbook_result_checker();
      //$addresses = DBUtil::selectObjectArray('addressbook_address', $where, null, null, '', $permChecker, null);
      $addresses = DBUtil::selectObjectArrayFilter('addressbook_address', $where, null, null, null, '', $permChecker, null);

      foreach ($addresses as $address)
      {
          if ((ModUtil::getVar('AddressBook', 'name_order'))==1)
          {
              $line_1 = $address['fname']." ".$address['lname'];
          } else {
              $line_1 = $address['lname'].", ".$address['fname'];
          }
          if (empty($line_1))
          $line_1 = $address['company'];
          else {
              if (!empty($address['company']))
              $line_1 .= " [".$address['company']."]";
          }

          if ((ModUtil::getVar('AddressBook', 'zipbeforecity'))==1)
          {
              $line_2 = $address['zip']." ".$address['city'];
          } else {
              $line_2 = $address['city']." ".$address['zip'];
          }


          $sql = $insertSql . '('
          . '\'' . DataUtil::formatForStore($line_1) . '\', '
          . '\'' . DataUtil::formatForStore($line_2) . '\', '
          . '\'' . DataUtil::formatForStore($address['id']) . '\', '
          . '\'' . 'AddressBook' . '\', '
          . '\'' . DataUtil::formatForStore($address['cr_date']) . '\', '
          . '\'' . DataUtil::formatForStore($sessionId) . '\')';
          $insertResult = DBUtil::executeSQL($sql);
          if (!$insertResult) {
              return LogUtil::registerError($this->__('Error! Could not load addresses.'));
          }
      }

      return true;
    }


    /**
     * Do last minute access checking and assign URL to items
     *
     * Access checking is ignored since access check has
     * already been done. But we do add a URL to the found user
     */
    public function search_check($args)
    {
        $datarow = &$args['datarow'];
        $ab_id = $datarow['extra'];
        $datarow['url'] = ModUtil::url($this->name, 'user', 'display', array('id' => $ab_id));

        return true;
    }
}

class AddressBook_result_checker
{
    var $enablecategorization;

    function addressbook_result_checker()
    {
        $this->enablecategorization = ModUtil::getVar('AddressBook', 'enablecategorization');
    }

    // This method is called by DBUtil::selectObjectArrayFilter() for each and every search result.
    // A return value of true means "keep result" - false means "discard".
    function checkResult(&$item)
    {
        $ok = SecurityUtil::checkPermission('AddressBook::', "::", ACCESS_OVERVIEW);
        if ($this->enablecategorization)
        {
            ObjectUtil::expandObjectWithCategories($item, 'AddressBook', 'id');
            $ok = $ok && CategoryUtil::hasCategoryAccess($item['__CATEGORIES__'],'AddressBook');
        }
        return $ok;
    }
}
