/**
 * Post functions
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */


/* Global variables */

var currentEditPost;


/* Navigation */

function filterPosts(target) {
	var searchField = $(target);
	var value = searchField.val();
	
	navigateToPage('Posts', '', {query: value});
}

function navigateToPost(postURL) {
	console.log('Navigating to post: ' + postURL);
	
	navigateToPage('PostDetails', postURL);
}


/* New post button */

function addPost() {
	currentEditPost = $('#post-prototype').clone();
	currentEditPost.prependTo('#posts-section');
	currentEditPost.attr('id', 'post-0');
	setNewPostMode(true);
	
	console.log('Adding new post');
}

function setNewPostMode(isEditMode) {
	var post = $('#post-0');
	showNewPost(isEditMode);
	showNewPostButton(!isEditMode)
	setPostEditMode(post, isEditMode);
}

function showNewPostButton(show) {
	showElement('#new-post-section button', show);
}

function showNewPost(show) {
	showElement('#post-0', show);
}

/* Edit post buttons */

function editPost(target) {
	var post = $(target);
	setPostEditMode(post, true);
	
	console.log('Editing post: ' + post.attr('id'));
}

function deletePost(target) {
	var post = $(target);
	console.log('Deleting post: ' + post.attr('id'));
	
	// Confirm delete
	var title = post.attr('data-title');
	var response = confirm('Are you sure you want to delete blog entry with title "' + title + '"?');
	if (response != true)
		return;
	
	// Fetch contents
	var postURL = post.attr('data-post-url');
	var url = concatenatePageURL($app.baseURL, 'PostDetails', postURL, {action: 'deletePost', onlyContents: true});
	console.log('Deleting post with url: ' + url);
	$.get(url, function (data) {
		// Successful deletion
		if (data.length > 0) {
			post.remove();
			navigateToHome();
		}
	});
}


/* Edit post toolbar buttons */

function cancelEditPost() {
	var post = currentEditPost;
	setPostEditMode(post, false);
	
	console.log('Canceling edit post: ' + post.attr('id'));
	
	if (post.attr('id') == 'post-0') {
		setNewPostMode(false);
	}
	
	// Fetch contents
	var postURL = post.attr('data-post-url');
	var url = concatenatePageURL($app.baseURL, 'PostDetails', postURL, {action: 'getPost', onlyContents: true});
	console.log('Retrieving original post with url: ' + url);
	$.get(url, function (data) {
		// Cancel successful
		if (data.length > 0) {
			post.replaceWith(data);
		}
	});
}

function savePost() {
	var post = currentEditPost;
	
	// Validate post
	if (!validatePost(post)) {
		console.log('Failed to validate post: ' + post.attr('id'));
		return;
	}
	
	setPostEditMode(post, false);
	
	console.log('Saving post: ' + post.attr('id'));
	
	// Get title
	var titleElement = post.find('.title');
	var title = $.trim(titleElement.text());
	
	// Get body
	var bodyElement = post.find('.body');
	var body = $.trim(bodyElement.html());
	
	// Fetch contents
	var isNewPost = (post.attr('id') == 'post-0');
	var action = (isNewPost) ? 'addPost' : 'updatePost';
	var postURL = post.attr('id');
	var url = concatenatePageURL($app.baseURL, 'PostDetails', postURL, {action: action, onlyContents: true});
	var postData = {
		title: title,
		body: body
	};
	console.log('Saving post with url: ' + url);
	$.post(url, postData, function (data) {
		// Save successful
		if (data.length > 0) {
			if (isNewPost) {
				var postsElement = $('#posts-section');
				postsElement.prepend(data);
				
				setNewPostMode(false);
			}
			else {
				post.replaceWith(data);
			}
		}
	});
}


/* Helper functions */

function validatePost(post) {
	// Get title
	var titleElement = post.find('.title');
	var title = $.trim(titleElement.text());
	var titleError = !validatePostTitle(title);
	
	// Get body
	var bodyElement = post.find('.body');
	var body = $.trim(bodyElement.html());
	var bodyText = $.trim(bodyElement.text());
	var bodyError = !validatePostBody(body, bodyText);
	
	if (!titleError && !bodyError)
		return true;
}

function validatePostTitle(title) {
	if (title && title.length > 0 && title.length <= 200)
		return true;
}

function validatePostBody(body, bodyText) {
	if (bodyText && bodyText.length > 0 && body && body.length <= 10000)
		return true;
}

function refreshSaveButtonState(event) {
	var target = event.target;
	var post = $(target).closest('article');
	
	var saveButtonElement = $('#toolbar-save-button');
	if (validatePost(post)) {
		saveButtonElement.removeClass('disabled');
	}
	else {
		saveButtonElement.addClass('disabled');
	}
}

function setPostEditMode(post, isEditMode) {
	// Show/hide hide-on-edit-post elements
	var hideElements = post.find('.hide-on-edit-post');
	var hideValue = (isEditMode) ? 'none' : 'block';
	hideElements.css('display', hideValue);
	
	showEditPostToolbar(isEditMode);
	showSearchField(!isEditMode);
	showBlackBox(isEditMode);
	
	// Promote/demote z-index
	var zIndexValue = (isEditMode) ? 600 : 0;
	post.css('z-index', zIndexValue);
	
	// Set content editable
	var editableElements = post.find('.editable');
	for (var i in editableElements) {
		editableElements[i].contentEditable = isEditMode;
	}
	
	var header = post.find('h2')[0];
	if (isEditMode)
	{
		// Remove link from header
		header.innerHTML = header.innerText;
	}
	else
	{
		// Restore link in header
		var pageURL = post.attr('data-page-url');
		var postURL = post.attr('data-post-url');
		var title = header.innerText;
		header.innerHTML = '<a href="' + pageURL + '" onclick="navigateToPost(\'' + postURL + '\'); return false;">' + title + '</a>';
	}
	
	// Set currently edited post
	if (isEditMode)
		currentEditPost = post;
	else
		currentEditPost = null;
	
	// Focous on body
	if (isEditMode)
		highlightBody(post);
}

function highlightBody(post) {
	var bodyElement = post.find('.body');
	bodyElement.focus();
}


/* Styling */

function setHeadingStyle() {
	setBlockStyle('h3');
}

function setBoldStyle() {
	setInlineStyle('b');
}

function setItalicStyle() {
	setInlineStyle('i');
}

function setUnderlineStyle() {
	setInlineStyle('u');
}

function setBlockStyle(elementName) {
	console.log('Setting block style: ' + elementName);
	
	// Get selection
	var selObj = window.getSelection();
	if (selObj.rangeCount == 0)
		return;
	var range = selObj.getRangeAt(0);
	
	// Limit the styling to the body element
	var bodyElement = currentEditPost.find('.body').get(0);
	if (!selObj.containsNode(bodyElement, true))
		return;
	
	// Get the type of block
	var startContainer = range.startContainer;
	var existingNode = $(startContainer).parentsUntil(bodyElement);
	var blockNode = existingNode.last();
	var blockNodeElementName = blockNode.get(0).tagName.toLowerCase();
	
	// If this styling already has been applied, revert back to paragraph
	if (elementName == blockNodeElementName)
		elementName = 'p';
	
	// Update styling
	var contents = '<' + elementName + '>' + blockNode.html() + '</' + elementName + '>';
	blockNode.replaceWith(contents);
}

function setInlineStyle(elementName) {
	console.log('Setting inline style: ' + elementName);
	
	// Get selection
	var selObj = window.getSelection();
	var range = selObj.getRangeAt(0);
	
	// Limit the styling to the body element
	var bodyElement = currentEditPost.find('.body').get(0);
	if (!selObj.containsNode(bodyElement, true))
		return;
	
	// Check if element exist as an ancestor
	var startContainer = range.startContainer;
	var existingNode = $(startContainer).closest(elementName, bodyElement);
	
	var styleAlreadyExists = (existingNode.get(0) != undefined);
	if (!styleAlreadyExists) {
		if (range.collapsed)
			return;
			
		// Add styling
		var newNode = document.createElement(elementName);
		range.surroundContents(newNode);
	}
	else {
		// Remove styling
		existingNode.replaceWith(existingNode.html());
	}
}