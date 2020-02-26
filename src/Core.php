<?php

namespace SSM;

use SSM\Core\Loader;
use SSM\Core\AdminCleanup;

class Core {

    /**
	 * The loader that"s responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */
    protected $loader;
    
    /**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {

		$this->loader = new Loader();

        $this->defineConstants();

	}
	
	public function setup() {

        // $this->loader->add_action('after_setup_theme', $this, 'loadModules', 100 );

        add_action( 'after_setup_theme', array( $this, 'loadModules'), 100 );

    }
    
    private function defineConstants() {

        define( "SSM_CORE_URL", trailingslashit ( plugin_dir_url( __FILE__ ) ) );
        define( "SSM_CORE_DIR", plugin_dir_path( __FILE__ ) );

    }

    /**
	 * Load Modules
	 *
	 */
	public function loadModules()
	{

        global $_wp_theme_features;

        foreach ( glob(SSM_CORE_DIR . 'modules/config/*.json') as $file) {

            $module = 'ssm-' . basename($file, '.json'); // ssm-admin-cleanup

            if (isset($_wp_theme_features[$module])) {

                $$module = json_decode( file_get_contents( $file ), true ); // $ssm-admin-cleanup = array( ... )

				if ( isset( $$module["hooks"] ) && !empty( $$module["hooks"] ) ) { // $ssm-admin-cleanup["hooks"]
					$this->registerModule( $$module ); // registerModule( $ssm-admin-cleanup )
				}

            }

        }

        $this->loader->run();

	}

    /**
	 * Receive "unpacked" data from .json file and register corresponding hooks
	 */
	private function registerModule( $module ) {
        
        ${$module["slug"]} = new $module["class"]; //$plugin_admin_cleanup = new "SSM\Core\AdminCleanup"

		foreach ( $module["hooks"] as $hook ) {

			$priority = ( isset( $hook["priority"] ) && $hook["priority"] != "" ) ? $hook["priority"] : 10;
			$arguments = ( isset( $hook["arguments"] ) && $hook["arguments"] != "" ) ? $hook["arguments"] : 1;

			call_user_func_array(
				array( $this->loader, "add_{$hook["type"]}" ), // array( $this->loader, "add_action" )
				array( $hook["name"], ${$module["slug"]}, $hook["function"], $priority, $arguments ) // array( 'init', $plugin_admin_cleanup, 'removeRoles' )
			);

        }
        
	}


}