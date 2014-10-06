<div class="dod_container">
<?php include '_header.php';?>

<h2>Deal Page Title</h2>

<a href="<?php echo site_url()?>?page_id=<?php echo get_option('dod_page_id')?>" target="_blank">View Page</a>
<style type="text/css">
    .last-row th {border-top: 1px solid #e1e1e1;}
    #sortable { list-style-type: none; margin: 10px 0 0; padding: 0; width: 100%; }
    #sortable ul { margin-left:20px; list-style: none; }
    #sortable li { padding: 2px 0px; margin: 4px 0px;  border: 1px solid #DDDDDD; cursor: move; -moz-border-radius:6px;}
    #sortable li span { display: block; background: #f9f8f8;  padding: 5px 10px; color:#555; font-size:13px; font-weight:bold;}
    #sortable li.placeholder{border: dashed 2px #ccc;height:25px;}
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
    });
</script>
<?php
if (count($query->posts)) {?>
<table class="wp-list-table widefat fixed posts" >
    <thead>
        <tr>
            <th width="20"><strong>ID</strong></th>
            <th><strong>Page</strong></th>
            <th><strong>Deal status</strong></th>
            <th><strong>Featured Deal</strong></th>
            <th><strong>re-order</strong></th>
        </tr>
    </thead>
<?php
foreach ($query->posts as $key => $p) {
	$hold = array_shift(get_post_meta($p->ID, 'dod_active_deal'));
	$is_featured = array_shift(get_post_meta($p->ID, 'dod_featured_deal'));
	?>
    <tr id="row_<?php echo $p->ID;?>">
        <td><?php echo $p->ID;?></td>
        <td><?php echo $p->post_title;?></td>
        <td> Active Deal  <input type="checkbox" name="<?php echo $p->ID . 'dod_active_deal'?>" class="dod_deal_status" value="<?php echo $p->ID;?>" <?php is_checked($hold);?> ></td>
        <td> <input type="checkbox" name="<?php echo $p->ID . 'featured_deal'?>" class="featured_deal" value="<?php echo $p->ID;?>" <?php is_checked($is_featured);?> ></td>
        <td> <?php echo ++$i;?></td>
    </tr>
<?php }?>
<thead class="last-row">
        <tr>
            <th width="20"><strong>ID</strong></th>
            <th><strong>Page</strong></th>
            <th><strong>Deal status</strong></th>
            <th><strong>Featured Deal</strong></th>
            <th><strong>re-order</strong></th>
        </tr>
    </thead>
</table>
<?php } else {
	echo 'No Products added yet. Click to <a href="">Add New Product</a>';
}?>
<?php
function is_checked($hold) {
	echo $hold == 'Yes' ? 'checked' : '';
}

?>
</div>