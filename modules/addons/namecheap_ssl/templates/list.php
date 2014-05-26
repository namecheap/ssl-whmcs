<?php echo $_LANG['ncssl_addon_list_user_list'] ?>:
<ul>
    <?php foreach($view['userlist'] as $user):?>
    <li><a href="<?php echo $view['global']['mod_action_url']?>&user=<?php echo $user['user']?>&acc=<?php echo $user['acc']?>"><?php echo $user['user']?></a></li>
    <?php endforeach;?>
</ul>


<?php if(!empty($view['items'])):?>
    <table width="100%" cellspacing="1" cellpadding="3" border="0" class="datatable" id="sortabletbl1">
        <tbody>
            <tr>
                <th><?php echo $_LANG['ncssl_addon_list_certificate_id']?></th>
                <th><?php echo $_LANG['ncssl_addon_list_host_name']?></th>
                <th><?php echo $_LANG['ncssl_addon_list_type']?></th>
                <th><?php echo $_LANG['ncssl_addon_list_purchase_date']?></th>
                <th><?php echo $_LANG['ncssl_addon_list_expire_date']?></th>
                <th><?php echo $_LANG['ncssl_addon_list_activation_expire_date']?></th>
                <th><?php echo $_LANG['ncssl_addon_list_expired']?></th>
                <th><?php echo $_LANG['ncssl_addon_list_status']?></th>
                <th><?php echo $_LANG['ncssl_addon_list_whmcs_product_id']?></th>
                <th><?php echo $_LANG['ncssl_addon_list_whmcs_cert_status']?></th>
            </tr>
            <?php foreach($view['items'] as $item):?>
            <tr>
                <td><?php echo $item['namecheap']['CertificateID']?></td>
                <td><?php echo $item['namecheap']['HostName']?></td>
                <td><?php echo $item['namecheap']['SSLType']?></td>
                <td><?php echo $item['namecheap']['PurchaseDate']?></td>
                <td><?php echo $item['namecheap']['ExpireDate']?></td>
                <td><?php echo $item['namecheap']['ActivationExpireDate']?></td>
                <td><?php echo 'true' == $item['namecheap']['IsExpiredYN'] ? 'Y' : 'N' ?></td>
                <td><?php echo $item['namecheap']['Status']?></td>
                <td><?php if(!empty($item['whmcs'])):?><a target="_blank" href="./clientsservices.php?userid=&id=<?php echo $item['whmcs']['serviceid']?>"><?php echo $item['whmcs']['serviceid'];?></a><?php else: ?>-<?php endif;?></td>
                <td><?php echo !empty($item['whmcs']) ? $item['whmcs']['status'] : '-'?></td>
            </tr>    
            <?php endforeach;?>
        </tbody>
    </table>
    
    <?php echo $_LANG['ncssl_addon_list_pages']?> :
    <?php foreach($view['pages'] as $p):?>
        <a href="<?php echo $view['global']['mod_action_url']?>&user=<?php echo $view['user']['user']?>&acc=<?php echo $view['user']['acc']?>&page=<?php echo $p?>"><?php if($view['current_page']==$p):?><strong><?php echo $p?></strong><?php else : ?><?php echo $p?><?php endif; ?></a>
    <?php endforeach;?>
    
<?php endif; ?>
