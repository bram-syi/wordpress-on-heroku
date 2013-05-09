<?php
    /**
     * Plugin Name: Additional image sizes
     * Plugin URI: http://www.waltervos.com/wordpress-plugins/additional-image-sizes/
     * Description: Allows you to create additional image sizes (in addition to the thumbnail, medium and large size that are default) for your blog
     * Version: 1.0.2
     * Author: Walter Vos
     * Author URI: http://www.waltervos.com/
     */

    register_activation_hook( __FILE__, 'ais_activate' );
    add_action('admin_menu', 'ais_add_admin', 100);
    add_action('admin_init', 'register_ais_settings' );
    add_action('admin_notices', 'ais_activation_notice');
    add_filter('intermediate_image_sizes', 'ais_intermediate_image_sizes', 1);
    add_filter('attachment_fields_to_edit', 'ais_attachment_fields', 11, 2);

    /**
     * This function runs only when the plugin is activated and sets ais_activated to true so that
     * ais_activation_notice knows that the plugin has just been activated
     */
    function ais_activate() {
        update_option('ais_activated', true);
    }

    /**
     * This adds the admin page for the plugin. It goes under the Media tab
     */
    function ais_add_admin() {
        add_submenu_page(
            'upload.php',
            'Additional image sizes',
            'Additional image sizes',
            8,
            'ais_admin',
            'ais_display_admin'
        );
    }

    /**
     * This function registers the necessary settings/options for this plugin to work
     */
    function register_ais_settings() {
        register_setting('ais_options', 'ais_sizes');
        register_setting('ais_options', 'ais_activated');
    }

    /**
     * Displays the activation notice
     */
    function ais_activation_notice() {
        if (get_option('ais_activated')) {
            echo '<div class="updated fade" id="message">
            <p><strong>Thank you for using Additional image sizes!</strong> <a href="' . admin_url('upload.php?page=ais_admin') . '">Go here</a> to add image sizes and to regenerate copies of previously uploaded images.</p></div>';
            delete_option('ais_activated');
        }
    }

    /**
     * This function is hooked to intermediate_image_sizes and makes sure that the sizes defined
     * by this plugin get added to the blog options. Because of this, WP will automatically
     * use the sizes defined by this plugin in various situations
     *
     * @package ais
     * @param array $sizes
     * @return array
     * @uses function ais_get_user_sizes
     */
    function ais_intermediate_image_sizes($sizes) {
        $ais_sizes = ais_get_user_sizes();
        if (!empty($ais_sizes)) {
            foreach ($ais_sizes as $key => $value) {
                $sizes[] = $key;
                update_option("{$key}_size_w", $value['size_w']);
                update_option("{$key}_size_h", $value['size_h']);
                update_option("{$key}_crop", $value['crop']);
            }
        }
        return $sizes;
    }

    /**
     * This function gets the user defined image sizes from the blog options
     *
     * @return array
     */
    function ais_get_user_sizes() {
        return get_option('ais_sizes');
    }

    /**
     * This function returns WordPress' predefined image sizes
     *
     * @return array
     */
    function ais_get_reserved_sizes() {
        return array('thumbnail' => 'thumbnail', 'medium' => 'medium', 'large' => 'large');
    }

    /**
     * This function is almost a replica of image_size_input_fields() which is found in
     * wp-admin/includes/media.php. It is hooked onto attachment_fields_to_edit. Unfortunately
     * WordPress doesn't provide an easy way to filter the data in the function
     * image_attachment_fields_to_edit itself, so we append the html from image_size_input_field().
     *
     * @package ais
     * @param string $form_fields
     * @param object $post
     * @return string
     */
    function ais_attachment_fields($form_fields, $post) {
        $size_names = array();
        $ais_user_sizes = ais_get_user_sizes();
        if (is_array($ais_user_sizes)) {
            foreach($ais_user_sizes as $key => $value) {
                $size_names[$key] = $key;
            }
        }
        foreach ( $size_names as $size => $name ) {
            $downsize = image_downsize($post->ID, $size);

            // is this size selectable?
            $enabled = ( $downsize[3] || 'full' == $size );
            $css_id = "image-size-{$size}-{$post->ID}";
            // if this size is the default but that's not available, don't select it
            if ( $checked && !$enabled )
                $checked = '';
            // if $checked was not specified, default to the first available size that's bigger than a thumbnail
            if ( !$checked && $enabled && 'thumbnail' != $size )
                $checked = $size;

            $html = "<div class='image-size-item'><input type='radio' ".( $enabled ? '' : "disabled='disabled'")."name='attachments[$post->ID][image-size]' id='{$css_id}' value='{$size}'".( $checked == $size ? " checked='checked'" : '') ." />";

            $html .= "<label for='{$css_id}'>" . __($name). "</label>";
            // only show the dimensions if that choice is available
            if ( $enabled )
                $html .= " <label for='{$css_id}' class='help'>" . sprintf( __("(%d&nbsp;&times;&nbsp;%d)"), $downsize[1], $downsize[2] ). "</label>";

            $html .= '</div>';

            $out .= $html;
        }

        $form_fields['image-size']['html'] .= $out;
        return $form_fields;
    }

    /**
     * This echoes the HTML for the admin page
     */
    function ais_display_admin() {
        $messages = ais_handle_post();
        ?>
    <div class="wrap">
        <h2>Additional image sizes</h2>
        <?php if (isset($messages['errors'])) { ?>
        <div class="error below-h2" id="message">
            <p><strong>Something(s) went wrong:</strong></p>
            <ul>
                <?php foreach ($messages['errors'] as $error) { ?>
                <li><?php echo $error; ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
        <?php if (isset($messages['succes'])) { ?>
        <div class="updated fade below-h2" id="message">
            <p><strong>That went quite well:</strong></p>
            <ul>
                <?php foreach ($messages['succes'] as $succes) { ?>
                <li><?php echo $succes; ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <?php settings_fields(ais_options); ?>
                <?php
                $ais_user_sizes = ais_get_user_sizes();
                ?>
            <table cellspacing="0" class="widefat page fixed">
                <thead>
                    <tr>
                        <th class="manage-column column-author" scope="col">Delete</th>
                        <th class="manage-column column-title" scope="col">Size name</th>
                        <th class="manage-column column-author" scope="col">Width</th>
                        <th class="manage-column column-author" scope="col">Height</th>
                        <th class="manage-column column-author" scope="col">Crop?</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="manage-column column-author" scope="col">Delete</th>
                        <th class="manage-column column-title" scope="col">Size name</th>
                        <th class="manage-column column-author" scope="col">Width</th>
                        <th class="manage-column column-author" scope="col">Height</th>
                        <th class="manage-column column-author" scope="col">Crop?</th>
                    </tr>
                </tfoot>
                    <?php
                    if (!empty($ais_user_sizes)) {
                        foreach ($ais_user_sizes as $key => $value) {
                            ?>
                <tr class="alternate iedit" id="<?php echo $key; ?>">
                    <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $key; ?>" name="ais_size_delete[]"/></th>
                    <td><strong><?php echo $key; ?></strong></td>
                    <td><?php echo $value['size_w']; ?></td>
                    <td><?php echo $value['size_h']; ?></td>
                    <td><?php echo ($value['crop']) ? 'yes' : 'no'; ?></td>
                </tr>
                        <?php
                        }
                        ?>
                <tr class="alternate iedit form-table">
                    <th class="manage-column column-author" scope="col"><strong><label for="ais_new_size">New:</label></strong></th>
                    <td><strong><input type="text" name="ais_size_name" id="ais_new_size" value="<?php echo (isset($messages['errors'])) ? $_POST['ais_size_name'] : ''; ?>" /></strong></td>
                    <td><input type="text" name="ais_size_w" class="small-text" value="<?php echo (isset($messages['errors'])) ? $_POST['ais_size_w'] : ''; ?>" /></td>
                    <td><input type="text" name="ais_size_h" class="small-text" value="<?php echo (isset($messages['errors'])) ? $_POST['ais_size_h'] : ''; ?>" /></td>
                    <td><input type="checkbox" name="ais_size_crop" value="1" <?php echo (isset($messages['errors'])) ? (($_POST['ais_size_crop'] == 1) ? 'checked="checked"' : '') : ''; // It's okay if you don't get this ;-) ?> /></td>
                </tr>
                    <?php
                    }
                    else {
                        ?>
                <tr class="alternate iedit form-table">
                    <th class="check-column" scope="row"><input type="checkbox" value="" name="ais_size_delete[]"/></th>
                    <td><strong><input type="text" name="ais_size_name" value="<?php echo (isset($messages['errors'])) ? $_POST['ais_size_name'] : ''; ?>" /></strong></td>
                    <td><input type="text" name="ais_size_w" class="small-text" value="<?php echo (isset($messages['errors'])) ? $_POST['ais_size_w'] : ''; ?>" /></td>
                    <td><input type="text" name="ais_size_h" class="small-text" value="<?php echo (isset($messages['errors'])) ? $_POST['ais_size_h'] : ''; ?>" /></td>
                    <td><input type="checkbox" name="ais_size_crop" value="1" <?php echo (isset($messages['errors'])) ? (($_POST['ais_size_crop'] == 1) ? 'checked="checked"' : '') : ''; // It's okay if you don't get this ;-) ?> /></td>
                </tr>
                    <?php
                    }
                    ?>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
            </p>
        </form>
        <h3>Regenerate intermediate size images</h3>
        <p>Creating an additional image size means that for any new image you upload, a copy of this additional size will be created. If you'd like, this plugin can also create such a copy for any existing images. Click the button below to do this:</p>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <?php settings_fields(ais_options); ?>
            <input type="hidden" name="regenerate_images" value="true" />
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Generate copies of new sizes'); ?>" />
            </p>
        </form>
    </div>
        <?php
    }

    /**
     * This function handles POST's that are made from the additional image sizes admin screen. It
     * returns an array of messages, success and error messages. I would like this to be a little
     * less ugly but that would probably mean rewriting the plugin from scratch and this works, so...
     *
     * @package ais
     * @return array
     * @uses function ais_get_user_sizes
     * @uses function ais_get_predef_sizes
     */
    function ais_handle_post() {
        if ($_POST['option_page'] != 'ais_options') return false; // Without this, I'm not doing anything!

        // The request appears valid, let's see what the user wanted. There are two possibilities: 2. the user
        // wanted to regenerate copies of existing images, 2. the user added or removed a size

        // First possibility, the user wants to regenerate copies of existing images. We run
        // ais_regenerate_images and return it's return value
        if ($_POST['regenerate_images']) { return ais_regenerate_images(); }

        // Second possibility, the user wants to add a new size, or delete an exising one. First, we'll
        // initialize some variables:
        $messages = array();
        $ais_user_sizes = ais_get_user_sizes();
        $ais_predef_sizes = ais_get_reserved_sizes();

        // Sizes must be an integer
        if (!absint($_POST['ais_size_w'])) {
            // Zero is fine as well
            if ($_POST['ais_size_w'] != 0) {
                $messages['errors']['width'] = __('The width you entered is not valid.');
            }
        }
        // Sizes must be an integer
        if (!absint($_POST['ais_size_h'])) {
            // Zero is fine as well
            if ($_POST['ais_size_h'] != 0) {
                $messages['errors']['height'] = __('The height you entered is not valid.');
            }
        }
        // At least one of width and height must be set
        if ($_POST['ais_size_w'] == 0 && $_POST['ais_size_h'] == 0) {
            $messages['errors']['width_height'] = __('Width and heigh can\'t both be 0 (zero)');
        }
        // This will probably never be true
        if (isset($_POST['ais_size_crop']) && $_POST['ais_size_crop'] != 1) {
            $messages['errors'][] = __('I received some unexpected data. I didn\'t know what to do with it. Sorry!');
        }
        // We need a name for that size you know
        if (!isset($_POST['ais_size_name']) || $_POST['ais_size_name'] == '') {
            $messages['errors']['size_name'] = __('Please enter a name for this size');
        }
        if (isset($ais_user_sizes[$_POST['ais_size_name']])) {
            $messages['errors'][] = __('A size with this name already exists');
        }
        if (isset($ais_predef_sizes[$_POST['ais_size_name']])) {
            $messages['errors'][] = __('This size name is reserved, please choose another one');
        }

        // There were no errors, so we're gonna go ahead and add the new size
        if (empty($messages['errors'])) {
            $ais_user_sizes[$_POST['ais_size_name']] = array(
                'size_w' => $_POST['ais_size_w'],
                'size_h' => $_POST['ais_size_h'],
                'crop' => $_POST['ais_size_crop']
            );
            update_option('ais_sizes', $ais_user_sizes);
            $messages['succes'][] = 'An additional image size named <strong>' . $_POST['ais_size_name'] . '</strong> was added.';
        }

        // Did the user want to delete any sizes? Then let's do so.
        if (isset($_POST['ais_size_delete'])) {
            // If none of size_name, height and width were set, but delete IS set, the
            // user just wanted to delete something. No need to bug him with validation errors.
            if (isset($messages['errors']['width_height']) && isset($messages['errors']['size_name'])) {
                // But if crop was set, maybe the user wanted to add a size anyway?
                if (isset($_POST['ais_size_crop']) && $_POST['ais_size_crop'] == 1) { /* Do nothing */ }
                else unset($messages['errors']);
            }
            foreach ($_POST['ais_size_delete'] as $delete) {
                // $delete holds the size name
                unset($ais_user_sizes[$delete]);
                $messages['succes'][] = "The size named <strong>$delete</strong> was deleted";
            }
            update_option('ais_sizes', $ais_user_sizes);
            $ais_user_sizes = get_option('ais_sizes');
        }

        // When no new size was entered and no existing size was deleted we don't have to send any
        // validation errors.
        if (isset($messages['errors']['width_height']) && isset($messages['errors']['size_name']) && !isset($_POST['ais_size_delete'])) {
            if (isset($_POST['ais_size_crop']) && $_POST['ais_size_crop'] == 1) {
                    // Crop was set, maybe the user wanted to add a size anyway?
                }
                else {
                    unset($messages['errors']);
                    $messages['errors'][] = 'Nothing added, nothing deleted.';
                }
        }
        return $messages;
    }

    /**
     * This function looks for images that don't have copies of the sizes defined by this plugin, and
     * then tries to make those copies. It returns an array of messages divided into error messages and
     * succes messages. It only makes copies for 10 images at a time.
     *
     * @package ais
     * @return array
     * @uses function ais_get_images
     */
    function ais_regenerate_images() {
        // This can take a while, so we'll keep track of execution time to exit prematurely when
        // it gets too high.
        $start = strtotime('now');
        $max_execution_time = ini_get('max_execution_time');

        // Let's divide that max_execution_time by 2 just to be on the safe side (we don't know
        // when WordPress started to run so $now is off as well).
        $max_execution_time = $max_execution_time / 2;

        $messages = array();
        $images = ais_get_images();
        $sizes = apply_filters('intermediate_image_sizes', array('thumbnail', 'medium', 'large'));
        $basedir = wp_upload_dir();
        $basedir = $basedir['basedir'];
        if (!empty($images)) {
            foreach ($images as $image) {
                $metadata = wp_get_attachment_metadata($image->ID);
                $file = get_post_meta($image->ID, '_wp_attached_file', true);
                foreach ($sizes as $size) {
                    $now = strtotime('now');
                    $current_execution_time = $now - $start;
                    if ($max_execution_time - $current_execution_time < 2) {
                        break;
                    }

                    if (!isset($metadata['sizes'][$size])) {
                        $image_path = $basedir . '/' . $file;
                        $result = image_make_intermediate_size(
                            $image_path, get_option("{$size}_size_w"),
                            get_option("{$size}_size_h"),
                            get_option("{$size}_crop")
                        );
                        if ($result) {
                            $metadata['sizes'][$size] = array(
                                'file' => $result['file'], 'width' => $result['width'], 'height' => $result['height']
                            );
                            wp_update_attachment_metadata($image->ID, $metadata);
                            $messages['succes'][] = 'Resized ' . $image->post_title . ' to size ' . $size . '.';

                        }
                        else {
                            $result = image_resize(
                                $image_path, get_option("{$size}_size_w"),
                                get_option("{$size}_size_h"),
                                get_option("{$size}_crop")
                            );
                            if (is_array($result->errors)) {
                                foreach ($result->errors as $key => $value) {
                                    $messages['errors'][] = $key . ': ' . $value[0];
                                }
                            }
                        }
                    }
                    else {
                        // $messages['succes'][] =  $size . ' already exists for ' . $image->post_title . '. Skipped.';
                    }
                }
                $now = strtotime('now');
                $current_execution_time = $now - $start;
                if ($max_execution_time - $current_execution_time < 2) {
                    $messages['succes'][] = '<strong>Not quite finished yet. We had to stop the script midway because it had been running for too long. Just press the button again to continue where we left off.</strong>';
                    break;
                }
            }
        }
        if (empty($messages)) $messages['succes'][] = 'All is well, no new copies needed to be created.';
        return $messages;
    }

    /**
     * Gets an array of images uploaded on the blog. Returns false when no result was found
     *
     * @return array | bool
     */
    function ais_get_images() {
                /* Get attachments */
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'numberposts' => -1,
            'post_status' => null,
            'post_parent' => null, // any parent
        );
        $attachments = get_posts($args);

        if (empty($attachments)) return false;

        else return $attachments;
    }
?>