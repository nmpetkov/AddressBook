<?php
class AddressBook_contenttypesapi_addressPlugin extends contentTypeBase
{
    var $addressid;

    function getModule() { return 'AddressBook'; }
    function getName() { return 'address'; }
    function getTitle()
    {
        $dom = ZLanguage::getModuleDomain('AddressBook');
        return __('AddressBook contact', $dom);
    }
    function getDescription()
    {
        $dom = ZLanguage::getModuleDomain('AddressBook');
        return __('Display a single address.', $dom);
    }
    function isTranslatable() { return false; }

    function loadData($data)
    {
        $this->addressid = $data['addressid'];
    }

    function display()
    {
        $address = ModUtil::func('AddressBook', 'user', 'simpledisplay', array('id' => (int) $this->addressid));
        return $address;
    }

    function displayEditing()
    {
        $dom = ZLanguage::getModuleDomain('AddressBook');
        if (!empty($this->addressid))
        {
            return ModUtil::func('AddressBook', 'user', 'simpledisplay', array('id' => (int) $this->addressid));
        }
        return __('No address selected', $dom);
    }

    function getDefaultData()
    {
        return array('addressid' => '');
    }


    function startEditing(&$render)
    {
        array_push($render->plugins_dir, 'modules/AddressBook/templates/form');
    }
}


function AddressBook_contenttypesapi_address($args)
{
    return new AddressBook_contenttypesapi_addressPlugin();
}