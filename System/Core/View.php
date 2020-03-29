<?php

/**
 * File: View.php.
 * Author: Self
 * Standard: PSR-2.
 * Do not change codes without permission.
 * Date: 2/22/2020
 */

namespace System\Core;

use App\Auth;

class View
{
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(TEMP_DIR);
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('current_user', Auth::getUser());
        }

        echo $twig->render($template, $args);
    }
}
