<!doctype html>
<html <?php language_attributes(); ?> <?php body_class(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php wp_title(' &rsaquo; ', true, 'right');bloginfo('name'); ?></title>
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	<link rel="icon" type="image/x-icon" href="<?php bloginfo('template_url'); ?>/assets/favicon.ico">
	<?php wp_head(); ?>
</head>
<body>
	<ul id="head">
		<?php wp_list_pages('title_li=&parent=0'); ?>
	</ul>
	<div id="wrap">