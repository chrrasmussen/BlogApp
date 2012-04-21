<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    
    <!-- RSS -->
    <link rel="alternate" title="BlogApp RSS-feed" href="<?=App::getBaseURL()?>/rss.php" type="application/rss+xml">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="<?=App::getBaseURL()?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?=App::getBaseURL()?>/assets/css/screen.css">
    <link rel="stylesheet" type="text/css" href="<?=App::getBaseURL()?>/assets/css/bootstrap-responsive.min.css">
  </head>
  <body>
  
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          
          <a class="brand" href="<?=App::getBaseURL()?>" onclick="navigateToHome(); return false;">BlogApp</a>
          
          <div class="nav-collapse">
            <ul class="nav" id="login-toolbar">
<?=App::getLoginToolbar();?>
            </ul>
            
            <ul class="nav pull-right" id="edit-post-toolbar">
              <li>
                <div class="btn-toolbar">
                  <div class="btn-group">
                    <button class="btn" onclick="setHeadingStyle();">Heading</button>
                  </div>
                  <div class="btn-group">
                    <button class="btn" onclick="setBoldStyle();">B</button>
                    <button class="btn" onclick="setItalicStyle();">I</button>
                    <button class="btn" onclick="setUnderlineStyle();">U</button>
                  </div>
                </div>
              </li>
              <li class="divider-vertical"></li>
              <li>
                <div class="btn-toolbar">
                  <div class="btn-group">
                    <button class="btn" onclick="cancelEditPost();">Cancel</button>
                  </div>
                  <div class="btn-group">
                    <button class="btn btn-primary" onclick="savePost();" id="toolbar-save-button">Save</button>
                  </div>
                </div>
              </li>
            </ul>
            
            <div class="pull-right" id="search-field">
              <form method="get" action="<?=App::concatenatePageURL(App::getBaseURL(), 'Posts')?>" class="navbar-search pull right">
                <input type="text" class="search-query" placeholder="Search" name="query" onkeyup="filterPosts('.search-query');" onsubmit="return false;">
              </form>
            </div>
          </div><!-- .nav-collapse -->
          
        </div><!-- .container -->
      </div><!-- .navbar-inner -->
    </div><!-- .navbar -->
    
    <div class="container">
      
      <header>
        <h1><a href="<?=App::getBaseURL()?>" onclick="navigateToHome(); return false;">Christian Rasmussen's Blog</a></h1>
      </header>
      
      <div class="row">
        <div class="span8" id="contents">
        
<?=$contents?>
        
        </div><!-- .span8 -->
        <div class="span4">
        
<?php include(__DIR__ . '/Snippets/Sidebar.php'); ?>
        
        </div><!-- .span4 -->
      </div><!-- .row -->
      <p>
        
      </p>
      <footer>
        <p>2012 &copy; <a href="http://rasmussen.io">Rasmussen I/O</a></p>
        <p class="small">Built with <a href="http://twitter.github.com/bootstrap/">Bootstrap</a> and <a href="http://jquery.com">jQuery</a>.</p>
      </footer>
      
    </div><!-- .container -->
    
    <div id="black-box"></div>
    
    <!-- Scripts -->
    <script type="text/javascript">
var $app = {
	baseURL: '<?=App::getBaseURL()?>',
	page: '<?=App::getPage()?>',
	id: '<?=App::getId()?>',
	isLoggedIn: <?=(App::isLoggedIn()) ? 'true' : 'false'?>
};
    </script>
    <script src="<?=App::getBaseURL()?>/assets/js/jquery.min.js"></script>
    <script src="<?=App::getBaseURL()?>/assets/js/bootstrap.min.js"></script>
    <script src="<?=App::getBaseURL()?>/assets/js/app.js"></script>
    <script src="<?=App::getBaseURL()?>/assets/js/post.js"></script>
    <script src="<?=App::getBaseURL()?>/assets/js/comment.js"></script>
    <script>
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
    </script>
  </body>
</html>