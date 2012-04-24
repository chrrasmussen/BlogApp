/**
 * Comment functions
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */


/* Add/delete comment */

function addComment() {
	// Get name
	var nameElement = $('#new-comment-name');
	var name = $.trim(nameElement.val());
	var nameError = (!validateCommentName(name) && !$app.isLoggedIn);
	
	// Get body
	var bodyElement = $('#new-comment-body');
	var body = $.trim(bodyElement.val());
	bodyError = !validateCommentBody(body);
	
	// Show alert
	var alertElement = $('#new-comment-section .alert');
	alertElement.hide();
	if (nameError || bodyError)
	{
		var alertNameElement = alertElement.find('.name');
		var alertNameValue = (nameError) ? 'block' : 'none';
		alertNameElement.css('display', alertNameValue);
		
		var alertBodyElement = alertElement.find('.body');
		var alertBodyValue = (bodyError) ? 'block' : 'none';
		alertBodyElement.css('display', alertBodyValue);
		
		alertElement.show('fast');
		
		return;
	}
	
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
			// Add comment
			var commentsElement = $('#comments-section');
			commentsElement.append(data);
			
			// Reset form fields
			nameElement.val('');
			bodyElement.val('');
			
			// Refresh no comments text
			refreshNoCommentsText();
		}
	});
}

function validateCommentName(name) {
	if (name && name.length > 0 && name.length <= 40)
		return true;
}

function validateCommentBody(body) {
	if (body && body.length > 0 && body.length <= 1000)
		return true;
}

function refreshNoCommentsText() {
	var commentsElement = $('#comments-section');
	var show = !(commentsElement.has('.comment').length);
	showElement('.no-comments', show);
}

function deleteComment(target) {
	var comment = $(target);
	console.log('Deleting comment: ' + comment.attr('id'));
	
	// Confirm delete
	var name = comment.attr('data-name');
	var response = confirm('Are you sure you want to delete comment by "' + name + '"?');
	if (response != true)
		return;
	
	// Send request
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
			refreshNoCommentsText();
		}
	});
}