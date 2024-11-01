<?php

class Bs_simple_slidshow_shortcode {
    public function __construct() {
        add_shortcode('bs_simple_slideshow', array($this, 'show_shortcode_bs_touch_slideshow'));
        add_action('wp_enqueue_scripts', array($this, 'bs_touch_slideshow_enqueue_scripts'));
    }
    private function bs_simple_slideshow($atts, $content = NULL) {
        extract(shortcode_atts(
                        array(
                          'id' => '',
                           ), $atts)
        );
        $query_args = array(
            'p' => (!empty($id)) ? $id : -1,
            'posts_per_page' => -1,
            'post_type' => 'bs_touch_slideshow',
            'order' => 'DESC',
            'orderby' => 'menu_order',
        );

        $wp_query = new WP_Query($query_args);
        if ($wp_query->have_posts()):while ($wp_query->have_posts()) : $wp_query->the_post();
                return $data_tables = get_post_meta($id, '_bs_touch_slideshow_group', true);
            endwhile;
        else: echo 'No touch slideshow Found';
        endif;
    }
    public function bs_slideshow_get_option()
    {
        $bs_slideshow_options_array= array(
                'bs_touch_slidshow_options'=>array(
                'bs_displayControls' =>'true',
                'bs_transitionDuration'=>300,
                'bs_touchControls'=>400,
                'bs_autoSlide'=>'true',
                'bs_listPosition'=>'true',
                'bs_effect'=>'true',
                'bs_items'=>1,
                'bs_adaptiveHeight'=>'true'
            ));

        foreach ($bs_slideshow_options_array as $key => $value) {
            return $value;
        }
    }
    public function show_shortcode_bs_touch_slideshow($atts, $content = NULL) {
        $bs_slideshow_options =get_option('bs_touch_slidshow_options');
        if(empty($bs_slideshow_options)){
            $bs_slideshow_options=$this->bs_slideshow_get_option();
        }
        $bs_navigation = $bs_slideshow_options['bs_displayControls'];
        $bs_slideSpeed = $bs_slideshow_options['bs_transitionDuration'];
        $bs_pagination = $bs_slideshow_options['bs_touchControls'];
        //$bs_single_item = $bs_slideshow_options['bs_autoSlide'];
        $bs_autoplay = $bs_slideshow_options['bs_effect'];
        $bs_loop = $bs_slideshow_options['bs_listPosition'];
        $bs_stopOnHover = $bs_slideshow_options['bs_adaptiveHeight'];
       // $bs_items = $bs_slideshow_options['bs_items'];
        $bs_height = $bs_slideshow_options['bs_imageheight'];
        $data_values = $this->bs_simple_slideshow($atts, $content = NULL);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery("#owl-demo").owlCarousel({
                navigation : <?php echo $bs_navigation;?>,
                slideSpeed : <?php echo $bs_slideSpeed;?>,
                paginationSpeed : <?php echo $bs_pagination;?>,
                singleItem : true,
                loop:<?php echo $bs_loop;?>,
                autoPlay: <?php echo $bs_autoplay;?>,
                stopOnHover : <?php echo $bs_stopOnHover;?>,
                navigationText: ["<img src=<?php echo plugin_dir_url(__FILE__).'/images/left.png';?>>","<img src='<?php echo plugin_dir_url(__FILE__).'/images/right.png';?>'>"]
                  });
                });
        </script>
        <style>
            #owl-demo .item img{
                display: block;
                width: 100%;
                height: auto;
            }
            .slider-img img {
                min-height: <?php echo $bs_height;?>px;
            }
        </style> 
        <?php if($bs_navigation=='true'){?>
        <style type="text/css">
         .owl-pagination {
            position: absolute;
            bottom: 0%;
            margin: 0 auto;
            display: block;
            width: 100%;  
        }
        @media screen and (max-width: 480px) {
            .owl-pagination {
            position: absolute;
            bottom: 0%;
            }
        }
        </style> 
        <?php } else{?>
            <style>
             .owl-pagination{ display: none;}
            </style>
        <?php } ?>  
         

    <div class="mod_je_responsive_touch_slideshow">  
        <div id="demo">
            <div id="owl-demo" class="owl-carousel">
                <?php
                if (!empty($data_values)) {
                   // print_r($data_values);
                    foreach ($data_values as $key => $data_table):?>
                      <div class="item">
                          <div class="slider-img">
                            <a href="#" target="_blank">
                                <img src="<?php echo $data_table['bs_img_slidshow_pic']  ;?>" alt="">
                            </a>
                          
                           <div class="slider-box-txt">
                               <h3>
                                  <p class="title-txt"><?php echo $data_table['bs_img_slidshow_title']?></p>
                                </h3>
                                  <p class="subtitle-txt"><?php echo $data_table['bs_img_slidshow_subtitle']?></p>
                                  <span class="signup-btn"><a href="<?php echo $data_table['bs_img_slidshow_btn_url']?>">
                                    <?php echo $data_table['bs_img_slidshow_btn_txt']?></a>
                                  </span>
                                  <small><?php echo $data_table['bs_img_slidshow_small_txt']?></small>
                                <div style:"clear:both"></div>
                                   
                         </div>
                         <div class="slider-bottom-txt">
                                    <p><?php echo $data_table['bs_img_slidshow_bottom_txt']?></p>
                                </div>
                         </div>
                      </div>
                       <?php endforeach;} ?>
            </div>
        </div>
    </div>
        <div style="clear:both;"></div>
        <?php
        $content = ob_get_contents();
        ob_get_clean();
        return $content;
    }

    public function bs_touch_slideshow_enqueue_scripts() {
        wp_enqueue_style('bs_touch_css', plugin_dir_url(__FILE__) . 'css/owl.carousel.css');
        wp_enqueue_style('bs_touch_css1', plugin_dir_url(__FILE__) . 'css/owl.theme.css');
        wp_enqueue_style('bs_touch_css2', plugin_dir_url(__FILE__) . 'css/style.css');
        wp_enqueue_script('bs_touch_slideshow_js', plugin_dir_url(__FILE__) . 'js/owl.carousel.js', array('jquery'), true);
    }
}

new Bs_simple_slidshow_shortcode();





