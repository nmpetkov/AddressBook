<?php

function smarty_modifier_addressbook_img($string,$format)
{

    switch ($format)
    {
        case 'tmb':
            $string = substr_replace($string, 'tmb.png',strlen($string)-7 , strlen($string));
            break;
        case 'pre':
            $string = substr_replace($string, 'pre.jpg',strlen($string)-7 , strlen($string));
            break;
        case 'org':
            $string = substr_replace($string, 'org.jpg',strlen($string)-7 , strlen($string));
            break;
    }

    return $string;
}