(function( $ ) {

	$(document).on( "click", ".core-settings-form #upload-image-button", function() {

		var send_attachment = wp.media.editor.send.attachment;
		var button = $(this);

		wp.media.editor.send.attachment = function(props, attachment) {

			var url = attachment.url;
			var origWidth = attachment.width;
			var origHeight = attachment.height;
			var orientation;
			var defaultLogo = login_logo.url;

			if ( origWidth > origHeight ) {
				orientation = "landscape";
			} else {
				orientation = "portrait";
			}

			if ( orientation == "landscape" && origWidth >= 290 ) {

				w = 290;
				h = w * (origHeight / origWidth);
				width = w.toString() + "px";
				height = h.toString() + "px";

			} else if ( orientation == "landscape" && origWidth < 290 ) {

				width = origWidth.toString() + "px";
				height = origHeight.toString() + "px";

			} else if ( orientation == "portrait" && origWidth >= 125 ) {

				w = 125;
				h = w * (origHeight / origWidth);
				width = w.toString() + "px";
				height = h.toString() + "px";

			} else if ( orientation == "portrait" && origWidth < 125 ) {

				width = origWidth.toString() + "px";
				height = origHeight.toString() + "px";

			}

			$("#ssm-core-login-logo").attr("value", url);
			$("#logo-preview").attr("src", url);
			$("#logo-preview").css({"width": width, "height": height});
			$("#ssm-core-login-logo-width").attr("value", width);
			$("#ssm-core-login-logo-height").attr("value", height);

			wp.media.editor.send.attachment = send_attachment;

	  };

	  wp.media.editor.open(button);
	  return false;

	});

	// The "Remove" button (remove the value from input type="hidden")
	$(document).on( "click", ".core-settings-form #remove-image-button", function() {

		var answer = confirm("Are you sure?");

		if (answer == true) {

			var defaultLogo = login_logo.url;

			$("#ssm-core-login-logo").attr("value", "");
			$("#logo-preview").attr("src", defaultLogo);
			$("#logo-preview").css({"width": "230px", "height": "auto"});
			$("#ssm-core-login-logo-width").attr("value", "");
			$("#ssm-core-login-logo-height").attr("value", "");

		}

		return false;

	});

})( jQuery );