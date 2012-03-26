<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: PNAddress.class.php 61 2010-03-31 13:44:02Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 * @subpackage DB
 */
/*  ----------------------------------------------------------------------
 *  Original Author of file: Thomas Smiatek
 *  Author Contact: thomas@smiatek.com
 *  Purpose of file: PN module version file
 *  Copyright: Thomas Smiatek
 *  ----------------------------------------------------------------------
 */

class PNAddress extends PNObject
{
    function PNAddress($init=null, $key=0)
    {
        $this->PNObject();
        $this->_objType  = 'addressbook_address';
        $this->_objField = 'id';
        $this->_objPath  = 'address';
        $this->_init($init, $key);
    }

    function getDataFromInputPostProcess ($data=null)
    {
        if (!$data)
        $data =& $this->_objData;

        $data['private']     = (isset($data['private']) ? 1 : 0);

        return $data;
    }

    function insertPreProcess()
    {
        $data =& $this->_objData;

        // sort column
        if (pnModGetVar('AddressBook', 'name_order')==1) {
            $sortvalue = $data[fname].' '.$data[lname];
        }
        else {
            $sortvalue = $data[lname].', '.$data[fname];
        }

        $data['sortname']    = _normalize_special_chars($sortvalue);
        $data['sortcompany'] = _normalize_special_chars($data['company']);
        $data['date']        = GetUserTime(time());

        // convert custom date type and numeric values
        // get the custom fields
        $cus_where = "";
        $cus_sort = "cus_pos ASC";
        if (!($cus_class = Loader::loadClassFromModule('AddressBook', 'customfield', true))) {
            return pn_exit(__('Error! Unable to load class [customfield]', $dom));
        }
        $cus_Array = new $cus_class();
        $customfields = $cus_Array->get ($cus_where, $cus_sort);
        foreach ($customfields as $cus)
        {
            $cusfield = "custom_".$cus['id'];
            if (!empty($data[$cusfield]))
            {
                if ($cus['type'] == 'date default NULL')
                {
                    $data[$cusfield] = DateUtil::parseUIDate($data[$cusfield]);
                    $data[$cusfield] = DateUtil::transformInternalDate($data[$cusfield]);
                }
                if ($cus['type'] == 'decimal(10,2) default NULL')
                {
                    $check_format = ereg_replace(",",".",$data[$cusfield]);
                    $split_format = explode(".",$check_format);
                    $count_array = count($split_format);
                    // example 1000
                    if($count_array == 1){
                        if(ereg("^[+|-]{0,1}[0-9]{1,}$",$check_format)){
                            $num="$split_format[0]";
                        }
                    }
                    // example 1000,20 or 1.000
                    if($count_array == 2){
                        if(ereg("^[+|-]{0,1}[0-9]{1,}.[0-9]{0,2}$",$check_format)){
                            $num="$split_format[0].$split_format[1]";
                        }
                    }
                    // example 1,000.20 or 1.000,20
                    if($count_array == 3){
                        if(ereg("^[+|-]{0,1}[0-9]{1,}.[0-9]{3}.[0-9]{0,2}$",$check_format)){
                            $num="$split_format[0]$split_format[1].$split_format[2]";
                        }
                    }
                    $data[$cusfield] = $num;
                }
            }
        }

        return $data;
    }

    function insertPostProcess()
    {
        $data['id'] = DBUtil::getInsertID('addressbook_address');
        return $data;
    }

    function updatePreProcess()
    {
        return $this->insertPreProcess();
    }

    function validate ($data=null)
    {
        $dom = ZLanguage::getModuleDomain('AddressBook');
        if (!$data) {
            $data =& $this->_objData;
        }

        if (!$data) {
            return false;
        }

        if (($data['company'] == '' || empty($data['company'])) && ($data['name'] == '' || empty($data['name']))&& ($data['fname'] == '' || empty($data['fname'])))
        {
            $_SESSION['validationErrors'][$this->_objPath]['company'] = __('An address must contain data in at least one field of the name tab!', $dom);
            $_SESSION['validationFailedObjects'][$this->_objPath] = $data;
            return false;
        }

        return true;
    }

}

function _normalize_special_chars ($string)
{

    $special1 = pnModGetVar('AddressBook', 'special_chars_1');
    $special2 = pnModGetVar('AddressBook', 'special_chars_2');
    $string = utf8_decode($string);
    $string = strtr($string, utf8_decode($special1), $special2);
    $string = strtolower($string);
    return utf8_encode($string);

}
