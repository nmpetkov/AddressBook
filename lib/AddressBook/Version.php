<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_Version extends Zikula_AbstractVersion
{
    
    /**
     * Meta data for the module
     * @return array
     */
    public function getMetaData()
    {
        $meta = array();
        $meta['name']             = 'AddressBook';
        $meta['oldnames']         = array('Addressbook');
        $meta['displayname']      = __('Address book');
        $meta['url']              = __('addressbook');
        $meta['version']          = '1.3.4';
        $meta['description']      = __('Zikula module to manage contacts.');
        $meta['author']           = 'AddressBook Development Team';
        $meta['contact']          = 'https://github.com/nmpetkov/AddressBook';
        $meta['admin']            = 1;
        $meta['user']             = 1;
        $meta['capabilities']   = array(HookUtil::SUBSCRIBER_CAPABLE => array('enabled' => true));
        $meta['securityschema']   = array('AddressBook::' => '::');
        return $meta;
    }

    protected function setupHookBundles()
    {
        // Register hooks
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.addressbook.ui_hooks.items', 'ui_hooks', $this->__('AddressBook Items Hooks'));
        $bundle->addEvent('display_view', 'addressbook.ui_hooks.items.display_view');
        $bundle->addEvent('form_edit', 'addressbook.ui_hooks.items.form_edit');
        $this->registerHookSubscriberBundle($bundle);
    }
}