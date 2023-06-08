<?php

namespace Admin\Auth;

use Core\Database\Repositories\UserRepo;
use Core\Validation\Validator;
use Admin\Models\UserSession;
use Core\Config\AppConfig;
use Core\Cookies;
use Core\Database\Repositories\SessionRepo;
use Core\Redirect;
use Admin\Models\User;
use Core\Sanitize;
use Core\Utils\CsrfToken;
use Core\Utils\HashUtils;

class AuthService {
    private $userRepo;
    protected $con;
    protected $sessionConfig;
    protected $session;

    public function __construct() 
    {
        $this->userRepo = new UserRepo;
    }

    /**
     * Checks if the CSRF token is valid.
     *
     * @param string $token The CSRF token to check.
     * @return bool Returns true if the token is valid, false otherwise.
     */
    public function checkToken($token): bool
    {
        return CsrfToken::check($token);  
    }

    /**
     * Registers a new user.
     *
     * @param User $user The User object to store the registration data.
     * @param string $fname The first name of the user.
     * @param string $lname The last name of the user.
     * @param string $password The user's password.
     * @param string $passwordConfirm The confirmation of the user's password.
     * @param string $email The email address of the user.
     * @param string $token The CSRF token for form validation.
     * @return bool Returns true if the registration is successful, false otherwise.
     */
    public function register(User $user, $fname, $lname, $password, $passwordConfirm, $email, $token)
    {
        $this->checkToken($token);

        // Validate form data
        $data = [
            'first name' => $fname,
            'last name' => $fname,
            'email' => $email,
            'password' => $password,
            'password confirmation' => $passwordConfirm,
        ];

        $rules = [
            'first name' => 'required|text',
            'last name' => 'required|text',
            'email' => 'required|email',
            'password' => 'required|password',
            'password confirmation' => 'required',
        ];

        $validator = new Validator($data, $rules);
        if (!$validator->validate()) {
            $errors = $validator->getErrors();
            Session::flash('error', $errors);
            return false;
        }

        if ($password !== $passwordConfirm){
            $errors[] = 'Password and password confirmation do not match.';
            Session::flash('error', $errors);
            return false;
        }

        if (!$this->userRepo->unique('userEmail', $email)){
            $errors[] = 'This email is already registered. Please use the login page.';
            Session::flash('error', $errors);
            return false;
        }

        // Hash the password
        $hashedPass = HashUtils::hashPassword($password);

        // Store data in the User model
        $user->setFirstName($fname);
        $user->setLastName($lname);
        $user->setEmail($email);
        $user->setPassword($hashedPass);

        $this->userRepo->save($user);
        return true;
    }

    /**
     * Authenticates a user and performs the login process.
     *
     * @param User $user The User object to perform the login.
     * @param string $email The email address of the user.
     * @param string $password The user's password.
     * @param string $token The CSRF token for form validation.
     * @return bool Returns true if the login is successful, false otherwise.
     */
    public function login(User $user, $email, $password, $token)
    {
        $loginAttempts = new LoginAttempts($email);

        if(!$this->checkToken($token)){
            die('CSRF Token validation error');
        };

        $data = [
            'email' => $email,
            'password' => $password,
        ];

        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $validator = new Validator($data, $rules);
        if (!$validator->validate()) {
            $errors = $validator->getErrors();
            Session::flash('error', $errors);
            return false;
        }

        // Validate failed login attempts
        if (!$loginAttempts->validateLoginAttempt($email)) {
            $status = $loginAttempts->getStatus();
            Session::flash('error', $status);
            Redirect::to('login');
        }

        // Validate credentials
        $userData = $this->userRepo->findBy($user, $email, 'userEmail');
        if ($userData) {
            // User found, verify the password
            if (!HashUtils::verifyPassword($password, $user->getPassword())) {
                // Invalid password
                $loginAttempts->incrementFailedLoginAttempts($email);
                $errors[] = 'Invalid password. You have only ' . $loginAttempts->getRemainAttempts($email) . ' login attempts';
                Session::flash('error', $errors);
                return false;
            }
        } else {
            // User not found
            $loginAttempts->incrementFailedLoginAttempts($email);
            $errors[] = 'Invalid email address. You have only ' . $loginAttempts->getRemainAttempts($email) . ' login attempts';
            Session::flash('error', $errors);
            return false;
        }

        // Reset login attempts
        $loginAttempts->resetFailedLoginAttempts($email);

        // Set session variables
        Session::set('LoggedIn', $user->getId());
        Session::regenerateId();

        return true;
    }

    /**
     * Checks if a user is logged in.
     *
     * @return bool Returns true if the user is logged in, false otherwise.
     */
    public function isLoggedIn() 
    {
        // Check if user is logged in and session is valid
        if (Session::has('LoggedIn') && Session::isValidSession()){
            // Check for session hijacking and fixation
            if (Session::get('user_agent') !== $_SERVER['HTTP_USER_AGENT'] || Session::get('ip_address') !== $_SERVER['REMOTE_ADDR']) {
                Session::destroySession();
                die('Session hijacking or fixation detected. Please log in again.');
            }

            // Regenerate session ID periodically
            if (Session::shouldRegenerateId()) {
                Session::regenerateId();
            }

            // Reset session timeout
            $_SESSION['creation_time'] = time();

            return true;
        }
        return false;
    }

    /**
     * Checks if a user is active.
     *
     * @param User $user The User object to check.
     * @return bool Returns true if the user is active, false otherwise.
     */
    public function isActive(User $user) 
    {
        return $user->getStatus() === 'active';
    } 

    /**
     * Checks if a user is authorized based on the required roles.
     *
     * @param User $user The User object to check.
     * @param array $requiredRoles The required roles for authorization.
     * @return bool Returns true if the user is authorized, false otherwise.
     */
    public function isAuthorized(User $user, $requiredRoles) 
    {
        return in_array($user->getRole(), $requiredRoles);
    }

    /**
     * Logs out the user and destroys the session.
     */
    public function logout() 
    {
        // Unset session variables
        Session::delete('LoggedIn');

        Session::destroySession();
        unset($_COOKIE[Session::$sessionName]);
    }
}
