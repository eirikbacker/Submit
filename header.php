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
	<div id="menu">
		<h1 class="cm"><a href="<?php bloginfo('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<ul class="cm c">
			<?php if(!intval(get_option('page_on_front'))){ ?>
				<li class="page_item<?php if(is_front_page())echo ' current_page_item'; ?>">
					<a href="<?php bloginfo('home'); ?>/"><?php _e('Forsiden'); ?></a>
				</li>
			<? } ?>
			<?php wp_list_pages('title_li=&parent=0'); ?>
			<li><a class="icon-globe" href="#"></a></li>
			<li><a class="icon-search" href="?s="></a>
				<form method="get" action="<?php bloginfo('home'); ?>/"><input type="text" name="s"></form>
			</li>
			<li><a class="icon-user" href="<?php bloginfo('home'); ?>/">
				<?php echo get_member()? 'Hei, ' . get_member('display_name') . '!' : __('Logg inn'); ?>
			</a></li>
		</ul>
	</div>
	<div id="wrap">
		<?php get_header(get_member()? 'member' : 'banner'); ?>