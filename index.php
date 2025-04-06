<?php
//آپارات

// 1. افزودن متاباکس
add_action('add_meta_boxes', 'rotify_aparat_add_metabox');
function rotify_aparat_add_metabox() {
    add_meta_box(
        'rotify_aparat_metabox',
        'ویدیو آپارات',
        'rotify_aparat_metabox_callback',
        ['post', 'product'], // یا فقط 'product' برای ووکامرس
        'normal',
        'default'
    );
}

function rotify_aparat_metabox_callback($post) {
    $video_url = get_post_meta($post->ID, '_rotify_aparat_url', true);
    echo '<label for="rotify_aparat_url">لینک ویدیو آپارات:</label>';
    echo '<input type="text" id="rotify_aparat_url" name="rotify_aparat_url" value="' . esc_attr($video_url) . '" style="width:100%;" />';
}

// 2. ذخیره لینک ویدیو
add_action('save_post', 'rotify_aparat_save_meta');
function rotify_aparat_save_meta($post_id) {
    if (array_key_exists('rotify_aparat_url', $_POST)) {
        update_post_meta($post_id, '_rotify_aparat_url', sanitize_text_field($_POST['rotify_aparat_url']));
    }
}

// 3. شورتکد برای نمایش ویدیو
add_shortcode('aparat_video', 'rotify_aparat_video_shortcode');
function rotify_aparat_video_shortcode($atts) {
    $post_id = get_the_ID();
    $video_url = get_post_meta($post_id, '_rotify_aparat_url', true);

    if (!$video_url) return '<p>لینک ویدیو وارد نشده است.</p>';

    // استخراج hash از لینک‌های مختلف آپارات
    $video_hash = '';

    // ساختار معمولی aparat.com/v/ujhppfi
    if (preg_match('/aparat\.com\/v\/([a-zA-Z0-9_-]+)/', $video_url, $matches)) {
        $video_hash = $matches[1];
    }

    // ساختار embed
    elseif (preg_match('/videohash\/([a-zA-Z0-9_-]+)/', $video_url, $matches)) {
        $video_hash = $matches[1];
    }

    if (!$video_hash) {
        return '<p>ویدیوی معتبر یافت نشد.</p>';
    }

    return '<div class="rotify-aparat-wrapper" style="margin:20px 0;">
        <iframe src="https://www.aparat.com/video/video/embed/vt/frame/showvideo/yes/videohash/' . esc_attr($video_hash) . '" 
        width="100%" height="400" allowfullscreen style="border:none;"></iframe>
    </div>';
}

//[aparat_video]

?>


