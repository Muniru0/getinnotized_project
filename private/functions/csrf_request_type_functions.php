<?php
// GET requests should not make changes
// Only POST requests should make changes


function request_is_get() {
	return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function request_is_post() {
	return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function request_is_put() {
	return $_SERVER['REQUEST_METHOD'] == 'PUT';
}

function request_is_delete() {
	return $_SERVER['REQUEST_METHOD'] == 'DELETE';
}

function request_is_upload() {	
	return isset($_FILES['file_upload']);
}


?>
