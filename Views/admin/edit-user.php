<main class="container row p-4 m-4">
  <class class="row">
    <form class="col-md-8" action="<?= $id ?>" method="post">
      <h1 class="h3 mb-3 fw-normal">Edit User</h1>
      <div class="row gx-1 my-1">
        <div class="form-floating col-md-6">
          <input type="text" class="form-control" id="floatingInput" placeholder="first name" name="fname" value="<?= $fname ?>">
          <label for="floatingInput">First Name</label>
        </div>
        <div class="form-floating col-md-6">
          <input type="text" class="form-control" id="floatingInput" placeholder="last name" name="lname" value="<?= $lname ?>">
          <label for="floatingInput">Last Name</label>
        </div>
      </div>
      <div class="row gx-1 my-4">
        <div class="form-floating col-md-6">
            <select name="status" id="">
                <option value="<?= $status ?>"><?= $status ?></option>
                <option value="active">active</option>
                <option value="pending">pending</option>
                <option value="suspended">suspended</option>
            </select>
        </div>
        <div class="form-floating col-md-6">
            <select name="role" id="">
                <option value="<?= $role ?>"><?= $role ?></option>
                <option value="admin">admin</option>
                <option value="author">author</option>
                <option value="public">public</option>
            </select>
        </div>
      </div>
  
      <div class="form-floating my-1 ">
        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" value="<?= $email ?>">
        <label for="floatingInput">Email address</label>
      </div>
      <input type="hidden" value="<?= $pass ?>" name="oldPass">
      <div class="row gx-1 my-1">
        <div class="form-floating col-md-6">
          <input type="password" class="form-control" id="floatingInput" placeholder="new password" name="password">
          <label for="floatingInput">New Password</label>
        </div>
        <div class="form-floating col-md-6">
          <input type="password" class="form-control" id="floatingPassword" placeholder="Confirm Password" name="passwordconfirm">
          <label for="floatingPassword">Confirm Password</label>
        </div>
      </div>  
      <input type="hidden" value="<?= $token ?>" name="token">
      <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit" value="submitted">Edit</button>
    </form>
    <div class="col-md-4 my-5">
      <span class="error"> <?= $errors ?></span>
    </div>
  </class>
</main>
  
  