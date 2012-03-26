<?php
/**
* AddressBook
*
* @copyright (c) 2009, AddressBook Development Team
* @link http://code.zikula.org/addressbook
* @version $Id: pntables.php 68 2010-04-01 13:07:05Z herr.vorragend $
* @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
* @package AddressBook
* @subpackage Init
*/
// ----------------------------------------------------------------------
// Original Author of file: Thomas Smiatek
// Purpose of file:  Table information for AddressBook module
// ----------------------------------------------------------------------

function AddressBook_pntables()
{
    // Initialise table array
    $pntable = array();

    $dbdriver = DBConnectionStack::getConnectionDBDriver();

    $pntable['addressbook_address'] = DBUtil::getLimitedTablename('addressbook_address');
    $pntable['addressbook_address_column'] = array(
    'id'               => 'adr_id',
    'cat_id'           => 'adr_catid',
    'prefix'           => 'adr_prefix',
    'lname'            => 'adr_name',
    'fname'            => 'adr_fname',
    'sortname'         => 'adr_sortname',
    'title'            => 'adr_title',
    'company'          => 'adr_company',
    'sortcompany'      => 'adr_sortcompany',
    'img'              => 'adr_img',
    'zip'              => 'adr_zip',
    'city'             => 'adr_city',
    'address1'         => 'adr_address1',
    'address2'         => 'adr_address2',
    'state'            => 'adr_state',
    'country'          => 'adr_country',
    'geodata'          => 'adr_geodata',
    'contact_1'        => 'adr_contact_1',
    'contact_2'        => 'adr_contact_2',
    'contact_3'        => 'adr_contact_3',
    'contact_4'        => 'adr_contact_4',
    'contact_5'        => 'adr_contact_5',
    'c_label_1'        => 'adr_c_label_1',
    'c_label_2'        => 'adr_c_label_2',
    'c_label_3'        => 'adr_c_label_3',
    'c_label_4'        => 'adr_c_label_4',
    'c_label_5'        => 'adr_c_label_5',
    'c_main'           => 'adr_c_main',
    'custom_1'         => 'adr_custom_1',
    'custom_2'         => 'adr_custom_2',
    'custom_3'         => 'adr_custom_3',
    'custom_4'         => 'adr_custom_4',
    'note'             => 'adr_note',
    'user_id'          => 'adr_user',
    'private'          => 'adr_private',
    'date'             => 'adr_date'
    );

    // Enable categorization services
    $pntable['addressbook_address_db_extra_enable_categorization'] = pnModGetVar('AddressBook', 'enablecategorization');
    $pntable['addressbook_address_primary_key_column'] = 'id';
    ObjectUtil::addStandardFieldsToTableDefinition($pntable['addressbook_address_column'], 'adr_');

    // add potential custom_fields
    addCustomFieldsToTableDefinition($pntable['addressbook_address_column']);

    $pntable['addressbook_address_column_def'] = array(
    'id'            => "I AUTO PRIMARY",
    'cat_id'        => "I(11)  NOTNULL DEFAULT 0",
    'prefix'        => "I(11)  NOTNULL DEFAULT 0",
    'lname'         => "VARCHAR(100) DEFAULT NULL",
    'fname'         => "VARCHAR(60) DEFAULT NULL",
    'sortname'      => "VARCHAR(180) DEFAULT NULL",
    'title'         => "VARCHAR(100) DEFAULT NULL",
    'company'       => "VARCHAR(100) DEFAULT NULL",
    'sortcompany'   => "VARCHAR(100) DEFAULT NULL",
    'img'           => "VARCHAR(100) DEFAULT NULL",
    'zip'           => "VARCHAR(30) DEFAULT NULL",
    'city'          => "VARCHAR(100) DEFAULT NULL",
    'address1'      => "VARCHAR(100) DEFAULT NULL",
    'address2'      => "VARCHAR(100) DEFAULT NULL",
    'state'         => "VARCHAR(60) DEFAULT NULL",
    'country'       => "VARCHAR(60) DEFAULT NULL",
    'geodata'       => "VARCHAR(180) DEFAULT NULL",
    'contact_1'     => "VARCHAR(80) DEFAULT NULL",
    'contact_2'     => "VARCHAR(80) DEFAULT NULL",
    'contact_3'     => "VARCHAR(80) DEFAULT NULL",
    'contact_4'     => "VARCHAR(80) DEFAULT NULL",
    'contact_5'     => "VARCHAR(80) DEFAULT NULL",
    'c_label_1'     => "I(4) DEFAULT NULL",
    'c_label_2'     => "I(4) DEFAULT NULL",
    'c_label_3'     => "I(4) DEFAULT NULL",
    'c_label_4'     => "I(4) DEFAULT NULL",
    'c_label_5'     => "I(4) DEFAULT NULL",
    'c_main'        => "I(4) DEFAULT NULL",
    'custom_1'      => "VARCHAR(60) DEFAULT NULL",
    'custom_2'      => "VARCHAR(60) DEFAULT NULL",
    'custom_3'      => "VARCHAR(60) DEFAULT NULL",
    'custom_4'      => "VARCHAR(60) DEFAULT NULL",
    'note'          => "TEXT DEFAULT NULL",
    'user_id'       => "I(11) DEFAULT NULL",
    'private'       => "I(4) DEFAULT NULL",
    'date'          => "I(11)  NOTNULL DEFAULT 0"
    );
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['addressbook_address_column_def'], 'adr_');


    $pntable['addressbook_labels'] = DBUtil::getLimitedTablename('addressbook_labels');
    $pntable['addressbook_labels_column'] = array(
    'id'            => 'lab_id',
    'name'          => 'lab_name'
    );
    $pntable['addressbook_labels_primary_key_column'] = 'id';
    ObjectUtil::addStandardFieldsToTableDefinition($pntable['addressbook_labels_column'], 'lab_');

    $pntable['addressbook_labels_column_def'] = array(
    'id'            => "I AUTO PRIMARY",
    'name'          => "VARCHAR(30) DEFAULT NULL"
    );
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['addressbook_labels_column_def'], 'lab_');

    $pntable['addressbook_customfields'] = DBUtil::getLimitedTablename('addressbook_customfields');
    $pntable['addressbook_customfields_column'] = array(
    'id'            => 'cus_id',
    'name'          => 'cus_name',
    'type'          => 'cus_type',
    'position'      => 'cus_pos',
    'option'        => 'cus_option'
    );
    $pntable['addressbook_customfields_primary_key_column'] = 'id';
    ObjectUtil::addStandardFieldsToTableDefinition($pntable['addressbook_customfields_column'], 'cus_');

    $pntable['addressbook_customfields_column_def'] = array(
    'id'           => "I AUTO PRIMARY",
    'name'         => "VARCHAR(30) DEFAULT NULL",
    'type'         => "VARCHAR(30) DEFAULT NULL",
    'position'     => "I(11)  NOTNULL DEFAULT 0",
    'option'       => "TEXT DEFAULT NULL"
    );
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['addressbook_customfields_column_def'], 'cus_');

    $pntable['addressbook_favourites'] = DBUtil::getLimitedTablename('addressbook_favourites');
    $pntable['addressbook_favourites_column'] = array(
    'favadr_id'    => 'fav_adr_id',
    'favuser_id'   => 'fav_user_id'
    );

    $pntable['addressbook_favourites_column_def'] = array(
    'favadr_id'    => "I(11)  NOTNULL DEFAULT 0",
    'favuser_id'   => "I(11)  NOTNULL DEFAULT 0"
    );

    // Return the table information
    return $pntable;
}

function addCustomFieldsToTableDefinition (&$columns)
{

    // get the global db prefix
    $prefix = pnConfigGetVar('prefix');

    $sql = "SELECT cus_id
    FROM ".$prefix."_addressbook_customfields
    WHERE cus_id > 4
    ORDER BY cus_id ASC";

    $result = DBUtil::executeSQL($sql,-1,-1,false,true);

    if ($result)
    {
        $customfields = DBUtil::marshallObjects ($result, array('id'), true);
        foreach ($customfields as $cus)
        {
            $col_def = 'custom_'.$cus[id];
            $columns[$col_def] = 'adr_'.$col_def;
        }
    }

    return;
}
