<?php

function smarty_function_ab_getdatatypes($params, &$smarty)
{
    $ab_datatype[1]['type'] = 'varchar(60) default NULL';
    $ab_datatype[1]['dspname'] = 'Text, 60 chars, 1 line';
    $ab_datatype[2]['type'] = 'varchar(120) default NULL';
    $ab_datatype[2]['dspname'] = 'Text, 120 chars, 2 lines';
    $ab_datatype[3]['type'] = 'varchar(240) default NULL';
    $ab_datatype[3]['dspname'] = 'Text, 240 chars, 4 lines';
    $ab_datatype[4]['type'] = 'text';
    $ab_datatype[4]['dspname'] = 'Text, unlimited, 6 lines';
    $ab_datatype[5]['type'] = 'int default NULL';
    $ab_datatype[5]['dspname'] = 'Integer numbers';
    $ab_datatype[6]['type'] = 'decimal(10,2) default NULL';
    $ab_datatype[6]['dspname'] = 'Decimal numbers';
    $ab_datatype[7]['type'] = 'date default NULL';
    $ab_datatype[7]['dspname'] = 'Date';
    $ab_datatype[8]['type'] = 'dropdown';
    $ab_datatype[8]['dspname'] = 'Dropdown List';
    $ab_datatype[9]['type'] = 'tinyint default NULL';
    $ab_datatype[9]['dspname'] =  'Blank line';
    $ab_datatype[10]['type'] = 'smallint default NULL';
    $ab_datatype[10]['dspname'] =  'Horizontal rule';

    if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $ab_datatype);
    } else {
        return ($ab_datatype);
    }
}