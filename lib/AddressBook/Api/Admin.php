<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

class AddressBook_Api_Admin extends Zikula_AbstractApi
{
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN)) {
            $args = array();
            $args['ot'] = 'labels';
            $url = ModUtil::url('AddressBook', 'admin', 'view', $args);
            $links[] = array('url' => $url, 'text' => $this->__('Contact labels', $dom));

            $args = array();
            $args['ot'] = 'customfield';
            $url = ModUtil::url('AddressBook', 'admin', 'view', $args);
            $links[] = array('url' => $url, 'text' => $this->__('Custom fields', $dom));

            $args = array();
            $args['ot'] = 'address';
            $url = ModUtil::url('AddressBook', 'admin', 'modifyconfig', $args);
            $links[] = array('url' => $url, 'text' => $this->__('Settings', $dom));
        }

        return $links;
    }

    function delete()
    {
        // security check
        if (!(SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_ADMIN))) {
            return LogUtil::registerPermissionError();
        }

        $dom = ZLanguage::getModuleDomain('AddressBook');

        $ot = FormUtil::getPassedValue('ot', 'categories', 'GETPOST');
        $id = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');

        $url = ModUtil::url('AddressBook', 'admin', 'view', array('ot'=>$ot));

        if (!($class = Loader::loadClassFromModule('AddressBook', $ot))) {
            return z_exit(__f('Error! Unable to load class [%s]', $ot, $dom));
        }

        $object = new $class();
        $data = $object->get($id);
        if (!$data) {
            LogUtil::registerError(__f('%1$s with ID of %2$s doesn\'\t seem to exist', array($ot, $id), $dom));
            return System::redirect($url);
        }
        $object->delete();

        if ($ot == "customfield")
        {
            $sql="ALTER TABLE addressbook_address DROP adr_custom_".$id;
            DBUtil::executeSQL($sql,-1,-1,true,true);
        }
        LogUtil::registerStatus (__('Done! Item deleted.', $dom));

        return System::redirect($url);
    }
}