<?php
/**
 * The $scroller variable is an array containing the keys:
 * - title
 * - text
 */
extract($scroller);
?>
<div class="postbox premise-content-scrollers-postbox" id="premise-content-scrollers-<?php echo $key; ?>">
    <div title="Click to toggle" class="handlediv">
        <br>
    </div>
    <h3 class="hndle"><span><span class="tab-name"><?php esc_html_e($title); ?></span> <span class="tab-description">(Content Scroller Content Tab)</span></span></h3>
    <div class="inside">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="premise-content-scrollers-<?php echo $key; ?>-title"><?php _e('Title'); ?></label>
                    </th>
                    <td>
                        <input class="regular-text premise-content-scrollers-title" type="text" name="premise[content-scrollers][<?php echo $key; ?>][title]" id="premise-content-scrollers-<?php echo $key; ?>-title" value="<?php esc_attr_e($title); ?>" />
                        <a href="#" class="premise-content-scrollers-delete-tab"><?php _e('Delete This Tab'); ?></a>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php premise_the_editor($text, 'premise[content-scrollers]['.$key.'][text]', '', true, $key+2, true); ?>
    </div>
</div>
