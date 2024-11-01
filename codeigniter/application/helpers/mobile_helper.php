<?php

if ( ! function_exists('lang_check'))
{
    function lang_check($line, $id = '')
    {
        $r_line = lang($line, $id);

        if(empty($r_line))
            $r_line = $line;
        
        return $r_line;
    }
}










?>