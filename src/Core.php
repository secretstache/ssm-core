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

		// $this->setFrontModules();
		// $this->setObjectModules();
		// $this->runSSMCLI();

    }
    
    private function defineConstants() {

        define( "SSM_CORE_URL", trailingslashit ( plugin_dir_url( __FILE__ ) ) );
        define( "SSM_CORE_DIR", plugin_dir_path( __FILE__ ) );

    }

    public function setup() {

        $this->loader = new Loader();

        $this->defineConstants();

        // $this->loader->add_action('after_setup_theme', $this, 'loadModules', 100 );

        add_action( 'after_setup_theme', array( $this, 'loadModules'), 100 );

    }

    /**
	 * Load Modules
	 *
	 */
	public function loadModules()
	{

        global $_wp_theme_features;

        foreach ( glob(SSM_CORE_DIR . 'modules/config/*.json') as $file) {

            $module = 'ssm-' . basename($file, '.json'); // ssm-admin-setup

            if (isset($_wp_theme_features[$module])) {

                $$module = json_decode( file_get_contents( $file ), true ); // $required_plugins = array( ... )

				if ( isset( $$module["hooks"] ) && !empty( $$module["hooks"] ) ) { // if ( isset( $required_plugins["hooks"] ) && !empty( $required_plugins["hooks"] ) )
					$this->registerModule( $$module ); // registerModule( $required_plugins )
				}

            }

        }

        $this->loader->run();

	}

    /**
	 * Receive "unpacked" data from .json file and register corresponding hooks
	 */
	private function registerModule( $module ) {
        
        ${$module["slug"]} = new $module["class"]; //$plugin_front_setup = new "SSM\Front\FrontSetup"

		foreach ( $module["hooks"] as $hook ) {

			$priority = ( isset( $hook["priority"] ) && $hook["priority"] != "" ) ? $hook["priority"] : 10;
			$arguments = ( isset( $hook["arguments"] ) && $hook["arguments"] != "" ) ? $hook["arguments"] : 1;

			call_user_func_array(
				array( $this->loader, "add_{$hook["type"]}" ), // array( $this->loader, "add_action" )
				array( $hook["name"], ${$module["slug"]}, $hook["function"], $priority, $arguments ) // array( wp_enqueue_scripts, $plugin_front_setup, enqueueStyles )
			);

        }
        
	}


}