<?php

namespace Admin\Controllers;

use Core\Database\Repositories\PostRepo;
use Core\Database\Repositories\UserRepo;
use Core\Redirect;
use Core\Template;
use Admin\Models\User;
use Admin\Auth\AuthService;
use Admin\Auth\Session;
use Core\Config\MysqlConfig;
use Core\Database\MySqlConnection;
use Core\Input;
use Core\Sanitize;
use Core\Upload;
use Core\Utils\CsrfToken;
use Core\Utils\HashUtils;
use Core\Validation\Validator;

/**
 * The controller responsible for managing the admin user functionality.
 */
class AdminUserController extends AdminController
{

    /**
     * Constructs a new instance of the AdminUserController.
     */
    public function __construct()
    {
        parent::__construct(['admin']);
    }

    /**
     * Displays the list of users in the admin panel.
     */
    public function index()
    {
        $userRepo = new UserRepo;
        $users = $userRepo->findAll();

        $template = new Template('admin/users', 'dashboard');
        
        $template->assign('pageTitle', 'Users');
        $template->assign('userName', $this->userName);
        $template->assign('users', $users);
        
        $err = Session::has('error')? Session::flash('error') : '';
        $msg = Session::has('message')? Session::flash('message') : '';

        $template->assign('errors', $err);
        $template->assign('messages', $msg);
        
        $template->render();

    }

    /**
     * Displays the edit user page in the admin panel.
     *
     * @param int $id The ID of the user to be edited.
     */
    public function edit($id)
    {
        $userRepo = new UserRepo;
        $user = new User;

        $data = $userRepo->findBy($user, $id);
        $fname = $user->getFirstName();
        $lname = $user->getLastName();
        $pass = $user->getPassword();
        $email = $user->getEmail();
        $status = $user->getStatus();
        $role = $user->getRole();
        $id = $user->getId();

        if ($data) {

            $token = CsrfToken::generate();

            $template = new Template('admin/edit-user', 'dashboard');
            $template->assign('pageTitle', 'Edit User');
            $template->assign('token', $token);
            $template->assign('fname', $fname);
            $template->assign('lname', $lname);
            $template->assign('email', $email);
            $template->assign('status', $status);
            $template->assign('role', $role);
            $template->assign('pass', $pass);
            $template->assign('id', $id);


            $err = Session::has('errors') ? Session::flash('errors') : '';
            $msg = Session::has('message') ? Session::flash('message') : '';

            $template->assign('errors', $err);
            $template->assign('messages', $msg);

            $template->render();
        }
    }

    /**
     * Updates the edited user in the database.
     *
     * @param int $id The ID of the user to be updated.
     */
    public function update($id)
    {
        if (Input::exists() && Input::get('submit') === 'submitted') {

            $fname = Input::get('fname');
            $lname = Input::get('lname');
            $email = Input::get('email');
            $status = Input::get('status');
            $role = Input::get('role');
            $password = Input::get('password');
            $passwordconfirm = Input::get('passwordconfirm');
            $oldPassword = Input::get('oldPass');
            $token = Input::get('token');

            CsrfToken::check($token);

            $data = [
                'first name' => $fname,
                'last name' => $fname,
                'email' => $email,
                'password' => $password,
            ];
            
            $rules = [
                'first name' => 'required|text',
                'last name' => 'required|text',
                'email' => 'required|email',
            ];

            if (!empty($password)){
                $rules['password'] = 'required|password';

                if ($password !== $passwordconfirm){
                    $errors[] = 'Password and password confirmation do not match';
                    Session::flash('error', $errors);
                    return false;
                }
                $hashedPass = HashUtils::hashPassword($password);

            } else {
                $hashedPass = $oldPassword;
            }
          
            $validator = new Validator($data, $rules);
            if (!$validator->validate()) {
                $errors = $validator->getErrors();
                Session::flash('error', $errors);
                return false;
            }

            if (!$this->userRepo->uniqueUpdate('userEmail', $email, 'userId', $id)) {
                $errors[] = 'There is another account using this email';
                Session::flash('errors', $errors);
                Redirect::to('edit-user/' . $id);
                return false;
            }

            $user = new User;          
            // Store data in the user model
            $user->setFirstName($fname);
            $user->setLastName($lname);
            $user->setEmail($email);
            $user->setPassword($hashedPass);
            $user->setStatus($status);
            $user->setRole($role);
          
            $userUpdate = $this->userRepo->update($user, $id);

            if ($userUpdate) {
                $msg[] = 'User has been updated successfully.';
                Session::flash('message', $msg);
                Redirect::to('/users');
            } else {
                $msg[] = 'User update failure.';
                Session::flash('errors', $msg);
                Redirect::to('edit-user/' . $id);
            }            

        }
        Redirect::to('edit-post/' . $id);
    }

    /**
     * Deletes a user from the database.
     *
     * @param int $id The ID of the user to be deleted.
     */
    public function delete($id)
    {

        if (Input::exists() && Input::get('submit') === 'submitted') {

            $userRepo = new UserRepo;
            $deleteUser = $userRepo->delete('userId', $id);

            if ($deleteUser) {
                $msg[] = 'User has been deleted successfully.';
                Session::flash('message', $msg);
                Redirect::to('/users');
            } else {
                $msg[] = 'User deletion failure.';
                Session::flash('errors', $msg);
                Redirect::to('/users');
            }
        }
        Redirect::to('/users');
    }
}



