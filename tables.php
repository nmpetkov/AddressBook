<?php
/**
 * AddressBook
 *
 * @copyright (c) AddressBook Development Team
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package AddressBook
 */
function AddressBook_tables()
{
    require_once 'lib/legacy/Compat.php';
    
    // Initialise table array
    $tables = array();

    $tables['addressbook_address'] = 'addressbook_address';
    $tables['addressbook_address_column'] = array(
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
        'date'             => 'adr_date',
        'counter'          => 'adr_counter' // count clicks
    );

    // Enable categorization services
    $tables['addressbook_address_db_extra_enable_categorization'] = ModUtil::getVar('AddressBook', 'enablecategorization');
    $tables['addressbook_address_primary_key_column'] = 'id';
    ObjectUtil::addStandardFieldsToTableDefinition($tables['addressbook_address_column'], 'adr_');

    // add potential custom_fields
    addCustomFieldsToTableDefinition($tables['addressbook_address_column']);

    $tables['addressbook_address_column_def'] = array(
        'id'            => "I AUTO PRIMARY",
        'cat_id'        => "I  NOTNULL DEFAULT 0",
        'prefix'        => "I  NOTNULL DEFAULT 0",
        'lname'         => "C(100) DEFAULT NULL",
        'fname'         => "C(60) DEFAULT NULL",
        'sortname'      => "C(180) DEFAULT NULL",
        'title'         => "C(100) DEFAULT NULL",
        'company'       => "C(100) DEFAULT NULL",
        'sortcompany'   => "C(100) DEFAULT NULL",
        'img'           => "C(100) DEFAULT NULL",
        'zip'           => "C(30) DEFAULT NULL",
        'city'          => "C(100) DEFAULT NULL",
        'address1'      => "C(100) DEFAULT NULL",
        'address2'      => "C(100) DEFAULT NULL",
        'state'         => "C(60) DEFAULT NULL",
        'country'       => "C(60) DEFAULT NULL",
        'geodata'       => "C(180) DEFAULT NULL",
        'contact_1'     => "C(100) DEFAULT NULL",
        'contact_2'     => "C(100) DEFAULT NULL",
        'contact_3'     => "C(100) DEFAULT NULL",
        'contact_4'     => "C(100) DEFAULT NULL",
        'contact_5'     => "C(100) DEFAULT NULL",
        'c_label_1'     => "I2 DEFAULT NULL",
        'c_label_2'     => "I2 DEFAULT NULL",
        'c_label_3'     => "I2 DEFAULT NULL",
        'c_label_4'     => "I2 DEFAULT NULL",
        'c_label_5'     => "I2 DEFAULT NULL",
        'c_main'        => "I2 DEFAULT NULL",
        'custom_1'      => "X DEFAULT NULL",
        'custom_2'      => "X DEFAULT NULL",
        'custom_3'      => "X DEFAULT NULL",
        'custom_4'      => "X DEFAULT NULL",
        'note'          => "X DEFAULT NULL",
        'user_id'       => "I DEFAULT NULL",
        'private'       => "I2 DEFAULT NULL",
        'date'          => "I  NOTNULL DEFAULT 0",
        'counter'       => "I  NOTNULL DEFAULT 0"
    );
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['addressbook_address_column_def'], 'adr_');


    $tables['addressbook_labels'] = 'addressbook_labels';
    $tables['addressbook_labels_column'] = array(
        'id'            => 'lab_id',
        'name'          => 'lab_name',
        'name1'          => 'lab_name1' // for localization, can use in templates
    );
    $tables['addressbook_labels_primary_key_column'] = 'id';
    ObjectUtil::addStandardFieldsToTableDefinition($tables['addressbook_labels_column'], 'lab_');

    $tables['addressbook_labels_column_def'] = array(
        'id'            => "I AUTO PRIMARY",
        'name'          => "C(30) DEFAULT NULL",
        'name1'          => "C(30) DEFAULT NULL"
    );
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['addressbook_labels_column_def'], 'lab_');

    $tables['addressbook_customfields'] = 'addressbook_customfields';
    $tables['addressbook_customfields_column'] = array(
        'id'            => 'cus_id',
        'name'          => 'cus_name',
        'name1'         => 'cus_name1', // for localization, can use in templates
        'type'          => 'cus_type',
        'position'      => 'cus_pos',
        'option'        => 'cus_option'
    );
    $tables['addressbook_customfields_primary_key_column'] = 'id';
    ObjectUtil::addStandardFieldsToTableDefinition($tables['addressbook_customfields_column'], 'cus_');

    $tables['addressbook_customfields_column_def'] = array(
        'id'           => "I AUTO PRIMARY",
        'name'         => "C(30) DEFAULT NULL",
        'name1'        => "C(30) DEFAULT NULL",
        'type'         => "C(30) DEFAULT NULL",
        'position'     => "I NOTNULL DEFAULT 0",
        'option'       => "X DEFAULT NULL"
    );
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['addressbook_customfields_column_def'], 'cus_');

    $tables['addressbook_favourites'] = 'addressbook_favourites';
    $tables['addressbook_favourites_column'] = array(
        'favadr_id'    => 'fav_adr_id',
        'favuser_id'   => 'fav_user_id'
    );

    $tables['addressbook_favourites_column_def'] = array(
        'favadr_id'    => "I NOTNULL DEFAULT 0",
        'favuser_id'   => "I NOTNULL DEFAULT 0"
    );

    // Return the table information
    return $tables;
}

function addCustomFieldsToTableDefinition (&$columns)
{
    // get the global db prefix
    $prefix = System::getVar('prefix');
    $prefix = $prefix ? $prefix.'_' : '';
    $tablecustomfields = $prefix."addressbook_customfields";

    $connection = Doctrine_Manager::getInstance()->getCurrentConnection();

    if ($connection->import->tableExists($tablecustomfields)) {
        $sql = "SELECT cus_id FROM ".$tablecustomfields." WHERE cus_id > 4 ORDER BY cus_id ASC";
        $result = DBUtil::executeSQL($sql,-1,-1,false,true);
        if ($result) {
            $customfields = DBUtil::marshallObjects ($result, array('id'), true);
            foreach ($customfields as $cus) {
                $col_def = 'custom_'.$cus[id];
                $columns[$col_def] = 'adr_'.$col_def;
            }
        }
    }

    return;
}
