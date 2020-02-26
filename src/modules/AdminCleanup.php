<?php

namespace SSM\Core;

class AdminCleanup {

    /**
     * Modify the admin footer text
     */
    public function adminFooterText() {

        $footer_text = "Secret Stache Media";
        $footer_link = "http://secretstache.com";

        echo "Built by <a href=\"" . $footer_link . "\" target=\"_blank\">" . $footer_text . "</a> with WordPress.";
    }

    /**
	 * Remove unnecessary standard WP Roles
	 */
	public function removeRoles()
	{

		remove_role( "subscriber" );
		remove_role( "contributor" );

	}

}