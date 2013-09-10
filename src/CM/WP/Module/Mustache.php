<?php

if (!class_exists('CM_WP_Module_Mustache')) {

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
         * Instantiates the mustache object using the config settings
         * 
         * @return void
         */
        protected function load_mustache() {
            $this->mustache = new Mustache_Engine( $this->mustache_config );
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



        /*********************************
         * Mustache pass-through methods *
         *********************************/

        /**
         * Calls the render method on the Mustache_Engine object
         * 
         * @param string       $template Template string/file
         * @param array|object $context  Context to pass to render for templating
         *
         * @return string
         */
        public function render( $template, $context ) {
            return $this->mustache->render( $template, $context );
        }
    }
}