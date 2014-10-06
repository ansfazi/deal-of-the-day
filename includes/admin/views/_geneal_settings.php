<div class="dod_container">
<?php include '_header.php';?>
<div class="dod_wrap">
    <form method="post" id="mainform" action="" enctype="multipart/form-data">
        <h2>General Settings</h2>
        <table class="form-table">

            <tbody>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="">Deals Page Title</label>
                    </th>
                    <td class="">
						<input type="text" name="page_title" value="<?php echo $page->post_title;?>" placeholder="">
                        <a href="<?php echo site_url()?>?page_id=<?php echo get_option('dod_page_id')?>" target="_blank">View Page</a>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="">Select Style</label>
                    </th>
                    <td class="">
                        <select name="page_template" >
                        <option value="default">Default Template</option>";
<?php
foreach ($templates as $title => $value) {
	$selected = '';
	if ($selected_template == $value) {
		$selected = 'selected="selected" ';
	}
	echo "<option value=\"$value\" $selected  >$title</option>";
}
?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="">Custom CSS</label>
                    </th>
                    <td class="">
						<textarea name="custom_css" cols="80" rows="10"><?php echo $custom_css;?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <div class="message updated fade" style="display: <?php echo $msg == '' ? 'none' : 'block';?>;"><p><?php echo $msg?></p></div>
<?php if (!isset($GLOBALS['hide_save_button'])):?>
            <input name="save-settings" class="button-primary" type="submit" value="<?php _e('Save changes', 'deal-of-day');?>" />
<?php endif;?>
        </p>
    </form>
</div>
</div>