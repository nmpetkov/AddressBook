<?php
/**
 * AddressBook
 *
 * @copyright (c) 2009, AddressBook Development Team
 * @link http://code.zikula.org/addressbook
 * @version $Id: PNCustomfield.class.php 44 2010-03-30 08:35:45Z herr.vorragend $
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

class PNCustomfield extends PNObject
{
    function PNCustomfield($init=null, $key=0)
    {
        $this->PNObject();
        $this->_objType  = 'addressbook_customfields';
        $this->_objField = 'id';
        $this->_objPath  = 'customfield';
        $this->_init($init, $key);
    }

}
