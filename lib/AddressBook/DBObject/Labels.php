<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_DBObject_Labels extends DBObject
{
    function AddressBook_DBObject_Labels($init=null, $key=0)
    {
        $this->_objType  = 'addressbook_labels';
        $this->_objField = 'id';
        $this->_objPath  = 'labels';
        $this->_init($init, $key);
    }

}
