<?php

class Users extends Controller
{
    public function __construct()
    { }

    public function register()
    {
        // Check for posts
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Process Form
        } else {
            // Init data
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            $this->view('users/register', $data);
        }
    }


    public function login()
    {
        // Check for posts
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Process Form
        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];

            // Load view
            $this->view('users/login', $data);
        }
    }
}
