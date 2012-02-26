<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?=$title?></title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?=$_ENV['app']['baseURL']?>Layout/Screen.css">
  </head>
  <body>
  
    <header class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="#">BlogApp</a>
        </div>
      </div>
    </header>
    
    <div class="container">
      <div class="content">
<? include($contentsFile) ?>
      </div>
    </div>
    
    <footer>
      <p>2012 &copy; Rasmussen I/O</p>
    </footer>
    
  </body>
</html>