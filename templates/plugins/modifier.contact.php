<?php

function smarty_modifier_contact($string)
{
    if (ereg("@",$string)) {
        $string = '<a href="mailto:'. $string . '">' . $string . '</a>';
    } else {
        $string = preg_replace_callback("#(\w+)://([\w\+\-\@\=\?\.\%\/\:\&\;~\|\#]+)(\.)?#",'_smarty_modifier_contact_callback',$string);
    }
    return DataUtil::formatForDisplayHTML($string);
}

function _smarty_modifier_contact_callback($matches)
{
    return '<a href="' . $matches[0] . '" target="_blank">' . $matches[0] . '</a>';
}