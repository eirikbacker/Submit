<?php
/* ------------------------------------------------------------------------------------- *
 * Setup wordpress
 * ------------------------------------------------------------------------------------- */

	//Assets
	add_action('init', 'assets_enqueue');
	function assets_enqueue(){
		if(is_admin())return wp_enqueue_style('admin', get_bloginfo('template_url') . '/admin-style.css');
		wp_enqueue_script('bootstrap', get_bloginfo('template_url') . '/assets/bootstrap.min.js', array('jquery'), null, true);
		wp_enqueue_script('core',      get_bloginfo('template_url') . '/assets/core.js', array('jquery', 'bootstrap'), null, true);
	}

	//Customize admin
	add_theme_support('post-thumbnails');
	add_image_size('project', 120, 140, true);
	add_filter('show_admin_bar','__return_false');

	//Customize TinyMCE
	add_editor_style();
	add_filter('mce_buttons', 'tinymce_buttons');
	add_filter('mce_buttons_2', '__return_empty_array');
	add_filter('tiny_mce_before_init', 'tinymce_options');
	function tinymce_buttons(){return explode(',', 'formatselect,bold,italic,strikethrough,|,bullist,numlist,blockquote,|,link,unlink,wp_more,|,pastetext,pasteword,removeformat,|,undo,redo,fullscreen');}
	function tinymce_options($args){return array_merge($args, array('theme_advanced_blockformats' => 'p,h1,h2,h3,h4,h5,h6'));}


/* ------------------------------------------------------------------------------------- *
 * Theme setup
 * ------------------------------------------------------------------------------------- */

	//Setup custom template redirects
	add_action('template_redirect','template_redirect');
	function template_redirect($tpl = ''){
		if(!is_404() || !is_user_logged_in())return;
		status_header(200);
		$GLOBALS['wp_query']->is_404 = false;
		die(get_template_part(get_query_var('pagename')));
	}

	//Remove "Private" and "Protected" from titles
	add_filter('private_title_format', 'title_format');
	add_filter('protected_title_format', 'title_format');
	function title_format(){return '%s';}

	//Span (columns in bootstrap) shortcodes
	add_filter('the_content', 'shortcode_fix');
	function shortcode_fix($h){return strtr($h, array('<p>['=>'[', ']</p>'=>']', ']<br />'=>']'));}
	function shortcode_col($a,$h='',$c=''){return '<div class="'.$c.'">'.do_shortcode($h).'</div>';}
	for($i=0;$i<=12;$i++)add_shortcode('span' . $i, 'shortcode_col');

	//Div shortcode for adding divs with attributes
	add_shortcode('div', 'shortcode_div');
	function shortcode_div($a,$h='',$c=''){
		if($a && array_keys($a) === range(0, count($a) - 1))$a = ' class="' . implode(' ', $a) . '"';
		else $a = $a? ' ' . strtr(urldecode(http_build_query($a)), array('&'=>'" data-','='=>'="')) . '"' : '';
		return '<div' . $a . '>' . do_shortcode($h) . '</div>';
	}


/* ------------------------------------------------------------------------------------- *
 * Logn and user roles
 * ------------------------------------------------------------------------------------- */

	//Register roles
	add_action('init', 'register_roles');
	function register_roles(){
		$role = array('jury'=>'Jurymeldem', 'chairman'=>'Juryformann');
		foreach($role as $k=>$v)if(!get_role($k))add_role($k, $v);
	}

	//Customize login screen
	add_action('login_enqueue_scripts', 'login_css');
	add_filter('login_headertitle',     'login_txt');
	add_filter('login_headerurl',       'login_url');
	function login_css(){echo '<link rel="stylesheet" href="' . get_bloginfo('template_url') . '/login-style.css">';}
	function login_txt(){return get_bloginfo('name');}
	function login_url(){return get_bloginfo('url');}

	//Customize login emails
	add_filter('wp_mail_from','login_from_mail');
	add_filter('wp_mail_from_name','login_from_name');
	function login_from_mail(){return get_option('admin_email');}
	function login_from_name(){return get_bloginfo('name');}

	//Manage login redirects
	add_action('admin_init', 'login_redirect');
	add_action('template_redirect','login_required');
	function login_redirect(){if(!current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX))wp_redirect(home_url());}
	function login_required(){if(!is_user_logged_in())auth_redirect();}
	
	//Filter posts in admin by user
	add_action('restrict_manage_posts', 'author_filter');
	function author_filter(){wp_dropdown_users('name=author&show_option_all=View+all+authors&selected=' . filter_input(INPUT_GET, 'author'));}


/* ------------------------------------------------------------------------------------- *
 * Payment handeling
 * ------------------------------------------------------------------------------------- */

	//Save and edit fields for price meta
	add_action('edited_category', 'save_term_meta');
	add_action('create_category', 'save_term_meta');
	add_action('category_add_form_fields',  'edit_term_meta');
	add_action('category_edit_form_fields', 'edit_term_meta');
	function get_term_meta($id, $k, $def = ''){$meta = get_option('taxonomy_' . $id);return isset($meta[$k])? $meta[$k] : $def;}
	function save_term_meta($id){if(isset($_POST[$k='term_meta']))update_option('taxonomy_' . $id, array_filter($_POST[$k]));}
	function edit_term_meta($term){
		$adds = is_string($term);
		$meta = $adds? array() : get_option('taxonomy_' . $term->term_id);
		$edit = array('price' => 'Price (NOK)');
		
		foreach($edit as $k=>$v){
			echo $adds? '<div class="form-field">' : '<tr class="form-field"><th scope="row" valign="top">';
			echo '<label for="term_meta[' . $k .']">' . __($v) . '</label>' . ($adds? '' : '</th><td>');
			echo '<input type="text" name="term_meta[' . $k .']" value="' . (isset($meta[$k])? esc_attr($meta[$k]) : '') . '">';
			echo $adds? '</div>' : '</td></tr>';
		}
	} 

	//Stay on term on save
	add_filter('wp_redirect', 'save_term_redirect');
	function save_term_redirect($to){return (strpos($to, $tag='edit-tags.php') && strpos($ref=wp_get_referer(), $tag))? $ref: $to;}

	//Get total outstanding for user
	function get_user_total($price = 0){
		if(isset($GLOBALS[$k = 'get_user_total']))return $GLOBALS[$k];
		return $GLOBALS[$k] = array_sum(array_map('get_post_total', get_posts('numberposts=-1&author=' . get_current_user_id())));
	}

	//Get total outstanding for post
	function get_post_total($post = null){
		$post = is_int($post)? $post : ($post? $post->ID : get_the_ID());
		if(isset($GLOBALS[$k = 'get_post_total_' . $post]))return $GLOBALS[$k];

		$cats = wp_list_pluck(get_the_category($post), 'term_id');
		foreach($cats as $k=>$id)$cats[$k] = intval(get_term_meta($id, 'price'));
		return $GLOBALS[$k] = array_sum($cats);
	}


/* ------------------------------------------------------------------------------------- *
 * Add post from frontend
 * ------------------------------------------------------------------------------------- */

	//Project meta handeling
	function the_project($k){echo empty($GLOBALS['post'])? '' : esc_attr(get_post_meta($GLOBALS['post']->ID, $k, true));}

	//Echo file-thumbnail based on ID
	function the_project_file($id){
		if(!strlen($img = wp_get_attachment_image($id = is_int($id)? $id : $id->ID, 'project', true, 'class=file-icon')))return;
		$val = http_build_query(array('action'=>'submit_file_delete', 'id'=>$id, '_ajax_nonce'=>wp_create_nonce('submit_file_delete')));
		echo '<div class="file file-trash">' . __('Klikk her for å slette filen fra prosjektet', get_template());
		echo '<input type="hidden" name="file[' . $id . ']" value="' . $val . '">' . $img . '</div>';
	}

	//File upload and delete
	add_action('init', 'submit_init');
	add_action('wp_ajax_submit_file', 'submit_file');
	add_action('wp_ajax_submit_file_delete', 'submit_file_delete');
	function submit_init(){wp_enqueue_script('plupload-all');}
	function submit_file_delete(){check_ajax_referer('submit_file_delete');wp_delete_post($_POST['id']);}
	function submit_file(){
		check_ajax_referer('submit_file');
		if(!is_wp_error($id = media_handle_upload('file', intval($_REQUEST['parent']))))die(the_project_file($id));
		else die('<div class="file">' . $id->get_error_message() . '</div>');
	}

	//Post save and delete
	add_action('wp_ajax_submit_save', 'submit_save');
	function submit_save(){
		check_ajax_referer('submit_save');

		$meta = explode(',', 'client,ad,leader,text,consultant,illustrator,photo,url,video,background,concept,conditions');
		$data = wp_parse_args(array('post_status'=>'publish','post_title'=>$_POST['title'],'ID'=>$_POST['id']), get_post($_POST['id'], ARRAY_A));
		$post = wp_insert_post($data, false);

		if(!empty($_POST['cats']))wp_set_post_terms($post, $_POST['cats'], 'category');											//Set categories
		if(!empty($_POST['file']))foreach($_POST['file'] as $id=>$v)wp_update_post(array('ID'=>$id, 'post_parent'=>$post));		//Attach images to post
		foreach($meta as $k)if(isset($_POST[$k]))update_post_meta($post, $k, sanitize_text_field($_POST[$k]));					//Store meta values
		die(get_permalink($post));																								//Return updated post url
	}

