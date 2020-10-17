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
        register_setting( "ssm-core-settings-group", "ssm_core_userback_script" );

		add_settings_section( "ssm-core-agency-options", "Agency Options", array( $this, "ssmCoreAgencyOptions"), "ssm_core");

		add_settings_field( "ssm-core-agency-name", "Agency Name", array( $this, "ssmCoreAgencyName" ), "ssm_core", "ssm-core-agency-options" );
		add_settings_field( "ssm-core-agency-url", "Agency URL", array( $this, "ssmCoreAgencyUrl" ), "ssm_core", "ssm-core-agency-options" );
        add_settings_field( "ssm-core-login-logo", "Login Logo", array( $this, "ssmCoreLoginLogo" ), "ssm_core", "ssm-core-agency-options" );
        add_settings_field( "ssm-core-userback-script", "Userback Script", array( $this, "ssmCoreUserbackScript" ), "ssm_core", "ssm-core-agency-options" );

        add_settings_section( "ssm-core-acf-options", "Access Restriction", array( $this, "ssmAcfOptions" ), "ssm_core" );

		add_settings_field(
            "ssm-core-team-members",
            "SSM Team Members",
            array( $this, "ssmCoreTeamMembers" ),
            "ssm_core",
            "ssm-core-acf-options",
            [ "members" => get_users( array("role" => "administrator") ) ]
		);

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
     * Add "Userback Script" field
     */
    public function ssmCoreUserbackScript()
    {

        $userback_script = get_option("ssm_core_userback_script") != NULL ? esc_attr( get_option("ssm_core_userback_script") ) : "";

    ?>

        <textarea name="ssm_core_userback_script" cols="45" rows="10"><?php echo $userback_script ?></textarea>

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
    public function ssmAcfOptions() {}

	/**
	 * Inject internal WP JS variables on Core Settings page
	 */
	public function	enqueueWpMedia() {

		if ( get_current_screen()->base == "settings_page_ssm_core" ) {
 			wp_enqueue_media();
 		}

    }

}