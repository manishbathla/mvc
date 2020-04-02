<?php

namespace App\Controller;

use System\Core\BaseController;
use System\Core\View;
use App\Model\UserModel;

/**
 * Password controller
 *
 * PHP version 7.0
 */

class PasswordController extends BaseController
{
    /**
     * Show the forgotten password page
     *
     * @return void
     */
    public function forgotAction()
    {
        View::renderTemplate('Password/forgot.twig');
    }
    /**
     * Send the password reset link to the supplied email
     *
     * @return void
     */
    public function requestResetAction()
    {
        User::sendPasswordReset($_POST['email']);
        View::renderTemplate('Password/reset_requested.twig');
    }
}
