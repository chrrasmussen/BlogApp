<div class="comment" id="comment-<?=$commentId?>" data-comment-id="<?=$commentId?>" data-name="<?=$name?>">
  <div class="btn-group">
    <button class="btn btn-danger" onclick="deleteComment('#comment-<?=$commentId?>'); return false;"><i class="icon-trash icon-white"></i> Delete</button>
  </div>
  <div class="well<?=($userId > 0) ? ' admin' : ''?>">
    <div><?=($userId > 0) ? '<i class="icon-user"></i> </span>' : ''?><span class="name"><?=$name?></span> <span class="date"><?=$createdAt?></span></div>
    <p><span class="body"><?=$body?></p>
  </div>
</div>