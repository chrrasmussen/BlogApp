<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');


header('Content-Type: text/xml; charset=utf-8');

require_once(__DIR__ . '/../Backend/RSS.php');
RSS::run();