<?php

/**
 * Description of MY_Composer
 *
 * @author Rana
 */
class MY_Composer
{
    function __construct()
    {
        if(file_exists(FCPATH.'/vendor/autoload.php'))
            include(FCPATH.'/vendor/autoload.php');
        else
        {
            exit('Composer loading failed');
        }
    }
}

?>