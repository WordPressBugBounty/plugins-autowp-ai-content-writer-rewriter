<?php




function autowp_rss_website_form_page_handler(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'autowp_wordpress_websites'; 
  
    $message = '';
    $notice = '';
  
  
    $default = array(
        'id' => 0,
        'website_name'             => '',
        'website_type'             => '',
        'domain_name'              => '',
        'category_id'              => '',
        'aigenerated_title'        => '',
        'aigenerated_content'      => '',
        'aigenerated_tags'         => '',
        'aigenerated_image'        => '',
        'post_count'               => '',
        'post_order'               => '',
        'title_prompt'             => '',
        'content_prompt'           => '',
        'tags_prompt'              => '',
        'image_prompt'             => '',
        'image_generating_status'  => '',
        'author_selection'         => '',
        'active'                   => ''
       

        
    );
  
  
    if ( isset($_REQUEST['nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['nonce'])), basename(__FILE__))) {
  
  
      
        
        // Process only the specific values needed
        $item = array(
          'id'                => isset($_POST['id']) ? intval($_POST['id']) : 0,
          'website_name'      => sanitize_text_field($_POST['website_name']),
          'domain_name'       => sanitize_text_field($_POST['domain_name']),
          'category_id'       => isset($_POST['category_id']) ? array_map('intval', $_POST['category_id']) : array(),
          'aigenerated_title'  => '1',
          'aigenerated_content'=> '1',
          'aigenerated_tags'   => '1',
          'aigenerated_image'  => '1',
          'post_count'         => 5,
          'post_order'         => sanitize_text_field($_POST['post_order']),
          'title_prompt'      => sanitize_textarea_field($_POST['title_prompt']),

          'content_prompt' => '[autowp-rewriting-promptcode]' . 
                    sanitize_text_field($_POST['languageSelect']) . ',' .
                    sanitize_text_field($_POST['subtitleSelect']) . ',' .
                    sanitize_text_field($_POST['narrationSelect']) .
                    '[/autowp-rewriting-promptcode]',

         
          'tags_prompt'       => sanitize_textarea_field($_POST['tags_prompt']),
          'image_prompt'      => sanitize_textarea_field($_POST['image_prompt']),
          'website_type'      => 'rss',
          'image_generating_status' => sanitize_textarea_field($_POST['image_generating_status']),
          'author_selection'        => sanitize_text_field($_POST['author_selection']),
          'active'                  => sanitize_text_field($_POST['active']) ?? '1'
        );


        //Convert category_id array to text
        $category_ids = implode(",", $item['category_id']);
        $item['category_id'] = $category_ids;
        
  
        if ($item['aigenerated_title'] !== '1'){
          $item['aigenerated_title'] = '0';
        }
  
        if($item['aigenerated_content'] !== '1'){
          $item['aigenerated_content'] = '0';
        }
  
        if($item['aigenerated_tags'] !== '1'){
          $item['aigenerated_tags'] = '0';
        }
  
        if($item['aigenerated_image'] !== '1'){
          $item['aigenerated_image'] = '0';
        }

        $item['website_type'] = 'rss';
  
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
      if (isset($_REQUEST['id'])) {
          $item_id = absint($_REQUEST['id']); // Sanitize the input as an integer
          $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $item_id), ARRAY_A);
      
          if (!$item) {
              $item = $default;
              $notice = esc_html__('Item not found', 'autowp'); // Escape the notice message
          }
      }
      
    }
  
    
    add_meta_box('websites_form_meta_box', __('Website Form', 'autowp'), 'autowp_rss_websites_form_meta_box_handler', 'add_new_rss_website_form', 'normal', 'default');
  
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

                    <?php do_meta_boxes('add_new_rss_website_form', 'normal', $item); ?>
                    <input type="submit" value="<?php esc_attr_e('Save', 'autowp')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
  </div>
  <?php
  }

  

function autowp_rss_websites_form_meta_box_handler($item){

      // Parsing the example content_prompt
      $parsed_values = autowp_parse_rewriting_prompt_code($item['content_prompt']) ?? '';

     
      $promptcode_language = $parsed_values['language'] ?? '';
      $promptcode_subtitle = $parsed_values['subtitle'] ?? '';
      $promptcode_narration = $parsed_values['narration'] ?? '';


    ?>
  <tbody >
  <form>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <div class="container">
    <form class="row g-3">
    <h4 style="font-weight: bold;">General Settings</h4>

        <div class="col-md-6">
            <label for="website_name" class="form-label">Website Name:</label>
            <?php
            $autowp_admin_email = autowp_get_admin_email();
            $autowp_domain_name = esc_url(get_site_url());

            echo '<input type="hidden" id="autowp_admin_email" value="' . esc_attr($autowp_admin_email) . '">';
            echo '<input type="hidden" id="autowp_domain_name" value="' . esc_attr($autowp_domain_name) . '">';
            ?>

            <input id="website_name" name="website_name" type="text" value="<?php echo esc_attr($item['website_name']) ?>" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="domain_name" class="form-label">RSS Feed URL:</label>
            <input id="domain_name" name="domain_name" type="text" value="<?php echo esc_attr($item['domain_name']) ?>" class="form-control" required>
            <p class="form-text"><?php esc_html_e('E.g https://kelimelerbenim.com/feed', 'autowp'); ?></p>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="author_selection"><?php esc_html_e('Author Selection', 'autowp'); ?></label>
            <select name="author_selection" class="form-select">
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
            <label for="category_id" class="form-label">Categories:</label>
            <select id="category_id" name="category_id[]" class="form-select" required multiple>
                <?php
                $selected_categories = $item['category_id'];
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


        <div class="col-md-6">
            <label for="post_order" class="form-label">Post Order:</label>
            <select id="post_order" name="post_order" class="form-select">
                <option value="desc" <?php if ($item['post_order'] === 'desc') {
                    echo 'selected';
                } ?>>Latest First</option>
                <option value="asc" <?php if ($item['post_order'] === 'asc') {
                    echo 'selected';
                } ?>>Oldest First</option>
                <option value="rand" <?php if ($item['post_order'] === 'rand') {
                    echo 'selected';
                } ?>>Random</option>
            </select>
        </div>
       

<br>
<h4 style="font-weight: bold;">Post Settings</h4>
<br>
<div class="col-md-6">
    <label for="languageSelect" class="form-label">Post Language:</label>
    <select class="form-select" id="languageSelect" name="languageSelect">
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
  

<?php foreach ($languages as $language): ?>
    <option value="<?php echo esc_attr($language); ?>" <?php echo ($language === $promptcode_language) ? 'selected' : ''; ?>>
        <?php echo esc_html($language); ?>
    </option>
<?php endforeach; ?>


    </select>
</div>
<br>
<div class="col-md-6">
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
<br>
<div class="col-md-6">
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

<br>
<h4 style="font-weight: bold;">Source Status</h4>


<!-- Status Field (Active/Inactive) -->
<div class="col-md-6">
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


<br>
    </form>
   
</div>

  
  
  
  
  
  </form>
  
  
  </tbody>
  <?php
  }

?>