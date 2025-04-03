<?php


function autowp_generate_content_prompt($form_data) {
    // Genel prompt ayarları
    $long_description = isset($form_data['long_description']) ? sanitize_textarea_field($form_data['long_description']) : '';
    $keywordInput     = isset($form_data['keywordInput']) ? sanitize_text_field($form_data['keywordInput']) : '';
    $languageSelect   = isset($form_data['languageSelect']) ? sanitize_text_field($form_data['languageSelect']) : '';
    $subtitleSelect   = isset($form_data['subtitleSelect']) ? intval($form_data['subtitleSelect']) : 1;
    $narrationSelect  = isset($form_data['narrationSelect']) ? sanitize_text_field($form_data['narrationSelect']) : '';
    $countrySelect    = isset($form_data['countrySelect']) ? sanitize_text_field($form_data['countrySelect']) : '';
    
    // Toggles for custom tools and knowledge base
    $enable_website_tools = isset($form_data['enable_website_tools']) ? '1' : '0';
    $enable_duckduckgo    = isset($form_data['enable_duckduckgo']) ? '1' : '0';
    $enable_wikipedia     = isset($form_data['enable_wikipedia']) ? '1' : '0';
    $enable_yfinancetools = isset($form_data['enable_yfinancetools']) ? '1' : '0';
    $enable_hackernews    = isset($form_data['enable_hackernews']) ? '1' : '0';
    
    $enable_pdf_kb = isset($form_data['enable_pdf_kb']) ? '1' : '0';
    $enable_csv_kb = isset($form_data['enable_csv_kb']) ? '1' : '0';
    $enable_text_kb = isset($form_data['enable_text_kb']) ? '1' : '0';

    // Custom Tools Ayarları
    $website_tools_knowledge_base_url = !empty($form_data['website_tools_knowledge_base_url'])
        ? sanitize_url($form_data['website_tools_knowledge_base_url'])
        : '';
    $duckduckgo_news = isset($form_data['duckduckgo_news']) ? '1' : '0';
    $duckduckgo_fixed_max_results = !empty($form_data['duckduckgo_fixed_max_results'])
        ? intval($form_data['duckduckgo_fixed_max_results'])
        : null;
    $wikipedia_knowledge_base = !empty($form_data['wikipedia_knowledge_base'])
        ? sanitize_text_field($form_data['wikipedia_knowledge_base'])
        : '';

    $yfinance_stock_price             = isset($form_data['yfinance_stock_price']) ? '1' : '0';
    $yfinance_company_info            = isset($form_data['yfinance_company_info']) ? '1' : '0';
    $yfinance_stock_fundamentals      = isset($form_data['yfinance_stock_fundamentals']) ? '1' : '0';
    $yfinance_income_statements       = isset($form_data['yfinance_income_statements']) ? '1' : '0';
    $yfinance_key_financial_ratios    = isset($form_data['yfinance_key_financial_ratios']) ? '1' : '0';
    $yfinance_analyst_recommendations = isset($form_data['yfinance_analyst_recommendations']) ? '1' : '0';
    $yfinance_company_news            = isset($form_data['yfinance_company_news']) ? '1' : '0';
    $yfinance_technical_indicators   = isset($form_data['yfinance_technical_indicators']) ? '1' : '0';
    $yfinance_historical_prices       = isset($form_data['yfinance_historical_prices']) ? '1' : '0';

    $hackernews_get_top_stories  = isset($form_data['hackernews_get_top_stories']) ? '1' : '0';
    $hackernews_get_user_details = isset($form_data['hackernews_get_user_details']) ? '1' : '0';

    $custom_tools = [
        'website_tools' => [
            'knowledge_base_url' => $website_tools_knowledge_base_url,
        ],
        'duckduckgo' => [
            'news'              => $duckduckgo_news,
            'fixed_max_results' => $duckduckgo_fixed_max_results,
        ],
        'wikipedia' => [
            'knowledge_base'    => $wikipedia_knowledge_base,
        ],
        'yfinancetools' => [
            'stock_price'             => $yfinance_stock_price,
            'company_info'            => $yfinance_company_info,
            'stock_fundamentals'      => $yfinance_stock_fundamentals,
            'income_statements'       => $yfinance_income_statements,
            'key_financial_ratios'    => $yfinance_key_financial_ratios,
            'analyst_recommendations' => $yfinance_analyst_recommendations,
            'company_news'            => $yfinance_company_news,
            'technical_indicators'   => $yfinance_technical_indicators,
            'historical_prices'       => $yfinance_historical_prices,
        ],
        'hackernews' => [
            'get_top_stories'  => $hackernews_get_top_stories,
            'get_user_details' => $hackernews_get_user_details,
        ],
    ];

    // Knowledge Base Ayarları
    $pdf_url_knowledge_base = !empty($form_data['pdf_url_knowledge_base'])
        ? sanitize_url($form_data['pdf_url_knowledge_base'])
        : '';
    $csv_url_knowledge_base = !empty($form_data['csv_url_knowledge_base'])
        ? sanitize_url($form_data['csv_url_knowledge_base'])
        : '';
    $text_knowledge_base = !empty($form_data['text_knowledge_base'])
        ? sanitize_textarea_field($form_data['text_knowledge_base'])
        : '';

    $knowledge_base = [
        'pdf_url' => $pdf_url_knowledge_base,
        'csv_url' => $csv_url_knowledge_base,
        'text'    => $text_knowledge_base,
    ];

    // JSON birleşik prompt yapısı
    $combined_prompt = [
        'content'          => $long_description,
        'keyword'          => $keywordInput,
        'language'         => $languageSelect,
        'subheading_count' => $subtitleSelect,
        'writing_style'    => $narrationSelect,
        'country'          => $countrySelect,
        'custom_tools'     => $custom_tools,
        'knowledge_base'   => $knowledge_base,
        // Toggle değerleri
        'enable_website_tools' => $enable_website_tools,
        'enable_duckduckgo'    => $enable_duckduckgo,
        'enable_wikipedia'     => $enable_wikipedia,
        'enable_yfinancetools' => $enable_yfinancetools,
        'enable_hackernews'    => $enable_hackernews,
        'enable_pdf_kb'        => $enable_pdf_kb,
        'enable_csv_kb'        => $enable_csv_kb,
        'enable_text_kb'       => $enable_text_kb,
    ];

    return json_encode($combined_prompt);
}


function autowp_auto_post_agent_form_page_handler() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'autowp_wordpress_websites';

    $message = '';
    $notice = '';

    // Manuel agentic scraper formunda kullanılan alan isimlerine göre varsayılan değerler
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
        'active'                   => ''
        
        
        
    );

    // Dış formdaki nonce değeri kullanılarak kontrol
    if ( isset($_REQUEST['nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['nonce'])), basename(__FILE__))) {
  
  
        // Only retrieve the specific values needed
        $item = array(
         'id'                => isset($_POST['id']) ? intval($_POST['id']) : 0,
         'website_name'      => sanitize_text_field($_POST['website_name']),
         'category_id'       => isset($_POST['category_id']) ? array_map('intval', $_POST['category_id']) : array(),
         'aigenerated_image' => '1',
         'title_prompt'      => sanitize_textarea_field($_POST['title_prompt']),
         'content_prompt' =>    autowp_generate_content_prompt($_POST),

         'tags_prompt'       => sanitize_textarea_field($_POST['tags_prompt']),
         'image_prompt'      => sanitize_textarea_field($_POST['image_prompt']),
         'website_type'      => 'agenticscraper',
         'image_generating_status' => sanitize_text_field($_POST['image_generating_status']),
         'author_selection'         => sanitize_text_field($_POST['author_selection']),
         'active'               => sanitize_text_field(($_POST['active'])) ?? '1'
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


       $item['website_type'] = 'agenticscraper';
 
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

    // Meta box ekleniyor
    add_meta_box('agenticscrape_form_meta_box', __('Post Agent Configuration', 'autowp'), 'autowp_agenticscrape_meta_box_renderer', 'add_new_auto_post_agent', 'normal', 'default');
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2>
            <?php esc_html_e('New Post Agent', 'autowp'); ?>
            <a class="add-new-h2" href="<?php echo esc_url(get_admin_url(get_current_blog_id(), 'admin.php?page=autowp_automaticPost')); ?>">
                <?php esc_html_e('Back to List', 'autowp'); ?>
            </a>
        </h2>

        <?php if (!empty($notice)): ?>
            <div id="notice" class="error"><p><?php echo esc_html($notice); ?></p></div>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <div id="message" class="updated"><p><?php echo esc_html($message); ?></p></div>
        <?php endif; ?>

        <form id="form" method="POST">
            <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce(basename(__FILE__))); ?>"/>
            <input type="hidden" name="id" value="<?php echo esc_attr($item['id']); ?>"/>

            <div class="metabox-holder" id="poststuff">
                <div id="post-body">
                    <div id="post-body-content">
                        <?php do_meta_boxes('add_new_auto_post_agent', 'normal', $item); ?>
                        <input type="submit" value="<?php esc_attr_e('Save', 'autowp'); ?>" id="submit" class="button-primary" name="submit">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
}

function autowp_agenticscrape_meta_box_renderer($item) {
    // Eğer mevcut kayıt varsa, content_prompt içerisindeki JSON'u decode edelim.
    $decoded_prompt = array();
    if ( isset($item['content_prompt']) && !empty($item['content_prompt']) ) {
        $decoded_prompt = json_decode($item['content_prompt'], true);
        if (!is_array($decoded_prompt)) {
            $decoded_prompt = array();
        }
    }
    
    // Dil listesi (manuel formdakiyle aynı)
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
    <div class="inside">
        <!-- General Settings -->
        <h4 style="font-weight: bold;">General Settings</h4>
        <div class="mb-3">
            <label for="website_name" class="form-label">Agent Name:</label>
            <input type="text" id="website_name" name="website_name" value="<?php echo isset($item['website_name']) ? esc_attr($item['website_name']) : ''; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Categories:</label>
            <select id="category_id" name="category_id[]" required multiple class="form-select">
                <?php
                $categories = get_categories(array(
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'hide_empty' => false
                ));
                $selected_cats = isset($item['category_id']) && !empty($item['category_id']) ? explode(",", $item['category_id']) : array();
                foreach ($categories as $category) {
                    $selected = in_array($category->term_id, $selected_cats) ? 'selected' : '';
                    echo '<option value="' . esc_attr($category->term_id) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                }
                ?>
            </select>
        </div>

        
            <class="mb-3">
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
        
       
        <div class="mb-3">
            <label for="image_generating_status" class="form-label">Image Generating Method</label>
            <select name="image_generating_status" class="form-select" id="image_generating_status">
                <option value="0" <?php selected(isset($item['image_generating_status']) ? $item['image_generating_status'] : '', '0'); ?>>FLUX Realism LoRA</option>
                <option value="1" <?php selected(isset($item['image_generating_status']) ? $item['image_generating_status'] : '', '1'); ?>>Stable Diffusion Ultra</option>
                <option value="2" <?php selected(isset($item['image_generating_status']) ? $item['image_generating_status'] : '', '2'); ?>>Stable Diffusion Core</option>
                <option value="3" <?php selected(isset($item['image_generating_status']) ? $item['image_generating_status'] : '', '3'); ?>>DALL-E 2</option>
                <option value="4" <?php selected(isset($item['image_generating_status']) ? $item['image_generating_status'] : '', '4'); ?>>DALL-E 3</option>
                <option value="5" <?php selected(isset($item['image_generating_status']) ? $item['image_generating_status'] : '', '5'); ?>>DuckDuckGo Search</option>

                <option value="6" <?php selected(isset($item['image_generating_status']) ? $item['image_generating_status'] : '', '6'); ?>>Default Image</option>
                <option value="7" <?php selected(isset($item['image_generating_status']) ? $item['image_generating_status'] : '', '7'); ?>>No Image</option>
                
            </select>
            <p class="form-text">By default FLUX Realism LoRA is selected.</p>
        </div>
        <!-- Long Description Prompt -->
        <div class="mb-3">
            <label for="long_description" class="form-label">Long Description Prompt:</label>
            <textarea class="form-control" id="long_description" name="long_description" rows="5" placeholder="Enter your detailed prompt here..."><?php echo isset($decoded_prompt['content']) ? esc_textarea($decoded_prompt['content']) : ''; ?></textarea>
            <div class="form-text">Provide a detailed prompt for generating content.</div>
        </div>
        <!-- Post Settings -->
        <h4 style="font-weight: bold;">Post Settings</h4>
        <div class="mb-3">
            <label for="keywordInput" class="form-label">Keyword:</label>
            <input type="text" class="form-control" id="keywordInput" name="keywordInput" placeholder="Enter keyword" value="<?php echo isset($decoded_prompt['keyword']) ? esc_attr($decoded_prompt['keyword']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="countrySelect" class="form-label">Country:</label>
            <select class="form-select" id="countrySelect" name="countrySelect">
                <?php
                $selected_country = isset($decoded_prompt['country']) ? $decoded_prompt['country'] : '';

                $countries = array(
                    'any' => 'Anywhere',
                    'AF' => 'Afghanistan',
                    'AX' => 'Åland Islands',
                    'AL' => 'Albania',
                    'DZ' => 'Algeria',
                    'AS' => 'American Samoa',
                    'AD' => 'Andorra',
                    'AO' => 'Angola',
                    'AI' => 'Anguilla',
                    'AQ' => 'Antarctica',
                    'AG' => 'Antigua and Barbuda',
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
                    'BO' => 'Bolivia, Plurinational State of',
                    'BQ' => 'Bonaire, Sint Eustatius and Saba',
                    'BA' => 'Bosnia and Herzegovina',
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
                    'CD' => 'Congo, the Democratic Republic of the',
                    'CK' => 'Cook Islands',
                    'CR' => 'Costa Rica',
                    'CI' => "Côte d'Ivoire",
                    'HR' => 'Croatia',
                    'CU' => 'Cuba',
                    'CW' => 'Curaçao',
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
                    'HM' => 'Heard Island and McDonald Islands',
                    'VA' => 'Holy See (Vatican City State)',
                    'HN' => 'Honduras',
                    'HK' => 'Hong Kong',
                    'HU' => 'Hungary',
                    'IS' => 'Iceland',
                    'IN' => 'India',
                    'ID' => 'Indonesia',
                    'IR' => 'Iran, Islamic Republic of',
                    'IQ' => 'Iraq',
                    'IE' => 'Ireland',
                    'IM' => 'Isle of Man',
                    'IL' => 'Israel',
                    'IT' => 'Italy',
                    'JM' => 'Jamaica',
                    'JP' => 'Japan',
                    'JE' => 'Jersey',
                    'JO' => 'Jordan',
                    'KZ' => 'Kazakhstan',
                    'KE' => 'Kenya',
                    'KI' => 'Kiribati',
                    'KP' => "Korea, Democratic People's Republic of",
                    'KR' => 'Korea, Republic of',
                    'KW' => 'Kuwait',
                    'KG' => 'Kyrgyzstan',
                    'LA' => "Lao People's Democratic Republic",
                    'LV' => 'Latvia',
                    'LB' => 'Lebanon',
                    'LS' => 'Lesotho',
                    'LR' => 'Liberia',
                    'LY' => 'Libya',
                    'LI' => 'Liechtenstein',
                    'LT' => 'Lithuania',
                    'LU' => 'Luxembourg',
                    'MO' => 'Macao',
                    'MK' => 'Macedonia, The Former Yugoslav Republic of',
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
                    'FM' => 'Micronesia, Federated States of',
                    'MD' => 'Moldova, Republic of',
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
                    'RE' => 'Réunion',
                    'RO' => 'Romania',
                    'RU' => 'Russian Federation',
                    'RW' => 'Rwanda',
                    'BL' => 'Saint Barthélemy',
                    'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
                    'KN' => 'Saint Kitts and Nevis',
                    'LC' => 'Saint Lucia',
                    'MF' => 'Saint Martin (French part)',
                    'PM' => 'Saint Pierre and Miquelon',
                    'VC' => 'Saint Vincent and the Grenadines',
                    'WS' => 'Samoa',
                    'SM' => 'San Marino',
                    'ST' => 'Sao Tome and Principe',
                    'SA' => 'Saudi Arabia',
                    'SN' => 'Senegal',
                    'RS' => 'Serbia',
                    'SC' => 'Seychelles',
                    'SL' => 'Sierra Leone',
                    'SG' => 'Singapore',
                    'SX' => 'Sint Maarten (Dutch part)',
                    'SK' => 'Slovakia',
                    'SI' => 'Slovenia',
                    'SB' => 'Solomon Islands',
                    'SO' => 'Somalia',
                    'ZA' => 'South Africa',
                    'GS' => 'South Georgia and the South Sandwich Islands',
                    'SS' => 'South Sudan',
                    'ES' => 'Spain',
                    'LK' => 'Sri Lanka',
                    'SD' => 'Sudan',
                    'SR' => 'Suriname',
                    'SJ' => 'Svalbard and Jan Mayen',
                    'SZ' => 'Swaziland',
                    'SE' => 'Sweden',
                    'CH' => 'Switzerland',
                    'SY' => 'Syrian Arab Republic',
                    'TW' => 'Taiwan, Province of China',
                    'TJ' => 'Tajikistan',
                    'TZ' => 'Tanzania, United Republic of',
                    'TH' => 'Thailand',
                    'TL' => 'Timor-Leste',
                    'TG' => 'Togo',
                    'TK' => 'Tokelau',
                    'TO' => 'Tonga',
                    'TT' => 'Trinidad and Tobago',
                    'TN' => 'Tunisia',
                    'TR' => 'Turkey',
                    'TM' => 'Turkmenistan',
                    'TC' => 'Turks and Caicos Islands',
                    'TV' => 'Tuvalu',
                    'UG' => 'Uganda',
                    'UA' => 'Ukraine',
                    'AE' => 'United Arab Emirates',
                    'GB' => 'United Kingdom',
                    'US' => 'United States',
                    'UM' => 'United States Minor Outlying Islands',
                    'UY' => 'Uruguay',
                    'UZ' => 'Uzbekistan',
                    'VU' => 'Vanuatu',
                    'VE' => 'Venezuela, Bolivarian Republic of',
                    'VN' => 'Viet Nam',
                    'VG' => 'Virgin Islands, British',
                    'VI' => 'Virgin Islands, U.S.',
                    'WF' => 'Wallis and Futuna',
                    'EH' => 'Western Sahara',
                    'YE' => 'Yemen',
                    'ZM' => 'Zambia',
                    'ZW' => 'Zimbabwe',
                );
               
                foreach ($countries as $code => $name) {
                    echo '<option value="' . esc_attr($code) . '" ' . selected($selected_country, $code, false) . '>' . esc_html($name) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="languageSelect_generated" class="form-label">Post Language (for Generated Content):</label>
            <select class="form-select" id="languageSelect_generated" name="languageSelect">
                <?php foreach ($languages as $language): ?>
                    <option value="<?php echo esc_attr($language); ?>" <?php selected(isset($decoded_prompt['language']) ? $decoded_prompt['language'] : '', $language); ?>>
                        <?php echo esc_html($language); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="subtitleSelect" class="form-label">Subheading Count:</label>
            <select class="form-select" id="subtitleSelect" name="subtitleSelect">
                <?php
                $subheading_count = isset($decoded_prompt['subheading_count']) ? intval($decoded_prompt['subheading_count']) : 1;
                for ($i = 1; $i <= 10; $i++) {
                    echo '<option value="' . esc_attr($i) . '" ' . selected($subheading_count, $i, false) . '>' . esc_html($i) . '</option>';
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
                $writing_style = isset($decoded_prompt['writing_style']) ? $decoded_prompt['writing_style'] : '';
                foreach ($styles as $value => $name) {
                    echo '<option value="' . esc_attr($value) . '" ' . selected($writing_style, $value, false) . '>' . esc_html($name) . '</option>';
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

    <p>
      When the cron job is triggered, any task marked as "Active" will be executed.
      If you select "Inactive," this task will be skipped, and the cron job will move
      on to the remaining active tasks. This makes it easy to enable or disable tasks
      without removing them entirely.
    </p>
</div>
        <!-- Custom Tools Section -->
        <h4 style="font-weight: bold;">Custom Tools</h4>
        <div class="accordion mb-4" id="accordionCustomTools">
            <!-- Website Tools -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingWebsiteTools">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <button class="accordion-button flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWebsiteTools" aria-expanded="true" aria-controls="collapseWebsiteTools">
                            Website Tools
                        </button>
                        <div class="form-check form-switch ms-2">
                         
                            <input class="form-check-input" type="checkbox" role="switch" id="toggle_website_tools" name="enable_website_tools" <?php echo isset($decoded_prompt['enable_website_tools']) ? checked($decoded_prompt['enable_website_tools'], '1', false) : 'checked'; ?> onchange="toggleAccordion('collapseWebsiteTools', this.checked);">

                        </div>
                    </div>
                </h2>
                <div id="collapseWebsiteTools" class="accordion-collapse collapse <?php echo isset($decoded_prompt['enable_website_tools']) ? ($decoded_prompt['enable_website_tools'] == '1' ? 'show' : '') : 'show'; ?>" aria-labelledby="headingWebsiteTools">

                    <div class="accordion-body">
                        <div class="mb-3">
                            <label for="website_tools_knowledge_base_url" class="form-label">Knowledge Base URL</label>
                            <input type="url" class="form-control" id="website_tools_knowledge_base_url" name="website_tools_knowledge_base_url" placeholder="https://example.com" value="<?php echo isset($decoded_prompt['custom_tools']['website_tools']['knowledge_base_url']) ? esc_url($decoded_prompt['custom_tools']['website_tools']['knowledge_base_url']) : ''; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <!-- DuckDuckGO Search -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDuckDuckGO">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <button class="accordion-button flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDuckDuckGO" aria-expanded="true" aria-controls="collapseDuckDuckGO">
                            DuckDuckGO Search
                        </button>
                        <div class="form-check form-switch ms-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="toggle_duckduckgo" name="enable_duckduckgo" <?php echo isset($decoded_prompt['enable_duckduckgo']) ? checked($decoded_prompt['enable_duckduckgo'], '1', false) : 'checked'; ?> onchange="toggleAccordion('collapseDuckDuckGO', this.checked);">

                        </div>
                    </div>
                </h2>
                <div id="collapseDuckDuckGO" class="accordion-collapse collapse <?php echo isset($decoded_prompt['enable_duckduckgo']) ? ($decoded_prompt['enable_duckduckgo'] == '1' ? 'show' : '') : 'show'; ?>" aria-labelledby="headingDuckDuckGO">

                    <div class="accordion-body">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="duckduckgo_news" name="duckduckgo_news" value="1" <?php checked(isset($decoded_prompt['custom_tools']['duckduckgo']['news']) && $decoded_prompt['custom_tools']['duckduckgo']['news'] == '1'); ?>>
                            <label class="form-check-label" for="duckduckgo_news">Include News (Default: Enabled)</label>
                        </div>
                        <div class="mb-3">
                            <label for="duckduckgo_fixed_max_results" class="form-label">Fixed Max Results</label>
                            <input type="number" class="form-control" id="duckduckgo_fixed_max_results" name="duckduckgo_fixed_max_results" placeholder="Enter a number or leave blank" value="<?php echo isset($decoded_prompt['custom_tools']['duckduckgo']['fixed_max_results']) ? esc_attr($decoded_prompt['custom_tools']['duckduckgo']['fixed_max_results']) : ''; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Wikipedia -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingWikipedia">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <button class="accordion-button flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWikipedia" aria-expanded="true" aria-controls="collapseWikipedia">
                            Wikipedia
                        </button>
                        <div class="form-check form-switch ms-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="toggle_wikipedia" name="enable_wikipedia" <?php echo isset($decoded_prompt['enable_wikipedia']) ? checked($decoded_prompt['enable_wikipedia'], '1', false) : 'checked'; ?> onchange="toggleAccordion('collapseWikipedia', this.checked);">

                        </div>
                    </div>
                </h2>
                <div id="collapseWikipedia" class="accordion-collapse collapse <?php echo isset($decoded_prompt['enable_wikipedia']) ? ($decoded_prompt['enable_wikipedia'] == '1' ? 'show' : '') : 'show'; ?>" aria-labelledby="headingWikipedia">

                    <div class="accordion-body">
                        <div class="mb-3">
                            <label for="wikipedia_knowledge_base" class="form-label">Knowledge Base Topics</label>
                            <input type="text" class="form-control" id="wikipedia_knowledge_base" name="wikipedia_knowledge_base" placeholder="topic1, topic2, topic3" value="<?php echo isset($decoded_prompt['custom_tools']['wikipedia']['knowledge_base']) ? esc_attr($decoded_prompt['custom_tools']['wikipedia']['knowledge_base']) : ''; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <!-- YFinanceTools -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingYFinanceTools">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <button class="accordion-button flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseYFinanceTools" aria-expanded="true" aria-controls="collapseYFinanceTools">
                            YFinanceTools
                        </button>
                        <div class="form-check form-switch ms-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="toggle_yfinancetools" name="enable_yfinancetools" <?php echo isset($decoded_prompt['enable_yfinancetools']) ? checked($decoded_prompt['enable_yfinancetools'], '1', false) : 'checked'; ?> onchange="toggleAccordion('collapseYFinanceTools', this.checked);">

                        </div>
                    </div>
                </h2>
                <div id="collapseYFinanceTools" class="accordion-collapse collapse <?php echo isset($decoded_prompt['enable_yfinancetools']) ? ($decoded_prompt['enable_yfinancetools'] == '1' ? 'show' : '') : 'show'; ?>" aria-labelledby="headingYFinanceTools">

                    <div class="accordion-body">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="yfinance_stock_price" name="yfinance_stock_price" value="1" <?php checked(isset($decoded_prompt['custom_tools']['yfinancetools']['stock_price']) && $decoded_prompt['custom_tools']['yfinancetools']['stock_price'] == '1'); ?>>
                            <label class="form-check-label" for="yfinance_stock_price">Stock Price (Default: Enabled)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="yfinance_company_info" name="yfinance_company_info" value="1" <?php checked(isset($decoded_prompt['custom_tools']['yfinancetools']['company_info']) && $decoded_prompt['custom_tools']['yfinancetools']['company_info'] == '1'); ?>>
                            <label class="form-check-label" for="yfinance_company_info">Company Info</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="yfinance_stock_fundamentals" name="yfinance_stock_fundamentals" value="1" <?php checked(isset($decoded_prompt['custom_tools']['yfinancetools']['stock_fundamentals']) && $decoded_prompt['custom_tools']['yfinancetools']['stock_fundamentals'] == '1'); ?>>
                            <label class="form-check-label" for="yfinance_stock_fundamentals">Stock Fundamentals</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="yfinance_income_statements" name="yfinance_income_statements" value="1" <?php checked(isset($decoded_prompt['custom_tools']['yfinancetools']['income_statements']) && $decoded_prompt['custom_tools']['yfinancetools']['income_statements'] == '1'); ?>>
                            <label class="form-check-label" for="yfinance_income_statements">Income Statements</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="yfinance_key_financial_ratios" name="yfinance_key_financial_ratios" value="1" <?php checked(isset($decoded_prompt['custom_tools']['yfinancetools']['key_financial_ratios']) && $decoded_prompt['custom_tools']['yfinancetools']['key_financial_ratios'] == '1'); ?>>
                            <label class="form-check-label" for="yfinance_key_financial_ratios">Key Financial Ratios</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="yfinance_analyst_recommendations" name="yfinance_analyst_recommendations" value="1" <?php checked(isset($decoded_prompt['custom_tools']['yfinancetools']['analyst_recommendations']) && $decoded_prompt['custom_tools']['yfinancetools']['analyst_recommendations'] == '1'); ?>>
                            <label class="form-check-label" for="yfinance_analyst_recommendations">Analyst Recommendations</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="yfinance_company_news" name="yfinance_company_news" value="1" <?php checked(isset($decoded_prompt['custom_tools']['yfinancetools']['company_news']) && $decoded_prompt['custom_tools']['yfinancetools']['company_news'] == '1'); ?>>
                            <label class="form-check-label" for="yfinance_company_news">Company News</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="yfinance_technical_indicators" name="yfinance_technical_indicators" value="1" <?php checked(isset($decoded_prompt['custom_tools']['yfinancetools']['technical_indicators']) && $decoded_prompt['custom_tools']['yfinancetools']['technical_indicators'] == '1'); ?>>
                            <label class="form-check-label" for="yfinance_technical_indicators">Technical Indicators</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="yfinance_historical_prices" name="yfinance_historical_prices" value="1" <?php checked(isset($decoded_prompt['custom_tools']['yfinancetools']['historical_prices']) && $decoded_prompt['custom_tools']['yfinancetools']['historical_prices'] == '1'); ?>>
                            <label class="form-check-label" for="yfinance_historical_prices">Historical Prices</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hacker News -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingHackerNews">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <button class="accordion-button flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHackerNews" aria-expanded="true" aria-controls="collapseHackerNews">
                            Hacker News
                        </button>
                        <div class="form-check form-switch ms-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="toggle_hackernews" name="enable_hackernews" <?php echo isset($decoded_prompt['enable_hackernews']) ? checked($decoded_prompt['enable_hackernews'], '1', false) : 'checked'; ?> onchange="toggleAccordion('collapseHackerNews', this.checked);">

                        </div>
                    </div>
                </h2>
                <div id="collapseHackerNews" class="accordion-collapse collapse <?php echo isset($decoded_prompt['enable_hackernews']) ? ($decoded_prompt['enable_hackernews'] == '1' ? 'show' : '') : 'show'; ?>" aria-labelledby="headingHackerNews">

                    <div class="accordion-body">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="hackernews_get_top_stories" name="hackernews_get_top_stories" value="1" <?php checked(isset($decoded_prompt['custom_tools']['hackernews']['get_top_stories']) && $decoded_prompt['custom_tools']['hackernews']['get_top_stories'] == '1'); ?>>
                            <label class="form-check-label" for="hackernews_get_top_stories">Get Top Stories (Default: Enabled)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="hackernews_get_user_details" name="hackernews_get_user_details" value="1" <?php checked(isset($decoded_prompt['custom_tools']['hackernews']['get_user_details']) && $decoded_prompt['custom_tools']['hackernews']['get_user_details'] == '1'); ?>>
                            <label class="form-check-label" for="hackernews_get_user_details">Get User Details (Default: Enabled)</label>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End of Custom Tools -->
        <!-- Knowledge Base Section -->
        <h4 style="font-weight: bold;">Knowledge Base</h4>
        <div class="accordion mb-4" id="accordionKnowledgeBase">
            <!-- PDF Knowledge Base -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPDFKB">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <button class="accordion-button collapsed flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePDFKB" aria-expanded="false" aria-controls="collapsePDFKB">
                            PDF Knowledge Base
                        </button>
                        <div class="form-check form-switch ms-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="toggle_pdf_kb" name="enable_pdf_kb" <?php echo isset($decoded_prompt['enable_pdf_kb']) ? checked($decoded_prompt['enable_pdf_kb'], '1', false) : ''; ?> onchange="toggleAccordion('collapsePDFKB', this.checked);">

                        </div>
                    </div>
                </h2>
                <div id="collapsePDFKB" class="accordion-collapse collapse <?php echo isset($decoded_prompt['enable_pdf_kb']) && $decoded_prompt['enable_pdf_kb'] == '1' ? 'show' : ''; ?>" aria-labelledby="headingPDFKB">

                    <div class="accordion-body">
                        <div class="mb-3">
                            <label for="pdf_url_knowledge_base" class="form-label">PDF URL Knowledge Base</label>
                            <input type="url" class="form-control" id="pdf_url_knowledge_base" name="pdf_url_knowledge_base" placeholder="https://example.com/document.pdf" value="<?php echo isset($decoded_prompt['knowledge_base']['pdf_url']) ? esc_url($decoded_prompt['knowledge_base']['pdf_url']) : ''; ?>">
                            <div class="form-text">Provide a URL to a PDF document. If filled, it must point to a valid PDF file.</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- CSV Knowledge Base -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCSVKB">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <button class="accordion-button collapsed flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCSVKB" aria-expanded="false" aria-controls="collapseCSVKB">
                            CSV Knowledge Base
                        </button>
                        <div class="form-check form-switch ms-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="toggle_csv_kb" name="enable_csv_kb" <?php echo isset($decoded_prompt['enable_csv_kb']) ? checked($decoded_prompt['enable_csv_kb'], '1', false) : ''; ?> onchange="toggleAccordion('collapseCSVKB', this.checked);">

                        </div>
                    </div>
                </h2>
                <div id="collapseCSVKB" class="accordion-collapse collapse <?php echo isset($decoded_prompt['enable_csv_kb']) && $decoded_prompt['enable_csv_kb'] == '1' ? 'show' : ''; ?>" aria-labelledby="headingCSVKB">

                    <div class="accordion-body">
                        <div class="mb-3">
                            <label for="csv_url_knowledge_base" class="form-label">CSV URL Knowledge Base</label>
                            <input type="url" class="form-control" id="csv_url_knowledge_base" name="csv_url_knowledge_base" placeholder="https://example.com/data.csv" value="<?php echo isset($decoded_prompt['knowledge_base']['csv_url']) ? esc_url($decoded_prompt['knowledge_base']['csv_url']) : ''; ?>">
                            <div class="form-text">Provide a URL to a CSV file. If filled, it must be a valid URL.</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Text Knowledge Base -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTextKB">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <button class="accordion-button collapsed flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTextKB" aria-expanded="false" aria-controls="collapseTextKB">
                            Text Knowledge Base
                        </button>
                        <div class="form-check form-switch ms-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="toggle_text_kb" name="enable_text_kb" <?php echo isset($decoded_prompt['enable_text_kb']) ? checked($decoded_prompt['enable_text_kb'], '1', false) : ''; ?> onchange="toggleAccordion('collapseTextKB', this.checked);">

                        </div>
                    </div>
                </h2>
                <div id="collapseTextKB" class="accordion-collapse collapse <?php echo isset($decoded_prompt['enable_text_kb']) && $decoded_prompt['enable_text_kb'] == '1' ? 'show' : ''; ?>" aria-labelledby="headingTextKB">

                    <div class="accordion-body">
                        <div class="mb-3">
                            <label for="text_knowledge_base" class="form-label">Text Knowledge Base</label>
                            <textarea class="form-control" id="text_knowledge_base" name="text_knowledge_base" rows="5" placeholder="Enter your knowledge base text here..."><?php echo isset($decoded_prompt['knowledge_base']['text']) ? esc_textarea($decoded_prompt['knowledge_base']['text']) : ''; ?></textarea>
                            <div class="form-text">Enter plain text for your knowledge base. This field supports a longer description.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End of Knowledge Base -->
    </div>
    <script>
        function toggleAccordion(collapseId, isActive) {
            var collapseElement = document.getElementById(collapseId);
            var bsCollapse = new bootstrap.Collapse(collapseElement, { toggle: false });
            if (isActive) {
                bsCollapse.show();
            } else {
                bsCollapse.hide();
            }
        }
    </script>

    
    <?php
}

?>
