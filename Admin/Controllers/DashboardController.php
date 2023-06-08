<?php

namespace Admin\Controllers;

use Core\Database\Repositories\PostRepo;
use Core\Database\Repositories\UserRepo;
use Core\Redirect;
use Core\Template;
use Admin\Models\User;
use Admin\Auth\AuthService;
use Admin\Auth\Session;

/**
 * The controller responsible for handling the dashboard-related tasks.
 */
class DashboardController extends AdminController
{

    /**
     * Constructs a new instance of the DashboardController.
     */
    public function __construct()
    {
        parent::__construct(['admin', 'author', 'moderator']);
    }

    /**
     * Displays the dashboard index page.
     */
    public function index()
    {
        $posts = $this->postRepo->findAll();

        $template = new Template('admin/main', 'dashboard');
        
        $template->assign('pageTitle', 'Dashboard');
        $template->assign('userName', $this->userName);
        $template->assign('posts', $posts);
        
        // Check if there are any error or message flash data
        $err = Session::has('error') ? Session::flash('error') : '';
        $msg = Session::has('message') ? Session::flash('message') : '';

        $template->assign('errors', $err);
        $template->assign('messages', $msg);
        
        $template->render();
    }
}
