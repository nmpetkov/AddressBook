<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: PNCustomfieldArray.class.php 44 2010-03-30 08:35:45Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 * @subpackage DB
 */
/*  ----------------------------------------------------------------------
 *  Original Author of file: Thomas Smiatek
 *  Author Contact: thomas@smiatek.com
 *  Purpose of file: PN module version file
 *  Copyright: Thomas Smiatek
 *  ----------------------------------------------------------------------
 */

class PNCustomfieldArray extends PNObjectArray
{
    function PNCustomfieldArray($init=null, $where='')
    {
        $this->PNObjectArray();
        $this->_objType  = 'addressbook_customfields';
        $this->_objField = 'id';
        $this->_objPath  = 'customfields_array';
        $this->_init($init, $where);
    }
}
