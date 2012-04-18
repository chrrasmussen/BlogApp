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

function addPost(target) {
	var post = $(target);
	post.css('display', 'block');
	
	var buttonElement = $('#new-post button');
	buttonElement.css('display', 'none');
	
	setPostEditMode(post, true);
	
	console.log('Adding post: ' + post.attr('id'));
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
	
	// Fetch contents
	var postURL = post.attr('data-post-url');
	var url = concatenatePageURL($app.baseURL, 'PostDetails', postURL, {action: 'getPost', onlyContents: true});
	console.log('Retrieving original post with url: ' + url);
	$.get(url, function (data) {
		// Cancel successfull
		if (data.length > 0) {
			post.replaceWith(data);
		}
	});
}

function savePost() {
	var post = currentEditPost;
	setPostEditMode(post, false);
	
	console.log('Saving post: ' + post.attr('id'));
	
	// Get title
	var titleElement = post.find('.title');
	var title = titleElement.text();
	
	// Get body
	var bodyElement = post.find('.body');
	var body = bodyElement.html();
	
	// Fetch contents
	var postURL = post.attr('data-post-url');
	var url = concatenatePageURL($app.baseURL, 'PostDetails', postURL, {action: 'updatePost', onlyContents: true});
	var postData = {
		title: title,
		body: body
	};
	console.log('Saving post with url: ' + url);
	$.post(url, postData, function (data) {
		// Save successful
		if (data.length > 0) {
			post.replaceWith(data);
		}
	});
}

/* Helper methods */

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


/* Styling methods */

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

function performShortcut(event) {
	if (!event.ctrlKey)
		return;
	
	var key = String.fromCharCode(event.keyCode);
	switch (key) {
	    case 'H':
	    	setHeadingStyle();
	    	break;
	    case 'B':
	    	setBoldStyle();
	    	break;
	    case 'I':
	    	setItalicStyle();
	    	break;
	    case 'U':
	    	setUnderlineStyle();
	    	break;
	}
	
	event.preventDefault();
	event.stopPropagation();
	return false;
	// TODO: Stop propagation
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