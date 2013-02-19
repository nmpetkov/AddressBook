<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_DBObject_LabelsArray extends DBObjectArray
{
    function AddressBook_DBObject_LabelsArray($init=null, $where='')
    {
        $this->_objType  = 'addressbook_labels';
        $this->_objField = 'id';
        $this->_objPath  = 'labels_array';
        $this->_init($init, $where);
    }
}
