//Google Analytics
window._gaq = [['_setAccount','UA-0000000-0'],['_trackPageview'],['_trackPageLoadTime']];
//jQuery.ajax({dataType:'script', cache:true, url:('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js'});

//Smart links
jQuery(document).on('click', 'a', function(e){
	if(this.href.indexOf('.pdf') > 0)return !(window.open('http://docs.google.com/gview?embedded=true&url=' + this.href)||1);
	if(this.hostname && this.hostname !== location.hostname)return e.metaKey || !(window.open(this.href)||1);
});

//Equalize columns
jQuery(window).on('load resize', {}, function(e){
	var box = (e.data.box = e.data.box || jQuery('.equalize')).css('height','auto'), fix = [], x, cur;
	jQuery.each(box, function(){if(this.offsetTop !== x){x = this.offsetTop;fix.push(cur = [])}cur.push(this)});
	jQuery.each(fix, function(){if(this[1])jQuery(this).height(Math.max.apply(Math, jQuery.map(this, function(el){return jQuery(el).height()})))});
});

//Enable bootstrap stuff
jQuery(function(){
	jQuery('.alert').alert();
	jQuery('.btn[data]').button();
});

//Creditcard check
jQuery(document).on('keyup change', 'input', {Visa: /^4[0-9]{12}(?:[0-9]{3})?$/, MasterCard: /^5[1-5][0-9]{14}$/}, function(e){
	if(this.name != 'creditCardNumber')return;
	var self = e.data.self || (e.data.self = jQuery(this));
	var type = e.data.type || (e.data.type = jQuery('input[name="creditCardType"]'));
	var card = jQuery.map(e.data, function(v, k){if(jQuery.type(v) === 'regexp' && v.test(self.val()))return k}).pop() || '';
	self.attr('data-card', card);
	type.val(card);
});

//Save project
jQuery(document).on('submit', '.single-project', {}, function(e){
	var self = e.data.self || (e.data.self = jQuery(this));
	var save = e.data.save || (e.data.save = jQuery('[name="save"]', this));
	var stop = e.preventDefault();

	jQuery.post(self.attr('action'), self.serializeArray(), function(href){
		var a = jQuery.extend(document.createElement('a'), {href:href});
		if(a.hostname == location.hostname && a.href != location.href)location = a.href;
		else save.button('complete').delay(2000).queue(function(){save.button('reset')});
	});
});

//Delete images
jQuery(document).on('click', '.file', {}, function(e){
	var send = jQuery(this).closest('form').attr('action').split('?').shift();
	var data = jQuery(this).not('.file-upload').remove().find('input').val();
	if(data)jQuery.post(send, data);
});

//Plupload
jQuery(function(){
	jQuery('.file-upload').each(function(i){
		var settings = jQuery.parseJSON(jQuery.trim(jQuery('textarea', this).val()));
		var uploader = new plupload.Uploader(settings);
		var self = jQuery(this).on({
			'dragover':  function(e){e.preventDefault();self.addClass('hover')},
			'dragleave': function(e){e.preventDefault();self.removeClass('hover')},
		});

		uploader.init();
		uploader.bind('FilesAdded', function(up, files){
			jQuery.each(files, function(i, file){
				var prog = jQuery('<div>').addClass('file-prog progress progress-striped active');
				var size = jQuery('<div>').addClass('file-size').text(plupload.formatSize(0) + ' / ' + plupload.formatSize(file.size));
				var bar  = jQuery('<div>').addClass('file-bar bar').appendTo(prog);

				jQuery('<div>', {id:file.id}).addClass('file').append(size,prog).insertBefore(self);
			});
			self.removeClass('hover');
			up.refresh();
			up.start();
		});
		uploader.bind('UploadProgress', function(up, file){
			jQuery('#' + file.id + ' .file-size').text(plupload.formatSize(parseInt(file.size*file.percent/100)) + ' / ' + plupload.formatSize(file.size));
			jQuery('#' + file.id + ' .file-bar').width(file.percent + '%');
		});
		uploader.bind('FileUploaded', function(up, file, data){
			jQuery('#' + file.id).replaceWith(data.response);
		});
	});
});