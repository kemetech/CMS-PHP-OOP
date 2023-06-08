    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Dashboard</h1>
      <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
          <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
          <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1">
          <svg class="bi"><use xlink:href="#calendar3"/></svg>
          This week
        </button>
      </div>
    </div>
    <span class="error"><?php if (!empty($errors)){
        foreach ($errors as $error){
          echo $error;
        }
      }?></span>

    <h2>Blog Articles</h2>
    <div class="table-responsive small">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Author</th>
            <th scope="col">Category</th>
            <th scope="col">Created At</th>
            <th scope="col">Control</th>
          </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($posts as $post){ ?>
            <tr>
                <td><?= $post->postId ?></td>
                <td><?= $post->postTitle ?></td>
                <td><?= $post->userId ?></td>
                <td><?= $post->catId ?></td>
                <td><?= $post->postCreatedAt ?></td>
                <td><a style="color: black;" href="article/<?= $post->postSlug ?>">View</a>
                <a style="color: green;" href="edit-post/<?= $post->postId ?>">Edit</a>
                <form method="POST" action="delete-post/<?= $post->postId ?>" >
                  <button type="submit" name="submit" value="submitted">Delete</button>
                </form>
            </tr>  
            <?php
            }
        ?>             
        </tbody>
      </table>
    </div>
    <a href="create-post" class="btn btn-primary">Add Post</a>
  