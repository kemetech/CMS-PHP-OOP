<?php
namespace App\Controllers;

use App\Models\Post;
use Core\Template;
use Core\Database\MySqlConnection;
use App\Repositories\PostRepo;
use Core\Auth\Session;

class HomeController 
{
    protected $pdo;

    public function __construct()
    {
        // Constructor code here (if any)
    }
    
    public function index()
    {
        // Create a new instance of the Template class with the template name 'home'
        $template = new Template('home');
        
        // Assign a value to the 'pageTitle' variable in the template
        $template->assign('pageTitle', 'HOME');

        // Render the template
        $template->render();
    }
}
