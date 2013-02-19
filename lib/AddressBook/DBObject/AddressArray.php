<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_DBObject_AddressArray extends DBObjectArray
{
    function AddressBook_DBObject_AddressArray($init=null, $where='')
    {
        $this->_objType  = 'addressbook_address';
        $this->_objField = 'id';
        $this->_objPath  = 'address_array';

        $this->_init($init, $where);
    }
}
