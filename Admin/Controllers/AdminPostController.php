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
use Core\Validation\Validator;

class AdminPostController extends AdminController
{

    /**
     * Initializes the AdminPostController.
     */
    public function __construct()
    {
        parent::__construct(['admin', 'author', 'moderator']);
    }

    /**
     * Displays the index page of posts in the admin panel.
     */
    public function index()
    {
        $posts = $this->postRepo->findAll();

        $template = new Template('admin/posts', 'dashboard');
        
        $template->assign('pageTitle', 'Posts');
        $template->assign('userName', $this->userName);
        $template->assign('posts', $posts);
        
        $err = Session::has('error') ? Session::flash('error') : '';
        $msg = Session::has('message') ? Session::flash('message') : '';

        $template->assign('errors', $err);
        $template->assign('messages', $msg);
        
        $template->render();
    }

    /**
     * Displays the create post page in the admin panel.
     */
    public function create()
    {
        $cats = $this->catRepo->findAll();
        $token = CsrfToken::generate();

        $template = new Template('admin/add-article', 'dashboard');
        $template->assign('pageTitle', 'Add Article');
        $template->assign('token', $token);
        $template->assign('cats', $cats);

        $err = Session::has('errors') ? Session::flash('errors') : '';
        $msg = Session::has('message') ? Session::flash('message') : '';

        $template->assign('errors', $err);
        $template->assign('messages', $msg);

        $template->render();
    }

    /**
     * Saves the newly created post to the database.
     */
    public function save()
    {
        if (Input::exists() && Input::get('submit') === 'submitted') {

            // Sanitize form data
            $title = Input::get('title');
            $slug = Input::get('slug');
            $body = Input::get('body');
            $cat = Input::get('category', 'number');
            $token = Input::get('token');

            if (Input::exists('file')) {
                $imageInput = Input::get('image', null, 'file');
                $upload = new Upload($imageInput);
                $image = $upload->upload();
                if (!$image) {
                    Redirect::to('create-post');
                }
            }

            if (empty($slug)) {
                $slug = str_replace(' ', '-', $title);
            }

            CsrfToken::check($token);

            $data = [
                'title' => $title,
                'slug' => $slug,
                'body' => $body,
                'image' => $image,
                'cat' => $cat,
            ];
            $rules = [
                'title' => 'required|max:100',
                'slug' => 'required',
                'body' => 'required|min:100|max:20000',
                'image' => 'required',
                'cat' => 'required',
            ];

            $validator = new Validator($data, $rules);
            if (!$validator->validate()) {
                $errors = $validator->getErrors();
                Session::flash('errors', $errors);
                Redirect::to('create-post');
            }

            if (!$this->postRepo->unique('postTitle', $title)) {
                $errors[] = 'There is another article using this title';
                Session::flash('errors', $errors);
                return false;
            }

            if (!$this->postRepo->unique('postSlug', $slug)) {
                $errors[] = 'There is another article using this slug';
                Session::flash('errors', $errors);
                return false;
            }

            $this->post->setTitle($title);
            $this->post->setSlug($slug);
            $this->post->setBody($body);
            $this->post->setImage($image);
            $this->post->setUserId($this->user->getId());
            $this->post->setCatId($cat);

            $createdPost = $this->postRepo->save($this->post);

            if ($createdPost) {
                $msg[] = 'Your post has been created successfully.';
                Session::flash('message', $msg);
                Redirect::to('dashboard');
            } else {
                Redirect::to('create-post');
            }
        }
        Redirect::to('create-post');
    }

    /**
     * Displays the edit post page in the admin panel.
     *
     * @param int $id The ID of the post to be edited.
     */
    public function edit($id)
    {
        $data = $this->postRepo->findByJoinCat($this->post, $this->cat, $id);
        $title = $this->post->getTitle();
        $body = $this->post->getBody();
        $slug = $this->post->getSlug();
        $image = $this->post->getImage();
        $postCat = $this->cat->getName();
        $postCatId = $this->post->getCatId();
        $id = $this->post->getId();

        if ($data) {

            $cats = $this->catRepo->findAll();
            $token = CsrfToken::generate();

            $template = new Template('admin/edit-article', 'dashboard');
            $template->assign('pageTitle', 'Edit Article');
            $template->assign('token', $token);
            $template->assign('cats', $cats);
            $template->assign('title', $title);
            $template->assign('slug', $slug);
            $template->assign('image', $image);
            $template->assign('postCat', $postCat);
            $template->assign('body', $body);
            $template->assign('postCatId', $postCatId);
            $template->assign('id', $id);


            $err = Session::has('errors') ? Session::flash('errors') : '';
            $msg = Session::has('message') ? Session::flash('message') : '';

            $template->assign('errors', $err);
            $template->assign('messages', $msg);

            $template->render();
        }
    }

    /**
     * Updates the edited post in the database.
     *
     * @param int $id The ID of the post to be updated.
     */
    public function update($id)
    {
        if (Input::exists() && Input::get('submit') === 'submitted') {

            // Sanitize form data
            $title = Input::get('title');
            $slug = Input::get('slug');
            $body = Input::get('body');
            $cat = Input::get('category', 'number');
            $token = Input::get('token');

            if (Input::exists('file')) {
                $imageInput = Input::get('image', null, 'file');
                $upload = new Upload($imageInput);
                $image = $upload->upload();
                if (!$image) {
                    Redirect::to("edit-post/$id");
                }
            }

            if (empty($slug)) {
                $slug = str_replace(' ', '-', $title);
            }

            CsrfToken::check($token);

            $data = [
                'title' => $title,
                'slug' => $slug,
                'body' => $body,
                'image' => $image,
                'cat' => $cat,
            ];
            $rules = [
                'title' => 'required|max:100',
                'slug' => 'required',
                'body' => 'required|min:100|max:20000',
                'image' => 'required',
                'cat' => 'required',
            ];

            $validator = new Validator($data, $rules);
            if (!$validator->validate()) {
                $errors = $validator->getErrors();
                Session::flash('errors', $errors);
                Redirect::to("edit-post/$id");
            }

            if (!$this->postRepo->unique('postTitle', $title, $id)) {
                $errors[] = 'There is another article using this title';
                Session::flash('errors', $errors);
                return false;
            }

            if (!$this->postRepo->unique('postSlug', $slug, $id)) {
                $errors[] = 'There is another article using this slug';
                Session::flash('errors', $errors);
                return false;
            }

            $this->post->setId($id);
            $this->post->setTitle($title);
            $this->post->setSlug($slug);
            $this->post->setBody($body);
            $this->post->setImage($image);
            $this->post->setUserId($this->user->getId());
            $this->post->setCatId($cat);

            $updatedPost = $this->postRepo->update($this->post, $id);

            if ($updatedPost) {
                $msg[] = 'Your post has been updated successfully.';
                Session::flash('message', $msg);
                Redirect::to('dashboard');
            } else {
                Redirect::to("edit-post/$id");
            }
        }
        Redirect::to('dashboard');
    }

    /**
     * Deletes a post from the database.
     *
     * @param int $id The ID of the post to be deleted.
     */
    public function delete($id)
    {
        $deletedPost = $this->postRepo->delete('postId', $id);

        if ($deletedPost) {
            $msg[] = 'The post has been deleted successfully.';
            Session::flash('message', $msg);
            Redirect::to('dashboard');
        } else {
            Redirect::to('dashboard');
        }
    }
}

?>
