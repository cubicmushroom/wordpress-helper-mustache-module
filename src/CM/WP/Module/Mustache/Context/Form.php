<?php

if (!class_exists('CM_WP_Module_Mustache_Context_Form')) {

    class CM_WP_Module_Mustache_Context_Form extends CM_WP_Module_Mustache_Context {

        const HTML_HIDDEN_INPUT = '<input type="hidden" name="%s" value="%s">';
        const PROTECTED_FIELDS_KEY = 'cm_protected_fields';



        /********************************
         * Static methods for verifying *
         ********************************/

        /**
         * Works out the action string based on the protected values
         *
         * @param array $values Array of values for hidden strings
         * @return [type]         [description]
         */
        static protected function build_action_str( $values ) {
            sort( $values );
            $action_str = md5( implode( ':', $values ) );
            return $action_str;
        }


        static public function verify_nonce( $form_vars ) {
            // Get nonce value
            $nonce = $form_vars['_cm_wpnonce'];

            // Get names of protected fields
            $field_names = explode( ':', $form_vars[self::PROTECTED_FIELDS_KEY] );

            // Build the action based on the values of the protected fields
            $values = array();
            foreach ( $field_names as $field_name) {

                // If field name indicates an array (e.g. signup[product_id]) we need
                // to work out how to access that variable
                $name_arr = explode(
                    '##',
                    preg_replace( '/\[([^\]]+)\]/', '##$1', $field_name )
                );

                $var = $form_vars;
                while ( 0 < count( $name_arr ) ) {
                    $var = $var[array_shift( $name_arr )];
                }

                $values[] = $var;
            }
            $nonce_action = self::build_action_str( $values );

            return wp_verify_nonce( $nonce, $nonce_action );
        }




        /*******************************
         * Object properties & methods *
         *******************************/

        /**
         * The frm_action for the form, used for automatic handling of form
         * submissions
         *
         * @var string
         */
        protected $frm_action;
        
        /**
         * Hidden fields that are nonce protected
         * 
         * @var array
         */
        protected $protected_hidden_fields = array();


        /**
         * Optionally provides the frm_action for the form
         *
         * @param string $frm_action (optional) frm_action for the form
         */
        public function __construct( $frm_action = null ) {
            if ( ! is_null( $frm_action ) ) {
                $this->frm_action = $frm_action;

                $this->add_protected_value( 'frm_action', $this->frm_action );
            }

            // parent::__construct();
        }


        /**
         * Adds a protected key value pair to be added as a hidden field
         *
         * @param string     $name  Name for the field
         * @param string|int $value Value for the field
         */
        public function add_protected_value( $name, $value ) {
            // We need to cast the $value as a string, so that when the values are
            // sorted to build the nonce action string prior to the form submission
            // the order is the same as after the form has posted (& all the values
            // have been converted to strings during the form submission)
            $this->protected_hidden_fields[$name] = (string) $value;
        }


        /**
         * Method to return the the HTML for the protected, hidden fields, nonce
         * field & frm_action
         *
         * @return string HTML
         */
        public function form_context_fields() {

            $fields = $this->protected_hidden_fields;

            $input_fields = array();
            foreach ( $fields as $name => $value ) {
                $input_fields[] = sprintf( self::HTML_HIDDEN_INPUT, $name, $value );
            }

            // Add a hidden field with the names of the protected fields
            $input_fields[] = sprintf(
                self::HTML_HIDDEN_INPUT,
                self::PROTECTED_FIELDS_KEY,
                implode( ':', array_keys( $fields ) )
            );

            // Add the nonce field
            $input_fields[] = wp_nonce_field(
                self::build_action_str( array_values( $fields ) ),
                '_cm_wpnonce',
                true,
                false
            );

            return implode( '', $input_fields );
        }

    }
}