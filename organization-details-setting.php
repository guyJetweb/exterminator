<?php
add_shortcode('contact_code', 'contact_code_func');

function contact_code_func($atts) {
    
    $opening_hours = genesis_get_option('opening_hours');
        $opening_hours = explode(',', $opening_hours);
        
    $code = '
            <div itemscope="" itemtype="http://schema.org/LocalBusiness">
   <span itemprop="name">' . genesis_get_option('name') . '</span>
   <div itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
     <span itemprop="streetAddress">' . genesis_get_option('street') . '</span>
     <span itemprop="addressLocality">' . genesis_get_option('locality') . '</span>,
     <span itemprop="addressRegion">' . genesis_get_option('region') . '</span>
     <span itemprop="postalCode">' . genesis_get_option('code') . '</span>
   </div>
   Phone: <span itemprop="telephone">' . genesis_get_option('telephone') . '</span>';
    
    if ($opening_hours) {
    foreach ($opening_hours as $key => $value) {
        $code .= '<meta itemprop="openingHours" content="' . $value . '"/>';
    }
    }
    
       $code .= '<meta itemprop="paymentAccepted" content="' . genesis_get_option("payment_accepted") . '"/>
       <div style="display:none" itemprop="areaServed" itemscope itemtype="http://schema.org/' . genesis_get_option("area_served_type") . '"/>
       <meta itemprop="name" content="' . genesis_get_option("area_served_name") . '"/>
       </div>
       <meta itemprop="isicV4" content="' . genesis_get_option("isicV4") . '"/>
       <meta itemprop="numberOfEmployees" content="' . genesis_get_option("number_of_employees") . '"/>
       <meta itemprop="Description" content="' . get_bloginfo( 'description' ) . '"/>
       <meta itemprop="url" content="' . home_url() . '"/>
       <meta itemprop="logo" content="' . get_header_image() . '"/>
</div>
';
    return $code;
}

function json_scheme_script() {
    if (genesis_get_option('json-footer-option') == '1') {
        
        $opening_hours = genesis_get_option('opening_hours');
        $opening_hours = implode("\",\"", explode(',', $opening_hours));
        $opening_hours = "\"" . $opening_hours . "\"";
        
        ?>
        <script type="application/ld+json">
            {  
                "@context":"http://schema.org",
                "@type":"ProfessionalService",
                "address":{  
                   "@type":"PostalAddress",
                   "addressLocality":"<?php echo genesis_get_option('locality') ?>",
                   "postalCode":"<?php echo genesis_get_option('code') ?>",
                   "streetAddress":"<?php echo genesis_get_option('street') ?>"
                },
                "name":"<?php echo genesis_get_option('name') ?>",
                "telephone":"<?php echo genesis_get_option('telephone') ?>",
                "openingHours" : [
                    <?php echo $opening_hours ?>
                ],
                "paymentAccepted" : "<?php echo genesis_get_option('payment_accepted') ?>",
                "areaServed" : {
                    "@type": "<?php echo genesis_get_option('area_served_type') ?>",
                    "name": "<?php echo genesis_get_option('area_served_name') ?>"
                },
                "email" : "<?php echo genesis_get_option('schema_email') ?>",
                "isicV4" : "<?php echo genesis_get_option('isicV4') ?>",
                "numberOfEmployees" : "<?php echo genesis_get_option('number_of_employees') ?>",
                "Description" : "<?php echo get_bloginfo( 'description' ); ?>",
                "url" : "<?php echo home_url() ?>",
                "logo" : "<?php echo get_header_image(); ?>"
                }
                
        </script>
        <?php
    }
}

add_action('wp_footer', 'json_scheme_script');

add_filter('genesis_theme_settings_defaults', 'org_details_defaults');
add_action('genesis_settings_sanitizer_init', 'register_org_details_sanitization_filters');
add_action('genesis_theme_settings_metaboxes', 'register_org_details_settings_box');

function org_details_defaults($defaults) {
    $defaults['json-footer-option'] = '0';
    $defaults['name'] = '';
    $defaults['street'] = '';
    $defaults['locality'] = '';
    $defaults['region'] = '';
    $defaults['code'] = '';
    $defaults['telephone'] = '';
    $defaults['schema_email'] = '';
    $defaults['number_of_employees'] = '';
    $defaults['area_served_name'] = '';
    $defaults['area_served_type'] = '';
    $defaults['payment_accepted'] = '';
    $defaults['opening_hours'] = '';
    return $defaults;
}

function register_org_details_sanitization_filters() {
    genesis_add_option_filter(
            'no_html', GENESIS_SETTINGS_FIELD, array(
        'json-footer-option',
        'name',
        'street',
        'locality',
        'region',
        'code',
        'telephone',
        'schema_email',
        'number_of_employees',
        'area_served_name',
        'area_served_type',
        'payment_accepted',
        'opening_hours',
            )
    );
}

function register_org_details_settings_box($_genesis_theme_settings_pagehook) {
    add_meta_box('org_details-settings', 'Organization Details', 'org_details_box', $_genesis_theme_settings_pagehook, 'main', 'high');
}

function org_details_box() {
    $checked = genesis_get_option('json-footer-option');
    ?>
    <br>
    <p>
        <input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[json-footer-option]" value="1" <?php checked($checked) ?>/> JSON footer
    </p>
    <p>

        Organization Name:<br> <input type="text"  name="<?php echo GENESIS_SETTINGS_FIELD; ?>[name]" value="<?php echo genesis_get_option('name'); ?>" size="50" /><br>
        Street Address:<br> <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[street]" value="<?php echo genesis_get_option('street') ?>" size="50" /><br>
        Address Locality:<br> <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[locality]" value="<?php echo genesis_get_option('locality') ?>" size="50" /><br>
        Address Region:<br> <input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[region]" value="<?php echo genesis_get_option('region') ?>" size="50" /><br>
        Postal Code: <br><input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[code]" value="<?php echo genesis_get_option('code') ?>" size="50" /><br>
        Telephone: <br><input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[telephone]" value="<?php echo genesis_get_option('telephone') ?>" size="50" /><br>
        Email: <br><input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[schema_email]" value="<?php echo genesis_get_option('schema_email') ?>" size="50" /><br>
        Number Of Employees: <br><input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[number_of_employees]" value="<?php echo genesis_get_option('number_of_employees') ?>" size="50" /><br>
        Area Served Type: (City,Country,State) <br><input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[area_served_type]" value="<?php echo genesis_get_option('area_served_type') ?>" size="50" /><br>
        Area Served Name: <br><input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[area_served_name]" value="<?php echo genesis_get_option('area_served_name') ?>" size="50" /><br>
        Payment Accepted: <br><input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[payment_accepted]" value="<?php echo genesis_get_option('payment_accepted') ?>" size="50" /><br>
        Opening Hours: <br><input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[opening_hours]" value="<?php echo genesis_get_option('opening_hours') ?>" size="50" /><br>
        <br> Enter opening hours in this format: example Mo-Sa 11:00-14:30,Mo-Th 17:00-21:30,Fr-Sa 17:00-22:00
    </p>


    <?php
}
