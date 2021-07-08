jQuery(document).ready(function($) {
	var mediaUploader;
	$('#image-upload').on('click', function(e){
		e.preventDefault();
		if( mediaUploader ){
			mediaUploader.open();
			return;
		}
		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Upload Image to Gallery',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});
		mediaUploader.on('select', function(){
			attachment = mediaUploader.state().get('selection').first().toJSON();
			$('#gallery-image').val(attachment.url);
			$('#gallery-image-preview').html('<img src="'+ attachment.url +'" width="80px" height="50px"/>');
		});
		mediaUploader.open();
	});
});
