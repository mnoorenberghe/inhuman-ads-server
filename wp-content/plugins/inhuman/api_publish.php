<?php

  require_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php');
  use PHPHtmlParser\Dom;

  //
  // Add screenshot (XHR from add-on method)
  //
  function inhuman_add_screenshot() {
    $data = json_decode(file_get_contents('php://input'), true);
    $shotId = $data["shotId"];
    $shotDomain = $data["shotDomain"];
    $url = sanitize_text_field("https://screenshots.firefox.com/{$shotId}/{$shotDomain}");

    $dom = new Dom;
    $dom->loadFromUrl($url);
    $domain = $dom->find('.shot-subtitle .subtitle-link')[0]->text;

    $post_id = wp_insert_post(array(
      'post_title'    => '',
      'post_content'  => '',
      'post_status'   => 'publish',
      'post_author'   => get_current_user_id(),
      'post_type' => 'inhuman_screenshot',
      'meta_input' => array(
        'inhuman_meta_type' => 'screenshot',
        'inhuman_meta_status' => 'draft',
        'inhuman_meta_shot_url' => $url,
        'inhuman_meta_publisher_domain' => $domain
      )
    ));

    // download screenshot image, add is as an attachment, and set it
    // as the featured image (thumbnail)

    $img = $dom->find('#clipImage')[0];
    media_sideload_image($img->src, $post_id, "Screenshot");

    // finds the last image added to the post attachments
    $attachments = get_posts(array(
      'numberposts' => '1',
      'post_parent' => $post_id,
      'post_type' => 'attachment',
      'post_mime_type' => 'image',
      'order' => 'ASC'));

    if(sizeof($attachments) > 0){
      set_post_thumbnail($post_id, $attachments[0]->ID);
    }

    echo json_encode(array('success' => true,
                           'url' => get_permalink($post_id),
                           'editUrl' => get_permalink($post_id) . "?edit"
    ));

    die();
  }
  add_action('wp_ajax_inhuman_add_screenshot', 'inhuman_add_screenshot');

  //
  // Update/publish screenshot (XHR from page)
  //
  function inhuman_update_screenshot() {
    $raw = json_decode(file_get_contents('php://input'), true);
    $post_id = sanitize_text_field($raw["post_id"]);
    $author = get_post_field('post_author', $post_id);

    if ($author != get_current_user_id()) {
      http_response_code(401);
      die();
    } elseif (false) {
      // FIXME: check if flagged for moderation / as spam
    } else {
      $offensive = sanitize_text_field($raw["offensive"]);
      wp_update_post(array(
        'ID' => $post_id,
        'post_title' => sanitize_text_field($raw["caption"]),
        'post_status' => 'publish',
        'meta_input' => array(
          'inhuman_meta_status' => 'publish',
          'inhuman_meta_ad_brand' => sanitize_text_field($raw["brand"]),
          'inhuman_meta_offensive' => $offensive
        )));
      if ($offensive) {
        _inhuman_report($post_id);
        _inhuman_flag_confirm($post_id);
      }

      echo json_encode(array('success' => true));

      die();
    }
  }
  add_action('wp_ajax_inhuman_update_screenshot', 'inhuman_update_screenshot');

  //
  // Remove screenshot
  //
  function inhuman_delete_screenshot() {
    $data = json_decode(file_get_contents('php://input'), true);
    $post_id = sanitize_text_field($data["post_id"]);
    $author_id = get_post_field('post_author', $post_id);
    $success = false;

    if ($author_id == get_current_user_id()) {
      if (wp_delete_post($post_id))
        $success = true;
    } else {
      http_response_code(401);
      die();
    }

    echo json_encode(array('success' => $success));
    die();
  }
  add_action('wp_ajax_inhuman_delete_screenshot', 'inhuman_delete_screenshot');

?>
