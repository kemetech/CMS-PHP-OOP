<img src="<?= url('Public/uploads/') . $image ?>" class="img-fluid" alt="Responsive image">

<form action="<?= $id ?>" method="POST" enctype="multipart/form-data">
  <input class="form-control my-4" type="text" name="title" placeholder="Title" required value="<?= $title ?>">
  <input class="form-control my-4" type="text" name="slug" placeholder="Slug" value="<?= $slug ?>">

  <textarea class="form-control my-4" name="body" placeholder="Content" required><?= $body ?></textarea>
  <input class="form-control my-4" type="file" name="image" placeholder="Image" >
  <input type="hidden" name="current-pic" value="<?= $image ?>">
  <input type="hidden" name="token" value="<?= $token ?>">
  <select class="form-control my-4" name="category" id="" >
  <option value="<?= $postCatId ?>"><?= $postCat ?></option>

    <?php 
    foreach ($cats as $cat) { ?>
      <option value="<?= htmlspecialchars($cat->catId, ENT_QUOTES, 'UTF-8');?>"><?= htmlspecialchars($cat->catName, ENT_QUOTES, 'UTF-8') ?></option>
      <?php } ?>
  </select>


  <button class="btn btn-primary" value="submitted" name="submit" type="submit">Update Post</button>
</form>
