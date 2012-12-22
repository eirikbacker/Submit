<?php
	//Prevent users from seeing eachothers prjects
	if(isset($post) && get_current_user_id() != $post->post_author)wp_redirect(home_url());
	get_header();

	$meta = array(
		'client'      => 'Kunde',
		'ad'          => 'AD',
		'leader'      => 'Prosjektleder',
		'text'        => 'Tekst',
		'consultant'  => 'Konsulent',
		'illustrator' => 'Illustrasjon',
		'photo'       => 'Foto',
		'url'         => 'Internettadresse',
	);
?>
<form class="single-project" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>?action=submit_save">
	<dl class="dl-horizontal">
		<dt><i class="icon-briefcase"></i> <?php _e('Tittel', get_template()); ?></dt>
		<dd><input class="input-block-level" type="text" name="title" value="<?php echo esc_attr(get_the_title()); ?>"></dd>

		<dt><i class="icon-info-sign"></i> <?php _e('Info', get_template()); ?></dt>
		<dd>
			<div class="row-fluid">
				<div class="span4"><label>Byrå/Utøver</label><input class="input-block-level" type="text" name="agency" value="<?php echo wp_get_current_user()->user_login; ?>" readonly="readonly"></div>
				<?php if($i=1)foreach($meta as $k=>$v){ ?>
					<?php if(!($i++%3))echo '</div><div class="row-fluid">'; ?>
					<div class="span4">
						<label><?php echo $v; ?></label>
						<input class="input-block-level" type="text" name="<?php echo $k; ?>" value="<?php the_project($k); ?>">
					</div>
				<?php } ?>
			</div>
		</dd>

		<dt><i class="icon-align-left"></i> <?php _e('Beskrivelse', get_template()); ?></dt>
		<dd>
			<div class="row-fluid">
				<div class="span4"><label>Bakgrund og målsetting for oppdraget/arbeidet</label><textarea class="input-block-level" rows="6" name="background"><?php the_project('background'); ?></textarea></div>
				<div class="span4"><label>Konsept/Idé/strategi</label><textarea class="input-block-level" rows="6" name="concept"><?php the_project('concept'); ?></textarea></div>
				<div class="span4"><label>Spesielle rammebetingelser</label><textarea class="input-block-level" rows="6" name="conditions"><?php the_project('conditions'); ?></textarea></div>
			</div>
		</dd>
		
		<dt><i class="icon-eye-open"></i> <?php _e('Media', get_template()); ?></dt>
		<dd>
			<div id="uploader" class="uploader">
				<label class="muted"><?php _e('Slipp filer her', get_template()); ?></label>
				<a id="uploader-button" class="btn" href="#"><i class="icon-plus"></i> <?php _e('Velg filer', get_template()); ?></a>
				<div class="uploader-queue"></div>
				<textarea style="display:none" name="files">
					<?php echo json_encode(array(
						'runtimes'            => 'html5,silverlight,flash,html4',
						'container'           => 'uploader',
						'drop_element'        => 'uploader',
						'browse_button'       => 'uploader-button',
						'file_data_name'      => 'async-upload',
						'max_file_size'       => '20mb',
						'url'                 => admin_url('admin-ajax.php'),
						'flash_swf_url'       => includes_url('js/plupload/plupload.flash.swf'),
						'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
						'filters'             => array(array('title'=>'Allowed Files', 'extensions'=>'*')),
						'multiple_queues'     => true,
						'multipart'           => true,
						'urlstream_upload'    => true,
						'multi_selection'     => true,
						'multipart_params'    => array(							//additional post data to send to our ajax hook
							'_ajax_nonce' => wp_create_nonce('submit_file'),	//will be added per uploader
							'action'      => 'submit_file',						//the ajax action name
						)
					)); ?>
				</textarea>
				<!--<div class="progress progress-striped active"><div class="bar" style="width:40%"></div></div>-->
			</div>
		</dd>
		
		<dt><i class="icon-tags"></i> <?php _e('Kategorier', get_template()); ?></dt>
		<dd>
			<div class="accordion" id="cats">
				<?php foreach(get_categories('hide_empty=&orderby=name&exclude=1') as $k=>$v)if($k = $v->term_id){ ?>
					<div class="accordion-group">
						<div class="accordion-heading">
							<input type="checkbox" name="cats[]" value="<?php echo $k;if(in_category($k))echo '" checked="checked'; ?>">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#cats" href="#<?php echo $k; ?>">
								<span class="label label-info pull-right"><?php echo intval(get_term_meta($k, 'price')); ?> NOK</span>
								<?php echo $v->name; ?>
							</a>
						</div>
						<div id="<?php echo $k; ?>" class="accordion-body collapse">
							<div class="accordion-inner"><?php echo $v->description; ?></div>
						</div>
					</div>
				<?php } ?>
			</div>
		</dd>

		<dt>&nbsp;</dt>
		<dd>
			<br>
			<button type="submit" name="save" disabled="disabled" class="btn btn-primary" 
				data-complete-text="<i class='icon-white icon-ok'></i> <?php _e('Prosjektet er lagret!', get_template()); ?>" 
				 data-loading-text="<i class='icon-white icon-time'></i> <?php _e('Lagrer...', get_template()); ?>">
				<?php _e('Lagre prosjekt', get_template()); ?>
			</button>
			<a class="btn btn-danger" href="#delete" data-toggle="modal">
				<i class="icon-white icon-trash"></i> 
				<?php _e('Slett', get_template()); ?>
			</a>
			<input type="hidden" name="ID" value="<?php echo isset($post)? get_the_ID() : '0'; ?>">
			<?php wp_nonce_field('submit_save'); ?>
		</dd>
	</dl>

	<div id="delete" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4><?php _e('Sikker på at du vil slette prosjektet', get_template());if($t=get_the_title())echo ' "'.$t.'"'; ?>?</h4>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal"><?php _e('Nei, ikke slett!', get_template()); ?></button>
			<a href="#" class="btn btn-danger"><?php _e('Ja, vekk med det', get_template()); ?></a>
		</div>
	</div>
</form>
<?php
	get_footer();