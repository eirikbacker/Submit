<?php
	get_header();
	query_posts('posts_per_page=-1&author=' . get_current_user_id());
?>
<?php if(get_user_total()){ ?>
	<div class="alert alert-error">
		<strong>NB!</strong>
		<?php 
			$link = '<a href="' . get_bloginfo('url') . '/paypal/">' . __('sendt inn  og betalt', get_template()) . ' &rsaquo;</a>';
			echo sprintf(__('Husk at prosjektene ikke er registret før du har %s', get_template()), $link);
		?>
	</div>
<?php } ?>
<div class="row-fluid">
	<div class="span4">
		<div class="index-project muted equalize">
			<a class="btn btn-primary pull-right" href="<?php bloginfo('url'); ?>/single/">
				<i class="icon-plus icon-white"></i> 
				<?php _e('Legg til prosjekt', get_template()); ?>
			</a>
			<h4><?php _e('Nytt prosjekt', get_template()); ?></h4>
			<div><?php _e('Ukategorisert', get_template()); ?></div>
		</div>
	</div>
	<?php while(have_posts()){the_post(); ?>
		<?php if(!(($wp_query->current_post+1)%3))echo '</div><div class="row-fluid">'; ?>
		<div class="span4">
			<div class="index-project equalize">
				<a class="btn pull-right" href="<?php the_permalink(); ?>">
					<i class="icon-edit"></i> 
					<?php _e('Rediger', get_template()); ?>
				</a>
				<h4><?php echo ucfirst($post->post_title); ?></h4>
				<div><?php echo implode(', ', wp_list_pluck(get_the_category(), 'name')); ?></div>
				<?php if($total = get_post_total()){ ?>
					<span class="label label-important">
						<?php echo sprintf(__('Utestående %s,- NOK', get_template()), $total); ?>
					</span>
				<?php }else if(!get_the_category()){ ?>
					<span class="label label-important">
						<?php _e('Mangler kategori!', get_template()); ?>
					</span>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
<?php
	get_footer();