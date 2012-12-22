//Google Analytics
window._gaq = [['_setAccount','UA-0000000-0'],['_trackPageview'],['_trackPageLoadTime']];
//jQuery.ajax({dataType:'script', cache:true, url:('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js'});

//Smart links
jQuery(document).on('click', 'a', function(e){
	if(this.href.indexOf('.pdf') > 0)return !(window.open('http://docs.google.com/gview?embedded=true&url=' + this.href)||1);
	if(this.hostname && this.hostname !== location.hostname)return e.metaKey || !(window.open(this.href)||1);
});

//Equalize
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

//Post editor
jQuery(document).on('submit change', '.single-project', {}, function(e){
	var self = e.data.self || (e.data.self = jQuery(this));
	var save = e.data.save || (e.data.save = jQuery('[name="save"]', this));
	if(e.type === 'change')return save.prop('disabled', false).button('reset');
	
	//TODO: Make better by doing button states more visible

	var stop = e.preventDefault();
	var send = jQuery.post(self.attr('action'), self.serializeArray(), function(data){
		console.log(data);
		save.button('complete').prop('disabled', true);
	});
});

//Plupload
jQuery(function(){
	jQuery('.uploader').each(function(i, self){
		var textarea = jQuery('textarea:hidden', self);
		var settings = jQuery.parseJSON(jQuery.trim(textarea.val()));
		var uploader = new plupload.Uploader(settings);

		var drop = jQuery('#' + settings.drop_element);
		drop.on({
			'dragover': function(e){e.preventDefault();drop.addClass('hover')},
			'dragleave': function(e){e.preventDefault();drop.removeClass('hover')},
		});

		uploader.bind('Init', function(up){textarea.val('')});
		uploader.init();
		uploader.bind('FilesAdded', function(up, files){
			jQuery.each(files, function (i, file) {
				jQuery('.uploader-queue', self).append(
					'<div class="file" id="' + file.id + '"><b>' +
					file.name + '</b> (<span>' + plupload.formatSize(0) + '</span>/' + plupload.formatSize(file.size) + ') ' +
					'<div class="fileprogress"></div></div>');
			});
			up.refresh();
			up.start();
		});
		uploader.bind('UploadProgress', function (up, file) {
			jQuery('#' + file.id + " .fileprogress").width(file.percent + "%");
			jQuery('#' + file.id + " span").html(plupload.formatSize(parseInt(file.size * file.percent / 100)));
		});
		uploader.bind('FileUploaded', function(up, file, response){
			//console.log(response);
			var response = response["response"];
			var value = jQuery.trim(textarea.val());
			jQuery('#' + file.id + " .fileprogress").width('100%');
			jQuery('#' + file.id + " span").html('100%');
			textarea.val(value? value + ',' + response: response);
		});
	});
});