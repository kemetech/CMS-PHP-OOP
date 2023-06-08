

  <div class="row g-5">
    <div class="col-md-8">
      <?php
        foreach ($posts as $post){?>
                <article class="blog-post">
                    <a href="articles/<?= $post->postSlug ?>"><h3 class="blog-post-title mb-1"><?= $post->postTitle ?></h3></a>
        
        <p class="blog-post-meta"><?= $post->postCreatedAt ?> </p>

        <p><?= $post->postBody ?></p>
        
      </article>

            <?php
        }
      ?>

      <nav class="blog-pagination" aria-label="Pagination">
        <a class="btn btn-outline-primary rounded-pill" href="#">Older</a>
        <a class="btn btn-outline-secondary rounded-pill disabled">Newer</a>
      </nav>
    </div>
    
