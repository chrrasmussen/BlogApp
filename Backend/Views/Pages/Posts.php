<section id="new-post-section"<?=(!App::isLoggedIn()) ? ' style="display: none;"' : ''?>>
  <button class="btn visible-desktop" onclick="addPost();">New Blog Entry</button>
  <article class="entry" id="post-prototype">
    <h2 class="title editable" onkeyup="refreshSaveButtonState(event);">Title of New Post</h2>
    <div class="body editable" onkeyup="refreshSaveButtonState(event);">
      <p>Body of new post.</p>
    </div>
  </article>
</section>
<section id="posts-section">
  <div class="entry no-posts"<?=(!empty($posts)) ? ' style="display: none;"' : ''?>>
    <p>No posts found!</p>
  </div>
<?=$posts?>
</section>