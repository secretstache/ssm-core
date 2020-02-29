<?php

namespace SSM\Core;

class OptionsPage {

    /**
     * Register SSM Core Settings
     */
    public function ssmCoreSettings()
    {

		register_setting( "ssm-core-settings-group", "ssm_core_acf_admin_users" );
		register_setting( "ssm-core-settings-group", "ssm_core_team_members" );

		register_setting( "ssm-core-settings-group", "ssm_core_agency_name" );
		register_setting( "ssm-core-settings-group", "ssm_core_agency_url" );

		register_setting( "ssm-core-settings-group", "ssm_core_login_logo" );
		register_setting( "ssm-core-settings-group", "ssm_core_login_logo_width" );
        register_setting( "ssm-core-settings-group", "ssm_core_login_logo_height" );

        if ( get_option( "alex_pass" ) || get_option( "rich_pass" ) ) {
            add_settings_section( "ssm-core-admin-credentials", "Admin Credentials", array( $this, "ssmCoreAdminCredentials"), "ssm_core");
        }

		add_settings_section( "ssm-core-agency-options", "Agency Options", array( $this, "ssmCoreAgencyOptions"), "ssm_core");

		add_settings_field( "ssm-core-agency-name", "Agency Name", array( $this, "ssmCoreAgencyName" ), "ssm_core", "ssm-core-agency-options" );
		add_settings_field( "ssm-core-agency-url", "Agency URL", array( $this, "ssmCoreAgencyUrl" ), "ssm_core", "ssm-core-agency-options" );
        add_settings_field( "ssm-core-login-logo", "Login Logo", array( $this, "ssmCoreLoginLogo" ), "ssm_core", "ssm-core-agency-options" );

        add_settings_section( "ssm-core-acf-options", "Access Restriction", array( $this, "ssm_acf_options" ), "ssm_core" );

		add_settings_field(
            "ssm-core-team-members",
            "SSM Team Members",
            array( $this, "ssmCoreTeamMembers" ),
            "ssm_core",
            "ssm-core-acf-options",
            [ "members" => get_users( array("role" => "administrator") ) ]
		);

        add_settings_field(
            "ssm-core-acf-admin-users",
            "Users with ACF access",
            array( $this, "ssmCoreACFAdminUsers" ),
            "ssm_core",
            "ssm-core-acf-options",
            [ "admins" => get_users( array("role" => "administrator") ) ]
		);

    }

	public function ssmCoreAdminCredentials()
	{

        echo "<div class=\"admin-credentials\">";

            echo "<p class=\"desc\">Please, make sure you copied and saved your password before removing corresponding option / common admin user.</p>";

            if ( $alex_pass = get_option( "alex_pass" ) ) {

                echo "<p class=\"user-pass\"><span class=\"username\">alex: </span> <span id=\"alex-pass\">" . $alex_pass . "</span>
						<button class=\"button button-primary copy-pass\" id=\"copy-alex-pass\">Copy</button>
						<button class=\"button button-primary send-email\" data-password=\"" . $alex_pass . "\" data-username=\"alex\" data-email-address=\"alex@secretstache.com\">Send Email</button>
                        <button class=\"button button-primary remove remove-option\" data-option-name=\"alex_pass\">Remove Option</button>
                    </p>";

            }

            if ( $rich_pass = get_option( "rich_pass" ) ) {

                echo "<p class=\"user-pass\"><span class=\"username\">jrstaatsiii: </span> <span id=\"rich-pass\">" . $rich_pass . "</span>
						<button class=\"button button-primary copy-pass\" id=\"copy-rich-pass\">Copy</button>
						<button class=\"button button-primary send-email\" data-password=\"" . $rich_pass . "\" data-username=\"jrstaatsiii\" data-email-address=\"rich@secretstache.com\">Send Email</button>
                        <button class=\"button button-primary remove remove-option\" data-option-name=\"rich_pass\">Remove Option</button>
                    </p>";
            }

            if ( $admin_id = username_exists("admin") ) {

                $reassign_id = ( username_exists("jrstaatsiii") ) ? username_exists("jrstaatsiii") : username_exists("alex");

                echo "<p class=\"user-pass\"><span class=\"username\">admin: </span> admin123
                        <button class=\"button button-primary remove remove-user\" data-reassign-id=\"" . $reassign_id . "\">Remove User</button>
                    </p>";

            }

        echo "</div>";

    }


    /**
     * Add Admin users who need access to ACF field
     */
	function ssmCoreACFAdminUsers( $args )
	{

        $admins = $args["admins"];
        $acfAdmins = get_option("ssm_core_acf_admin_users") != NULL ? get_option("ssm_core_acf_admin_users") : array();

        ?>

        <select id="ssm-core-acf-admin-users" name="ssm_core_acf_admin_users[]" multiple style="min-width: 200px;">

            <?php foreach ( $admins as $admin ) { ?>

                <?php $selected = in_array( $admin->ID, $acfAdmins ) ? " selected" : ""; ?>

                <option value="<?php echo $admin->ID; ?>"<?php echo $selected; ?>>
                    <?php echo $admin->user_login; ?>
                </option>

            <?php } ?>

        </select>

        <?php
	}

	/**
     * Add SSM Team Members
     */
	function ssmCoreTeamMembers( $args )
	{

        $admins = $args["members"];
        $membersOption = get_option("ssm_core_team_members") != NULL ? get_option("ssm_core_team_members") : array();

        ?>

        <select id="ssm-core-team-members" name="ssm_core_team_members[]" multiple style="min-width: 200px;">

            <?php foreach ( $admins as $admin ) { ?>

                <?php $selected = in_array( $admin->ID, $membersOption ) ? " selected" : ""; ?>

                <option value="<?php echo $admin->ID; ?>"<?php echo $selected; ?>>
                    <?php echo $admin->user_login; ?>
                </option>

            <?php } ?>

        </select>

        <?php
    }

    /**
     * Add "Agency Name" field
     */
    public function ssmCoreAgencyName()
    {
        $agency_name = get_option("ssm_core_agency_name") != NULL ? esc_attr( get_option("ssm_core_agency_name") ) : "Secret Stache Media";
        ?>

        <input type="text" name="ssm_core_agency_name" value="<?php echo $agency_name ?>" class="regular-text"/>

    <?php
    }

    /**
     * Add "Agency URL" field
     */
    public function ssmCoreAgencyUrl()
    {
        $agency_URL = get_option("ssm_core_agency_url") != NULL ? esc_attr( get_option("ssm_core_agency_url") ) : "https://secretstache.com";
        ?>

		<input type="text" name="ssm_core_agency_url" value="<?php echo $agency_URL ?>" class="regular-text url"/>
		<p class="description">Include <code>http(s)://</code></p>

    <?php
    }

    /**
     * Add "Agency Logo" field
     */
    public function ssmCoreLoginLogo()
    {

        $default_logo = SSM_CORE_URL . "assets/images/login-logo.png";
        $login_logo = get_option("ssm_core_login_logo") != NULL ? esc_attr( get_option("ssm_core_login_logo") ) : $default_logo;

    ?>

	    <div class="login-logo-wrap">

            <img src="<?php echo $login_logo ?>" id="logo-preview" class="login-logo" alt="Login Logo" style="height: auto; width: 230px" />

            <div class="media-buttons">
                <input type="button" id="upload-image-button" class="button button-secondary" value="Upload Logo" />
                <input type="button" id="remove-image-button" class="button button-secondary" value="Remove Logo" />
            </div>

            <input type="hidden" id="ssm-core-login-logo" name="ssm_core_login_logo" value="<?php echo $login_logo ?>">
            <input type="hidden" id="ssm-core-login-logo-width" name="ssm_core_login_logo_width" value="230px">
            <input type="hidden" id="ssm-core-login-logo-height" name="ssm_core_login_logo_height" value="auto">

        </div>

    <?php

	}

    /**
     * Add Options Page
     */
    public function addSsmOptionsPage()
    {

		add_submenu_page(
		    "options-general.php",
		    "SSM Core", // page title
		    "Core", // menu title
		    "manage_options",
		    "ssm_core",
	        array( $this, "ssmCoreOptionsPage" )
	    );

	}

    /**
     * Add "Agency Name" field - include template
     */
    public function ssmCoreOptionsPage()
    {
    ?>

        <div class="wrap">

            <?php if ( get_option("ssm_core_agency_name") ) { ?>

                <h1><?php echo get_option("ssm_core_agency_name"); ?> Admin Core</h1>

            <?php } else { ?>

                <h1>Admin Core</h1>

            <?php } ?>

            <div class="core-settings-form">

                <form method="post" action="options.php">

                    <?php settings_fields( "ssm-core-settings-group" ); ?>
                    <?php do_settings_sections( "ssm_core" ); ?>

                    <?php submit_button(); ?>

                </form>

            </div>

        </div>

    <?php
    }

    /**
     * Empty functions we are obligatory to leave here
     * since they are callbacks for field declarations
     */
    public function ssmCoreAgencyOptions() {}
    public function ssmCoreAdminModules() {}
    public function ssmCoreFrontModules() {}
    public function ssmCoreHelpers() {}
    public function ssm_acf_options() {}

	/**
	 * Inject internal WP JS variables on Core Settings page
	 */
	public function	enqueueWpMedia() {

		if ( get_current_screen()->base == "settings_page_ssm_core" ) {
 			wp_enqueue_media();
 		}

    }

}