Mustache Module for CM WordPress Helper
=======================================

Adds Mustache functionality to cubicmushroom/wordpress-helper


Basic Usage
-----------

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

       
Form Handling Helper
--------------------

This module also provides context class/objects that are useful for rendering forms.  These offer the advantage over regular arrays or objects in that they provide an easy way to protect fields values using the nonce functionality.


### Setting up the form

To use this use something similar to the following code…

	// Prepare a CM_WP_Module_Mustache_Context_Form object for use, that 
	// will include the frm_action hidden field (see
	// cubicmushroom/wordpress-helper for action hook provided for forms
	// submitted with this property set)
    $form_context = $mustache->get_form_context( 'do_something' );
    
    // Add a regular context item
    $form_context->add_context( $key, $value );
    
    // Add multiple context items in 1 go
    $context_items = array(
        'key1' => 'value1',
        'key2' => 'value2',
   	);
    $form_context->add_context( $context_items );
    
    // Add a hidden field who's value is to be protected but the form
    $form_context->add_protected_value( '<field_name>', '<value>' );
    
	// Now use it as a Mustache context
	$mustache = new Mustache_Engine(
	    array(
	        'loader' => new Mustache_Loader_FilesystemLoader(
                /some/tempalte/dir,
                array( 'extension' => '.mhtml' )
            ),
	    )
	);
	
    $mustache->render( '<template_file>', $form_context );


### Rendering the form


Now, in your Mustache template, include the 'form_context_fields' tag in addition to the regular ones you wish to use.  This will include all the required hidden fields to support the nonce verification…

    <form class="signup" action="{{action_uri}}" method="post" accept-charset="utf-8">
        {{{form_context_fields}}}

        <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" class="form-control" id="name" name="signup[name]" placeholder="Your name">
        </div>

        <div class="form-group">
            <label for="organisation">Organisation</label>
            <input type="text" class="form-control" id="organisation" name="signup[organisation]" placeholder="Organisation">
        </div>

		<input type="submit" name="submit" value="Go">
    </form>
    
### Handling the submitted form

The form submission is handler automatically by the main wordpress helper library.  Any form submission that includes a `frm_action` property will automatically trigger an action hook.  The action hook triggered will be of the form `frm_action_{frm_action_value}`


### Verifying the form has not been tampered with

When the form is submitted, you can verify the hidden fields have not been tampered with using the following code…

    $mustache_module = $plugin->load_module( 'mustache' );
    if ( ! $mustache_module->verify_form_nonce( $_POST ) ) {
        // Do something because nonce check has failed!
    }