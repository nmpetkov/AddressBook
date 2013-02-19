<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_DBObject_Customfield extends DBObject
{
    function AddressBook_DBObject_Customfield($init=null, $key=0)
    {
        $this->_objType  = 'addressbook_customfields';
        $this->_objField = 'id';
        $this->_objPath  = 'customfield';
        $this->_init($init, $key);
    }

}
