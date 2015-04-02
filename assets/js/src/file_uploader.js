/**
 * WP User Manager
 * http://wp-user-manager.com
 *
 * Copyright (c) 2015 Alessandro Tesoro
 * Licensed under the GPLv2+ license.
 */

jQuery(function($) {

	$('.wpum-file-upload').fileupload({
		dataType: 'json',
		url: wpum_frontend_js.ajax,
		maxNumberOfFiles: 1,
		formData: {
			script: true,
			action: 'wpum_upload_file'
		},
		add: function (e, data) {
			var $file_field     = $( this );
			var $form           = $file_field.closest( 'form' );
			var $uploaded_files = $file_field.parent().find('.wpum-uploaded-files');
			var uploadErrors    = [];

			// Validate type
			var allowed_types = $(this).data('file_types');

			if ( allowed_types ) {
        		var acceptFileTypes = new RegExp( "(\.|\/)(" + allowed_types + ")$", "i" );

		        if ( data.originalFiles[0]['name'].length && ! acceptFileTypes.test( data.originalFiles[0]['name'] ) ) {
		        	uploadErrors.push( wpum_frontend_js.i18n_invalid_file_type + ' ' + allowed_types );
		        }
		    }

        	if ( uploadErrors.length > 0 ) {
            	alert( uploadErrors.join( "\n" ) );
			} else {
				$form.find(':input[type="submit"]').attr( 'disabled', 'disabled' );
				data.context = $('<progress value="" max="100"></progress>').appendTo( $uploaded_files );
				data.submit();
			}
		},
		progress: function (e, data) {
			var $file_field     = $( this );
			var $uploaded_files = $file_field.parent().find('.wpum-uploaded-files');
			var progress        = parseInt(data.loaded / data.total * 100, 10);
			data.context.val( progress );
		},
		done: function (e, data) {
			var $file_field     = $( this );
			var $form           = $file_field.closest( 'form' );
			var $uploaded_files = $file_field.parent().find('.wpum-uploaded-files');
			var multiple        = $file_field.attr( 'multiple' ) ? 1 : 0;
			var image_types     = [ 'jpg', 'gif', 'png', 'jpeg', 'jpe' ];

			data.context.remove();

			$.each(data.result.files, function(index, file) {
				if ( file.error ) {
					alert( file.error );
				} else {
					if ( $.inArray( file.extension, image_types ) >= 0 ) {
						var html = $.parseHTML( wpum_frontend_js.js_field_html_img );
						$( html ).find('.wpum-uploaded-file-preview img').attr( 'src', file.url );
					} else {
						var html = $.parseHTML( wpum_frontend_js.js_field_html );
						$( html ).find('.wpum-uploaded-file-name code').text( file.name );
					}

					$( html ).find('.input-text').val( file.url );
					$( html ).find('.input-text').attr( 'name', 'current_' + $file_field.attr( 'name' ) );

					if ( multiple ) {
						$uploaded_files.append( html );
					} else {
						$uploaded_files.html( html );
					}
				}
			});

			$form.find(':input[type="submit"]').removeAttr( 'disabled' );
		}
	});

});