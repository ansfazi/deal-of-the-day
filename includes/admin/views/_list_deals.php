<div class="dod_container">
<?php include '_header.php';?>

<h2>Deal Page Title</h2>
<p>
    <a href="<?php echo site_url()?>?page_id=<?php echo get_option('dod_page_id')?>" target="_blank">View Page</a> |
    <a href="<?php echo site_url()?>/wp-admin/post-new.php?post_type=product" target="_blank">Add New Product</a>
</p>
<style type="text/css">
    .last-row th {border-top: 1px solid #e1e1e1;}
    #sortable { list-style-type: none; margin:  0; padding: 0; width: 100%; }
    #sortable ul { margin-left:20px; list-style: none; }
    #sortable li { padding: 4px 0px; margin: 0px 0px; -moz-border-radius:6px; height: 45px;}
    #sortable li.placeholder{border: dashed 2px #ccc;height:45px;}
    .row-action{ display: none; }
    .row-action .id{ color: #666; }
    #sortable li:hover .row-action{ display: block;}
    .products-list{ background: #fff ; border: 1px solid #e5e5e5; -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);box-shadow: 0 1px 1px rgba(0,0,0,.04); }
    .products-list>.thead{  min-height: 35px; background: #fff;line-height: 35px; border-bottom: 1px solid #ddd; }
    .products-list .thead:last-child{  min-height: 35px; background: #fff;line-height: 35px; border-top: 1px solid #ddd; }
    .products-list .th{ float: left; width: 24%; padding: 0 5px;}
    .ui-sortable .tr{ width: 100%; clear: both; min-height: 25px;}
    .ui-sortable .tr div{ width: 24%; float: left; line-height: 25px;  padding: 0px 5px;}
    .products-list .reorder{ cursor: move;}
    div.error, div.updated{ margin-bottom: 8px;}
</style>
  <script type="text/javascript" >
    jQuery(document).ready(function($) {
        var deal_id = 0;
        $('.dod_deal_status').change(function() {
            deal_id = $(this).val();
            var data = {
                'action': 'dod_update_deal_status',
                'deal_id': deal_id
            };
            $.post(ajaxurl, data, function(data) {
                if( data == 1 ){
                    $('#row_' + deal_id ).css('background-color', '#AAF46B');
                    setTimeout(function(){$('#row_' + deal_id ).css('background-color', '#fff');} , 200 )
                }else{
                    $('#row_' + deal_id ).css('background-color', '#FA8989');
                    setTimeout(function(){$('#row_' + deal_id ).css('background-color', '#fff');} , 200 )
                }
            });
        });
         $('.featured_deal').change(function() {
            deal_id = $(this).val();
            var data = {
                'action': 'dod_featured_deal',
                'deal_id': deal_id
            };
            $.post(ajaxurl, data, function(data) {
                if( data == 1 ){
                    $('#row_' + deal_id ).css('background-color', '#AAF46B');
                    setTimeout(function(){$('#row_' + deal_id ).css('background-color', '#fff');} , 200 )
                }else{
                    $('#row_' + deal_id ).css('background-color', '#FA8989');
                    setTimeout(function(){$('#row_' + deal_id ).css('background-color', '#fff');} , 200 )
                }
            });
        });

        jQuery("#sortable").sortable({
            'tolerance':'intersect',
            'cursor':'pointer',
            'items':'li',
            'placeholder':'placeholder',
            'nested': 'ul',
        });
        jQuery("#sortable" ).on( "sortupdate", function( event, ui ) {
            jQuery("#sortable").disableSelection();
            jQuery.post( ajaxurl, { action:'update_deals_order', order:jQuery("#sortable").sortable("serialize") }, function() {
                jQuery("#ajax-response").html('<div class="messadge updated fade"><p><?php _e("Deals reordered", "deal-of-day")?></p></div>');
                jQuery("#ajax-response div").delay(2000).fadeOut("slow");
            });
         } );
    });
</script>
<div id="ajax-response"></div>
<?php
if (count($query->posts)) {?>
<div class="products-list">
        <div class="thead">
            <div class="th"><strong>Product</strong></div>
            <div class="th"><strong>Deal status</strong></div>
            <div class="th"><strong>Featured Deal</strong></div>
            <div class="th"><strong>re-order</strong></div>
        </div>
    <ul id="sortable" class="ui-sortable">
<?php
global $dealofday;
	foreach ($query->posts as $key => $p) {
		$hold = array_shift(get_post_meta($p->ID, 'dod_active_deal'));
		$is_featured = array_shift(get_post_meta($p->ID, 'dod_featured_deal'));
		?>
            <li id="item_<?php echo $p->ID;?>">
                <div id="row_<?php echo $p->ID;?>" class="tr">
                 <!--    <div><?php echo $p->ID;?></div> -->
                    <div>
                    <strong><?php echo $p->post_title;?></strong>
                        <span class="row-action">
                            <span class="id">ID: <?php echo $p->ID;?> |</span>
                            <span class="edit"><a href="<?php echo site_url();?>/wp-admin/post.php?post=<?php echo $p->ID;?>&amp;action=edit" target="_blank">Edit</a>|</span>
                            <span class="view"><a href="<?php echo get_permalink($p->ID);?>" target="_blank"> View </a></span>
                        </span>
                    </div>
                    <div> Active Deal  <input type="checkbox" name="<?php echo $p->ID . 'dod_active_deal'?>" class="dod_deal_status" value="<?php echo $p->ID;?>" <?php is_checked($hold);?> ></div>
                    <div> <input type="checkbox" name="<?php echo $p->ID . 'featured_deal'?>" class="featured_deal" value="<?php echo $p->ID;?>" <?php is_checked($is_featured);?> ></div>
                    <div class="reorder"> <img src="<?php echo $dealofday::plugin_url()?>/assets/images/move.jpg" alt=""></div>
                </div>
            </li>
<?php }?>
</ul>
         <div class="thead">
            <div class="th"><strong>Page</strong></div>
            <div class="th"><strong>Deal status</strong></div>
            <div class="th"><strong>Featured Deal</strong></div>
            <div class="th"><strong>re-order</strong></div>
        </div>
    </div>

<?php } else {
	echo 'No Products added yet. Click to <a href="">Add New Product</a>';
}?>
<?php
function is_checked($hold) {
	echo $hold == 'Yes' ? 'checked' : '';
}

?>
</div>