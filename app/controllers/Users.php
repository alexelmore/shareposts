<?php

class Users extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model(('User'));
    }

    public function register()
    {
        // Check for posts
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Process Form

            //Sanitized POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter in your email';
            } else {
                //Check email
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            // Validate name 
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter in your name';
            }

            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter in your password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least six characters long';
            }

            // Validate Confirm password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm your password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure errors are empty
            if (
                empty($data['email_err']) && empty($data['name_err']) &&
                empty($data['password_err']) && empty($data['confirm_password_err'])
            ) {
                // Validated

                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register User
                if ($this->userModel->register($data)) {
                    flash('register_success', 'You are registered and can now login');
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/register', $data);
            }
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
            //Sanitized POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [

                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',

            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter in your email';
            }
            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter in your password';
            }

            // Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])) {
                //User found
            } else {
                // No user found
                $data['email_err'] = 'No user found.';
            }

            // Make sure errors are empty
            if (
                empty($data['email_err']) &&
                empty($data['password_err'])
            ) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('users/login', $data);
                }
            } else {

                // Load view with errors
                $this->view('users/login', $data);
            }
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

    // Create user session
    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        redirect('posts');
    }

    // Create logout method that consists of getting rid of session variables
    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);

        session_destroy();
        redirect('users/login');
    }
}
