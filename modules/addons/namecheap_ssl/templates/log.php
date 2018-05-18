
<form action="" method="get">

    <input type="hidden" name="module" value="<?php echo htmlspecialchars($view['global']['module'])?>" />
    <input type="hidden" name="action" value="<?php echo htmlspecialchars($view['global']['action'])?>" />

<input type="hidden" name="filter_action" value="<?php echo htmlspecialchars($view['filter_action_value'])?>" />
<input type="hidden" name="filter_date_from" value="<?php echo htmlspecialchars($view['filter_date_from_value'])?>" />
<input type="hidden" name="filter_date_to" value="<?php echo htmlspecialchars($view['filter_date_to_value'])?>" />
<input type="hidden" name="filter_user_value" value="<?php echo htmlspecialchars($view['filter_user_value'])?>" />


<table width="100%" cellspacing="0" cellpadding="3" border="0"><tbody><tr>


<td width="50%" align="left"><?php echo (int)$view['log_items_count']?> <?php echo $_LANG['ncssl_addon_log_records_found']?>, <?php echo $_LANG['ncssl_addon_log_page']?> <?php echo (int)$view['log_items_current_page']?> <?php echo $_LANG['ncssl_addon_log_of']?> <?php echo (int)$view['log_items_count_of_pages']?></td>
<td width="50%" align="right">
    <?php echo $_LANG['ncssl_addon_log_jump_to_page']?>:
    <select onchange="submit()" name="page">
        <?php for($i=1;$i<=$view['log_items_count_of_pages'];$i++):?>
            <option value="<?php echo (int)$i?>"<?php if ($i==$view['log_items_current_page']):?> selected="selected"<?php endif;?>><?php echo (int)$i?></option>
        <?php endfor;?>
    </select>
</tr></tbody></table>
    
</form>

<div style="padding:10px 0">
    <form action="" method="get">
        
        <input type="hidden" name="module" value="<?php echo htmlspecialchars($view['global']['module'])?>" />
        <input type="hidden" name="action" value="<?php echo htmlspecialchars($view['global']['action'])?>" />

        
        <select name="filter_action">
            <option value=""><?php echo $_LANG['ncssl_addon_log_choose_action']?></option>
            <?php foreach($view['filter_action_options'] as $filter_action):?>
            <option value="<?php echo htmlspecialchars($filter_action)?>" <?php if ($filter_action==$view['filter_action_value']):?>selected="selected"<?php endif;?>><?php echo htmlspecialchars($filter_action)?></option>
            <?php endforeach;?>
        </select>
        &nbsp;
        <input name="filter_date_from" class="datepick" value="<?php echo htmlspecialchars($view['filter_date_from_value'])?>" placeholder="<?php echo $_LANG['ncssl_addon_log_start_date']?>" /> 
        <input name="filter_date_to" class="datepick" value="<?php echo htmlspecialchars($view['filter_date_to_value'])?>" placeholder="<?php echo $_LANG['ncssl_addon_log_end_date']?>"/> 
        
        <input type="text" size="30" name="filter_user" value="<?php echo htmlspecialchars($view['filter_user_value'])?>" placeholder="<?php echo $_LANG['ncssl_addon_log_user_input_placeholder']?>" />
        
        <input type="submit" value="<?php echo $_LANG['ncssl_addon_log_filter_button']?>" />
        
    
    </form>
</div>

<table width="100%" cellspacing="1" cellpadding="3" border="0" class="datatable">
    <tbody>
        <tr>
            <th><?php echo $_LANG['ncssl_addon_log_date']?></th>
            <th><?php echo $_LANG['ncssl_addon_log_action']?></th>
            <th><?php echo $_LANG['ncssl_addon_log_description']?></th>
            <th style="white-space: nowrap">Service ID</th>
            <th><?php echo $_LANG['ncssl_addon_log_username']?></th>
            <th><?php echo $_LANG['ncssl_addon_log_ipaddress']?></th>
        </tr>
        <?php foreach ($view['log_items'] as $log_item):?>
        <tr>        
            <td style="white-space: nowrap"><?php echo htmlspecialchars($log_item['date'])?></td>
            <td><?php echo htmlspecialchars($log_item['action'])?></td>
            <td><?php echo nl2br(htmlspecialchars($log_item['description']))?></td>
            <td><?php echo htmlspecialchars($log_item['serviceid'])?></td>
            <td><?php echo htmlspecialchars($log_item['user'])?></td>
            <td><?php echo htmlspecialchars($log_item['ipaddr'])?></td>
        </tr>
        <?php endforeach;?>
</tbody></table>