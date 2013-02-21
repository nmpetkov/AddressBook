<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */

/**
 * Listeners EventHandler.
 */
class AddressBook_EventHandler_Listeners
{
    /**
     * ContentType discovery event handler.
     * 
     * @param Zikula_Event $event
     */
    public static function getContentTypes(Zikula_Event $event)
    {
        $types = $event->getSubject();

        // add content types with add('classname')
        $types->add('AddressBook_ContentType_Address');
    }
}
