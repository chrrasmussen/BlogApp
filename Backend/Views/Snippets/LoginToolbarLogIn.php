<li class="dropdown" id="login-dropdown">
  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
    Log In <b class="caret"></b>
  </a>
  <form class="dropdown-menu" action="<?=$pageURL?>" method="post">
    <input type="text" name="email" id="login-email" placeholder="Email">
    <input type="password" name="password" id="login-password" placeholder="Password">
    <button type="submit" class="btn btn-primary" onclick="logIn(); return false;">Log In</button>
    <div class="alert alert-error" style="margin: 9px 0 0 0">
      <b>Login failed!</b> Incorrect email or password entered.
    </div>
  </form>
</li>