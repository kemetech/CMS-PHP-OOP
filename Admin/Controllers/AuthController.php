<?php

namespace Admin\Controllers;

use Core\Template;
use Core\Input;
use Core\Redirect;
use Admin\Auth\AuthService;
use Admin\Auth\Session;
use Admin\Models\User;
use Core\Utils\CsrfToken;

/**
 * The controller responsible for handling authentication-related tasks.
 */
class AuthController
{
    private $authService;
    private $user;
    private $requiredRules;

    /**
     * Constructs a new instance of the AuthController.
     */
    public function __construct()
    {
        $this->authService = new AuthService;
        $this->requiredRules = ['admin', 'author', 'moderator'];
        $this->user = new User;
        Session::start();
    }

    /**
     * Handles the login process.
     */
    public function login()
    {
        if (Input::exists() && Input::get('submit') === 'submitted') {
            $password = Input::get('password');
            $email = Input::get('email', 'email');
            $token = Input::get('token');
            
            // Attempt to log in the user
            if ($this->authService->login($this->user, $email, $password, $token)) {
                if ($this->authService->isLoggedIn()) {
                    // Check if the user's account is active
                    if (!$this->authService->isActive($this->user)) {
                        die('Your account is ' . $this->user->getStatus() . ', please contact the administrator');
                    };

                    // Check if the user is authorized to access the required rules
                    if (!$this->authService->isAuthorized($this->user, $this->requiredRules)) {
                        die('Unauthorized access, You have no permission to access this page');  
                    };
                    Redirect::to('dashboard');
                } else {
                    Redirect::to('/');
                };

            } else {
                Redirect::to('login');
            }

        } else {
            Redirect::to('login');
        };  
    }

    /**
     * Handles the registration process.
     */
    public function register()
    {   
        if (Input::exists() && Input::get('submit') === 'submitted') {
            $fname = Input::get('fname');
            $lname = Input::get('lname');
            $password = Input::get('password');
            $passwordConfirm = Input::get('passwordconfirm');
            $email = Input::get('email', 'email');
            $token = Input::get('token');

            // Attempt to register the user
            $register = $this->authService->register($this->user, $fname, $lname, $password, $passwordConfirm, $email, $token);
      
            if ($register) {
                $msg[] = 'User added successfully.';
                Session::flash('message', $msg);
                Redirect::to('users');
            } else {
                Redirect::to('register');
            }

        } else {
            Redirect::to('register');
        };        
    }

    /**
     * Logs out the user.
     */
    public function logout()
    {
        $this->authService->logout();
        Redirect::to('login');
    }

    /**
     * Displays the registration form.
     */
    public function registerForm()
    {
        $token = CsrfToken::generate();
        $template = new Template('signup', 'auth');
        $template->assign('token', $token);
        $template->assign('pageTitle', 'SIGNUP');

        // Check if there are any error or message flash data
        $err = Session::has('error') ? Session::flash('error') : '';
        $msg = Session::has('message') ? Session::flash('message') : '';

        $template->assign('errors', $err);
        $template->assign('messages', $msg);

        $template->render();
    }

    /**
     * Displays the login form.
     */
    public function loginForm()
    {
        $token = CsrfToken::generate();
        $template = new Template('login', 'auth');
        $template->assign('token', $token);
        $template->assign('pageTitle', 'LOGIN');
        
        // Check if there are any error or message flash data
        $err = Session::has('error') ? Session::flash('error') : '';
        $msg = Session::has('message') ? Session::flash('message') : '';

        $template->assign('errors', $err);
        $template->assign('messages', $msg);
        
        $template->render();
    }
}
