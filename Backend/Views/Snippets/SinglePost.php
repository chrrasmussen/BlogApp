<article class="entry" id="post-<?=$postId?>" data-page-url="<?=App::concatenatePageURL(App::getBaseURL(), App::getPage(), $postURL)?>" data-post-url="<?=$postURL?>" data-title="<?=$title?>">
  <div class="hide-on-edit-post visible-desktop">
    <div class="post-metadata">
      <ul class="unstyled">
        <li><?=$modifiedAt?> <i class="icon-time"></i></li>
        <li><?=$fullName?> <i class="icon-user"></i></li>
      </ul>
    </div>
    
    <div class="post-actions">
      <div class="btn-toolbar">
        <div class="btn-group">
          <button class="btn btn-danger" onclick="deletePost('#post-<?=$postId?>'); return false;"><i class="icon-trash icon-white"></i> Delete</button>
        </div>
        <div class="btn-group">
          <button class="btn" onclick="editPost('#post-<?=$postId?>'); return false;"><i class="icon-edit"></i> Edit</button>
        </div>
      </div>
    </div>
  </div>
  
  <h2 class="title editable" onkeyup="refreshSaveButtonState(event);"><a href="<?=App::concatenatePageURL(App::getBaseURL(), 'PostDetails', $postURL)?>" onclick="navigateToPost('<?=$postURL?>'); return false;"><?=$title?></a></h2>
  <div class="body editable" onkeyup="refreshSaveButtonState(event);">
    <?=$body?>
  </div>
</article>