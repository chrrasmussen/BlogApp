<section id="new-post-section"<?=(!App::isLoggedIn()) ? ' style="display: none;"' : ''?>>
  <button class="btn" onclick="addPost('#post-new');">New Blog Entry</button>
  <article class="entry" id="post-0">
    <h2 class="title editable">Title of New Post</h2>
    <div class="body editable">
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