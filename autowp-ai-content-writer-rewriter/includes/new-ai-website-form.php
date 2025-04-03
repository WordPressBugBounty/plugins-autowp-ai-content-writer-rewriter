<?php 

// Function to parse the prompt code and return an associative array of values
function autowp_parse_prompt_code($prompt_string) {
  // Strip the surrounding tags to isolate the data
  $start_tag = '[autowp-promptcode]';
  $end_tag = '[/autowp-promptcode]';
  $start_pos = strlen($start_tag);
  $end_pos = strpos($prompt_string, $end_tag);

  $data_string = substr($prompt_string, $start_pos, $end_pos - $start_pos);
  
  // Split the string by commas to extract individual values
  $values = explode(',', $data_string);

  // Assign each value to a variable, using array_pad to ensure there are exactly 6 elements
  list($keyword, $generationMethod, $country, $language, $subtitle, $narration) = array_pad($values, 6, '');

  // Return an associative array with each value labeled
  return [
      'keyword' => sanitize_text_field($keyword),
      'generationMethod' => sanitize_text_field($generationMethod),
      'country' => sanitize_text_field($country),
      'language' => sanitize_text_field($language),
      'subtitle' => sanitize_text_field($subtitle),
      'narration' => sanitize_text_field($narration)
  ];
}

function autowp_parse_rewriting_prompt_code($prompt_string) {
  // Strip the surrounding tags to isolate the data
  $start_tag = '[autowp-rewriting-promptcode]';
  $end_tag = '[/autowp-rewriting-promptcode]';
  $start_pos = strlen($start_tag);
  $end_pos = strpos($prompt_string, $end_tag);

  $data_string = substr($prompt_string, $start_pos, $end_pos - $start_pos);
  
  // Split the string by commas to extract individual values
  $values = explode(',', $data_string);

  // Assign each value to a variable, using array_pad to ensure there are exactly 6 elements
  list($language, $subtitle, $narration) = array_pad($values, 6, '');

  // Return an associative array with each value labeled
  return [
    
      'language' => sanitize_text_field($language),
      'subtitle' => sanitize_text_field($subtitle),
      'narration' => sanitize_text_field($narration)
  ];
}


function autowp_ai_website_form_page_handler(){
    // AI website form content and handling
    global $wpdb;
    $table_name = $wpdb->prefix . 'autowp_wordpress_websites'; 
  
    $message = '';
    $notice = '';
  
  
    $default = array(
        'id' => 0,
        'website_name'             => '',
        'website_type'             => '',
        'category_id'              => '',
        'aigenerated_title'        => '',
        'aigenerated_content'      => '',
        'aigenerated_tags'         => '',
        'aigenerated_image'        => '',
        'title_prompt'             => '',
        'content_prompt'           => '',
        'tags_prompt'              => '',
        'image_prompt'             => '',
        'image_generating_status'  => '',
        'author_selection'         => '',
        'active'                   => '',
        
        
        
    );
  
  
    if ( isset($_REQUEST['nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['nonce'])), basename(__FILE__))) {
  
  
         // Only retrieve the specific values needed
         $item = array(
          'id'                => isset($_POST['id']) ? intval($_POST['id']) : 0,
          'website_name'      => sanitize_text_field($_POST['website_name']),
          'category_id'       => isset($_POST['category_id']) ? array_map('intval', $_POST['category_id']) : array(),
          'aigenerated_image' => '1',
          'title_prompt'      => sanitize_textarea_field($_POST['title_prompt']),
          'content_prompt' => '[autowp-promptcode]' . 
                    sanitize_text_field($_POST['keywordInput']) . ',' . 
                    'aiGenerated'. ',' .
                    sanitize_text_field($_POST['countrySelect']) . ',' .
                    sanitize_text_field($_POST['languageSelect']) . ',' .
                    sanitize_text_field($_POST['subtitleSelect']) . ',' .
                    sanitize_text_field($_POST['narrationSelect']) .
                    '[/autowp-promptcode]',

          'tags_prompt'       => sanitize_textarea_field($_POST['tags_prompt']),
          'image_prompt'      => sanitize_textarea_field($_POST['image_prompt']),
          'website_type'      => 'ai',
          'image_generating_status' => sanitize_text_field($_POST['image_generating_status']),
          'author_selection'         => sanitize_text_field($_POST['author_selection']),
          'active'                  => sanitize_text_field(($_POST['active']))
       );

     
    
        //Convert category_id array to text
        $category_ids = implode(",", $item['category_id']);
        $item['category_id'] = $category_ids;
        

        $item['aigenerated_title'] = '1';
        $item['aigenerated_content'] = '1';
        $item['aigenerated_tags'] = '1';

        if($item['aigenerated_image'] !== '1'){
            $item['aigenerated_image'] = '0';
        }


        $item['website_type'] = 'ai';
  
        // Set WP-CRON 
  
        $settings = unserialize(get_option('autowp_settings'));
  
        $wpcron_status = $settings['wpcron_status'];
  
        if(!isset($wpcron_status)){
          autowp_update_wp_cron_status('1');
        }

        $time_value_type = $settings['selected_time_type'];

        $user_wpcron_time = autowp_get_wpcron_time($time_value_type);

        $next_two_minutes = time() + 2 * 60;

        // Schedule WP-Cron
            if (!wp_next_scheduled('autowp_cron')) {
            wp_schedule_event($next_two_minutes, $user_wpcron_time, 'autowp_cron');
            
          } else {
            wp_clear_scheduled_hook('autowp_cron');
            wp_schedule_event($next_two_minutes, $user_wpcron_time, 'autowp_cron');
          }
  
        
  
  
  
        $item_valid = autowp_validate_website($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = __('New process was successfully saved! Next process execution time : ' . get_next_cron_time('autowp_cron') .  ' in your server time. You can change process execution interval in your settings page! ', 'autowp');
                } else {
                    $notice = __('There was an error while saving item', 'autowp');
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = __('New process was successfully updated! Next process execution time : ' . get_next_cron_time('autowp_cron') .  ' in your server time. You can change process execution interval in your settings page! ', 'autowp');
                } else {
                    $notice = __('There was an error while updating item', 'autowp');
                }
            }
        } else {
            
            $notice = $item_valid;
        }
    }
    else {
        
        $item = $default;
        $sanitized_id = absint($_REQUEST['id']);
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $sanitized_id), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Item not found', 'autowp');
            }
        }
    }
  
    
    add_meta_box('websites_form_meta_box', __('Form', 'autowp'), 'autowp_ai_websites_form_meta_box_handler', 'add_new_ai_website_form', 'normal', 'default');
  
    ?>
  <div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php esc_html_e('Add New', 'autowp'); ?> <a class="add-new-h2"
        href="<?php echo esc_url(get_admin_url(get_current_blog_id(), 'admin.php?page=autowp_automaticPost')); ?>"><?php esc_html_e('Back to List', 'autowp'); ?></a>
    </h2>

  
    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo esc_html($notice) ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo esc_html($message) ?></p></div>
    <?php endif;?>
  
    <form id="form" method="POST">
    <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce(basename(__FILE__))); ?>"/>

        
        <input type="hidden" name="id" value="<?php echo esc_html($item['id']) ?>"/>
  
        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    
                    <?php do_meta_boxes('add_new_ai_website_form', 'normal', $item); ?>
                    <input type="submit" value="<?php esc_attr_e('Save', 'autowp')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
  </div>
  <?php
  }
  
  
  
  
  function autowp_ai_websites_form_meta_box_handler($item){

    // Parsing the example content_prompt
    $parsed_values = autowp_parse_prompt_code($item['content_prompt']) ?? '';

    // Accessing values
    $promptcode_keyword = $parsed_values['keyword'] ?? '';
    $promptcode_generationMethod = $parsed_values['generationMethod'] ?? '';
    $promptcode_country = $parsed_values['country'] ?? '';
    $promptcode_language = $parsed_values['language'] ?? '';
    $promptcode_subtitle = $parsed_values['subtitle'] ?? '';
    $promptcode_narration = $parsed_values['narration'] ?? '';


    ?>
<tbody >
<form>
<div class="container">
    <form class="form2bc row">
        <div class="col-md-6">
        <h4 style="font-weight: bold;">General Settings</h4>
            <div class="mb-3">
                <label for="website_name" class="form-label">Name:</label>
                <?php
                //Get admin domain name
                $autowp_admin_email = autowp_get_admin_email();
                $autowp_domain_name = esc_url(get_site_url());

                echo '<input type="hidden" id="autowp_admin_email" value="' . esc_attr($autowp_admin_email) . '">';
                echo '<input type="hidden" id="autowp_domain_name" value="' . esc_attr($autowp_domain_name) . '">';
                ?>
                <input id="website_name" name="website_name" type="text" value="<?php echo esc_attr($item['website_name']) ?>" required class="form-control">
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Categories:</label>
                <select id="category_id" name="category_id[]" required multiple class="form-select">
                    <?php
                    // Get selected categories
                    $selected_categories = $item['category_id'];
                    // Turn categories to array list
                    $selected_categories = explode(',', $selected_categories);

                    $categories = get_categories(array(
                        'orderby' => 'name',
                        'order'   => 'ASC',
                        'hide_empty' => false
                    ));

                    foreach ($categories as $category) {
                        if (isset($selected_categories) && in_array($category->term_id, $selected_categories)) {
                            echo '<option value="' . esc_attr($category->term_id) . '" selected>' . esc_html($category->name) . '</option>';
                            continue;
                        }
                        echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="author_selection" class="form-label"><?php esc_html_e('Author Selection', 'autowp'); ?></label>
                <select name="author_selection" id="author_selection" class="form-select">
                    <?php
                    $authors = get_users();

                    foreach ($authors as $author) {
                        $author_id = $author->ID;
                        $author_name = $author->display_name;
                        $author_description = get_the_author_meta('description', $author_id);

                        if ($item['author_selection'] === strval($author_id)) {
                            echo '<option value="' . esc_attr($author_id) . '" selected>' . esc_html($author_name) . '</option>';
                            continue;
                        }

                        echo '<option value="' . esc_attr($author_id) . '">' . esc_html($author_name) . '</option>';
                    }
                    ?>
                </select>
                <p class="form-text"><?php esc_html_e('Select an author from the list.', 'autowp'); ?></p>
            </div>

       

            <div class="col-md-6">
    <label class="form-label" for="image_generating_status"><?php esc_html_e('Image Generating Method', 'autowp'); ?></label>
    <select name="image_generating_status" class="form-select">
        <option value="0" <?php if ($item['image_generating_status'] === '0') {
            echo 'selected';
        } ?>><?php esc_html_e('FLUX Realism LoRA', 'autowp'); ?></option>

        <option value="1" <?php if ($item['image_generating_status'] === '1') {
            echo 'selected';
        } ?>><?php esc_html_e('Stable Diffusion Ultra', 'autowp'); ?></option>


        <option value="2" <?php if ($item['image_generating_status'] === '2') {
            echo 'selected';
        } ?>><?php esc_html_e('Stable Diffusion Core', 'autowp'); ?></option>


        <option value="3" <?php if ($item['image_generating_status'] === '3') {
            echo 'selected';
        } ?>><?php esc_html_e('DALL-E 2', 'autowp'); ?></option>


        <option value="4" <?php if ($item['image_generating_status'] === '4') {
            echo 'selected';
        } ?>><?php esc_html_e('DALL-E 3', 'autowp'); ?></option>


        <option value="5" <?php if ($item['image_generating_status'] === '5') {
            echo 'selected';
        } ?>><?php esc_html_e('DuckDuckGo Search', 'autowp'); ?></option>

<option value="6" <?php if ($item['image_generating_status'] === '6') {
            echo 'selected';
        } ?>><?php esc_html_e('Default Image', 'autowp'); ?></option>

<option value="7" <?php if ($item['image_generating_status'] === '7') {
            echo 'selected';
        } ?>><?php esc_html_e('No Image', 'autowp'); ?></option>


    </select>
    <p class="form-text"><?php esc_html_e('By default FLUX Realism LoRA is selected.', 'autowp'); ?></p>
</div>


        </div>
    </form>
</div>



<div class="container">
    <div class="form2bc">
    <div class="col-md-6">

                 



                <h4 style="font-weight: bold;">Post Settings</h4>
<!-- Integration of your initial form inputs starts here -->
            <div class="mb-3">
                <label for="keywordInput" class="form-label">Keyword:</label>
                <input type="text" class="form-control" id="keywordInput" name="keywordInput" placeholder="Enter keyword" value="<?php echo esc_attr($promptcode_keyword); ?>" required class="form-control">

            </div>
          
            <div class="mb-3">
                <label for="countrySelect" class="form-label">Country:</label>
                <select class="form-select" id="countrySelect" name="countrySelect">
                    <!-- Countries list -->
                    <?php
       $countries = array
       (
         'AF' => 'Afghanistan',
         'AX' => 'Aland Islands',
         'AL' => 'Albania',
         'DZ' => 'Algeria',
         'AS' => 'American Samoa',
         'AD' => 'Andorra',
         'AO' => 'Angola',
         'AI' => 'Anguilla',
         'AQ' => 'Antarctica',
         'AG' => 'Antigua And Barbuda',
         'AR' => 'Argentina',
         'AM' => 'Armenia',
         'AW' => 'Aruba',
         'AU' => 'Australia',
         'AT' => 'Austria',
         'AZ' => 'Azerbaijan',
         'BS' => 'Bahamas',
         'BH' => 'Bahrain',
         'BD' => 'Bangladesh',
         'BB' => 'Barbados',
         'BY' => 'Belarus',
         'BE' => 'Belgium',
         'BZ' => 'Belize',
         'BJ' => 'Benin',
         'BM' => 'Bermuda',
         'BT' => 'Bhutan',
         'BO' => 'Bolivia',
         'BA' => 'Bosnia And Herzegovina',
         'BW' => 'Botswana',
         'BV' => 'Bouvet Island',
         'BR' => 'Brazil',
         'IO' => 'British Indian Ocean Territory',
         'BN' => 'Brunei Darussalam',
         'BG' => 'Bulgaria',
         'BF' => 'Burkina Faso',
         'BI' => 'Burundi',
         'KH' => 'Cambodia',
         'CM' => 'Cameroon',
         'CA' => 'Canada',
         'CV' => 'Cape Verde',
         'KY' => 'Cayman Islands',
         'CF' => 'Central African Republic',
         'TD' => 'Chad',
         'CL' => 'Chile',
         'CN' => 'China',
         'CX' => 'Christmas Island',
         'CC' => 'Cocos (Keeling) Islands',
         'CO' => 'Colombia',
         'KM' => 'Comoros',
         'CG' => 'Congo',
         'CD' => 'Congo, Democratic Republic',
         'CK' => 'Cook Islands',
         'CR' => 'Costa Rica',
         'CI' => 'Cote D\'Ivoire',
         'HR' => 'Croatia',
         'CU' => 'Cuba',
         'CY' => 'Cyprus',
         'CZ' => 'Czech Republic',
         'DK' => 'Denmark',
         'DJ' => 'Djibouti',
         'DM' => 'Dominica',
         'DO' => 'Dominican Republic',
         'EC' => 'Ecuador',
         'EG' => 'Egypt',
         'SV' => 'El Salvador',
         'GQ' => 'Equatorial Guinea',
         'ER' => 'Eritrea',
         'EE' => 'Estonia',
         'ET' => 'Ethiopia',
         'FK' => 'Falkland Islands (Malvinas)',
         'FO' => 'Faroe Islands',
         'FJ' => 'Fiji',
         'FI' => 'Finland',
         'FR' => 'France',
         'GF' => 'French Guiana',
         'PF' => 'French Polynesia',
         'TF' => 'French Southern Territories',
         'GA' => 'Gabon',
         'GM' => 'Gambia',
         'GE' => 'Georgia',
         'DE' => 'Germany',
         'GH' => 'Ghana',
         'GI' => 'Gibraltar',
         'GR' => 'Greece',
         'GL' => 'Greenland',
         'GD' => 'Grenada',
         'GP' => 'Guadeloupe',
         'GU' => 'Guam',
         'GT' => 'Guatemala',
         'GG' => 'Guernsey',
         'GN' => 'Guinea',
         'GW' => 'Guinea-Bissau',
         'GY' => 'Guyana',
         'HT' => 'Haiti',
         'HM' => 'Heard Island & Mcdonald Islands',
         'VA' => 'Holy See (Vatican City State)',
         'HN' => 'Honduras',
         'HK' => 'Hong Kong',
         'HU' => 'Hungary',
         'IS' => 'Iceland',
         'IN' => 'India',
         'ID' => 'Indonesia',
         'IR' => 'Iran, Islamic Republic Of',
         'IQ' => 'Iraq',
         'IE' => 'Ireland',
         'IM' => 'Isle Of Man',
         'IL' => 'Israel',
         'IT' => 'Italy',
         'JM' => 'Jamaica',
         'JP' => 'Japan',
         'JE' => 'Jersey',
         'JO' => 'Jordan',
         'KZ' => 'Kazakhstan',
         'KE' => 'Kenya',
         'KI' => 'Kiribati',
         'KR' => 'Korea',
         'KW' => 'Kuwait',
         'KG' => 'Kyrgyzstan',
         'LA' => 'Lao People\'s Democratic Republic',
         'LV' => 'Latvia',
         'LB' => 'Lebanon',
         'LS' => 'Lesotho',
         'LR' => 'Liberia',
         'LY' => 'Libyan Arab Jamahiriya',
         'LI' => 'Liechtenstein',
         'LT' => 'Lithuania',
         'LU' => 'Luxembourg',
         'MO' => 'Macao',
         'MK' => 'Macedonia',
         'MG' => 'Madagascar',
         'MW' => 'Malawi',
         'MY' => 'Malaysia',
         'MV' => 'Maldives',
         'ML' => 'Mali',
         'MT' => 'Malta',
         'MH' => 'Marshall Islands',
         'MQ' => 'Martinique',
         'MR' => 'Mauritania',
         'MU' => 'Mauritius',
         'YT' => 'Mayotte',
         'MX' => 'Mexico',
         'FM' => 'Micronesia, Federated States Of',
         'MD' => 'Moldova',
         'MC' => 'Monaco',
         'MN' => 'Mongolia',
         'ME' => 'Montenegro',
         'MS' => 'Montserrat',
         'MA' => 'Morocco',
         'MZ' => 'Mozambique',
         'MM' => 'Myanmar',
         'NA' => 'Namibia',
         'NR' => 'Nauru',
         'NP' => 'Nepal',
         'NL' => 'Netherlands',
         'AN' => 'Netherlands Antilles',
         'NC' => 'New Caledonia',
         'NZ' => 'New Zealand',
         'NI' => 'Nicaragua',
         'NE' => 'Niger',
         'NG' => 'Nigeria',
         'NU' => 'Niue',
         'NF' => 'Norfolk Island',
         'MP' => 'Northern Mariana Islands',
         'NO' => 'Norway',
         'OM' => 'Oman',
         'PK' => 'Pakistan',
         'PW' => 'Palau',
         'PS' => 'Palestinian Territory, Occupied',
         'PA' => 'Panama',
         'PG' => 'Papua New Guinea',
         'PY' => 'Paraguay',
         'PE' => 'Peru',
         'PH' => 'Philippines',
         'PN' => 'Pitcairn',
         'PL' => 'Poland',
         'PT' => 'Portugal',
         'PR' => 'Puerto Rico',
         'QA' => 'Qatar',
         'RE' => 'Reunion',
         'RO' => 'Romania',
         'RU' => 'Russian Federation',
         'RW' => 'Rwanda',
         'BL' => 'Saint Barthelemy',
         'SH' => 'Saint Helena',
         'KN' => 'Saint Kitts And Nevis',
         'LC' => 'Saint Lucia',
         'MF' => 'Saint Martin',
         'PM' => 'Saint Pierre And Miquelon',
         'VC' => 'Saint Vincent And Grenadines',
         'WS' => 'Samoa',
         'SM' => 'San Marino',
         'ST' => 'Sao Tome And Principe',
         'SA' => 'Saudi Arabia',
         'SN' => 'Senegal',
         'RS' => 'Serbia',
         'SC' => 'Seychelles',
         'SL' => 'Sierra Leone',
         'SG' => 'Singapore',
         'SK' => 'Slovakia',
         'SI' => 'Slovenia',
         'SB' => 'Solomon Islands',
         'SO' => 'Somalia',
         'ZA' => 'South Africa',
         'GS' => 'South Georgia And Sandwich Isl.',
         'ES' => 'Spain',
         'LK' => 'Sri Lanka',
         'SD' => 'Sudan',
         'SR' => 'Suriname',
         'SJ' => 'Svalbard And Jan Mayen',
         'SZ' => 'Swaziland',
         'SE' => 'Sweden',
         'CH' => 'Switzerland',
         'SY' => 'Syrian Arab Republic',
         'TW' => 'Taiwan',
         'TJ' => 'Tajikistan',
         'TZ' => 'Tanzania',
         'TH' => 'Thailand',
         'TL' => 'Timor-Leste',
         'TG' => 'Togo',
         'TK' => 'Tokelau',
         'TO' => 'Tonga',
         'TT' => 'Trinidad And Tobago',
         'TN' => 'Tunisia',
         'TR' => 'Turkey',
         'TM' => 'Turkmenistan',
         'TC' => 'Turks And Caicos Islands',
         'TV' => 'Tuvalu',
         'UG' => 'Uganda',
         'UA' => 'Ukraine',
         'AE' => 'United Arab Emirates',
         'GB' => 'United Kingdom',
         'US' => 'United States',
         'UM' => 'United States Outlying Islands',
         'UY' => 'Uruguay',
         'UZ' => 'Uzbekistan',
         'VU' => 'Vanuatu',
         'VE' => 'Venezuela',
         'VN' => 'Viet Nam',
         'VG' => 'Virgin Islands, British',
         'VI' => 'Virgin Islands, U.S.',
         'WF' => 'Wallis And Futuna',
         'EH' => 'Western Sahara',
         'YE' => 'Yemen',
         'ZM' => 'Zambia',
         'ZW' => 'Zimbabwe',
       );

       foreach ($countries as $code => $name) {
        $selected = ($promptcode_country == $code) ? 'selected' : '';       
        echo "<option value='" . esc_attr($code) . "' " . esc_attr($selected) . ">" . esc_html($name) . "</option>";


    }
    
        ?>
                </select>
            </div>
            <?php
$languages = [
    "Afrikaans",
    "Albanian",
    "Arabic",
    "Armenian",
    "Basque",
    "Bengali",
    "Bulgarian",
    "Catalan",
    "Cambodian",
    "Chinese (Mandarin)",
    "Croatian",
    "Czech",
    "Danish",
    "Dutch",
    "English",
    "Estonian",
    "Fiji",
    "Finnish",
    "French",
    "Georgian",
    "German",
    "Greek",
    "Gujarati",
    "Hebrew",
    "Hindi",
    "Hungarian",
    "Icelandic",
    "Indonesian",
    "Irish",
    "Italian",
    "Japanese",
    "Javanese",
    "Korean",
    "Latin",
    "Latvian",
    "Lithuanian",
    "Macedonian",
    "Malay",
    "Malayalam",
    "Maltese",
    "Maori",
    "Marathi",
    "Mongolian",
    "Nepali",
    "Norwegian",
    "Persian",
    "Polish",
    "Portuguese",
    "Punjabi",
    "Quechua",
    "Romanian",
    "Russian",
    "Samoan",
    "Serbian",
    "Slovak",
    "Slovenian",
    "Spanish",
    "Swahili",
    "Swedish",
    "Tamil",
    "Tatar",
    "Telugu",
    "Thai",
    "Tibetan",
    "Tonga",
    "Turkish",
    "Ukrainian",
    "Urdu",
    "Uzbek",
    "Vietnamese",
    "Welsh",
    "Xhosa"
];
?>

<div class="mb-3">
    <label for="languageSelect" class="form-label">Post Language:</label>
    <select class="form-select" id="languageSelect" name="languageSelect">
   


<?php foreach ($languages as $language): ?>
    <option value="<?php echo esc_attr($language); ?>" <?php echo ($language === $promptcode_language) ? 'selected' : ''; ?>>
        <?php echo esc_html($language); ?>
    </option>
<?php endforeach; ?>


    </select>
</div>

<div class="mb-3">
    <label for="subtitleSelect" class="form-label">Subheading Count:</label>
    <select class="form-select" id="subtitleSelect" name="subtitleSelect">
        <?php
        for ($i = 1; $i <= 10; $i++) {
            $selected = ($promptcode_subtitle == strval($i)) ? 'selected' : '';
            echo "<option value='" . esc_attr($i) . "' " . esc_attr($selected) . ">" . esc_html($i) . "</option>";

        }
        ?>
    </select>
</div>

<div class="mb-3">
    <label for="narrationSelect" class="form-label">Writing Style:</label>
    <select class="form-select" id="narrationSelect" name="narrationSelect">
        <?php
        $is_inactive = (isset($item['active']) && $item['active'] === '0');

        $styles = [
            "Descriptive" => "Descriptive",
            "Narrative" => "Narrative",
            "Explanatory" => "Explanatory",
            "Argumentative" => "Argumentative",
            "Comparative" => "Comparative",
            "Process Analysis" => "Process Analysis",
            "Allegorical" => "Allegorical",
            "Chronological" => "Chronological",
            "Ironic" => "Ironic",
            "ConsistencyAndRepetition" => "Consistency and Repetition",
            "LanguagePlayAndPoeticExpression" => "Language Play and Poetic Expression",
            "InternalMonologue" => "Internal Monologue",
            "Dialogical" => "Dialogical"
        ];
        foreach ($styles as $value => $name) {
            $selected = ($promptcode_narration == $value) ? 'selected' : '';
            echo "<option value='" . esc_attr($value) . "' " . esc_attr($selected) . ">" . esc_html($name) . "</option>";


        }
        ?>
    </select>
</div>


<h4 style="font-weight: bold;">Source Status</h4>


<!-- Status Field (Active/Inactive) -->
<div class="mb-3">
    <label for="active" class="form-label">Status:</label>

  
    <select id="active" name="active" class="form-select">
        <option value="1" <?php echo (!$is_inactive) ? 'selected' : ''; ?>>
            Active
        </option>
        <option value="0" <?php echo ($is_inactive) ? 'selected' : ''; ?>>
            Inactive
        </option>
    </select>

    <p class="message">
      When the cron job is triggered, any task marked as "Active" will be executed.
      If you select "Inactive," this task will be skipped, and the cron job will move
      on to the remaining active tasks. This makes it easy to enable or disable tasks
      without removing them entirely.
    </p>
</div>

            
            <!-- Integration of your initial form inputs ends here -->

        </div>
        <div class="col-md-6">



        <p class="message">For prompt examples, visit <a class="link" href="https://www.aiprm.com/prompts/">aiprm.com/prompts</a></p>
    </div>
</div>
</form>

	
</tbody>
<?php
}


?>