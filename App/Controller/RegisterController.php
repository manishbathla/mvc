<?php

/**
 * File: RegisterController.php.
 * Author: Self
 * Standard: PSR-12.
 * Do not change codes without permission.
 * Date: 3/24/2020
 */

namespace App\Controller;

use System\Core\BaseController;
use System\Core\View;

class RegisterController extends BaseController
{
    private $error = array();

    public function indexAction()
    {
        if ($this->request->server['REQUEST_METHOD'] === 'POST' && $this->validate()) {
            echo $this->request->post['first_name'];
            echo $this->request->post['last_name'];
            echo $this->request->post['mobile_number'];
            echo $this->request->post['email'];
            echo $this->request->post['password'];
        }

        $data['error_first_name'] = $this->error['first_name'] ?? '';
        $data['error_last_name'] = $this->error['last_name'] ?? '';
        $data['error_mobile_number'] = $this->error['mobile_number'] ?? '';
        $data['error_email'] = $this->error['email'] ?? '';
        $data['error_password'] = $this->error['password'] ?? '';

        $data['base_url'] = BASE_URL;

        View::renderTemplate('register/register.twig', $data);
    }

    protected function validate()
    {
        if ((strlen(trim($this->request->post['first_name'] < 1 ))) || (strlen(trim($this->request->post['first_name'] > 20)))) {
            $this->error['first_name'] = 'Required!';
        }

        if ((strlen(trim($this->request->post['last_name'] < 1 ))) || (strlen(trim($this->request->post['last_name'] > 20)))) {
            $this->error['last_name'] = 'Required!';
        }

        if (empty($this->request->post['mobile_number'])) {
            $this->error['mobile_number'] = 'Required!';
        }

        if (empty($this->request->post['email'])) {
            $this->error['email'] = 'Required!';
        }

        if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = 'Invalid email!';
        }

        if (empty($this->request->post['password'])) {
            $this->error['password'] = 'Required!';
        }

        return !$this->error;
    }
}