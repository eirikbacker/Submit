<?php
	//Setup Wordpress
	add_theme_support('post-thumbnails');
	if(!is_admin())wp_enqueue_script('core', get_bloginfo('template_url') . '/assets/core.js', array('jquery'));

	//Simplify Wordpress
	add_action('admin_menu', 'remove_menus');
	function remove_menus(){
		global $menu;end($menu);
		$hide = array(__('Links'), __('Comments'), __('Posts'));
		while(prev($menu))if(in_array(reset($v = explode(' ',$menu[key($menu)][0])) != NULL?$v[0]:"" , $hide)){unset($menu[key($menu)]);}
	}