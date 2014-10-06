<div class="dod_container">
<?php include '_header.php';?>
<h2>Order Deals</h2>
<style type="text/css">
    .last-row th {border-top: 1px solid #e1e1e1;}
    #sortable { list-style-type: none; margin: 10px 0 0; padding: 0; width: 100%; }
    #sortable ul { margin-left:20px; list-style: none; }
    #sortable li { padding: 2px 0px; margin: 4px 0px;  border: 1px solid #DDDDDD; cursor: move; -moz-border-radius:6px;}
    #sortable li span { display: block; background: #f9f8f8;  padding: 5px 10px; color:#555; font-size:13px; font-weight:bold;}
    #sortable li.placeholder{border: dashed 2px #ccc;height:25px;}
</style>
<?php
if (count($query->posts)) {?>

<a href="<?php echo site_url()?>?page_id=<?php echo get_option('dod_page_id')?>" target="_blank">View Page</a>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#sortable").sortable({
            'tolerance':'intersect',
            'cursor':'pointer',
            'items':'li',
            'placeholder':'placeholder',
            'nested': 'ul'
        });
        jQuery("#sortable").disableSelection();
        jQuery("#save-order").bind( "click", function() {
            jQuery.post( ajaxurl, { action:'update_deals_order', order:jQuery("#sortable").sortable("serialize") }, function() {
                jQuery("#ajax-response").html('<div class="message updated fade"><p><?php _e('Items Order Updated', 'cpt')?></p></div>');
                jQuery("#ajax-response div").delay(3000).hide("slow");
            });
        });
    });
</script>
<div id="order-post-type">
    <ul id="sortable" class="ui-sortable">
<?php
foreach ($query->posts as $key => $p) {
	$hold = array_shift(get_post_meta($p->ID, 'dod_active_deal'));
	$is_featured = array_shift(get_post_meta($p->ID, 'dod_featured_deal')) == 'Yes';
	?>
        <li id="item_<?php echo $p->ID;?>">
            <span><?php echo $p->post_title . ($is_featured ? ' &nbsp;&nbsp;&nbsp;[Featured Deal]' : '');?></span>
        </li>
<?php }?>
</ul>
    <div class="clear"></div>
</div>
<p class="">
    <div id="ajax-response"></div>
    <br>
    <button id="save-order" class="button-primary" type="">Save Order</button>
</p>

<?php } else {
	echo 'No Products added yet. Click to <a href="">Add New Product</a>';
}?>
<?php
function is_checked($hold) {
	echo $hold == 'Yes' ? 'checked' : '';
}

?>
</div>