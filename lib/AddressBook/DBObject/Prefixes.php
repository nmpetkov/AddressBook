<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_DBObject_Prefixes extends DBObject
{
    function AddressBook_DBObject_Prefixes($init=null, $key=0)
    {
        $this->_objType  = 'addressbook_prefixes';
        $this->_objField = 'id';
        $this->_objPath  = 'prefixes';
        $this->_init($init, $key);
    }

}
