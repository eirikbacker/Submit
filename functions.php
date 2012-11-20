<?php
	//Setup Wordpress
	add_theme_support('post-thumbnails');
	if(!is_admin())wp_enqueue_script('core', get_bloginfo('template_url') . '/assets/core.js', array('jquery'));

	//Simplify Wordpress
	/*add_action('admin_menu', 'remove_menus');
	function remove_menus(){
		global $menu;end($menu);
		$hide = array(__('Links'), __('Comments'), __('Posts'));
		while(prev($menu))if(in_array(reset($v = explode(' ',$menu[key($menu)][0])) != NULL?$v[0]:"" , $hide)){unset($menu[key($menu)]);}
	}*/

	//Layout shortcodes
	add_filter('the_content', 'shortcode_fix');
	function shortcode_fix($h){return strtr($h, array('<p>['=>'[', ']</p>'=>']', ']<br />'=>']'));}
	function shortcode_col($a,$h='',$c=''){return '<div class="c'.$c.'"><div class="box">'.do_shortcode($h).'</div></div>';}
	foreach(explode(',','0,1,1x2,1x3,2x3,1x4,3x4,1x5,2x5,3x5,4x5,1x6,5x6,1x8,3x8,5x8,7x8') as $v)add_shortcode($v, 'shortcode_col');

	//Area shortcodes
	add_shortcode('button', 'shortcode_area');
	function shortcode_area($a,$h='',$c=''){
		$a = $a? '" data-'.strtr(urldecode(http_build_query($a)), array('&'=>'" data-','='=>'="')) : '';
		return '<div class="c ' . $c . $a . '">' . do_shortcode($h) . '</div>';
	}

	//Custom gallery
	/*add_filter('post_gallery','return_gallery');
	function return_gallery($txt = ''){
		global $post;
		$attr = '&post_parent=' . $post->ID . '&exclude=' . get_post_thumbnail_id($post->ID);
		$imgs = get_children('post_type=attachment&post_mime_type=image&orderby=menu_order&order=ASC' . $attr);
		foreach(return_imgs($post->ID, $attr) as $k=>$v)if(!strpos($post->post_content, $v->guid)){
			$txt.= '<a href="' . $v->guid . '">' . wp_get_attachment_image($v->ID, 'thumbnail') . '</a>';
		}
		return '<div class="gallery cf">' . $txt . '</div>';
	}*/