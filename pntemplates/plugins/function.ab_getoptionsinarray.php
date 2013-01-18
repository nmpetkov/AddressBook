<?php
function smarty_function_ab_getoptionsinarray ($params, &$smarty)
{
    if (!$params['option']) {
        exit ('smarty_function_ab_getoptionsinarray: invalid option passed ...');
    }

    $drop_array = explode(",",$params['option']);
    for($x=0;$x<count($drop_array);$x++){
        $dropdata[] = $drop_array[$x];
    }

    $smarty->assign($params['assign'], $dropdata);
}
