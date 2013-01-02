<?php
	get_header();
	query_posts('posts_per_page=-1&cat=' . implode(',', get_user_cats())); //&meta_key=payment

	$meta = array(
		/*'ad'          => 'AD',
		'leader'      => 'Prosjektleder',
		'text'        => 'Tekst',
		'consultant'  => 'Konsulent',
		'illustrator' => 'Illustrasjon',
		'photo'       => 'Foto',*/
		'client'      => 'Kunde',
		'url'         => 'Internettadresse',
		'video'       => 'Videoadresse (youtube/vimeo)',
	);
?>
<div class="accordion" id="posts">
	<?php while(have_posts()){the_post(); ?>
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#posts" href="#<?php echo $wp_query->current_post; ?>">
					<h4><?php the_title(); ?></h4>
				</a>
			</div>
			<div id="<?php echo $wp_query->current_post; ?>" class="accordion-body collapse">
				<div class="accordion-inner">
					<ul class="thumbnails">
						<?php foreach(get_children('post_type=attachment&post_parent=' . $post->ID) as $v){
							$ico = wp_get_attachment_image($v->ID, 'medium', true);
							$src = wp_get_attachment_image_src($v->ID, 'large');

							if(!wp_attachment_is_image($v->ID))echo '<li class="span1"><a class="thumbnail" href="' . $v->guid . '">' . $ico . '</a></li>';
							else echo '<li class="span3"><a class="thumbnail" rel="lightbox['.$post->ID.']" href="' . $src[0]  . '">' . $ico . '</a></li>';
						} ?>
					</ul>
					<div class="row-fluid">
						<div class="span4">
							<dl class="dl-horizontal">
								<?php foreach($meta as $k=>$v)if($k = get_the_project($k))echo '<dt>' . $v . '</dt><dd>' . $k . '</dd>'; ?>
							</dl>
						</div>
						<div class="span8">
							<h5>Bakgrund og målsetting for oppdraget/arbeidet</h5><p><?php echo strip_author(get_the_project('background')); ?></p>
							<h5>Konsept/Idé/strategi</h5><p><?php  echo strip_author(get_the_project('concept')); ?></p>
							<h5>Spesielle rammebetingelser</h5><p><?php  echo strip_author(get_the_project('conditions')); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
<?php
	get_footer();