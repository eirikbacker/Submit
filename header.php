<!doctype html>
<html <?php language_attributes(); ?> <?php body_class(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php wp_title(' &rsaquo; ', true, 'right');bloginfo('name'); ?></title>
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/assets/bootstrap.min.css">
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/assets/bootstrap-responsive.min.css">
	<link rel="icon" type="image/x-icon" href="<?php bloginfo('template_url'); ?>/assets/favicon.ico">
	<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<?php wp_head(); ?>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<div class="pull-right">
					<a class="btn btn-<?php echo ($tot = get_user_total())? 'danger' : 'primary'; ?>" href="<?php bloginfo('url'); ?>/paypal/">
						<i class="icon-white icon-shopping-cart"></i>
						<span class="hidden-phone"><?php $tot? _e('Send inn', get_template()) : _e('Mine kvitteringer', get_template()); ?></span>
					</a>
					<a class="btn btn-inverse" href="<?php echo wp_logout_url(); ?>">
						<i class="icon-white icon-off"></i>
						<span class="hidden-phone"> <?php _e('Log out'); ?></span>
					</a>
				</div>
				<a class="brand" href="<?php bloginfo('url'); ?>">Hei, <?php echo wp_get_current_user()->user_login; ?>!</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<?php if(!intval(get_option('page_on_front'))){ ?>
							<li class="page_item<?php if(is_home())echo  ' active'; ?>">
								<a href="<?php bloginfo('url'); ?>"><?php _e('Innsending', get_template()); ?></a>
							</li>
						<?php } ?>
						<?php echo str_replace('current_page_item', 'active', wp_list_pages('title_li=&echo=&parent=0')); ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="container">