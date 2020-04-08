<?php

/**
 * File: PasswordController.php.
 * Author: Self
 * Standard: PSR-12.
 * Do not change codes without permission.
 * Date: 3/24/2020
 */

namespace App\Controller;

use App\Model\UserModel;
use System\Core\BaseController;
use System\Core\View;

class PasswordController extends BaseController
{
    public function forgotAction()
    {
        View::renderTemplate('Password/forgot.twig');
    }

    public function requestResetAction()
    {
        UserModel::sendPasswordReset($this->request->post['email']);
        View::renderTemplate('Password/reset_requested.twig');
    }

    public function reset($key)
    {
        echo $key;
    }
}
