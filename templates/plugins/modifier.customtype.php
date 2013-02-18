<?php

function smarty_modifier_customtype($string)
{

    $ret_string = $string;

    $ar_type = array('varchar(60) default NULL',
                     'varchar(120) default NULL',
                     'varchar(240) default NULL',
                     'text',
                     'int default NULL',
                     'decimal(10,2) default NULL',
                     'date default NULL',
                     'dropdown',
                     'tinyint default NULL',
                     'smallint default NULL');

    $ar_dspname = array('Text, 60 chars, 1 line',
                        'Text, 120 chars, 2 lines',
                        'Text, 240 chars, 4 lines',
                        'Text, unlimited, 6 lines',
                        'Integer numbers',
                        'Decimal numbers',
                        'Date',
                        'Dropdown List',
                        'Blank line',
                        'Horizontal rule');

    for($i=0;$i<count($ar_type);$i++)
    {
        if ($string == $ar_type[$i])
        {
            $ret_string = $ar_dspname[$i];
            break;
        }
    }

    return DataUtil::formatForDisplayHTML($ret_string);
}