<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_DBObject_PrefixesArray extends DBObjectArray
{
    function AddressBook_DBObject_PrefixesArray($init=null, $where='')
    {
        $this->_objType  = 'addressbook_prefixes';
        $this->_objField = 'id';
        $this->_objPath  = 'prefixes_array';
        $this->_init($init, $where);
    }
}