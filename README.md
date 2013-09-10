Mustache Module for CM WordPress Helper
=======================================

Adds Mustache functionality to cubicmushroom/wordpress-helper

Register the module in the usual way…

    $plugin = CM_WP_Plugin::register( '<plugin>', __FILE__ );
    $plugin->register_module( 'mustache' );
    

To specify a directory to load templates from…

    $plugin->template_directory_is(
               dirname( __FILE__ ) . '/templates/internal'
           );
           

You can also chain the methods together if you prefer…

    $plugin->register_module( 'mustache' )
           ->template_directory_is(
               dirname( __FILE__ ) . '/templates/internal'
           );