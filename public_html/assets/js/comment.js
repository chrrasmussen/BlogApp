function addComment() {
	// Get name
	var nameElement = $('#new-comment-name');
	var name = nameElement.val();
	nameElement.val('');
	
	// Get body
	var bodyElement = $('#new-comment-body');
	var body = bodyElement.val();
	bodyElement.val('');
	
	// Send request
	var url = concatenatePageURL($app.baseURL, $app.page, $app.id, {action: 'addComment', onlyContents: true});
	var postData = {
		name: name,
		body: body
	};
	console.log('Posting comment to url: ' + url + ' (data: ' + $.param(postData) + ')');
	$.post(url, postData, function (data) {
		// Add comment successful
		if (data.length > 0) {
			var commentsElement = $('#comments');
			commentsElement.append(data);
		}
	});
}

function deleteComment(target) {
	var comment = $(target);
	console.log('Deleting comment: ' + comment.attr('id'));
	
	// Confirm delete
	var name = comment.attr('data-name');
	var response = confirm('Are you sure you want to delete comment by "' + name + '"?');
	if (response != true)
		return;
	
	// Fetch contents
	var commentId = comment.attr('data-comment-id');
	var url = concatenatePageURL($app.baseURL, $app.page, $app.id, {action: 'deleteComment', onlyContents: true});
	var postData = {
		commentId: commentId
	};
	console.log('Deleting comment with url: ' + url);
	$.post(url, postData, function (data) {
		// Delete successful
		if (data.length > 0) {
			comment.remove();
		}
	});
}