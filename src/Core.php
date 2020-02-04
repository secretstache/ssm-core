<?php

namespace SSM;

class Core {

    public function setup() {

        add_filter('admin_footer_text', array( $this, 'override_admin_footer_text') );

    }

    /**
     * Modify the admin footer text
     */
    public function override_admin_footer_text() {

        $footer_text = "Secret Stache Media";
        $footer_link = "http://secretstache.com";

        echo "Built by <a href=\"" . $footer_link . "\" target=\"_blank\">" . $footer_text . "</a> with WordPress.";
    }

}