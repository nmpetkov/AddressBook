<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: PNLabelsArray.class.php 44 2010-03-30 08:35:45Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 * @subpackage DB
 */

class PNLabelsArray extends PNObjectArray
{
    function PNLabelsArray($init=null, $where='')
    {
        $this->PNObjectArray();
        $this->_objType  = 'addressbook_labels';
        $this->_objField = 'id';
        $this->_objPath  = 'labels_array';
        $this->_init($init, $where);
    }
}
