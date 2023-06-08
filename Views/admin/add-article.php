<form action="create-post" method="POST" enctype="multipart/form-data">
  <input class="form-control my-4" type="text" name="title" placeholder="Title" required>
  <input class="form-control my-4" type="text" name="slug" placeholder="Slug" >

  <textarea class="form-control my-4" name="body" placeholder="Content" required></textarea>
  <input class="form-control my-4" type="file" name="image" placeholder="Image" >
  <input type="hidden" name="token" value="<?= $token ?>">
  <select class="form-control my-4" name="category" id="">
  <option value="">Select Category</option>

    <?php 
    foreach ($cats as $cat) { ?>
      <option value="<?= htmlspecialchars($cat->catId, ENT_QUOTES, 'UTF-8');?>"><?= htmlspecialchars($cat->catName, ENT_QUOTES, 'UTF-8') ?></option>
      <?php } ?>
  </select>


  <button class="btn btn-primary" value="submitted" name="submit" type="submit">Create Post</button>
</form>
