<main class="container row p-4 m-4">
  <span class="success"><?= $messages ?></span>
  <class class="row">
    <form class="col-md-8" action="login" method="post">
      <h1 class="h3 mb-3 fw-normal">Login</h1>
  
      <div class="form-floating my-1 ">
        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
        <label for="floatingInput">Email address</label>
      </div>
      <div class="form-floating col-md-6">
        <input type="password" class="form-control" id="floatingInput" placeholder="password" name="password">
        <label for="floatingInput">Password</label>
      </div>
      <div class="checkbox mb-3">
          <input type="checkbox" value="remember" name="remember" id="remember">
          <label for="remember">Remember me</label>
      </div>
      <input type="hidden" value="<?= $token ?>" name="token">
      <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit" value="submitted">Sign in</button>
    </form>
    <div class="col-md-4 my-5">
      <span class="error"><?php if (!empty($errors)){
        foreach ($errors as $error){
          echo $error;
        }
      }?>
    </div>
  </class>
</main>
  
  