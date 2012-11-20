<?php
	//Redirect if post is empty and has subs
	if(!$post->post_content && ($subs=get_pages('sort_column=menu_order&child_of='.$post->ID)))wp_redirect(get_permalink($subs[0]->ID));
	get_header();
	the_post();
?>
<div id="filter">
	<ul class="cm c">
		<?php if(get_post($top = empty($post->ancestors)? $post->ID : end($post->ancestors))->post_content){ ?>
			<li class="page_item<?php if($top === $post->ID)echo ' current_page_item'; ?> ">
				<a href="<?php echo get_permalink($top); ?>"><?php echo get_the_title($top); ?></a>
			</li>
		<?php } ?>
		<?php wp_list_pages('post_type=' . $post->post_type .'&title_li=&depth=0&child_of=' . $top); ?>
	</ul>
</div>
<div id="cont" class="cm c">
	<?php the_content(); ?>
</div>
<?php
	get_footer();