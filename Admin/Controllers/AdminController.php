<?php
namespace Admin\Controllers;

use Core\Database\Repositories\PostRepo;
use Core\Database\Repositories\UserRepo;
use Core\Redirect;
use Core\Template;
use Admin\Models\User;
use Admin\Auth\AuthService;
use Admin\Auth\Session;
use App\Models\Category;
use App\Models\Post;
use Core\Database\Repositories\CatRepo;

class AdminController
{
    protected $authService;
    protected $user;
    protected $userRepo;
    protected $postRepo;
    protected $userName;
    protected $requiredRules = ['admin'];
    protected $post;
    protected $catRepo;
    protected $cat;

    /**
     * AdminController constructor.
     * @param array $requiredRules The required user roles for accessing the controller's actions.
     */
    public function __construct($requiredRules = [])
    {
        $this->authService = new AuthService();
        $this->requiredRules = $requiredRules;
        $this->userRepo = new UserRepo();
        $this->user = new User();
        $this->postRepo = new PostRepo();
        $this->post = new Post();
        $this->catRepo = new CatRepo();
        $this->cat = new Category();

        Session::start();

        if (!$this->authService->isLoggedIn()) {
            Redirect::to('login');
        }

        $id = Session::get('LoggedIn');
        $this->userRepo->findBy($this->user, $id);
        $this->userName =  $this->user->getFirstName() . ' ' . $this->user->getLastName();

        if (!$this->authService->isActive($this->user)){
            die('Your account is ' . $this->user->getStatus() . ', please contact the administrator.');
        }

        if (!$this->authService->isAuthorized($this->user, $this->requiredRules)){
            die('You have no permission to access this area.');
        }
    }

}
