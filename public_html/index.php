<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

/* header('Content-Type: text/html; charset=utf-8'); */

require_once(__DIR__ . '/../Backend/App.php');
App::run();


// ---
// Testing area
// ---

/*
require_once(__DIR__ . '/../Backend/Models/Comment.php');
print(Post::getIdFromPostURL('54'));
*/

/* $user = User::getUserById(1); */
/* $post = Post::getPostById(1); */
/* $comment = Comment::getCommentById(1); */
/*
$posts = Post::getPosts();
print_r($posts);
*/