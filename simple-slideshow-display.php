<?php

/*

  Plugin Name: Simple Slideshow Display

  Plugin URI:http://www.crayfishcreative.com

  Description: Thanks for installing Simple Slideshow Display.

  Version: 1.0

  Author: Amisha Patel

  Author URI: http://www.crayfishcreative.com

 */

include dirname(__FILE__) . '/inc/bs-simple-slideshow-display-post.php';
include dirname(__FILE__) . '/simple-slideshow-display-shortcode.php';

class Bs_simple_slideshow {
    public function Bs_simple_Instance() {
        $custom_post = new Bs_simple_slideshow_Post('bs-touch-slideshow');
        $custom_post->Bs_simple_slideshow_Post('bs_touch_slideshow', 'Responsive Simple SlideShow', 'Responsive Simple SlideShows', array('supports' => array('title',)));
        add_action('admin_init', array($this, 'bs_touch_slideshow_metabox_feild'));  //metabox
        add_action('admin_init', array($this, 'bs_touch_slideshow_metabox_feild_shortcode'));  //shortcode
        add_action('save_post', array($this,  'add_bs_touch_slideshow'), 10, 2);   //metabox save
        add_action('admin_menu', array($this, 'bs_touch_slidshow_setting'));   // add submenu
        add_action('admin_init', array($this, 'bs_touch_slidshow_register_settings'));  // setting page opton
        add_action('admin_head', array($this, 'bs_touch_slideshow_admin_css'));
        add_action('admin_enqueue_scripts', array($this, 'bs_touch_slideshow_load_wp_media_files'));
    }

    public function bs_simple_slideshow_getInstance() {
        $this->Bs_simple_Instance();
    }

    public function bs_touch_slideshow_metabox_feild() {
        add_meta_box('bs_touch_slideshow_meta_id', 'Add slideshow', array($this, 'display_bs_touch_slideshow_metabox'), 'bs_touch_slideshow', 'normal', 'high');
    }

    public function bs_touch_slideshow_metabox_feild_shortcode() {
        add_meta_box('bs_touch_slideshow_meta_shortcode', 'ShortCode', array($this, 'display_bs_touch_slideshow_metabox_shortcode'), 'bs_touch_slideshow', 'side', 'low');

    }

    public function display_bs_touch_slideshow_metabox_shortcode($bs_touch_slideshow) {

        ?>
        <div class="bs_price">
            <input type="text" name="bs_touch_slideshow_shortcode[]" value="[bs_simple_slideshow id='<?= get_the_id(); ?>']" disabled></input>
        </div>
        <?php

    }

    public function display_bs_touch_slideshow_metabox($bs_touch_slideshow) {
        wp_nonce_field('bs_touch_slidshow_nonce', 'bs_touch_slidshow_nonce_field');
        $data_tables = get_post_meta($bs_touch_slideshow->ID, '_bs_touch_slideshow_group', true);
        include dirname(__FILE__) . '/inc/metabox.php';
    }

    public function add_bs_touch_slideshow($post_id, $bs_touch_slideshow) {
        if (!isset($_POST['bs_touch_slidshow_nonce_field']) || !wp_verify_nonce($_POST['bs_touch_slidshow_nonce_field'], 'bs_touch_slidshow_nonce')) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if ($bs_touch_slideshow->post_type == 'bs_touch_slideshow') {

            if (empty($_POST['bs_touch_slideshow_group'])) {
                $_POST['bs_touch_slideshow_group'] = array('');
            }

            foreach ($_POST['bs_touch_slideshow_group'] as $key => $data_table) {
                $bs_touch_slidshow_group_array[] = array_map('sanitize_text_field',$data_table);
                foreach ($bs_touch_slidshow_group_array as $key => $data_table) {
                    if (empty($data_table['bs_img_slidshow_title']) && 
                        empty($data_table['bs_img_slidshow_subtitle']) &&
                        empty($data_table['bs_img_slidshow_btn_txt']) &&
                        empty($data_table['bs_img_slidshow_btn_url']) &&
                        empty($data_table['bs_img_slidshow_small_txt']) &&
                        empty($data_table['bs_img_slidshow_bottom_txt']) &&
                        empty($data_table['bs_img_slidshow_pic']))
                        unset($bs_touch_slidshow_group_array[$key]);
                }
            }

            if (isset($_POST['bs_touch_slideshow_group']) &&
                    $_POST['bs_touch_slideshow_group'] != '') {
                update_post_meta($post_id, '_bs_touch_slideshow_group', $bs_touch_slidshow_group_array);
            }
        }
    }

    public function bs_touch_slidshow_setting() {
        add_submenu_page('edit.php?post_type=bs_touch_slideshow', __('Slidshow Settings', 'bs-touch-slideshow'), __('Settings', 'bs-touch-slideshow'), 'manage_options', 'bs_touch_slideshow_setting', array($this, 'bs_touch_slidshow_setting_field'));
    }

    public function bs_touch_slidshow_setting_field() { ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <form method="post" action="options.php" enctype="multipart/form-data">
                <?php settings_fields('bs_touch_slidshow_options'); ?>
                <?php do_settings_sections('bs_img_slidshow'); ?>
                <p class="submit">
                    <input name="submit" type="submit" class="button-primary" value="Save Changes"/>
                </p>
            </form>
        </div>

    <?php }

    public function bs_touch_slidshow_register_settings() {
        register_setting('bs_touch_slidshow_options', 'bs_touch_slidshow_options');
        add_settings_section('bs_touch_slideshow', '', array($this, 'bs_video_section_text'), 'bs_img_slidshow');
        add_settings_field('bs_displayControls', __('Display Controls', 'bs-touch-slideshow'), array($this, 'bs_touch_slidshow_displayControls'), 'bs_img_slidshow', 'bs_touch_slideshow');
        //add_settings_field('bs_autoSlide', __('Single Item', 'bs-touch-slideshow'), array($this, 'bs_touch_slidshow_autoSlide'), 'bs_img_slidshow', 'bs_touch_slideshow');
        //add_settings_field('bs_items', __('Items', 'bs-touch-slideshow'), array($this, 'bs_touch_slidshow_items'), 'bs_img_slidshow', 'bs_touch_slideshow');
        add_settings_field('bs_effect', __('Autoplay', 'bs-touch-slideshow'), array($this, 'bs_touch_slidshow_transitionEffect'), 'bs_img_slidshow', 'bs_touch_slideshow');
        add_settings_field('bs_touchControls', __('PaginationSpeed', 'bs-touch-slideshow'), array($this, 'bs_touch_slidshow_touchControls'), 'bs_img_slidshow', 'bs_touch_slideshow');
        add_settings_field('bs_listPosition', __('Loop', 'bs-touch-slideshow'), array($this, 'bs_touch_slidshow_listPosition'), 'bs_img_slidshow', 'bs_touch_slideshow');
       // add_settings_field('bs_displayList', __('Display List', 'bs-touch-slideshow'), array($this, 'bs_touch_slidshow_displayList'), 'bs_img_slidshow', 'bs_touch_slideshow');
        add_settings_field('bs_adaptiveHeight', __('Stop On Hover', 'bs-touch-slideshow'), array($this, 'bs_touch_slidshow_adaptiveHeight'), 'bs_img_slidshow', 'bs_touch_slideshow');
        add_settings_field('bs_transitionDuration', __('Transition Duration', 'bs-touch-slideshow'), array($this, 'bs_touch_slidshow_transitionDuration'), 'bs_img_slidshow', 'bs_touch_slideshow');
         add_settings_field('bs_imageheight', __('Slider Image Height', 'bs-touch-slideshow'), array($this, 'bs_simple_slidshow_imageheight'), 'bs_img_slidshow', 'bs_touch_slideshow');

    }

    public function bs_video_section_text()
    {
       echo "<h2>Responsive Simple SlideShow Configuration</h2>";

    }

    public function bs_touch_slidshow_items(){
         $bs_slideshow_options = get_option('bs_touch_slidshow_options');
        echo "<select id='bs_items' name='bs_touch_slidshow_options[bs_items]'>";
        $know = array(1,2,3);
        foreach ($know as $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bs_slideshow_options['bs_items']) {
                echo 'selected="selected"';
            }
            echo '>' . $v . '</option>';

        }
        echo "</select>";
        echo '<span> Must Be Select Single Item "NO" </span>';

    }

    public function bs_touch_slidshow_displayControls() {
        $bs_slideshow_options = get_option('bs_touch_slidshow_options');
        echo "<select id='bs_displayControls' name='bs_touch_slidshow_options[bs_displayControls]'>";
        $know = array('Yes' => 'true', 'No' => 'false');
        foreach ($know as $key => $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bs_slideshow_options['bs_displayControls']) {
                echo 'selected="selected"';
            }
            echo '>' . $key . '</option>';
        }
        echo "</select>";

    }


    public function bs_touch_slidshow_autoSlide() {
        $bs_slideshow_options = get_option('bs_touch_slidshow_options');
        echo "<select id='bs_autoSlide' name='bs_touch_slidshow_options[bs_autoSlide]'>";
        $know = array('Yes' => 'true', 'No' => 'false');
        foreach ($know as $key => $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bs_slideshow_options['bs_autoSlide']) {
                echo 'selected="selected"';
            }
            echo '>' . $key . '</option>';
        }
        echo "</select>";

    }

    public function bs_touch_slidshow_transitionEffect() {
        $bs_slideshow_options = get_option('bs_touch_slidshow_options');
        echo "<select id='bs_effect' name='bs_touch_slidshow_options[bs_effect]'>";
        $know = array('Yes' => 'true', 'No' => 'false');
        foreach ($know as $key => $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bs_slideshow_options['bs_effect']) {
                echo 'selected="selected"';
            }
            echo '>' . $key . '</option>';
        }
        echo "</select>";
    }

    public function bs_touch_slidshow_touchControls() {
        //echo "<select id='bs_touchControls' name='bs_touch_slidshow_options[bs_touchControls]'>";
        $bs_slideshow_options = get_option('bs_touch_slidshow_options');
        empty($bs_slideshow_options['bs_touchControls']) ? $bs_slideshow_options['bs_touchControls'] = 500 : $bs_slideshow_options['bs_touchControls'];
        echo "<input id='bs_touchControls' name='bs_touch_slidshow_options[bs_touchControls]' size='20' type='text' value='{$bs_slideshow_options['bs_touchControls']}' />";
    }

    

    public function bs_touch_slidshow_listPosition() {
        $bs_slideshow_options = get_option('bs_touch_slidshow_options');
        echo "<select id='bs_listPosition' name='bs_touch_slidshow_options[bs_listPosition]'>";
        $know = array('Yes' => 'true', 'No' => 'false');
        foreach ($know as $key => $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bs_slideshow_options['bs_listPosition']) {
                echo 'selected="selected"';
            }
            echo '>' . $key . '</option>';
        }
        echo "</select>";
    }
    public function bs_touch_slidshow_displayList() {
        $bs_slideshow_options = get_option('bs_touch_slidshow_options');
        echo "<select id='bs_displayList' name='bs_touch_slidshow_options[bs_displayList]'>";
        $know = array('Yes' => 'true', 'No' => 'false');
        foreach ($know as $key => $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bs_slideshow_options['bs_displayList']) {
                echo 'selected="selected"';
            }
            echo '>' . $key . '</option>';
        }
        echo "</select>";
    }

    public function bs_touch_slidshow_adaptiveHeight() {
        $bs_slideshow_options = get_option('bs_touch_slidshow_options');
        echo "<select id='bs_adaptiveHeight' name='bs_touch_slidshow_options[bs_adaptiveHeight]'>";
        $know = array('Yes' => 'true', 'No' => 'false');
        foreach ($know as $key => $v) {
            echo '<option value="' . $v . '"';
            if ($v == $bs_slideshow_options['bs_adaptiveHeight']) {
                echo 'selected="selected"';
            }
            echo '>' . $key . '</option>';

        }
        echo "</select>";
    }

    public function bs_touch_slidshow_transitionDuration() {
        $bs_slideshow_options = get_option('bs_touch_slidshow_options');
        empty($bs_slideshow_options['bs_transitionDuration']) ? $bs_slideshow_options['bs_transitionDuration'] = 500 : $bs_slideshow_options['bs_transitionDuration'];
        echo "<input id='bs_transitionDuration' name='bs_touch_slidshow_options[bs_transitionDuration]' size='20' type='text' value='{$bs_slideshow_options['bs_transitionDuration']}' />";
    }


    public function bs_simple_slidshow_imageheight(){
        $bs_slideshow_options = get_option('bs_touch_slidshow_options');
        empty($bs_slideshow_options['bs_imageheight']) ? $bs_slideshow_options['bs_imageheight'] = 400 : $bs_slideshow_options['bs_imageheight'];
        echo "<input id='bs_imageheight' name='bs_touch_slidshow_options[bs_imageheight]' size='20' type='text' value='{$bs_slideshow_options['bs_imageheight']}' />";
       
    }

    public function bs_touch_slideshow_admin_css() {
        wp_enqueue_style('bs_touch_slideshow_admin_css', plugin_dir_url(__FILE__) . 'css/admin_style.css');
    }
    public function bs_touch_slideshow_load_wp_media_files() {
        wp_enqueue_media();
    }

}

$var = new Bs_simple_slideshow();
$var->bs_simple_slideshow_getInstance();



