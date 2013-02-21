<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

/**
 * AddressBook Pub ContentType.
 */
class AddressBook_ContentType_Address extends Content_AbstractContentType
{
    protected $addressid;

    public function getTitle()
    {
        return $this->__('AddressBook contact');
    }
    public function getDescription()
    {
        return $this->__('Display a single address.');
    }
    public function isTranslatable() { return false; }

    public function loadData($data)
    {
        $this->addressid = $data['addressid'];
    }

    public function display()
    {
        $address = ModUtil::func('AddressBook', 'user', 'simpledisplay', array('id' => (int) $this->addressid));
        return $address;
    }

    public function displayEditing()
    {
        if (!empty($this->addressid))
        {
            return ModUtil::func('AddressBook', 'user', 'simpledisplay', array('id' => (int) $this->addressid));
        }
        return     $this->__('No address selected');
    }

    public function getDefaultData()
    {
        return array('addressid' => '');
    }


    public function startEditing(&$render)
    {
        array_push($render->plugins_dir, 'modules/AddressBook/templates/form');
    }
}
