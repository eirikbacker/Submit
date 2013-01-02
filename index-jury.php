<?php
	get_header();
	$cats = get_user_cats();
	$rate = wp_nonce_url(admin_url('admin-ajax.php?action=submit_rate'), 'submit_rate');
?>
<ul class="nav nav-tabs" id="cats">
	<?php foreach($cats as $cat){ ?>
		<li><a data-toggle="tab" href="#tab-<?php echo $cat; ?>"><?php echo get_category($cat)->name; ?></a></li>
	<?php } ?>
</ul>
<div class="tab-content">
	<?php foreach($cats as $cat){query_posts('posts_per_page=-1&cat=' . $cat); //&meta_key=payment ?>
		<div class="tab-pane active" id="tab-<?php echo $cat; ?>">
			<div class="accordion" id="collapse-<?php echo $cat; ?>">
				<?php while(have_posts()){the_post();
					var_export(array_keys(get_post_custom()));
					 ?>
					<div class="accordion-group">
						<div class="accordion-heading">
							<div class="btn-group" data-toggle="buttons-radio">
								<?php 
									$cur = intval(get_post_meta($post->ID, 'rate_' . $cat . '_' . get_current_user_id(), true));
									foreach(get_jury_rates() as $k=>$v){
										$val = esc_html(add_query_arg(array('cat'=>$cat, 'id'=>$post->ID, 'key'=>$k), $rate));
										echo '<a href="' . $val . '" class="btn btn-rate' . ($v == $cur? ' active' : '') . '">' . $v . '</a>';
									}
								?>
							</div>
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#collapse-<?php echo $cat; ?>" href="#post-<?php echo $cat . '-' . $post->ID; ?>">
								<span class="pull-right"><?php the_project('client');?></span>
								<h4><?php the_title(); ?></h4>
							</a>
						</div>
						<div id="post-<?php echo $cat . '-' . $post->ID; ?>" class="accordion-body collapse">
							<div class="accordion-inner">
								<ul class="thumbnails">
									<?php foreach(get_children('post_type=attachment&post_parent=' . $post->ID) as $v){
										$img = wp_attachment_is_image($v->ID);
										$ico = wp_get_attachment_image($v->ID, 'medium', true);
										$src = $img? reset(wp_get_attachment_image_src($v->ID, 'large')) : $v->guid;
										$rel = $img? 'lightbox['.$post->ID.']' : 'ext';
										$css = $img? 'span3' : 'span1';
			
										echo '<li class="' . $css . '"><a class="thumbnail" rel="' . $rel . '" href="' . $src . '">' . $ico . '</a></li>';
									} ?>
								</ul>
								<div class="btn-group">
									<?php if($web = get_the_project('url'))echo '<a class="btn" href="' . esc_attr($web) . '">Se webside</a>'; ?>
									<?php if($vid = get_the_project('video'))echo '<a class="btn" href="' . esc_attr($vid) . '">Se video</a>'; ?>
								</div>
								<div class="row-fluid">
									<div class="span4"><h5>Bakgrund og målsetting for oppdraget/arbeidet</h5><p><?php echo strip_author(get_the_project('background')); ?></p></div>
									<div class="span4"><h5>Konsept/Idé/strategi</h5><p><?php  echo strip_author(get_the_project('concept')); ?></p></div>
									<div class="span4"><h5>Spesielle rammebetingelser</h5><p><?php  echo strip_author(get_the_project('conditions')); ?></p></div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
<?php
	get_footer();