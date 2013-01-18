<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: PNAddressArray.class.php 44 2010-03-30 08:35:45Z herr.vorragend $
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

class PNAddressArray extends PNObjectArray
{
    function PNAddressArray($init=null, $where='')
    {
        $this->PNObjectArray();
        $this->_objType  = 'addressbook_address';
        $this->_objField = 'id';
        $this->_objPath  = 'address_array';

        $this->_init($init, $where);
    }
}
