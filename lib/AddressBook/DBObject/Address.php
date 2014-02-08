<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_DBObject_Address extends DBObject
{
    public function AddressBook_DBObject_Address($init=null, $key=0)
    {
        $this->_objType  = 'addressbook_address';
        $this->_objField = 'id';
        $this->_objPath  = 'address';
        $this->_init($init, $key);
    }

    public function getDataFromInputPostProcess ($data=null)
    {
        if (!$data)
        $data =& $this->_objData;

        $data['private']     = (isset($data['private']) ? 1 : 0);

        return $data;
    }

    public function insertPreProcess($data = null)
    {
        $data =& $this->_objData;

        // sort column
        $sortvalue = $data['fname'].' '.$data['lname'];

        $data['sortname']    = $sortvalue; // removet _normalize_special_chars, no need if utf8
        $data['sortcompany'] = $data['company']; // same
        $data['date']        = time();

        // convert custom date type and numeric values
        // get the custom fields
        $cus_where = "";
        $cus_sort = "cus_pos ASC";
        $cus_Array = new AddressBook_DBObject_CustomfieldArray();
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

    public function insertPostProcess($data = null)
    {
        $data['id'] = DBUtil::getInsertID('addressbook_address');
        return $data;
    }

    public function updatePreProcess($data = null)
    {
        return $this->insertPreProcess();
    }

    public function validate ($data=null)
    {
        $dom = ZLanguage::getModuleDomain('AddressBook');
        if (!$data) {
            $data =& $this->_objData;
        }

        if (!$data) {
            return false;
        }

        if (($data['company'] == '' || empty($data['company'])) && ($data['lname'] == '' || empty($data['lname']))&& ($data['fname'] == '' || empty($data['fname'])))
        {
            $_SESSION['validationErrors'][$this->_objPath]['company'] = __('An address must contain data in at least one field of the name tab!', $dom);
            $_SESSION['validationFailedObjects'][$this->_objPath] = $data;
            return false;
        }

        return true;
    }

}
