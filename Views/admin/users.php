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

    <h2>Users</h2>
    <div class="table-responsive small">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Status</th>
            <th scope="col">Role</th>
            <th scope="col">Registered At</th>
            <th scope="col">Control</th>
          </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($users as $user){ ?>
            <tr>
                <td><?= $user->userId ?></td>
                <td><?= $user->userFirstName . ' ' . $user->userLastName ?></td>
                <td><?= $user->userStatus ?></td>
                <td><?= $user->userRole ?></td>
                <td><?= $user->userCreatedAt ?></td>
                <td>
                <a style="color: green;" href="edit-user/<?= $user->userId ?>">Edit</a>
                <form method="POST" action="delete-user/<?= $user->userId ?>" >
                  <button type="submit" name="submit" value="submitted">Delete</button>
                </form>
            </td>
            </tr>  
            <?php
            }
        ?>             
        </tbody>
      </table>
    </div>
    <a href="register" class="btn btn-primary">Add User </a>
  