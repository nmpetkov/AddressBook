<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_DBObject_FavouriteArray extends DBObjectArray
{
    function AddressBook_DBObject_FavouriteArray($init=null, $where='')
    {
        $this->_objType  = 'addressbook_favourites';
        $this->_objField = 'id';
        $this->_objPath  = 'favourite_array';

        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'id',
                    'object_field_name'   =>  'id',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'lname',
                    'object_field_name'   =>  'lname',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'fname',
                    'object_field_name'   =>  'fname',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'sortname',
                    'object_field_name'   =>  'sortname',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'company',
                    'object_field_name'   =>  'company',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'sortcompany',
                    'object_field_name'   =>  'sortcompany',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'contact_1',
                    'object_field_name'   =>  'contact_1',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'contact_2',
                    'object_field_name'   =>  'contact_2',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'contact_3',
                    'object_field_name'   =>  'contact_3',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'contact_4',
                    'object_field_name'   =>  'contact_4',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'contact_5',
                    'object_field_name'   =>  'contact_5',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'c_main',
                    'object_field_name'   =>  'c_main',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');
        $this->_objJoin[] = array ( 'join_table' =>  'addressbook_address',
                    'join_field'          =>  'user_id',
                    'object_field_name'   =>  'user_id',
                    'compare_field_table' =>  'favadr_id',
                    'compare_field_join'  =>  'id');

        $this->_init($init, $where);
    }
}
