<?php 



/**
 * Plugin Name:       AutoWP - AI Content Writer & Rewriter
 * Plugin URI:        https://autowp.app
 * Description:       AI Content Writer & Rewriter. Write content with AI from zero. Import content from RSS, Wordpress and rewrite with AI. Generate SEO optimized content,tags,title and generate image. ChatGPT, Content Writer, Auto Content Writer, Image Generator, AutoGPT, ChatPDF, SEO optimizer, AI Training.
 * Version:           2.2.4
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Neuralabz LTD.
 * Author URI:        https://autowp.app
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       autowp
 * Domain Path:       /languages
 */



 defined( 'ABSPATH' ) or die( 'PERMİSSİON ERROR!' );
 
 require plugin_dir_path( __FILE__ ) . 'includes/new-wp-website-form.php';
 require plugin_dir_path( __FILE__ ) . 'includes/new-rss-website-form.php';
 require plugin_dir_path( __FILE__ ) . 'includes/new-ai-website-form.php';
 require plugin_dir_path( __FILE__ ) . 'includes/new-news-website-form.php';
 require plugin_dir_path( __FILE__ ) . 'includes/new-own-ai-agent-form.php';




 
// Enqueque JS Files
function autowp_enqueue_scripts() {
    $autowp_my_plugin_dir_url  = plugin_dir_url(__FILE__);
    $autowp_my_plugin_dir_path = plugin_dir_path(__FILE__);

    $bootstrap_js_path = $autowp_my_plugin_dir_path . 'assets/js/bootstrap.min.js';
    $bootstrap_js_url  = $autowp_my_plugin_dir_url . 'assets/js/bootstrap.min.js';
    $bootstrap_js_ver  = file_exists($bootstrap_js_path) ? filemtime($bootstrap_js_path) : false;

    //Enqueque AutoWP JS File
    wp_register_script('bootstrapjs', $bootstrap_js_url, array('jquery'), $bootstrap_js_ver, true);

    wp_enqueue_script( 'bootstrapjs' );

    wp_register_script( 'autowpjs',$autowp_my_plugin_dir_url.'assets/js/autowp.js' , array('jquery'), false, true );
    wp_enqueue_script( 'autowpjs' );

    wp_register_script( 'autowp_ai_modal',$autowp_my_plugin_dir_url.'assets/js/autowp_ai_modal.js' , array('jquery'), false, true );
    wp_enqueue_script( 'autowp_ai_modal' );

    wp_register_script( 'autowp_rewriting_modal',$autowp_my_plugin_dir_url.'assets/js/autowp_rewriting_modal.js' , array('jquery'), false, true );
    wp_enqueue_script( 'autowp_rewriting_modal' );

    wp_register_script( 'autowp_bootstrap_bundle',$autowp_my_plugin_dir_url.'assets/js/bootstrap.bundle.min.js' , array('jquery','autowp_jquery_ui'), false, true );
    wp_enqueue_script( 'autowp_bootstrap_bundle' );

    wp_register_script( 'autowp_jquery_ui',$autowp_my_plugin_dir_url.'assets/js/jquery-ui.min.js' , array('jquery'), false, true );
    wp_enqueue_script( 'autowp_jquery_ui' );

    wp_register_script( 'autowp_sortable_list',$autowp_my_plugin_dir_url.'assets/js/sortable_list.js' , array('jquery'), false, true );
    wp_enqueue_script( 'autowp_sortable_list' );

    wp_enqueue_script('autowp-toggle-js', plugins_url('assets/js/admin-toggle.js', __FILE__), array('jquery'), '1.0', true);
  

    
    

    



}

add_action('admin_enqueue_scripts','autowp_enqueue_scripts');


function autowp_toggle_website() {
  // Güvenlik kontrolü: Nonce doğrulaması
  check_ajax_referer('autowp_toggle_nonce', 'security');
  $website_id = intval($_POST['id']);
  global $wpdb;
  $table_name = $wpdb->prefix . 'autowp_wordpress_websites';
  
  // Mevcut "active" değerini al (eğer yoksa varsayılan olarak 1 kabul edilir)
  $current = $wpdb->get_var($wpdb->prepare("SELECT active FROM $table_name WHERE id = %d", $website_id));
  $current = isset($current) ? intval($current) : 1;
  // Yeni durumu tersine çevir: Eğer aktifse 0 (pasif) yap, pasifse 1 (aktif) yap
  $new_status = $current ? 0 : 1;
  
  // Veritabanında güncelleme yapın
  $wpdb->update($table_name, array('active' => $new_status), array('id' => $website_id), array('%d'), array('%d'));
  wp_send_json_success(array('new_status' => $new_status));
}
add_action('wp_ajax_autowp_toggle_website', 'autowp_toggle_website');


  
//Set FAQ Schema

// JSON-LD ekleme fonksiyonu


// 4. Post başlığında meta veriyi kontrol et ve şema yapısını ekle
function inject_faq_schema_into_head() {
  if (is_single()) {
      global $post;
      $faq_schema = get_post_meta($post->ID, '_faq_schema', true);

      // Meta veride schema varsa header'a ekleyelim
      if ($faq_schema) {
          // Yalnızca belirli HTML etiketlerine izin verelim
          $allowed_tags = array(
              'script' => array(
                  'type' => true,
              ),
          );

          echo wp_kses($faq_schema, $allowed_tags);
      }
  }
}
add_action('wp_head', 'inject_faq_schema_into_head');





  
//Enqueque CSS Files
function autowp_enqueue_styles(){
    $my_plugin_dir = plugin_dir_url(__FILE__);

    $screen = get_current_screen();
    $slug = $screen->id;


      


    if($slug == 'toplevel_page_autowp_menu' || $slug == 'autowp_page_autowp_manualPost' || $slug == 'autowp_page_autowp_automaticPost' || $slug == 'admin_page_add_new_wp_website_form' || $slug == 'autowp_page_autowp_settings' || $slug == 'autowp_page_autowp_linking_management' || $slug == 'admin_page_add_new_rss_website_form' || $slug == 'admin_page_add_new_ai_website_form' || $slug == 'autowp_page_autowp_add_new_website_form' || $slug == 'admin_page_add_new_agenticscraper_form' || $slug == 'admin_page_manual_post_wp_website_form'  || $slug == 'admin_page_manual_post_rss_website_form' || $slug == 'admin_page_manual_post_ai_website_form' || $slug == 'admin_page_manual_post_agenticscraper_website_form'  || $slug == 'admin_page_manual_post_news_website_form' || $slug == 'admin_page_add_new_news_website_form' || $slug == 'admin_page_autowp_promptschemes' || $slug == 'autowp_page_autowp_promptSettings' or $slug == 'admin_page_autowp_rewriting_promptschemes' or $slug=='admin_page_autowp-setup'  ){
      wp_register_style('autowp_bootstrap', $my_plugin_dir.'assets/css/bootstrap.min.css', array(), 1);
      wp_enqueue_style('autowp_bootstrap');
      
    


      wp_register_style('autowp_jquery_ui', $my_plugin_dir.'assets/css/jquery-ui.css', array(), 1);    
      wp_enqueue_style('autowp_jquery_ui');

      
      wp_register_style('autowp_loader', $my_plugin_dir.'assets/css/loader.css', array(), 1);    
      wp_enqueue_style('autowp_loader');

      wp_register_style('autowp_style', $my_plugin_dir.'assets/css/style.css', array(), 1);    
      wp_enqueue_style('autowp_style');


     

   

    }

   
  
  
}
    
add_action('admin_print_styles','autowp_enqueue_styles');

//Register AutoWP API with Domain

// Eklenti etkinleştirildiğinde çalışacak fonksiyon
function autowp_activate() {
  $settings = unserialize(get_option('autowp_settings'));

  // API e-posta ve anahtarını kontrol et
  if (empty($settings['api_email']) || empty($settings['api_key'])) {
      // Kullanıcıyı kurulum sayfasına yönlendir
      add_option('autowp_show_setup', true);
  }
}
register_activation_hook(__FILE__, 'autowp_activate');

// Admin sayfasına yönlendirme
function autowp_redirect_to_setup() {
  if (get_option('autowp_show_setup')) {
      delete_option('autowp_show_setup');
      wp_redirect(admin_url('admin.php?page=autowp-setup'));
      exit;
  }
}
add_action('admin_init', 'autowp_redirect_to_setup');



function autowp_get_page_slug_from_website_type($website_type){

  switch($website_type){
    case 'wordpress':
      return 'add_new_wp_website_form';
    case 'rss':
      return 'add_new_rss_website_form';
    case 'ai':
      return 'add_new_ai_website_form';
    case 'news':
      return 'add_new_news_website_form';
    case 'agenticscraper':
      return 'add_new_agenticscraper_form';
    default:
      return '';
  }

}

//WP-CRON START


function autowp_get_wpcron_time($time){

  switch($time){
    case 1:
      return 'hourly';
    case 2:
      return 'twicedaily';
    case 3:
      return 'daily';
    case 4:
      return 'weekly';
    default:
      return 'hourly';

  }

}


function autowp_set_featured_image($image_url, $post_id) {
  $upload_dir = wp_upload_dir();

  // Kullanılacak güvenli fonksiyon: wp_remote_get
  $response = wp_remote_get($image_url);

  // HTTP hata kontrolü
  if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
      return false;
  }

  $image_data = wp_remote_retrieve_body($response);

  if ($image_data) {
      $filename = sanitize_file_name(basename($image_url));
      $file_path = trailingslashit($upload_dir['path']) . $filename;
      $file_path = wp_unique_filename($upload_dir['path'], $filename); // Make sure the file name is unique

      // Güvenli bir şekilde dosyayı kaydet
      $file_saved = wp_upload_bits($filename, null, $image_data);

      if (!$file_saved['error']) {
          $file = $file_saved['file'];

          $wp_filetype = wp_check_filetype($file, null);

          $attachment = array(
              'post_mime_type' => $wp_filetype['type'],
              'post_title'     => $filename,
              'post_content'   => '',
              'post_status'    => 'inherit'
          );

          // Use the 'wp_insert_attachment_data' filter to modify attachment data before insertion
          $attachment = apply_filters('wp_insert_attachment_data', $attachment, $file, $post_id);

          $attach_id = wp_insert_attachment($attachment, $file, $post_id);

          if (!is_wp_error($attach_id)) {
              require_once ABSPATH . 'wp-admin/includes/image.php';
              $attach_data = wp_generate_attachment_metadata($attach_id, $file);
              wp_update_attachment_metadata($attach_id, $attach_data);

              return $attach_id;
          } else {
              // If there's an error in attachment insertion, delete the file
              unlink($file);
          }
      }
  }

  return false;
}






function autowp_upload_image_to_media($image_url) {
  require_once(ABSPATH . 'wp-admin/includes/image.php');
  require_once(ABSPATH . 'wp-admin/includes/file.php');
  require_once(ABSPATH . 'wp-admin/includes/media.php');

  // Using WordPress HTTP API to get image data
  $response = wp_safe_remote_get($image_url);

  if (is_wp_error($response)) {
    return false;
  }

  $image_data = wp_remote_retrieve_body($response);

  // Get the file name and extension
  $file_name = basename($image_url);
  $file_array = wp_upload_bits($file_name, null, $image_data);

  // Check for errors during upload
  if ($file_array['error']) {
    return false;
  }

  // Create the attachment post
  $attachment = array(
    'post_mime_type' => $file_array['type'],
    'post_title' => sanitize_file_name($file_name),
    'post_content' => '',
    'post_status' => 'inherit',
  );

  // Insert the attachment into the media library
  $attachment_id = wp_insert_attachment($attachment, $file_array['file']);

  // Generate the metadata for the attachment
  $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_array['file']);

  // Update the attachment metadata
  wp_update_attachment_metadata($attachment_id, $attachment_data);

  // Return the upload URL of the image
  return wp_get_attachment_url($attachment_id);
}



function autowp_upload_and_replace_image_sources($html) {
  $dom = new DOMDocument();
  $dom->encoding = 'UTF-8';
  libxml_use_internal_errors(true);
  $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
  libxml_clear_errors();

  $images = $dom->getElementsByTagName('img');
  foreach ($images as $image) {
      $src = $image->getAttribute('src');
      $new_image_url = autowp_upload_image_to_media($src);
      if ($new_image_url) {
          $image->setAttribute('src', $new_image_url);
      }
  }

  return $dom->saveHTML();
}

function get_excerpt_from_content($content) {
  // HTML etiketlerini kaldır
  $plain_text_content = strip_tags($content);

  // HTML özel karakterlerini dönüştür
  $plain_text_content = html_entity_decode($plain_text_content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

  // Metni cümlelere böl ('.', '!' veya '?' sonrası boşluk karakteriyle ayır)
  $sentences = preg_split('/(\.|\!|\?)\s+/', $plain_text_content, 3, PREG_SPLIT_DELIM_CAPTURE);

  // İlk iki cümleyi birleştir
  $excerpt = isset($sentences[0]) ? $sentences[0] : '';
  $excerpt .= isset($sentences[1]) ? $sentences[1] : '';
  $excerpt .= isset($sentences[2]) ? $sentences[2] : '';
  $excerpt .= isset($sentences[3]) ? $sentences[3] : '';

  return trim($excerpt);
}

function autowp_apply_linking_to_content($post_content) {
  // Linkleme ayarlarını veritabanından çek
  $linking_settings = get_option('autowp_linking_settings', []);

  // Eğer hiç kayıtlı linkleme ayarı yoksa, içeriği değiştirmeden döndür
  if (empty($linking_settings) || !is_array($linking_settings)) {
      return $post_content;
  }

  // Kullanıcının eklediği tüm anahtar kelimeleri organize etmek için bir dizi oluştur
  $keyword_settings = [];

  foreach ($linking_settings as $setting) {
      $keyword = $setting['keyword'];
      $link = $setting['link'];
      $html_tag = $setting['html_tag'];
      $link_count = $setting['link_count'];

      

      // Eğer anahtar kelime daha önce eklenmişse, mevcut dizinin içine ekle
      if (!isset($keyword_settings[$keyword])) {
          $keyword_settings[$keyword] = [];
      }

      // Kullanıcının eklediği tüm HTML etiketlerini aynı kelime için sakla
      $keyword_settings[$keyword][] = [
          'link' => $link,
          'html_tag' => $html_tag,
          'link_count' => $link_count
      ];
  }

  // İçeriği değiştirmek için işlemi başlat
  foreach ($keyword_settings as $keyword => $settings) {
      // Eğer içerikte bu anahtar kelime yoksa, devam et
      if (stripos($post_content, $keyword) === false) {
          continue;
      }

      // Bu kelime için kaç tane değişiklik yapıldığını takip eden sayaç
      $count = 0;

      // Regex kullanarak kelimeyi değiştir
      $post_content = preg_replace_callback(
          '/\b' . preg_quote($keyword, '/') . '\b/i',
          function ($matches) use ($settings, &$count) {
              $original_keyword = $matches[0]; // Orijinal metin içinde nasıl geçtiyse onu al

              // Kullanıcının seçtiği tüm etiketleri uygula
              $modified_keyword = $original_keyword;
              foreach ($settings as $setting) {
                  $html_tag = $setting['html_tag'];
                  $link = $setting['link'];
                  $link_count = $setting['link_count'];

                  // Eğer "a" etiketi seçildiyse, SEO uyumlu link ekle
                  if ($html_tag === "a") {
                      if ($count < intval($link_count) || $link_count === "all") {
                          $modified_keyword = "<a href=\"$link\" target=\"_blank\" rel=\"noopener noreferrer\" title=\"$original_keyword\">$modified_keyword</a>";
                          
                      }
                  } else {
                      // Link değilse sadece belirlenen etiketi uygula
                      if ($count < intval($link_count) || $link_count === "all") {
                        $modified_keyword = "<$html_tag>$modified_keyword</$html_tag>";
                       
                      }
                      
                  }
                  $count++;
              }

              return $modified_keyword;
          },
          $post_content
      );
  }

  return $post_content;
}



function autowp_set_new_post($post_title, $post_content, $post_status, $post_author, $post_type, $featured_image_url, $post_category, $post_tags, $focus_keyword,$faq_schema) {

  $post_content = autowp_upload_and_replace_image_sources($post_content);

  $post_content = autowp_apply_linking_to_content($post_content);

  //Set SEO 
    //focus keyword
    $title_explode = explode(" ", $post_title);
   // $focus_keyword =  //$title_explode[0] . " " . $title_explode[1];
    
    //Meta desc
    $meta_desc = substr(strip_tags($post_content), 0, 155);

  $meta_input = ["_yoast_wpseo_title" => $post_title, "_yoast_wpseo_metadesc" => $meta_desc, "_yoast_wpseo_focuskw" => $focus_keyword, "rank_math_title" => $post_title, "rank_math_description" => $meta_desc, "rank_math_focus_keyword" => $focus_keyword, "_faq_schema" => $faq_schema];



  $post = array(
    'post_title'    => $post_title,
    'post_content'  => $post_content,
    'post_status'   => $post_status, // "publish" olarak ayarla
    'post_author'   => $post_author, // 1 olarak ayarla
    'post_type'     => $post_type, // "post" olarak ayarla
    'post_category' => $post_category, //category array
    'tags_input'    => $post_tags, //tags
    'meta_input'    => $meta_input, //meta inputs
    'post_excerpt'         => get_excerpt_from_content($post_content)
    
  );



  $new_post_id = wp_insert_post($post);

  if (!empty($featured_image_url)) {
    $image_id = autowp_set_featured_image($featured_image_url, $new_post_id);
    if ($image_id !== false) {
      set_post_thumbnail($new_post_id, $image_id);
    }
  }

  return $new_post_id; // Fonksiyonun sonunda bu satırı ekleyin

}






function autowp_wpcron_setAutoPosting(){

  $settings = unserialize(get_option('autowp_settings'));

  $wpcron_status = $settings['wpcron_status'];

  if($wpcron_status === '1'){
    autowp_wordpress_post();
  }

}

add_filter('autowp_cron','autowp_wpcron_setAutoPosting');

function autowp_get_wp_autowp_wordpress_websites() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'autowp_wordpress_websites';
  $sql = "SELECT * FROM $table_name";
  $results = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);
  return $results;
}



function autowp_stringToArray($input) {
  $numbers = explode(",", $input);
  $result = array();

  foreach ($numbers as $number) {
      $result[] = (int)$number;
  }

  return $result;
}


function autowp_update_published_post_ids($new_post_ids) {
  $autowp_settings = get_option('autowp_post_settings');
  $autowp_settings = unserialize($autowp_settings);
  
  // Update the published_post_ids value
  $autowp_settings['published_post_ids'] = $new_post_ids;
  
  // Serialize and update the option value
  update_option('autowp_post_settings', serialize($autowp_settings));
}

function autowp_update_wp_cron_status($new_status) {
  $autowp_settings = get_option('autowp_settings');
  $autowp_settings = unserialize($autowp_settings);
  
  // Update the published_post_ids value
  $autowp_settings['wpcron_status'] = $new_status;
  
  // Serialize and update the option value
  update_option('autowp_settings', serialize($autowp_settings));
}

add_action('admin_notices', 'autowp_create_alert');

function autowp_create_alert() {
  $alerts = get_option('autowp_alerts');
  $settings = get_option('autowp_settings');
  $settings = $settings ? unserialize($settings) : [];

  $announcements = get_option('autowp_announcements'); // Duyuruları opsiyondan al
  $dismissed_announcements = get_option('autowp_dismissed_announcements', []); // Kullanıcı tarafından gizlenmiş duyuruları al

  // Eğer dismissed_announcements bir string ise, diziye çevir
  if (!is_array($dismissed_announcements)) {
      $dismissed_announcements = [];
  }

  // Eğer alert veya duyurular varsa
  if (!empty($alerts) || !empty($announcements)) {
      echo '
      <div class="notice notice-info is-dismissible">
          <p><strong>AutoWP Announcement:</strong></p>';
      
      // Eğer alerts varsa göster
      if (!empty($alerts)) {
          echo '<p>' . esc_html($alerts) . '</p>';
      }

      // Eğer duyurular varsa, her birini ayrı bir satırda göster
      if (!empty($announcements)) {
          foreach ($announcements as $announcement_id => $announcement) {
              // Eğer bu duyuru gizlenmediyse göster
              if (!in_array($announcement_id, $dismissed_announcements)) {
                  echo '<p>' . esc_html($announcement['title']) . ': ' . wp_kses_post($announcement['message']) . '</p>';
                  echo '<button data-announcement-id="' . esc_attr($announcement_id) . '" class="button dismiss-announcement">Dismiss</button>';
              }
          }
      }
      
      echo '<p><a href="admin.php?page=autowp_menu" class="button button-primary">Upgrade Membership</a></p>
      </div>';

      // Bu butonlar için JavaScript ekle (AJAX ile belirli bir duyuruyu gizle)
      echo '
      <script type="text/javascript">
          jQuery(document).on("click", ".dismiss-announcement", function() {
              var announcement_id = jQuery(this).data("announcement-id");

              var data = {
                  action: "autowp_dismiss_announcement",
                  security: "' . esc_js(wp_create_nonce("autowp_dismiss_nonce")) . '",
                  announcement_id: announcement_id
              };

              jQuery.post(ajaxurl, data, function(response) {
                  if(response.success) {
                      location.reload(); // Sayfayı yeniden yükle
                  }
              });
          });
      </script>';
  }

  // İkinci alert: Setup kontrolü
  if (empty($settings['api_email']) || empty($settings['api_key'])) {
      echo '
      <div class="notice notice-error is-dismissible">
          <p><strong>AutoWP Setup Required</strong></p>
          <p>AutoWP is not set up properly. To use this plugin, you need to complete the setup process.</p>
          <p>If you do not complete the setup, AutoWP will not work correctly.</p>
          <p><a href="admin.php?page=autowp-setup" class="button button-primary">Go to Setup</a></p>
      </div>';
  }
}

// AJAX isteğini ele alan fonksiyon
function autowp_dismiss_announcement() {
  check_ajax_referer('autowp_dismiss_nonce', 'security');
  
  if (isset($_POST['announcement_id'])) {
      $announcement_id = sanitize_text_field($_POST['announcement_id']);

      // Daha önce dismissed duyuruları al
      $dismissed_announcements = get_option('autowp_dismissed_announcements', []);

      // Eğer dismissed_announcements bir string ise, diziye çevir
      if (!is_array($dismissed_announcements)) {
          $dismissed_announcements = [];
      }
      
      // Yeni dismissed duyuruyu ekle
      if (!in_array($announcement_id, $dismissed_announcements)) {
          $dismissed_announcements[] = $announcement_id;
          update_option('autowp_dismissed_announcements', $dismissed_announcements);
      }

      wp_send_json_success();
  } else {
      wp_send_json_error();
  }
}
add_action('wp_ajax_autowp_dismiss_announcement', 'autowp_dismiss_announcement');



// Eklenti etkinleştirildiğinde çalışan fonksiyon
function autowp_announcements_activation() {
  if ( ! wp_next_scheduled( 'autowp_fetch_announcements' ) ) {
      wp_schedule_event( time(), 'ten_minutes', 'autowp_fetch_announcements' );
  }
}
register_activation_hook( __FILE__, 'autowp_announcements_activation' );

// Eklenti devre dışı bırakıldığında çalışan fonksiyon
function autowp_announcements_deactivation() {
  wp_clear_scheduled_hook( 'autowp_fetch_announcements' );
}
register_deactivation_hook( __FILE__, 'autowp_announcements_deactivation' );

// Zamanlanmış olayları (interval) ekle
function autowp_custom_intervals( $schedules ) {
  $schedules['ten_minutes'] = array(
      'interval' => 600, // 600 saniye = 10 dakika
      'display'  => __( 'Every 10 Minutes' ),
  );
  return $schedules;
}
add_filter( 'cron_schedules', 'autowp_custom_intervals' );

// API'den duyuruları çekme fonksiyonu
function autowp_fetch_announcements() {
  // autowp_settings opsiyonunu al ve çöz
  $settings_option = get_option( 'autowp_settings' );
  $server_url = unserialize(get_option("autowp_settings"))["autowp_server_url"];

  
  if ( empty( $settings_option ) ) {
      return; // Ayar yoksa işlemi durdur
  }

  // Ayarlardan api_key değerini al
  $settings = maybe_unserialize( $settings_option );
  $api_key = isset( $settings['api_key'] ) ? $settings['api_key'] : '';

  $api_url = 'https://api.autowp.app/announcements';

     
  

  if ( isset( $server_url ) 
     && $server_url !== null 
     && $server_url !== '' 
     && strpos( $server_url, 'autowp.app' ) === false
  ) {
    
    $api_url = $server_url . '/announcements';

  }


  // API isteğini api_key varsa ona göre yapılandır
  $request_url = ! empty( $api_key ) ? add_query_arg( 'api_key', $api_key, $api_url ) : $api_url;

  // API'ye istek yap
  $response = wp_remote_get( $request_url, array(
      'timeout'   => 15,
      'sslverify' => false,
      'headers'   => array(
          'Content-Type' => 'application/json',
          'Accept'       => 'application/json',
      )
  ) );

  if ( is_wp_error( $response ) ) {
      return; // Hata varsa işlemi durdur
  }

  $body = wp_remote_retrieve_body( $response );
  $announcements = json_decode( $body, true );

  if (  is_array( $announcements ) ) {
      // Duyuruları WordPress opsiyonuna kaydet
      update_option( 'autowp_announcements', $announcements );
  }
}

add_action( 'autowp_fetch_announcements', 'autowp_fetch_announcements' );

function autowp_get_user_email_from_settings() {
  // autowp_settings opsiyonunu alın
  $autowp_settings = get_option('autowp_settings');
  $autowp_settings = $autowp_settings ? unserialize($autowp_settings) : [];

  // Ayarlardan email'i çek
  $user_email = isset($autowp_settings['api_email']) ? sanitize_email($autowp_settings['api_email']) : '';

  return $user_email;
}


function autowp_wordpress_post(){
  $wordpress_websites = autowp_get_wp_autowp_wordpress_websites();
  // Randomize the order of the array
  shuffle($wordpress_websites);
  $counter = 0;
  $max_posts_per_cron = absint(unserialize(get_option('autowp_settings'))['max_posts_per_cron'] ?? 1);
  
  foreach($wordpress_websites as $wordpress_website){

    if ($counter >= $max_posts_per_cron) {
      break;
    }

    if ($wordpress_website['active'] === '0'){
      continue;
    }




    $user_domainname = esc_url(get_site_url());
    $user_email = autowp_get_user_email_from_settings();
    $website_domainname = sanitize_url($wordpress_website['domain_name']);
    $website_categories = $wordpress_website['website_category_id'];
    $wordpress_categories = $wordpress_website['category_id'];


    $post_count = $wordpress_website['post_count'];
    $post_order = $wordpress_website['post_order'];

    $title_prompt = $wordpress_website['title_prompt'];
    $content_prompt = $wordpress_website['content_prompt'];
    $tags_prompt = $wordpress_website['tags_prompt'];
    $image_prompt = $wordpress_website['image_prompt'];

    $aigenerated_title = 1;
    $aigenerated_content = 1;
    $aigenerated_tags = 1;
    $aigenerated_image = 1;

    $source_type = $wordpress_website['website_type'];

    $image_generating_status = $wordpress_website['image_generating_status'];

    $author_selection = $wordpress_website['author_selection'];

    //News

    $news_keyword = $wordpress_website['news_keyword'];
    $news_country = $wordpress_website['news_country'];
    $news_language = $wordpress_website['news_language'];
    $news_time_published = $wordpress_website['news_time_published'];
    $is_html = true;


  

    $image_settings = unserialize(get_option('autowp_settings'));
    $image_settings_json = [];

    if(!empty($image_settings)){
      $image_settings_json = json_encode($image_settings);
    }

    $prompts_option = get_option('autowp_rewriting_promptscheme');
    $prompt_option_str = strval($prompts_option);
    $rewriting_prompt_scheme = [];
    
    if (!empty($prompts_option)) {
        $rewriting_prompt_scheme = json_encode($prompts_option);
        
        // JSON'dan diziye dönüşüm yapılıyor ve true ile birlikte kullanıldığı için asosiyatif dizi elde ediliyor
    }



    
    

    $get_data_from_api = autowp_get_posts_from_wp_website($user_domainname, $user_email, $website_domainname, $website_categories, $post_count,$post_order,'',$title_prompt,$content_prompt,$tags_prompt,$image_prompt,$aigenerated_title,$aigenerated_content,$aigenerated_tags,$aigenerated_image,$image_settings_json,$source_type,$image_generating_status,$news_keyword,$news_country,$news_language,$news_time_published,$is_html,$prompt_option_str);
    sleep(rand(2, 3)); // 2-3 saniye bekleme süresi eklendi
    $wp_posts = $get_data_from_api['autowp-api'];

    if($get_data_from_api['error']){
      update_option('autowp_alerts', $get_data_from_api['error']);
      continue;
    }else{
      update_option('autowp_alerts', '');
    }

    

    

    foreach($wp_posts as $post){
      $post_title = $post['post_title']; 
      $post_content = $post['content'];
      $post_featured_image = $post['preview_image_original'];
      $post_tags = $post['tags'];
      $post_status = $image_settings['post_status'] ?? 'publish';
      $post_author = $author_selection;
      $post_type = 'post';
      $focus_keyword = $post['focus_keyword'];
      $faq_schema = $post['faq_schema'];



      
      $new_post_id = autowp_set_new_post($post_title,$post_content,$post_status,$post_author,$post_type,$post_featured_image, autowp_stringToArray($wordpress_categories),$post_tags,$focus_keyword,$faq_schema);
      $counter++;

     
    }

   

    
    
  }
}

function autowp_get_posts_from_wp_website($user_domainname, $user_email, $website_domainname, $website_categories, $post_count,$post_order,$post_ids,$title_prompt,$content_prompt,$tags_prompt,$image_prompt,$aigenerated_title,$aigenerated_content,$aigenerated_tags,$aigenerated_image,$image_settings,$source_type,$image_generating_status,$news_keyword = '',$news_country = '',$news_language = '',$news_time_published = '',$is_html = false,$rewriting_prompt_scheme = null) {


  $prompts_option = get_option('autowp_rewriting_promptscheme');

  if (!is_array($prompts_option)) {
    $prompts_option = json_decode($prompts_option, true);
  }

  $prompt_option_string = json_encode($prompts_option);

  $server_url = unserialize(get_option("autowp_settings"))["autowp_server_url"];



    
  $url = 'https://api.autowp.app/latest-posts';

  if ( isset( $server_url ) 
     && $server_url !== null 
     && $server_url !== '' 
     && strpos( $server_url, 'autowp.app' ) === false
  ) {
    
    $url = $server_url . '/latest-posts';

  }

  

  
  $data = array(
      'user_domainname'         => $user_domainname,
      'user_email'              => $user_email,
      'website_domainname'      => $website_domainname,
      'website_categories'      => $website_categories,
      'post_count'              => 5,
      'post_order'              => $post_order,
      'published_post_ids'      => $post_ids,
      
      'title_prompt'            => $title_prompt,
      'content_prompt'          => $content_prompt,
      'tags_prompt'             => $tags_prompt,
      'image_prompt'            => $image_prompt,


      'aigenerated_title'       => $aigenerated_title,
      'aigenerated_content'     => $aigenerated_content,
      'aigenerated_tags'        => $aigenerated_tags,
      'aigenerated_image'       => $aigenerated_image,


      'image_settings'          => $image_settings,


      'source_type'             => $source_type,

      'image_generating_status' => $image_generating_status,

      'news_keyword'            => $news_keyword,
      'news_country'            => $news_country,
      'news_language'           => $news_language,
      'news_time_published'     => $news_time_published,
      'is_html'                 => $is_html,

      'rewriting_prompt_scheme' => $prompt_option_string 
  );

  
  
  $response = wp_remote_post($url, array(
   
    'timeout' => 2400, // Timeout set to 4 minutes.
      
      'body' => $data,
      
  ));

  update_option('autowp_alerts', strval($response));
  
  if (is_wp_error($response)) {
    $error_message = wp_remote_retrieve_response_message($response);
    return 'Error: ' . $error_message;
}

  
  $body = wp_remote_retrieve_body($response);

  
  $result = json_decode($body, true);
  
  
  
  return $result;
}


function autowp_set_wpcron(){
  // Current settings
  $current_settings = unserialize(get_option('autowp_settings'));

  // Received data from user
  $received_data = array(
    "selected_time_type" => '1',
    "wpcron_status" => '1',
    "watermark_link" => '',
    "nano_banana_prompt" => '',
    "image_modification_status" => '0',
    "image_generating_status" => '0',
    "ai_image_width" =>  0,
    "ai_image_height" => 0,
    "stable_diffusion_style" => 'None',
    // New settings for flux, stable diffusion size, DALL-E 2, DALL-E 3 sizes
    "flux_image_size" => 'landscape_16_9',               // Default value for flux image size
    "stable_diffusion_size" => '16:9',               // Default value for stable diffusion size
    "dalle_2_size" => '1024x1024',                    // Default value for DALL-E 2 size
    "dalle_3_size" => '1024x1024',                   // Default value for DALL-E 3 size
    "dalle_3_style" => 'natural',
    "image_format" => "png",
     // New default values
     "max_posts_per_cron" => 1,              // Default value for maximum posts per cron
     "max_posts_per_day" => 20,              // Default value for maximum posts per day
     "spam_ad_filter" => '0',                // Default value for spam and ad filter (passive)
     "duplicate_content_filter" => '1',      // Default value for duplicate content filter (active)
     "primary_llm" => 'openai',
     "secondary_llm" => 'xai',
     "default_image_url" => "https://gorsel.autowp.app/en/en/1.png",
     "autowp_server_url" => 'https://api.autowp.app',

  );

  if(empty($current_settings)){

     // Serialize and update options
    update_option('autowp_settings', serialize($received_data), "yes");

  }

  $time_value_type = sanitize_text_field('2');

  $user_wpcron_time = autowp_get_wpcron_time($time_value_type);
 

  // Schedule WP-Cron
  if (!wp_next_scheduled('autowp_cron')) {
    wp_schedule_event(time(), $user_wpcron_time, 'autowp_cron');
  } else {
    wp_clear_scheduled_hook('autowp_cron');
    wp_schedule_event(time(), $user_wpcron_time, 'autowp_cron');
  }
}


function update_autowp_promptscheme_option() {
  // Define the data as an array
  $data = [
    [
      'name' => 'AutoWP Introduction',
      'detailed_prompt' => 'autowp-introduction',
      'max_tokens' => 300000,
    ],
    [
      'name' => 'AutoWP Subheadings',
      'detailed_prompt' => 'autowp-subheadings',
      'max_tokens' => 300000,
    ],
    [
      'name' => 'AutoWP FAQ',
      'detailed_prompt' => 'autowp-faq',
      'max_tokens' => 300000,
    ],
    [
      'name' => 'AutoWP Results',
      'detailed_prompt' => 'autowp-result',
      'max_tokens' => 300000,
    ]
  ];

  // Serialize the array
  $serialized_data = serialize($data);

  // Check if the option is empty before updating
  if (empty(get_option('autowp_rewriting_promptscheme'))) {
    update_option('autowp_rewriting_promptscheme', $serialized_data);
  }
}

register_activation_hook(__FILE__, 'update_autowp_promptscheme_option');






register_activation_hook(__FILE__, 'autowp_set_wpcron');

function autowp_unset_wpcron(){

  // find out when the last event was scheduled
	$timestamp = wp_next_scheduled ('autowp_cron');
	// unschedule previous event if any
	wp_unschedule_event ($timestamp, 'autowp_cron');

}

register_deactivation_hook(__FILE__,'autowp_unset_wpcron');

//WP-CRON END

// Dil desteği için 'autowp' önekini kullanarak metinleri çevirelim



function autowp_get_user_from_autowp_api() {

  $server_url = unserialize(get_option("autowp_settings"))["autowp_server_url"];


  // API URL'si
  $url = 'https://api.autowp.app/getUserByDomain';



     
  

  if ( isset( $server_url ) 
     && $server_url !== null 
     && $server_url !== '' 
     && strpos( $server_url, 'autowp.app' ) === false
  ) {
    
    $url = $server_url . '/getUserByDomain';

  }
  
  // Setup kısmında kaydedilen ayarları al
  $autowp_settings = get_option('autowp_settings');
  $autowp_settings = $autowp_settings ? unserialize($autowp_settings) : [];

  // autowp_settings içinden user_email ve api_key'i al
  $user_email = isset($autowp_settings['api_email']) ? sanitize_email($autowp_settings['api_email']) : 'email@example.com';
  $api_key = isset($autowp_settings['api_key']) ? sanitize_text_field($autowp_settings['api_key']) : '';

  // API'ye gönderilecek veriler
  $args = array(
      'body' => array(
          'user_domainname' => esc_url(get_site_url()),
          'user_email' => $user_email,
          'api_key' => $api_key
      )
  );

  // API çağrısı
  $response = wp_remote_post($url, $args);
  
  // Yanıtı JSON olarak ayrıştır
  $json = json_decode(wp_remote_retrieve_body($response), true);

  return $json;
}

function autowp_generalSettings(){  
  ?>
  
  <!-- Logo Eklemesi -->
  
  <form method="post" class="form-horizontal">
  <fieldset>
  
  <?php 
  $user = autowp_get_user_from_autowp_api();
  $isUserPremium = $user['product_name'] ?? 'Free Users';
  
  // Eğer kullanıcının AIContentGenerator kredisi ve premium üyeliği yoksa
  if ($isUserPremium == 'Free User') {
    echo '<div class="alert alert-warning" role="alert">' .
      esc_html__('You are not a premium user so you have limited balance. If you want to generate more posts or image, you should upgrade to premium membership.','autowp') . ' ' .
    '</div>';
  }
  ?>
  
  <div class="form-group">
    <div class="card text-center">
      <div class="card-body">
        <h4 class="card-title"><img src="<?php echo esc_url(plugins_url( '/assets/images/logo128.png', __FILE__ )) ?>" alt="AutoWP" style="height: 100px; width: 100px;"></h4>
        <p class="card-text"> <?php echo esc_html__('Package Name:', 'autowp'); ?> <?php echo esc_html($isUserPremium); ?></p>
        <p class="card-text"> <?php echo esc_html__('Renewal Date:', 'autowp'); ?> <?php echo esc_html($user['renewal_date'] ?? esc_html__('NO RENEWAL DATE', 'autowp')); ?></p>
        <p class="card-text"> <?php echo esc_html__('AI-Generated Post Balance:', 'autowp'); ?> <?php echo esc_html($user['aigenerated_post_balance']) . ' ' . esc_html__('Posts', 'autowp'); ?></p>
        <p class="card-text"> <?php echo esc_html__('AI-Generated Image Balance:', 'autowp'); ?> <?php echo esc_html($user['aigenerated_image_balance']) . ' ' . esc_html__('Images', 'autowp'); ?></p>
        <br>
        <a href="https://api.whatsapp.com/send/?phone=447384097397" class="btn btn-primary"><?php echo esc_html__('Contact Us', 'autowp'); ?></a>
        <a href="https://billing.stripe.com/p/login/00g6p317J8cZ07u3cc" class="btn btn-primary"><?php echo esc_html__('Manage Subscription', 'autowp'); ?></a>
      </div>
    </div>
  </div>


  
  <br>


  <!-- Yeni Card: "R10 Özel Fiyatlar için Tıklayın!" -->


  
  <?php

  $request_url = 'https://api.autowp.app/getPackages';

  if ( get_locale() === 'tr_TR' ) {
    $request_url = 'https://api.autowp.app/getPackages?is_turkish=True';
  }
  $response = wp_remote_get($request_url);

  


  
  if (is_wp_error($response)) {
    echo '<div class="alert alert-danger" role="alert">' .
      esc_html__('An error occurred while fetching packages. Please try again later.', 'autowp') . ' ' .
    '</div>';
  } else {
    $packages = json_decode(wp_remote_retrieve_body($response), true);
  
    // Unlimited paketleri önce sıralamak için düzenleme
    usort($packages, function($a, $b) {
        return ($b['is_unlimited'] ?? false) - ($a['is_unlimited'] ?? false);
    });
    
    $hasAnnual = false;
    $hasMonthly = false;
  
    foreach ($packages as $package) {
      if ($package['is_annual']) {
        $hasAnnual = true;
      } else {
        $hasMonthly = true;
      }
    }

    
  
    if ($hasMonthly && $hasAnnual) {
      echo '<center><div class="btn-group" role="group" aria-label="Package Options">';
  
      if ($hasMonthly) {
        echo '<button type="button" class="btn btn-primary active" id="monthly-tab" onclick="filterPackages(\'monthly\')">' . esc_html__('Monthly', 'autowp') . '</button>';
      }
  
      if ($hasAnnual) {
        echo '<button type="button" class="btn btn-secondary" id="annual-tab" onclick="filterPackages(\'annual\')">' . esc_html__('Annual', 'autowp') . '</button>';
      }
  
      echo '</div><center>';
    }

    if ( get_locale() === 'tr_TR' ) {
      echo '<div class="card text-center" style="width: 100%; margin-bottom: 20px;">
          <div class="card-body">
              <h4 class="card-title" style="font-weight: bold; color: #FF6347;">' . esc_html__('TÜRKÇE DESTEK HATTI VE ÖZEL İNDİRİMLER İÇİN TIKLAYIN!', 'autowp') . '</h4>
              <a href="https://api.whatsapp.com/send/?phone=447384097397" class="btn btn-warning">' . esc_html__('Tıklayın', 'autowp') . '</a>
          </div>
      </div>';
  }

  
    echo '<div id="packagesContainer">';
  
    if (!empty($packages)) {
      foreach ($packages as $package) {
        $isUnlimited = !empty($package['is_unlimited']) && $package['is_unlimited'];
        $isAnnualClass = $package['is_annual'] ? 'annual' : 'monthly';

        $currency = '£';

        if ( get_locale() === 'tr_TR' ) {
          $currency = 'TL';
        }
    
        if ($isUnlimited) {
          ?>
          <div class="columns package unlimited <?php echo esc_attr($isAnnualClass); ?>">
            <ul class="price special-unlimited">
              <li class="header" style="background-color: #FFD700; color: #000;"><?php echo esc_html(ucwords($package['name'])); ?></li>
              <li class="grey">
    <?php echo esc_html($currency); ?>
    <?php echo esc_html(number_format($package['package_price'], 2)); ?> /
    <?php echo $package['is_annual'] ? esc_html__('Year', 'autowp') : esc_html__('Month', 'autowp'); ?>
</li>

              <li><?php echo esc_html__('Unlimited AI-Generated Posts', 'autowp'); ?></li>
              <li><?php echo esc_html__('Unlimited AI-Generated Images', 'autowp'); ?></li>
              <li>
  <?php 
  echo $package['max_website'] == 0 
    ? esc_html__('Unlimited Websites', 'autowp') 
    : esc_html($package['max_website']) . ' ' . esc_html__('max website', 'autowp'); 
  ?>
</li>

              <li><?php echo esc_html__('Auto Indexing', 'autowp'); ?></li>
              <li><?php echo esc_html__('Social Media Sharing', 'autowp'); ?></li>
              <li><?php echo esc_html__('Auto Image Editing', 'autowp'); ?></li>
              <li><?php echo esc_html__('Use Your Own API Keys', 'autowp'); ?></li>
              <li class="grey"><a href="https://api.autowp.app/v2/subscribe?package_id=<?php echo esc_attr($package['id']); ?>&user_id=<?php echo esc_attr($user['user_id']); ?>" class="button"><?php echo esc_html__('Sign Up', 'autowp'); ?></a></li>
            </ul>
          </div>
          <?php
      }
       else {
            ?>
            <div class="columns package <?php echo esc_attr($isAnnualClass); ?>" style="<?php echo $package['is_annual'] ? 'display: none;' : ''; ?>">
              <ul class="price">
                <li class="header"><?php echo esc_html(ucwords($package['name'])); ?></li>
                <li class="grey">
    <?php echo esc_html($currency); ?>
    <?php echo esc_html(number_format($package['package_price'], 2)); ?> /
    <?php echo $package['is_annual'] ? esc_html__('Year', 'autowp') : esc_html__('Month', 'autowp'); ?>
</li>

                <li><?php echo esc_html($package['max_ai_generated_post_per_month']) . ' ' . esc_html__('AI-Generated Post per Month', 'autowp'); ?></li>
                <li><?php echo esc_html($package['max_ai_generated_image_per_month']) . ' ' . esc_html__('AI-Generated Image per Month', 'autowp'); ?></li>
                <li><?php echo esc_html__('Unlimited Websites', 'autowp'); ?></li>
                <li><?php echo esc_html__('Auto Indexing', 'autowp'); ?></li>
                <li><?php echo esc_html__('Social Media Sharing', 'autowp'); ?></li>
                <li><?php echo esc_html__('Auto Image Editing', 'autowp'); ?></li>
                <li class="grey"><a href="https://api.autowp.app/v2/subscribe?package_id=<?php echo esc_attr($package['id']); ?>&user_id=<?php echo esc_attr($user['user_id']); ?>" class="button"><?php echo esc_html__('Sign Up', 'autowp'); ?></a></li>
              </ul>
            </div>
            <?php
        }
    }
    
    } else {
      echo '<div class="alert alert-info" role="alert">' .
        esc_html__('No packages available at the moment. Please check back later.', 'autowp') . ' ' .
      '</div>';
    }
  
    echo '</div>';
  }
  ?>
  
  </form>
  
  
  <?php
}

    
    

// Loading WP_List_Table class file
// We need to load it as it's not automatically loaded by WordPress
if (!class_exists('WP_List_Table')) {
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}






function autowp_create_table() {
  global $wpdb;
    $table_name = $wpdb->prefix . 'autowp_wordpress_websites';

    // Check if the table already exists
    $prepared_query = $wpdb->prepare("SHOW TABLES LIKE %s", $table_name);
    if ($wpdb->get_var($prepared_query) === $table_name) {
      return;
    }


    $charset_collate = $wpdb->get_charset_collate();

    // Define the table structure
    $sql = "CREATE TABLE $table_name (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `website_name` varchar(255) NOT NULL,
      `website_type` text NOT NULL,
      `domain_name` varchar(255) NOT NULL,
      `category_id` text NOT NULL,
      `website_category_id` text NOT NULL,
      `aigenerated_title` text NOT NULL,
      `aigenerated_content` text NOT NULL,
      `aigenerated_tags` text NOT NULL,
      `aigenerated_image` text NOT NULL,
      `post_count` text NOT NULL,
      `post_order` text NOT NULL,
      `title_prompt` text NOT NULL,
      `content_prompt` text NOT NULL,
      `tags_prompt` text NOT NULL,
      `image_prompt` text NOT NULL,
      `image_generating_status` text NOT NULL,
      `author_selection` text NOT NULL,


      `news_time_published` text NOT NULL,
      `news_language` text NOT NULL,
      `news_country` text NOT NULL,
      `news_keyword` text NOT NULL,

      PRIMARY KEY (`id`)
      );";


    // Include the necessary file for dbDelta()
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // Create the table
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'autowp_create_table');



function autowp_create_table_or_update() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'autowp_wordpress_websites';

  // Tablo var mı kontrol et
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
      return; // Eğer tablo yoksa, değişiklik yapma
  }

  // "active" sütunu var mı kontrol et?
  $column_exists = $wpdb->get_var("SELECT COUNT(*) 
                                   FROM INFORMATION_SCHEMA.COLUMNS 
                                   WHERE TABLE_NAME = '$table_name' 
                                   AND COLUMN_NAME = 'active'");
  
  if (!$column_exists) {
      // Eğer "active" sütunu yoksa, ekleyelim.
      $wpdb->query("ALTER TABLE $table_name ADD COLUMN active TINYINT(1) NOT NULL DEFAULT 1");
  }

}

// Güncellemeleri kontrol etmek için init hook'u kullan
function autowp_check_for_updates() {
  autowp_create_table_or_update();
}

add_action('init', 'autowp_check_for_updates');

register_activation_hook(__FILE__, 'autowp_check_for_updates');






// Plugin menu callback function
function autowp_automaticPost()
{
      // Creating an instance
      $table = new AutoWP_Wordpress_Websites();

      echo '<div class="wrap"><h2>SupportHost Admin Table</h2>';
      echo '<form method="post">';

      // Add nonce field
      wp_nonce_field('autowp_websites_menu_nonce', '_wpnonce');
      // Prepare table
      $table->prepare_items();
      // Search form
      $table->search_box('search', 'search_id');
      // Display table
      $table->display();
      echo '</div></form>';
}


// Extending class
class AutoWP_Wordpress_Websites extends WP_List_Table
{
    // Here we will add our code

    // Define table columns
    function get_columns()
    {
        $columns = array(
                
                'cb'            => '<input type="checkbox" />',
                'website_type'          => __('Website Type', 'autowp'),
                'website_name'          => __('Website Name', 'autowp'),
                'domain_name'         => __('Domain Name', 'autowp'),
                'category_id'   => __('Your Categories', 'autowp'),
                'website_category_id'        => __('Website Categories', 'autowp'),
                'active'        => __('Status', 'autowp'),

        );
        return $columns;
    }

    function column_active( $item ) {
      // Eğer değer 1 ise aktif, aksi durumda pasif
      if ( (int) $item['active'] !== 0 ) {
          // Dashicons kullanarak ikon ekleyelim: https://developer.wordpress.org/resource/dashicons/
          return '<span class="dashicons dashicons-yes" style="color:green;"></span> ' 
               . __('Active/Running', 'autowp');
      } else {
          return '<span class="dashicons dashicons-no-alt" style="color:red;"></span> '
               . __('Passive/Paused', 'autowp');
      }
  }


// define $table_data property
private $table_data;

    // Bind table with columns, data and all
    function prepare_items()
    {
        //data
        if ( isset( $_POST['s'] ) && isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'autowp_websites_menu_nonce' ) ) {
          $search_query = sanitize_text_field($_POST['s']);
          $this->table_data = $this->get_table_data($search_query);
        } else {
          $this->table_data = $this->get_table_data();
        }
      

        $columns = $this->get_columns();
        $hidden = ( is_array(get_user_meta( get_current_user_id(), 'managetoplevel_page_list_tablecolumnshidden', true)) ) ? get_user_meta( get_current_user_id(), 'managetoplevel_page_list_tablecolumnshidden', true) : array();
        $sortable = $this->get_sortable_columns();
        $primary  = 'name';
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);
        $this->process_bulk_action();
        $this->table_data = $this->get_table_data();

        usort($this->table_data, array($this, 'usort_reorder'));

        /* pagination */
        $per_page = $this->get_items_per_page('elements_per_page', 10);
        $current_page = $this->get_pagenum();
        $total_items = count($this->table_data);

        $this->table_data = array_slice($this->table_data, (($current_page - 1) * $per_page), $per_page);

        $this->set_pagination_args(array(
                'total_items' => $total_items, // total number of items
                'per_page'    => $per_page, // items to show on a page
                'total_pages' => ceil( $total_items / $per_page ) // use ceil to round up
        ));
        
        $this->items = $this->table_data;
    }

     

    function column_website_type($item) {
      // Website type bilgisini alıyoruz.
      $type = $item['website_type'];
  
      // Her website type için ikon URL’lerini tanımlayan dizi:
      $icons = array(
          'wordpress'      => plugins_url('assets/images/wordpress-icon.png', __FILE__),
          'rss'            => plugins_url('assets/images/rss-icon.png', __FILE__),
          'ai'             => plugins_url('assets/images/robot-icon.png', __FILE__),
          'news'           => plugins_url('assets/images/gnews.png', __FILE__),
          'agenticscraper' => plugins_url('assets/images/robot-icon.png', __FILE__),
      );
  
      // İlgili type için ikon URL'si; yoksa varsayılan ikon:
      $icon_url = isset($icons[$type]) ? $icons[$type] : plugins_url('assets/images/default-icon.png', __FILE__);
  
      // Orijinal fonksiyonda website type'a göre edit sayfa slug'ı alınıyordu:
      $website_page = autowp_get_page_slug_from_website_type($type);
      
      // Edit ve Delete linklerini oluşturuyoruz. URL'leri oluşturmak için admin_url() kullanıyoruz:
      $actions = array(
           'edit'   => sprintf(
               '<a href="%s?page=%s&id=%s">%s</a>',
               admin_url('admin.php'),
               $website_page,
               $item['id'],
               __('Edit', 'autowp')
           ),
           'delete' => sprintf(
               '<a href="%s?page=%s&action=delete&id=%s">%s</a>',
               admin_url('admin.php'),
               sanitize_text_field($_REQUEST['page']),
               $item['id'],
               __('Delete', 'autowp')
           ),
      );

      return sprintf(
        '<span style="display:inline-flex; align-items:center;">
            <img src="%s" alt="%s" style="width:40px; height:40px; margin-right:8px;">
            <strong>%s</strong>
        </span> %s',
        esc_url($icon_url),
        esc_attr($type),
        esc_html(strtoupper($type)), // İsterseniz burada büyük/küçük harf dönüşümü yapmadan doğrudan $type da kullanabilirsiniz.
        $this->row_actions($actions)
    );
  }
  
  

    
      

       // To show bulk action dropdown
    function get_bulk_actions()
    {
            $actions = array(
                    'delete_all'    => __('Delete', 'autowp'),
                    
            );
            return $actions;
    }

    function process_bulk_action()
{
    global $wpdb;

    $table = $wpdb->prefix . 'autowp_wordpress_websites';

    if ('delete_all' === $this->current_action() || ('delete' === $this->current_action() && isset($_REQUEST['id']))) {
        $request_id = isset($_REQUEST['id']) ? array_map('absint', (array) $_REQUEST['id']) : array();

        if (!empty($request_id)) {
            // Prepare the DELETE query with proper escaping
            $placeholders = implode(',', array_fill(0, count($request_id), '%d'));
            $query = $wpdb->prepare("DELETE FROM $table WHERE id IN($placeholders)", $request_id);

            // Execute the query
            $wpdb->query($query);
        }
    }
}

    

      // Get table data
      private function get_table_data( $search = '' ) {
        global $wpdb;
    
        $table = $wpdb->prefix."autowp_wordpress_websites";
        
    
        if ( ! empty( $search ) ) {
            $prepared_search = $wpdb->esc_like( $search );
            $prepared_search = '%' . $wpdb->esc_like( $search ) . '%';
    
            return $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM {$table} WHERE website_name LIKE %s OR domain_name LIKE %s OR category_id LIKE %s",
                    $prepared_search,
                    $prepared_search,
                    $prepared_search
                ),
                ARRAY_A
            );
        } else {
         
          return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$table}",
                $table
            ),
            ARRAY_A
        );
        
        }
    }
    
    function column_default($item, $column_name)
    {


          switch ($column_name) {
                case 'id':
                case 'website_type':             
                case 'website_name':                
                case 'domain_name':               
                case 'category_id':          
                case 'website_category_id':
                default:
                    return $item[$column_name];
          }
    }

    function column_cb($item){
        return sprintf(
                '<input type="checkbox" name="id[]" value="%s" />',
                $item['id']
        );
    }

    protected function get_sortable_columns(){
      $sortable_columns = array(
            'website_name'  => array('website_name', false),
            'domain_name' => array('website_name', false),
            'id'   => array('id', true)
      );
      return $sortable_columns;
    }

      // Sorting function
      function usort_reorder($a, $b)
      {
          // If no sort, default to user_login
          $sanitized_orderby = sanitize_text_field($_GET['orderby']);
          $orderby = (!empty($sanitized_orderby)) ? $sanitized_orderby : 'website_name';
  
          // If no order, default to asc
          $sanitized_get_id = sanitize_text_field($_GET['id']);
          $order = (!empty($sanitized_get_id)) ? $sanitized_get_id : 'asc';
  
          // Determine sort order
          $result = strcmp($a[$orderby], $b[$orderby]);
  
          // Send final sort direction to usort
          return ($order === 'asc') ? $result : -$result;
      }

      


}

// add screen options
function autowp_wordpress_websites_options() {
 
	global $autowp_wordpress_website_list_page;
        global $table;
 
	$screen = get_current_screen();
 
	// get out of here if we are not on our settings page
	if(!is_object($screen) || $screen->id != $autowp_wordpress_website_list_page)
		return;
 
	$args = array(
		'label' => __('Elements per page', 'autowp'),
		'default' => 2,
		'option' => 'elements_per_page'
	);
	add_screen_option( 'per_page', $args );

    $table = new AutoWP_Wordpress_Websites();

}

function autowp_isValidDomain($domain) {
  // WordPress wp_http_validate_url fonksiyonunu kullanarak URL'yi doğrula
  $valid_url = wp_http_validate_url( $domain);

  // Eğer geçerli bir URL dönerse, alan adı geçerlidir
  if (!is_wp_error($valid_url)) {
      return true;
  } else {
      return false;
  }
}


function autowp_is_site_working($site_url, $site_type) {


  $api_url = 'https://api.autowp.app/check_website'; // Flask API URL

  // JSON formatında veriyi hazırlayın
  $body = json_encode([
      'site_url' => $site_url,
      'site_type' => $site_type,
  ]);

  // WordPress'in HTTP API'si ile POST isteği gönderin
  $response = wp_remote_post($api_url, [
      'body'    => $body,
      'headers' => [
          'Content-Type' => 'application/json',
      ],
      'timeout' => 5,
  ]);

  // Eğer yanıt bir hata dönerse veya geçerli değilse false döner
  if (is_wp_error($response)) {
      return false;
  }

  // Yanıtı alın ve JSON olarak çözümleyin
  $body = wp_remote_retrieve_body($response);
  $data = json_decode($body, true);

  // Yanıtta 'valid' değeri varsa onu döndürün, aksi halde false döner
  return isset($data['valid']) ? $data['valid'] : false;
}


function autowp_validate_agenticscraper( $item, $is_manual = false ) {
  $messages = array();

  // AgenticScraper için "content_prompt" (long description) zorunludur.
  if ( empty( $item['content_prompt'] ) ) {
      $messages[] = __( 'Long Description prompt is required for Agentic Scraper.', 'autowp' );
  }

  // --- Custom Tools Doğrulamaları ---

  // Website Tools: Eğer alan etkinse ve URL girilmişse, geçerli URL formatında olmalıdır.
  if ( ! empty( $item['enable_website_tools'] ) ) {
      if ( ! empty( $item['website_tools_knowledge_base_url'] ) ) {
          $url = esc_url_raw( trim( $item['website_tools_knowledge_base_url'] ) );
          if ( false === wp_http_validate_url( $url ) ) {
              $messages[] = __( 'Website Tools Knowledge Base URL is invalid.', 'autowp' );
          }
      }
  }

  // DuckDuckGO Search: Eğer etkinse, girilmiş "Fixed Max Results" değeri sayısal olmalıdır.
  if ( ! empty( $item['enable_duckduckgo'] ) ) {
      if ( ! empty( $item['duckduckgo_fixed_max_results'] ) && ! is_numeric( $item['duckduckgo_fixed_max_results'] ) ) {
          $messages[] = __( 'Fixed Max Results for DuckDuckGO must be a number.', 'autowp' );
      }
  }

  // Wikipedia: Aktifse, konu listesi girilmişse ek doğrulama gerekmez.
  // YFinanceTools ve Hacker News: Ek zorunlu doğrulama kuralları bulunmamaktadır.


  // --- Knowledge Base Doğrulamaları ---
  // PDF Knowledge Base: Eğer etkinse, URL girilmeli ve geçerli bir URL olmalıdır.
  if ( ! empty( $item['enable_pdf_kb'] ) ) {
      if ( empty( $item['pdf_url_knowledge_base'] ) ) {
          $messages[] = __( 'PDF Knowledge Base URL is required when enabled.', 'autowp' );
      } else {
          $url = esc_url_raw( trim( $item['pdf_url_knowledge_base'] ) );
          if ( false === wp_http_validate_url( $url ) ) {
              $messages[] = __( 'Invalid PDF Knowledge Base URL.', 'autowp' );
          }
      }
  }

  // CSV Knowledge Base: Eğer etkinse, URL girilmeli ve geçerli bir URL olmalıdır.
  if ( ! empty( $item['enable_csv_kb'] ) ) {
      if ( empty( $item['csv_url_knowledge_base'] ) ) {
          $messages[] = __( 'CSV Knowledge Base URL is required when enabled.', 'autowp' );
      } else {
          $url = esc_url_raw( trim( $item['csv_url_knowledge_base'] ) );
          if ( false === wp_http_validate_url( $url ) ) {
              $messages[] = __( 'Invalid CSV Knowledge Base URL.', 'autowp' );
          }
      }
  }

  // Text Knowledge Base: Eğer etkinse, metin alanı boş olmamalıdır.
  if ( ! empty( $item['enable_text_kb'] ) ) {
      if ( empty( $item['text_knowledge_base'] ) ) {
          $messages[] = __( 'Text Knowledge Base is required when enabled.', 'autowp' );
      }
  }

  // --- Ortak Alanlar ---
  // Manuel gönderim değilse, "website_name" zorunludur.
  if ( empty( $item['website_name'] ) && ! $is_manual ) {
      $messages[] = __( 'Website name is required', 'autowp' );
  }
  if ( empty( $item['category_id'] ) ) {
      $messages[] = __( 'Category is required', 'autowp' );
  }

  if ( empty( $messages ) ) {
      return true;
  }
  return implode( '<br />', $messages );
}



function autowp_validate_website($item,$is_manual = false)
{
    $messages = array();

    if($item['website_type'] !== 'ai' && $item['website_type'] !== 'agenticscraper'){

   

      if($item['website_type'] !== 'news'){
        if (empty($item['domain_name'])) $messages[] = __('Domain Name is required', 'autowp');

      

      if(!autowp_is_site_working(sanitize_text_field($item['domain_name']), sanitize_text_field($item['website_type']))){
        $messages[] = __('Domain name should be valid.', 'autowp');
      }

      }else{

        if (empty($item['news_keyword'])) $messages[] = __('Keyword is required', 'autowp');

      }

      

    }else{

      if(empty($item['title_prompt']) && empty($item['content_prompt']) && empty($item['tags_prompt']) && empty($item['image_prompt']) ){
        $messages[] = __('You should enter at least 1 prompt! ', 'autowp');
      }

      if(str_contains($item['content_prompt'],'autowp-promptcode') && !autowp_validate_prompt_code($item['content_prompt'] )){
        $messages[] = __('invalid prompt code. ', 'autowp');
      }

      if(str_contains($item['title_prompt'],'autowp-promptcode') || str_contains($item['tags_prompt'],'autowp-promptcode') || str_contains($item['image_prompt'],'autowp-promptcode') ){
        $messages[] = __('only content prompt can use prompt codes. ', 'autowp');
      }


    }   

    if (empty($item['website_name']) && !$is_manual) $messages[] = __('Website name is required', 'autowp');
    if (empty($item['category_id'])) $messages[] = __('Category is required', 'autowp');

    if($item['website_type'] === 'news'){

      if($item['news_country'] === 'any' && $item['news_language'] !== 'any' ){
        $messages[] = __('you should select a country! ', 'autowp');


      }

    }

    if (empty($messages)) return true;
    return implode('<br />', $messages);
}


function autowp_validate_prompt_code($input) {
   // "autowp-promptcode" yapısını arayın ve içeriğini alın
   preg_match('/\[autowp-promptcode\](.*?)\[\/autowp-promptcode\]/s', $input, $matches);
    
   if (empty($matches) || count($matches) < 2) {
       // Eğer "autowp-promptcode" yapısı bulunamazsa veya içeriği eksikse false döndürün
       return false;
   }
   
   $content = $matches[1]; // "autowp-promptcode" içeriğini alın
   $fields = explode(',', $content); // Virgülle ayrılmış alanları parçalayın
   
   // Gerekli sayıda alanın olduğunu ve her bir alanın dolu olduğunu kontrol edin
   if (count($fields) != 6) {
       return false;
   }
   
   foreach ($fields as $field) {
       if (empty(trim($field))) {
           return false;
       }
   }
   
   return true; // Geçerli ise true döndürün
}


function autowp_get_last_cron_time($cron_name) {
  // _get_cron_array fonksiyonunu kullanarak cron görevlerini al
  $cron_array = _get_cron_array();

  // autowp_cron görevinin en son çalışma zamanını al
  $last_execution_time = isset($cron_array[$cron_name]['schedule']) ? $cron_array[$cron_name]['schedule'] : 0;

  // Zamanı okunabilir bir formata çevir (isteğe bağlı)
  $last_execution_time_readable = date('Y-m-d H:i:s', $last_execution_time);

  // En son çalışma zamanını döndür
  return $last_execution_time_readable;
}

function autowp_is_cron_executed_recently($cron_name) {
  // Cron'un en son çalışma zamanını al
  $last_execution_time = autowp_get_last_cron_time($cron_name);

  // Şu anki zamanı al
  $current_time = time();

  // 10 dakika önceki zamanı hesapla
  $ten_minutes_ago = $current_time - (10 * 60);

  // Eğer en son çalışma zamanı 10 dakika içindeyse true döndür, aksi takdirde false döndür
  return $last_execution_time >= $ten_minutes_ago;
}




function autowp_settings_page_set_options() {
  $server_url = unserialize(get_option("autowp_settings"))["autowp_server_url"];


  $url = 'https://api.autowp.app/confirm_email';

  if ( isset( $server_url ) 
     && $server_url !== null 
     && $server_url !== '' 
     && strpos( $server_url, 'autowp.app' ) === false
  ) {
    
    $url = $server_url . '/confirm_email';

  }
  



  
  if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'autowp_settings_nonce')) {

    $time_value_type = sanitize_text_field($_POST["selected_time_type"]);
    $user_wpcron_time = autowp_get_wpcron_time($time_value_type);

    // API email and API key to be sent to the Flask API for confirmation
    $api_email = sanitize_text_field($_POST["api_email"]);
    $api_key = sanitize_text_field($_POST["api_key"]);

    // API request body
    $api_request_body = array(
      'body' => json_encode(array(
        'user_email' => $api_email,
        'api_key' => $api_key,
      )),
      'headers' => array(
        'Content-Type' => 'application/json'
      ),
    );

    // Send a POST request to the Flask API
    $api_response = wp_remote_post($url, $api_request_body);

    // Check for errors in the API response
    $save_api_key_email = false;
    if (!is_wp_error($api_response)) {
      $response_code = wp_remote_retrieve_response_code($api_response);
      $response_body = json_decode(wp_remote_retrieve_body($api_response), true);

      if ($response_code === 200 && isset($response_body['message']) && $response_body['message'] === 'Successfully confirmed!') {
        // If the API confirms successfully, save the API key and email
        $save_api_key_email = true;
      } else {
        // If there's an error in the API response, show the error message
        echo '<div class="alert alert-danger" role="alert">' . esc_html__('Error: ' . $response_body['error'], 'autowp') . '</div>';
      }
    } else {
      // If there's a request error
      $error_message = $api_response->get_error_message();
      echo '<div class="alert alert-danger" role="alert">' . esc_html__('API Request Error: ' . $error_message, 'autowp') . '</div>';
    }

    $existing_settings_serialized = get_option('autowp_settings');
if ($existing_settings_serialized) {
    $existing_settings = unserialize($existing_settings_serialized);
} else {
    $existing_settings = array();
}

    // Prepare data to save
    $received_data = array(
      "selected_time_type" => sanitize_text_field($_POST["selected_time_type"]),
      "wpcron_status" => sanitize_text_field($_POST['wpcron_status']),
      "watermark_link" => sanitize_text_field($_POST['watermark_link']),
      "nano_banana_prompt" => sanitize_text_field($_POST['nano_banana_prompt']),
      "image_modification_status" => sanitize_text_field($_POST['image_modification_status']),
      "ai_image_width" => absint($_POST['ai_image_width']),
      "ai_image_height" => absint($_POST['ai_image_height']),
      "stable_diffusion_style" => sanitize_text_field($_POST['stable_diffusion_style']),
      "flux_image_size" => sanitize_text_field($_POST['flux_image_size']),
      "stable_diffusion_size" => sanitize_text_field($_POST['stable_diffusion_size']),
      "dalle_2_size" => sanitize_text_field($_POST['dalle_2_size']),
      "dalle_3_size" => sanitize_text_field($_POST['dalle_3_size']),
      "dalle_3_style" => sanitize_text_field($_POST["dalle_3_style"]),
      "image_format" => sanitize_text_field($_POST["image_format"]),
      "post_status" => sanitize_text_field($_POST['post_status']),
      "content_image_generation_method" => sanitize_text_field($_POST['content_image_generation_method']),

      // New fields
      "max_posts_per_cron" => absint($_POST['max_posts_per_cron']),
      "max_posts_per_day" => absint($_POST['max_posts_per_day']),
      "spam_ad_filter" => sanitize_text_field($_POST['spam_ad_filter']),
      "duplicate_content_filter" => sanitize_text_field($_POST['duplicate_content_filter']),

      // New fields
      "openai_api_key" => sanitize_text_field($_POST['openai_api_key']),
      "xai_api_key" => sanitize_text_field($_POST['xai_api_key']),
      "falai_api_key" => sanitize_text_field($_POST['falai_api_key']),
      "stabilityai_api_key" => sanitize_text_field($_POST['stabilityai_api_key']),
      "serperdev_api_key" => sanitize_text_field($_POST['serperdev_api_key']),
      "primary_llm" => sanitize_text_field($_POST['primary_llm']),
      "secondary_llm" => sanitize_text_field($_POST['secondary_llm']),
      "deepseek_api_key" => sanitize_text_field(($_POST['deepseek_api_key'])),
      "groq_model" => sanitize_text_field($_POST['groq_model']),
      "default_image_url" => sanitize_url(($_POST['default_image_url'])),

      // Social media sharing settings
      "social_media_status" => sanitize_text_field($_POST['social_media_status']),
      "twitter_api_key" => sanitize_text_field($_POST['twitter_api_key']),
      "telegram_api_key" => sanitize_text_field($_POST['telegram_api_key']),
      "instagram_api_key" => sanitize_text_field($_POST['instagram_api_key']),
      "openai_base_url" => sanitize_text_field($_POST['openai_base_url']),

      "autowp_server_url" => sanitize_text_field($_POST['autowp_server_url'])


    );

 
    // API e-posta ve anahtarını sadece API başarılı şekilde onaylanırsa kaydedin
if ($save_api_key_email) {
  $received_data["api_key"] = $api_key;
  $received_data["api_email"] = $api_email;
} else {
  // Mevcut API anahtarı ve e-postayı koru
  if (isset($existing_settings["api_key"])) {
    $received_data["api_key"] = $existing_settings["api_key"];
  }
  if (isset($existing_settings["api_email"])) {
    $received_data["api_email"] = $existing_settings["api_email"];
  }
}

    // Serialize and save the data
    $save = serialize($received_data);
    update_option('autowp_settings', $save, "yes");

    // Show success message if successfully saved, if not show error message
    if (get_option('autowp_settings') == $save) {
      echo '<div class="alert alert-success" role="alert">' . esc_html__('Settings saved successfully!', 'autowp') . '</div>';
    } else {
      echo '<div class="alert alert-danger" role="alert">' . esc_html__('Error saving settings!', 'autowp') . '</div>';
    }

    // Schedule WP-Cron
    if (!wp_next_scheduled('autowp_cron')) {
      wp_schedule_event(time(), $user_wpcron_time, 'autowp_cron');
    } else {
      wp_clear_scheduled_hook('autowp_cron');
      wp_schedule_event(time(), $user_wpcron_time, 'autowp_cron');
    }
  }
}


function autowp_linking_page_handler() {
  // Veritabanından mevcut linkleme ayarlarını yükle
  $linking_settings = get_option('autowp_linking_settings', []);
  if (!is_array($linking_settings)) {
      $linking_settings = maybe_unserialize($linking_settings);
      if (!is_array($linking_settings)) {
          $linking_settings = [];
      }
  }

  // Form gönderildiğinde veriyi işle
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['add_link'])) {
          // Nonce kontrolü
          if (!isset($_POST['autowp_nonce']) || !wp_verify_nonce($_POST['autowp_nonce'], 'autowp_add_link_nonce')) {
              wp_die(esc_html__('Invalid request. Nonce verification failed.', 'autowp'));
          }

          // Kullanıcı girişlerini temizleme
          $keyword = sanitize_text_field($_POST['keyword']);
          $link = esc_url_raw($_POST['link']);
          $html_tag = sanitize_text_field($_POST['html_tag']);
          $link_count = sanitize_text_field($_POST['link_count']);

          // Boş değer kontrolü
          // "a" etiketi seçildiyse, URL zorunludur. Diğer etiketler için URL gerekmiyor.
          if (empty($keyword)) {
            echo '<div class="alert alert-danger" role="alert">Keyword field cannot be empty!</div>';
          } elseif ($html_tag === "a" && empty($link)) {
            echo '<div class="alert alert-danger" role="alert">Error: A URL is required when selecting the "a" (anchor link) tag.</div>';
          } else {
            // Yeni linkleme ayarını oluştur
            $new_link = [
                'keyword' => $keyword,
                'link' => ($html_tag === "a") ? $link : '', // Sadece "a" etiketi için URL ekle
                'html_tag' => $html_tag,
                'link_count' => $link_count,
            ];
            $linking_settings[] = $new_link;

            // Veriyi veritabanına kaydet
            update_option('autowp_linking_settings', $linking_settings);
            echo '<div class="alert alert-success" role="alert">Your linking setting has been saved!</div>';
          }

      } elseif (isset($_POST['delete_link'])) {
          // Nonce kontrolü
          if (!isset($_POST['autowp_nonce']) || !wp_verify_nonce($_POST['autowp_nonce'], 'autowp_delete_link_nonce')) {
              wp_die(esc_html__('Invalid request. Nonce verification failed.', 'autowp'));
          }

          // Silinecek indexi al
          $index_to_delete = intval($_POST['delete_index']);
          array_splice($linking_settings, $index_to_delete, 1);

          // Güncellenmiş listeyi kaydet
          update_option('autowp_linking_settings', $linking_settings);
          echo '<div class="alert alert-success" role="alert">Your linking setting has been deleted!</div>';
      }
  }

  ?>

  <div class="wrap">
      <h2 class="mb-4"><?php echo esc_html__('Linking Management', 'autowp'); ?></h2>

      <form method="post" class="mb-4">
          <?php wp_nonce_field('autowp_add_link_nonce', 'autowp_nonce'); ?>

          <div class="mb-3">
              <label for="keyword" class="form-label"><?php echo esc_html__('Keyword', 'autowp'); ?></label>
              <input type="text" id="keyword" name="keyword" required class="form-control" />
          </div>

          <div class="mb-3">
              <label for="link" class="form-label"><?php echo esc_html__('Link (URL)', 'autowp'); ?></label>
              <input type="url" id="link" name="link"  class="form-control" />
          </div>

          <div class="mb-3">
              <label for="html_tag" class="form-label"><?php echo esc_html__('HTML Tag', 'autowp'); ?></label>
              <select id="html_tag" name="html_tag" class="form-control" required>
                  <option value="a">Anchor Link (&lt;a&gt;)</option>
                  <option value="strong">Bold Text (&lt;strong&gt;)</option>
                  <option value="span">Span Element (&lt;span&gt;)</option>
                  <option value="em">Italic Text (&lt;em&gt;)</option>
              </select>
              <small class="form-text text-muted">Choose how the link should be wrapped.</small>
          </div>

          <div class="mb-3">
              <label for="link_count" class="form-label"><?php echo esc_html__('Linking Count', 'autowp'); ?></label>
              <select id="link_count" name="link_count" class="form-control" required>
                  <option value="all">All Keywords</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
              </select>
              <small class="form-text text-muted">Select how many times the keyword should be linked.</small>
          </div>

          <button type="submit" name="add_link" class="btn btn-primary"><?php echo esc_attr__('Save Link Setting', 'autowp'); ?></button>
      </form>

      <h3 class="mt-5 mb-3"><?php echo esc_html__('Existing Linking Settings', 'autowp'); ?></h3>
      <ul class="list-group">
          <?php foreach ($linking_settings as $index => $setting) { ?>
              <li class="list-group-item d-flex justify-content-between align-items-start">
                  <div>
                      <strong>Keyword:</strong> <?php echo esc_html($setting['keyword']); ?><br>
                      <strong>Link:</strong> 
<?php 
if ($setting['html_tag'] === "a" && !empty($setting['link'])) { 
    echo '<a href="' . esc_url($setting['link']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($setting['link']) . '</a>'; 
} else {
    echo "N/A"; // Eğer "a" değilse veya link yoksa, "N/A" göster
}
?><br>

                      <strong>HTML Tag:</strong> <?php echo esc_html($setting['html_tag']); ?><br>
                      <strong>Linking Count:</strong> <?php echo esc_html($setting['link_count']); ?>
                  </div>
                  <div>
                      <form method="post">
                          <?php wp_nonce_field('autowp_delete_link_nonce', 'autowp_nonce'); ?>
                          <input type="hidden" name="delete_index" value="<?php echo esc_attr($index); ?>">
                          <button type="submit" name="delete_link" class="btn btn-danger btn-sm">Delete</button>
                      </form>
                  </div>
              </li>
          <?php } ?>
      </ul>
  </div>

  <?php
}




function autowp_rewriting_promptscheme_page_handler() {
  // Veritabanından mevcut ayarları yükle
  $prompts = get_option('autowp_rewriting_promptscheme', []);
if (!is_array($prompts)) {
    $prompts = maybe_unserialize($prompts); // Serileştirilmiş veriyi çözümleme
    if (!is_array($prompts)) {
        $prompts = []; // Eğer hala dizi değilse, boş bir diziye ayarla
    }
}


  // Formdan gelen veriyi işle (ekleme, silme, sıralama değiştirme)
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['add_prompt'])) {

        if (!isset($_POST['autowp_nonce']) || !wp_verify_nonce($_POST['autowp_nonce'], 'autowp_add_prompt_nonce')) {
          wp_die(esc_html__('Invalid request. Nonce verification failed.', 'autowp'));
      }
      
      

                  $element_type = sanitize_text_field($_POST['element_type']);
                  $detailed_prompt = '';
                  $is_content_or_blank = false;


                  if($element_type == 'static-content'){
                    $detailed_prompt = wp_kses_post($_POST['content']);
                    $is_content_or_blank = ($detailed_prompt == '' or $detailed_prompt == null);
                  }else{
                    $detailed_prompt = sanitize_text_field($_POST['prompt']);
                    $is_content_or_blank = ($detailed_prompt == '' or $detailed_prompt == null);
                  }

                  
          
          // Ekleme sırasında kontroller
          if (count($prompts) >= 10 or $is_content_or_blank) {
              echo '<div class="alert alert-danger" role="alert">Maximum 10 prompts allowed and prompt/content cannot be empty!</div>';
          } else {
            
              // Kelime sınırı kontrolü
              $prompt_words = explode(" ", $_POST['prompt']);
              if (count($prompt_words) > 300) {
                  echo '<div class="alert alert-danger" role="alert">Detailed Prompt should not exceed 300 words.</div>';
              } else {
                  
                  $new_prompt = [
                      'name' => sanitize_text_field($_POST['name']),
                      'detailed_prompt' => $detailed_prompt,
                      'element_type' => sanitize_text_field($_POST['element_type']),
                  ];
                  $prompts[] = $new_prompt;
                  echo '<div class="alert alert-success" role="alert">Your prompt successfully added to scheme!</div>';
              }
          }
      } elseif (isset($_POST['delete_prompt'])) {
        if (!isset($_POST['autowp_nonce']) || !wp_verify_nonce($_POST['autowp_nonce'], 'autowp_delete_prompt_nonce')) {
          wp_die(esc_html__('Invalid request. Nonce verification failed.', 'autowp'));
      }
      
          $index_to_delete = intval($_POST['delete_index']);
          array_splice($prompts, $index_to_delete, 1);
          echo '<div class="alert alert-success" role="alert">Your prompt successfully deleted!</div>';
      } elseif (isset($_POST['move_up'])) {
          $index = intval($_POST['index']);
          if ($index > 0) {
              $temp = $prompts[$index - 1];
              $prompts[$index - 1] = $prompts[$index];
              $prompts[$index] = $temp;
              echo '<div class="alert alert-success" role="alert">Your prompt successfully move up!</div>';
          }
      } elseif (isset($_POST['move_down'])) {
          $index = intval($_POST['index']);
          if ($index < count($prompts) - 1) {
              $temp = $prompts[$index + 1];
              $prompts[$index + 1] = $prompts[$index];
              $prompts[$index] = $temp;
              echo '<div class="alert alert-success" role="alert">Your prompt successfully move down!</div>';
          }
      } elseif (isset($_POST['add_template'])) {
          $template_name = sanitize_text_field($_POST['template_name']);
          $template_prompt = sanitize_text_field($_POST['template_prompt']);
          if (count($prompts) >= 10) {
              echo '<div class="alert alert-danger" role="alert">Maximum 10 prompts allowed.</div>';
          } else {
              $new_prompt = [
                  'name' => $template_name,
                  'detailed_prompt' => $template_prompt,
                  'max_tokens' => 300000
              ];
              $prompts[] = $new_prompt;
              echo '<div class="alert alert-success" role="alert">Your prompt successfully added to scheme!</div>';
          }
      }
      update_option('autowp_rewriting_promptscheme', $prompts);
  }

  // HTML form ve mevcut verileri göster
  ?>
  <div class="wrap">
    <h2 class="mb-4"><?php echo esc_html__('Content Planner', 'autowp'); ?></h2>

    <form method="post" class="mb-4">
    <?php wp_nonce_field('autowp_add_prompt_nonce', 'autowp_nonce'); ?>
        <div class="mb-3">
            <label for="name" class="form-label"><?php echo esc_html__('Name', 'autowp'); ?></label>
            <input type="text" id="name" name="name" required class="form-control" />
        </div>
 

        <div class="mb-3"  id="prompt-container">
            <label for="prompt" class="form-label"><?php echo esc_html__('Detailed Prompt', 'autowp'); ?></label>
            <div class="tag-buttons mb-2">
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{new_title}')">Post Title</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{language_code}')">Language Name</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{focus_keyword}')">Focus Keyword</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{combined_content}')">Original Content</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{subheading_count}')">Subheading Count</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{narration_style}')">Narration Style</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{related_keywords}')">Related Keywords</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{original_link}')">Original Link</button>
    </div>
          <textarea id="prompt" name="prompt" rows="4" maxlength="1500" class="form-control"></textarea>

            <small class="form-text text-muted">Maximum 300 words.</small>
        </div>

        <!-- Static Content (hidden by default) -->
<div class="mb-3" id="content-container" style="display: none;">

    <label for="content" class="form-label"><?php echo esc_html__('HTML Content', 'autowp'); ?></label>
    <div class="tag-buttons mb-2">
    <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertHTMLTag('<h1>Your Heading</h1>')">Heading 1</button>
<button type="button" class="btn btn-sm btn-outline-primary" onclick="insertHTMLTag('<h2>Your Subheading</h2>')">Heading 2</button>
<button type="button" class="btn btn-sm btn-outline-primary" onclick="insertHTMLTag('<p>Your paragraph text goes here.</p>')">Paragraph</button>
<button type="button" class="btn btn-sm btn-outline-primary" onclick="insertHTMLTag('<img src=\'your-image-url.jpg\' alt=\'Image description\' />')">Image</button>
<button type="button" class="btn btn-sm btn-outline-primary" onclick="insertHTMLTag('<blockquote>Your blockquote text.</blockquote>')">Blockquote</button>
<button type="button" class="btn btn-sm btn-outline-primary" onclick="insertHTMLTag('<pre><code>Your code goes here.</code></pre>')">Code Block</button>
<button type="button" class="btn btn-sm btn-outline-primary" onclick="insertHTMLTag('<ul><li>Item 1</li><li>Item 2</li></ul>')">Unordered List</button>
<button type="button" class="btn btn-sm btn-outline-primary" onclick="insertHTMLTag('<ol><li>Item 1</li><li>Item 2</li></ol>')">Ordered List</button>
<button type="button" class="btn btn-sm btn-outline-primary" onclick="insertHTMLTag('<hr />')">Horizontal Line</button>
<button type="button" class="btn btn-sm btn-outline-primary" onclick="insertHTMLTag('<br />')">Line Break</button>

    </div>
    <textarea id="content" name="content" rows="6" class="form-control"></textarea>
    <small class="form-text text-muted">Enter custom HTML content for static generation.</small>
</div>




        <!-- Element Type Select -->
        <div class="mb-3">
            <label for="element_type" class="form-label"><?php echo esc_html__('HTML Tag Type', 'autowp'); ?></label>
            <select id="element_type" name="element_type" class="form-control" required>
                <option value="p">Paragraph (&lt;p&gt;)</option>
                <option value="ph2">Paragraphs with H2 Subheadings (&lt;p&gt;,&lt;h2&gt;)</option>
                <option value="h1">Subheading H1 (&lt;h1&gt;)</option>
                <option value="h2">Subheading H2 (&lt;h2&gt;)</option>
                <option value="h3">Subheading H3 (&lt;h3&gt;)</option>
                <option value="h4">Subheading H4 (&lt;h4&gt;)</option>
                <option value="h5">Subheading H5 (&lt;h5&gt;)</option>
                <option value="h6">Subheading H6 (&lt;h6&gt;)</option>
                <option value="img">AI-Generated Image  (&lt;img&gt;)</option>
                <option value="blockquote">Block Quote  (&lt;blockquote&gt;)</option>
                <option value="code">Code Block  (&lt;code&gt;)</option>
                <option value="table">HTML Table  (&lt;table&gt;)</option>
                <option value="faq">FAQ (Without Heading) </option>
                <option value="static-content">Static HTML Content</option>
                <option value="ai-generated-html">AI-Generated HTML Part</option>

               
            </select>
            <small class="form-text text-muted">Select the HTML element type for content generation (e.g., paragraphs, subheadings).</small>
        </div>
        <button type="submit" name="add_prompt" class="btn btn-primary"><?php echo esc_attr__('Add Prompt', 'autowp'); ?></button>
    </form>

    <h3 class="mb-3"><?php echo esc_html__('Ready-Made Templates', 'autowp'); ?></h3>
    <!-- Template butonları için ayrı form yapıları -->
    <form method="post" class="d-grid gap-2 d-md-block mb-3">
        <input type="hidden" name="add_template" value="true">
        <input type="hidden" name="template_name" value="AutoWP Introduction">
        <input type="hidden" name="template_prompt" value="autowp-introduction">
        <button type="submit" class="btn btn-secondary">Add Introduction Template</button>
    </form>
    <form method="post" class="d-grid gap-2 d-md-block mb-3">
        <input type="hidden" name="add_template" value="true">
        <input type="hidden" name="template_name" value="AutoWP Subheadings">
        <input type="hidden" name="template_prompt" value="autowp-subheadings">
        <button type="submit" class="btn btn-secondary">Add Subheadings Template</button>
    </form>
    <form method="post" class="d-grid gap-2 d-md-block mb-3">
        <input type="hidden" name="add_template" value="true">
        <input type="hidden" name="template_name" value="AutoWP FAQ">
        <input type="hidden" name="template_prompt" value="autowp-faq">
        <button type="submit" class="btn btn-secondary">Add FAQ Template</button>
    </form>
    <form method="post" class="d-grid gap-2 d-md-block mb-3">
        <input type="hidden" name="add_template" value="true">
        <input type="hidden" name="template_name" value="AutoWP Results">
        <input type="hidden" name="template_prompt" value="autowp-result">
        <button type="submit" class="btn btn-secondary">Add Table + Results Template</button>
    </form>
    
    <form method="post" class="d-grid gap-2 d-md-block mb-3">
        <input type="hidden" name="add_template" value="true">
        <input type="hidden" name="template_name" value="AutoWP Source Link">
        <input type="hidden" name="template_prompt" value="autowp-source-link">
        <button type="submit" class="btn btn-secondary">Add Source Link Template</button>
    </form>

    <h3 class="mt-5 mb-3"><?php echo esc_html__('Existing Prompts', 'autowp'); ?></h3>
<ul class="list-group">
    <?php foreach ($prompts as $index => $prompt) { ?>
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div>
                <strong>Prompt or Content: </strong><pre style="white-space: pre-wrap;"><?php echo esc_html($prompt['detailed_prompt']); ?></pre><br>
                
                <strong>Element Type: </strong><?php echo isset($prompt['element_type']) ? esc_html($prompt['element_type']) : 'AutoWP Template'; ?><br>
            </div>
            <div class="btn-group">
                <form method="post" style="display: inline;">
                    <?php wp_nonce_field('autowp_delete_prompt_nonce', 'autowp_nonce'); ?>
                    <input type="hidden" name="delete_index" value="<?php echo esc_attr($index); ?>">
                    <button type="submit" name="delete_prompt" class="btn btn-danger btn-sm">×</button>
                </form>
                <?php if ($index > 0) { ?>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="index" value="<?php echo esc_attr($index); ?>">
                        <button type="submit" name="move_up" class="btn btn-link btn-sm text-primary">↑</button>
                    </form>
                <?php } ?>
                <?php if ($index < count($prompts) - 1) { ?>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="index" value="<?php echo esc_attr($index); ?>">
                        <button type="submit" name="move_down" class="btn btn-link btn-sm text-primary">↓</button>
                    </form>
                <?php } ?>
            </div>
        </li>
    <?php } ?>
</ul>

  </div>
  <?php
}



function autowp_writing_promptscheme_page_handler() {
  // Veritabanından mevcut ayarları yükle
  $prompts = get_option('autowp_writing_promptscheme', []);

  // Formdan gelen veriyi işle (ekleme, silme, sıralama değiştirme)
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['add_prompt'])) {

        if (!isset($_POST['autowp_nonce']) || !wp_verify_nonce($_POST['autowp_nonce'], 'autowp_add_prompt_nonce')) {
          wp_die(esc_html__('Invalid request. Nonce verification failed.', 'autowp'));
      }
      
      
          // Ekleme sırasında kontroller
          if (count($prompts) >= 5) {
              echo '<div class="alert alert-danger" role="alert">Maximum 5 prompts allowed.</div>';
          } else {
            
              // Kelime sınırı kontrolü
              $prompt_words = explode(" ", $_POST['prompt']);
              if (count($prompt_words) > 300) {
                  echo '<div class="alert alert-danger" role="alert">Detailed Prompt should not exceed 300 words.</div>';
              } else {
                  $new_prompt = [
                      'name' => sanitize_text_field($_POST['name']),
                      'detailed_prompt' => sanitize_textarea_field($_POST['prompt']),
                      'max_tokens' => intval($_POST['tokens'])
                  ];
                  $prompts[] = $new_prompt;
                  echo '<div class="alert alert-success" role="alert">Your prompt successfully added to scheme!</div>';

              }
          }
      } elseif (isset($_POST['delete_prompt'])) {
        if (!isset($_POST['autowp_nonce']) || !wp_verify_nonce($_POST['autowp_nonce'], 'autowp_delete_prompt_nonce')) {
          wp_die(esc_html__('Invalid request. Nonce verification failed.', 'autowp'));
      }
      
          $index_to_delete = intval($_POST['delete_index']);
          array_splice($prompts, $index_to_delete, 1);
          echo '<div class="alert alert-success" role="alert">Your prompt successfully deleted!</div>';

      } elseif (isset($_POST['move_up'])) {
          $index = intval($_POST['index']);
          if ($index > 0) {
              $temp = $prompts[$index - 1];
              $prompts[$index - 1] = $prompts[$index];
              $prompts[$index] = $temp;
              echo '<div class="alert alert-success" role="alert">Your prompt successfully move up!</div>';

          }
      } elseif (isset($_POST['move_down'])) {
          $index = intval($_POST['index']);
          if ($index < count($prompts) - 1) {
              $temp = $prompts[$index + 1];
              $prompts[$index + 1] = $prompts[$index];
              $prompts[$index] = $temp;
              echo '<div class="alert alert-success" role="alert">Your prompt successfully move down!</div>';

          }
      } elseif (isset($_POST['add_template'])) {
          $template_name = sanitize_text_field($_POST['template_name']);
          $template_prompt = sanitize_text_field($_POST['template_prompt']);
          if (count($prompts) >= 5) {

            echo '<div class="alert alert-danger" role="alert">Maximum 5 prompts allowed.</div>';


          }else{

            $new_prompt = [
              'name' => $template_name,
              'detailed_prompt' => $template_prompt,
              'max_tokens' => 3000
            ];
            $prompts[] = $new_prompt;
            echo '<div class="alert alert-success" role="alert">Your prompt successfully added to scheme!</div>';


          }
          
      }
      update_option('autowp_writing_promptscheme', $prompts);
  }

  // HTML form ve mevcut verileri göster
  ?>
  <div class="wrap">
    <h2 class="mb-4"><?php echo esc_html__('Writing Prompt Schemes', 'autowp'); ?></h2>
    <a class="btn btn-secondary mb-4" href="<?php echo esc_url(admin_url('admin.php?page=autowp_promptSettings')); ?>"><?php echo esc_html__('Back', 'autowp'); ?></a>

    <form method="post" class="mb-4">
        <div class="mb-3">
            <label for="name" class="form-label"><?php echo esc_html__('Name', 'autowp'); ?></label>
            <input type="text" id="name" name="name" required class="form-control" />
        </div>
        <div class="mb-3">
            <label for="prompt" class="form-label"><?php echo esc_html__('Detailed Prompt', 'autowp'); ?></label>
             <!-- Tag Buttons -->
    <div class="tag-buttons mb-2">
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{new_title}')">New Title</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{language_code}')">Language Code</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{focus_keyword}')">Focus Keyword</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{combined_content}')">Combined Content</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{subheading_count}')">Subheading Count</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{narration_style}')">Narration Style</button>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertTag('{related_keywords}')">Related Keywords</button>
    </div>
            <textarea id="prompt" name="prompt" required rows="4" maxlength="1500" class="form-control"></textarea>
            <small class="form-text text-muted">Maximum 300 words.</small>
            <br><br>
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">Prompt Codes</h4>
                <p>$keywords: If you have selected Google Trends, it will return the most talked about topics related to your keyword on Trends. If you have selected "aigenerated", it will return the keywords with the highest search volume related to your keyword in search engines.</p>
               
            </div>
        </div>
        <div class="mb-3">
            <label for="tokens" class="form-label"><?php echo esc_html__('Maximum Tokens', 'autowp'); ?></label>
            <input type="number" id="tokens" name="tokens" required min="300" max="1000" class="form-control" />
        </div>
        <button type="submit" name="add_prompt" class="btn btn-primary"><?php echo esc_attr__('Add Prompt', 'autowp'); ?></button>
    </form>

    <h3 class="mb-3"><?php echo esc_html__('Ready-Made Templates', 'autowp'); ?></h3>
    <!-- Template butonları için ayrı form yapıları -->
    <form method="post" class="d-grid gap-2 d-md-block mb-3">
        <input type="hidden" name="add_template" value="true">
        <input type="hidden" name="template_name" value="AutoWP Introduction">
        <input type="hidden" name="template_prompt" value="autowp-introduction">
        <button type="submit" class="btn btn-secondary">Add Introduction Template</button>
    </form>
    <form method="post" class="d-grid gap-2 d-md-block mb-3">
        <input type="hidden" name="add_template" value="true">
        <input type="hidden" name="template_name" value="AutoWP Subheadings">
        <input type="hidden" name="template_prompt" value="autowp-subheadings">
        <button type="submit" class="btn btn-secondary">Add Subheadings Template</button>
    </form>
    <form method="post" class="d-grid gap-2 d-md-block">
        <input type="hidden" name="add_template" value="true">
        <input type="hidden" name="template_name" value="AutoWP Results">
        <input type="hidden" name="template_prompt" value="autowp-result">
        <button type="submit" class="btn btn-secondary">Add Results Template</button>
    </form>

    <h3 class="mt-5 mb-3"><?php echo esc_html__('Existing Prompts', 'autowp'); ?></h3>
    <ul class="list-group">
        <?php foreach ($prompts as $index => $prompt) { ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <?php echo esc_html($prompt['name'] . ' - ' . $prompt['detailed_prompt'] . ' (Max Tokens: ' . $prompt['max_tokens'] . ')'); ?>
                <div class="btn-group">
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="delete_index" value="<?php echo esc_attr($index); ?>">
                        <button type="submit" name="delete_prompt" class="btn btn-danger btn-sm">×</button>
                    </form>
                    <?php if ($index > 0) { ?>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="index" value="<?php echo esc_attr($index); ?>">
                            <button type="submit" name="move_up" class="btn btn-link btn-sm text-primary">↑</button>
                        </form>
                    <?php } ?>
                    <?php if ($index < count($prompts) - 1) { ?>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="index" value="<?php echo esc_attr($index); ?>">
                            <button type="submit" name="move_down" class="btn btn-link btn-sm text-primary">↓</button>
                        </form>
                    <?php } ?>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>


  <?php
}






function autowp_settings_page_handler() {
  
  autowp_settings_page_set_options();

  // Prepare the next cron time
  $next_cron_time = wp_next_scheduled('autowp_cron');
  $next_cron_time_formatted = $next_cron_time ? date_i18n('Y-m-d H:i:s', $next_cron_time) : __('No scheduled cron event found', 'autowp');
?>

<!-- Include the success/error message container -->
<div id="cron-message" class="alert" style="display:none;"></div>

<form method="post" class="form-horizontal">
<?php wp_nonce_field('autowp_settings_nonce', '_wpnonce'); ?>
  <fieldset>
      <!-- Form Name -->
      <legend><?php esc_html_e('Cron Settings', 'autowp'); ?></legend>

      <!-- Next Scheduled Cron Time -->
      <div class="form-group">
          <label class="col-md-4 control-label"><?php esc_html_e('Next Cron Trigger Time', 'autowp'); ?></label>
          <div class="col-md-4">
              <p id="next-cron-time"><?php echo esc_html($next_cron_time_formatted); ?></p>
          </div>
      </div>

     

      
      <!-- Time Type -->
      <div class="form-group">
          <label class="col-md-4 control-label" for="selected_time_type"><?php esc_html_e('Time Type', 'autowp'); ?></label>
          <div class="col-md-4">
              <select id="selected_time_type" name="selected_time_type" class="form-control">
                  <option  value="1" <?php if(unserialize(get_option("autowp_settings"))["selected_time_type"] === '1') {echo ' selected'; } ?>><?php esc_html_e('Hour', 'autowp'); ?></option>
                  <option  value="2" <?php if(unserialize(get_option("autowp_settings"))["selected_time_type"] === '2') {echo ' selected'; } ?>><?php esc_html_e('Twice Daily', 'autowp'); ?></option>
                  <option  value="3" <?php if(unserialize(get_option("autowp_settings"))["selected_time_type"] === '3') {echo ' selected'; } ?>><?php esc_html_e('Daily', 'autowp'); ?></option>
                  <option  value="4" <?php if(unserialize(get_option("autowp_settings"))["selected_time_type"] === '4') {echo ' selected'; } ?>><?php esc_html_e('Weekly', 'autowp'); ?></option>
              </select>
          </div>
      </div>
      
    

      <!-- WP-Cron Status -->
      <div class="form-group">
            <label class="col-md-4 control-label" for="wpcron_status"><?php esc_html_e('WP-Cron Status', 'autowp'); ?></label>
            <div class="col-md-4">
                <label class="radio-inline">
                    <input type="radio" name="wpcron_status" value="1" <?php $settings = unserialize(get_option('autowp_settings')); if($settings['wpcron_status'] === '1' || !isset($settings['wpcron_status']) ){ echo 'checked="checked"'; } ?> ><?php esc_html_e('Active', 'autowp'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="wpcron_status" value="2" <?php $settings = unserialize(get_option('autowp_settings')); if($settings['wpcron_status'] !== '1' && isset($settings['wpcron_status']) ){ echo 'checked="checked"'; } ?> ><?php esc_html_e('Passive', 'autowp'); ?>
                </label>
            </div>
      </div>
      
      <!-- Description -->
      <div class="form-group">
          <label class="col-md-4 control-label" for="description"><?php esc_html_e('If you want to stop generating new posts, you should set WP-Cron to passive.', 'autowp'); ?></label>
          <div class="col-md-4">
              <label><?php esc_html_e('', 'autowp'); ?></label>
          </div>
      </div>
<!-- API Ayarları -->
<legend><?php esc_html_e('API Settings', 'autowp'); ?></legend>
      
      <!-- Email -->
      <div class="form-group">
          <label class="col-md-4 control-label" for="api_email"><?php esc_html_e('Email', 'autowp'); ?></label>
          <div class="col-md-4">
              <input id="api_email" name="api_email" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["api_email"]); ?>" placeholder="<?php esc_html_e('Enter your email', 'autowp'); ?>">
              <p class="help-block"><?php esc_html_e('IMPORTANT! This email is used for API registration and authentication.', 'autowp'); ?></p>
          </div>
      </div>

      <!-- API Key -->
      <div class="form-group">
          <label class="col-md-4 control-label" for="api_key"><?php esc_html_e('AutoWP API Key', 'autowp'); ?></label>
          <div class="col-md-4">
              <input id="api_key" name="api_key" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["api_key"]); ?>" placeholder="<?php esc_html_e('Enter your API key', 'autowp'); ?>">
              <p class="help-block"><?php esc_html_e('IMPORTANT! AutoWP API key for generating content. If you left it empty, you cannot generate any content!', 'autowp'); ?></p>
          </div>
      </div>

      <!-- OpenAI API Key -->
<div class="form-group">
    <label class="col-md-4 control-label" for="openai_api_key"><?php esc_html_e('OpenAI API Key', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="openai_api_key" name="openai_api_key" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["openai_api_key"]); ?>" placeholder="<?php esc_html_e('Enter your OpenAI API key', 'autowp'); ?>">
        <p class="help-block"><?php esc_html_e('IMPORTANT! Only users with the UNLIMITED package need to configure this setting. Learn how to obtain the key here: https://www.youtube.com/watch?v=OB99E7Y1cMA', 'autowp'); ?></p>
    </div>
</div>

<!-- OpenAI Base URL -->
<div class="form-group">
    <label class="col-md-4 control-label" for="openai_base_url"><?php esc_html_e('OpenAI Base URL', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="openai_base_url" name="openai_base_url" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["openai_base_url"]); ?>" placeholder="<?php esc_html_e('Enter your OpenAI Base URL (optional)', 'autowp'); ?>">
        <p class="help-block"><?php esc_html_e('OPTIONAL: Only set this if you are using a custom proxy or self-hosted OpenAI-compatible server. Leave empty to use the default OpenAI API.', 'autowp'); ?></p>
    </div>
</div>


<!-- xAI API Key -->
<div class="form-group">
    <label class="col-md-4 control-label" for="xai_api_key"><?php esc_html_e('xAI API Key', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="xai_api_key" name="xai_api_key" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["xai_api_key"]); ?>" placeholder="<?php esc_html_e('Enter your xAI API key', 'autowp'); ?>">
        <p class="help-block"><?php esc_html_e('IMPORTANT! Only users with the UNLIMITED package need to configure this setting. Learn how to obtain the key here: https://www.youtube.com/watch?v=4Tzs4qunYJY', 'autowp'); ?></p>
    </div>
</div>

<!-- Groq API Key -->
<div class="form-group">
  <label class="col-md-4 control-label" for="deepseek_api_key"><?php esc_html_e('Groq API Key', 'autowp'); ?></label>
  <div class="col-md-4">
    <input id="deepseek_api_key" name="deepseek_api_key" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["deepseek_api_key"]); ?>" placeholder="<?php esc_html_e('Enter your Groq API key', 'autowp'); ?>">
    <p class="help-block"><?php esc_html_e('IMPORTANT! Enter your Groq API key for accessing Groq services.', 'autowp'); ?></p>
  </div>
</div>

<!-- Groq Model Selection (Statik) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="groq_model"><?php esc_html_e('Groq Model', 'autowp'); ?></label>
  <div class="col-md-4">
    <select id="groq_model" name="groq_model" class="form-control">
      <?php
      // Groq model seçiminin kaydedilen değerini alıyoruz
      $selected_groq_model = isset(unserialize(get_option("autowp_settings"))["groq_model"]) ? unserialize(get_option("autowp_settings"))["groq_model"] : '';

      // Ekteki ekran görüntülerindeki model gruplarına göre statik bir dizi oluşturuyoruz.
      $static_models = array(
        'Alibaba Cloud' => array(
          'qwen-2.5-32b',
          'qwen-2.5-coder-32b',
          'qwen-qwq-32b',
        ),
        'DeepSeek / Alibaba Cloud' => array(
          'deepseek-r1-distill-qwen-32b',
        ),
        'DeepSeek / Meta' => array(
          'deepseek-r1-distill-llama-70b',
        ),
        'Google' => array(
          'gemma2-9b-it',
        ),
        'Hugging Face' => array(
          'distil-whisper-large-v3-en',
        ),
        'OpenAI' => array(
          'whisper-large-v3',
          'whisper-large-v3-turbo',
        ),
        'Mistral AI' => array(
          'mistral-saba-24b',
          'mixtral-8x7b-32768',
        ),
        'Llama' => array(
          'llama-3.2-11b-vision-preview',
          'llama-3.2-3b-preview',
          'llama-3.2-7b-preview',
          'llama-3.2-90b-vision-preview',
          'llama-3.2-90b-preview',
          'llama-3.2-70b-vision-preview',
          'llama-3.3-70b-versatile',
          'llama-3.3-70b-specdec',
          'llama-guard-3-8b',
          'llama-70b-8192',
          'llama-3-8b-8192',
        ),
      );

      // Grupları ve modelleri select içinde listeliyoruz
      foreach ($static_models as $group_label => $models) {
        echo '<optgroup label="'.esc_attr($group_label).'">';
        foreach ($models as $model) {
          $selected = ($model === $selected_groq_model) ? 'selected' : '';
          echo '<option value="'.esc_attr($model).'" '.$selected.'>'.esc_html($model).'</option>';
        }
        echo '</optgroup>';
      }
      ?>
    </select>
    <p class="help-block"><?php esc_html_e('Select a Groq model to use from the static list of available models.', 'autowp'); ?></p>
  </div>
</div>




<!-- FalAI API Key -->
<div class="form-group">
    <label class="col-md-4 control-label" for="falai_api_key"><?php esc_html_e('FalAI API Key', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="falai_api_key" name="falai_api_key" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["falai_api_key"]); ?>" placeholder="<?php esc_html_e('Enter your FalAI API key', 'autowp'); ?>">
        <p class="help-block"><?php esc_html_e('IMPORTANT! Only users with the UNLIMITED package need to configure this setting. Learn how to obtain the key here: https://fal.ai', 'autowp'); ?></p>
    </div>
</div>

<!-- StabilityAI API Key -->
<div class="form-group">
    <label class="col-md-4 control-label" for="stabilityai_api_key"><?php esc_html_e('StabilityAI API Key', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="stabilityai_api_key" name="stabilityai_api_key" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["stabilityai_api_key"]); ?>" placeholder="<?php esc_html_e('Enter your StabilityAI API key', 'autowp'); ?>">
        <p class="help-block"><?php esc_html_e('IMPORTANT! Only users with the UNLIMITED package need to configure this setting. Learn how to obtain the key here: https://www.youtube.com/watch?v=De-SOrWHMh8', 'autowp'); ?></p>
    </div>
</div>

<!-- SerperDEV API Key -->
<div class="form-group">
    <label class="col-md-4 control-label" for="serperdev_api_key"><?php esc_html_e('SerperDEV API Key', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="serperdev_api_key" name="serperdev_api_key" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["serperdev_api_key"]); ?>" placeholder="<?php esc_html_e('Enter your SerperDEV API key', 'autowp'); ?>">
        <p class="help-block"><?php esc_html_e('IMPORTANT! Only users with the UNLIMITED package need to configure this setting. Learn how to obtain the key here: https://serper.dev/signup', 'autowp'); ?></p>
    </div>
</div>

<!-- Primary LLM Option -->
<div class="form-group">
  <label class="col-md-4 control-label" for="primary_llm"><?php esc_html_e('Primary LLM Option', 'autowp'); ?></label>
  <div class="col-md-4">
    <select id="primary_llm" name="primary_llm" class="form-control">
      <option value="openai" <?php if (isset(unserialize(get_option("autowp_settings"))["primary_llm"]) && unserialize(get_option("autowp_settings"))["primary_llm"] === 'openai') { echo 'selected'; } ?>><?php esc_html_e('OpenAI', 'autowp'); ?></option>
      <option value="xai" <?php if (isset(unserialize(get_option("autowp_settings"))["primary_llm"]) && unserialize(get_option("autowp_settings"))["primary_llm"] === 'xai') { echo 'selected'; } ?>><?php esc_html_e('xAI Grok', 'autowp'); ?></option>
      <option value="groq" <?php if (isset(unserialize(get_option("autowp_settings"))["primary_llm"]) && unserialize(get_option("autowp_settings"))["primary_llm"] === 'groq') { echo 'selected'; } ?>><?php esc_html_e('Groq', 'autowp'); ?></option>
    </select>
    <p class="help-block"><?php esc_html_e('Select the primary LLM provider. This provider will be used as the default for content generation.', 'autowp'); ?></p>
  </div>
</div>

<!-- Secondary LLM Option -->
<div class="form-group">
  <label class="col-md-4 control-label" for="secondary_llm"><?php esc_html_e('Secondary LLM Option', 'autowp'); ?></label>
  <div class="col-md-4">
    <select id="secondary_llm" name="secondary_llm" class="form-control">
      <option value="openai" <?php if (isset(unserialize(get_option("autowp_settings"))["secondary_llm"]) && unserialize(get_option("autowp_settings"))["secondary_llm"] === 'openai') { echo 'selected'; } ?>><?php esc_html_e('OpenAI', 'autowp'); ?></option>
      <option value="xai" <?php if (isset(unserialize(get_option("autowp_settings"))["primary_llm"]) && unserialize(get_option("autowp_settings"))["secondary_llm"] === 'xai') { echo 'selected'; } ?>><?php esc_html_e('xAI Grok', 'autowp'); ?></option>
      <option value="groq" <?php if (isset(unserialize(get_option("autowp_settings"))["secondary_llm"]) && unserialize(get_option("autowp_settings"))["secondary_llm"] === 'groq') { echo 'selected'; } ?>><?php esc_html_e('Groq', 'autowp'); ?></option>
    </select>
    <p class="help-block"><?php esc_html_e('Select the secondary LLM provider. This option will be used if the primary provider fails.', 'autowp'); ?></p>
  </div>
</div>

      
  <!-- Post Status -->
<legend><?php esc_html_e('Post Settings', 'autowp'); ?></legend>

<!-- Default Image URL -->
<div class="form-group">
    <label class="col-md-4 control-label" for="default_image_url"><?php esc_html_e('Default Image URL', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="default_image_url" name="default_image_url" type="url" class="form-control" value="<?php 
        echo isset(unserialize(get_option("autowp_settings"))["default_image_url"]) ? esc_url(unserialize(get_option("autowp_settings"))["default_image_url"]) : ''; 
        ?>" placeholder="<?php esc_html_e('Enter default image URL', 'autowp'); ?>">
        <p class="help-block"><?php esc_html_e('If no specific thumbnail is set for a post, this image URL will be used as the default thumbnail. Leave empty to disable this feature.', 'autowp'); ?></p>
    </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="post_status"><?php esc_html_e('Post Status', 'autowp'); ?></label>
  <div class="col-md-4">
    <select id="post_status" name="post_status" class="form-control">
      <?php
      $selected_post_status = isset(unserialize(get_option("autowp_settings"))["post_status"]) ? unserialize(get_option("autowp_settings"))["post_status"] : 'publish';
      
      $statuses = array(
        'publish' => __('Published', 'autowp'),
        'draft'   => __('Draft', 'autowp')
      );

      foreach ($statuses as $status_value => $status_label) {
        $selected = ($status_value === $selected_post_status) ? 'selected' : '';
        echo '<option value="' . esc_attr($status_value) . '" ' . esc_attr($selected) . '>' . esc_html($status_label) . '</option>';
      }
      ?>
    </select>
  </div>
</div>


<!-- Content Image Generation Method -->
<div class="form-group">
  <label class="col-md-4 control-label" for="content_image_generation_method"><?php esc_html_e('Content Image Generation Method', 'autowp'); ?></label>
  <div class="col-md-4">
    <select id="content_image_generation_method" name="content_image_generation_method" class="form-control">
      <?php
      $methods = array(
        'stable_diffusion' => __('Stable Diffusion', 'autowp'),
        'duckduckgo_image_search' => __('DuckDuckGo Image Search', 'autowp')
      );

      $selected_method = isset(unserialize(get_option("autowp_settings"))["content_image_generation_method"]) ? unserialize(get_option("autowp_settings"))["content_image_generation_method"] : 'stable_diffusion';

      foreach ($methods as $method_value => $method_label) {
        $selected = ($method_value === $selected_method) ? 'selected' : '';
        echo '<option value="' . esc_attr($method_value) . '" ' . esc_attr($selected) . '>' . esc_html($method_label) . '</option>';
      }
      ?>
    </select>
    <p class="help-block">
      <?php esc_html_e('This setting defines the method used to generate images within the post content. It is not related to the thumbnail image.', 'autowp'); ?>
    </p>
  </div>
</div>

<!-- Maximum Posts Per Cron -->
<div class="form-group">
    <label class="col-md-4 control-label" for="max_posts_per_cron"><?php esc_html_e('Maximum Posts Per Cron Trigger', 'autowp'); ?></label>
    <div class="col-md-4">
        <select id="max_posts_per_cron" name="max_posts_per_cron" class="form-control">
            <?php 
            $current_value = isset(unserialize(get_option("autowp_settings"))["max_posts_per_cron"]) ? 
                esc_html(unserialize(get_option("autowp_settings"))["max_posts_per_cron"]) : 1;
            ?>
            <option value="1" <?php selected($current_value, 1); ?>>1</option>
            <option value="2" <?php selected($current_value, 2); ?>>2</option>
            <option value="3" <?php selected($current_value, 3); ?>>3</option>
        </select>
        <p class="help-block"><?php esc_html_e('This sets the maximum number of posts that can be generated each time the cron job is triggered. Default: 1.', 'autowp'); ?></p>
    </div>
</div>


<!-- Maximum Posts Per Day -->
<div class="form-group">
    <label class="col-md-4 control-label" for="max_posts_per_day"><?php esc_html_e('Maximum Posts Per Day', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="max_posts_per_day" name="max_posts_per_day" type="number" class="form-control" value="<?php 
        echo isset(unserialize(get_option("autowp_settings"))["max_posts_per_day"]) ? esc_html(unserialize(get_option("autowp_settings"))["max_posts_per_day"]) : 20; 
        ?>" placeholder="<?php esc_html_e('Enter maximum posts per day', 'autowp'); ?>" min="1">
        <p class="help-block"><?php esc_html_e('This sets the maximum number of posts that can be generated within a single day. Default: 20.', 'autowp'); ?></p>
    </div>
</div>

<!-- Spam and Ad Filter -->
<div class="form-group">
    <label class="col-md-4 control-label" for="spam_ad_filter"><?php esc_html_e('Spam and Ad Filter', 'autowp'); ?></label>
    <div class="col-md-4">
        <label class="radio-inline">
            <input type="radio" name="spam_ad_filter" value="1" <?php if(unserialize(get_option("autowp_settings"))["spam_ad_filter"] === '1') { echo 'checked'; } ?>>
            <?php esc_html_e('Active', 'autowp'); ?>
        </label>
        <label class="radio-inline">
            <input type="radio" name="spam_ad_filter" value="0" <?php if(unserialize(get_option("autowp_settings"))["spam_ad_filter"] !== '1') { echo 'checked'; } ?>>
            <?php esc_html_e('Passive', 'autowp'); ?>
        </label>
        <p class="help-block"><?php esc_html_e('When active, content sources will be checked for spam and advertisements before generating posts. Processing will start only if the content passes the spam filter. Default: Passive.', 'autowp'); ?></p>
    </div>
</div>

<!-- Duplicate Content Filter -->
<div class="form-group">
    <label class="col-md-4 control-label" for="duplicate_content_filter"><?php esc_html_e('Similar Content Filter', 'autowp'); ?></label>
    <div class="col-md-4">
        <label class="radio-inline">
            <input type="radio" name="duplicate_content_filter" value="1" <?php if(!isset(unserialize(get_option("autowp_settings"))["duplicate_content_filter"]) || unserialize(get_option("autowp_settings"))["duplicate_content_filter"] === '1') { echo 'checked'; } ?>>
            <?php esc_html_e('Active', 'autowp'); ?>
        </label>
        <label class="radio-inline">
            <input type="radio" name="duplicate_content_filter" value="0" <?php if(isset(unserialize(get_option("autowp_settings"))["duplicate_content_filter"]) && unserialize(get_option("autowp_settings"))["duplicate_content_filter"] === '0') { echo 'checked'; } ?>>
            <?php esc_html_e('Passive', 'autowp'); ?>
        </label>
        <p class="help-block"><?php esc_html_e('When active, the plugin will avoid generating content that is similar to existing posts. Default: Active.', 'autowp'); ?></p>
    </div>
</div>





    <legend><?php esc_html_e('Image Format', 'autowp'); ?></legend>
    <!-- Image Format -->
<div class="form-group">
  <label class="col-md-4 control-label" for="image_format"><?php esc_html_e('Image Format', 'autowp'); ?></label>
  <div class="col-md-4">
    <select id="image_format" name="image_format" class="form-control">
      <?php
      $image_formats = array('png', 'jpeg', 'webp');
      $selected_image_format = isset(unserialize(get_option("autowp_settings"))["image_format"]) ? unserialize(get_option("autowp_settings"))["image_format"] : 'jpeg';

      foreach ($image_formats as $format) {
        $selected = ($format === $selected_image_format) ? 'selected' : '';
        echo '<option value="' . esc_attr($format) . '" ' . esc_attr($selected) . '>' . esc_html(strtoupper($format)) . '</option>';
      }
      ?>
    </select>
  </div>
</div>


    <legend><?php esc_html_e('Stable Diffusion Settings', 'autowp'); ?></legend>
    <!-- Stable Diffusion Style -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="stable_diffusion_style"><?php esc_html_e('Stable Diffusion Style', 'autowp'); ?></label>
      <div class="col-md-4">
        <select id="stable_diffusion_style" name="stable_diffusion_style" class="form-control">
          <?php
          $styles = array('None', 'enhance', 'anime', 'photographic', 'digital-art','comic-book','fantasy-art','line-art','analog-film','neon-punk','isometric','low-poly','origami','modeling-compound','cinematic','3d-model','pixel-art','tile-texture');
          $selected_style = isset(unserialize(get_option("autowp_settings"))["stable_diffusion_style"]) ? unserialize(get_option("autowp_settings"))["stable_diffusion_style"] : 'None';

          foreach ($styles as $style) {
            $selected = ($style === $selected_style) ? 'selected' : '';
            echo '<option value="' . esc_attr($style) . '" ' . esc_attr($selected) . '>' . esc_html($style) . '</option>';
          }
        
          ?>
        </select>
      </div>
    </div>


          <!-- Stable Diffusion Size -->
<div class="form-group">
  <label class="col-md-4 control-label" for="stable_diffusion_size"><?php esc_html_e('Stable Diffusion Size', 'autowp'); ?></label>
  <div class="col-md-4">
    <select id="stable_diffusion_size" name="stable_diffusion_size" class="form-control">
      <?php
      $sd_sizes = array('16:9', '1:1', '21:9', '2:3', '3:2', '4:5', '5:4', '9:16', '9:21');
      $selected_sd_size = isset(unserialize(get_option("autowp_settings"))["stable_diffusion_size"]) ? unserialize(get_option("autowp_settings"))["stable_diffusion_size"] : '16:9';

      foreach ($sd_sizes as $size) {
        $selected = ($size === $selected_sd_size) ? 'selected' : '';
        echo '<option value="' . esc_attr($size) . '" ' . esc_attr($selected) . '>' . esc_html($size) . '</option>';
      }
      ?>
    </select>
  </div>
</div>



<!-- Flux Settings -->
<legend><?php esc_html_e('Flux Settings', 'autowp'); ?></legend>

<!-- Image Size -->
<div class="form-group">
  <label class="col-md-4 control-label" for="flux_image_size"><?php esc_html_e('Image Size', 'autowp'); ?></label>
  <div class="col-md-4">
    <select id="flux_image_size" name="flux_image_size" class="form-control">
      <?php
      $flux_sizes = array('square_hd', 'square', 'portrait_3_4', 'portrait_9_16', 'landscape_4_3', 'landscape_16_9');
      $selected_flux_size = isset(unserialize(get_option("autowp_settings"))["flux_image_size"]) ? unserialize(get_option("autowp_settings"))["flux_image_size"] : 'square_hd';

      foreach ($flux_sizes as $size) {
        $selected = ($size === $selected_flux_size) ? 'selected' : '';
        echo '<option value="' . esc_attr($size) . '" ' . esc_attr($selected) . '>' . esc_html(str_replace('_', ' ', ucfirst($size))) . '</option>';
      }
      ?>
    </select>
  </div>
</div>

<legend><?php esc_html_e('DALL-E Settings', 'autowp'); ?></legend>

<!-- DALL-E 2 Size -->
<div class="form-group">
  <label class="col-md-4 control-label" for="dalle_2_size"><?php esc_html_e('DALL-E 2 Size', 'autowp'); ?></label>
  <div class="col-md-4">
    <select id="dalle_2_size" name="dalle_2_size" class="form-control">
      <?php
      $dalle_2_sizes = array('1024x1024','512x512','256x256');
      $selected_dalle_2_size = isset(unserialize(get_option("autowp_settings"))["dalle_2_size"]) ? unserialize(get_option("autowp_settings"))["dalle_2_size"] : '1024x1024';

      foreach ($dalle_2_sizes as $size) {
        $selected = ($size === $selected_dalle_2_size) ? 'selected' : '';
        echo '<option value="' . esc_attr($size) . '" ' . esc_attr($selected) . '>' . esc_html($size) . '</option>';
      }
      ?>
    </select>
  </div>
</div>


<!-- DALL-E 3 Size -->
<div class="form-group">
  <label class="col-md-4 control-label" for="dalle_3_size"><?php esc_html_e('DALL-E 3 Size', 'autowp'); ?></label>
  <div class="col-md-4">
    <select id="dalle_3_size" name="dalle_3_size" class="form-control">
      <?php
      $dalle_3_sizes = array('1024x1024', '1792x1024', '1024x1792');
      $selected_dalle_3_size = isset(unserialize(get_option("autowp_settings"))["dalle_3_size"]) ? unserialize(get_option("autowp_settings"))["dalle_3_size"] : '1024x1024';

      foreach ($dalle_3_sizes as $size) {
        $selected = ($size === $selected_dalle_3_size) ? 'selected' : '';
        echo '<option value="' . esc_attr($size) . '" ' . esc_attr($selected) . '>' . esc_html($size) . '</option>';
      }
      ?>
    </select>
  </div>
</div>


<!-- DALL-E 3 Style -->
<div class="form-group">
  <label class="col-md-4 control-label" for="dalle_3_style"><?php esc_html_e('DALL-E 3 Style', 'autowp'); ?></label>
  <div class="col-md-4">
    <select id="dalle_3_style" name="dalle_3_style" class="form-control">
      <?php
      $dalle_3_styles = array('natural', 'vivid');
      $selected_dalle_3_style = isset(unserialize(get_option("autowp_settings"))["dalle_3_style"]) ? unserialize(get_option("autowp_settings"))["dalle_3_style"] : 'natural';

      foreach ($dalle_3_styles as $style) {
        $selected = ($style === $selected_dalle_3_style) ? 'selected' : '';
        echo '<option value="' . esc_attr($style) . '" ' . esc_attr($selected) . '>' . esc_html(ucfirst($style)) . '</option>';
      }
      ?>
    </select>
  </div>
</div>




      <!-- Image Motification Settings -->
      <legend><?php esc_html_e('Image Modification Settings', 'autowp'); ?></legend>



       <!-- Image Modification Status -->
       <div class="form-group">
          <label class="col-md-4 control-label" for="image_modification_status"><?php esc_html_e('Image Modification Status', 'autowp'); ?></label>
          <div class="col-md-4">
              <label class="radio-inline">
                  <input type="radio" name="image_modification_status" value="1" <?php if(unserialize(get_option("autowp_settings"))["image_modification_status"] === '1') {echo ' checked'; } ?>><?php esc_html_e('Active', 'autowp'); ?>
              </label>
              <label class="radio-inline">
                  <input type="radio" name="image_modification_status" value="0" <?php if(unserialize(get_option("autowp_settings"))["image_modification_status"] !== '1') {echo ' checked'; } ?>><?php esc_html_e('Inactive', 'autowp'); ?>
              </label>
              <p class="help-block"><?php esc_html_e('If inactive, the images will be published without any modification.', 'autowp'); ?></p>
          </div>
      </div>




      <!-- Nano Banana -->
      <div class="form-group">
          <label class="col-md-4 control-label" for="nano_banana_prompt"><?php esc_html_e('AI Image Editing', 'autowp'); ?></label>
          <div class="col-md-4">
              <input id="nano_banana_prompt" name="nano_banana_prompt" type="text" class="form-control" value="<?php echo esc_html(unserialize(get_option("autowp_settings"))["nano_banana_prompt"]) ?>" placeholder="<?php esc_html_e('Enter the prompt for image editing using Gemini Nano Banana', 'autowp'); ?>">
              <p class="help-block"><?php esc_html_e('You can perform AI-powered image editing using the Gemini Nano Banana model. The image will be modified based on the prompt you enter.

e.g: "Make the image black and white."
"Remove any logos or links in the image."', 'autowp'); ?></p>
          </div>
      </div>


<!-- Image Generating Settings -->
     <!-- Width -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="ai_image_width"><?php esc_html_e('Width', 'autowp'); ?></label>
      <div class="col-md-4">
        <input id="ai_image_width" name="ai_image_width" type="number" class="form-control" value="<?php
         if (isset(unserialize(get_option("autowp_settings"))["ai_image_width"])) {
          echo esc_html(unserialize(get_option("autowp_settings"))["ai_image_width"]);
         } else{
           echo '0'; 
         }
         ?>" placeholder="Enter width">
      </div>
    </div>

    <!-- Height -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="ai_image_height"><?php esc_html_e('Height', 'autowp'); ?></label>
      <div class="col-md-4">
        <input id="ai_image_height" name="ai_image_height" type="number" class="form-control" value="<?php 
        if (isset(unserialize(get_option("autowp_settings"))["ai_image_height"])){
          echo esc_html(unserialize(get_option("autowp_settings"))["ai_image_height"]);
        }else{
          echo '0';
        }  
        ?>" placeholder="Enter height">
        <br><p class="help-block"><?php esc_html_e('If you want to resize the images you can enter a value here. If you do not enter a value here the images will remain in their original size.', 'autowp'); ?></p>
      </div>
    </div>


      <!-- Watermark Link -->
      <div class="form-group">
          <label class="col-md-4 control-label" for="watermark_link"><?php esc_html_e('Watermark Link', 'autowp'); ?></label>
          <div class="col-md-4">
              <input id="watermark_link" name="watermark_link" type="text" class="form-control" value="<?php echo esc_html(unserialize(get_option("autowp_settings"))["watermark_link"]) ?>" placeholder="<?php esc_html_e('Enter the watermark link for post cover images', 'autowp'); ?>">
              <p class="help-block"><?php esc_html_e('Leave it empty if you do not want to add a watermark.', 'autowp'); ?></p>
          </div>
      </div>


      <legend><?php esc_html_e('Social Media Settings', 'autowp'); ?></legend>

      <!-- Social Media Status -->
<div class="form-group">
    <label class="col-md-4 control-label" for="social_media_status"><?php esc_html_e('Social Media Sharing Status', 'autowp'); ?></label>
    <div class="col-md-4">
        <label class="radio-inline">
            <input type="radio" name="social_media_status" value="1" <?php if (unserialize(get_option("autowp_settings"))["social_media_status"] === '1') { echo 'checked'; } ?>>
            <?php esc_html_e('Active', 'autowp'); ?>
        </label>
        <label class="radio-inline">
            <input type="radio" name="social_media_status" value="0" <?php if (unserialize(get_option("autowp_settings"))["social_media_status"] !== '1') { echo 'checked'; } ?>>
            <?php esc_html_e('Inactive', 'autowp'); ?>
        </label>
        <p class="help-block"><?php esc_html_e('Enable or disable social media sharing globally for your posts.', 'autowp'); ?></p>
    </div>
</div>


<!-- Twitter API Key -->
<div class="form-group">
    <label class="col-md-4 control-label" for="twitter_api_key"><?php esc_html_e('Twitter API Key', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="twitter_api_key" name="twitter_api_key" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["twitter_api_key"]); ?>" placeholder="<?php esc_html_e('Enter your Twitter API key', 'autowp'); ?>">
        <p class="help-block"><?php esc_html_e('IMPORTANT! Obtain your Twitter API key from https://developer.twitter.com/en/apps.', 'autowp'); ?></p>
    </div>
</div>

<!-- Telegram API Key -->
<div class="form-group">
    <label class="col-md-4 control-label" for="telegram_api_key"><?php esc_html_e('Telegram API Key', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="telegram_api_key" name="telegram_api_key" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["telegram_api_key"]); ?>" placeholder="<?php esc_html_e('Enter your Telegram API key', 'autowp'); ?>">
        <p class="help-block"><?php esc_html_e('IMPORTANT! Obtain your Telegram API key by creating a bot through BotFather: https://core.telegram.org/bots.', 'autowp'); ?></p>
    </div>
</div>

<!-- Instagram API Key -->
<div class="form-group">
    <label class="col-md-4 control-label" for="instagram_api_key"><?php esc_html_e('Instagram API Key', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="instagram_api_key" name="instagram_api_key" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["instagram_api_key"]); ?>" placeholder="<?php esc_html_e('Enter your Instagram API key', 'autowp'); ?>">
        <p class="help-block"><?php esc_html_e('IMPORTANT! Obtain your Instagram API key by registering an app on the Meta for Developers platform: https://developers.facebook.com.', 'autowp'); ?></p>
    </div>
</div>

      <legend><?php esc_html_e('Server Settings', 'autowp'); ?></legend>

<!-- AutoWP Server Base URL  -->
<div class="form-group">
    <label class="col-md-4 control-label" for="autowp_server_url"><?php esc_html_e('AutoWP Server Base URL', 'autowp'); ?></label>
    <div class="col-md-4">
        <input id="autowp_server_url" name="autowp_server_url" type="text" class="form-control" value="<?php echo esc_attr(unserialize(get_option("autowp_settings"))["autowp_server_url"]); ?>" placeholder="<?php esc_html_e('Enter AutoWP Backend Server Base URL (only if you have LİFETIME package): ', 'autowp'); ?>">
        <p class="help-block"><?php esc_html_e('IMPORTANT WARNING! This field should only be used by users with the SELF HOSTING/LIFETIME package. The AutoWP plugin will not function properly if users with packages other than the Lifetime package change it.', 'autowp'); ?></p>
    </div>
</div>



      <!-- Button -->
      <div class="form-group">
          <label class="col-md-4 control-label" for="singlebutton"></label>
          <div class="col-md-4">
              <button id="singlebutton" name="singlebutton" class="btn btn-primary"><?php esc_html_e('Save', 'autowp'); ?></button>
          </div>
      </div>
  </fieldset>
</form>


<?php
}

function autowp_show_alert_with_message($isSuccess,$message) {
  if ($isSuccess) {
      $alertType = "success";
  } else {
      $alertType = "danger";
  }

  echo '<div class="alert alert-' . esc_html($alertType) . ' alert-dismissible fade show" role="alert">';
  echo esc_html($message);
  echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
  echo '</div>';
}


// Register AJAX action for triggering cron
add_action('wp_ajax_autowp_trigger_cron_now', 'autowp_trigger_cron_now');

function autowp_trigger_cron_now() {
    // Check if the user has the correct capabilities
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('Unauthorized', 'autowp')));
    }

    // Attempt to run the cron job immediately
    if (wp_unschedule_event(time(), 'autowp_cron')) {
        // Schedule it now
        wp_schedule_single_event(time(), 'autowp_cron');

        // Get the next cron time
        $next_cron_time = wp_next_scheduled('autowp_cron');
        $next_cron_time_formatted = $next_cron_time ? date_i18n('Y-m-d H:i:s', $next_cron_time) : __('No scheduled cron event found', 'autowp');

        wp_send_json_success(array('message' => __('Cron triggered successfully!', 'autowp'), 'next_cron_time' => $next_cron_time_formatted));
    } else {
        wp_send_json_error(array('message' => __('Failed to trigger cron.', 'autowp')));
    }
}



function autowp_manual_post_rss_form_page_setOptions($form_data){ 

  




    $autowp_admin_email     = autowp_get_admin_email();
    $autowp_domain_name     = esc_url(get_site_url());
    $website_domainname     = sanitize_url($form_data['domain_name']);
    $website_categories     = '1,2,3';
    $post_count             = 5;
    $post_order             = sanitize_text_field($form_data['post_order']);
    $post_ids               = '';
    $title_prompt           = sanitize_text_field($form_data['title_prompt']);
    $content_prompt         = sanitize_text_field($form_data['content_prompt']);
    $tags_prompt            = sanitize_text_field($form_data['tags_prompt']);
    $image_prompt           = sanitize_text_field($form_data['image_prompt']);

    $image_generating_status = sanitize_text_field($form_data['image_generating_status']);

    $author_selection = sanitize_text_field($form_data['author_selection']) ?? 1;


    $aigenerated_title      = '0';
    $aigenerated_content    = '0';
    $aigenerated_tags       = '0';
    $aigenerated_image      = '0';

    if(isset($form_data['aigenerated_title'])){
      $aigenerated_title      = sanitize_text_field($form_data['aigenerated_title']);
    }

    if(isset($form_data['aigenerated_content'])){
      $aigenerated_content      = sanitize_text_field($form_data['aigenerated_content']);
    }

    if(isset($form_data['aigenerated_tags'])){
      $aigenerated_tags      = sanitize_text_field($form_data['aigenerated_tags']);
    }

    if(isset($form_data['aigenerated_image'])){
      $aigenerated_image      = sanitize_text_field($form_data['aigenerated_image']);
    }



    
    $source_type            = 'rss';
   

    $wordpress_categories = isset($form_data['category_id']) ? array_map('intval', $form_data['category_id']) : array();

    $image_generating_status = sanitize_text_field($form_data['image_generating_status']);

    $prompts_option = get_option('autowp_rewriting_promptscheme');
    $prompt_option_str = strval($prompts_option);
    $rewriting_prompt_scheme = [];
    
    if (!empty($prompts_option)) {
        $rewriting_prompt_scheme = json_encode($prompts_option);
        
        // JSON'dan diziye dönüşüm yapılıyor ve true ile birlikte kullanıldığı için asosiyatif dizi elde ediliyor
    }


    $image_settings = unserialize(get_option('autowp_settings'));
    $image_settings_json = [];

    if(!empty($image_settings)){
      $image_settings_json = json_encode($image_settings);
    }

    

    $user_email = autowp_get_user_email_from_settings();

    $get_data_from_api = autowp_get_posts_from_wp_website($autowp_domain_name,  $user_email, $website_domainname, $website_categories, $post_count,$post_order,$post_ids,$title_prompt,$content_prompt,$tags_prompt,$image_prompt,$aigenerated_title,$aigenerated_content,$aigenerated_tags,$aigenerated_image,$image_settings_json,$source_type,$image_generating_status,$prompt_option_str);
    
    
    $wp_posts = $get_data_from_api['autowp-api'];

   
    if($get_data_from_api['error']){
      update_option('autowp_alerts', $get_data_from_api['error']);
     
    }else{
      update_option('autowp_alerts', '');
      if(!empty($wp_posts)){
       // autowp_show_alert_with_message(true,'Success!');
      }else{
       // autowp_show_alert_with_message(false,'There is no new post from your feed url!');
        update_option('autowp_alerts', 'There is no new post from your feed url!');
      }
    }

    

    foreach($wp_posts as $post){

      $post_ids = $post_ids . ','  . $post['post_id'];
      $post_title = $post['post_title']; 
      $post_content = $post['content'];
      $post_featured_image = $post['preview_image_original'];
      $post_tags = $post['tags'];
      $post_status = $image_settings['post_status'] ?? 'publish';
      $post_author = $author_selection;
      $post_type = 'post';
      $focus_keyword = $post['focus_keyword'];
      $faq_schema = $post['faq_schema'];

      autowp_set_new_post($post_title,$post_content,$post_status,$post_author,$post_type,$post_featured_image, $wordpress_categories,$post_tags,$focus_keyword,$faq_schema);
     
    }

    autowp_update_published_post_ids($post_ids);

   

  
}

function schedule_autowp_manual_post_rss_event($form_data = array()) {
  if (!is_array($form_data) || empty($form_data) || !isset($form_data['website_type'])) {
    return;
  }

  // Schedule the event and pass form data as parameters
  $timestamp = time() + 2;
  wp_schedule_single_event($timestamp, 'autowp_manual_post_rss_event', array($form_data));
}

// Hook this function to run when the form is submitted
add_action('init', 'schedule_autowp_manual_post_rss_event');




function autowp_manual_post_wp_form_page_setOptions($form_data){


    $autowp_admin_email     = autowp_get_admin_email();
    $autowp_domain_name     = esc_url(get_site_url());
    $website_domainname     = sanitize_url($form_data['domain_name']);
    $website_categories     = implode(",", array_map('sanitize_text_field', $form_data['website_category_id']));
    $post_count             = 5;
    $post_order             = sanitize_text_field($form_data['post_order']);
    $post_ids               = '';
    $title_prompt           = sanitize_text_field($form_data['title_prompt']);
    $content_prompt         = sanitize_text_field($form_data['content_prompt']);
    $tags_prompt            = sanitize_text_field($form_data['tags_prompt']);
    $image_prompt           = sanitize_text_field($form_data['image_prompt']);

    $author_selection = sanitize_text_field($form_data['author_selection']) ?? 1;

    $aigenerated_title        = '0';
    $aigenerated_content      = '0';
    $aigenerated_tags         = '0';
    $aigenerated_image        = '0';

    if(isset($form_data['aigenerated_title'])){
      $aigenerated_title      = sanitize_text_field($form_data['aigenerated_title']);
    }

    if(isset($form_data['aigenerated_content'])){
      $aigenerated_content      = sanitize_text_field($form_data['aigenerated_content']);
    }

    if(isset($form_data['aigenerated_tags'])){
      $aigenerated_tags      = sanitize_text_field($form_data['aigenerated_tags']);
    }

    if(isset($form_data['aigenerated_image'])){
      $aigenerated_image      = sanitize_text_field($form_data['aigenerated_image']);
    }


    $image_settings = unserialize(get_option('autowp_settings'));
    $image_settings_json = [];

    if(!empty($image_settings)){
      $image_settings_json = json_encode($image_settings);
    }



    
    $source_type            = 'wordpress';
   

    $wordpress_categories = isset($form_data['category_id']) ? array_map('intval', $form_data['category_id']) : array();

    $image_generating_status = sanitize_text_field($form_data['image_generating_status']);

    $prompts_option = get_option('autowp_rewriting_promptscheme');
    $prompt_option_str = strval($prompts_option);
    $rewriting_prompt_scheme = [];
    
    if (!empty($prompts_option)) {
        $rewriting_prompt_scheme = json_encode($prompts_option);
        
        // JSON'dan diziye dönüşüm yapılıyor ve true ile birlikte kullanıldığı için asosiyatif dizi elde ediliyor
    }

    $user_email = autowp_get_user_email_from_settings();

    $get_data_from_api = autowp_get_posts_from_wp_website($autowp_domain_name, $user_email, $website_domainname, $website_categories, $post_count,$post_order,$post_ids,$title_prompt,$content_prompt,$tags_prompt,$image_prompt,$aigenerated_title,$aigenerated_content,$aigenerated_tags,$aigenerated_image,$image_settings_json,$source_type,$image_generating_status,$prompt_option_str);
    $wp_posts = $get_data_from_api['autowp-api'];




    if($get_data_from_api['error']){
      update_option('autowp_alerts', $get_data_from_api['error']);
     
    }else{
      update_option('autowp_alerts', '');
      if(!empty($wp_posts)){
       // autowp_show_alert_with_message(true,'Success!');
      }else{
       // autowp_show_alert_with_message(false,'There is no new post from your feed url!');
        update_option('autowp_alerts', 'There is no new post from your feed url!');
      }
    }


    

    foreach($wp_posts as $post){

      $post_ids = $post_ids . ','  . $post['post_id'] . $post['slug'];
      $post_title = $post['post_title']; 
      $post_content = $post['content'];
      $post_featured_image = $post['preview_image_original'];
      $post_tags = $post['tags'];
      $post_status = $image_settings['post_status'] ?? 'publish';
      $post_author = $author_selection;
      $post_type = 'post';
      $focus_keyword = $post['focus_keyword'];
      $faq_schema = $post['faq_schema'];

      
      autowp_set_new_post($post_title,$post_content,$post_status,$post_author,$post_type,$post_featured_image, $wordpress_categories,$post_tags,$focus_keyword,$faq_schema);

     
    }

    autowp_update_published_post_ids($post_ids);







  

}

function autowp_manual_post_news_form_page_setOptions($form_data){


    $autowp_admin_email     = autowp_get_admin_email();
    $autowp_domain_name     = esc_url(get_site_url());
    $website_domainname     = sanitize_url($form_data['domain_name']);
    $website_categories     = '1,2,3';
    $post_count             = 5;
    $post_order             = sanitize_text_field($form_data['post_order']);
    $post_ids               = '';
    $title_prompt           = sanitize_text_field($form_data['title_prompt']);
    $content_prompt         = sanitize_text_field($form_data['content_prompt']);
    $tags_prompt            = sanitize_text_field($form_data['tags_prompt']);
    $image_prompt           = sanitize_text_field($form_data['image_prompt']);

    $author_selection = sanitize_text_field($form_data['author_selection']) ?? 1;

    $aigenerated_title        = '0';
    $aigenerated_content      = '0';
    $aigenerated_tags         = '0';
    $aigenerated_image        = '0';

    if(isset($form_data['aigenerated_title'])){
      $aigenerated_title      = sanitize_text_field($form_data['aigenerated_title']);
    }

    if(isset($form_data['aigenerated_content'])){
      $aigenerated_content      = sanitize_text_field($form_data['aigenerated_content']);
    }

    if(isset($form_data['aigenerated_tags'])){
      $aigenerated_tags      = sanitize_text_field($form_data['aigenerated_tags']);
    }

    if(isset($form_data['aigenerated_image'])){
      $aigenerated_image      = sanitize_text_field($form_data['aigenerated_image']);
    }


    $image_settings = unserialize(get_option('autowp_settings'));
    $image_settings_json = [];

    if(!empty($image_settings)){
      $image_settings_json = json_encode($image_settings);
    }



    
    $source_type            = 'news';
   

    $wordpress_categories = isset($form_data['category_id']) ? array_map('intval', $form_data['category_id']) : array();

    $image_generating_status = sanitize_text_field($form_data['image_generating_status']);

   

    $news_keyword = sanitize_text_field($form_data['news_keyword']); // New field for News website
    $news_country = sanitize_text_field($form_data['news_country']);
    $news_language = sanitize_text_field($form_data['news_language']);
    $news_time_published = sanitize_text_field($form_data['news_time_published']);

    $prompts_option = get_option('autowp_rewriting_promptscheme');
    $prompt_option_str = strval($prompts_option);
    $rewriting_prompt_scheme = [];
    
    if (!empty($prompts_option)) {
        $rewriting_prompt_scheme = json_encode($prompts_option);
        
        // JSON'dan diziye dönüşüm yapılıyor ve true ile birlikte kullanıldığı için asosiyatif dizi elde ediliyor
    }

    $user_email = autowp_get_user_email_from_settings();


    $get_data_from_api = autowp_get_posts_from_wp_website($autowp_domain_name, $user_email, $website_domainname, $website_categories, $post_count,$post_order,$post_ids,$title_prompt,$content_prompt,$tags_prompt,$image_prompt,$aigenerated_title,$aigenerated_content,$aigenerated_tags,$aigenerated_image,$image_settings_json,$source_type,$image_generating_status,$news_keyword,$news_country,$news_language,$news_time_published,$prompt_option_str);
    $wp_posts = $get_data_from_api['autowp-api'];




    if($get_data_from_api['error']){
      update_option('autowp_alerts', $get_data_from_api['error']);
     
    }else{
      update_option('autowp_alerts', '');
      if(!empty($wp_posts)){
       // autowp_show_alert_with_message(true,'Success!');
      }else{
       // autowp_show_alert_with_message(false,'There is no new post from your feed url!');
        update_option('autowp_alerts', 'There is no new post from your feed url!');
      }
    }


    

    foreach($wp_posts as $post){

      $post_ids = $post_ids . ','  . $post['post_id'] . $post['slug'];
      $post_title = $post['post_title']; 
      $post_content = $post['content'];
      $post_featured_image = $post['preview_image_original'];
      $post_tags = $post['tags'];
      $post_status = $image_settings['post_status'] ?? 'publish';
      $post_author = $author_selection;
      $post_type = 'post';
      $focus_keyword = $post['focus_keyword'];
      $faq_schema = $post['faq_schema'];

      
      autowp_set_new_post($post_title,$post_content,$post_status,$post_author,$post_type,$post_featured_image, $wordpress_categories,$post_tags,$focus_keyword,$faq_schema);

     
    }

    autowp_update_published_post_ids($post_ids);







}




function autowp_manual_post_news_form_page_handler(){
  //autowp_manual_post_news_form_page_setOptions();

  if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'autowp_manual_post_news_nonce')) {

    $form_data = $_POST;

    $form_data['website_type'] = 'news';

    $form_data['content_prompt'] = '[autowp-rewriting-promptcode]' . 
    sanitize_text_field($form_data['languageSelect']) . ',' .
    sanitize_text_field($form_data['subtitleSelect']) . ',' .
    sanitize_text_field($form_data['narrationSelect']) .
    '[/autowp-rewriting-promptcode]';

    $validate_form = autowp_validate_website($form_data,true);
    
    

    if($validate_form === true){
      autowp_show_alert_with_message(true,'Your process successfully started!');
      schedule_autowp_manual_post_rss_event($form_data);
    }else{

      
      autowp_show_alert_with_message(false,$validate_form);
      
    }


  

  }

   ?>
  <div class="wrap">
      <h1>Manual Post - News</h1>
      <a href="javascript:history.back()" class="btn btn-primary">Go Back</a>

      <div id="poststuff">
          <div id="post-body" class="metabox-holder columns-2">
              <!-- Main Content -->
              <div id="post-body-content">
                  <div class="meta-box-sortables ui-sortable">
                      <div class="postbox">
                          <h2 class="hndle ui-sortable-handle">Manual News Settings</h2>
                          <div class="inside">
                              <div id="loading">
                                  <div class="loader">
                                      <div class="inner one"></div>
                                      <div class="inner two"></div>
                                      <div class="inner three"></div>
                                  </div>
                              </div>
                              <form id="autowp_manual_post_form" method="post">
                              <?php wp_nonce_field('autowp_manual_post_news_nonce', '_wpnonce'); ?>

                                  <div class="form2bc">
                                  <div class="container">
    <form class="row g-3" id="post_generation_form">
    <div class="col-md-6">
            <label for="website_name" class="form-label">Name:</label>
            <input id="website_name" name="website_name" type="text" value="" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="author_selection">Author Selection:</label>
            <select name="author_selection" id="author_selection" class="form-select">
                <?php
                $authors = get_users();

                foreach ($authors as $author) {
                    $author_id = $author->ID;
                    $author_name = $author->display_name;

                    echo '<option value="' . esc_attr($author_id) . '">' . esc_html($author_name) . '</option>';
                }
                ?>
            </select>
        </div>
        
        <div class="col-md-6">
            <label for="category_id" class="form-label">Categories:</label>
            <select id="category_id" name="category_id[]" class="form-select" required multiple>
                <?php
                

                $categories = get_categories(array(
                    'orderby' => 'name',
                    'order'   => 'ASC',
                    'hide_empty' => false
                ));

                foreach ($categories as $category) {
                    
                    echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="news_keyword" class="form-label">Keyword:</label>
            <input id="news_keyword" name="news_keyword" type="text" value="" class="form-control">
        </div>

        <div class="col-md-6">
    <label class="form-label" for="news_country">News Country:</label>
    <select name="news_country" class="form-select">
        <!-- ISO 3166-1 alpha-2 country codes -->
        <!-- Replace with actual country codes and names -->
        <?php
        $countries = [
            'any'   => 'Anywhere',
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
        ];

        foreach ($countries as $code => $name) {
            
          echo '<option value="' . esc_attr($code) . '">' . esc_html($name) . '</option>';
        }
        ?>
    </select>
</div>


<div class="col-md-6">
    <label class="form-label" for="news_language">Language:</label>
    <select name="news_language" class="form-select">
        <!-- ISO 639-1 alpha-2 language codes -->
        <!-- Replace with actual language codes and names -->
        <?php
        $languages = [
            'any' => 'Any Language',
            'af' => 'Afrikaans',
            'sq' => 'Albanian',
            'am' => 'Amharic',
            'ar' => 'Arabic',
            'hy' => 'Armenian',
            'az' => 'Azerbaijani',
            'eu' => 'Basque',
            'be' => 'Belarusian',
            'bn' => 'Bengali',
            'bs' => 'Bosnian',
            'bg' => 'Bulgarian',
            'ca' => 'Catalan',
            'ceb' => 'Cebuano',
            'ny' => 'Chichewa',
            'zh-CN' => 'Chinese',
            'co' => 'Corsican',
            'hr' => 'Croatian',
            'cs' => 'Czech',
            'da' => 'Danish',
            'nl' => 'Dutch',
            'en' => 'English',
            'eo' => 'Esperanto',
            'et' => 'Estonian',
            'tl' => 'Filipino',
            'fi' => 'Finnish',
            'fr' => 'French',
            'fy' => 'Frisian',
            'gl' => 'Galician',
            'ka' => 'Georgian',
            'de' => 'German',
            'el' => 'Greek',
            'gu' => 'Gujarati',
            'ht' => 'Haitian Creole',
            'ha' => 'Hausa',
            'haw' => 'Hawaiian',
            'iw' => 'Hebrew',
            'hi' => 'Hindi',
            'hmn' => 'Hmong',
            'hu' => 'Hungarian',
            'is' => 'Icelandic',
            'ig' => 'Igbo',
            'id' => 'Indonesian',
            'ga' => 'Irish',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'jw' => 'Javanese',
            'kn' => 'Kannada',
            'kk' => 'Kazakh',
            'km' => 'Khmer',
            'ko' => 'Korean',
            'ku' => 'Kurdish (Kurmanji)',
            'ky' => 'Kyrgyz',
            'lo' => 'Lao',
            'la' => 'Latin',
            'lv' => 'Latvian',
            'lt' => 'Lithuanian',
            'lb' => 'Luxembourgish',
            'mk' => 'Macedonian',
            'mg' => 'Malagasy',
            'ms' => 'Malay',
            'ml' => 'Malayalam',
            'mt' => 'Maltese',
            'mi' => 'Maori',
            'mr' => 'Marathi',
            'mn' => 'Mongolian',
            'my' => 'Myanmar (Burmese)',
            'ne' => 'Nepali',
            'no' => 'Norwegian',
            'ps' => 'Pashto',
            'fa' => 'Persian',
            'pl' => 'Polish',
            'pt' => 'Portuguese',
            'pa' => 'Punjabi',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'sm' => 'Samoan',
            'gd' => 'Scots Gaelic',
            'sr' => 'Serbian',
            'st' => 'Sesotho',
            'sn' => 'Shona',
            'sd' => 'Sindhi',
            'si' => 'Sinhala',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'so' => 'Somali',
            'es' => 'Spanish',
            'su' => 'Sundanese',
            'sw' => 'Swahili',
            'sv' => 'Swedish',
            'tg' => 'Tajik',
            'ta' => 'Tamil',
            'te' => 'Telugu',
            'th' => 'Thai',
            'tr' => 'Turkish',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            'vi' => 'Vietnamese',
            'cy' => 'Welsh',
            'xh' => 'Xhosa',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'zu' => 'Zulu',
        ];

        foreach ($languages as $code => $name) {
            
          echo '<option value="' . esc_attr($code) . '">' . esc_html($name) . '</option>';
        }
        ?>
    </select>
</div>

<div class="col-md-6">
    <label class="form-label" for="news_time_published">Time Published:</label>
    <select name="news_time_published" class="form-select">
        <?php
        $time_options = [
            'anytime' => 'Anytime',
            '1h' => 'Last Hour',
            '1d' => 'Last Day',
            '7d' => 'Last 7 Days',
            '1y' => 'Last Year',
        ];

        foreach ($time_options as $value => $label) {
          echo '<option value="' . esc_attr($value) . '">' . esc_html($label) . '</option>';
        }
        ?>
    </select>
</div>


        <div class="col-md-6">
            <label for="domain_name" class="form-label">Source URL (optional):</label>
            <input id="domain_name" name="domain_name" type="text" value="" class="form-control">
        </div>
<br>
<p>If you want to get news from a specific source website, enter the site's address here. It is not mandatory to fill in this field.</p>

       
        <div class="col-md-6">
    <label class="form-label" for="image_generating_status"><?php esc_html_e('Image Generating Method', 'autowp'); ?></label>
    <select name="image_generating_status" class="form-select">
        <option value="0"><?php esc_html_e('FLUX Realism LoRA', 'autowp'); ?></option>
        <option value="1"><?php esc_html_e('Stable Diffusion Ultra', 'autowp'); ?></option>
        <option value="2"><?php esc_html_e('Stable Diffusion Core', 'autowp'); ?></option>
        <option value="3"><?php esc_html_e('DALL-E 2', 'autowp'); ?></option>
        <option value="4"><?php esc_html_e('DALL-E 3', 'autowp'); ?></option>
        <option value="5"><?php esc_html_e('DuckDuckGo Search', 'autowp'); ?></option>

        <option value="6"><?php esc_html_e('Default Image', 'autowp'); ?></option>
        <option value="7"><?php esc_html_e('No Image', 'autowp'); ?></option>
    </select>
    <p class="form-text"><?php esc_html_e('By default FLUX Realism LoRA is selected.', 'autowp'); ?></p>
</div>


        <div class="col-md-6">
            <label for="post_order" class="form-label">Post Order:</label>
            <select id="post_order" name="post_order" class="form-select">
                <option value="desc">Latest First</option>
                <option value="asc">Oldest First</option>
                <option value="rand">Random</option>
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
    <option value="<?php echo esc_attr($language); ?>">
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
          echo '<option value="' . esc_attr($i) . '">' . esc_html($i) . '</option>';
        }
        ?>
    </select>
</div>
<br>
<div class="col-md-6">
    <label for="narrationSelect" class="form-label">Writing Style:</label>
    <select class="form-select" id="narrationSelect" name="narrationSelect">
        <?php
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
            echo '<option value="' . esc_attr($value) . '">' . esc_html($name) . '</option>';
        }
        ?>

    </select>
    <br>
</div>



        <div class="mb-3">
                                                  <p class="submit">
                                                      <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Generate Post">
                                                  </p>
                                              </div>
        
    </form>
</div>

                              </form>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- Sidebar -->
              <div id="postbox-container-1" class="postbox-container">
                  <!-- Sidebar content here (if any) -->
              </div>
          </div>
          <br class="clear">
      </div>
  </div>
  <?php

}


function get_next_cron_time($cron_id) {
  // Önce, wpcron ID'ye ait cron işleminin zamanlamasını alalım
  $next_cron_timestamp = wp_next_scheduled($cron_id);

  // Eğer bir sonraki tetiklenme zamanı varsa, tarih ve saat formatında geri döndürelim
  if ($next_cron_timestamp) {
      return date('Y-m-d H:i:s', $next_cron_timestamp);
  } else {
      // Eğer bir sonraki tetiklenme zamanı yoksa, boş bir değer döndürelim veya hata durumuna göre düzenleyebilirsiniz
      return 'Bir sonraki tetiklenme zamanı bulunamadı.';
  }
}


function autowp_manual_post_wp_form_page_handler() {
  //autowp_manual_post_wp_form_page_setOptions();

  if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'autowp_manual_post_wp_nonce')) {

    $form_data = $_POST;

    $form_data['website_type'] = 'wordpress';

    $form_data['content_prompt'] = '[autowp-rewriting-promptcode]' . 
    sanitize_text_field($_POST['languageSelect']) . ',' .
    sanitize_text_field($_POST['subtitleSelect']) . ',' .
    sanitize_text_field($_POST['narrationSelect']) .
    '[/autowp-rewriting-promptcode]';

    $validate_form = autowp_validate_website($form_data,true);
    
    

    if($validate_form === true){
      autowp_show_alert_with_message(true,'Your process successfully started!');
      schedule_autowp_manual_post_rss_event($form_data);
    }else{

      
      autowp_show_alert_with_message(false,$validate_form);
      
    }


  

  }

  ?>
  <div class="wrap">
      <h1>Manual Post - Wordpress</h1>
      <a href="javascript:history.back()" class="btn btn-primary">Go Back</a>

      <div id="poststuff">
          <div id="post-body" class="metabox-holder columns-2">
              <!-- Main Content -->
              <div id="post-body-content">
                  <div class="meta-box-sortables ui-sortable">
                      <div class="postbox">
                          <h2 class="hndle ui-sortable-handle">Wordpress Settings</h2>
                          <div class="inside">
                              <div id="loading">
                                  <div class="loader">
                                      <div class="inner one"></div>
                                      <div class="inner two"></div>
                                      <div class="inner three"></div>
                                  </div>
                              </div>
                              <form id="autowp_manual_post_form" method="post">
                              <?php wp_nonce_field('autowp_manual_post_wp_nonce', '_wpnonce'); ?>

                                  <div class="form2bc">
                                  <div class="container">
    <form class="row g-3" id="post_generation_form">
        <div class="col-md-6">
            <label for="website_name" class="form-label">Website Name:</label>
            <?php
            $autowp_admin_email = autowp_get_admin_email();
            $autowp_domain_name = esc_url(get_site_url());
            $is_empty = empty($item['domain_name']);
            ?>
            <input type="hidden" id="autowp_admin_email" value="<?= esc_attr($autowp_admin_email) ?>">
            <input type="hidden" id="autowp_domain_name" value="<?= esc_attr($autowp_domain_name) ?>">
            <input id="website_name" name="website_name" type="text" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="domain_name" class="form-label">Domain Name:</label>
            <input id="domain_name" name="domain_name" type="text" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="author_selection" class="form-label"><?php esc_html_e('Author Selection', 'autowp'); ?></label>
            <select name="author_selection" class="form-select">
                <?php
                $authors = get_users();
                foreach ($authors as $author) {
                    $author_id = $author->ID;
                    $author_name = $author->display_name;
                    $author_description = get_the_author_meta('description', $author_id);
                    echo '<option value="' . esc_attr($author_id) . '">' . esc_html($author_name) . '</option>';
                }
                ?>
            </select>
            <p class="form-text"><?php esc_html_e('Select an author from the list.', 'autowp'); ?></p>
        </div>

        <div class="col-md-6">
            <label for="website_category_id" class="form-label">Website Categories:</label>
            <br>
            <select id="website_category_id" name="website_category_id[]" multiple style="display: none;"></select>
            <button type="button" class="btn btn-primary" onclick="refreshWebsiteCategories()">
                <i class="bi bi-arrow-clockwise"></i>
                Get Categories
            </button>
        </div>
<br>
        <div class="col-md-6">
            <label for="category_id" class="form-label">Categories:</label>
            <select id="category_id" name="category_id[]" required multiple class="form-select">
                <?php
                $categories = get_categories(array(
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'hide_empty' => false
                ));

                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                }
                ?>
            </select>
        </div>

    

        <div class="col-md-6">
    <label class="form-label" for="image_generating_status"><?php esc_html_e('Image Generating Method', 'autowp'); ?></label>
    <select name="image_generating_status" class="form-select">
        <option value="0"><?php esc_html_e('FLUX Realism LoRA', 'autowp'); ?></option>
        <option value="1"><?php esc_html_e('Stable Diffusion Ultra', 'autowp'); ?></option>
        <option value="2"><?php esc_html_e('Stable Diffusion Core', 'autowp'); ?></option>
        <option value="3"><?php esc_html_e('DALL-E 2', 'autowp'); ?></option>
        <option value="4"><?php esc_html_e('DALL-E 3', 'autowp'); ?></option>
        <option value="5"><?php esc_html_e('DuckDuckGo Search', 'autowp'); ?></option>
        <option value="6"><?php esc_html_e('Default Image', 'autowp'); ?></option>
        <option value="7"><?php esc_html_e('No Image', 'autowp'); ?></option>
        <option value="8"><?php esc_html_e('Original Image', 'autowp'); ?></option>
    </select>
    <p class="form-text"><?php esc_html_e('By default FLUX Realism LoRA is selected.', 'autowp'); ?></p>
</div>



        <div class="col-md-6">
            <label for="post_order" class="form-label">Post Order:</label>
            <select id="post_order" name="post_order" class="form-select">
                <option value="desc">Latest First</option>
                <option value="asc">Oldest First</option>
                <option value="rand">Random</option>
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
    <option value="<?php echo esc_attr($language); ?>">
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
            
            echo '<option value="' . esc_attr($i) . '">' . esc_html($i) . '</option>';
        }
        ?>
    </select>
</div>
<br>
<div class="col-md-6">
    <label for="narrationSelect" class="form-label">Writing Style:</label>
    <select class="form-select" id="narrationSelect" name="narrationSelect">
        <?php
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
            echo '<option value="' . esc_attr($value) . '">' . esc_html($name) . '</option>';
        }
        ?>

    </select>
    
</div>

        <div class="col-md-12">
            <p class="message">For prompt examples, visit <a class="link" href="https://www.aiprm.com/prompts/">aiprm.com/prompts</a></p>
        </div>
        <div class="col-12">
            <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Generate Post">
        </div>
    </form>
</div>

                              </form>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- Sidebar -->
              <div id="postbox-container-1" class="postbox-container">
                  <!-- Sidebar content here (if any) -->
              </div>
          </div>
          <br class="clear">
      </div>
  </div>
  <?php
}



function autowp_manual_post_rss_scheduled_event($form_data) {
  if (!is_array($form_data) || !isset($form_data['website_type'])) {
    return;
  }

  if($form_data['website_type'] === 'rss'){
    // Call the function with the form data
    autowp_manual_post_rss_form_page_setOptions($form_data);
  }else if ( $form_data['website_type'] === 'wordpress'){
    autowp_manual_post_wp_form_page_setOptions($form_data);

  }else if ($form_data['website_type'] === 'news'){
    autowp_manual_post_news_form_page_setOptions($form_data);
  }else if ($form_data['website_type'] === 'ai'){
    autowp_manual_post_ai_form_page_setOptions($form_data);
  }else if ($form_data['website_type'] === 'agenticscraper') {
    autowp_manual_post_agenticscraper_form_page_setOptions($form_data);

  }
  
}


// Hook this function to run when the scheduled event occurs
add_action('autowp_manual_post_rss_event', 'autowp_manual_post_rss_scheduled_event');






function autowp_manual_post_rss_form_page_handler() {

  if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'autowp_manual_post_rss_nonce')) {

    $form_data = $_POST;

    $form_data['website_type'] = 'rss';


    $form_data['content_prompt'] ='[autowp-rewriting-promptcode]' . 
    sanitize_text_field($form_data['languageSelect']) . ',' .
    sanitize_text_field($form_data['subtitleSelect']) . ',' .
    sanitize_text_field($form_data['narrationSelect']) .
    '[/autowp-rewriting-promptcode]';

    $validate_form = autowp_validate_website($form_data,true);
    
    

    if($validate_form === true){
      autowp_show_alert_with_message(true,'Your process successfully started!');
      schedule_autowp_manual_post_rss_event($form_data);
    }else{

      
      autowp_show_alert_with_message(false,$validate_form);
      
    }


  

  }
  ?>

<div class="wrap">
  <h1>Manual Post - RSS</h1>
  <a href="javascript:history.back()" class="btn btn-primary">Go Back</a>

  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <!-- Main Content -->
      <div id="post-body-content">
        <div class="meta-box-sortables ui-sortable">
          <div class="postbox">
            <h2 class="hndle ui-sortable-handle">RSS Settings</h2>
            <div class="inside">
              <div id="loading">
                <div class="loader">
                  <div class="inner one"></div>
                  <div class="inner two"></div>
                  <div class="inner three"></div>
                </div>
              </div>
              <form id="autowp_manual_post_form" method="post">
              <?php wp_nonce_field('autowp_manual_post_rss_nonce', '_wpnonce'); ?>
                <tbody>
                  <div class="form2bc">
                   
                  <div class="container">
    <form class="row g-3" id="post_generation_form">
        <div class="col-md-6">
            <label for="domain_name" class="form-label">RSS Feed URL:</label>
            <input id="domain_name" name="domain_name" type="text" class="form-control" value="" required>
            <p class="form-text"><?php esc_html_e('E.g https://kelimelerbenim.com/feed', 'autowp'); ?></p>
        </div>

        <div class="col-md-6">
            <label for="category_id" class="form-label">Categories:</label>
            <select id="category_id" name="category_id[]" required multiple class="form-select">
                <?php
                $categories = get_categories(array(
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                    'hide_empty' => false
                ));

                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="author_selection" class="form-label"><?php esc_html_e('Author Selection', 'autowp'); ?></label>
            <select name="author_selection" class="form-select">
                <?php
                $authors = get_users();
                foreach ($authors as $author) {
                    $author_id = $author->ID;
                    $author_name = $author->display_name;
                    $author_description = get_the_author_meta('description', $author_id);
                    echo '<option value="' . esc_attr($author_id) . '">' . esc_html($author_name) . '</option>';
                }
                ?>
            </select>
            <p class="form-text"><?php esc_html_e('Select an author from the list.', 'autowp'); ?></p>
        </div>

  

        <div class="col-md-6">
    <label class="form-label" for="image_generating_status"><?php esc_html_e('Image Generating Method', 'autowp'); ?></label>
    <select name="image_generating_status" class="form-select">
        <option value="0"><?php esc_html_e('FLUX Realism LoRA', 'autowp'); ?></option>
        <option value="1"><?php esc_html_e('Stable Diffusion Ultra', 'autowp'); ?></option>
        <option value="2"><?php esc_html_e('Stable Diffusion Core', 'autowp'); ?></option>
        <option value="3"><?php esc_html_e('DALL-E 2', 'autowp'); ?></option>
        <option value="4"><?php esc_html_e('DALL-E 3', 'autowp'); ?></option>
        <option value="5"><?php esc_html_e('DuckDuckGo Search', 'autowp'); ?></option>
        <option value="6"><?php esc_html_e('Default Image', 'autowp'); ?></option>
        <option value="7"><?php esc_html_e('No Image', 'autowp'); ?></option>
        
    </select>
    <p class="form-text"><?php esc_html_e('By default FLUX Realism LoRA is selected. ', 'autowp'); ?></p>
</div>



        <div class="col-md-6">
            <label for="post_order" class="form-label">Post Order:</label>
            <select id="post_order" name="post_order" class="form-select">
                <option value="desc">Latest First</option>
                <option value="asc">Oldest First</option>
                <option value="rand">Random</option>
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
    <option value="<?php echo esc_attr($language); ?>">
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
            echo '<option value="' . esc_attr($i) . '">' . esc_html($i) . '</option>';
        }
        ?>
    </select>
</div>
<br>
<div class="col-md-6">
    <label for="narrationSelect" class="form-label">Writing Style:</label>
    <select class="form-select" id="narrationSelect" name="narrationSelect">
        <?php
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
            echo '<option value="' . esc_attr($value) . '">' . esc_html($name) . '</option>';
        }
        ?>

    </select>
    
</div>

        <div class="col-md-12">
            <p class="message">For prompt examples, visit <a class="link" href="https://www.aiprm.com/prompts/">aiprm.com/prompts</a></p>
        </div>
        <div class="col-12">
        <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Generate Post">
                    </div>
       
    </form>
</div>

                  </div>
                </tbody>
                
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Sidebar -->
      <div id="postbox-container-1" class="postbox-container">
        <!-- Sidebar content here (if any) -->
      </div>
    </div>
    <br class="clear">
  </div>
</div>


<?php
}

function autowp_manual_post_agenticscraper_form_page_setOptions($form_data) {

  // Temel ayarlar
  $autowp_admin_email   = autowp_get_admin_email();
  $autowp_domain_name   = esc_url(get_site_url());
  $website_domainname   = '';
  $website_categories   = '';
  $post_count           = '';
  $post_order           = '';
  $post_ids             = '';

  // Başlık, etiket ve resim promptları kullanılmıyor
  $title_prompt         = '';
  $tags_prompt          = '';
  $image_prompt         = '';

  // Kaynak tipi özel formumuz için
  $source_type          = 'agenticscraper';

  // Yazar seçimi (varsayılan 1)
  $author_selection     = isset($form_data['author_selection']) ? sanitize_text_field($form_data['author_selection']) : 1;

  // İçerik promptunda kullanılacak uzun açıklama (long description)
  $long_description = sanitize_textarea_field($form_data['long_description']);

  $keywordInput   = isset($form_data['keywordInput']) ? sanitize_text_field($form_data['keywordInput']) : '';
  $languageSelect = isset($form_data['languageSelect']) ? sanitize_text_field($form_data['languageSelect']) : '';
  $subtitleSelect = isset($form_data['subtitleSelect']) ? intval($form_data['subtitleSelect']) : 1;
  $narrationSelect = isset($form_data['narrationSelect']) ? sanitize_text_field($form_data['narrationSelect']) : '';


  $wordpress_categories = isset($form_data['category_id']) ? array_map('intval', $form_data['category_id']) : array();


  // Varsayılanlar (başlık, içerik, etiketler için)
  $aigenerated_title    = '1';
  $aigenerated_content  = '1';
  $aigenerated_tags     = '1';
  $aigenerated_image    = '1';

  if(isset($form_data['aigenerated_image'])){
    $aigenerated_image      = sanitize_text_field($form_data['aigenerated_image']);
  }

  // Resim ayarlarını alıyoruz
  $image_settings = unserialize(get_option('autowp_settings'));
  $image_settings_json = !empty($image_settings) ? json_encode($image_settings) : '';

  // Bu formda resim oluşturma durumu kullanılmayabilir
  $image_generating_status = sanitize_text_field($form_data['image_generating_status']);

  // Yeniden yazım prompt şeması
  $prompts_option   = get_option('autowp_rewriting_promptscheme');
  $prompt_option_str = strval($prompts_option);

  // Kullanıcı e-postasını alıyoruz
  $user_email = autowp_get_user_email_from_settings();

  /*
   * Özel Araçlar (Custom Tools) Ayarları
   */
  // Website Tools: Knowledge Base URL (varsa, geçerli URL olmalı)
  $website_tools_knowledge_base_url = !empty($form_data['website_tools_knowledge_base_url'])
      ? sanitize_url($form_data['website_tools_knowledge_base_url'])
      : '';

  // DuckDuckGO Search ayarları
  $duckduckgo_news = isset($form_data['duckduckgo_news']) ? '1' : '0';
  $duckduckgo_fixed_max_results = !empty($form_data['duckduckgo_fixed_max_results'])
      ? intval($form_data['duckduckgo_fixed_max_results'])
      : null;

  // Wikipedia ayarları: virgülle ayrılmış konu listesi
  $wikipedia_knowledge_base = !empty($form_data['wikipedia_knowledge_base'])
      ? sanitize_text_field($form_data['wikipedia_knowledge_base'])
      : '';

  // YFinanceTools ayarları
  $yfinance_stock_price              = isset($form_data['yfinance_stock_price']) ? '1' : '0';
  $yfinance_company_info             = isset($form_data['yfinance_company_info']) ? '1' : '0';
  $yfinance_stock_fundamentals       = isset($form_data['yfinance_stock_fundamentals']) ? '1' : '0';
  $yfinance_income_statements        = isset($form_data['yfinance_income_statements']) ? '1' : '0';
  $yfinance_key_financial_ratios     = isset($form_data['yfinance_key_financial_ratios']) ? '1' : '0';
  $yfinance_analyst_recommendations  = isset($form_data['yfinance_analyst_recommendations']) ? '1' : '0';
  $yfinance_company_news             = isset($form_data['yfinance_company_news']) ? '1' : '0';
  $yfinance_technical_indicators    = isset($form_data['yfinance_technical_indicators']) ? '1' : '0';
  $yfinance_historical_prices        = isset($form_data['yfinance_historical_prices']) ? '1' : '0';


   
  // Toggles for custom tools and knowledge base
  $enable_website_tools = isset($form_data['enable_website_tools']) ? '1' : '0';
  $enable_duckduckgo    = isset($form_data['enable_duckduckgo']) ? '1' : '0';
  $enable_wikipedia     = isset($form_data['enable_wikipedia']) ? '1' : '0';
  $enable_yfinancetools = isset($form_data['enable_yfinancetools']) ? '1' : '0';
  $enable_hackernews    = isset($form_data['enable_hackernews']) ? '1' : '0';
  
  $enable_pdf_kb = isset($form_data['enable_pdf_kb']) ? '1' : '0';
  $enable_csv_kb = isset($form_data['enable_csv_kb']) ? '1' : '0';
  $enable_text_kb = isset($form_data['enable_text_kb']) ? '1' : '0';


  // Hacker News ayarları
  $hackernews_get_top_stories  = isset($form_data['hackernews_get_top_stories']) ? '1' : '0';
  $hackernews_get_user_details = isset($form_data['hackernews_get_user_details']) ? '1' : '0';

  // Özel araç ayarlarını diziye ekleyelim
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

  /*
   * Knowledge Base Ayarları
   */
  $pdf_url_knowledge_base = !empty($form_data['pdf_url_knowledge_base'])
      ? sanitize_url($form_data['pdf_url_knowledge_base'])
      : '';
  $csv_url_knowledge_base = !empty($form_data['csv_url_knowledge_base'])
      ? sanitize_url($form_data['csv_url_knowledge_base'])
      : '';
  $text_knowledge_base    = !empty($form_data['text_knowledge_base'])
      ? sanitize_textarea_field($form_data['text_knowledge_base'])
      : '';

  $knowledge_base = [
      'pdf_url' => $pdf_url_knowledge_base,
      'csv_url' => $csv_url_knowledge_base,
      'text'    => $text_knowledge_base,
  ];

  /*
   * Ekstra parametreleri içerik promptu içinde JSON formatında birleştiriyoruz.
   * Böylece API çağrısında content_prompt içerisinde hem long description hem de
   * custom tools ve knowledge base ayarlarını gönderebiliyoruz.
   */
  $combined_prompt = [
    'content'        => $long_description,
    'keyword'        => $keywordInput,
    'language'       => $languageSelect,
    'subheading_count' => $subtitleSelect,
    'writing_style'  => $narrationSelect,
    'custom_tools'   => $custom_tools,
    'knowledge_base' => $knowledge_base,
    'enable_website_tools' => $enable_website_tools,
    'enable_duckduckgo'    => $enable_duckduckgo,
    'enable_wikipedia'     => $enable_wikipedia,
    'enable_yfinancetools' => $enable_yfinancetools,
    'enable_hackernews'    => $enable_hackernews,
    'enable_pdf_kb'        => $enable_pdf_kb,
    'enable_csv_kb'        => $enable_csv_kb,
    'enable_text_kb'       => $enable_text_kb,
  ];

  $content_prompt = json_encode($combined_prompt);

  /*
   * API çağrısını mevcut parametrelerle yapıyoruz. Artık ek parametre eklemeye gerek kalmadı.
   */
  $get_data_from_api = autowp_get_posts_from_wp_website(
      $autowp_domain_name,
      $user_email,
      $website_domainname,
      $website_categories,
      $post_count,
      $post_order,
      $post_ids,
      $title_prompt,
      $content_prompt,
      $tags_prompt,
      $image_prompt,
      $aigenerated_title,
      $aigenerated_content,
      $aigenerated_tags,
      $aigenerated_image,
      $image_settings_json,
      $source_type,
      $image_generating_status,
      $prompt_option_str
  );
  $wp_posts = $get_data_from_api['autowp-api'];

  if ($get_data_from_api['error']) {
      update_option('autowp_alerts', $get_data_from_api['error']);
  } else {
      update_option('autowp_alerts', '');
      if (empty($wp_posts)) {
          update_option('autowp_alerts', 'There is no new post from your feed url!');
      }
  }

  // İşlenen postları oluşturuyoruz
  foreach ($wp_posts as $post) {
      $post_ids .= ',' . $post['post_id'] . $post['slug'];
      $post_title          = $post['post_title'];
      $post_content        = $post['content'];
      $post_featured_image = $post['preview_image_original'];
      $post_tags           = $post['tags'];
      $post_status         = $image_settings['post_status'] ?? 'publish';
      $post_author         = $author_selection;
      $post_type           = 'post';
      $focus_keyword       = $post['focus_keyword'];
      $faq_schema          = $post['faq_schema'];

      autowp_set_new_post(
          $post_title,
          $post_content,
          $post_status,
          $post_author,
          $post_type,
          $post_featured_image,
          $wordpress_categories, // Bu formda kategori gönderilmiyor
          $post_tags,
          $focus_keyword,
          $faq_schema
      );
  }

  autowp_update_published_post_ids($post_ids);
}


function autowp_manual_post_ai_form_page_setOptions($form_data){

    $autowp_admin_email     = autowp_get_admin_email();
    $autowp_domain_name     = esc_url(get_site_url());
    $website_domainname     = '';
    $website_categories     = '';
    $post_count             = '';
    $post_order             = '';
    $post_ids               = '';
    $title_prompt           = sanitize_text_field($form_data['title_prompt']);
    $content_prompt         = sanitize_text_field($form_data['content_prompt']);
    $tags_prompt            = sanitize_text_field($form_data['tags_prompt']);
    $image_prompt           = sanitize_text_field($form_data['image_prompt']);

    $author_selection = sanitize_text_field($form_data['author_selection']) ?? 1;

    $aigenerated_title      = '1';
    $aigenerated_content      = '1';
    $aigenerated_tags      = '1';
    $aigenerated_image      = '1';

    if(isset($form_data['aigenerated_image'])){
      $aigenerated_image      = sanitize_text_field($form_data['aigenerated_image']);
    }

    $image_settings = unserialize(get_option('autowp_settings'));
    $image_settings_json = [];

    if(!empty($image_settings)){
      $image_settings_json = json_encode($image_settings);
    }


    $source_type            = 'ai';
   

    $wordpress_categories = isset($form_data['category_id']) ? array_map('intval', $form_data['category_id']) : array();

    $image_generating_status = sanitize_text_field($form_data['image_generating_status']);

    $prompts_option = get_option('autowp_rewriting_promptscheme');
    $prompt_option_str = strval($prompts_option);
    $rewriting_prompt_scheme = [];
    
    if (!empty($prompts_option)) {
        $rewriting_prompt_scheme = json_encode($prompts_option);
        
        // JSON'dan diziye dönüşüm yapılıyor ve true ile birlikte kullanıldığı için asosiyatif dizi elde ediliyor
    }

    $user_email = autowp_get_user_email_from_settings();


    $get_data_from_api = autowp_get_posts_from_wp_website($autowp_domain_name, $user_email, $website_domainname, $website_categories, $post_count,$post_order,$post_ids,$title_prompt,$content_prompt,$tags_prompt,$image_prompt,$aigenerated_title,$aigenerated_content,$aigenerated_tags,$aigenerated_image,$image_settings_json,$source_type,$image_generating_status,$prompt_option_str);
    $wp_posts = $get_data_from_api['autowp-api'];



   
    if($get_data_from_api['error']){
      update_option('autowp_alerts', $get_data_from_api['error']);
     
    }else{
      update_option('autowp_alerts', '');
      if(!empty($wp_posts)){
       // autowp_show_alert_with_message(true,'Success!');
      }else{
       // autowp_show_alert_with_message(false,'There is no new post from your feed url!');
        update_option('autowp_alerts', 'There is no new post from your feed url!');
      }
    }


    

    foreach($wp_posts as $post){

      $post_ids = $post_ids . ','  . $post['post_id'] . $post['slug'];
      $post_title = $post['post_title']; 
      $post_content = $post['content'];
      $post_featured_image = $post['preview_image_original'];
      $post_tags = $post['tags'];
      $post_status = $image_settings['post_status'] ?? 'publish';
      $post_author = $author_selection;
      $post_type = 'post';
      $focus_keyword = $post['focus_keyword'];
      $faq_schema = $post['faq_schema'];



      
      autowp_set_new_post($post_title,$post_content,$post_status,$post_author,$post_type,$post_featured_image, $wordpress_categories,$post_tags,$focus_keyword,$faq_schema);

     
    }

    autowp_update_published_post_ids($post_ids);

  
 
}


function autowp_manual_post_agenticscraper_form_page_handler() {

  // Process form submission
  if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'autowp_manual_post_agenticscraper_nonce' ) ) {

      $form_data = $_POST;
      $form_data['website_type'] = 'agenticscraper';

      // Use long description as content prompt
      $form_data['content_prompt'] = sanitize_text_field( $form_data['long_description'] );

      // Additional validations can be added here
      $validate_form = autowp_validate_agenticscraper( $form_data, true );

      if ( $validate_form === true ) {
          autowp_show_alert_with_message( true, 'Your process successfully started!' );
          schedule_autowp_manual_post_rss_event( $form_data );
      } else {
          autowp_show_alert_with_message( false, $validate_form );
      }
  }
  ?>
  <div class="wrap">
      <h1>Manual Post - Agentic Scraper</h1>
      <a href="javascript:history.back()" class="btn btn-primary mb-3">Go Back</a>
      <div id="poststuff">
          <div id="post-body" class="metabox-holder">
              <div id="post-body-content" class="row">
                  <div class="col-md-9">
                      <div class="meta-box-sortables ui-sortable">
                          <div class="postbox mb-4">
                              <h2 class="hndle ui-sortable-handle">Agentic Scraper Settings</h2>
                              <div class="inside">
                                  <form method="post">
                                      <?php wp_nonce_field( 'autowp_manual_post_agenticscraper_nonce', '_wpnonce' ); ?>

                                      <div class="mb-3">
                                                  <label for="author_selection" class="form-label"><?php esc_html_e('Author Selection', 'autowp'); ?></label>
                                                  <select name="author_selection" class="form-select">
                                                      <?php
                                                      $authors = get_users();

                                                      foreach ($authors as $author) {
                                                          $author_id = $author->ID;
                                                          $author_name = $author->display_name;
                                                          $author_description = get_the_author_meta('description', $author_id);

                                                          echo '<option value="' . esc_attr($author_id) . '">' . esc_html($author_name) . '</option>';
                                                      }
                                                      ?>
                                                  </select>
                                                  <p class="form-text"><?php esc_html_e('Select an author from the list.', 'autowp'); ?></p>
                                              </div>

                                              <div class="mb-3">
                                                  <label for="category_id" class="form-label">Categories:</label>
                                                  <select id="category_id" name="category_id[]" required multiple class="form-select">
                                                      <?php
                                                      $categories = get_categories(array(
                                                          'orderby' => 'name',
                                                          'order'   => 'ASC',
                                                          'hide_empty' => false
                                                      ));

                                                      foreach ($categories as $category) {
                                                          echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                                                      }
                                                      ?>
                                                  </select>
                                              </div>

                                              <div class="mb-3">
                                                  <label class="form-check-label">AI Settings :</label>
                                                  <div class="form-check">
                                                      <br>
                                                      <input type="checkbox" id="aigenerated_image" name="aigenerated_image" value="1" class="form-check-input">
                                                      <label for="aigenerated_image" class="form-check-label">Generated thumbnail with AI (SEO Friendly)</label>
                                                  </div>
                                              </div>

                                              <div class="col-md-6">
    <label class="form-label" for="image_generating_status"><?php esc_html_e('Image Generating Method', 'autowp'); ?></label>
    <select name="image_generating_status" class="form-select">
        <option value="0"><?php esc_html_e('FLUX Realism LoRA', 'autowp'); ?></option>
        <option value="1"><?php esc_html_e('Stable Diffusion Ultra', 'autowp'); ?></option>
        <option value="2"><?php esc_html_e('Stable Diffusion Core', 'autowp'); ?></option>
        <option value="3"><?php esc_html_e('DALL-E 2', 'autowp'); ?></option>
        <option value="4"><?php esc_html_e('DALL-E 3', 'autowp'); ?></option>
        <option value="5"><?php esc_html_e('DuckDuckGo Search', 'autowp'); ?></option>
        <option value="6"><?php esc_html_e('Default Image', 'autowp'); ?></option>
        <option value="7"><?php esc_html_e('No Image', 'autowp'); ?></option>
    </select>
    <p class="form-text"><?php esc_html_e('By default FLUX Realism LoRA is selected.', 'autowp'); ?></p>
</div>



                                      <!-- Long Description Prompt -->
                                      <div class="mb-3">
                                          <label for="long_description" class="form-label">Long Description Prompt:</label>
                                          <textarea class="form-control" id="long_description" name="long_description" rows="5" placeholder="Enter your detailed prompt here..."></textarea>
                                          <div class="form-text">Provide a detailed prompt for generating content.</div>
                                      </div>




                <h4 style="font-weight: bold;">Post Settings</h4>
<!-- Integration of your initial form inputs starts here -->
            <div class="mb-3">
                <label for="keywordInput" class="form-label">Keyword:</label>
                <input type="text" class="form-control" id="keywordInput" name="keywordInput" placeholder="Enter keyword" value="" required class="form-control">
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
    <option value="<?php echo esc_attr($language); ?>">
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
            echo '<option value="' . esc_attr($i) . '">' . esc_html($i) . '</option>';
        }
        ?>
    </select>
</div>

<div class="mb-3">
    <label for="narrationSelect" class="form-label">Writing Style:</label>
    <select class="form-select" id="narrationSelect" name="narrationSelect">
        <?php
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
            echo '<option value="' . esc_attr($value) . '">' . esc_html($name) . '</option>';
        }
        ?>
    </select>
</div>

                                      <!-- Custom Tools Section -->
                                      <h3 class="mb-3">Custom Tools</h3>
                                      <div class="accordion mb-4" id="accordionCustomTools">
                                          <!-- Website Tools -->
                                          <div class="accordion-item">
                                              <h2 class="accordion-header" id="headingWebsiteTools">
                                                  <div class="d-flex justify-content-between align-items-center w-100">
                                                      <button class="accordion-button flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWebsiteTools" aria-expanded="true" aria-controls="collapseWebsiteTools">
                                                          Website Tools
                                                      </button>
                                                      <div class="form-check form-switch ms-2">
                                                          <input class="form-check-input" type="checkbox" role="switch" id="toggle_website_tools" name="enable_website_tools" checked style="transform: scale(1.3);" onchange="toggleAccordion('collapseWebsiteTools', this.checked);">
                                                      </div>
                                                  </div>
                                              </h2>
                                              <div id="collapseWebsiteTools" class="accordion-collapse collapse show" aria-labelledby="headingWebsiteTools">
                                                  <div class="accordion-body">
                                                      <div class="mb-3">
                                                          <label for="website_tools_knowledge_base_url" class="form-label">Knowledge Base URL</label>
                                                          <input type="url" class="form-control" id="website_tools_knowledge_base_url" name="website_tools_knowledge_base_url" placeholder="https://example.com">
                                                          <div class="form-text">Enter a valid URL if you want to include a knowledge base. Must be in proper URL format.</div>
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
                                                          <input class="form-check-input" type="checkbox" role="switch" id="toggle_duckduckgo" name="enable_duckduckgo" checked style="transform: scale(1.3);" onchange="toggleAccordion('collapseDuckDuckGO', this.checked);">
                                                      </div>
                                                  </div>
                                              </h2>
                                              <div id="collapseDuckDuckGO" class="accordion-collapse collapse show" aria-labelledby="headingDuckDuckGO">
                                                  <div class="accordion-body">
                                                      <div class="form-check form-switch mb-3">
                                                          <input type="checkbox" class="form-check-input" id="duckduckgo_news" name="duckduckgo_news" value="1" checked>
                                                          <label class="form-check-label" for="duckduckgo_news">Include News (Default: Enabled)</label>
                                                      </div>
                                                      <div class="mb-3">
                                                          <label for="duckduckgo_fixed_max_results" class="form-label">Fixed Max Results</label>
                                                          <input type="number" class="form-control" id="duckduckgo_fixed_max_results" name="duckduckgo_fixed_max_results" placeholder="Enter a number or leave blank">
                                                          <div class="form-text">Optional: set a maximum number of results.</div>
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
                                                          <input class="form-check-input" type="checkbox" role="switch" id="toggle_wikipedia" name="enable_wikipedia" checked style="transform: scale(1.3);" onchange="toggleAccordion('collapseWikipedia', this.checked);">
                                                      </div>
                                                  </div>
                                              </h2>
                                              <div id="collapseWikipedia" class="accordion-collapse collapse show" aria-labelledby="headingWikipedia">
                                                  <div class="accordion-body">
                                                      <div class="mb-3">
                                                          <label for="wikipedia_knowledge_base" class="form-label">Knowledge Base Topics</label>
                                                          <input type="text" class="form-control" id="wikipedia_knowledge_base" name="wikipedia_knowledge_base" placeholder="topic1, topic2, topic3">
                                                          <div class="form-text">Enter topics separated by commas if you want to include a knowledge base.</div>
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
                                                          <input class="form-check-input" type="checkbox" role="switch" id="toggle_yfinancetools" name="enable_yfinancetools" checked style="transform: scale(1.3);" onchange="toggleAccordion('collapseYFinanceTools', this.checked);">
                                                      </div>
                                                  </div>
                                              </h2>
                                              <div id="collapseYFinanceTools" class="accordion-collapse collapse show" aria-labelledby="headingYFinanceTools">
                                                  <div class="accordion-body">
                                                      <div class="form-check form-switch mb-2">
                                                          <input type="checkbox" class="form-check-input" id="yfinance_stock_price" name="yfinance_stock_price" value="1" checked>
                                                          <label class="form-check-label" for="yfinance_stock_price">Stock Price (Default: Enabled)</label>
                                                      </div>
                                                      <div class="form-check form-switch mb-2">
                                                          <input type="checkbox" class="form-check-input" id="yfinance_company_info" name="yfinance_company_info" value="1">
                                                          <label class="form-check-label" for="yfinance_company_info">Company Info</label>
                                                      </div>
                                                      <div class="form-check form-switch mb-2">
                                                          <input type="checkbox" class="form-check-input" id="yfinance_stock_fundamentals" name="yfinance_stock_fundamentals" value="1">
                                                          <label class="form-check-label" for="yfinance_stock_fundamentals">Stock Fundamentals</label>
                                                      </div>
                                                      <div class="form-check form-switch mb-2">
                                                          <input type="checkbox" class="form-check-input" id="yfinance_income_statements" name="yfinance_income_statements" value="1">
                                                          <label class="form-check-label" for="yfinance_income_statements">Income Statements</label>
                                                      </div>
                                                      <div class="form-check form-switch mb-2">
                                                          <input type="checkbox" class="form-check-input" id="yfinance_key_financial_ratios" name="yfinance_key_financial_ratios" value="1">
                                                          <label class="form-check-label" for="yfinance_key_financial_ratios">Key Financial Ratios</label>
                                                      </div>
                                                      <div class="form-check form-switch mb-2">
                                                          <input type="checkbox" class="form-check-input" id="yfinance_analyst_recommendations" name="yfinance_analyst_recommendations" value="1">
                                                          <label class="form-check-label" for="yfinance_analyst_recommendations">Analyst Recommendations</label>
                                                      </div>
                                                      <div class="form-check form-switch mb-2">
                                                          <input type="checkbox" class="form-check-input" id="yfinance_company_news" name="yfinance_company_news" value="1">
                                                          <label class="form-check-label" for="yfinance_company_news">Company News</label>
                                                      </div>
                                                      <div class="form-check form-switch mb-2">
                                                          <input type="checkbox" class="form-check-input" id="yfinance_technical_indicators" name="yfinance_technical_indicators" value="1">
                                                          <label class="form-check-label" for="yfinance_technical_indicators">Technical Indicators</label>
                                                      </div>
                                                      <div class="form-check form-switch mb-2">
                                                          <input type="checkbox" class="form-check-input" id="yfinance_historical_prices" name="yfinance_historical_prices" value="1">
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
                                                          <input class="form-check-input" type="checkbox" role="switch" id="toggle_hackernews" name="enable_hackernews" checked style="transform: scale(1.3);" onchange="toggleAccordion('collapseHackerNews', this.checked);">
                                                      </div>
                                                  </div>
                                              </h2>
                                              <div id="collapseHackerNews" class="accordion-collapse collapse show" aria-labelledby="headingHackerNews">
                                                  <div class="accordion-body">
                                                      <div class="form-check form-switch mb-2">
                                                          <input type="checkbox" class="form-check-input" id="hackernews_get_top_stories" name="hackernews_get_top_stories" value="1" checked>
                                                          <label class="form-check-label" for="hackernews_get_top_stories">Get Top Stories (Default: Enabled)</label>
                                                      </div>
                                                      <div class="form-check form-switch mb-2">
                                                          <input type="checkbox" class="form-check-input" id="hackernews_get_user_details" name="hackernews_get_user_details" value="1" checked>
                                                          <label class="form-check-label" for="hackernews_get_user_details">Get User Details (Default: Enabled)</label>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div> <!-- End of accordionCustomTools -->

                                      <!-- Knowledge Base Section (3 Separate Items) -->
                                      <h3 class="mb-3">Knowledge Base</h3>
                                      <div class="accordion mb-4" id="accordionKnowledgeBase">
                                          <!-- PDF Knowledge Base -->
                                          <div class="accordion-item">
                                              <h2 class="accordion-header" id="headingPDFKB">
                                                  <div class="d-flex justify-content-between align-items-center w-100">
                                                      <button class="accordion-button collapsed flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePDFKB" aria-expanded="false" aria-controls="collapsePDFKB">
                                                          PDF Knowledge Base
                                                      </button>
                                                      <div class="form-check form-switch ms-2">
                                                          <input class="form-check-input" type="checkbox" role="switch" id="toggle_pdf_kb" name="enable_pdf_kb" style="transform: scale(1.3);" onchange="toggleAccordion('collapsePDFKB', this.checked);">
                                                      </div>
                                                  </div>
                                              </h2>
                                              <div id="collapsePDFKB" class="accordion-collapse collapse" aria-labelledby="headingPDFKB">
                                                  <div class="accordion-body">
                                                      <div class="mb-3">
                                                          <label for="pdf_url_knowledge_base" class="form-label">PDF URL Knowledge Base</label>
                                                          <input type="url" class="form-control" id="pdf_url_knowledge_base" name="pdf_url_knowledge_base" placeholder="https://example.com/document.pdf">
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
                                                          <input class="form-check-input" type="checkbox" role="switch" id="toggle_csv_kb" name="enable_csv_kb" style="transform: scale(1.3);" onchange="toggleAccordion('collapseCSVKB', this.checked);">
                                                      </div>
                                                  </div>
                                              </h2>
                                              <div id="collapseCSVKB" class="accordion-collapse collapse" aria-labelledby="headingCSVKB">
                                                  <div class="accordion-body">
                                                      <div class="mb-3">
                                                          <label for="csv_url_knowledge_base" class="form-label">CSV URL Knowledge Base</label>
                                                          <input type="url" class="form-control" id="csv_url_knowledge_base" name="csv_url_knowledge_base" placeholder="https://example.com/data.csv">
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
                                                          <input class="form-check-input" type="checkbox" role="switch" id="toggle_text_kb" name="enable_text_kb" style="transform: scale(1.3);" onchange="toggleAccordion('collapseTextKB', this.checked);">
                                                      </div>
                                                  </div>
                                              </h2>
                                              <div id="collapseTextKB" class="accordion-collapse collapse" aria-labelledby="headingTextKB">
                                                  <div class="accordion-body">
                                                      <div class="mb-3">
                                                          <label for="text_knowledge_base" class="form-label">Text Knowledge Base</label>
                                                          <textarea class="form-control" id="text_knowledge_base" name="text_knowledge_base" rows="5" placeholder="Enter your knowledge base text here..."></textarea>
                                                          <div class="form-text">Enter plain text for your knowledge base. This field supports a longer description.</div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div> <!-- End of accordionKnowledgeBase -->

                                      <div class="mb-3">
                                          <button type="submit" name="submit" id="submit" class="btn btn-primary">Generate Post</button>
                                      </div>
                                  </form>
                              </div><!-- .inside -->
                          </div><!-- .postbox -->
                      </div><!-- .meta-box-sortables -->
                  </div><!-- .col-md-9 -->
              </div><!-- #post-body-content -->
          </div><!-- #poststuff -->
      </div><!-- .wrap -->

      <!-- Inline JavaScript to handle toggle-controlled accordion behavior -->
      <script>
          function toggleAccordion(collapseId, isActive) {
              var collapseElement = document.getElementById(collapseId);
              var bsCollapse = new bootstrap.Collapse(collapseElement, {
                  toggle: false
              });
              if (isActive) {
                  bsCollapse.show();
              } else {
                  bsCollapse.hide();
              }
          }
      </script>
  <?php
}






function autowp_manual_post_ai_form_page_handler() {

  if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'autowp_manual_post_ai_nonce')) {

    $form_data = $_POST;

    $form_data['website_type'] = 'ai';

    $form_data['content_prompt'] = '[autowp-promptcode]' . 
    sanitize_text_field($form_data['keywordInput']) . ',' . 
    'aiGenerated' . ',' .
    sanitize_text_field($form_data['countrySelect']) . ',' .
    sanitize_text_field($form_data['languageSelect']) . ',' .
    sanitize_text_field($form_data['subtitleSelect']) . ',' .
    sanitize_text_field($form_data['narrationSelect']) .
    '[/autowp-promptcode]';

    $validate_form = autowp_validate_website($form_data,true);
    
    

    if($validate_form === true){
      autowp_show_alert_with_message(true,'Your process successfully started!');
      schedule_autowp_manual_post_rss_event($form_data);
    }else{

      
      autowp_show_alert_with_message(false,$validate_form);
      
    }


  

  }
  //autowp_manual_post_ai_form_page_setOptions();
  ?>

  <div class="wrap">
      <h1>Manual Post - AI</h1>
      <a href="javascript:history.back()" class="btn btn-primary">Go Back</a>

      <div id="poststuff">
          <div id="post-body" class="metabox-holder">
              <!-- Main Content -->
              <div id="post-body-content" class="row">
                  <div class="col-md-9">
                      <div class="meta-box-sortables ui-sortable">
                          <div class="postbox">
                              <h2 class="hndle ui-sortable-handle">AI Settings</h2>
                              <div class="inside">
                                  <form method="post">
                                      <?php wp_nonce_field('autowp_manual_post_ai_nonce', '_wpnonce'); ?>
                                      <form class="row g-3" id="post_generation_form">
                                          <div class="col-md-6">
                                              <div class="mb-3">
                                                  <label for="author_selection" class="form-label"><?php esc_html_e('Author Selection', 'autowp'); ?></label>
                                                  <select name="author_selection" class="form-select">
                                                      <?php
                                                      $authors = get_users();

                                                      foreach ($authors as $author) {
                                                          $author_id = $author->ID;
                                                          $author_name = $author->display_name;
                                                          $author_description = get_the_author_meta('description', $author_id);

                                                          echo '<option value="' . esc_attr($author_id) . '">' . esc_html($author_name) . '</option>';
                                                      }
                                                      ?>
                                                  </select>
                                                  <p class="form-text"><?php esc_html_e('Select an author from the list.', 'autowp'); ?></p>
                                              </div>

                                              <div class="mb-3">
                                                  <label for="category_id" class="form-label">Categories:</label>
                                                  <select id="category_id" name="category_id[]" required multiple class="form-select">
                                                      <?php
                                                      $categories = get_categories(array(
                                                          'orderby' => 'name',
                                                          'order'   => 'ASC',
                                                          'hide_empty' => false
                                                      ));

                                                      foreach ($categories as $category) {
                                                          echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                                                      }
                                                      ?>
                                                  </select>
                                              </div>

                                              <div class="mb-3">
                                                  <label class="form-check-label">AI Settings :</label>
                                                  <div class="form-check">
                                                      <br>
                                                      <input type="checkbox" id="aigenerated_image" name="aigenerated_image" value="1" class="form-check-input">
                                                      <label for="aigenerated_image" class="form-check-label">Generated thumbnail with AI (SEO Friendly)</label>
                                                  </div>
                                              </div>

                                              <div class="col-md-6">
    <label class="form-label" for="image_generating_status"><?php esc_html_e('Image Generating Method', 'autowp'); ?></label>
    <select name="image_generating_status" class="form-select">
        <option value="0"><?php esc_html_e('FLUX Realism LoRA', 'autowp'); ?></option>
        <option value="1"><?php esc_html_e('Stable Diffusion Ultra', 'autowp'); ?></option>
        <option value="2"><?php esc_html_e('Stable Diffusion Core', 'autowp'); ?></option>
        <option value="3"><?php esc_html_e('DALL-E 2', 'autowp'); ?></option>
        <option value="4"><?php esc_html_e('DALL-E 3', 'autowp'); ?></option>
        <option value="5"><?php esc_html_e('DuckDuckGo Search', 'autowp'); ?></option>

        <option value="6"><?php esc_html_e('Default Image', 'autowp'); ?></option>
        <option value="7"><?php esc_html_e('No Image', 'autowp'); ?></option>
    </select>
    <p class="form-text"><?php esc_html_e('By default FLUX Realism LoRA is selected.', 'autowp'); ?></p>
</div>


                                          



                <h4 style="font-weight: bold;">Post Settings</h4>
<!-- Integration of your initial form inputs starts here -->
            <div class="mb-3">
                <label for="keywordInput" class="form-label">Keyword:</label>
                <input type="text" class="form-control" id="keywordInput" name="keywordInput" placeholder="Enter keyword" value="" required class="form-control">
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
        echo '<option value="' . esc_attr($code) . '">' . esc_html($name) . '</option>';
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
    <option value="<?php echo esc_attr($language); ?>">
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
            echo '<option value="' . esc_attr($i) . '">' . esc_html($i) . '</option>';
        }
        ?>
    </select>
</div>

<div class="mb-3">
    <label for="narrationSelect" class="form-label">Writing Style:</label>
    <select class="form-select" id="narrationSelect" name="narrationSelect">
        <?php
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
            echo '<option value="' . esc_attr($value) . '">' . esc_html($name) . '</option>';
        }
        ?>
    </select>
</div>




                                            

                                              <div class="mb-3">
                                                  <p class="submit">
                                                      <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Generate Post">
                                                  </p>
                                              </div>
                                         
                                      
                                  </form>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- Sidebar -->
              </div>
          </div>
      </div>
  </div>
  <?php
}

function autowp_prompt_settings_page_handler(){
  ?>
    <div class="wrap">
      <h2><?php esc_html_e('Content Schemes', 'autowp'); ?></h2>
      <p class="lead"><?php esc_html_e('Select ', 'autowp'); ?></p>
      <ul class="list-group">

        <!-- Writing Prompt Scheme Option -->
        <li class="list-group-item">
          <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=autowp_promptschemes')); ?>" class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <img src="<?php echo esc_url(plugins_url('assets/images/wordpress-icon.png', __FILE__)); ?>" alt="<?php esc_attr_e('Writing Prompt Scheme', 'autowp'); ?>" class="me-3" style="width: 80px; height: 80px;">
              <div>
                <h5 class="mb-1"><?php esc_html_e('Writing Prompt Scheme', 'autowp'); ?></h5>
                <p class="mb-0"><?php esc_html_e('Create a prompt scheme from scratch for generating new posts.', 'autowp'); ?></p>
              </div>
            </div>
          </a>
        </li>

        <!-- Rewriting Prompt Scheme Option -->
        <li class="list-group-item">
          <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=autowp_rewriting_promptschemes')); ?>" class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <img src="<?php echo esc_url(plugins_url('assets/images/rss-icon.png', __FILE__)); ?>" alt="<?php esc_attr_e('Rewriting Prompt Scheme', 'autowp'); ?>" class="me-3" style="width: 80px; height: 80px;">
              <div>
                <h5 class="mb-1"><?php esc_html_e('Rewriting Prompt Scheme', 'autowp'); ?></h5>
                <p class="mb-0"><?php esc_html_e('Create a prompt scheme that rewrites posts based on source content (e.g., RSS Feeds).', 'autowp'); ?></p>
              </div>
            </div>
          </a>
        </li>

        <!-- Other Prompt Settings Option -->
        <li class="list-group-item">
          <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=manual_post_ai_website_form')); ?>" class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <img src="<?php echo esc_url(plugins_url('assets/images/robot-icon.png', __FILE__)); ?>" alt="<?php esc_attr_e('Other Prompt Settings', 'autowp'); ?>" class="me-3" style="width: 80px; height: 80px;">
              <div>
                <h5 class="mb-1"><?php esc_html_e('Other Prompt Settings', 'autowp'); ?></h5>
                <p class="mb-0"><?php esc_html_e('Customize titles, keywords, thumbnails, tags, and other prompt settings.', 'autowp'); ?></p>
              </div>
            </div>
          </a>
        </li>

        <!-- Video Tutorials Option (with modal trigger) -->
        <li class="list-group-item">
          <a href="#" data-bs-toggle="modal" data-bs-target="#tutorialModal" class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <img src="<?php echo esc_url(plugins_url('assets/images/tutorial.png', __FILE__)); ?>" alt="<?php esc_attr_e('Video Tutorials', 'autowp'); ?>" class="me-3" style="width: 80px; height: 80px;">
              <div>
                <h5 class="mb-1"><?php esc_html_e('Video Tutorials', 'autowp'); ?></h5>
                <p class="mb-0"><?php esc_html_e('Watch tutorials to learn how to configure and use the AI prompt settings.', 'autowp'); ?></p>
              </div>
            </div>
          </a>
        </li>

      </ul>

      <!-- Bootstrap Modal -->
      <div class="modal fade" id="tutorialModal" tabindex="-1" aria-labelledby="tutorialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="tutorialModalLabel"><?php esc_html_e('Video Tutorials', 'autowp'); ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <h5><?php esc_html_e('Manual Processes', 'autowp'); ?></h5>
                <p><?php esc_html_e('Once you click "Generate Post", the process will run in the background, typically taking about 5 minutes. You can monitor errors or progress in the WordPress admin panel.', 'autowp'); ?></p>
              </div>

              <div class="mb-3">
                <h5><?php esc_html_e('Automatic Processes', 'autowp'); ?></h5>
                <p><?php esc_html_e('Automatic processes are triggered based on the cron schedule you set in the settings. Make sure to configure the interval after adding new processes.', 'autowp'); ?></p>
              </div>

              <!-- Video Tutorials Section -->
              <div class="mb-3">
                <h6><?php esc_html_e('How to Generate Posts with AI', 'autowp'); ?></h6>
                <iframe width="450" height="350" src="https://www.youtube.com/embed/p5KpM9eZftE" frameborder="0" allowfullscreen></iframe>
              </div>

              <div class="mb-3">
                <h6><?php esc_html_e('How to Rewrite Posts with AI from RSS Feed', 'autowp'); ?></h6>
                <iframe width="450" height="350" src="https://www.youtube.com/embed/A-wTvmlz7og" frameborder="0" allowfullscreen></iframe>
              </div>

              <div class="mb-3">
                <h6><?php esc_html_e('How to Rewrite Posts with AI from WordPress Websites', 'autowp'); ?></h6>
                <iframe width="450" height="350" src="https://www.youtube.com/embed/xo9IbyZ_HXY" frameborder="0" allowfullscreen></iframe>
              </div>

              <div class="mb-3">
                <h6><?php esc_html_e('How to Rewrite Posts with AI from Google News', 'autowp'); ?></h6>
                <iframe width="450" height="350" src="https://www.youtube.com/embed/z8sM2953VBQ" frameborder="0" allowfullscreen></iframe>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php esc_html_e('Close', 'autowp'); ?></button>
            </div>
          </div>
        </div>
      </div>

    </div>
  <?php
}


function autowp_setup_page() {
  $user_email = wp_get_current_user()->user_email;

    $server_url = unserialize(get_option("autowp_settings"))["autowp_server_url"];


   $url = 'https://api.autowp.app/register_email';
   $url_confirm = 'https://api.autowp.app/confirm_email';

  if ( isset( $server_url ) 
     && $server_url !== null 
     && $server_url !== '' 
     && strpos( $server_url, 'autowp.app' ) === false
  ) {
    
    $url = $server_url . '/register_email';
    $url_confirm = $server_url . '/confirm_email';

  }

  ?>
  <div class="wrap">
      <h1><?php esc_html_e('AutoWP Setup', 'autowp'); ?></h1>
      <form method="post" class="form-horizontal" id="autowp-setup-form">
          <fieldset>
              <legend><?php esc_html_e('API Registration', 'autowp'); ?></legend>

              <!-- Email -->
              <div class="form-group">
                  <label class="col-md-4 control-label" for="api_email"><?php esc_html_e('Email', 'autowp'); ?></label>
                  <div class="col-md-4">
                      <input id="api_email" name="api_email" type="text" class="form-control" value="<?php echo esc_attr($user_email); ?>" placeholder="<?php esc_html_e('Enter your email', 'autowp'); ?>">
                      <p class="help-block"><?php esc_html_e('This email will be used to register and retrieve your API key.', 'autowp'); ?></p>
                  </div>
              </div>

              <!-- API Key -->
              <div class="form-group">
                  <label class="col-md-4 control-label" for="api_key"><?php esc_html_e('API Key', 'autowp'); ?></label>
                  <div class="col-md-4">
                      <input id="api_key" name="api_key" type="text" class="form-control" placeholder="<?php esc_html_e('Enter your API key', 'autowp'); ?>">
                      <p class="help-block"><?php esc_html_e('If you don’t have an API key, use the button below to request one.', 'autowp'); ?></p>
                  </div>
              </div>

              <!-- Request API Key Button -->
              <div class="form-group">
                  <div class="col-md-4 col-md-offset-4">
                      <button type="button" id="request_api_key" class="btn btn-info"><?php esc_html_e('Request API Key', 'autowp'); ?></button>
                  </div>
              </div>

              <!-- Save Settings Button -->
              <div class="form-group" style="margin-top: 20px;">
                  <div class="col-md-4 col-md-offset-4">
                      <button type="submit" class="btn btn-primary"><?php esc_html_e('Save Settings', 'autowp'); ?></button>
                  </div>
              </div>

          </fieldset>
      </form>
      
      <!-- Bilgilendirme Mesajları -->
      <div id="autowp-messages"></div>
  </div>

  <script>
  jQuery(document).ready(function($) {
      // Request API Key Button Click Event
      $('#request_api_key').click(function() {
          var email = $('#api_email').val();

          if (!email) {
              $('#autowp-messages').html('<div class="alert alert-danger">Please enter an email address.</div>');
              return;
          }

          // API çağrısını yap
          // API çağrısını yap
$.ajax({
    url: '<?php echo esc_url( $url ); ?>',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({ user_email: email }),
    success: function(response) {
        $('#autowp-messages').html('<div class="alert alert-success">API key has been sent to your email.</div>');
    },
    error: function(xhr, status, error) {
        var errorMessage = 'Error occurred while requesting API key. Please try again later.';

        // Hata mesajını API'den gelen cevaba göre ayarla
        if (xhr.responseJSON && xhr.responseJSON.error) {
            errorMessage = xhr.responseJSON.error;
        } else if (xhr.responseText) {
            // JSON değilse ham yanıtı göster
            errorMessage = xhr.responseText;
        }

        $('#autowp-messages').html('<div class="alert alert-danger">' + errorMessage + '</div>');
    }
});

      });

      // Save Settings Button Click Event
      $('#autowp-setup-form').submit(function(event) {
          event.preventDefault(); // Formun varsayılan davranışını engelle

          var email = $('#api_email').val();
          var api_key = $('#api_key').val();

          if (!email || !api_key) {
              $('#autowp-messages').html('<div class="alert alert-danger">Email and API key cannot be empty.</div>');
              return;
          }

         // API confirm_email çağrısını yap
$.ajax({
    url: '<?php echo esc_url( $url_confirm ); ?>',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({ user_email: email, api_key: api_key }),
    success: function(response) {
        // Başarılı ise bilgileri kaydet
        var settings = {
            'api_email': email,
            'api_key': api_key
        };

        // AJAX ile WordPress'te settings güncelle
        $.ajax({
            url: ajaxurl, // WordPress'in global ajax URL'si
            type: 'POST',
            data: {
                action: 'save_autowp_settings',
                settings: settings
            },
            success: function() {
                $('#autowp-messages').html('<div class="alert alert-success">API settings saved successfully.</div>');

                // 1-2 saniye sonra yönlendirme yap
                setTimeout(function() {
                    window.location.href = 'admin.php?page=autowp_add_new_website_form';
                }, 1500); // 1500 milisaniye = 1.5 saniye
            }
        });
    },
    error: function(xhr, status, error) {
        var errorMessage = 'Error: An error occurred while confirming the email and API key.';

        // Hata mesajını API'den gelen cevaba göre ayarla
        if (xhr.responseJSON && xhr.responseJSON.error) {
            errorMessage = 'Error: ' + xhr.responseJSON.error;
        } else if (xhr.responseText) {
            // JSON değilse ham yanıtı göster
            errorMessage = 'Error: ' + xhr.responseText;
        }

        $('#autowp-messages').html('<div class="alert alert-danger">' + errorMessage + '</div>');
    }
});

      });
  });
  </script>

  <?php
}

// AJAX ile ayarları kaydetme fonksiyonu
function save_autowp_settings() {
  // Mevcut ayarları al
  $existing_settings = get_option('autowp_settings');
  $existing_settings = $existing_settings ? unserialize($existing_settings) : [];

  if (isset($_POST['settings'])) {
      // Sadece api_email ve api_key değerlerini güncelle
      $new_settings = $_POST['settings'];

      // Mevcut ayarlarla birleştir (sadece api_email ve api_key'i günceller)
      $updated_settings = array_merge($existing_settings, [
          'api_email' => isset($new_settings['api_email']) ? sanitize_email($new_settings['api_email']) : '',
          'api_key' => isset($new_settings['api_key']) ? sanitize_text_field($new_settings['api_key']) : ''
      ]);

      // Ayarları güncelle
      update_option('autowp_settings', serialize($updated_settings));
  }

  wp_die(); // AJAX çağrısının sonlandırılması için gerekli
}
add_action('wp_ajax_save_autowp_settings', 'save_autowp_settings');


function autowp_manual_post_selection_page_handler(){
  ?>
  <!-- Add this in the <head> section of your HTML -->

  <div class="wrap">
    <h2><?php esc_html__('Manual Post', 'autowp')?></h2>
    <p><?php esc_html__('Please select the type of website you want manual posting:', 'autowp')?></p>
    <ul class="list-group">
      <li class="list-group-item">
        <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=manual_post_wp_website_form')); ?>" class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
          <img src="<?php echo esc_url( plugins_url( 'assets/images/wordpress-icon.png', __FILE__ ) ); ?>" alt="<?php esc_attr_e('WordPress Website'); ?>" class="me-3" style="width: 80px; height: 80px;">
            <div>
              <h5><?php esc_html_e('AI-Rewrite From Wordpress Website', 'autowp')?></h5>
              <p><?php esc_html_e('Fetch posts from WordPress site and rewrite with artificial intelligence.', 'autowp')?></p>
            </div>
          </div>
        </a>
      </li>
      <li class="list-group-item">
        <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=manual_post_rss_website_form')); ?>" class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
          <img src="<?php echo esc_url( plugins_url( 'assets/images/rss-icon.png', __FILE__ ) ); ?>" alt="<?php esc_attr_e('RSS Website'); ?>" class="me-3" style="width: 80px; height: 80px;">
            <div>
              <h5><?php esc_html_e('AI-Rewrite From RSS Website', 'autowp')?></h5>
              <p><?php esc_html_e('Fetch content with RSS and rewrite with artificial intelligence.', 'autowp')?></p>
            </div>
          </div>
        </a>
      </li>
      <li class="list-group-item">
      <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=manual_post_ai_website_form')); ?>" class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <img src="<?php echo esc_url(plugins_url( 'assets/images/robot-icon.png', __FILE__ )); ?>" alt="Artificial Intelligence" class="me-3" style="width: 80px; height: 80px;">
            <div>
            <h5><?php esc_attr_e('AI Agents with Web Research Tools', 'autowp')?></h5>
            <p><?php esc_attr_e('Create original content from scratch using AutoWP AI Agent with web research tool !', 'autowp')?></p>
            </div>
          </div>
        </a>
      </li>

      <li class="list-group-item">
      <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=manual_post_agenticscraper_website_form')); ?>" class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
          <img src="<?php echo esc_url(plugins_url('assets/images/robot-icon.png', __FILE__)); ?>" alt="<?php esc_attr_e('Artificial Intelligence'); ?>" class="me-3" style="width: 80px; height: 80px;">
            <div>
              <h5><?php esc_attr_e('Agentic Scraper with Custom Tools and Prompts', 'autowp')?></h5>
              <p><?php esc_attr_e('Create your own Agentic Scraper with your customize tools and prompts!', 'autowp')?></p>
            </div>
          </div>
        </a>
      </li>


      <li class="list-group-item">
        <a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=manual_post_news_website_form')); ?>" class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
          <img src="<?php echo esc_url( plugins_url( 'assets/images/gnews.png', __FILE__ ) ); ?>" alt="<?php esc_attr_e('Rewrite With AI From News'); ?>" class="me-3" style="width: 80px; height: 80px;">
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
            <img src="<?php echo esc_url(plugins_url( 'assets/images/tutorial.png', __FILE__ )); ?>" alt="Tutorial" class="me-3" style="width: 80px; height: 80px;">
            <div>
              <h5><?php esc_html_e('Video Tutorials', 'autowp')?></h5>
              <p><?php esc_html_e('Learn how to add new process.', 'autowp')?></p>
            </div>
          </div>
        </a>
      </li>
    </ul>
  </div>

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
          <h6>How to Use - Detailed Tutorial </h6>
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





  

  <?php
}

// Setup sayfasını kayıt etme
function autowp_register_setup_page() {
  add_submenu_page(null, 'AutoWP Setup', 'AutoWP Setup', 'manage_options', 'autowp-setup', 'autowp_setup_page');
}
add_action('admin_menu', 'autowp_register_setup_page');

 function autowp_setAdminMenu(){
  add_menu_page(__('AutoWP', 'autowp'), __('AutoWP', 'autowp'), 'activate_plugins', 'autowp_menu', 'autowp_generalSettings',plugins_url( '/assets/images/autowp-icon25.png',__FILE__ ));
  add_submenu_page('autowp_menu', __('Automatic Post', 'autowp'), __('Automatic Post', 'autowp'), 'activate_plugins', 'autowp_automaticPost', 'autowp_wp_page_handler');
  add_submenu_page('autowp_menu', __('Add new', 'autowp'), __('Add new', 'autowp'), 'activate_plugins', 'autowp_add_new_website_form', 'autowp_website_selection_page_handler');
  add_submenu_page('autowp_menu', __("Manual Post","autowp"), __("Manual Post","autowp"), '2' ,'autowp_manualPost' ,'autowp_manual_post_selection_page_handler', '7');
  add_submenu_page('autowp_menu', __("Content Planner","autowp"), __("Content Planner","autowp"), '3' ,'autowp_promptSettings' ,'autowp_rewriting_promptscheme_page_handler', '8');
  add_submenu_page(
    'autowp_menu', 
    __('Linking Management', 'autowp'), 
    __('Linking Management', 'autowp'), 
    'manage_options', 
    'autowp_linking_management', 
    'autowp_linking_page_handler'
);


  //Automatic Post
  add_submenu_page('autowp_add_new_website_form', __('Add new WordPress Website', 'autowp'), __('Add new WordPress Website', 'autowp'), 'manage_options', 'add_new_wp_website_form', 'autowp_wp_website_form_page_handler');
  add_submenu_page('autowp_add_new_website_form',__('Add new RSS Website', 'autowp'), __('Add new RSS Website', 'autowp'), 'manage_options', 'add_new_rss_website_form', 'autowp_rss_website_form_page_handler');
  add_submenu_page('autowp_add_new_website_form', __('Add new AI-Generated Process', 'autowp'), __('Add new AI-Generated Process', 'autowp'), 'manage_options', 'add_new_ai_website_form', 'autowp_ai_website_form_page_handler');
  add_submenu_page('autowp_add_new_website_form', __('Add new News Website', 'autowp'), __('Add new News Website', 'autowp'), 'manage_options', 'add_new_news_website_form', 'autowp_news_website_form_page_handler');
  add_submenu_page('autowp_add_new_website_form', __('Add new Customize AI Agent', 'autowp'), __('Add new Customize AI Agent', 'autowp'), 'manage_options', 'add_new_agenticscraper_form', 'autowp_auto_post_agent_form_page_handler');


  //Manual Post

  add_submenu_page('autowp_manualPost', __('Wordpress Website', 'autowp'), __('AI Post Writing', 'autowp'), 'manage_options', 'manual_post_ai_website_form', 'autowp_manual_post_ai_form_page_handler');
  add_submenu_page('autowp_manualPost', __('Wordpress Website', 'autowp'), __('Agentic Scraper with Custom Tools', 'autowp'), 'manage_options', 'manual_post_agenticscraper_website_form', 'autowp_manual_post_agenticscraper_form_page_handler');
  add_submenu_page('autowp_manualPost', __('Wordpress Website', 'autowp'), __('AI Rewrite Post From RSS Website', 'autowp'), 'manage_options', 'manual_post_rss_website_form', 'autowp_manual_post_rss_form_page_handler');
  add_submenu_page('autowp_manualPost', __('Wordpress Website', 'autowp'), __('AI Rewrite Post From Wordpress Website', 'autowp'), 'manage_options', 'manual_post_wp_website_form', 'autowp_manual_post_wp_form_page_handler');
  add_submenu_page('autowp_manualPost', __('Wordpress Website', 'autowp'), __('Wordpress Website', 'autowp'), 'manage_options', 'manual_post_news_website_form', 'autowp_manual_post_news_form_page_handler');

  //Prompt Settings



  //Add a new submenu for Prompt Scheme Management

  add_submenu_page('autowp_promptSettings', __('Writing Prompt Schemes', 'autowp'), __('Writing Prompt Schemes', 'autowp'), 'manage_options', 'autowp_promptschemes', 'autowp_writing_promptscheme_page_handler');
  add_submenu_page('autowp_promptSettings', __('Content Planner', 'autowp'), __('Prompt Schemes', 'autowp'), 'manage_options', 'autowp_rewriting_promptschemes', 'autowp_rewriting_promptscheme_page_handler');

  add_submenu_page(
    'autowp_menu',            // Parent slug (the AutoWP main menu)
    __('Transactions', 'autowp'), // Page title
    __('Transactions', 'autowp'), // Menu title
    'manage_options',         // Capability required to access the page
    'autowp_transactions',    // Menu slug
    'autowp_transactions_page_handler' // Function to display the content
);

  // Add a new submenu page for Settings
  add_submenu_page('autowp_menu', __('Settings', 'autowp'), __('Settings', 'autowp'), 'activate_plugins', 'autowp_settings', 'autowp_settings_page_handler');

    
}

add_action('admin_menu','autowp_setAdminMenu');



function autowp_transactions_page_handler() {


   $server_url = unserialize(get_option("autowp_settings"))["autowp_server_url"];


   $autowp_server_url = 'https://api.autowp.app';
   

  if ( isset( $server_url ) 
     && $server_url !== null 
     && $server_url !== '' 
     && strpos( $server_url, 'autowp.app' ) === false
  ) {
     $autowp_server_url = $server_url;

  }



  // Enqueue Bootstrap styles and scripts
  wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
  wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), null, true);

  ?>
  <div class="container-fluid mt-5">
      <h1 class="text-center"><?php esc_html_e('Transactions Overview', 'autowp'); ?></h1>

      
      <!-- Tab Navigation -->
      <ul class="nav nav-tabs" id="transactionsTabs" role="tablist">
          <li class="nav-item">
              
              <a class="nav-link active" id="post-tab" data-toggle="tab" href="#post" role="tab" aria-controls="post" aria-selected="true"><?php esc_html_e('Post Transactions', 'autowp'); ?></a>

          </li>
          <li class="nav-item">
              <a class="nav-link" id="image-tab" data-toggle="tab" href="#image" role="tab" aria-controls="image" aria-selected="false"><?php esc_html_e('Image Transactions', 'autowp'); ?></a>

          </li>
          <li class="nav-item">
              <a class="nav-link" id="exceptions-tab" data-toggle="tab" href="#exceptions" role="tab" aria-controls="exceptions" aria-selected="false"><?php esc_html_e('Error Logs', 'autowp'); ?></a>
          </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content" id="transactionsTabsContent">
          <!-- Post Transactions Tab -->
          <div class="tab-pane fade show active" id="post" role="tabpanel" aria-labelledby="post-tab">
              
                  <div class="card-header">
                     
                      <h2><?php esc_html_e('Post Transactions', 'autowp'); ?></h2>

                  </div>
                  <div class="card-body p-0">

                      <div class="spinner-border text-primary" role="status" id="post-loading">
                          <span class="sr-only">Loading...</span>
                      </div>
                      <!-- Tam genişlikte tablo ve responsive yapı -->
                      <div class="table-responsive">
                          <table class="table table-striped table-bordered w-100" id="post-transactions-table" style="display:none;">
                              <thead class="thead-light">
                                  <tr id="post-transactions-header"></tr>
                              </thead>
                              <tbody></tbody>
                          </table>
                      </div>
                      <div class="alert alert-info" id="post-no-data" style="display:none;">No Post Transactions found.</div>
                  </div>
              
          </div>

          <!-- Image Transactions Tab -->
          <div class="tab-pane fade" id="image" role="tabpanel" aria-labelledby="image-tab">
              
                  <div class="card-header">
                      <h2><?php esc_html_e('Image Transactions', 'autowp'); ?></h2>
                  </div>
                  <div class="card-body p-0">

                      <div class="spinner-border text-primary" role="status" id="image-loading">
                          <span class="sr-only">Loading...</span>
                      </div>
                      <div class="table-responsive">
                          <table class="table table-striped table-bordered w-100" id="image-transactions-table" style="display:none;">
                              <thead class="thead-light">
                                  <tr id="image-transactions-header"></tr>
                              </thead>
                              <tbody></tbody>
                          </table>
                      </div>
                      <div class="alert alert-info" id="image-no-data" style="display:none;">No Image Transactions found.</div>
                  </div>
              
          </div>

          <!-- Exceptions Tab -->
          <div class="tab-pane fade" id="exceptions" role="tabpanel" aria-labelledby="exceptions-tab">
            
                  <div class="card-header">
                      <h2><?php esc_html_e('Error Logs', 'autowp'); ?></h2>
                  </div>
                  <div class="card-body p-0">

                      <div class="spinner-border text-primary" role="status" id="exceptions-loading">
                          <span class="sr-only">Loading...</span>
                      </div>
                      <div class="table-responsive">
                          <table class="table table-striped table-bordered w-100" id="exceptions-table" style="display:none;">
                              <thead class="thead-light">
                                  <tr id="exceptions-header"></tr>
                              </thead>
                              <tbody></tbody>
                          </table>
                      </div>
                      <div class="alert alert-info" id="exceptions-no-data" style="display:none;">No Exceptions found.</div>
                  </div>
              </div>
          </div>
      
  </div>

  <script type="text/javascript">

    const API_BASE_URL = '<?php echo esc_js( $autowp_server_url ); ?>';
    
  document.addEventListener('DOMContentLoaded', function() {
      var userEmail = '<?php 
    $settings = unserialize(get_option('autowp_settings')); 
    echo esc_js($settings["api_email"]); 
?>'; // Get the API email from autowp_settings

      fetchTransactions(userEmail);
  });

  function createTableHeaders(fields, tableHeaderId) {
      let headerHtml = '';
      fields.forEach(field => {
          headerHtml += '<th>' + field + '</th>';
      });
      document.getElementById(tableHeaderId).innerHTML = headerHtml;
  }

  function fetchTransactions(userEmail) {
      // Post Transactions
      fetch(API_BASE_URL + '/post_transactions?user_email=' + encodeURIComponent(userEmail))

      .then(response => response.json())
      .then(data => {
          var postTable = document.getElementById('post-transactions-table');
          var postLoading = document.getElementById('post-loading');
          var postNoData = document.getElementById('post-no-data');
          if (data.length > 0) {
              postTable.style.display = 'table';
              const fields = Object.keys(data[0]);
              createTableHeaders(fields, 'post-transactions-header');
              let html = '';
              data.forEach(transaction => {
                  html += '<tr>';
                  fields.forEach(field => {
                   
                      html += '<td>' + transaction[field] + '</td>';
                  });
                  html += '</tr>';
              });
              postTable.querySelector('tbody').innerHTML = html;
          } else {
              postNoData.style.display = 'block';
          }
          postLoading.style.display = 'none';
      });

      // Image Transactions
      fetch(API_BASE_URL + '/image_transactions?user_email=' + encodeURIComponent(userEmail))

      .then(response => response.json())
      .then(data => {
          var imageTable = document.getElementById('image-transactions-table');
          var imageLoading = document.getElementById('image-loading');
          var imageNoData = document.getElementById('image-no-data');
          if (data.length > 0) {
              imageTable.style.display = 'table';
              const fields = Object.keys(data[0]);
              createTableHeaders(fields, 'image-transactions-header');
              let html = '';
              data.forEach(transaction => {
                  html += '<tr>';
                  fields.forEach(field => {
                      if (field === 'image_url') {
                          html += '<td><img src="' + transaction[field] + '" alt="Image" class="img-thumbnail" width="100"></td>';
                      } else {
                          html += '<td>' + transaction[field] + '</td>';
                      }
                  });
                  html += '</tr>';
              });
              imageTable.querySelector('tbody').innerHTML = html;
          } else {
              imageNoData.style.display = 'block';
          }
          imageLoading.style.display = 'none';
      });

      // Exceptions
      fetch(API_BASE_URL + '/exceptions?user_mail=' + encodeURIComponent(userEmail))

      .then(response => response.json())
      .then(data => {
          var exceptionsTable = document.getElementById('exceptions-table');
          var exceptionsLoading = document.getElementById('exceptions-loading');
          var exceptionsNoData = document.getElementById('exceptions-no-data');
          if (data.length > 0) {
              exceptionsTable.style.display = 'table';
              const fields = Object.keys(data[0]);
              createTableHeaders(fields, 'exceptions-header');
              let html = '';
              data.forEach(exception => {
                  html += '<tr>';
                  fields.forEach(field => {
                      html += '<td>' + exception[field] + '</td>';
                  });
                  html += '</tr>';
              });
              exceptionsTable.querySelector('tbody').innerHTML = html;
          } else {
              exceptionsNoData.style.display = 'block';
          }
          exceptionsLoading.style.display = 'none';
      });
  }
  </script>
  <?php
}




?>
