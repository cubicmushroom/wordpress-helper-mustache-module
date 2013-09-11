<?php

if (!class_exists('CM_WP_Module_Mustache_Context')) {

    class CM_WP_Module_Mustache_Context {

        /**
         * Context properties
         *
         * @var array
         */
        protected $context_properties = array();

        /**
         * Adds an item to the context peroperties
         * 
         * @param array|string $key If string, this is the key the value will be
         *                          stored under.
         *                          If array, the array keys & values will be used, &
         *                          the second argument is ignored
         * @param mixed $value      (optional) Value to be added to context
         *                          properties.
         *                          Not required if $key is an array
         *
         * @return void
         */
        public function add_context( $key, $value = null ) {
            if ( ! is_array( $key ) ) {
                $key = array( $key => $value );
            }

            foreach ( $key as $k => $v) {
                $this->context_properties[$k] = $v;
            }
        }


        /**
         * Confirms whether a 'property' is available
         *
         * This is required to allow support for the __get method with Mustache
         *
         * @param string  $what Name of the property to check
         *
         * @return boolean
         */
        public function __isset( $what ) {
            if ( ! isset( $this->context_properties[$what] ) ) {
                return false;
            }

            return true;
        }

        /**
         * Returns the context object form $this->context_properties array
         * 
         * @param string $what Key of what context property has been requested
         * 
         * @return mixed
         */
        public function __get( $what ) {
            if ( ! isset( $this->context_properties[$what] ) ) {
                return null;
            }

            return $this->context_properties[$what];
        }
    }
}