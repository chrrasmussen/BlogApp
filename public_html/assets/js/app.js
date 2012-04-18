/* Start-up configuration */

$(function() {
	console.log('Init');
	
	setUpLoginToolbar();
	configureAdminStylesheet();
});

function setUpLoginToolbar() {
	$('.dropdown-toggle').dropdown();
	$('.dropdown').click(function (e) {
		e.stopPropagation();
	});
}

function configureAdminStylesheet() {
	if ($app.isLoggedIn == true) {
		var stylesheetURL = $app.baseURL + '/assets/css/screen-admin.css';
		$('head').append('<link rel="stylesheet" type="text/css" href="' + stylesheetURL + '" id="admin-stylesheet" />');
	}
	else {
		$('#admin-stylesheet').remove();
	}
}


/* Navigation */

function concatenatePageURL(baseURL, page, id, queryParameters)
{
	var pageURL = baseURL + '/index.php';
	
	if (typeof(page) == 'string' && page.length > 0)
	{
		pageURL += '?page=' + page;
		
		if (typeof(id) == 'string' && id.length > 0)
			pageURL += '&id=' + id;
	}
	
	if (typeof(queryParameters) == 'object')
	{
		var separator = (typeof(page) == 'string' && page.length > 0) ? '&' : '?';
		var queryString = $.param(queryParameters)
		pageURL += separator + queryString;
	}
	
	return pageURL;
}

function refreshPage() {
	navigateToPage($app.page, $app.id);
}

function navigateToPage(page, id, queryParameters) {
/*
	// Get previous state
	var previousState = {
	    page: $app.page,
	    id: $app.id
	};
*/
	
	// Set new state
	$app.page = page || '';
	$app.id = id || '';
	
	// Set up query parameters
	if (typeof(queryParameters) != 'object')
		queryParameters = {};
	queryParameters.onlyContents = true;
	
	// Fetch contents
	var url = concatenatePageURL($app.baseURL, $app.page, $app.id, queryParameters);
	console.log('Loading page with url: ' + url);
	$.get(url, function (data) {
/*
		// Modify URL
		var historyURL = concatenatePageURL($app.baseURL, $app.page, $app.id);
		history.pushState(previousState, null, historyURL);
*/
		// Fetch successful
		if (data.length > 0) {
			// Replace contents
			var contentsElement = $('#contents');
			contentsElement.html(data);
		}
	});
}

function navigateToHome() {
	console.log('Navigating to home');
	
	navigateToPage();
}


/*
window.addEventListener("popstate", function(event) {
    console.log('Navigating back: ' + location.pathname);
    console.log(event.state);
    if (event.state != null) {
    	console.log(event);
    	navigateToPage(event.state.page, event.state.id);
    }
});
*/


/* Toolbar */

function showEditPostToolbar(show) {
	var toolbarElement = $('#edit-post-toolbar');
	var toolbarValue = (show) ? 'block' : 'none';
	toolbarElement.css('display', toolbarValue);
}

function showSearchField(show) {
	var searchElement = $('#search-field');
	var searchValue = (show) ? 'block' : 'none';
	searchElement.css('display', searchValue);
}

function showBlackBox(show) {
	var blackBoxElement = $('#black-box');
	var blackBoxValue = (show) ? 'block' : 'none';
	blackBoxElement.css('display', blackBoxValue);
}

function logIn() {
	// Get email
	var emailElement = $('#login-email');
	var email = emailElement.val();
	
	// Get password
	var passwordElement = $('#login-password');
	var password = passwordElement.val();
	
	// Get log in alert
	var loginAlertElement = $('#login-dropdown .alert');
	loginAlertElement.hide();
	
	// Fetch contents
	var url = concatenatePageURL($app.baseURL, $app.page, $app.id, {action: 'logIn', onlyContents: true});
	var postData = {
		email: email,
		password: password
	};
	console.log('Logging in with url: ' + url);
	$.post(url, postData, function (data) {
		// Login successful
		if (data.length > 0) {
			// Replace contents
			var loginElement = $('#login-toolbar');
			loginElement.html(data);
			
			// Change isLoggedIn state
			$app.isLoggedIn = true;
			
			// Add stylesheet
			configureAdminStylesheet();
			
			// Refresh page contents
			refreshPage();
		}
		else {
			loginAlertElement.show('fast');
		}
	});
}

function logOut() {
	// Fetch contents
	var url = concatenatePageURL($app.baseURL, $app.page, $app.id, {action: 'logOut', onlyContents: true});
	console.log('Logging out with url: ' + url);
	$.get(url, function (data) {
		// Logout successful
		if (data.length > 0)
		{
			// Replace contents
			var loginElement = $('#login-toolbar');
			loginElement.html(data);
			
			// Set up login toolbar
			setUpLoginToolbar();
			
			// Change isLoggedIn state
			$app.isLoggedIn = false;
			
			// Remove stylesheet
			configureAdminStylesheet();
			
			// Refresh page contents
			refreshPage();
		}
	});
}