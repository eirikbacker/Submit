<?php
	//Template Name: News
	get_header();
	if(is_page())query_posts('page_type=post&paged=' . max(1,intval(get_query_var('page'))));	//Make sure we loop posts
	$GLOBALS['more'] = intval(is_single());
?>
<div id="filter">
	<ul class="cm c">
		<li>
			<select name="nytt">
				<option value="<?php bloginfo('url'); ?>">Velg region eller bransje</option>
				<?php foreach(get_categories('hide_empty=0&parent=0&order=DESC&exclude=1') as $v){		//exclude 1 == uncategorized
					echo '<optgroup label="' . $v->name . '">';
					foreach(get_categories('hide_empty=0&child_of=' . $v->term_id) as $cat){
						$sel = $cat->slug == is_category($cat->term_id)? '" selected="selected':'';
						echo '<option value="' . get_term_link($cat) . $sel . '">' . $cat->name . '</option>';
					}
					echo '</optgroup>';
				} ?>
			</select>
		</li>
		<?php if(is_category()){
			$cat  = get_queried_object();
			$type = get_category($cat->parent)->slug;
			$top  = get_posts('numberposts=1&post_type=' . $type . '&meta_key=' . $cat->taxonomy . '&meta_value=' . $cat->term_id);
			
			wp_list_pages('post_type=' . $type . '&title_li=&depth=0&child_of=' . reset($top)->ID);
		} ?>
	</ul>
</div>
<div id="cont" class="cm c">
	<div class="c2x3">
		<?php while(have_posts()){the_post(); ?>
			<div class="cf post<?php if(!get_member() && !is_paged() && $wp_query->current_post)echo ' post-mini'; ?>">
				<?php if(get_member() || is_single())the_post_thumbnail(is_single()? 'large' : 'medium', 'class=post-image'); ?>
				<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="post-meta"><?php the_time('d. F'); ?>&nbsp; &Iota; &nbsp;<?php the_category('&nbsp; &Iota; &nbsp;'); ?></div>
				<div class="post-content"><?php the_content('Les videre'); ?></div>
				<?php if(is_single() && comments_open()){ ?>
					<div class="post-comments">
						<h5><?php _e('Kommentarer'); ?></h5>
						<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-num-posts="10" data-width="620"></div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="box">
			<?php echo paginate_links(array(
				'base' 		=> str_replace($big = 99999, '%#%', esc_url(get_pagenum_link($big))),
				'prev_text'    => __('&lsaquo; Nyere'),
				'next_text'    => __('Eldre &rsaquo;'),
				'current' 	=> max( 1, get_query_var('paged')),
				'total' 	=> $wp_query->max_num_pages,
			)); ?>
		</div>
	</div>
	<div class="c1x3"><?php dynamic_sidebar('news'); ?></div>
</div>
<?php
	get_footer();