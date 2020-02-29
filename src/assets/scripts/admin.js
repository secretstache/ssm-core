(function( $ ) {

	$(document).ready(function() {

		var grid = $(".acf-columns-2 .wp-list-table");

		if (grid.length != 0) {

			grid.find("thead tr").append(
				"<th scope=\"col\" id=\"acf-fg-category\" class=\"manage-column column-acf-fg-category\">Category</th>"
			);

			grid.find("tbody tr").each(function() {

				if ( $(this).attr("class") ) {

					var category_class = $(this).attr("class").split(/\s+/).slice(-1)[0];
					var category = category_class.split("-");
					var category_name = "";

					switch (category.length) {
						case 2:
							category_name = category[1];
							break;
						case 3:
							category_name = category[1] + "-" + category[2];
							break;
						case 4:
							category_name = category[1] + "-" + category[2] + "-" + category[3];
							break;
						case 5:
							category_name = category[1] + "-" + category[2] + "-" + category[3] + "-" + category[4];
							break;
					}
				}

				if ( category_name ) {
					category_link = "/wp-admin/edit.php?post_type=acf-field-group&acf_category=" + category_name;

					$(this).append(
						"<td class=\"acf-fg-category column-acf-fg-category\" data-colname=\"Category\">\
							<a class=\"row-category\" href=\"" + category_link + "\" aria-label=\"“" + category_name + "” (Edit)\">" + category_name + "</a>\
						</td>"
					)
				}

			})

			grid.find("tfoot tr").append(
				"<th scope=\"col\" class=\"manage-column column-acf-fg-category\">Category</th>"
			)

		}

	});

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

	function copyToClipboard( element ) {

		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text()).select();
		document.execCommand("copy");
		$temp.remove();

	}

	$(document).on( "click", ".admin-credentials .copy-pass", function( e ) {

		e.preventDefault();

		var id = $(this).attr("id");

		if ( id == "copy-alex-pass" ) {

			copyToClipboard( "#alex-pass" );
			$(this).text("Copied").addClass("copied");
			$("#copy-rich-pass").text("Copy").removeClass("copied");

		} else {

			copyToClipboard( "#rich-pass" );
			$(this).text("Copied").addClass("copied");
			$("#copy-alex-pass").text("Copy").removeClass("copied");

		}


	});

	$(document).on( "click", ".admin-credentials .send-email", function( e ) {

		e.preventDefault();

		var email_address = $(this).data("email-address");
		var password = $(this).data("password");
		var username = $(this).data("username");

		$.ajax({

			url: custom.ajax_url,
			type: "post",
			async: false,
			ContentType: "application/json",

			data: {
				action: "send_admin_email",
				email_address: email_address,
				username: username,
				password: password
			},

			success: (html) => {

				if ( JSON.parse( html ) == true ) {
					$(this).text("Sent").addClass("sent");
				}

			},

		});


	});

	$(document).on( "click", ".admin-credentials .remove", function( e ) {

		e.preventDefault();

		var custom_action = $(this).attr("class").split(" ")[3];

		if (custom_action == "remove-user") {
			custom_value = $(this).data("reassign-id");
		} else if ( custom_action == "remove-option" ) {
			custom_value = $(this).data("option-name");
		}

		$.ajax({

			url: custom.ajax_url,
			type: "post",
			async: false,
			ContentType: "application/json",

			data: {
				action: "remove_from_admins",
				custom_action: custom_action,
				custom_value: custom_value
			},

			success: (html) => {

				if ( JSON.parse( html ) == true ) {
					$(this).parents(".user-pass").fadeOut();
				}

			},

		});


	});


})( jQuery );