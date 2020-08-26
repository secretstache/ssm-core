<?php

namespace SSM\Core;

class WhiteLabel {

    /**
	 * Disable unused widgets.
	 */
	public function removeWidgets()
	{

		unregister_widget( "WP_Widget_Pages" );
		unregister_widget( "WP_Widget_Calendar" );
		unregister_widget( "WP_Widget_Meta" );
		unregister_widget( "WP_Widget_Recent_Posts" );
		unregister_widget( "WP_Widget_Recent_Comments" );
		unregister_widget( "WP_Widget_RSS" );
		unregister_widget( "WP_Widget_Tag_Cloud" );

    }

    /**
	 * Remove default dasboards
	 */
	public function removeDashboardMeta()
	{

		remove_meta_box( "dashboard_right_now", "dashboard", "normal" );
		remove_meta_box( "dashboard_incoming_links", "dashboard", "normal" );
		remove_meta_box( "dashboard_plugins", "dashboard", "normal" );
		remove_meta_box( "dashboard_primary", "dashboard", "side" );
		remove_meta_box( "dashboard_secondary", "dashboard", "normal" );
		remove_meta_box( "dashboard_quick_press", "dashboard", "side" );
		remove_meta_box( "dashboard_recent_drafts", "dashboard", "side" );
		remove_meta_box( "dashboard_recent_comments", "dashboard", "normal" );
		remove_meta_box( "dashboard_activity", "dashboard", "normal");
		remove_meta_box( "rg_forms_dashboard", "dashboard", "normal" );
		remove_meta_box( "wpe_dify_news_feed", "dashboard", "normal" );
		remove_meta_box( "wpseo-dashboard-overview", "dashboard", "normal" );
		remove_meta_box( "ssm_main_dashboard_widget", "dashboard", "normal" );
		remove_action( "try_gutenberg_panel", "wp_try_gutenberg_panel" );

    }
    
    /**
	 * Makes the login screen"s logo link to your homepage, instead of to WordPress.org
	 */
	public function loginHeaderUrl( $url )
	{
		return home_url();
	}

	/**
	 * Makes the login screen"s logo title attribute your site title, instead of "Powered by WordPress".
	 */
	public function loginHeaderText()
	{
		return get_bloginfo( "name" );
    }
    
    	/**
	 * Replaces the login screen"s WordPress logo with the "login-logo.png"
	 */
	public function loginLogo()
	{
		$defaultLogo = SSM_CORE_URL . "assets/images/login-logo.png";
		$background_image =  get_option("ssm_core_login_logo") != NULL ? get_option("ssm_core_login_logo") : $defaultLogo;

        if ( $GLOBALS["pagenow"] === "wp-login.php" ):
            
    ?>
        <style type="text/css">
            body.login div#login h1 a {
                background-image: url(<?php echo $background_image; ?>) !important;
                background-repeat: no-repeat;
                background-size: contain;
                width: auto;
                height: 90px;
                margin-bottom: 15px;
            }
        </style>

    <?php

	    endif;
    }

	/**
	 * Makes WordPress-generated emails appear "from" your WordPress site name, instead of from "WordPress".
	 */
	public function mailFromName()
	{
		return get_option( "blogname" );
	}

	/**
	 * Makes WordPress-generated emails appear "from" your WordPress admin email address.
	 * Disabled by default, in case you don"t want to reveal your admin email.
	 */
	public function wpMailFrom()
	{
		return get_option( "admin_email" );
	}

	/**
	 * Removes the WP icon from the admin bar
	 * See: http://wp-snippets.com/remove-wordpress-logo-admin-bar/
	 */
	public function removeIconBar()
	{
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu("wp-logo");
	}

	/**
	 * Modify the admin footer text
	 * See: http://wp-snippets.com/change-footer-text-in-wp-admin/
	 */
	public function adminFooterText()
	{

		$footer_text = get_option("ssm_core_agency_name") != NULL ? get_option("ssm_core_agency_name") : "Secret Stache Media";
		$footer_link = get_option("ssm_core_agency_url") != NULL ? get_option("ssm_core_agency_url") : "http://secretstache.com";

		echo "Built by <a href=\"" . $footer_link . "\" target=\"_blank\">" . $footer_text . "</a> with WordPress.";
	}

}