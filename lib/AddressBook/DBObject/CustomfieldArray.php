<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_DBObject_CustomfieldArray extends DBObjectArray
{
    function AddressBook_DBObject_CustomfieldArray($init=null, $where='')
    {
        $this->_objType  = 'addressbook_customfields';
        $this->_objField = 'id';
        $this->_objPath  = 'customfields_array';
        $this->_init($init, $where);
    }
}
