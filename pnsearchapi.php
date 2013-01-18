<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: pnsearchapi.php 61 2010-03-31 13:44:02Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 * @subpackage API
 */

/**
 * Search plugin info
 **/
function addressbook_searchapi_info()
{
    return array('title' => 'AddressBook',
                 'functions' => array('AddressBook' => 'search'));
}

/**
 * Search form component
 **/
function addressbook_searchapi_options($args)
{
    if (SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ)) {
        // Create output object - this object will store all of our output so that
        // we can return it easily when required
        $pnRender = & pnRender::getInstance('AddressBook');
        $pnRender->assign('active',(isset($args['active'])&&isset($args['active']['AddressBook']))||(!isset($args['active'])));
        return $pnRender->fetch('addressbook_search_options.html');
    }

    return '';
}

/**
 * Search plugin main function
 **/
function addressbook_searchapi_search($args)
{
    $dom = ZLanguage::getModuleDomain('AddressBook');
    if (!SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ)) {
        return true;
    }

    pnModDBInfoLoad('Search');
    $pntable = pnDBGetTables();
    $addresstable = $pntable['addressbook_address'];
    $addresscolumn = $pntable['addressbook_address_column'];
    $searchTable = $pntable['search_result'];
    $searchColumn = $pntable['search_result_column'];

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
    if (pnUserLoggedIn()) {
        $user_id = pnUserGetVar('uid');
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
        if (((pnModGetVar('AddressBook', 'globalprotect'))==1) && (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)))) {
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

  pnModAPILoad('AddressBook', 'user');

  $sort = "sortname DESC,sortcompany DESC";

  $permChecker = new addressbook_result_checker();
  //$addresses = DBUtil::selectObjectArray('addressbook_address', $where, null, null, '', $permChecker, null);
  $addresses = DBUtil::selectObjectArrayFilter('addressbook_address', $where, null, null, null, '', $permChecker, null);

  foreach ($addresses as $address)
  {
      if ((pnModGetVar('AddressBook', 'name_order'))==1)
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

      if ((pnModGetVar('AddressBook', 'zipbeforecity'))==1)
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
          return LogUtil::registerError(__('Error! Could not load addresses.', $dom));
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
function addressbook_searchapi_search_check(&$args)
{
    $datarow = &$args['datarow'];
    $ab_id = $datarow['extra'];

    $datarow['url'] = pnModUrl('AddressBook', 'user', 'display', array('id' => $ab_id));

    return true;
}

