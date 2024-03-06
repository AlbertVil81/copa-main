<?php /* Template Name: Logout */ ?>
<?php
	unset($_SESSION['user_logged']);
	unset($_SESSION['user']);
	wp_redirect( get_bloginfo( 'url' ) . '/' );	
?>