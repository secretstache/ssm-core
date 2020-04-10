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

})( jQuery );