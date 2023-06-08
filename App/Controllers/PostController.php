<?php
namespace App\Controllers;

use App\Models\Category;
use App\Models\Post;
use Core\Template;
use Core\Database\MySqlConnection;
use Core\Database\Repositories\PostRepo;

class PostController 
{
    private $postRepo;
    private $post;
    private $cat;

    public function __construct()
    {
        // Create instances of the PostRepo, Post, and Category classes
        $this->postRepo = new PostRepo;
        $this->post = new Post;
        $this->cat = new Category;
    }

    public function index()
    {
        // Get all posts using the PostRepo's findAll method
        $posts = $this->postRepo->findAll();

        // Create a new instance of the Template class with the template name 'posts' and layout 'default'
        $template = new Template('posts', 'default');

        // Assign values to the template variables
        $template->assign('pageTitle', 'Posts');
        $template->assign('posts', $posts);

        // Render the template
        $template->render();
    }

    public function view($slug)
    {
        // Find a post by its slug and join with the category using the PostRepo's findByJoinCat method
        $data = $this->postRepo->findByJoinCat($this->post, $this->cat, $slug, 'postSlug');

        // Get the post details from the Post model
        $title = $this->post->getTitle();
        $body = $this->post->getBody();
        $slug = $this->post->getSlug();
        $image = $this->post->getImage();
        $postCat = $this->cat->getName();
        $date = $this->post->getCreateDate();

        // Create a new instance of the Template class with the template name 'single-post' and layout 'default'
        $template = new Template('single-post', 'default');

        // Assign values to the template variables
        $template->assign('pageTitle', 'Posts');
        $template->assign('title', $title);
        $template->assign('body', $body);
        $template->assign('cat', $postCat);
        $template->assign('image', $image);
        $template->assign('date', $date);

        // Render the template
        $template->render();
    }
}
