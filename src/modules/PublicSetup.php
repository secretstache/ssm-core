<?php

namespace SSM\Core;

class PublicSetup {

    /**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueueStyles()
	{
		wp_enqueue_style( "ssm", SSM_CORE_URL . "assets/styles/public.css", array(), "1.0", "all" );
	}

    /**
     * Show current year as a shortcode
	 */
    public function addYearShortcode()
    {
        add_shortcode("year", array( $this, "addYearShortcodeCB" ) );
    }

    /**
     * addYearShortcode() callback
	 */
    public function addYearShortcodeCB()
    {

        $year = date("Y");
        return $year;

    }

    /**
     * Set Favicon
     */
    public function setFavicon()
    {

        if ( $favicon = get_field("favicon", "options") ) {
            echo "<link rel=\"shortcut icon\" href=\"" . $favicon["url"] . "\" />";
        }

    }

    /**
     * Dynamically Adds the Facebook Pixel
     */
    public function doFacebookPixel()
    {

        if ( $fb_id = get_field("facebook_account_id", "options") ) {

            global $post;

            $fb_standard_event = "";
            $value = "";

            if ( get_field("facebook_standard_event") != NULL && get_field("facebook_standard_event") == "purchase" ) {

                if ( $value ) {
                    $fb_standard_event = "fbq(\"track\", \"Purchase\", {\"value\": \"" . $value . "\" , \"currency\" : \"USD\"});";
                } else {
                    $fb_standard_event = "fbq(\"track\", \"Purchase\");";
                }

            } elseif ( get_field("facebook_standard_event") != NULL ) {
                $fb_standard_event = get_field("facebook_standard_event");
            }

            ?>

            <!-- Facebook Pixel Code -->
            <script>
                !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
                n.push=n;n.loaded=!0;n.version="2.0";n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
                document,"script","https://connect.facebook.net/en_US/fbevents.js");
                fbq("init", "<?php echo $fb_id; ?>");
                fbq("track", "PageView");

                <?php echo $fb_standard_event; ?>

            </script>

            <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo $fb_id; ?>&ev=PageView&noscript=1"/></noscript>
            <!-- End Facebook Pixel Code -->
        <?php } ?>
    <?php }

    /**
     * Setup Google Tag Manager
     */
    public function setupGoogleTagManager()
    {
        ?>

        <?php if ( $gtm = get_field("google_tag_manager_id", "options") ) { ?>

        <!-- Begin Google Tag Manager -->
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({"gtm.start":
            new Date().getTime(),event:"gtm.js"});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!="dataLayer"?"&l="+l:"";j.async=true;j.src=
            "//www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,"script","dataLayer","<?php echo $gtm; ?>");
        </script>
        <!-- End Google Tag Manager -->

        <?php } ?>

    <?php }

	/**
     * Add Google Tag Manager <noscript>
     */
	public function addGoogleTagManagerNoScript() {

        if ( $gtm = get_field("google_tag_manager_id", "options") ) {
            echo "<noscript><iframe src=\"//www.googletagmanager.com/ns.html?id=" . $gtm . "\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>";
        }

	}

    /**
     * Setup Google Tag Manager
     */
    public function setupGoogleSiteVerification()
    {
        ?>

        <?php if ( $sv = get_field("google_site_verification_id", "options") ) { ?>

        <!-- Begin Google Search Console Verification -->
        <meta name="google-site-verification" content="<?php echo $sv; ?>" />
        <!-- End Begin Google Search Console Verification -->

        <?php } ?>

    <?php }

    /**
     * Custom Head Scripts
     */
    public function customHeadScripts()
    {

		$custom_scripts = get_field("custom_tracking_scripts", "options");

		if ( $custom_scripts ) {

            foreach ( $custom_scripts as $script ) {

                if ( $script["location"] == "header" && $script["script"] != NULL ) {
                    echo $script["script"];
                }
            }
        }
    }

    /**
     * Custom Footer Scripts
     */
    public function customFooterScripts()
    {

        $custom_scripts = get_field("custom_tracking_scripts", "options");

        if ( $custom_scripts ) {

            foreach ( $custom_scripts as $script ) {

                if ( $script["location"] == "footer" && $script["script"] != NULL ) {
                    echo $script["script"];
                }

            }
        }
    }

    /**
     * Injects inline CSS into the head
	 */
    public function injectInlineCss()
    {

        global $post;
        $styles = array();

        if ( $global_styles = get_field("global_inline_styles", "options") ) {
            $styles[] = $global_styles;
        }

        if ( $page_styles = get_field("page_inline_styles") ) {
            $styles[] = $page_styles;
        }

		$output = '';

        foreach ( $styles as $style ) {
            $output .= $style;
        }

        if ( $output != "" ) {
            echo "<style id=\"inline-css\">" . $output . "</style>";
        }

    }

    /**
     * Injects inline JS into the footer
	 */
    public function injectInlineJs()
    {
        global $post;

        if ( $page_script = get_field("page_inline_scripts") ) {
            echo "<script type=\"text/javascript\" id=\"inline-js\">" . $page_script . "</script>";
        }

    }

    /**
     * Conditionally shows message if URL contains ssmpb=save_reminder
     */
    public function saveReminderNotice()
    {

        if (isset($_GET["ssmpb"]) && trim($_GET["ssmpb"]) == "save_reminder") {

            global $post;

            echo "<div class=\"notice notice-warning is-dismissible\">";
            echo "<p>After you save this new " . get_post_type() . " item, you will need to reload the last page to retreive it.</p>";
            echo "</div>";
        }

	}

	/**
	 * Remove Admin Bar on frontend
	 *
	 */
	public function removeAdminBar() {

		return false;

	}

    /**
	 * Remove Admin Bar with custom Bar
	 *
	 */
	public function replaceAdminBar() {

		if ( is_user_logged_in() ) {

			global $post;
			$user = wp_get_current_user();

			if ( ! user_can( $user, "edit_pages" ) ) {
			    return;
			}

			echo "<div class=\"ssm-admin-menu\">";

                echo "<ul class=\"menu horizontal\">";

                if ( defined( "SSM_ENVIRONMENT" ) && $env = SSM_ENVIRONMENT ) {
                    echo "<li class=\"env env-" . sanitize_title_with_dashes( $env ) . "\">" . ucfirst( $env ) . " Environment</li>";
                }

                if( is_home() ) {

                    $edit_post_link = get_edit_post_link( get_option( 'page_for_posts' ) );

                    $post_label = "Page";

                } elseif ( is_page() || is_single() ) {

                    $edit_post_link = get_edit_post_link( $post->ID );

                    $post_label = get_post_type_object( get_post_type() )->labels->singular_name;

                } elseif( get_queried_object()->taxonomy ){

                    $term = get_term( get_queried_object()->term_id );
                    $edit_post_link = get_edit_term_link( $term->term_id, $term->taxonomy );

                    $post_label = get_taxonomy_labels( get_taxonomy( $term->taxonomy ) )->singular_name;

                } elseif( is_archive() ) {

                    $edit_post_link = admin_url( "edit.php?post_type=" . $post->post_type );

                    $post_label = get_post_type_object( $post->post_type )->labels->name;

                }

			    echo "<li><a href=\"" . $edit_post_link . "\">Edit " . $post_label . "</a></li>";
			    echo "<li><a href=\"" . admin_url() . "\">Admin Dashboard</a></li>";
			    echo "<li><a href=\"" . admin_url("admin.php?page=acf-options-brand-settings") . "\">Brand Settings</a></li>";

                echo "<li><a href=\"" . wp_logout_url() . "\">Logout</a></li>";

				echo "</ul>";

			echo "</div>";

		}

    }

    /**
	 * Replace standard meta="viewport"
	 *
	 */
    public function replaceMetaViewport() {
        echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0\">";
    }

    /**
	 * Add Userback script
	 *
	 */
    public function addUserbackScript() {

        if ( ( $userback_script = get_option("ssm_core_userback_script") ) && ( !empty( $userback_script ) ) && ( is_user_logged_in() ) ) {
            echo $userback_script;
        }
        
    }
    
}