<section id="posts-section">
<?=$posts?>
</section>
<section id="comments-section">
  <h3>Comments</h3>
  <p class="no-comments"<?=(!empty($comments)) ? ' style="display: none;"' : ''?>>No comments</p>
<?=$comments?>
</section>
<section id="new-comment-section">
  <h3>New Comment</h3>
  <p id="new-comment-logged-in"><span class="user-label">Commenting as:</span> <span class="user-full-name"><?=(App::getUser() != null) ? App::getUser()->fullName : ''?></span></p>
  <form method="post" action="<?=App::ConcatenatePageURL(App::getBaseURL(), App::getPage(), App::getId(), array('action' => 'addComment'))?>">
    <fieldset>
      <input type="text" placeholder="Name" class="span7" name="name" id="new-comment-name">
      <textarea placeholder="Comment" class="span7" name="body" id="new-comment-body"></textarea>
    </fieldset>
    <button type="submit" class="btn btn-primary" onclick="addComment(); return false;"><i class="icon-comment icon-white"></i> Post Comment</button>
  </form>
  <div class="alert alert-error">
    <div class="name"><strong>Name:</strong> Please fill in the name field. Max 40 characters.</div>
    <div class="body"><strong>Message:</strong> Please fill in the message field. Max 1 000 characters.</div>
  </div>
</section>