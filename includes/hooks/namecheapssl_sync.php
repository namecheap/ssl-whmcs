<?php

    
    if (!defined("WHMCS"))
	die("This file cannot be accessed directly");
    
    require_once dirname(__FILE__) . "/../../modules/servers/namecheapssl/namecheapapi.php";
    
    
    
    
    function Namecheapssl_hook_sync(){
        
        set_time_limit(0);
        
        // production sync
        $r = NcSql::q("SELECT DISTINCT configoption1 AS user, configoption2 AS password FROM tblproducts WHERE configoption9='' AND configoption1!='' AND configoption2!='' AND servertype='namecheapssl'");
        while ($row=NcSql::fetchAssoc($r)){
            Namecheapssl_hook_sync_2($row['user'],$row['password'],false);
        }
        
        // sandbox sync
        $r = NcSql::q("SELECT DISTINCT configoption3 AS user, configoption4 AS password FROM tblproducts WHERE configoption9='on' AND configoption3!='' AND configoption4!='' AND servertype='namecheapssl'");
        while ($row= NcSql::fetchAssoc($r)){
            Namecheapssl_hook_sync_2($row['user'],$row['password'],true);
        }
        
    }
    
    
    function Namecheapssl_hook_sync_2($user,$password,$debugMode = false){
        
        require_once dirname(__FILE__) . "/../../modules/servers/namecheapssl/namecheapssl.php";
        
        namecheapssl_log('hook.sync', 'sync_hook_started', $user);
        
        
        $sync_date_offset = NcSql::sql2cell("SELECT value FROM mod_namecheapssl_settings WHERE name='sync_date_offset'");
        
        
        $api = new NamecheapApi($user, $password, $debugMode);
        
        $iPageSize = 22;
        $requestParams = array("Page" => 1, "PageSize" => $iPageSize);
        
        try{
            $response = $api->request("namecheap.ssl.getList", $requestParams);
            $result = $api->parseResponse($response);            
        }catch(Exception $e){
            echo $e->getMessage();
            return;
            //exit();
        }
        
        if ($result){
            $iTotalPages = (int)  ceil($result['Paging']['TotalItems']/$iPageSize);  
        }
        
        for($i=1;$i<=$iTotalPages;$i++){
            if (1!=$i){
                try{
                    $requestParams = array("Page" => $i, "PageSize" => $iPageSize);
                    $response = $api->request("namecheap.ssl.getList", $requestParams);
                    $result = $api->parseResponse($response);            
                }catch(Exception $e){
                    echo $e->getMessage();
                    return;
                    //exit();
                }
            }
            
            
            foreach ($result["SSLListResult"]["SSL"] as $aCertInfo){
                
                if ('active'==$aCertInfo['@attributes']['Status'] || 'replaced'==$aCertInfo['@attributes']['Status']){
                    
                    
                    // synchronize expire date
                    list($month, $day, $year) = explode("/", $aCertInfo['@attributes']['ExpireDate']);
                    
                    // 
                    $res = NcSql::q("SELECT h.id FROM `tblhosting` h INNER JOIN `tblsslorders` s ON s.serviceid=h.id  WHERE s.remoteid='{$aCertInfo['@attributes']['CertificateID']}' AND h.`nextduedate` != '$year-$month-$day'");
                    
                    if (NcSql::numRows($res)){
                        $iHostingId = array_shift(NcSql::fetchArray($res));
                        
                        $duedate = "$year-$month-$day";
                        if($sync_date_offset){
                             $duedate = date('Y-m-d',strtotime($duedate . "-$sync_date_offset days"));
                        }
                        
                        $sql = "update `tblhosting`
                                   set `nextduedate` = '$duedate',
                                       `nextinvoicedate` = '$duedate'
                                 where `id` = '$iHostingId'";                        
                        NcSql::q($sql);
                        namecheapssl_log('hook.sync', 'sync_hook_updated_duedate', array("$duedate"),$iHostingId);
                    }
                    
                    // sync domain
                    if(!empty($aCertInfo['@attributes']['HostName']) && 'active'==$aCertInfo['@attributes']['Status']){
                        $domain = NcSql::e($aCertInfo['@attributes']['HostName']);
                        $res = NcSql::q("SELECT h.id FROM `tblhosting` h INNER JOIN `tblsslorders` s ON s.serviceid=h.id  WHERE s.remoteid='{$aCertInfo['@attributes']['CertificateID']}' AND h.`domain` != '$domain'");
                        if (NcSql::numRows($res)){
                            $iHostingId = array_shift(NcSql::fetchArray($res));
                            $sql = "update `tblhosting`
                                        set `domain` = '$domain'
                                        where `id` = '$iHostingId'";								 
                            NcSql::q($sql);
                            namecheapssl_log('hook.sync', 'sync_hook_updated_domain', array($domain),$iHostingId);
                        }
                    }
                    
                    
                }
                
                
                if ('replaced'==$aCertInfo['@attributes']['Status']){
                    
                        // synchronize reissue state
                        $sql ="SELECT * FROM tblsslorders WHERE remoteid='{$aCertInfo['@attributes']['CertificateID']}'";
                        $r = NcSql::q($sql);
                        if (NcSql::numRows($r)){
                            $aWhmcsCert = NcSql::fetchAssoc($r);
                            // get replaced certificate info                            
                            try{
                                $replaced_cert_request_params = array('CertificateID' => (int)$aWhmcsCert['remoteid']);        
                                $replaced_cert_response = $api->request("namecheap.ssl.getInfo", $replaced_cert_request_params);
                                $replaced_cert_result = $api->parseResponse($replaced_cert_response);
                                
                                if (!empty($replaced_cert_result["SSLGetInfoResult"]["@attributes"]["ReplacedBy"])){
                                    
                                    $replacedBy = (int)$replaced_cert_result["SSLGetInfoResult"]["@attributes"]["ReplacedBy"];
                                    if(0==$replacedBy){
                                        echo 'Wrong "replaced by" attribute: ' . $replaced_cert_result["SSLGetInfoResult"]["@attributes"]["ReplacedBy"];
                                        return;
                                        //exit();
                                    }
                                    
                                    $sql = "UPDATE tblsslorders SET remoteid='$replacedBy' WHERE remoteid='{$aCertInfo['@attributes']['CertificateID']}'";
                                    NcSql::q($sql);
                                    
                                    $sql = "UPDATE mod_namecheapssl SET certificate_id='$replacedBy' WHERE certificate_id='{$aCertInfo['@attributes']['CertificateID']}'";
                                    NcSql::q($sql);
                                    
                                    namecheapssl_log('hook.sync', 'sync_hook_updated_remoteid', array($aCertInfo['@attributes']['CertificateID'], $replacedBy),$aWhmcsCert['serviceid']);
                                    
                                }
                                
                            }catch(Exception $e){
                                echo $e->getMessage();
                                return;
                            }
                            
                        }                        
                        
                }  
                
                       
            }
            
        }
        
        
    }
    
    
    function Namecheapssl_hook_report(){
        
        // create html for report
        $dateEnd = date('Y-m-d H:i:00');        
        $dateStart = date('Y-m-d H:i:59',  mktime(date('H'), date('i'), date('s'), date('n'), date('d')-1));
        
        
        $query = "SELECT log.*,c.email FROM mod_namecheapssl_log log LEFT JOIN tblclients AS c ON (log.userid=c.id AND user='client') WHERE log.date BETWEEN '$dateStart' AND '$dateEnd' AND `debug`=0 ";
        
        $r = NcSql::q($query);
        
        if(NcSql::numRows($r)){
            
            $html = "Namecheap SSL Module Cron Job Report for $dateStart-$dateEnd <br><br>";
            while($row= NcSql::fetchAssoc($r)){
                $html .= "{$row['date']}; {$row['description']}; " . ('client' == $row['user'] ? ' User(client): ' . $row['email'] : ' Admin user: ' . $row['user']) . "({$row['userid']});" .  ( !empty($row['serviceid']) ? "Service id: {$row['serviceid']}; " : '') ;
                $html .= '<br>';
                
            }
            
            sendAdminNotification('system', "SSL Actions Report", $html);
            
        }
        
        
    }
    
    
    
    function Namecheapssl_hook_prevent_san_reduction($params){
        
        if(!empty($params['filename']) && !empty($params['type']) && $params['filename']=='upgrade' && $params['type']=='configoptions'){
            if(!empty($params['configoptions'])&&!empty($params['id'])){
                
                $r = NcSql::q('SELECT tblproducts.servertype FROM tblproducts JOIN tblhosting ON tblhosting.packageid=tblproducts.id WHERE tblhosting.id='.(int)$params['id']);
                if(!$r){return;}
                $row = NcSql::fetchAssoc($r);
                if('namecheapssl'!==$row['servertype']){
                    return;
                }
                // so, it's definitely an upgrade page for namecheapssl product
                
                foreach($params['configoptions'] as $configid=>$newvalue){
                    
                    
                    // check if options is san and related to namecheap module product
                    $r = NcSql::q("SELECT * FROM tblproductconfigoptions WHERE id=$configid");
                    $row = NcSql::fetchAssoc($r);
                    if(substr($row['optionname'],0,3)==='san'){
                        
                        // it's a san option; we need to check old value
                        $r = NcSql::q("SELECT qty FROM tblhostingconfigoptions WHERE relid=".(int)$params['id'] . " AND configid=".$configid);
                        $row = NcSql::fetchAssoc($r);
                        
                        $qty = $row['qty'];
                        
                        // this is it
                        if($newvalue<$qty){
                            $location = $params['systemurl'] . '?m=namecheap_ssl&san_reduction';
                            header("Location: $location");
                            exit();
                        }
                        
                    }
                }
            }
        }
        
    }
    
    
    if (function_exists('add_hook')){
        add_hook("DailyCronJob", 11, "Namecheapssl_hook_sync");
        add_hook("DailyCronJob", 12, "Namecheapssl_hook_report");        
        add_hook("ClientAreaPage", 10, 'Namecheapssl_hook_prevent_san_reduction');        
    }
    
?>
