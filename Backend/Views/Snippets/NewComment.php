<h3>New Comment</h3>
<form method="post" action="<?=App::ConcatenatePageURL(App::getBaseURL(), App::getPage(), App::getId(), array('action' => 'addComment'))?>">
  <fieldset>
    <input type="text" placeholder="Name" class="span7" name="name" id="new-comment-name">
    <textarea placeholder="Comment" class="span7" name="body" id="new-comment-body"></textarea>
  </fieldset>
  <button type="submit" class="btn btn-primary" onclick="addComment(); return false;"><i class="icon-comment icon-white"></i> Post Comment</button>
</form>