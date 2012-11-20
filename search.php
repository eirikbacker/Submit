<?php
	get_header();
?>
<div id="filter">
	<ul class="cm c">
		<li><span class="relevanssi-count">Vi fant <?php echo $wp_query->found_posts; ?> treff på søket "<?php echo get_search_query(); ?>"</span></li>
	</ul>
</div>
<div id="cont" class="cm c">
	<div class="c2x3">
		<?php while(have_posts()){the_post(); ?>
			<div class="cf post">
				<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="post-excerpt"><?php the_excerpt(); ?></div>
			</div>
		<?php } ?>
		<div class="box">
			<?php echo paginate_links( array(
				'base' 		=> str_replace($big = 99999, '%#%', esc_url(get_pagenum_link($big))),
				'prev_text'    => __('&lsaquo; Forrige'),
				'next_text'    => __('Neste &rsaquo;'),
				'current' 	=> max( 1, get_query_var('paged')),
				'total' 	=> $wp_query->max_num_pages,
			)); ?>
		</div>
	</div>
	<div class="c1x3">
		<form class="box" method="get" action="<?php bloginfo('home'); ?>/">
			<input class="search-input" type="text" name="s" value="<?php echo get_search_query(); ?>">
			<button class="search-submit" type="submit">Søk</button>
		</form>
	</div>
</div>
<?php
	get_footer();