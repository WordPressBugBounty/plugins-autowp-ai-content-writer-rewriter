<?php
function autowp_wp_page_handler()
{
    global $wpdb;

    $table = new AutoWP_Wordpress_Websites();
    
    $table->prepare_items();

    $message = '';
    if ('delete_all' === $table->current_action()) {

      // Good idea to make sure things are set before using them
      $deleted_items_ids = isset($_POST['id']) ? array_map('absint', (array)$_POST['id']) : array();
  
      // Sanitize the array using array_map and absint
      $deleted_items_ids = array_map('absint', $deleted_items_ids);
  
      // Validate the IDs, make sure they are positive integers
      $deleted_items_ids = array_filter($deleted_items_ids, function ($id) {
          return $id > 0;
      });
  
      $message = 'Items deleted: ' . count($deleted_items_ids);
  
      // Escape the message before outputting it
      $message = esc_html($message);
   }
  

    if ('delete' === $table->current_action()) {
      // Sanitize and escape the 'id' parameter
      $deleted_item_id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0;
      
      // Escape the output and use the sprintf function for better readability
      $message = 'Item deleted : '  .  $deleted_item_id;
  }
  
    
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php esc_html_e('Websites', 'autowp'); ?> <a class="add-new-h2"
        href="<?php echo esc_url(get_admin_url(get_current_blog_id(), 'admin.php?page=autowp_add_new_website_form')); ?>"><?php esc_html_e('Add new', 'autowp'); ?></a>
    </h2>

    <?php 
    if($message && !empty($message)){
      echo '<div class="updated below-h2" id="message"><p>' . esc_html($message) . '</p></div>'; 

    }
    ?>

    <form id="contacts-table" method="POST">
    <?php
    // Sanitize and escape the 'page' parameter
    $page_value = isset($_REQUEST['page']) ? sanitize_text_field($_REQUEST['page']) : '';
    ?>
    <input type="hidden" name="page" value="<?php echo esc_html($page_value); ?>"/>
    <?php $table->display() ?>
</form>


</div>
<?php
}


function autowp_wp_website_form_page_handler(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'autowp_wordpress_websites'; 

    $message = '';
    $notice = '';


    $default = array(
        'id' => 0,
        'website_name'             => '',
        'domain_name'              => '',
        'category_id'              => '',
        'website_category_id'      => '',
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
        'active'                   => '',
      
        
    );


    if ( isset($_REQUEST['nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['nonce'])), basename(__FILE__))) {


      
        
        // Process only the specific values needed
        $item = array(
          'id'                => isset($_POST['id']) ? intval($_POST['id']) : 0,
          'website_name'      => sanitize_text_field($_POST['website_name']),
          'domain_name'       => sanitize_url($_POST['domain_name']),
          'category_id'       => isset($_POST['category_id']) ? array_map('intval', $_POST['category_id']) : array(),
          'website_category_id'=> isset($_POST['website_category_id']) ? array_map('intval', $_POST['website_category_id']) : array(),
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
          'image_generating_status' => sanitize_textarea_field($_POST['image_generating_status']),
          'website_type'      => 'wordpress',
          'author_selection'  => sanitize_text_field($_POST['author_selection']),
          'active'            => sanitize_text_field(($_POST['active'])) ?? '1'
        );
        //Convert category_id array to text
        $category_ids = implode(",", $item['category_id']);
        $item['category_id'] = $category_ids;
        //Convert website_category_id to text
        if($item['website_category_id']){
          $website_category_ids = implode(",", $item['website_category_id']);
          $item['website_category_id'] = $website_category_ids;
        }
        

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

        $item['website_type'] = 'wordpress';

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
          $sanitized_id = absint($_REQUEST['id']); // Sanitize as an integer
          $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $sanitized_id), ARRAY_A);
          if (!$item) {
              $item = $default;
              $notice = __('Item not found', 'autowp');
          }
      }
      
    }

    
    add_meta_box('websites_form_meta_box', __('Website Form', 'autowp'), 'autowp_websites_form_meta_box_handler', 'add_new_wp_website_form', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php esc_html_e('Add New', 'autowp'); ?> <a class="add-new-h2"
        href="<?php echo esc_url(get_admin_url(get_current_blog_id(), 'admin.php?page=autowp_automaticPost')); ?>"><?php esc_html_e('Back to List', 'autowp'); ?></a>
    </h2>


    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo esc_attr($notice) ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo esc_attr($message) ?></p></div>
    <?php endif;?>

    <form id="form" method="POST">
        <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce(basename(__FILE__))); ?>"/>
        
        <input type="hidden" name="id" value="<?php echo esc_attr($item['id']) ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    
                    <?php do_meta_boxes('add_new_wp_website_form', 'normal', $item); ?>
                    <input type="submit" value="<?php esc_attr_e('Save', 'autowp')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}


function autowp_get_categories_from_wordpress_website($user_domainname, $user_email, $website_domainname) {

    $url = 'https://api.autowp.app/getWebsiteCategories';

    // Set request data
    $data = array(
        'user_domainname' => $user_domainname,
        'user_email' => $user_email,
        'website_domainname' => $website_domainname
    );
  
    $response = wp_remote_post(
      $url,
      array(
        'timeout' => 120,
        'headers' => array(
          'Content-Type' => 'application/json'
        ),
        'body' => json_encode($data)
      )
    );

    $response_data = wp_remote_retrieve_body($response);
      $responseData = json_decode($response_data, true);
      return $responseData;


  
    if ( is_wp_error( $response ) ) {
      $error_message = $response->get_error_message();
      return "Something went wrong: $error_message";
    } else {
      $response_data = wp_remote_retrieve_body($response);
      $responseData = json_decode($response_data, true);
      return $responseData;
    } 
    
    
    
}


function autowp_get_admin_email(){
    $admin_email = get_option('admin_email', false);
    if ($admin_email !== false) {


    } else {
      $admin_email = 'email@example.com';
    }
    return $admin_email;
}
  

  

function autowp_websites_form_meta_box_handler($item)
{

    // Parsing the example content_prompt
    $parsed_values = autowp_parse_rewriting_prompt_code($item['content_prompt']) ?? '';

     
    $promptcode_language = $parsed_values['language'] ?? '';
    $promptcode_subtitle = $parsed_values['subtitle'] ?? '';
    $promptcode_narration = $parsed_values['narration'] ?? '';
    ?>
    <div id="loading">
<div class="loader">
  <div class="inner one"></div>
  <div class="inner two"></div>
  <div class="inner three"></div>
</div>
</div>

<tbody >
<form>
  <div class="form2bc">
  <div class="container">
  <form class="row g-3">
    <div class="col-md-6">
      <label for="website_name" class="form-label">Website Name:</label>
      <?php
      //Get admin domain name
      $autowp_admin_email = autowp_get_admin_email();
      $autowp_domain_name = esc_url(get_site_url());

      $is_empty = empty($item['domain_name']);
      ?>

      <input type="hidden" id="autowp_admin_email" value="<?php echo esc_attr($autowp_admin_email); ?>">
      <input type="hidden" id="autowp_domain_name" value="<?php echo esc_attr($autowp_domain_name); ?>">

      <input id="website_name" name="website_name" type="text" class="form-control" value="<?php echo esc_attr($item['website_name']); ?>" required>
    </div>
    <div class="col-md-6">
      <label for="domain_name" class="form-label">Domain Name:</label>
      <input id="domain_name" name="domain_name" type="url" class="form-control" value="<?php echo esc_attr($item['domain_name']); ?>" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Author Selection</label>
      <select name="author_selection" class="form-select">
        <?php
        $authors = get_users();

        foreach ($authors as $author) {
          $author_id = $author->ID;
          $author_name = $author->display_name;
          $author_description = get_the_author_meta('description', $author_id); // Yazarın açıklamasını al

          if ($item['author_selection'] === strval($author_id)) {
            echo '<option value="' . esc_attr($author_id) . '" selected>' . esc_html($author_name) . '</option>';
            continue;
          }

          echo '<option value="' . esc_attr($author_id) . '">' . esc_html($author_name) . '</option>';
        }
        ?>
      </select>
      <p class="form-text">Select an author from the list.</p>
    </div>

    <div class="col-md-6">
      <label for="website_category_id" class="form-label">Website Categories:</label><br>
      <select id="website_category_id" name="website_category_id[]" multiple class="form-select" <?php
                                                                                            if ($is_empty) {
                                                                                              echo 'style="display: none;"';
                                                                                            }
                                                                                            ?>>
        <?php

        //Get selected categories
        $selected_website_categories = $item['website_category_id'];
        //Turn categories to array list
        $selected_website_categories = explode(',', $selected_website_categories);

        //Get website categories from domain
        if (!$is_empty) {

          $autowp_wp_website_domain_name = esc_url($item['domain_name']);
          $website_categories = autowp_get_categories_from_wordpress_website($autowp_domain_name, $autowp_domain_name, $autowp_wp_website_domain_name);

          if (!$website_categories['error']) {

            foreach ($website_categories as $website_category) {
              if (in_array($website_category['id'], $selected_website_categories)) {
                echo '<option value="' . esc_attr($website_category['id']) . '" selected>' . esc_html($website_category['name']) . '</option>';
                continue;
              }
              echo '<option value="' . esc_attr($website_category['id']) . '">' . esc_html($website_category['name']) . '</option>';
            }
          }
        }
        ?>
      </select>
      <button type="button" class="btn btn-primary" onclick="refreshWebsiteCategories()">
        <i class="bi bi-arrow-clockwise"></i>
        <?php
        $category_button_name = 'Get Categories';

        if (!$is_empty) {
          $category_button_name = 'Refresh';
        }

        echo esc_html($category_button_name);
        ?>
      </button>
    </div>
<br>
    <div class="col-md-6">
      <label for="category_id" class="form-label">Categories:</label>
      <select id="category_id" name="category_id[]" required multiple class="form-select">
        <?php

        //Get selected categories
        $selected_categories = $item['category_id'];
        //Turn categories to array list
        $selected_categories = explode(',', $selected_categories);

        $categories = get_categories(array(
          'orderby' => 'name',
          'order' => 'ASC',
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
<br>
 

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

<option value="8" <?php if ($item['image_generating_status'] === '8') {
            echo 'selected';
        } ?>><?php esc_html_e('Original Image', 'autowp'); ?></option>
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
            echo '<option value="' . esc_attr($i) . '" ' . esc_attr($selected) . '>' . esc_html($i) . '</option>';
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

function autowp_website_selection_page_handler(){
  ?>
  <!-- Add this in the <head> section of your HTML -->

  <div class="wrap">
    <h2><?php esc_attr_e('Add New Source', 'autowp')?></h2>
    <p><?php esc_attr_e('Please select the type of source you want to add for automatic generating:', 'autowp')?></p>
    <ul class="list-group">
      <li class="list-group-item">
      <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=add_new_wp_website_form')); ?>" class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
          <img src="<?php echo esc_url( plugins_url( '../assets/images/wordpress-icon.png', __FILE__ ) ); ?>" alt="<?php echo esc_attr( 'WordPress Website' ); ?>" class="me-3" style="width: 80px; height: 80px;">
            <div>
              <h5><?php esc_attr_e('AI-Rewrite From Wordpress Website', 'autowp')?></h5>
              <p><?php esc_attr_e('Fetch posts from WordPress site and rewrite with artificial intelligence.', 'autowp')?></p>
            </div>
          </div>
        </a>
      </li>
      <li class="list-group-item">
      <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=add_new_rss_website_form')); ?>" class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
          <img src="<?php echo esc_url(plugins_url('../assets/images/rss-icon.png', __FILE__)); ?>" alt="<?php esc_attr_e('RSS Website'); ?>" class="me-3" style="width: 80px; height: 80px;">
            <div>
              <h5><?php esc_attr_e('AI-Rewrite From RSS Website', 'autowp')?></h5>
              <p><?php esc_attr_e('Fetch content with RSS and rewrite with artificial intelligence.', 'autowp')?></p>
            </div>
          </div>
        </a>
      </li>


      <li class="list-group-item">
      <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=add_new_ai_website_form')); ?>" class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
          <img src="<?php echo esc_url(plugins_url('../assets/images/robot-icon.png', __FILE__)); ?>" alt="<?php esc_attr_e('Artificial Intelligence'); ?>" class="me-3" style="width: 80px; height: 80px;">
            <div>
              <h5><?php esc_attr_e('AI Agents with Web Research Tools', 'autowp')?></h5>
              <p><?php esc_attr_e('Create original content from scratch using AutoWP AI Agent with web research tool !', 'autowp')?></p>
            </div>
          </div>
        </a>
      </li>


      <li class="list-group-item">
      <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=add_new_agenticscraper_form')); ?>" class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
          <img src="<?php echo esc_url(plugins_url('../assets/images/robot-icon.png', __FILE__)); ?>" alt="<?php esc_attr_e('Artificial Intelligence'); ?>" class="me-3" style="width: 80px; height: 80px;">
            <div>
              <h5><?php esc_attr_e('Agentic Scraper with Custom Tools and Prompts', 'autowp')?></h5>
              <p><?php esc_attr_e('Create your own Agentic Scraper with your customize tools and prompts!', 'autowp')?></p>
            </div>
          </div>
        </a>
      </li>




      <li class="list-group-item">
      <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=add_new_news_website_form')); ?>" class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
          <img src="<?php echo esc_url(plugins_url('../assets/images/gnews.png', __FILE__)); ?>" alt="<?php esc_attr_e('Rewrite With AI From News'); ?>" class="me-3" style="width: 80px; height: 80px;">
            <div>
            <h5><?php esc_html_e('AI-Rewrite News From Google News ', 'autowp')?></h5>
            <p><?php esc_html_e('Fetch content from Google News and rewrite with artificial intelligence.', 'autowp')?></p>
            </div>
          </div>
        </a>
      </li>



         <!-- New item with modal trigger -->
   <li class="list-group-item">
        <a href="#" data-bs-toggle="modal" data-bs-target="#tutorialModal" class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <img src="<?php echo esc_url(plugins_url( '../assets/images/tutorial.png', __FILE__ )); ?>" alt="Tutorial" class="me-3" style="width: 80px; height: 80px;">
            <div>
              <h5><?php esc_html_e('Video Tutorials', 'autowp')?></h5>
              <p><?php esc_html_e('Learn how to add new process.', 'autowp')?></p>
            </div>
          </div>
        </a>
      </li>


       <!-- Bootstrap Modal -->
<div class="modal fade" id="tutorialModal" tabindex="-1" aria-labelledby="tutorialModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tutorialModalLabel">Video Tutorials</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="mb-3">
          <h5>Manual Processes:</h5>
          <p>After clicking the "Generate Post" button at the bottom, your process will start running in the background. The average duration of this process is around 5 minutes. If an error occurs, you can find detailed information at the top of your WordPress admin panel.</p>
        </div>

        <div class="mb-3">
          <h5>Automatic Processes:</h5>
          <p>For each added process, it will be automatically triggered at the specified time interval (you can set this in the settings, under the cron section). Therefore, after adding processes, you need to set the time and wait for the posts to be generated automatically.</p>
        </div>
       <!-- Video Tutorials -->
<div class="mb-3">
  <h6>How to Use - Detailed Tutorial</h6>
  <iframe width="450" height="350" src="https://www.youtube.com/embed/idN8NNyyjW8" frameborder="0" allowfullscreen></iframe>
</div>

<!-- Timestamp Links -->
<div class="video-timestamps">
  <ul>
    <li><a href="https://www.youtube.com/watch?v=idN8NNyyjW8&t=0s" target="_blank"><strong>0:00</strong> - Setup AutoWP</a></li>
    <li><a href="https://www.youtube.com/watch?v=idN8NNyyjW8&t=46s" target="_blank"><strong>0:46</strong> - Add WordPress website as a source</a></li>
    <li><a href="https://www.youtube.com/watch?v=idN8NNyyjW8&t=90s" target="_blank"><strong>1:30</strong> - Add RSS Feed as a source</a></li>
    <li><a href="https://www.youtube.com/watch?v=idN8NNyyjW8&t=126s" target="_blank"><strong>2:06</strong> - Add Google News as a source</a></li>
    <li><a href="https://www.youtube.com/watch?v=idN8NNyyjW8&t=178s" target="_blank"><strong>2:58</strong> - Posts generated by AutoWP</a></li>
    <li><a href="https://www.youtube.com/watch?v=idN8NNyyjW8&t=204s" target="_blank"><strong>3:24</strong> - Content Planner – Adding a Section with Your Own Prompt</a></li>
    <li><a href="https://www.youtube.com/watch?v=idN8NNyyjW8&t=440s" target="_blank"><strong>7:20</strong> - Content Planner – Adding a Fixed HTML Section</a></li>
    <li><a href="https://www.youtube.com/watch?v=idN8NNyyjW8&t=455s" target="_blank"><strong>7:35</strong> - Content Planner – Ready-Made Section Templates</a></li>
    <li><a href="https://www.youtube.com/watch?v=idN8NNyyjW8&t=466s" target="_blank"><strong>7:46</strong> - Content Planner – Adding/Removing Ready-Made Section Templates</a></li>
    <li><a href="https://www.youtube.com/watch?v=idN8NNyyjW8&t=487s" target="_blank"><strong>8:07</strong> - Exit</a></li>
  </ul>
</div>

     

      
        <!-- End Video Tutorials -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



    </ul>
    
  </div>
  <?php
}


