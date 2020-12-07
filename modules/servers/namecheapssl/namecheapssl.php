<?php

// ****************************************************************************
// *                                                                          *
// * NameCheap.com WHMCS SSL Module                                           *
// * Version 1.6.7
// * Email: sslsupport@namecheap.com                                          *
// *                                                                          *
// * Copyright 2010-2013 NameCheap.com                                        *
// *                                                                          *
// *                                                                          *
// ****************************************************************************
// *                                                                          *
// * Installation notes                                                       *
// * 1. Download and unzip attached archive. Copy the contents to the root folder of whmcs (folder structure will remain the same).
// * 2. Go to 'Setup >Addon Modules' in your WHMCS Admin area and activate the addon. Then please click ‘Addons > NamecheapSSL Module addon’ or go to http://yourdomain.com/admin/addonmodules.php?module=namecheapssl (given you have WHMCS installed onhttp://yourdomain.com/). The SSL Module Addon installation is now complete.
// * For those upgrading to 1.3.x from previous versions.
// * 1. The installation script /modules/servers/namecheapssl/install.php is now deprecated and should be removed if you still have it.
// * 2. The module /modules/admin/namecheapssl/ is deprecated and should be removed. Its functionality was moved to the addon.
// * For those upgrading between 1.3.x versions:
// * 1. Download and unzip attached archive. Copy the contents to the root folder of whmcs (folder structure will remain the same).
// * 2. Go to ‘Addons > NamecheapSSL Module addon’ in admin area or go to http://yourdomain.com/admin/addonmodules.php?module=namecheapssl (given you have WHMCS installed on http://yourdomain.com/). The SSL Module Addon will take care of the update automatically.
// ****************************************************************************
// *************                  -- Change log --                *************
// * Updated on Jan 21, 2011                                                  *
// * Now supports the following certificates as well:                         *
// * VeriSign Secure Site                                                     *
// * VeriSign Secure Site Pro                                                 *
// * VeriSign Secure Site EV                                                  *
// * VeriSign Secure Site Pro EV                                              *
// * Geotrust TrueBusinessID                                                  *
// * Geotrust TrueBusinessID WildCard                                         *
// *                                                                          *
// *                                                                          *
// * Updated on Feb 3 2011                                                    *
// * Automatic create order bugfix                                            *
// *                                                                          *
// *                                                                          *
// * Updated on May 19 2011                                                   *
// * "Use tokens" bugfix                                                      *
// *                                                                          *
// *                                                                          *
// * Updated on July 28 2011 to Version 1.1.3                                 * 
// * The list of available SSL Certificates is automatically synchronized     *
// * with Namecheap.com                                                       *
// *                                                                          *
// * Compatibility with the latest version of WHMCS (4.5.2) added             *
// *                                                                          *
// * Customization options for "Technical Contact Details" added.             *
// *                                                                          *
// * SSL Certificate Validity Period configuration process changed.           *
// * Now validity period is set based on the billing cycle.                   *
// *                                                                          *
// *                                                                          *
// * Updated on August 18 2011 to Version 1.1.4                               * 
// * Minor bugfixes.                                                          *
// * Multilanguage support added to client area (via module language file).   *
// * SSL Certificate "auto Renewal" functionality added.                      *
// *                                                                          *
// * Updated on 21 December 2011 to Version 1.1.5                             *
// * Api changes                                                              *
// * Minor bugfixes                                                           *
// *                                                                          *
// *                                                                          *
// * Updated on 27 January 2012 to Version 1.2.0                              *
// * Reissue functionality (Client and Admin Areas)                           *
// * View certificate details                                                 *
// * Manual duedate and reissue state synchronization                         *
// * Automatical due date and reissue state synchronization (WHMCS hook)      *
// * History of reissues (WHMCS addon)                                        *
// *                                                                          *
// * Updated on 4 April 2012 to Version 1.2.1                                 *
// *                                                                          *
// * Updated on June 1, 2013 to Version 1.3.0                                 *
// * WHMCS 5.2.x compatibility added.                                         *
// * Installation flow reworked.                                              *
// * View info and sync dates bug fixed.                                      *
// * Minor bug fixes.                                                         * 
// *                                                                          *
// * Updated on July 4, 2013 to Version 1.3.1                                 *
// * 1. Changed installation process.
// * 2. Changed certificate creation process. Now the NC side certificate is created on the third step of configuration. 
// * 2.1. The created Certificate’s used for backward compatibility with the presence of certificate NC REMOTE ID in database. 
// * 3. If the product settings of the ordered Certificate have "Use existing SSL from account" option enabled, then on the third step of configuration all the certificates with the "new purchase" status will be verified. If there is an Active certificate of the same type a REMOTE ID will be used and a new certificate will not be created.
// * 3.1. For PositiveSSL certificates type of "PositiveSSL Free" is checked .
// * 4. Added Thawte certificates support.
// * 5. Added necessary additional fields Comodo EV SSL, Comodo EV SGC SSL certificates on the first configuration step.
// * 6. Added necessary additional fields for the following Certificates: GEOTRUST: True BusinessID With EV, True BusinessID, True BusinessID Wildcard;
// * VERISIGN: Secure Site, Secure Site Pro, Secure Site with EV, Secure Site Pro with EV;
// * THAWTE: SSL123, SSL Web Server, SGC Super Certs, SSL WebServer EV
// * Bug fix 1: Fixed a case where the php.ini directive arg_separator.input not equal to "&".
// * Bug fix 2: Removed extra check for null value phone / fax number on the side of the module.
// * Bug fix 3: Fixed the hook to synchronize the date and state of expiration and reissue
// * Bug fix 4: Fixed possible php parse error in addon. Could take place previously when php.ini directive short_open_tag was disabled.
// *
// * Updated on July 4, 2013 to Version 1.3.2                                 *
// * 1. Fixed minor file issue from version 1.3.1					
// ****************************************************************************
// 
// Updated on September 26, 2013 to version 1.4.0
// 
// Improved log functionality
// Added possibility to link/relink Namecheap certificates (existing) with WHMCS orders.
// Added built-in lookup for list of certs for all of users having products set up within WHMCS module.
// Added compatibility with Comodo Sandbox Environment
// Added daily email report for all actions with Namecheap SSL Module.
// Reduced amount of hard-coded lang variables for better translatability
// Minor fixes
// 
// Updated on October 30, 2013 to version 1.4.1
// 
// Added possibility to reissue Symantec certificates from WHMCS interface.
// Added possibility to use HTTP-based validation instead of approval email.
// Added possibility to download certificate from WHMCS user area.
// Added additional checks for API responses.
// Added Whois emails to the list of addresses for Domain Control Validation.
// Enhanced error message ‘Invalid Phone or Country code’.
// Added dependency of module’s language upon WHMCS’s language selection
// Minor fixes.
// 
// Updated on November 15, 2013 to Version 1.4.2
// Fixed bug related to ‘Invalid Link Followed’ error  (Addon updated to version 1.3)
// 
// 
// Updated on December 13, 2013 to Version 1.5
// Multi-domain certificates support added to module.
// Fixed bug causing incorrect display of certificates list in addon for cases when production and sandbox usernames were the same.
// Fixed showing all possible Management options in user area instead of showing only available ones.
// Fixed of issue with no fields appearing in ‘Module Settings’ after selecting ‘namecheapssl’ from dropdown menu.
// Fixed ‘Unhandled Exception’ issue while reissue of a certificate.
// Minor fixes.
// 
// 
// Updated on July 23, 2014 to Version 1.6 for WHMCS 5  
// Added MySQL wrapper, improved security for DBMS work.
// Changed RapidSSL/GeoTrust/Thawte/Symantec reissue procedure: Now, administrative information and emails cannot be edited, due to restrictions from the Certificate Authority.
// Added revocation functionality.
// Performed partial code refactoring.
// Fixed error involving inability to use multi-domain certificates without setting up configurable options.
// Added fix for hook sending empty requests in case of mySQL server malfunction.
// Minor fixes
// 
// 
// Updated on December 5, 2014 to Version 1.6.2 for WHMCS 5
// Fixed default number of addon domains for Comodo EV Multi Domain SSL and Multi Domain SSL to 3.
// Fixed approver email resending for GeoTrust DV certificates.
// Fixed Organization fields for Comodo EV Multi Domain SSL.
// Fixed issues with Job Title field: Added Job Title to Module Settings and made it required field for Thawte, Symantec and EV GeoTrust certificates.
// Added 15 seconds timeout was implemented for uploading certificate types.
// Added “SourceOfCall” parameter to API calls to Namecheap.
// 
// 
// Updated on December 19, 2014 to Version 1.6.3 for WHMCS 5
// Fixed bug with Job Title field for Thawte and Symantec certificates
//
// 
// Updated on November 23, 2015 to version 1.6.4
// Removed email and HTTP validation for Symantec OV and EV certificates
// Added product due date synchronization offset in settings section of addon
// Simplified domain validation choice for Symantec DV certificates reissue
//
// 
// Updated on March 28, 2016 to version 1.6.5
// Fixed bug with approver emails for Symantec OV and EV certificates
// Added notification about latin characters to the first certificate activation page
//
// Updated on July 21 2017 to version 1.6.6
// Minor fixes according to changes in API
// Removed mysql_ functions, added support of MySQLi extension
//
// 
// Updated on May 18 2018 to version 1.6.7
// Security changes: escaped all variables in SQL queries and templates
// Fixed bug with duplicate configuration email after addon reactivation
// Fixed bug with revoke function
// 


require_once dirname(__FILE__) . "/namecheapapi.php";

function namecheapssl_getModuleConfigFields() {

    $_fields = array(
        "Username" => "Username",
        "SandboxUsername" => "Sandbox Username",
        "ApiKey" => "API key",
        "SandboxApiKey" => "Sandbox API key",
        "CertificateType" => "Certificate Type",
        "Years" => "Years",
        "PromotionCode" => "Promotion Code",
        "TestMode" => "Test Mode",
        "DebugMode" => "Debug Mode",
        "UseTokens" => "Use existing SSL from account",
        "TechEmail" => "Technical Email",
        "TechFirstName" => "Tech First Name",
        "TechLastName" => "Tech Last Name",
        "TechAddress1" => "Tech Address",
        "TechCity" => "Tech City",
        "TechStateProvince" => "Tech State Province",
        "TechCountry" => "Tech Country",
        "TechPostalCode" => "Tech Postal Code",
        "TechPhone" => "Tech Phone",
        "TechOrganizationName" => "Tech Organization Name",
        "TechJobTitle" => "Tech Job Title"
    );
    return $_fields;
}

function namecheapssl_getWebServerTypes() {
    global $_LANG;

    $_webServerTypes = array(
        "1001" => "oher", // AOL
        "1002" => "apachessl", // Apache +ModSSL
        "1003" => "apacheapachessl", // Apache-SSL (Ben-SSL, not Stronghold)
        "1004" => "c2net", // C2Net Stronghold
        "1005" => "cobaltseries", // Cobalt Raq
        "1006" => "other", // Covalent Server Software
        "1031" => "cpanel", // cPanel / WHM
        "1029" => "ensim", // Ensim
        "1032" => "hsphere", // H-Sphere
        "1007" => "ibmhttp", // IBM HTTP Server
        "1008" => "ibmhttp", // IBM Internet Connection Server
        "1009" => "iplanet", // iPlanet
        "1010" => "other", // Java Web Server (Javasoft / Sun)
        "1011" => "domino", // Lotus Domino
        "1012" => "dominogo4625", // Lotus Domino Go!
        "1013" => "iis4", // Microsoft IIS 1.x to 4.x
        "1014" => "iis5", // Microsoft IIS 5.x and later
        "1015" => "netscape", // Netscape Enterprise Server
        "1016" => "netscape", // Netscape FastTrack
        "1017" => "other", // Novell Web Server
        "1018" => "other", // Oracle
        "1000" => "other", // Other (not listed)
        "1030" => "plesk", // Plesk
        "1019" => "other", // Quid Pro Quo
        "1020" => "other", // R3 SSL Server
        "1021" => "other", // Raven SSL
        "1022" => "other", // RedHat Linux
        "1023" => "other", // SAP Web Application Server
        "1024" => "tomcat", // Tomcat
        "1025" => "website", // Website Professional
        "1026" => "webstar", // WebStar 4.x and later
        "1027" => "other", // WebTen (from Tenon)
        "1028" => "zeusv3", // Zeus Web Server
    );
    return $_webServerTypes;
}

function namecheapssl_getSslTypes($returnAdvanced = false) {
    
    
    /**
     * check cache
     */
    global $templates_compiledir;
    
    $cacheDir = $templates_compiledir;
    $cacheFileName = $cacheDir . "/namecheap_ssl_certlist_adv.txt";
    $cacheLifetime = 3600;
    $cerListCacheData = array();

    if (file_exists($cacheFileName)) {
        $cerListSrlz = file_get_contents($cacheFileName);
        if (strlen($cerListSrlz)) {
            $cerListCacheData = unserialize($cerListSrlz);
        }

        if (isset($cerListCacheData['created']) && (int) $cerListCacheData['created']
                && $cerListCacheData['created'] > time() - $cacheLifetime) {

            $_namecheapSSLTypes = $cerListCacheData['certlist'];
            $_namecheapSSLTypesAdvanced = $cerListCacheData['certlist_advanced'];
            if (sizeof($_namecheapSSLTypes)) {
                return $returnAdvanced ? $_namecheapSSLTypesAdvanced : $_namecheapSSLTypes;
            }
        }
    }

    /**
     * get remote cert list
     */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.namecheap.com/syndicated/ssl-certificates/get-type-list.aspx');
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $respXML = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    
    if (false === $respXML) {
        return false;
    }

    if (preg_match_all("/<Certificate\s+Type=\"([a-z0-9\s\-]+)\"\s+Provider=\"([a-z0-9\s]+)\".+?ValidationType=\"(\w+)\"/mi", $respXML, $matches)) {

        $_namecheapSSLTypes = array();
        $_namecheapSSLTypesAdvanced = array();

        foreach ($matches[1] as $key => $type) {
            if (preg_match("/\s+FREE/", $type)) {
                continue;
            }
            
            
            // temporary fix for incorrect types            
            if('Positivessl Multi Domain'==$type) $type = 'PositiveSSL Multi Domain';
            //
            //
            
            
            // create label
            $vendor = strtolower($matches[2][$key]);
            if ($vendor == 'geotrust')
                $vendor = 'GeoTrust';
            else if ($vendor == 'verisign')
                $vendor = 'VeriSign';
            else if ($vendor == 'comodo')
                $vendor = 'Comodo';
            else if ($vendor == 'thawte')
                $vendor = 'Thawte';
            $label = $vendor . " " . $type;
            
            // geoutrust certs
            if ($type == 'RapidSSL' || $type == 'RapidSSL Wildcard')
                $label = $type;
            
            
            $_namecheapSSLTypes[$label] = $type;
            $_namecheapSSLTypesAdvanced[$label] = array(
                'Type' => $type,
                'Provider' => $matches[2][$key],
                'ValidationType' => $matches[3][$key]
            );
            
            
            /**
             * $label is stored in tblproducts.configoption5 (string)
             * $type is stored in tblsslorders.certtype, modnamecheapssl.type (string)
             * both values should not be renamed
             */
            
            
        }
    } else {
        return false;
    }
    ksort($_namecheapSSLTypes);
    ksort($_namecheapSSLTypesAdvanced);


    /**
     * write cache
     */
    if (file_exists($cacheFileName))
        unlink($cacheFileName);
    $cacheFile = fopen($cacheFileName, "w");
    if (!$cacheFile) {
        $errMessage = "Unable write certificate list cache file (" . $cacheFileName . ").";
        $errMessage .= "Please check permissions for " . $cacheDir . ".";
        logActivity($errMessage);
    } else {
        $cacheData = array('created' => time(), 'certlist' => $_namecheapSSLTypes, 'certlist_advanced' => $_namecheapSSLTypesAdvanced);
        fwrite($cacheFile, serialize($cacheData));
        fclose($cacheFile);
    }

    return $returnAdvanced ? $_namecheapSSLTypesAdvanced : $_namecheapSSLTypes;
}

function _namecheapssl_getDefaultSunCount($type){
    
    $map = 
    array(
        
            'EV Multi Domain SSL' => 3,
            'Multi Domain SSL' => 3,
        
            'ComodoSSL Multi Domain SSL' => 3,
            'PositiveSSL Multi Domain' => 3,
            'Unified Communications' => 3,
            'ComodoSSL EV Multi Domain SSL' => 3,
            'True BusinessID Multi Domain' => 5,
            'True BusinessID with EV Multi Domain' => 5
        );
    
    return !empty($map[$type]) ? $map[$type] - 1 : 0;
            
}

function namecheapssl_initlang() {
    
    global $CONFIG, $_LANG, $smarty;
    $lang = !empty($_SESSION['Language']) ? $_SESSION['Language'] : $CONFIG['Language'];
    
    if (defined("ADMINAREA")){
        $sql = "SELECT language FROM tbladmins WHERE id='".(int) $_SESSION['adminid']."'";
        $row = NcSql::sql2row($sql);
        $lang = !empty($row['language']) ? $row['language'] : 'english';
    }
    
    
    $langFile = dirname(__FILE__) . "/lang/" . $lang . ".php";
    if (!file_exists($langFile))
        $langFile = dirname(__FILE__) . "/lang/" . ucfirst($lang) . ".php";
    if (!file_exists($langFile))
        $langFile = dirname(__FILE__) . "/lang/English.php";
    
    
    require_once dirname(__FILE__) . '/lang/English.php';
    require_once $langFile;
    
    if (is_array($_MOD_LANG)) {
        foreach ($_MOD_LANG as $k => $v) {
            if (empty($_LANG[$k])) {
                $_LANG[$k] = $v;
            }
        }
    }

    if (isset($smarty)) {
        $smarty->assign("LANG", $_LANG);
    }
}

function namecheapssl_ConfigOptions() {
    
    global $db_name, $_LANG;
    $_namecheapSSLTypes = namecheapssl_getSslTypes();

    $_fields = namecheapssl_getModuleConfigFields();
    $_webServerTypes = namecheapssl_getWebServerTypes();
    
    
    $techNotes = '<script type="text/javascript">
$(document).ready(function() {

    $("#techemail").parent().parent("tr").prev().after("<tr><td colspan=4><div id=\"newtechnote\"></td></tr>")
    $("#newtechnote").html($("#tech_note").html());
    $("#tech_note").html("");
});
</script>

<div id="tech_note">
<b>Attention:</b> The fields below should be filled <b>ONLY</b> in case you wish to use a custom <b>Technical Contact Email</b> for your SSL Certificates. Default Technical Email is sslsupport@namecheap.com. Using a custom Technical Email is useful for <b>resellers</b>, who wish to appear as an independent business entity in front of their customers.<br />
&nbsp;&nbsp;* In order to use <b>Default Technical Email</b> leave ALL of the fields below empty.<br />
&nbsp;&nbsp;* If you wish to use <b>Custom Technical Email</b> - fill in ALL of the fields below.<br />
</div>
';


    $configarray = array(
        $_fields['Username'] => array('Type' => "text",
            'Size' => "20",
            'Description' => "<br />Enter your username here."
        ),
        $_fields['ApiKey'] => array(
            'Type' => "text",
            'Size' => "20",
            'Description' => "<br />Enter your API key here. To get your api key, go to Manage Profile section in Namecheap.com, then click API access link on the left hand side. C/p the key here. DON'T include your password."
        ),
        $_fields['SandboxUsername'] => array(
            'Type' => "text",
            'Size' => "20",
            'Description' => "<br />Enter your sandbox username here. (This will be used only if you set the test mode on.)"
        ),
        $_fields['SandboxApiKey'] => array(
            'Type' => "text",
            'Size' => "20",
            'Description' => "<br />Enter your sandbox API key here. (This will be used only if you set the test mode on.)"
        ),
        $_fields['CertificateType'] => array(
            'Type' => 'dropdown',
            'Options' => ',' . implode(",", array_keys($_namecheapSSLTypes)),
        ),
        ' ' => array(),
        $_fields['PromotionCode'] => array(
            'Type' => 'text',
            'Size' => '20',
            'Description' => "<br />Enter your promotional (coupon) code."
        ),
        '    ' => array('Description' => $techNotes),
        $_fields['TestMode'] => array(
            'Type' => 'yesno'
        ),
        $_fields['UseTokens'] => array(
            'Type' => 'yesno',
            'Description' => "<br />If enabled we will try to use existing SSL certificate in your Namecheap account. If there is no corresponding type of SSL that can be used, new certificate will be purchased."
        ),
        $_fields['TechEmail'] => array(
            'Type' => 'text',
            'Size' => 20,
            'Description' => '<div id="techemail"></div>'
        ),
        $_fields['TechFirstName'] => array(
            'Type' => 'text',
            'Size' => 20,
            'Description' => ''
        ),
        $_fields['TechLastName'] => array(
            'Type' => 'text',
            'Size' => 20,
            'Description' => ''
        ),
        $_fields['TechAddress1'] => array(
            'Type' => 'text',
            'Size' => 20,
            'Description' => ''
        ),
        $_fields['TechCity'] => array(
            'Type' => 'text',
            'Size' => 20,
            'Description' => ''
        ),
        $_fields['TechStateProvince'] => array(
            'Type' => 'text',
            'Size' => 20,
            'Description' => ''
        ),
        $_fields['TechCountry'] => array(
            'Type' => 'text',
            'Size' => 20,
            'Description' => ''
        ),
        $_fields['TechPostalCode'] => array(
            'Type' => 'text',
            'Size' => 20,
            'Description' => ''
        ),
        $_fields['TechPhone'] => array(
            'Type' => 'text',
            'Size' => 20,
            'Description' => '+NNN.NNNNNNNNNN'
        ),
        $_fields['TechOrganizationName'] => array(
            'Type' => 'text',
            'Size' => 20,
            'Description' => ''
        ),
        $_fields['DebugMode'] => array(
            'Type' => 'yesno'
        ),
        $_fields['TechJobTitle'] => array(
            'Type' => 'text',
            'Size' => 20,
            'Description' => ''
        )
    );

    return $configarray;
}

function namecheapssl_CreateAccount($params) {

    if (!namecheapssl_check_install()) {
        return
                "Namecheap SSL Module error. Addon Module Namecheap SSL Module Addon hasn't been activated/upgraded. Please go to Setup - Addon Modules and perform activation/go to addon page."
        ;
    }

    global $CONFIG;

    $_namecheapSSLTypes = namecheapssl_getSslTypes();
    if (!$_namecheapSSLTypes){
        return 'Unable to retrieve list of supported certificate-types. Please try again in several minutes.';
    }

    $_fields = namecheapssl_getModuleConfigFields();
    $_webServerTypes = namecheapssl_getWebServerTypes();
    
    $sql = "SELECT id FROM tblsslorders WHERE serviceid='" . (int)$params['serviceid']."'";
    if (NcSql::sqlNumRows($sql)) {
        return "An SSL Order already exists for this order";
    }


    if (!empty($params["configoptions"]["Certificate Type"])) {
        $tblproducts_cert_type = $params["configoptions"]["Certificate Type"];
    } else {
        $tblproducts_cert_type = $params["configoption5"];
    }

    // certificate type labels are stored in product configuration
    // it equal to label of dropdownlist in product configuration    
    $certtype = $_namecheapSSLTypes[$tblproducts_cert_type];

    if (empty($certtype)) {
        foreach ($_namecheapSSLTypes as $label => $nc_type) {
            if (strtolower($tblproducts_cert_type) == strtolower($label)) {
                $certtype = $nc_type;
                break;
            }
        }
    }

    if (empty($certtype)) {
        return "Undefined certificate type";
    }



    $cycles = array('Annually' => 1, 'Biennially' => 2, 'Triennially' => 3);
    
    $sql = "SELECT * FROM tblhosting WHERE id = '" . (int)$params['serviceid']. "'";
    $service = NcSql::sql2row($sql);
    
    $certyears = !empty($cycles[$service['billingcycle']]) ? $cycles[$service['billingcycle']] : 1;

    // Certificate id equals to 0
    $certificateId = 0;
    // 1. Create record at WHMCS tblssorders table

    $queryData = array(
        "userid" => $params["clientsdetails"]["userid"],
        "serviceid" => $params["serviceid"],
//      "remoteid"  => $orderId,
        "remoteid" => $certificateId,
        "module" => "namecheapssl",
        "certtype" => $certtype,
        "status" => _namecheapssl_getIncompleteStatus()
    );
    $sslorderid = NcSql::insert('tblsslorders',$queryData);


    // 2. Create record at custom module table
    $queryData = array(
        'id' => $sslorderid,
        'user_id' => $params["clientsdetails"]["userid"],
        'certificate_id' => $certificateId,
        'type' => $certtype,
        'period' => $certyears,
    );
    NcSql::insert('mod_namecheapssl', $queryData);
    
    

    // 3. Send certificate welcome email
    //
    $sendWelcome = false;
    if (!empty($_POST) && !empty($_POST['vars']['products'][$params['serviceid']])
            && !empty($_POST['vars']['products'][$params['serviceid']]['sendwelcome'])) {
        $sendWelcome = true;
    }
    if (empty($_POST['vars']['products'][$params['serviceid']])) {
        
        $sql = "SELECT autosetup FROM tblproducts WHERE id = '" . (int) $params['packageid'] . "' LIMIT 1";
        $product = NcSql::sql2row($sql);
        
        $sendWelcome = $product['autosetup'] == 'payment' || $product['autosetup'] == 'order';
    }



    if ($sendWelcome) {
        $sslconfigurationlink = $CONFIG["SystemURL"] . "/configuressl.php?cert=" . md5($sslorderid);
        $sslconfigurationlink = "<a href=\"$sslconfigurationlink\">$sslconfigurationlink</a>";

        sendMessage("SSL Certificate Configuration Required", $params["serviceid"], array("ssl_configuration_link" => $sslconfigurationlink)
        );
    }


    namecheapssl_log('admin.create', 'admin_create', null, $params['serviceid']);


    return "success";
}

function namecheapssl_SuspendAccount($params) {
    return "success";
}

function namecheapssl_SSLStepOne($params) {
    
    global $CONFIG, $_LANG;
    
    namecheapssl_initlang();
    
    $values = array();

    // get info at first step and store in in session
    if (!$_SESSION["namecheapsslcert"][$certificateId]["id"]) {
        //$certInfoFromList = _namecheapssl_getCertificateInfoFromList($params,$certificateId);
        //$certInfo = _namecheapssl_getCertificateInfo($params,$certificateId);
    }
    
        
    $localCertInfo = _namecheapssl_getLocalCertInfo($params['serviceid']);
    
    if(!$localCertInfo->loadServerTypes()){
        // can't return error from step-one function
        exit( $_LANG['ncssl_unable_retrieve_certtypes'] . ' ' . $_LANG['ncssl_try_again_in_several_minutes'] );
    }
    
    $provider = $localCertInfo->getProvider();
    
    
    
    if( ('COMODO'!=$provider && $localCertInfo->inReissueState()) ){
        
        // need to backup previous config data
        $localCertInfo->backupConfigData();
        
        $script = '<script>';
        $script .= "$(document).ready(function(){";
        
        $previousConfigData = $localCertInfo->getConfigData(true);
        
        
        $fields = array('firstname','lastname','orgname','jobtitle','email','address1','address2','city','state','postcode','country','phonenumber');
        foreach($fields as $field){            
            if($previousConfigData){
                
                $field = htmlspecialchars($field);
                $previousConfigData[$field] = htmlspecialchars($previousConfigData[$field]);
                
                $script .= "$('[name=$field]').val('" . addslashes($previousConfigData[$field]) . "')\n";
                $script .= "$('[name=$field]').prop('disabled',true)\n";
                $script .= "$('[name=$field]').after($('<input type=hidden name=$field value=\"" . addslashes($previousConfigData[$field]) . "\" >'))\n";
            }
        }
        
        if($previousConfigData['fields']){
            foreach($previousConfigData['fields'] as $field=>$value){
                
                $field = htmlspecialchars($field);
                $value = htmlspecialchars($value);
                
                $script .= "$('[name=firstname]').after($('<input type=hidden name=fields[$field] value=\"" . addslashes($value) . "\" >'))\n";
            }
        }
        
        
        $script .= "})";
        $script .= '</script>';
        
        _namecheapssl_replaceLangVariable('ssladmininfodetails', $_LANG['ncssl_latin_characters_notice'] . '<br/><br/>' . $_LANG['ncssl_reissue_notice_step1'].$script);
        
    }else{
        
        _namecheapssl_replaceLangVariable('ssladmininfodetails', $_LANG['ncssl_latin_characters_notice']);
        
    }
    
    
    //
    // additional domains (san)
    //
    $sanCount = (int)$params['configoptions']['san'] + _namecheapssl_getDefaultSunCount( $localCertInfo->getType() );
    
    
    if($sanCount>0){

        $sanQuickSslPremium = false;
        if('QuickSSL Premium' == $localCertInfo->getType() ){
            $sanQuickSslPremium = true;
            $sanCount = 4;
        }
        
        // create san fields        
        for($i=1;$i<=$sanCount;$i++){
            $values['additionalfields'][$_LANG['ncssl_san_items_title']]["san_$i"] = array(                    
                "FriendlyName" => ( $sanQuickSslPremium ?  $_LANG['ncssl_san_items_item_quick_ssl_premium']  : $_LANG['ncssl_san_items_item']  ) . " $i",
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => false
            );
        }
        
        // additional san note
        global $smarty;
        if (!empty($_LANG['ncssl_san_csr_note'])) {
            $LANG = $smarty->get_template_vars('LANG');
            $LANG['sslserverinfodetails'] .= ('<br><br>' . $_LANG['ncssl_san_csr_note']);
            $smarty->assign('LANG', $LANG);
        }
        
    }
    //
    // additional domains (san)
    //
    
    
    // 1. CallBack Parameters for activating Comodo OV Certificates (InstantSSL, InstantSSL Pro, PremiumSSL, PremiumSSL Wildcard):
    if (
        !('COMODO'!=$provider && $localCertInfo->inReissueState()) &&
        in_array( $localCertInfo->getType() , 
                array('InstantSSL', 'PremiumSSL', 'InstantSSL Pro', 'PremiumSSL Wildcard', 'Unified Communications', 'Multi Domain SSL'))) {

        $values['additionalfields'][$_LANG['ncssl_comodo_add_form_title']] = array(
            "OrganizationRepFirstName" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepFirstName'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => true
            ),
            "OrganizationRepLastName" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepLastName'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => true
            ),
            "OrganizationRepTitle" => array(
                "FriendlyName" => $_LANG["ncssl_comodo_add_OrganizationRepTitle"],
                "Type" => "text",
                "Size" => "30",
                "Description" => ""
            ),
            "OrganizationRepEmailAddress" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepEmailAddress'],
                "Type" => "text",
                "Size" => "30",
                "Description" => $_LANG['ncssl_comodo_add_OrganizationRepEmailAddress_Description'],
                "Required" => true
            ),
            "OrganizationRepPhone" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepPhone'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => true
            ),
            "OrganizationRepFax" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepFax'],
                "Fax Number",
                "Type" => "text",
                "Size" => "30",
                "Description" => ""
            ),
            "OrganizationRepCallbackMethod" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepCallbackMethod'],
                "Type" => "dropdown",
                "Options" => 'Phone,Letter',
                "Description" => "",
                "Required" => true,
            ),
            "OrganizationRepCallbackDestinationSame" => array(
                "FriendlyName" => "",
                "Type" => "dropdown",
                "Options" => 'Yes,No',
                "Description" => $_LANG['ncssl_comodo_add_OrganizationRepCallbackDestinationSame_Description'],
            ),
        );




        if ( 
                !('COMODO'!=$provider && $localCertInfo->inReissueState()) && 
                !empty($_REQUEST['fields']['OrganizationRepCallbackDestinationSame'])) {
            if ($_REQUEST['fields']['OrganizationRepCallbackDestinationSame'] == 'No') {

                //
                $values['additionalfields'][$_LANG['ncssl_comodo_add_form_title']] += array(
                    "OrganizationRepLegalName" => array(
                        "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepLegalName'],
                        "Type" => "text",
                        "Size" => "30",
                        "Description" => "",
                        "Required" => true
                    ),
                    "OrganizationRepAddress1" => array(
                        "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepAddress1'],
                        "Type" => "text",
                        "Size" => "30",
                        "Description" => "",
                        "Required" => true
                    ),
                    "OrganizationRepAddress2" => array(
                        "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepAddress2'],
                        "Type" => "text",
                        "Size" => "30",
                        "Description" => "",
                    ),
                    "OrganizationRepCity" => array(
                        "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepCity'],
                        "Type" => "text",
                        "Size" => "30",
                        "Description" => "",
                        "Required" => true,
                    ),
                    "OrganizationRepStateProvince" => array(
                        "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepStateProvince'],
                        "Type" => "text",
                        "Size" => "30",
                        "Description" => "",
                        "Required" => true
                    ),
                    "OrganizationRepPostalCode" => array(
                        "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepPostalCode'],
                        "Type" => "text",
                        "Size" => "30",
                        "Description" => "",
                        "Required" => true
                    ),
                    "OrganizationRepCountry" => array(
                        "FriendlyName" => $_LANG['ncssl_comodo_add_OrganizationRepCountry'],
                        "Type" => "country",
                        "Description" => "",
                        "Required" => true
                    )
                );
            }
        }
    }



    // 2. Additional Parameters for activating Comodo EV Certificates (Comodo EV SSL, Comodo EV SGC SSL)
    if (  
            !('COMODO'!=$provider && $localCertInfo->inReissueState()) && 
            in_array( $localCertInfo->getType() , array('EV SSL', 'EV SSL SGC', 'EV Multi Domain SSL'))) {
        $values['additionalfields'][$_LANG['ncssl_comodo_ev_add_form_title']] = array(
            "CompanyIncorporationCountry" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_ev_add_CompanyIncorporationCountry'],
                "Type" => "country",
                "Size" => "30",
                "Description" => "",
                "Required" => true
            ),
            "CompanyIncorporationLocality" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_ev_add_CompanyIncorporationLocality'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => false,
            ),
            "CompanyIncorporationStateProvince" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_ev_add_CompanyIncorporationStateProvince'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => false,
            ),
            "CompanyIncorporationDate" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_ev_add_CompanyIncorporationDate'],
                "Type" => "text",
                "Size" => "30",
                "Description" => $_LANG['ncssl_comodo_ev_add_CompanyIncorporationDateDescription'],
                "Required" => false,
            ),
            "CompanyDBA" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_ev_add_CompanyDBA'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => false,
            ),
            "CompanyRegistrationNumber" => array(
                "FriendlyName" => $_LANG['ncssl_comodo_ev_add_CompanyRegistrationNumber'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => false,
            ),
        );
    }


    // 3. Additional Parameters for activating Geotrust, Verisign and Thawte OV and EV Certificates and Thawte SSL 123
    if (  
            !('COMODO'!=$provider && $localCertInfo->inReissueState()) &&
            in_array( $localCertInfo->getType() , array('True BusinessID With EV', 'True BusinessID', 'True BusinessID Wildcard', // GEOTRUST
                'Secure Site', 'Secure Site Pro', 'Secure Site with EV', 'Secure Site Pro with EV', // VERISIGN
                'SSL123', 'SSL Web Server', 'SGC Super Certs', 'SSL WebServer EV', // THAWTE 
                'True BusinessID Multi Domain', 'True BusinessID with EV Multi Domain'
            ))
    ) {

        $values['additionalfields'][$_LANG['ncssl_oth_ev_ov_add_form_title']] = array(
            "OrganizationLegalName" => array(
                "FriendlyName" => $_LANG['ncssl_oth_ev_ov_add_OrganizationLegalName'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => true
            ),
            "OrganizationDUNS" => array(
                "FriendlyName" => $_LANG['ncssl_oth_ev_ov_add_OrganizationDUNS'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => false
            ),
            "OrganizationAddress1" => array(
                "FriendlyName" => $_LANG['ncssl_oth_ev_ov_add_OrganizationAddress1'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => true
            ),
            "OrganizationAddress2" => array(
                "FriendlyName" => $_LANG['ncssl_oth_ev_ov_add_OrganizationAddress2'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => false
            ),
            "OrganizationCity" => array(
                "FriendlyName" => $_LANG['ncssl_oth_ev_ov_add_OrganizationCity'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => false
            ),
            "OrganizationStateProvince" => array(
                "FriendlyName" => $_LANG['ncssl_oth_ev_ov_add_OrganizationStateProvince'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => true
            ),
            "OrganizationPostalCode" => array(
                "FriendlyName" => $_LANG['ncssl_oth_ev_ov_add_OrganizationPostalCode'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => true
            ),
            "OrganizationCountry" => array(
                "FriendlyName" => $_LANG['ncssl_oth_ev_ov_add_OrganizationCountry'],
                "Type" => "country",
                "Size" => "30",
                "Description" => "",
                "Required" => true
            ),
            "OrganizationPhone" => array(
                "FriendlyName" => $_LANG['ncssl_oth_ev_ov_add_OrganizationPhone'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => true
            ),
            "OrganizationFax" => array(
                "FriendlyName" => $_LANG['ncssl_oth_ev_ov_add_OrganizationFax'],
                "Type" => "text",
                "Size" => "30",
                "Description" => "",
                "Required" => true
            )
        );
    }

    namecheapssl_log('client.stepOne', 'client_step_one', array( $localCertInfo->getType() ), $params['serviceid']);

    return $values;
}

function namecheapssl_SSLStepTwo($params) {
    
    global $CONFIG, $_LANG;
    namecheapssl_initlang();

    $api = _namecheapssl_initApi($params);
    
    
    $localCertInfo = _namecheapssl_getLocalCertInfo($params['serviceid']);
    if(!$localCertInfo->loadServerTypes()){
        return array( 'error' => $_LANG['ncssl_unable_retrieve_certtypes'] . ' ' . $_LANG['ncssl_try_again_in_several_minutes'] );
    }
    $provider = $localCertInfo->getProvider();
    
    
    if((
            NcLocalCertInfo::CERTIFICATE_VALIDATION_TYPE_EV == $localCertInfo->getValidationType() ||
            NcLocalCertInfo::PROVIDER_THAWTE == $provider ||
            NcLocalCertInfo::PROVIDER_VERISIGN == $provider
            ) && empty($params['jobtitle']) ){
        return array( 'error' => $_LANG['ncsssl_jobtitle_required_for_symantec']);
    }
    
    // 
    // check san
    // 
    $sans = array();
    if(!empty($_REQUEST['fields'])){
        foreach($_REQUEST['fields'] as $fieldName=>$fieldValue){
            $fieldValue = trim($fieldValue);
            if( substr($fieldName,0,4 )=='san_' && !empty($fieldValue)){                
                $sans[] = $fieldValue;
            }
        }
    }
    
    
    
    // added 20/02/2012 - whmcs 4.4.2 compatibility
    if (version_compare("4.4.2", $CONFIG['Version'], ">=")) {
        $configKeys = array("remoteid", "approveremail", "csr", "servertype", "email", "firstname",
            "lastname", "orgname", "address1", "address2", "jobtitle", "city", "state", "postcode",
            "country", "phonenumber"
            , 'fields'
        );

        $_SESSION['namecheapssl'] = array();

        foreach ($configKeys as $confKey) {
            if (isset($params[$confKey])) {
                $_SESSION['namecheapssl'][$confKey] = $params[$confKey];
            }
        }
    }
    // end: added 20/02/2012 - whmcs 4.4.2 compatibility


    foreach ($params as $key => $val) {
        if(is_string($val))
           $params[$key] = trim($val);
    }

    
    $sql = "SELECT mod_namecheapssl.* FROM mod_namecheapssl LEFT JOIN tblsslorders ON (tblsslorders.id = mod_namecheapssl.id) WHERE tblsslorders.serviceid = '" . (int)$params['serviceid'] . "'";
    $row = NcSql::sql2row($sql);

    if (!empty($params['email'])) {
        $sql = "UPDATE mod_namecheapssl SET admin_email = '" . NcSql::e ($params["email"]) . "' WHERE id = '" . (int)$row['id'] . "'";
        NcSql::q($sql);
    }


    $values = array();
    $values["displaydata"]["Domain"] = htmlspecialchars($row['domain']);
    $values["displaydata"]["Validity Period"] = htmlspecialchars(($row['period'] * 12) . " " . $_LANG['ncssl_months']);
    $values["displaydata"]["Expiration Date"] = htmlspecialchars($row['expiry_date']);
    $certType = $row['type'];

    // parse CSR
    $requestParams = array(
            "csr" => $params["csr"],
            "CertificateType" => in_array($certType, array('Multi Domain SSL','EV Multi Domain SSL')) ? 'EV SSL' : $certType
        );
    
    try {
        // $api 
        $response = $api->request("namecheap.ssl.parseCSR", $requestParams);
        $result = $api->parseResponse($response);

        $values["displaydata"]["Organization"] = htmlspecialchars($result["SSLParseCSRResult"]["CSRDetails"]["Organisation"]);
        $values["displaydata"]["Organization Unit"] = htmlspecialchars($result["SSLParseCSRResult"]["CSRDetails"]["OrganisationUnit"]);
        $values["displaydata"]["Email"] = htmlspecialchars($result["SSLParseCSRResult"]["CSRDetails"]["Email"]);
        $values["displaydata"]["Locality"] = htmlspecialchars($result["SSLParseCSRResult"]["CSRDetails"]["Locality"]);
        $values["displaydata"]["State"] = htmlspecialchars($result["SSLParseCSRResult"]["CSRDetails"]["State"]);
        $values["displaydata"]["Country"] = htmlspecialchars($result["SSLParseCSRResult"]["CSRDetails"]["Country"]);

        $domain = $values["displaydata"]["Domain"] = htmlspecialchars($result["SSLParseCSRResult"]["CSRDetails"]['CommonName']);
    } catch (Exception $e) {

        return array('error' => $_LANG['ncssl_error_occured'] . $e->getMessage());
    }

    if(empty($sans) || ('QuickSSL Premium'==$certType)){
        try {
            $requestParams = array("DomainName" => $domain, "CertificateType" => $certType);

            //$api 
            $response = $api->request("namecheap.ssl.getApproverEmailList", $requestParams);

            $result = $api->parseResponse($response);
            $approvalEmailList = $result['GetApproverEmailListResult']['Genericemails']['email'];

            if (!empty($result['GetApproverEmailListResult']['Domainemails']['email'])) {
                if (!is_array($result['GetApproverEmailListResult']['Domainemails']['email'])) {
                    $result['GetApproverEmailListResult']['Domainemails']['email'] = array($result['GetApproverEmailListResult']['Domainemails']['email']);
                }
                $approvalEmailList = array_merge($result['GetApproverEmailListResult']['Domainemails']['email'], $approvalEmailList);
            }
            
            $approvalEmailList = array_unique($approvalEmailList);
            
        } catch (Exception $e) {

            return array('error' => $_LANG['ncssl_error_occured'] . $e->getMessage());
        }
    }
    else if(!empty($sans)){
        $approvalEmailList = array(
            'webmaster@',
            'postmaster@',
            'hostmaster@',
            'administrator@',
            'admin@'
        );
    }
    
    
    $customNotice = '';
    if(empty($sans) || ('QuickSSL Premium'==$certType)){
        $customNotice = $_LANG['ncssl_custom_phrase_sslcertapproveremaildetails'];
    }else{
        $customNotice = $_LANG['ncssl_custom_phrase_sslcertapproveremaildetails_san'];
    }
    
    if(!in_array( $certType, 
            array(
                'Secure Site','Secure Site Pro','Secure Site with EV','Secure Site Pro with EV','True BusinessID With EV','True BusinessID',
                'True BusinessID Wildcard','SSL Web Server','SGC Super Certs','SSL WebServer EV','True BusinessID Multi Domain','True BusinessID with EV Multi Domain',
                'InstantSSL', 'PremiumSSL', 'EV SSL', 'EV SSL SGC', 'InstantSSL Pro', 'PremiumSSL Wildcard', 'Multi Domain SSL', 'Unified Communications', 'EV Multi Domain SSL'
                )
        )){
        
        array_unshift($approvalEmailList, $_LANG['ncssl_http_based_validation']);
        
        if(empty($sans) || ('QuickSSL Premium'==$certType)){
            $customNotice .= $_LANG['ncssl_custom_phrase_sslcertapproveremaildetails_http_based'];
        }else{
            $customNotice .= $_LANG['ncssl_custom_phrase_sslcertapproveremaildetails_san_http_based'];
        }
        
    }
    
    
    if(!empty($customNotice)){
        global $smarty;    
        $LANG = $smarty->get_template_vars('LANG');
        $LANG['sslcertapproveremaildetails'] = $customNotice;
        $smarty->assign('LANG', $LANG);        
    }

    // update service domain name
    $sql = "SELECT domain FROM tblhosting WHERE id = '" . (int) $params['serviceid'] . "'";
    $row = NcSql::sql2row($sql);        
    if (!strlen($row['domain'])) {
        $sql = "UPDATE tblhosting SET domain = '" . NcSql::e($domain) . "' WHERE id = '" . (int) $params['serviceid'] . "'";
        NcSql::q($sql);
    }
    
    
    $values["approveremails"] = $approvalEmailList;
    
    
    if('COMODO'!=$provider && $localCertInfo->inReissueState()){
        
        // disable approver email list
        
        $backupedConfigData = $localCertInfo->getConfigData(true);
        
        
        if(empty($backupedConfigData['approveremail'])){
            
            if($localCertInfo->hasFileName()){
                $backupedConfigData['approveremail'] = $_LANG['ncssl_http_based_validation'];
            }else{
                
                try{
                    $api = _namecheapssl_initApi($params);
                    $response = $api->request("namecheap.ssl.getInfo", array('CertificateID' => (int) $localCertInfo->getRemoteId() ));
                    $result = $api->parseResponse($response);               
                    $backupedConfigData['approveremail'] = $result['SSLGetInfoResult']['CertificateDetails']['ApproverEmail'];
                    
                } catch (Exception $e) {
                    return array('error' => $e->getMessage());
                }
                
            }
            
        }
        
        $values['approveremails'] = array($backupedConfigData['approveremail']);
        
        $script  = '<script>';
        $script .= "$(document).ready(function(){";
        
        //$script .= "$('[value=\"".addslashes($backupedConfigData['approveremail'])."\"]').prop('checked',true)\n";
        $script .= "$('[name=approveremail]').prop('disabled',true)\n";
        $script .= "$('[name=approveremail]:last').after($('<input type=hidden name=approveremail value=\"" . addslashes(htmlspecialchars($backupedConfigData['approveremail'])) . "\" >'))\n";
        
        
        $script .= "})";
        $script .= '</script>';
        
        _namecheapssl_replaceLangVariable('sslcertapproveremaildetails', $_LANG['ncssl_reissue_notice_step2'] . $script);
        
    }
    
    
    namecheapssl_log('client.stepTwo', 'client_step_two', array($certType, $domain), $params['serviceid']);
    
    
    // hide approver emails for ov/ev symantec certificates
    if( (NcLocalCertInfo::CERTIFICATE_VALIDATION_TYPE_EV == $localCertInfo->getValidationType() || NcLocalCertInfo::CERTIFICATE_VALIDATION_TYPE_OV == $localCertInfo->getValidationType()) && NcLocalCertInfo::PROVIDER_COMODO!=$provider){
        $script  = '<script>';
        $script .= "$(document).ready(function(){";
        $script .= "$('[name=approveremail]').hide()\n";
        $script .= "})";
        $script .= '</script>';
        _namecheapssl_replaceLangVariable('sslcertapproveremaildetails',  $script);
        $values['approveremails'] = array($_LANG['ncssl_symantec_approver_email_notice']);
    }
    
    
    return $values;
    
}

function namecheapssl_SSLStepThree($params) {
    
    
    global $CONFIG, $_LANG;
    namecheapssl_initlang();
    
    
    $localCertInfo = _namecheapssl_getLocalCertInfo($params['serviceid']);
    if(!$localCertInfo->loadServerTypes()){
        return array( 'error' => $_LANG['ncssl_unable_retrieve_certtypes'] . ' ' . $_LANG['ncssl_try_again_in_several_minutes'] );
    }
    $provider = $localCertInfo->getProvider();
    
    
    $useHttpBasedValidation = (false === strpos($params['approveremail'], '@')) && $_LANG['ncssl_symantec_approver_email_notice']!=$params['approveremail'];
    
    
    // added 20/02/2012 - whmcs 4.4.2 compatibility
    if (empty($params['configdata']) && !empty($_SESSION['namecheapssl'])) {
        $params['configdata'] = $_SESSION['namecheapssl'];
    }
    // end: added 20/02/2012 - whmcs 4.4.2 compatibility


    $reissueProcess = false;
    
    $sql = "SELECT id FROM mod_namecheapssl WHERE reissue='1' AND certificate_id='" . (int)$params['remoteid'] . "'";
    if (NcSql::sqlNumRows($sql)) {
        $reissueProcess = true;
    }


    $_fields = namecheapssl_getModuleConfigFields();
    $_webServerTypes = namecheapssl_getWebServerTypes();

    
    $requestParams = array(
        'CertificateID' => $params["remoteid"],
        'ApproverEmail' => $params["approveremail"],
        'csr' => $params['configdata']["csr"],
        'WebServerType' => $_webServerTypes[$params['configdata']['servertype']],
        'AdminEmailAddress' => $params['configdata']["email"],
        'AdminFirstName' => $params['configdata']["firstname"],
        'AdminLastName' => $params['configdata']["lastname"],
        'AdminOrganizationName' => $params['configdata']["orgname"],
        'AdminAddress1' => $params['configdata']["address1"],
        'AdminAddress2' => $params['configdata']["address2"],
        'AdminJobTitle' => $params['configdata']["jobtitle"],
        'AdminCity' => $params['configdata']["city"],
        'AdminStateProvince' => $params['configdata']["state"],
        'AdminPostalCode' => $params['configdata']["postcode"],
        'AdminCountry' => $params['configdata']["country"],
        'AdminPhone' => $params['configdata']["phonenumber"]
    );

    if ($useHttpBasedValidation) {
        $requestParams['HttpDCValidation'] = 'true';
        unset($requestParams['ApproverEmail']);
    }
    
    
    // remove dcv or email validation
    if( (NcLocalCertInfo::CERTIFICATE_VALIDATION_TYPE_EV == $localCertInfo->getValidationType() || NcLocalCertInfo::CERTIFICATE_VALIDATION_TYPE_OV == $localCertInfo->getValidationType()) && NcLocalCertInfo::PROVIDER_COMODO!=$provider){
        unset($requestParams['ApproverEmail'],$requestParams['HttpDCValidation']);
    }
    
    
    /* if (!empty($requestParams['AdminCountry']) && !empty($requestParams['AdminOrganizationName'])) {
      $requestParams['CompanyIncorporationCountry'] = $requestParams['AdminCountry'];
      } */


    if ((!empty($params["configoptions"]["TechEmail"]) && strlen($params["configoptions"]["TechEmail"])) ||
            (!empty($params["configoption11"]) && strlen($params["configoption11"]))) {
        if (!empty($params["configoptions"]["TechEmail"])) {
            $requestParams['TechEmailAddress'] = $requestParams['BillingEmailAddress'] = $params["configoptions"]["TechEmail"];
        } else {
            $requestParams['TechEmailAddress'] = $requestParams['BillingEmailAddress'] = $params["configoption11"];
        }

        if (!empty($params["configoptions"]["TechFirstName"])) {
            $requestParams['TechFirstName'] = $requestParams['BillingFirstName'] = $params["configoptions"]["TechFirstName"];
        } else {
            $requestParams['TechFirstName'] = $requestParams['BillingFirstName'] = $params["configoption12"];
        }

        if (!empty($params["configoptions"]["TechLastName"])) {
            $requestParams['TechLastName'] = $requestParams['BillingLastName'] = $params["configoptions"]["TechLastName"];
        } else {
            $requestParams['TechLastName'] = $requestParams['BillingLastName'] = $params["configoption13"];
        }

        if (!empty($params["configoptions"]["TechAddress1"])) {
            $requestParams['TechAddress1'] = $requestParams['BillingAddress1'] = $params["configoptions"]["TechAddress1"];
        } else {
            $requestParams['TechAddress1'] = $requestParams['BillingAddress1'] = $params["configoption14"];
        }

        if (!empty($params["configoptions"]["TechCity"])) {
            $requestParams['TechCity'] = $requestParams['BillingCity'] = $params["configoptions"]["TechCity"];
        } else {
            $requestParams['TechCity'] = $requestParams['BillingCity'] = $params["configoption15"];
        }

        if (!empty($params["configoptions"]["TechStateProvince"])) {
            $requestParams['TechStateProvince'] = $requestParams['BillingStateProvince'] = $params["configoptions"]["TechStateProvince"];
        } else {
            $requestParams['TechStateProvince'] = $requestParams['BillingStateProvince'] = $params["configoption16"];
        }

        if (!empty($params["configoptions"]["TechCountry"])) {
            $requestParams['TechCountry'] = $requestParams['BillingCountry'] = $params["configoptions"]["TechCountry"];
        } else {
            $requestParams['TechCountry'] = $requestParams['BillingCountry'] = $params["configoption17"];
        }

        if (!empty($params["configoptions"]["TechPostalCode"])) {
            $requestParams['TechPostalCode'] = $requestParams['BillingPostalCode'] = $params["configoptions"]["TechPostalCode"];
        } else {
            $requestParams['TechPostalCode'] = $requestParams['BillingPostalCode'] = $params["configoption18"];
        }

        if (!empty($params["configoptions"]["TechPhone"])) {
            $requestParams['TechPhone'] = $requestParams['BillingPhone'] = $params["configoptions"]["TechPhone"];
        } else {
            $requestParams['TechPhone'] = $requestParams['BillingPhone'] = $params["configoption19"];
        }

        if (!empty($params["configoptions"]["TechOrganizationName"])) {
            $requestParams['TechOrganizationName'] = $requestParams['BillingOrganizationName'] = $params["configoptions"]["TechOrganizationName"];
        } else {
            $requestParams['TechOrganizationName'] = $requestParams['BillingOrganizationName'] = $params["configoption20"];
        }
    }

    // load information from db. todo: remove this queries, use locaCertInfo only
    $sql = "SELECT * FROM tblsslorders WHERE serviceid = '" . (int) $params['serviceid'] . "'";
    $sslOrderInfo = NcSql::sql2row($sql);
    
    $sql = "SELECT * FROM mod_namecheapssl WHERE id = '" . (int)$sslOrderInfo['id'] . "'";
    $sslOrderCustomInfo = NcSql::sql2row($sql);

    
    
    
    if(NcLocalCertInfo::CERTIFICATE_VALIDATION_TYPE_EV==$localCertInfo->getValidationType() ||
            NcLocalCertInfo::PROVIDER_THAWTE == $provider ||
            NcLocalCertInfo::PROVIDER_VERISIGN == $provider){
        if(!empty($params['configoption22'])){
            $requestParams['TechJobTitle'] = $requestParams['BillingJobTitle'] = $params['configoption22'];
        }else{
            $requestParams['TechJobTitle'] = $requestParams['BillingJobTitle'] = 'NA';
        }
    }
    
    
    
    $sans = array();        
    if(!empty($params['fields'])){
        foreach($params['fields'] as $fieldName=>$fieldValue){
            $fieldValue = trim($fieldValue);
            if( substr($fieldName,0,4 )=='san_' && !empty($fieldValue)){
                // it's san cert
                $sans[] = $fieldValue;
            }
        }
    }
    
    
    
    // san related params
    $sanProcess = false;
    if(!empty($sans)){
        
        $sanProcess = true;
        
        
        // DNS Names in Request
        $requestParams['DNSNames'] = join(',',$sans);
        
        
        $sanQuickSslPremium = false;
        if('QuickSSL Premium'==$sslOrderCustomInfo['type']){
            $sanQuickSslPremium = true;
        }
        
        if( (NcLocalCertInfo::CERTIFICATE_VALIDATION_TYPE_EV == $localCertInfo->getValidationType() || NcLocalCertInfo::CERTIFICATE_VALIDATION_TYPE_OV == $localCertInfo->getValidationType()) && NcLocalCertInfo::PROVIDER_COMODO!=$provider){
            // skip approver emails
        }else{
            
            if(!$useHttpBasedValidation && !$sanQuickSslPremium){
                
                // set approver email to main domain
                $requestParams['ApproverEmail'] = $params['approveremail'] . $params['domain'];        
                $sansApproverEmails = array();
                foreach($sans as $item){
                    $sansApproverEmails[] = $params['approveremail'] . $item;
                }
                $requestParams['DNSApproverEmails'] = join(',',$sansApproverEmails);

            }
            
        }
        
        
        if($sanQuickSslPremium){
            $currentAdditionalSansCount = 0;
        }else{
            
            if(!empty($params['configoptions']['san'])){
                $currentAdditionalSansCount = count($sans);
                
                $currentAdditionalSansCount -= _namecheapssl_getDefaultSunCount($sslOrderCustomInfo['type']);
                if($currentAdditionalSansCount<0){
                    $currentAdditionalSansCount=0;
                }
                
            }else{
                $currentAdditionalSansCount = 0;
            }
            
        }
        
        
    }
    
    // 
    // additional params
    if (!empty($params['configdata']['fields'])) {

        foreach ($params['configdata']['fields'] as $k => $v) {
            
            // skip sans field
            if(substr($k,0,4 )=='san_'){
                continue;
            }
            
            if ('OrganizationRepCallbackMethod' == $k || 'OrganizationRepCallbackDestinationSame' == $k) {
                $v = strtolower($v);
            }
            $requestParams[$k] = $v;
        }
    }
    //


    $certificateId = (int) $params["remoteid"];

    
    if(0==$certificateId && $reissueProcess){
        return array("error"=>"Unknown remote id");
    }
    
    
    
    if(0==$certificateId) {

        // 
        // Use Tokens section
        // NC Service Feature
        // Find existing certificate (1.1)
        //
        $useTokens = isset($params[$_fields['UseTokens']]) ? $params[$_fields['UseTokens']] : $params['configoption10'];
        
        
        if ($useTokens) {
            
            if ('positivessl' == strtolower($sslOrderInfo['certtype'])) {
                $certificateTypesForSearch = array('positivessl free', 'positivessl');
            } else {
                $certificateTypesForSearch = array($sslOrderInfo['certtype']);
            }

            foreach ($certificateTypesForSearch as $certificateType) {
                try {
                    
                    $requestParamsUseTokens = array("Page" => 1, "PageSize" => 100, "ListType" => "NewPurchase", 'Years' => $sslOrderCustomInfo['period']);
                    
                    
                    $api = _namecheapssl_initApi($params);

                    do {
                        $response = $api->request("namecheap.ssl.getList", $requestParamsUseTokens);
                        $result = $api->parseResponse($response);

                        foreach ($result['SSLListResult']['SSL'] as $k => $certInfo) {

                            $k = (string) $k;
                            if ($k != '@attributes') {
                                $certInfo = $certInfo['@attributes'];
                            }

                            // check activation expire date for free tokens
                            if (!empty($certInfo['ActivationExpireDate'])) {

                                $now = time() + date("Z");
                                $gmt = $api->getGmtTS($response);
                                $expireTimestamp = strtotime($certInfo['ActivationExpireDate']) + $gmt;

                                // skip expired free certificate
                                if ($expireTimestamp < $now) {
                                    continue;
                                }
                            }

                            if (strtolower(trim($certInfo['SSLType'])) == strtolower($certificateType)) {
                                
                                $sql = "SELECT id FROM tblsslorders WHERE remoteid='".(int)$certInfo['CertificateID']."'";
                                if (0 == NcSql::sqlNumRows($sql)) {
                                    
                                    $certificateId = $certInfo['CertificateID'];
                                    
                                    // 
                                    if($sanProcess){
                                        try{
                                            $api = _namecheapssl_initApi($params);
                                            $response = $api->request("namecheap.ssl.getInfo", array('CertificateID' => (int) $certificateId ));
                                            $result = $api->parseResponse($response);               
                                        } catch (Exception $e) {
                                            return array('error' => $e->getMessage());
                                        }            
                                        $existingSansCount = !empty($result['SSLGetInfoResult']['@attributes']['SANSCount']) ? $result['SSLGetInfoResult']['@attributes']['SANSCount'] : 0;            
                                        $existingAdditionalSansCount = $existingSansCount - _namecheapssl_getDefaultSunCount($sslOrderCustomInfo['type']);
                                        if($currentAdditionalSansCount>$existingAdditionalSansCount){
                                            try{
                                                // call .purchasemoresans
                                                $api = _namecheapssl_initApi($params);
                                                $response = $api->request("namecheap.ssl.purchasemoresans", 
                                                        array(
                                                            'CertificateID' => (int) $certificateId ,
                                                            'NumberOfSANSToAdd' => $currentAdditionalSansCount - $existingAdditionalSansCount
                                                        )
                                                        );
                                                $result = $api->parseResponse($response);
                                            } catch (Exception $e) {
                                                return array('error' => $e->getMessage());
                                            }
                                        }
                                    }
                                    //
                                    
                                    $certificateId = $certInfo['CertificateID'];
                                    namecheapssl_log('client.stepThree', 'client_step_three_remote_id_selected_from_account', array($sslOrderInfo['certtype'], $certificateId), $params['serviceid']);
                                    break 2;
                                }
                            }
                        }

                        $totalPages = ceil($result['Paging']['TotalItems'] / $result['Paging']['PageSize']);
                        $currentPage = $result['Paging']['CurrentPage'];
                        if ($currentPage >= $totalPages) {
                            break;
                        }
                        $requestParamsUseTokens['Page']++;
                    } while (1);
                } catch (Exception $e) {
                    
                    return array('error' => "An error occured: " . $e->getMessage());
                }
            }
        }
        
        
        
        // end of "Use Tokens" section
        // 
        // OR create new cert (1.2)
        if (0 == $certificateId) {

            $request_params = array(
                "Years" => $sslOrderCustomInfo['period'],
                "PromotionCode" => isset($params[$fields['PromotionCode']]) ? $params[$fields['PromotionCode']] : $params["configoption7"],
                "Quantity" => "1",
                "Type" => $sslOrderCustomInfo['type']
            );
            
            if($sanProcess && $currentAdditionalSansCount>0){
                $request_params['SANSToAdd'] = $currentAdditionalSansCount;                
            }

            try {
                $api = _namecheapssl_initApi($params);
                $response = $api->request("namecheap.ssl.create", $request_params);
                $result = $api->parseResponse($response);
                $certificateId = $result['SSLCreateResult']['SSLCertificate']['@attributes']['CertificateID'];
                namecheapssl_log('client.stepThree', 'client_step_three_remote_id_created', array($sslOrderInfo['certtype'], $certificateId), $params['serviceid']);
            } catch (Exception $e) {
                
                return array('error' => $_LANG['ncssl_error_occured'] . $e->getMessage());
            }
        }


        // add remote id to request params
        $requestParams['CertificateID'] = $certificateId;

        // (2) update remote id in existing tables tblsslorders, mod_namecheapssl; important
        $sql = "UPDATE mod_namecheapssl SET certificate_id='" . (int)$certificateId . "',admin_email='" . NcSql::e($requestParams['AdminEmailAddress']) . "' WHERE id='" . (int)$sslOrderCustomInfo['id'] . "'";
        NcSql::q($sql);
        $sql = "UPDATE tblsslorders SET remoteid='" . (int)$certificateId . "' WHERE id='" . (int)$sslOrderInfo['id'] . "'";
        NcSql::q($sql);
        
        
    } else {
        
    }
    
    
    if($reissueProcess){
        
        //
        // certificate reissue process
        //
        
        
        // check sans count
        if($sanProcess){            
            try{
                $api = _namecheapssl_initApi($params);
                $response = $api->request("namecheap.ssl.getInfo", array('CertificateID' => (int) $certificateId ));
                $result = $api->parseResponse($response);               
            } catch (Exception $e) {
                return array('error' => $e->getMessage());
            }            
            $existingSansCount = !empty($result['SSLGetInfoResult']['@attributes']['SANSCount']) ? $result['SSLGetInfoResult']['@attributes']['SANSCount'] : 0;            
            $existingAdditionalSansCount = $existingSansCount - _namecheapssl_getDefaultSunCount($sslOrderCustomInfo['type']);
            if($currentAdditionalSansCount>$existingAdditionalSansCount){
                try{
                    // call .purchasemoresans
                    $api = _namecheapssl_initApi($params);
                    $response = $api->request("namecheap.ssl.purchasemoresans", 
                            array(
                                'CertificateID' => (int) $certificateId ,
                                'NumberOfSANSToAdd' => $currentAdditionalSansCount - $existingAdditionalSansCount
                               )
                            );
                    $result = $api->parseResponse($response);
                } catch (Exception $e) {
                    return array('error' => $e->getMessage());
                }
            }
        }
        
        
        try{
            
            
            $api = _namecheapssl_initApi($params);
            $response = $api->request("namecheap.ssl.reissue", $requestParams);
            $result = $api->parseResponse($response);
            
            // whmcs 4.4.2 compatibility
            if (!empty($_SESSION['namecheapssl'])) {
                unset($_SESSION['namecheapssl']);
            }
            // whmcs 4.4.2 compatibility
            
            
            $localCertInfo = _namecheapssl_getLocalCertInfo($params['serviceid']);
            $localCertInfo->clearBackupedConfigData();
            
            
            $revokeManager = _namecheapssl_getRevokeManager($params);
            $revokeManager->addRemoteIdForRevocation($certificateId);
            
            
            $sql = "UPDATE tblsslorders SET remoteid='".(int)$result['SSLReissueResult']['@attributes']['ID'] . "' WHERE remoteid='" . (int)$certificateId . "'";
            NcSql::q($sql);
            
            $sql = "UPDATE mod_namecheapssl SET certificate_id='".(int)$result['SSLReissueResult']['@attributes']['ID']."', reissue='0' WHERE certificate_id='" . (int)$certificateId . "'";
            NcSql::q($sql);

            namecheapssl_log('client.reissue', 'client_reissue', array($certificateId, $result['SSLReissueResult']['@attributes']['ID']), $params['serviceid']);
            
            if ($useHttpBasedValidation) {
                if(!isset($result['SSLReissueResult']['HttpDCValidation']['DNS'][0])){
                    $result['SSLReissueResult']['HttpDCValidation']['DNS'][0] = $result['SSLReissueResult']['HttpDCValidation']['DNS'];
                }
                $fileName = $result['SSLReissueResult']['HttpDCValidation']['DNS'][0]['FileName'];
                $fileContent = $result['SSLReissueResult']['HttpDCValidation']['DNS'][0]['FileContent'];
            }
            
            
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }
        
        
        //
        // end of certificate reissue process
        //
        
    }
    else
    {
        
        //
        // certificate activation process
        //
        try {
            $api = _namecheapssl_initApi($params);
            $response = $api->request("namecheap.ssl.activate", $requestParams);
            $result = $api->parseResponse($response);
            
            if($useHttpBasedValidation){
                namecheapssl_log('client.stepThree', 'client_step_three_activated_http_based_validation', array($sslOrderInfo['certtype'], $certificateId, $requestParams['AdminEmailAddress']), $params['serviceid']);                
            }else{
                namecheapssl_log('client.stepThree', 'client_step_three_activated', array($sslOrderInfo['certtype'], $certificateId, $params["approveremail"], $requestParams['AdminEmailAddress']), $params['serviceid']);
            }
            
            // added 20/02/2012 - whmcs 4.4.2 compatibility
            if (!empty($_SESSION['namecheapssl'])) {
                unset($_SESSION['namecheapssl']);
            }
            
            if ($useHttpBasedValidation) {
                if(!isset($result['SSLActivateResult']['HttpDCValidation']['DNS'][0]))
                {
                    $result['SSLActivateResult']['HttpDCValidation']['DNS'][0] = $result['SSLActivateResult']['HttpDCValidation']['DNS'];
                }
                $fileName = $result['SSLActivateResult']['HttpDCValidation']['DNS'][0]['FileName'];
                $fileContent = $result['SSLActivateResult']['HttpDCValidation']['DNS'][0]['FileContent'];
            }
            
        } catch (Exception $e) {
            
            // change error message
            if (2011166==$e->getCode()){
                return array('error' => $_LANG['ncssl_error_2011166'] . $params['domain']);
            }
            
            
            // default error message
            return array('error' => $_LANG['ncssl_error_occured'] . $e->getMessage());
        }
        //
        // end of certificate activation process
        //
        
    }
    
    
    // 
    // http based validation values
    // 
    if ($useHttpBasedValidation) {

        // save file name and file content and redirect to custom client area page
        $sql = "UPDATE mod_namecheapssl SET file_name='" . NcSql::e($fileName) . "', file_content='" . NcSql::e($fileContent) . "' WHERE id='".(int)$sslOrderCustomInfo['id'] ."'";
        NcSql::q($sql);

        global $smarty;
        if (!empty($_LANG['ncssl_custom_phrase_sslconfigcompletedetails'])) {

            $LANG = $smarty->get_template_vars('LANG');

            $_LANG['ncssl_custom_phrase_sslconfigcompletedetails'] = str_replace('%filename%', $fileName, $_LANG['ncssl_custom_phrase_sslconfigcompletedetails']);
            $_LANG['ncssl_custom_phrase_sslconfigcompletedetails'] = str_replace('%contents%', nl2br($fileContent), $_LANG['ncssl_custom_phrase_sslconfigcompletedetails']);

            $LANG['sslconfigcompletedetails'] = $_LANG['ncssl_custom_phrase_sslconfigcompletedetails'];
            $smarty->assign('LANG', $LANG);
        }
    }else{
        // query changed 16.04
        $sql = "UPDATE mod_namecheapssl SET file_name='', file_content='' WHERE id='".(int)$sslOrderCustomInfo['id'] ."'";
        NcSql::q($sql);
    }
    //
    // http based validation values
    //



    return $values;
}

function namecheapssl_Renew($params) {

    if (!namecheapssl_check_install()) {
        return "Namecheap SSL Module error. Addon Module Namecheap SSL Module Addon hasn't been activated/upgraded. Please go to Setup - Addon Modules and perform activation/go to addon page.";
    }

    global $CONFIG;
    
    
    $_fields = namecheapssl_getModuleConfigFields();
    $_webServerTypes = namecheapssl_getWebServerTypes();


    $sql = "SELECT tblsslorders.*, tblhosting.billingcycle FROM tblsslorders LEFT JOIN tblhosting ON (tblhosting.id = tblsslorders.serviceid) WHERE serviceid = '" . (int) $params["serviceid"] . "'";
    $certificateOrderData = NcSql::sql2row($sql);

    $cycles = array('Annually' => 1, 'Biennially' => 2, 'Triennially' => 3);

    $request_params = array("CertificateID" => $certificateOrderData['remoteid'],
        "Years" => $cycles[$certificateOrderData['billingcycle']],
        "SSLType" => $certificateOrderData['certtype']
    );

    
    try {
        $api = _namecheapssl_initApi($params);
        $response = $api->request("namecheap.ssl.renew", $request_params);
        $result = $api->parseResponse($response);

        $certId = (int) $result['SSLRenewResult']['@attributes']['CertificateID'];

        if (0 == $certId) {
            return "An error ocurred: invalid remote id recieved for renewal: " . $result['SSLRenewResult']['@attributes']['CertificateID'];
        }

        $status = _namecheapssl_getIncompleteStatus();
        
        $sql = "UPDATE tblsslorders SET remoteid = '" . (int)$certId . "', status = '" . NcSql::e($status) . "' WHERE id = '" . (int)$certificateOrderData['id'] . "'";
        NcSql::q($sql);
        
        $sql = "UPDATE mod_namecheapssl SET reissue=0, certificate_id='" . (int)$certId . "' WHERE mod_namecheapssl.id = '" . (int)$certificateOrderData['id'] . "'";
        NcSql::q($sql);

        $sslconfigurationlink = $CONFIG["SystemURL"] . "/configuressl.php?cert=" . md5($certificateOrderData['id']);
        $sslconfigurationlink = "<a href=\"$sslconfigurationlink\">$sslconfigurationlink</a>";

        sendMessage("SSL Certificate Configuration Required", $params["serviceid"], array("ssl_configuration_link" => $sslconfigurationlink));

        namecheapssl_log('system.renew', 'renewed', array($certificateOrderData['remoteid'], $certId), $params['serviceid']);
        
    } catch (Exception $e) {
        return "An error occured: " . $e->getMessage();
    }
    
    return "success";
}

function namecheapssl_AdminCustomButtonArray(){

    $buttonarray = array(
        "Cancel" => "cancel",
        "Resend Configuration Email" => "resend",
        "Reissue Certificate" => "adminstartreissue",
        "Synchronize with Namecheap" => "sync",
    );
    
    #### $buttonarray["Reset reissue status"] = "adminresetreissue";
    #### buttonarray["Renew"] = "renew";
    
    return $buttonarray;
}


function namecheapssl_cancel($params) {
    global $_LANG;

    // deleted 18/04/2012 by Andrey
    /*
      $result = select_query("tblsslorders","COUNT(*)",array("serviceid"=>$params["serviceid"],"status"=>"Incomplete"));
      $data = mysql_fetch_array($result);
      if (!$data[0]) {
      return $_LANG['ncssl_no_incomplete_certificate'];
      }
      update_query("tblsslorders",array("status"=>"Cancelled"),array("serviceid"=>$params["serviceid"]));
     */

    // added 18/04/2012 by Andrey
    return "Thanks for clicking the 'Cancel' button. Unfortunately at this time cancellation operation is not supported by Namecheap API, therefore nothing happened. If you need to cancel the certificate please contact Namecheap Support.";
}

function namecheapssl_resend($params) {
    global $CONFIG, $_LANG;
    
    $sql = "SELECT id FROM tblsslorders WHERE serviceid='" . (int)$params['serviceid'] . "'";
    $data = NcSql::sql2row($sql);
    
    $id = $data["id"];
    if (!$id) {
        return $_LANG['ncssl_no_certificate_exists'];
    }

    $sslconfigurationlink = $CONFIG["SystemURL"] . "/configuressl.php?cert=" . md5($id);
    $sslconfigurationlink = "<a href=\"$sslconfigurationlink\">$sslconfigurationlink</a>";


    sendMessage("SSL Certificate Configuration Required", $params["serviceid"], array("ssl_configuration_link" => $sslconfigurationlink));

    namecheapssl_log('admin.resend', 'admin_resend_configuration_email', null, $params['serviceid']);

    return 'success';
}


function namecheapssl_adminstartreissue($params) {

    if (!namecheapssl_check_install()) {
        return
                "Namecheap SSL Module error. Addon Module Namecheap SSL Module Addon hasn't been activated/upgraded. Please go to Setup - Addon Modules and perform activation/go to addon page."
        ;
    }
    
    global $CONFIG, $_LANG;
    namecheapssl_initlang();


    $sql = "SELECT * FROM tblsslorders WHERE serviceid = '" . (int) $params['serviceid'] . "'";
    $data = NcSql::sql2row($sql);
    
    
    if (empty($data['remoteid'])) {
        return "Remote id is missing";
    }

    $aSertificateInfo = _namecheapssl_getCertificateInfo($params, $data['remoteid']);

    $sProviderName = $aSertificateInfo["SSLGetInfoResult"]["Provider"]["Name"];
    $sCommonName = $aSertificateInfo["SSLGetInfoResult"]["CertificateDetails"]["CommonName"];

    if ('active' != $aSertificateInfo["SSLGetInfoResult"]['@attributes']['Status']) {
        return "The current status of this certificate is '{$aSertificateInfo["SSLGetInfoResult"]['@attributes']['Status']}'" .
                "";
    }


    // 1. update whmcs sertificate status
    $sql = "UPDATE mod_namecheapssl SET reissue=1 WHERE certificate_id='". (int)$data['remoteid']."'";
    NcSql::q($sql);

    $status = _namecheapssl_getIncompleteStatus();
    
    $sql = "UPDATE tblsslorders SET status='$status' WHERE id='".(int)$data['id']."'";
    NcSql::q($sql);
    
    
    // 2. send SSL Certificate Reissue Invitation

    $sslconfigurationlink = $CONFIG["SystemURL"] . "/configuressl.php?cert=" . md5($data['id']);
    $sslconfigurationlink = "<a href=\"$sslconfigurationlink\">$sslconfigurationlink</a>";

    sendMessage('SSL Certificate Reissue Invitation', $params['serviceid'], array(
        "ssl_configuration_link" => $sslconfigurationlink,
        "ssl_certificate_id" => $data['remoteid']
    ));

    namecheapssl_log('admin.initReissue', 'admin_init_reissue_success', array($sProviderName, $data['remoteid']), $params['serviceid']);
    return 'success';
    
    
    /*{
        $sLink = _get_provider_reissue_link($sProviderName);
        $sMessage = $_LANG['ncssl_client_reissue_notice_1'] . ' ' . "<a target=\"_blank\" href=\"$sLink?domain=$sCommonName\">$sLink?domain=$sCommonName</a>" . "\n\n" . $_LANG['ncssl_client_reissue_notice_3'];
        namecheapssl_log('admin.initReissue', 'admin_init_reissue_link', array($sProviderName, $data['remoteid'], $sLink), $params['serviceid']);
        if (version_compare("5.0.0", $CONFIG['Version'], "<=")) {
            return strip_tags($sMessage);
        } else {
            return $sMessage;
        }
    }*/
    
}


function namecheapssl_sync($params) {
    
    global $CONFIG;
    
    
    $_fields = namecheapssl_getModuleConfigFields();
    $_webServerTypes = namecheapssl_getWebServerTypes();
    
    
    $sql = "SELECT * FROM tblsslorders WHERE serviceid = '".(int)$params['serviceid']."'";
    $cert = NcSql::sql2row($sql);
    
    
    if (empty($cert['remoteid'])) {
        return "Unknown RemoteId for this product.";
    }
    
    
    try {

        $request_params = array('CertificateID' => (int) $cert['remoteid']);

        $api = _namecheapssl_initApi($params);
        $response = $api->request("namecheap.ssl.getInfo", $request_params);
        $result = $api->parseResponse($response);


        // sync expire date
        $expireDate = $result['SSLGetInfoResult']['@attributes']['Expires'];
        if (!empty($expireDate)) {
            
            list($month, $day, $year) = explode("/", $expireDate);
            
            $duedate = "$year-$month-$day";
            
            // correct due date using offset
            $sync_date_offset = NcSql::sql2cell("SELECT value FROM mod_namecheapssl_settings WHERE name='sync_date_offset'");
            if($sync_date_offset){
                $duedate = date('Y-m-d',strtotime($duedate . "-$sync_date_offset days"));
            }
            
            $sql = "UPDATE tblhosting SET nextduedate = '".  NcSql::e($duedate)."', nextinvoicedate = '".  NcSql::e($duedate)."' WHERE id = '" . (int) $params['serviceid'] . "'";
            NcSql::q($sql);
            
            namecheapssl_log('admin.sync', 'admin_sync_updated_duedate', array("$year-$month-$day"), $params['serviceid']);
        }



        // sync domain name
        if (!empty($result['SSLGetInfoResult']['CertificateDetails']['CommonName'])) {
            
            $domain = $result['SSLGetInfoResult']['CertificateDetails']['CommonName'];
            
            $sql = "UPDATE tblhosting SET domain = '".NcSql::e($domain)."' WHERE id = '" . (int) $params['serviceid'] . "'";
            NcSql::q($sql);
            
            namecheapssl_log('admin.sync', 'admin_sync_updated_domain', array($domain), $params['serviceid']);
        }

        // sync remote id for "replaced" items
        if ('replaced' == $result['SSLGetInfoResult']['@attributes']['Status']) {
            
            $sql = "UPDATE tblsslorders SET remoteid='" . (int)$result['SSLGetInfoResult']['@attributes']['ReplacedBy']."' WHERE serviceid='" . (int)$params['serviceid'] . "'";
            NcSql::q($sql);

            // added 07-03-2013. not required.
            $sql = "UPDATE mod_namecheapssl SET certificate_id='".(int)$result['SSLGetInfoResult']['@attributes']['ReplacedBy']."' WHERE id='". (int)$cert['id']."'";
            NcSql::q($sql);

            namecheapssl_log('admin.sync', 'admin_sync_updated_remoteid', array($cert['remoteid'], $result['SSLGetInfoResult']['@attributes']['ReplacedBy']), $params['serviceid']);
            
        }
    } catch (Exception $e) {
        return $e->getMessage();
    }
    return "success";
}

// //
function namecheapssl_AdminServicesTabFields($params) {

    global $CONFIG, $_LANG;
    namecheapssl_initlang();
    

    if (isset($_REQUEST['viewDetails'])) {

        
        $sql = "SELECT * FROM tblsslorders WHERE serviceid='" . (int)$params['serviceid'] . "'";
        $cert = NcSql::sql2row($sql);
        
        $configdata = @unserialize($cert['configdata']);
        
        
        $sans = array();        
        if(!empty($configdata['fields'])){
            foreach($configdata['fields'] as $fieldName=>$fieldValue){
                if('san_' == substr($fieldName,0,4)){
                    $sans[] = htmlspecialchars($fieldValue);
                }
            }
        }
        
        
        if (empty($cert['remoteid'])) {
            namecheapssl_log('admin.viewInfo', 'admin_view_info_for_non_configured_cert', null, $params['serviceid']);
            $fieldssarray = array("Certificate Info" => "Certificate info will be available after activation");
            return $fieldssarray;
        }

        try {
            $request_params = array('CertificateID' => (int) $cert['remoteid']);

            $api = _namecheapssl_initApi($params);
            $response = $api->request("namecheap.ssl.getInfo", $request_params);
            $result = $api->parseResponse($response);
            
            
            if (!empty($result['SSLGetInfoResult']['CertificateDetails']['CSR'])) {
                
                $csr_result = $api->parseResponse($api->request("namecheap.ssl.parseCSR", array(
                    'csr' => $result['SSLGetInfoResult']['CertificateDetails']['CSR'],
                    'CertificateType' => $result['SSLGetInfoResult']['@attributes']['Type']
                    )));
                
            }
            
        } catch (Exception $e) {
            $fieldsarray = array("Certificate Info" => $e->getMessage());
            return $fieldsarray;
        }

        namecheapssl_log('admin.view_info', 'admin_view_info', array((int) $cert['remoteid']), $params['serviceid']);

        // view data
        $vw = $result['SSLGetInfoResult'];
        $vw_csr = $csr_result['SSLParseCSRResult']['CSRDetails'];
        
        
        
        $html = "
    
    <fieldset style=\"margin:10px 0\">
        <legend><b>{$_LANG['ncssl_admin_viewdetails_certificate_details']}</b></legend>
        <table>
            <tr><td>{$_LANG['ncssl_admin_viewdetails_status']}:</td><td>".htmlspecialchars($vw['@attributes']['Status'])."</td></tr>
            <tr><td>{$_LANG['ncssl_admin_viewdetails_status_description']}:</td><td>".htmlspecialchars($vw['@attributes']['StatusDescription'])."</td></tr>
            <tr><td>{$_LANG['ncssl_admin_viewdetails_type']}:</td><td>".htmlspecialchars($vw['@attributes']['Type'])."</td></tr>
            <tr><td>{$_LANG['ncssl_admin_viewdetails_issued_on']}:</td><td>".htmlspecialchars($vw['@attributes']['IssuedOn'])."</td></tr>
            <tr><td>{$_LANG['ncssl_admin_viewdetails_expires']}:</td><td>".htmlspecialchars($vw['@attributes']['Expires'])."</td></tr>
            <tr><td>{$_LANG['ncssl_admin_viewdetails_activation_expire_date']}:</td><td>".htmlspecialchars($vw['@attributes']['ActivationExpireDate'])."</td></tr>
            <tr><td>{$_LANG['ncssl_admin_viewdetails_nc_order_id']}:</td><td>".htmlspecialchars($vw['@attributes']['OrderId'])."</td></tr>
            <tr><td>{$_LANG['ncssl_admin_viewdetails_nc_cert_id']}:</td><td>".htmlspecialchars($cert['remoteid'])."</td></tr>
            "
            .
            (!empty($vw['@attributes']['SANSCount']) ? "<tr><td>{$_LANG['ncssl_admin_viewdetails_count_of_sans']}:</td><td>".htmlspecialchars($vw['@attributes']['SANSCount'])."</td></tr>":'').        
            (!empty($sans) ? "<tr><td>{$_LANG['ncssl_admin_viewdetails_sans']}:</td><td>".join(',',$sans)."</td></tr>" : '')
            .
            "<tr><td>
                    {$_LANG['ncssl_admin_viewdetails_csr']}:
                </td>
                <td>
                    <div style=\"margin:5px 0; border: 1px solid #ccc; padding:2px\">
                        " . nl2br(htmlspecialchars($vw['CertificateDetails']['CSR'])) . "
                    </div>
                    ";

        if ($vw_csr) {
            $html .="
                    <div style=\"margin:5px 0; border: 1px solid #ccc; padding:2px\">                        
                        <table>
                            <tr><td colspan=\"2\">{$_LANG['ncssl_admin_viewdetails_decoded_csr']}</td></tr>
                            <tr><td>{$_LANG['ncssl_admin_viewdetails_common_name']}:</td><td> ".htmlspecialchars($vw_csr['CommonName'])."</td></tr>
                            <tr><td>{$_LANG['ncssl_admin_viewdetails_domain_name']}:</td><td> ".htmlspecialchars($vw_csr['DomainName'])."</td></tr>
                            <tr><td>{$_LANG['ncssl_admin_viewdetails_country']}:</td><td> ".htmlspecialchars($vw_csr['Country'])."</td></tr>
                            <tr><td>{$_LANG['ncssl_admin_viewdetails_organization_unit']}:</td><td> ".htmlspecialchars($vw_csr['OrganisationUnit'])."</td></tr>
                            <tr><td>{$_LANG['ncssl_admin_viewdetails_organization']}:</td><td> ".htmlspecialchars($vw_csr['Organisation'])."</td></tr>
                            <tr><td>{$_LANG['ncssl_admin_viewdetails_valid_true_domain']}:</td><td> " . ($vw_csr['ValidTrueDomain'] ? 'yes' : 'no') . "</td></tr>
                            <tr><td>{$_LANG['ncssl_admin_viewdetails_state']}:</td><td> ".htmlspecialchars($vw_csr['State'])."</td></tr>
                            <tr><td>{$_LANG['ncssl_admin_viewdetails_locality']}:</td><td> ".htmlspecialchars($vw_csr['Locality'])."</td></tr>
                            <tr><td>{$_LANG['ncssl_admin_viewdetails_email']}:</td><td> ".htmlspecialchars($vw_csr['Email'])."</td></tr>
                        </table>
                    </div>";
        }


        $html .= "</td>
            </tr>
            <tr><td>{$_LANG['ncssl_admin_viewdetails_approver_email']}:</td><td>" . nl2br(htmlspecialchars($vw['CertificateDetails']['ApproverEmail'])) . "</td></tr>
            " .
              // . () .
            "
            <tr><td>{$_LANG['ncssl_admin_viewdetails_common_name']}:</td><td>" . nl2br(htmlspecialchars($vw['CertificateDetails']['CommonName'])) . "</td></tr>
            <tr><td>{$_LANG['ncssl_admin_viewdetails_provider_order_id']}:</td><td>" . nl2br(htmlspecialchars($vw['Provider']['OrderID'])) . "</td></tr>
            <tr><td>{$_LANG['ncssl_admin_viewdetails_provider_name']}:</td><td>" . nl2br(htmlspecialchars($vw['Provider']['Name'])) . "</td></tr>
        </table>
    </fieldset>

	";
        $fieldsarray = array("Certificate Info" => $html);
    } else {
        
        $fieldsarray = array("Certificate Info" => '<input type="button" value="' . $_LANG['ncssl_admin_viewdetails_button'] . '" onclick="window.location=\'?userid=' . (int)$params['clientdetails']['userid'] . '&id=' . (int)$params['serviceid'] . '&viewDetails\'" />');
        
    }
    
    
    $revokeManager = _namecheapssl_getRevokeManager($params);
    if($revokeManager -> showButton()){
        $fieldsarray[''] = '<input type="button" value="Revoke old certificates" onclick="if(confirm(\'' . $_LANG['ncssl_revoke_confirmation_text'] . '\')){ runModuleCommand(\'custom\',\'revoke\')}" />';
    }
    
    
    return $fieldsarray;
    
}


function namecheapssl_Revoke($params){
    
    $revokeManager = _namecheapssl_getRevokeManager($params);
    $localCertInfo = _namecheapssl_getLocalCertInfo($params['serviceid']);
    
    
    $descriptions = array();
    
    $ids = $revokeManager->getRemoteIdForRevocation();
    
    $localCertInfo->loadServerTypes();
    
    if ('COMODO'!=$localCertInfo->getProvider() && count($ids)>1){
        return $_LANG['ncssl_error_revoke_4'];
    }
    
    
    foreach($ids as $id){
        
        $api = _namecheapssl_initApi($params);
        $request_params = array(
            "CertificateID" => $id,
            "CertificateType" => $localCertInfo->getType()
        );
        
        try{
            
            $response = $api->request("namecheap.ssl.revokecertificate", $request_params);
            $result = $api->parseResponse($response);
            
            if(isset($result['RevokeCertificateResult']['Description'])){
                $descriptions[] = $result['RevokeCertificateResult']['Description'];
            }
            $revokeManager->setRemoteIdRevoked($id);
            
        } catch (Exception $e) {
            $message = $e->getMessage();
            return $message;
        }
        
    }
    
    
    $replacedDescriptions = array();
    if(!empty($descriptions)){
        foreach($descriptions as $k => $description){
            if (false!==stripos($description,'At this time, revocation of this certificate is available from the')){
                $replacedDescriptions[] = 1;
            }
            if (false!==stripos($description,'If not approved, revocation request will expire in 72 hours. Once revocation email is approved, certificate will be revoked by the certificate authority')){
                $replacedDescriptions[] = 2;
            }
            if (false!==stripos($description,'The revocation will be processed manually between us and Symantec')){
                $replacedDescriptions[] = 3;
            }
        }
    }
    
    
    if (!empty($replacedDescriptions)){
        
        if (defined("ADMINAREA") || count($replacedDescriptions)>1){
            $messageArray = array();
            foreach($replacedDescriptions as $code){
                $messageArray[] = $_LANG['ncssl_error_revoke_'.$code];
            }
            return implode(';',$messageArray);
        }else{
            
            // client Area, single message
            global $CONFIG;
            $location =  $CONFIG['SystemURL'] . '?m=namecheap_ssl&revoke_message='.$replacedDescriptions[0].'&serviceid='.$params['serviceid'];
            header("Location: $location");
            exit();
            
        }
        
        
        
    }
    
    
    
    return 'success';
    
}


function namecheapssl_ClientArea($params) {

    global $_LANG;
    namecheapssl_initlang();
    
    $sql = "SELECT * FROM tblsslorders WHERE serviceid = '" . (int)$params['serviceid'] . "'";
    $cert = NcSql::sql2row($sql);
    
    
    if (!empty($cert) && !empty($cert['id'])) {

        $code = '<span><form action="clientarea.php?action=productdetails" method="post">' . "\n";
        $code .= '<input type="hidden" name="id" value="' . (int)$params['serviceid'] . '" />' . "\n";
        $code .= '<input type="hidden" name="modop" value="custom" />' . "\n";
        $code .= '<input type="hidden" name="a" value="viewdetails" />' . "\n";
        $code .= '<input type="submit" value="' . $_LANG['ncssl_view_certificate_details'] . '" />' . "\n";
        $code .= '</form></span>' . "\n";
    }

    return $code;
}

function namecheapssl_ClientAreaCustomButtonArray($params) {
    
    global $CONFIG, $_LANG;
    namecheapssl_initlang();
    
    $status = 'not_exists';
    
    // get remoteid
    $sql = "SELECT tblsslorders.id,
                                  tblsslorders.remoteid,
                                  tblsslorders.configdata,
                                  mod_namecheapssl.admin_email,
                                  mod_namecheapssl.file_name
                             FROM tblsslorders
                             JOIN mod_namecheapssl
                             USING (id)
                             WHERE tblsslorders.serviceid = '" . (int)$params['serviceid'] . "'";
    $row = NcSql::sql2row($sql);
    
    if($row){
        $buttonarray = array(        
            $_LANG['ncssl_view_certificate_details'] => "viewdetails"        
        );
    }
    
    if($row && !empty($row['remoteid'])){
        
        $remoteid = $row['remoteid'];
        $result = _namecheapssl_getCertificateInfo($params,$remoteid);
        $status = $result['SSLGetInfoResult']['@attributes']['Status'];
        
        if('active'==$status){
            $buttonarray[$_LANG['ncssl_reissue_certificate']] = "clientstartreissue";
            $buttonarray[$_LANG['ncssl_download_certificate']] = 'download';
        }else{
            if(!empty($row['file_name'])){
                $buttonarray[$_LANG['ncssl_show_validation_file_contents']] = 'show_validation_file_contents';
            }else{
                $buttonarray[$_LANG['ncssl_resend_approver_email']] = "resendapprove";
            }
        }
        
        
        // revoke button
        $revokeManager = _namecheapssl_getRevokeManager($params);
        if($revokeManager -> showButton()){
            $buttonarray[$_LANG['ncssl_revoke_button']] = 'revoke';
        }
        
        
    }
    
    //$buttonarray['test'] = 'test';
    //$buttonarray[$_LANG['ncssl_revoke_button']] = 'revoke';
    
    /*$buttonarray = array(
        $_LANG['ncssl_resend_approver_email'] => "resendapprove",
        $_LANG['ncssl_view_certificate_details'] => "viewdetails", # 
        $_LANG['ncssl_resend_certificate'] => "resendcert", #
        $_LANG['ncssl_reissue_certificate'] => "clientstartreissue", #
        $_LANG['ncssl_show_validation_file_contents'] => 'show_validation_file_contents', #
        $_LANG['ncssl_download_certificate'] => 'download' #
    );*/
    
    return $buttonarray;
    
}


function namecheapssl_test($params){
}


function namecheapssl_resendapprove($params) {
    
    global $CONFIG, $_LANG;
    namecheapssl_initlang();

    $_fields = namecheapssl_getModuleConfigFields();
    $_webServerTypes = namecheapssl_getWebServerTypes();


    $serviceId = (int) $params['serviceid'];
    
    $sql = "SELECT * FROM tblsslorders WHERE serviceid = '" . (int)$serviceId . "'";
    $data = NcSql::sql2row($sql);
    
    $certID = $data['remoteid'];
    
    
    $type = $data['certtype'];
    $noResendTypes = array(
        //"RapidSSL",
        "QuickSSL",
        //"QuickSSL Premium",
        "True BusinessID",
        "True BusinessID with EV",
        "True BusinessID Wildcard",
        "Secure Site",
        "Secure Site Pro",
        "Secure Site with EV",
        "Secure Site Pro with EV"
    );

    if (in_array($type, $noResendTypes)) {
        $pagearray = array(
            'templatefile' => 'no_approver',
            'vars' => array());
        return $pagearray;
    }

    try {

        $request_params = array('CertificateID' => $certID);

        $api = _namecheapssl_initApi($params);
        $response = $api->request("namecheap.ssl.resendApproverEmail", $request_params);
        $result = $api->parseResponse($response);

        namecheapssl_log('client.resendApproverEmail', 'client_resend_approver_email', null, $params['serviceid']);
        
    } catch (Exception $e) {


        $message = $_LANG['ncssl_error_occured'] . $e->getMessage() . "<br />";
        $message .= $_LANG['ncssl_please_contact_support'] . " " . $CONFIG['Email'] . "<br />";
        $message .= namecheapssl_ClientArea($params);
        die($message);
    }

    return "success";
}


function namecheapssl_viewdetails($params) {
    global $CONFIG, $_LANG;
    namecheapssl_initlang();

    $_fields = namecheapssl_getModuleConfigFields();
    $_webServerTypes = namecheapssl_getWebServerTypes();


    $sql = "SELECT tblsslorders.id,
                                  tblsslorders.remoteid,
                                  tblsslorders.configdata,
                                  mod_namecheapssl.admin_email,
                                  mod_namecheapssl.file_name
                             FROM tblsslorders
                             JOIN mod_namecheapssl
                             USING (id)
                             WHERE tblsslorders.serviceid = '" . (int) $params['serviceid'] . "'";
    $data = NcSql::sql2row($sql);
    
    $certID = $data['remoteid'];
    $sslorderid = $data['id'];
    $adminEmail = $data['admin_email'];
    $fileName = $data['file_name'];
    
    $configdata = @unserialize($data['configdata']);
    
    $sans = array();        
    if(!empty($configdata['fields'])){
        foreach($configdata['fields'] as $fieldName=>$fieldValue){
            if('san_' == substr($fieldName,0,4)){
                $sans[] = htmlspecialchars($fieldValue);
            }
        }
    }

    if (!empty($certID)) {
        try {
            $request_params = array('CertificateID' => $certID);

            $api = _namecheapssl_initApi($params);
            $response = $api->request("namecheap.ssl.getInfo", $request_params);
            $result = $api->parseResponse($response);

            $status = $result['SSLGetInfoResult']['@attributes']['Status'];
            $type = $result['SSLGetInfoResult']['@attributes']['Type'];

            $renewal = 0;
            if (!empty($result['SSLGetInfoResult']['@attributes']['StatusDescription'])) {
                //"Renewal of SSL"
                if (preg_match("/Renewal/", $result['SSLGetInfoResult']['@attributes']['StatusDescription'])) {
                    $renewal = 1;
                }
            }

            $approverEmail = $result['SSLGetInfoResult']['CertificateDetails']['ApproverEmail'];
            $domain = $result['SSLGetInfoResult']['CertificateDetails']['CommonName'];
            
            $sansCount = !empty($result['SSLGetInfoResult']['@attributes']['SANSCount'])?$result['SSLGetInfoResult']['@attributes']['SANSCount']:'';
            
            

            ### $csr = $result['SSLGetInfoResult']['CertificateDetails']['CSR'];
            
        } catch (Exception $e) {

            $message = $_LANG['ncssl_error_occured'] . $e->getMessage() . "<br />";
            $message .= $_LANG['ncssl_please_contact_support'] . " " . $CONFIG['Email'];
            die($message);
        }
    } else {
        $status = 'not_exists';
        // (remote id doesn't exist)
    }

    $sslconfigurationlink = "configuressl.php?cert=" . md5($sslorderid);
    
    
    $revokeManager = _namecheapssl_getRevokeManager($params);
    $showRevokeButton = $revokeManager -> showButton();
    
    
    $pagearray = array(
        'templatefile' => 'viewdetails',
        //'breadcrumb' => ' > Certificate Details',
        'breadcrumb' => ' > ' . $_LANG['ncssl_certificate_details'],
        'vars' => array(
            'status' => htmlspecialchars($status),
            'renewal' => $renewal,
            'type' => htmlspecialchars($type),
            'approverEmail' => htmlspecialchars($approverEmail),
            'domain' => htmlspecialchars($domain),
            'serviceid' => (int)$params['serviceid'],
            'configlink' => $sslconfigurationlink,
            'confighash' => md5($sslorderid),
            'adminEmail' => htmlspecialchars($adminEmail),
            
            'httpBasedValidation' => !empty($fileName),
            'showRevokeButton' => $showRevokeButton,
            
            'sansCount' => $sansCount,
            'sans'=>join(',',$sans)
        )
    );
    
    namecheapssl_log('client.viewDetails', 'client_view_details', null, $params['serviceid']);

    return $pagearray;
}

function namecheapssl_show_validation_file_contents($params){
    
    global $_LANG;
    namecheapssl_initlang();
    
    $sql =  " SELECT mod_namecheapssl.file_name, mod_namecheapssl.file_content FROM ".
            " mod_namecheapssl JOIN tblsslorders USING (id) WHERE tblsslorders.serviceid='" . (int)$params['serviceid'] . "'";
    
    $row = NcSql::sql2row($sql);
    
    $phrase = $_LANG['ncssl_custom_phrase_sslconfigcompletedetails'];
    $phrase = str_replace('%filename%', $row['file_name'], $phrase);
    $phrase = str_replace('%contents%', nl2br($row['file_content']), $phrase);
    
    $pagearray = array(
        'templatefile' => 'show_validation_file_contents',
        'vars' => array(
            'phrase'=> htmlspecialchars($phrase),
            'serviceid' => (int)$params['serviceid']
        )
    );
    return $pagearray;
    
}


function namecheapssl_download($params){
    
    $sql = " SELECT tblsslorders.remoteid,tblsslorders.configdata FROM ".
           " mod_namecheapssl JOIN tblsslorders USING (id) WHERE tblsslorders.serviceid='" . (int)$params['serviceid'] ."'";
    $row = NcSql::sql2row($sql);
    
    $remoteid = $row['remoteid'];
    
    $configDataString = $row['configdata'];
    
    $individualFormat = true;
    if(!empty($configDataString)){
        $configData = unserialize($configDataString);
        
        #### $configData['servertype'] = '1013';
        
        $MSServerTypes = array('1013','1014');
        if(!empty($configData['servertype']) && in_array($configData['servertype'],$MSServerTypes)){
            $individualFormat = false;
        }
    }
    
    
    try{
        $api = _namecheapssl_initApi($params);
        $request_params = array(
            'CertificateID' => $remoteid,
            'Returncertificate' => 'true',
            'Returntype' => $individualFormat ? 'individual' : 'PKCS7'
        );
        $response = $api->request("namecheap.ssl.getInfo", $request_params);
        $result = $api->parseResponse($response);
        
        namecheapssl_log('client.downloadCertificate', 'client_downloaded_certificate', $individualFormat ? 'individual' : 'PKCS7' , $params['serviceid']);
        
    }  catch (Exception $e){
        return $e->getMessage();
    }
    
    $commonName = $result['SSLGetInfoResult']['CertificateDetails']['CommonName'];
    
    if($individualFormat){
        // main file
        $rootContent = $result['SSLGetInfoResult']['CertificateDetails']['Certificates']['Certificate'];
        //ca-bundle
        $caBundleContent = '';
        if(!empty($result['SSLGetInfoResult']['CertificateDetails']['Certificates']['CaCertificates'])){
            $caBundleInfo = $result['SSLGetInfoResult']['CertificateDetails']['Certificates']['CaCertificates']['Certificate'];
            if(!is_array($caBundleInfo)){$caBundleInfo=array($caBundleInfo);}
            foreach($caBundleInfo as $item){
               $caBundleContent .= $item['Certificate'] . "\n";
            }
            $caBundleContent = trim($caBundleContent,"\n ");
        }
        
        // create zip archive 
        $temp_file = tempnam(sys_get_temp_dir(), 'cert.zip');
        $zip = new ZipArchive;
        $resource = $zip->open($temp_file, ZipArchive::CREATE);
        $zip->addFromString(str_replace('.','_',$commonName) . '.crt', $rootContent);
        if(!empty($caBundleContent)){
            $zip->addFromString(str_replace('.','_',$commonName) . '.ca-bundle', $caBundleContent);
        }
        $zip->close();
        
        header('Content-Type: application/zip');
        header('Content-Length: ' . filesize($temp_file));
        header('Content-Disposition: attachment; filename="' . str_replace('.','_',$commonName) . '.zip"');
        readfile($temp_file);
        unlink($temp_file);
        exit();
        
    }else{
        
        // ms format
        header('Content-Type: application/x-pkcs7-certificates');
        header('Content-Length: ' . strlen($result['SSLGetInfoResult']['CertificateDetails']['Certificates']['Certificate']));
        header('Content-Disposition: attachment; filename="' . str_replace('.','_',$commonName) . '.p7b"');
        echo $result['SSLGetInfoResult']['CertificateDetails']['Certificates']['Certificate'];
        exit();
        
    }
    
}

function namecheapssl_resendcert($params) {

    global $CONFIG, $_LANG;
    namecheapssl_initlang();

    $_fields = namecheapssl_getModuleConfigFields();
    $_webServerTypes = namecheapssl_getWebServerTypes();

    $sql = "SELECT remoteid FROM tblsslorders WHERE serviceid='" . (int)$params['serviceid'] . "'";    
    $data = NcSql::sql2row($sql);
    
    $certID = $data['remoteid'];

    try {

        $request_params = array('CertificateID' => $certID);
        $api = _namecheapssl_initApi($params);
        $response = $api->request("namecheap.ssl.resendfulfillmentemail", $request_params);
        $result = $api->parseResponse($response);

        namecheapssl_log('client.resendCert', 'client_resend_cert', null, $params['serviceid']);
    } catch (Exception $e) {

        $message = $_LANG['ncssl_error_occured'] . $e->getMessage();
        $message .= $_LANG['ncssl_please_contact_support'] . " " . $CONFIG['Email'] . "<br />";
        $message .= namecheapssl_ClientArea($params);
        echo $message;
        exit();
    }

    return "success";
}

function namecheapssl_clientstartreissue($params) {

    global $CONFIG, $_LANG;

    
    $sql = "SELECT * FROM tblsslorders WHERE serviceid='".(int)$params['serviceid']."'";
    $row = NcSql::sql2row($sql);


    if (!$row) {
        return false;
    }


    $aSertificateInfo = _namecheapssl_getCertificateInfo($params, $row['remoteid']);
    $sProviderName = $aSertificateInfo["SSLGetInfoResult"]["Provider"]["Name"];


    $aConfigData = unserialize($row['configdata']);
    $sEmail = $aConfigData['email'];
    $sCommonName = $aSertificateInfo["SSLGetInfoResult"]["CertificateDetails"]["CommonName"];


    // reissue certificate via api
    $sql = "UPDATE mod_namecheapssl SET reissue=1 WHERE certificate_id='".(int)$row['remoteid']."'";
    NcSql::q($sql);

    $status = _namecheapssl_getIncompleteStatus();
    
    $sql = "UPDATE tblsslorders SET status='".NcSql::e($status)."' WHERE id='".(int)$row['id']."'";
    NcSql::q($sql);
    
    
    $sslconfigurationlink = $CONFIG["SystemURL"] . "/configuressl.php?cert=" . md5($row['id']);

    namecheapssl_log('client.initReissue', 'client_init_reissue_success', array($sProviderName, $row['remoteid']), $params['serviceid']);

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $sslconfigurationlink");
    exit();


    /*$sLink = _get_provider_reissue_link($sProviderName);
    namecheapssl_log('client.initReissue', 'client_init_reissue_link', array($sProviderName, $row['remoteid'], $sLink), $params['serviceid']);

    if (false != $sLink) {
        namecheapssl_initlang();
        $pagearray = array(
            'templatefile' => 'reissue_notice',
            'vars' => array(
                'link' => $sLink,
                'common_name' => $sCommonName
            )
        );
        return $pagearray;
    }

    return false;*/
    
}

function _namecheapssl_getCertificateInfo($params, $remoteid) {

    // get certificate info
    try {
        $request_params = array('CertificateID' => $remoteid);
        $api = _namecheapssl_initApi($params);
        $response = $api->request("namecheap.ssl.getInfo", $request_params);
        $result = $api->parseResponse($response);
        return $result;
    } catch (Exception $e) {
        return false;
        //return $e->getMessage();
    }
}

function _get_provider_reissue_link($sProviderName) {

    switch ($sProviderName) {
        case 'GEOTRUST':
            $sLink = 'https://products.geotrust.com/orders/orderinformation/authentication.do';
            break;
        case 'VERISIGN':
            $sLink = 'https://products.verisign.com/orders/orderinformation/authentication.do';
            break;
        case 'THAWTE':
            $sLink = 'https://products.thawte.com/orders/orderinformation/authentication.do';
            break;
        default:
            $sLink = false;
            break;
    }
    return $sLink;
}

function namecheapssl_save_debug_info($message, $command, $isResponse = false, $parentid = 0) {

    if (!empty($_SESSION['adminid'])) {
        $userid = $_SESSION["adminid"];
        $sql = "SELECT username FROM tbladmins WHERE id='".(int)$userid."'";
        $username = NcSql::sql2cell($sql);
    } else {
        $userid = $_SESSION['uid'];
        $username = 'client';
    }

    $debug = $isResponse ? 2 : 1;
    
    $sql = "INSERT INTO mod_namecheapssl_log SET date=NOW(), debug='".(int)$debug."', action='" . NcSql::e($command) . "', description='".  NcSql::e($message) . "', ipaddr='".NcSql::e($_SERVER['REMOTE_ADDR'])."', userid='".(int)$userid."', user='".NcSql::e($username)."', parentid='".(int)$parentid."'";
    NcSql::q($sql);
    
    
    return NcSql::insertId();
}

function namecheapssl_log($action, $messageKey, $args = null, $serviceId = 0) {

    static $_M;
    if (empty($_M)) {
        include_once dirname(__FILE__) . '/namecheaplog.php';
    }
    if (empty($_M[$messageKey])) {
        #####
        //// exit('empty log alias');
        return false;
    }

    // try to detect user
    $username = 'system';
    $userid = 0;

    if (!empty($_SESSION['adminid'])) {
        $userid = $_SESSION["adminid"];
        $sql = "SELECT username FROM tbladmins WHERE id='".(int)$userid."'";
        $username = NcSql::sql2cell($sql);
    }
    if (!empty($_SESSION['uid'])) {
        $userid = $_SESSION['uid'];
        $username = 'client';
    }

    $action = 'mod.' . $action;

    if (is_null($args)) {
        $message = $_M[$messageKey];
    } else {
        if (is_string($args)) {
            $args = array($args);
        }

        $message = vsprintf($_M[$messageKey], $args);
    }

    $sql = "INSERT INTO mod_namecheapssl_log SET date=NOW(), debug='0', action='".  NcSql::e($action) . "', serviceid='".  (int)$serviceId."', description='" . NcSql::e($message) . "', ipaddr='".  NcSql::e($_SERVER['REMOTE_ADDR'])."', userid='".(int)$userid."', user='".  NcSql::e($username)."'";
    NcSql::q($sql);
    return NcSql::insertId();
    
}

function namecheapssl_check_install() {

    $path = dirname(__FILE__) . '/../../addons/namecheap_ssl/namecheap_ssl.php';

    if (!file_exists($path)) {
        return false;
    }

    include_once $path;

    $configarray = namecheap_ssl_config();
    $version = $configarray['version'];

    // need to activate module
    $sql = "SELECT * FROM tbladdonmodules WHERE module='namecheap_ssl' AND setting='version' AND value='" . NcSql::e($version). "'";
    return (bool)NcSql::sqlNumRows($sql);
    
}

/**
 * @author namecheap
 * @staticvar NamecheapApi $api
 * @param type $params
 * @return NamecheapApi  private module functions
 */
function _namecheapssl_initApi($params) {
    

    $_fields = namecheapssl_getModuleConfigFields();

    $testmode = isset($params['TestMode']) ? (bool) $params['TestMode'] : (bool) $params['configoption9'];
    $debugmode = isset($params['DebugMode']) ? (bool) $params['DebugMode'] : (bool) $params['configoption21'];

    if ($testmode) {
        $username = isset($params[$_fields['SandboxUsername']]) ? $params[$_fields['SandboxUsername']] : $params['configoption3'];
        $password = isset($params[$_fields['SandboxApiKey']]) ? $params[$_fields['SandboxApiKey']] : $params['configoption4'];
    } else {
        $username = isset($params[$_fields['Username']]) ? $params[$_fields['Username']] : $params['configoption1'];
        $password = isset($params[$_fields['ApiKey']]) ? $params[$_fields['ApiKey']] : $params['configoption2'];
    }


    $api = new NamecheapApi($username, $password, $testmode, $debugmode);
    return $api;
}


function _namecheapssl_getCertificateInfoFromList($params, $certificateId) {

    $api = _namecheapssl_initApi($params);

    try {
        $requestParams = array("Page" => 1, "PageSize" => 100);
        do {
            $response = $api->request("namecheap.ssl.getList", $requestParams);
            $result = $api->parseResponse($response);

            if (isset($result['SSLListResult']['SSL']['@attributes'])) {
                $result['SSLListResult']['SSL'][0] = $result['SSLListResult']['SSL'];
            }

            foreach ($result['SSLListResult']['SSL'] as $certInfo) {

                // !! it's now working $certInfo['CertificateID']['@attributes'] (1)
                if ($certInfo['CertificateID']['@attributes'] == $certificateId) {
                    break 2;
                }
            }

            $totalPages = ceil($result['Paging']['TotalItems'] / $result['Paging']['PageSize']);
            $currentPage = $result['Paging']['CurrentPage'];
            if ($currentPage >= $totalPages) {
                break;
            }

            $requestParams['Page']++;
        } while (1);
    } catch (Exception $e) {
        return $e->getMessage();
    }


    return $certInfo['@attributes'];
}


function _namecheapssl_searchCertificateInfoInList($params, $searchParams) {
    
    $api = _namecheapssl_initApi($params);
    
    $searchResult = array();
    
    try {
        $requestParams = array("Page" => 1, "PageSize" => 100);
        
        do {
            $response = $api->request("namecheap.ssl.getList", $requestParams);
            $result = $api->parseResponse($response);

            if (isset($result['SSLListResult']['SSL']['@attributes'])) {
                $result['SSLListResult']['SSL'][0] = $result['SSLListResult']['SSL'];
            }

            foreach ($result['SSLListResult']['SSL'] as $certInfo) {
                
                $bAddItem = true;
                foreach($searchParams as $key=>$value){
                    if(empty($certInfo['@attributes'][$key]) || $certInfo['@attributes'][$key]!=$value){
                        $bAddItem = false;
                        break;
                    }
                }
                
                if($bAddItem){
                    $searchResult[] = $certInfo['@attributes'];
                }
                
            }

            $totalPages = ceil($result['Paging']['TotalItems'] / $result['Paging']['PageSize']);
            $currentPage = $result['Paging']['CurrentPage'];
            if ($currentPage >= $totalPages) {
                break;
            }

            $requestParams['Page']++;
        } while (1);
        
    } catch (Exception $e) {
        return false;
    }
    
    
    return $searchResult;
    
}

function _namecheapssl_getIncompleteStatus(){
    
    global $CONFIG;
    
    $status = 'Incomplete';  
    if (version_compare("4.5.2", $CONFIG['Version'], "<=")) {
        $status = "Awaiting Configuration";
    }
    return $status;
    
}


function _namecheapssl_getLocalCertInfo($serviceId){
    return new NcLocalCertInfo($serviceId);
}


function _namecheapssl_getRevokeManager($params){
    $localCertInfo = new NcLocalCertInfo($params['serviceid']);
    return new NcRevokeManager($localCertInfo,$params);
}


function _namecheapssl_replaceLangVariable($key, $value){
    
    global $smarty;
    $LANG = $smarty->get_template_vars('LANG');
    $LANG[$key] = $value;
    $smarty->assign('LANG', $LANG);
    
}


?>
