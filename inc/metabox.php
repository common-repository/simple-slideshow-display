<?php
for ($i = 0; $i < 3; $i++) {
    if (empty($data_tables[$i]['bs_img_slidshow_title']))
        $data_tables[$i]['bs_img_slidshow_title'] = '';
    if (empty($data_tables[$i]['bs_img_slidshow_subtitle']))
        $data_tables[$i]['bs_img_slidshow_subtitle'] = '';
    if (empty($data_tables[$i]['bs_img_slidshow_btn_txt']))
        $data_tables[$i]['bs_img_slidshow_btn_txt'] = '';
    if (empty($data_tables[$i]['bs_img_slidshow_btn_url']))
        $data_tables[$i]['bs_img_slidshow_btn_url'] = '';
    if (empty($data_tables[$i]['bs_img_slidshow_small_txt']))
        $data_tables[$i]['bs_img_slidshow_small_txt'] = '';
    if (empty($data_tables[$i]['bs_img_slidshow_bottom_txt']))
        $data_tables[$i]['bs_img_slidshow_bottom_txt'] = '';
    if (empty($data_tables[$i]['bs_img_slidshow_pic']))
        $data_tables[$i]['bs_img_slidshow_pic'] = '';


    ?>

    <div class="bs_touch_slideshow">
        <h3 class="table_title">slideshow <?php echo $i + 1 ?></h3>
        <div class="bs_touch_slideshow_des">
            <div class="bs_touch_slideshow_left">
                <label class="bs_price_table_label" >Background Image</label>
            </div>
            <div class="bs_touch_slideshow_right">
                <input class="price_table_cl bs_img_value_<?= $i ?>" type="text" value="<?= esc_attr($data_tables[$i]['bs_img_slidshow_pic']); ?>" disabled></input>
                <input class="price_table_cl bs_img_value_<?= $i ?>" type="hidden" name="<?= 'bs_touch_slideshow_group[' . $i . '][bs_img_slidshow_pic]'; ?>" value="<?= $data_tables[$i]['bs_img_slidshow_pic']; ?>"></input>
                <a href="#" id='bs_add_touch_slideshow_<?= $i ?>' class="bs_touch_slideshow_btn">Upload</a>
            </div>
            <div class="bs_img_show">
                <img class="bs_touch_slideshow_img_<?= $i ?>" src="<?php echo esc_attr($data_tables[$i]['bs_img_slidshow_pic']); ?>"></img>
            </div>
            <div class="bs_touch_slideshow_left">
                <label class="bs_price_table_label_title" >Title</label>
            </div>
            <div class="bs_touch_slideshow_right">
                <input class="price_table_cl" type="text" name="<?= 'bs_touch_slideshow_group[' . $i . '][bs_img_slidshow_title]'; ?>" value="<?= esc_attr($data_tables[$i]['bs_img_slidshow_title']); ?>"></input>
            </div>
              <div class="bs_touch_slideshow_left">
                <label class="bs_price_table_label_subtitle" >Subtitle</label>
            </div>
            <div class="bs_touch_slideshow_right">
                <input class="price_table_cl" type="text" name="<?= 'bs_touch_slideshow_group[' . $i . '][bs_img_slidshow_subtitle]'; ?>" value="<?= esc_attr($data_tables[$i]['bs_img_slidshow_subtitle']); ?>"></input>
            </div>
            <div class="bs_touch_slideshow_left">
                <label class="bs_price_table_label_btn-txt" >Button Text</label>
            </div>
            <div class="bs_touch_slideshow_right">
                <input class="price_table_cl" type="text" name="<?= 'bs_touch_slideshow_group[' . $i . '][bs_img_slidshow_btn_txt]'; ?>" value="<?= esc_attr($data_tables[$i]['bs_img_slidshow_btn_txt']); ?>"></input>
            </div>

             <div class="bs_touch_slideshow_left">
                <label class="bs_price_table_label_btn-txt" >Button URL</label>
            </div>
            <div class="bs_touch_slideshow_right">
                <input class="price_table_cl" type="text" name="<?= 'bs_touch_slideshow_group[' . $i . '][bs_img_slidshow_btn_url]'; ?>" value="<?= esc_attr($data_tables[$i]['bs_img_slidshow_btn_url']); ?>"></input>
            </div>
            <div class="bs_touch_slideshow_left">
                <label class="bs_price_table_label_btn-txt" >Small Text</label>
            </div>
            <div class="bs_touch_slideshow_right">
                <input class="price_table_cl" type="text" name="<?= 'bs_touch_slideshow_group[' . $i . '][bs_img_slidshow_small_txt]'; ?>" value="<?= esc_attr($data_tables[$i]['bs_img_slidshow_small_txt']); ?>"></input>
            </div>
            <div class="bs_touch_slideshow_left">
                <label class="bs_price_table_label_btn-txt" >Bottom Text</label>
            </div>
            <div class="bs_touch_slideshow_right">
                <input class="price_table_cl" type="text" name="<?= 'bs_touch_slideshow_group[' . $i . '][bs_img_slidshow_bottom_txt]'; ?>" value="<?= esc_attr($data_tables[$i]['bs_img_slidshow_bottom_txt']); ?>"></input>
            </div>
            
            
          
          
       </div>
    </div>

    <script>

        jQuery.noConflict();
        jQuery(function ($) {
            var frame,
                    bs_img_slideshow_metaBox = $('#bs_touch_slideshow_meta_id'),
                    bs_add_img = bs_img_slideshow_metaBox.find('#bs_add_touch_slideshow_<?= $i ?>'),
                    imgContainer = bs_img_slideshow_metaBox.find('.bs_touch_slideshow_img_<?= $i ?>'),
                    imgIdInput = bs_img_slideshow_metaBox.find('.bs_img_value_<?= $i ?>');

            bs_add_img.on('click', function (event) {
                event.preventDefault();
                if (frame) {
                    frame.open();
                    return;
                }
                frame = wp.media({
                    title: 'Select or Upload Media Of Your Chosen Persuasion',
                    button: {
                        text: 'Use this media'
                    },
                    multiple: false

                });

                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first().toJSON();
                    console.log(imgContainer.attr('src', attachment.url));
                    imgIdInput.attr('value', attachment.url);
                });
                frame.open();
            });
        });
    </script>
<?php } ?>

















