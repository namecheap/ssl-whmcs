<div style="font-weight: bold; padding:10px 0 ">
    <?php echo $_LANG['ncssl_addon_sync_notice']?>
</div>

<form action="" method="get">

<input type="hidden" name="module" value="namecheap_ssl" />
<input type="hidden" name="action" value="sync" />

<?php echo $_LANG['ncssl_addon_sync_specify_product_id']?> <input type="text" size="5" name="hostingid" value="<?php echo $view['hostingid']?>" />
<input type="submit" value="<?php echo $_LANG['ncssl_addon_sync_search_button']?>" />

</form>

<?php if(!empty($view['hostingid'])):?>

    <?php if ( false==$view['found'] ) { ?> 

        <?php if($view['cert_has_san_option']):?>
            <?php echo $_LANG['ncssl_addon_sync_notice_san'] ?> 
        <?php else: ?>
            <?php echo $_LANG['ncssl_addon_sync_not_found'] ?> 
        <?php endif;?>
        
    <?php } ?>
    
    <?php if ( true==$view['found'] ) { ?>
    
        <?php if ($view['updated']==true) :?>
        <div style="padding:10px 0"><?php echo $_LANG['ncssl_addon_sync_success']?></div>
        <?php endif;?>
    
        <b><?php echo $_LANG['ncssl_addon_sync_order_no']?></b> <?php echo $view['hosting']['orderid']?>  
        <a href="./orders.php?action=view&id=<?php echo $view['hosting']['orderid']?>" target="_blank">View Order</a>
        <br />
        <b><?php echo $_LANG['ncssl_addon_sync_product_service']?>:</b> <?php echo $view['hosting']['productname']?> <br />
        <b><?php echo $_LANG['ncssl_addon_sync_domain']?>:</b> <?php echo $view['hosting']['domain'] ?> <br />
        <b><?php echo $_LANG['ncssl_addon_sync_cert_type']?>:</b> <?php echo $view['hosting']['ssl_order_certtype']?> <br />
        <b><?php echo $_LANG['ncssl_addon_sync_remote_id']?>:</b> <?php echo $view['hosting']['ssl_order_remoteid']?>
        
        <form action="" method="POST">
            <b><?php echo $_LANG['ncssl_addon_sync_specify_remote_id']?>:</b>
            <input type="text" size="8" name="remoteid" />
            <input type="hidden" name="ssl_order_id" value="<?php echo $view['hosting']['ssl_order_id']?>" />
            <input type="submit" value="<?php echo $_LANG['ncssl_addon_sync_button']?>" />
        </form>
    
    <?php } ?>

<?php endif;?>
