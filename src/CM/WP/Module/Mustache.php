<?php

class CM_WP_Module_Mustache extends CM_WP_Module {

	/**
	 * Mustache object for rendering
	 *
	 * @var Mustache_Engine
	 */
	protected $mustache;

	/**
	 * Stores the settings for initialising the Mustache_Engine object
	 *
	 * @var array
	 */
	protected $mustache_config = array();

	/**
	 * Prepares the mustache object for use
	 */
	public function __construct() {
	}

	/**
	 * Sets the template directory to use
	 *
	 * @param  string $dir Directory to use templates from
	 *
	 * @return void
	 */
	public function template_directory_is( $dir ) {
		$this->mustache_config['loader'] =
			new Mustache_Loader_FilesystemLoader(
				$dir,
				array( 'extension' => '.mhtml' )
			);

		$this->load_mustache();
	}

	/**
	 * Instantiates a CM_WP_Module_Mustache_Context object
	 *
	 * @return CM_WP_Module_Mustache_Context
	 */
	public function get_context() {
		return new CM_WP_Module_Mustache_Context();
	}




	/**************************
	 * Context helper methods *
	 **************************/

	/**
	 * Instantiates a CM_WP_Module_Mustache_Context_Form object
	 *
	 * @param string $frm_action frm_action for the form
	 *
	 * @return CM_WP_Module_Mustache_Context_Form
	 */
	public function get_form_context( $frm_action = null ) {
		if ( is_null( $frm_action ) ) {
			return new CM_WP_Module_Mustache_Context_Form();
		}

		return new CM_WP_Module_Mustache_Context_Form( $frm_action );
	}

	/**
	 * Verifies the submitted form data for forms build using a
	 * CM_WP_Module_Mustache_Context_Form context object to protect hidden form
	 * fields
	 *
	 * @param array $form_vars Form data (probably $_POST or $_GET)
	 *
	 * @return bool
	 */
	public function verify_form_nonce( $form_vars ) {
		return CM_WP_Module_Mustache_Context_Form::verify_nonce( $form_vars );
	}

	/**
	 * Calls the render method on the Mustache_Engine object
	 *
	 * @param string $template Template string/file
	 * @param array|object $context (optional) Context to pass to render for
	 *                               templating
	 *
	 * @return string
	 */
	public function render( $template, $context = array() ) {
		return $this->mustache->render( $template, $context );
	}



	/*********************************
	 * Mustache pass-through methods *
	 *********************************/

	/**
	 * Instantiates the mustache object using the config settings
	 *
	 * @return void
	 */
	protected function load_mustache() {
		$this->mustache = new Mustache_Engine( $this->mustache_config );
	}
}