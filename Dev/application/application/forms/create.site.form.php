<?php

\Kernel()->events('ui')->addFilter('load_form', 'application_forms', function($attribs) {

    if($attribs['params']['name'] == 'site.create') {

        $attribs['params']['heading'] = 'Create New WordPress Site';
        $attribs['params']['buttons'] = array(
            'cancel'    => array(
                'class'  => 'default',
                'label'  => 'Cancel'
            ),
            'submit'    => array(
                'class'  => 'success',
                'label'  => 'Create'
            ),
        );

        $attribs['modal'] = array(
            //'keyboard'  => true,
            //'backdrop'  => 'static'
        );

        $attribs['fields']['formdesc']    = array(
            'type'          => 'text',
            'text'   => array(
                'margin-bottom' => '15px;',
                'content'   => 'Enter an unique name, or the domain, for your new site, and select the version of WordPress you would like to use.'
            ),
            'weight'        => 15
        );

        $attribs['fields']['name']    = array(
            'label'         => 'Site Name',
            'hide_label'    => true,
            'type'          => 'input',
            'input'         => 'text',
            'placeholder'   => 'Enter a site name or domain',
            //'description'   => 'This is a demo description for the above field ',
            //'options'       => null,
            'required'      => true,
            'validation'    => FORM_VALIDATE_TYPE_STRING,
            'validation_callback'   => function($value) {
                $tenant = get_session_tenant();
                $existing = \Model\Project::find_by_name_and_tenant_id($value['name'], $tenant->id);
                if($existing) {
                    return array('Site name "'. $value['name'] .'" can not be reused.');
                }
                return true;
            },
            'class'         => array(
                'wrapper'       => null,
                'input'         => null
            ),
            'append'        => null,
            'prepend'       => null,
            'weight'        => 25,
            'width'         => '308px'
        );

        $wp_options = array();
        foreach(get_wordpress_versions() as $version) {
            $wp_options[$version] = 'WP Version ' . $version;
        }

        $attribs['fields']['wpversion']    = array(
            'label'         => 'WP Version',
            'hide_label'    => true,
            'type'          => 'input',
            'input'         => 'select',
            'options'       => $wp_options,
            'placeholder'   => 'Select WP Version',
            'left-margin'   => '15px',
            'required'      => true,
            'class'         => array(
                'wrapper'       => null,
                'input'         => null
            ),
            'weight'        => 35,
            'width'         => '147px'
        );


    }
    return $attribs;
});


\Kernel()->events('ui')->addFilter('process_form_submission', 'application_forms', function($attribs) {
    if($attribs['params']['name'] == 'site.create') {
        $tenant = get_session_tenant();
        $user = get_session_user();
        $project = \Model\Application::create(array(
            'name'          => $attribs['values']['name'],
            'wp_version'    => $attribs['values']['wpversion'],
            'user_id'       => $user->id,
            'tenant_id'     => $tenant->id
        ));
        $attribs['notify'] = array(
            'type'      => 'success',
            'content'   => 'New application, "'.$project->name.'" has been successfully created'
        );
        $tenant->send_socket_message('update_content_element', array(
            'source'    => '/app'
        ));
    }
    return $attribs;
});