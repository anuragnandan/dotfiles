Vim�UnDo� ��\���Q�)�\����wral�xj���   m           I      $       $   $   $    W�MT    _�                     7   s    ����                                                                                                                                                                                                                                                                                                                                                             W�>B     �               m   <?php       /**    *    *Order Import file generator    *16150000/16150030 -Beachbody    *@author - Anurag Raperthy    */   $envLong = 'Staging';   .if(ucfirst(substr(php_uname('n'),2,1)) == 'P')       $envLong = 'Production';   $Account = '16150000';   $Subaccount = '16150030';   Urequire_once(dirname(__FILE__)."/../../../../WG5/framework/wg5_utilities.class.php");   =$databaseINI = parse_ini_file('/etc/atmosphere/general.ini');   //var_dump($databaseINI);   *$database_host =     $databaseINI['host'];   *$database_user =     $databaseINI['user'];   *$database_password = $databaseINI['pass'];   j$logFileName = dirname(__FILE__)."/../../../../logs/$Account/$Subaccount/OrderResponseReconciliation.log";   %$wg5UtilityObj = new wg5_utilities();   $orders = array();   >$fileDropPath = "/CustomerFTP/$Account/$Subaccount/$envLong/";   G/*Connect to atmosphere_schemas to get the IVR DB connection parameters    */       N$mysql_conn = mysql_connect($database_host,$database_user,$database_password);   if(!$mysql_conn)   {   \    $wg5UtilityObj->debug(__FILE__." Cannot connect to $database_host",ALERT, $logFileName);   5    die(__FILE__."Cannot connect to $database_host");   }   X$sql = "SELECT * FROM atm_globals.atmosphere_schemas WHERE connection_type = 'GEN_IVR'";   ($result = mysql_query($sql,$mysql_conn);   if($result === FALSE)   {   �     $wg5UtilityObj->debug(__FILE__."**************************ERROR: Cannot Get MYSQL host parameters***********************************************", ALERT, $logFileName);   `     $wg5UtilityObj->debug(__FILE__.": Result of query [$sql] = [$result]", ALERT,$logFileName);   +	die("Result of query [$sql] = [$result]");   }   (while($row = mysql_fetch_assoc($result))   {   A    $db_conn[$row['connection_type']]['host'] = $row['hostname'];   A    $db_conn[$row['connection_type']]['user'] = $row['username'];   E    $db_conn[$row['connection_type']]['password'] = $row['password'];       }       {$mysql_conn_ivr = mysql_connect($db_conn['GEN_IVR']['host'], $db_conn['GEN_IVR']['user'], $db_conn['GEN_IVR']['password']);   if(!$mysql_conn_ivr)   {   j    $wg5UtilityObj->debug(__FILE__." Cannot connect to ".$db_conn['GEN_IVR']['host'],ALERT, $logFileName);   C    die(__FILE__."Cannot connect to ".$db_conn['GEN_IVR']['host']);   }   �$sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',a.dispo) AS dispo,   d                            IF(a.totrans = '0', 'pending transcription', a.dispo_type) AS dispo_type   0                     FROM boc1615.16150030flow a   7                     WHERE a.order_response_recon = 0";       F$result_call_details = mysql_query($sql_call_details,$mysql_conn_ivr);   "if($result_call_details === FALSE)   {   �     $wg5UtilityObj->debug(__FILE__."**************************ERROR: Query $sql_call_details failed***********************************************", ALERT, $logFileName);   }     $wg5UtilityObj->debug(__FILE__.": Result of query [$sql_call_details] = [$result_call_detailsall]", ALERT,$logFileName);   4     die(__FILE__."Query $sql_call_details failed");   }   Bwhile($row_call_details = mysql_fetch_assoc($result_call_details))   {   !   $orders[] = $row_call_details;   }   if(count($orders) == 0)   	exit();       $output_string = '';   $uniqueids = array();   foreach($orders as $order)   {     if($order['totrans'] != '0')   .    $uniqueids[] = "'".$order['uniqueid']."'";   "  $output_string .= "Advantone,";    �  $output_string .= "ADV_".$order['uniqueid'] . "," . "Inbound," . $order['dispo'] . "," . $order['dispo_type'] . "," . $order['call_date'] . "," . $order['call_time'] . "\n";   }       <$extractFileName = "ADVANTONE_RD_RECON_".date('Ymd').".csv";   )$tempFileName = "/tmp/".$extractFileName;   +$filePath = $fileDropPath.$extractFileName;   :$fh = fopen($tempFileName, 'w') or die("can't open file");   fputs($fh, $output_string);   fclose($fh);       %if (rename($tempFileName, $filePath))   �   $wg5UtilityObj->debug(__FILE__." | ".date("y-m-d h:i:s")." | ".getmypid().": successfully moved tmp file '$tempFileName' to final location '$filePath' ", DEBUG, $logFileName);   else   �   $wg5UtilityObj->debug(__FILE__." | ".date("Y-m-d H:i:s")." | ". getmypid().": Failure moving tmp file '$tempFileName' to final location '$filePath'!! ", ALERT, $logFileName);       if(count($uniqueids) != 0)   {   {	$sql_update = "UPDATE boc1615.16150030flow SET order_response_recon = 1 WHERE uniqueid IN (".implode(',', $uniqueids).")";   <	$result_update = mysql_query($sql_update, $mysql_conn_ivr);   	if($result_update === FALSE)   	{   �	   $wg5UtilityObj->debug(__FILE__." | ".date("Y-m-d H:i:s")." | ". getmypid().": Failure while updating order_conf status after emailing. Update them manually with query [$sql_update]", ALERT, $logFileName);   	}   	else   	{   �	   $wg5UtilityObj->debug(__FILE__." | ".date("Y-m-d H:i:s")." | ". getmypid().": Updated records with UIDS".implode(',',$uniqueids), DEBUG, $logFileName);   	}   }   ?>5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m      �$sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m      �$sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo=''a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m      �$sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER'a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m      �$sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like ''a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m      �$sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%%'a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m      �$sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%'a.dispo) AS dispo,5�_�      	              7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m      �$sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%',''a.dispo) AS dispo,5�_�      
           	   7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m      �$sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','',a.dispo) AS dispo,5�_�   	              
   7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','Other Inquiry',a.dispo) AS dispo,5�_�   
                 7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','Other INquiry',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','Other INQuiry',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','Other INQUiry',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','Other INQUIry',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','Other INQUIRy',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','Other INQUIRY',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','ther INQUIRY',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     
$sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','her INQUIRY',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     	$sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','er INQUIRY',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','r INQUIRY',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�D�     �   6   8   m     $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%',' INQUIRY',a.dispo) AS dispo,5�_�                    7   �    ����                                                                                                                                                                                                                                                                                                                                                             W�E}     �   6   8   m     $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','INQUIRY',a.dispo) AS dispo,5�_�                    8   I    ����                                                                                                                                                                                                                                                                                                                                                             W�L     �   7   9   m      d                            IF(a.totrans = '0', 'pending transcription', a.dispo_type) AS dispo_type5�_�                    8   L    ����                                                                                                                                                                                                                                                                                                                                                             W�L     �   7   9   m      g                            IF(a.totrans = '0', 'pending transcription', if(a.dispo_type) AS dispo_type5�_�                    8   W    ����                                                                                                                                                                                                                                                                                                                                                             W�L     �   7   9   m      s                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = ''a.dispo_type) AS dispo_type5�_�                    8   ]    ����                                                                                                                                                                                                                                                                                                                                                             W�L     �   7   9   m      x                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = 'ORDER'a.dispo_type) AS dispo_type5�_�                    8   t    ����                                                                                                                                                                                                                                                                                                                                                             W�L$     �   7   9   m      �                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = 'ORDER' and a.Valid not like '' a.dispo_type) AS dispo_type5�_�                    8   u    ����                                                                                                                                                                                                                                                                                                                                                             W�L3     �   7   9   m      �                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = 'ORDER' and a.Valid not like '%%' a.dispo_type) AS dispo_type5�_�                    8   w    ����                                                                                                                                                                                                                                                                                                                                                             W�LC     �   7   9   m      �                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = 'ORDER' and a.Valid not like '%  %' a.dispo_type) AS dispo_type5�_�                    8   |    ����                                                                                                                                                                                                                                                                                                                                                             W�LH     �   7   9   m      �                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = 'ORDER' and a.Valid not like '%Valid%' a.dispo_type) AS dispo_type5�_�                     8   ~    ����                                                                                                                                                                                                                                                                                                                                                             W�LJ     �   7   9   m      �                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = 'ORDER' and a.Valid not like '%Valid%','' a.dispo_type) AS dispo_type5�_�      !               8   ~    ����                                                                                                                                                                                                                                                                                                                                                             W�LS     �   7   9   m      �                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = 'ORDER' and a.Valid not like '%Valid%','' a.dispo_type) AS dispo_type5�_�       "           !   8   �    ����                                                                                                                                                                                                                                                                                                                                                             W�L]     �   7   9   m      �                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = 'ORDER' and a.Valid not like '%Valid%','Other Inquiry' a.dispo_type) AS dispo_type5�_�   !   #           "   8   �    ����                                                                                                                                                                                                                                                                                                                                                             W�L_     �   7   9   m      �                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = 'ORDER' and a.Valid not like '%Valid%','Other Inquiry', a.dispo_type) AS dispo_type5�_�   "   $           #   8   �    ����                                                                                                                                                                                                                                                                                                                                                             W�La    �   7   9   m      �                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = 'ORDER' and a.Valid not like '%Valid%','Other Inquiry', a.dispo_type) AS dispo_type5�_�   #               $   8   �    ����                                                                                                                                                                                                                                                                                                                                                             W�MS    �               m   <?php       /**    *    *Order Import file generator    *16150000/16150030 -Beachbody    *@author - Anurag Raperthy    */   $envLong = 'Staging';   .if(ucfirst(substr(php_uname('n'),2,1)) == 'P')       $envLong = 'Production';   $Account = '16150000';   $Subaccount = '16150030';   Urequire_once(dirname(__FILE__)."/../../../../WG5/framework/wg5_utilities.class.php");   =$databaseINI = parse_ini_file('/etc/atmosphere/general.ini');   //var_dump($databaseINI);   *$database_host =     $databaseINI['host'];   *$database_user =     $databaseINI['user'];   *$database_password = $databaseINI['pass'];   j$logFileName = dirname(__FILE__)."/../../../../logs/$Account/$Subaccount/OrderResponseReconciliation.log";   %$wg5UtilityObj = new wg5_utilities();   $orders = array();   >$fileDropPath = "/CustomerFTP/$Account/$Subaccount/$envLong/";   G/*Connect to atmosphere_schemas to get the IVR DB connection parameters    */       N$mysql_conn = mysql_connect($database_host,$database_user,$database_password);   if(!$mysql_conn)   {   \    $wg5UtilityObj->debug(__FILE__." Cannot connect to $database_host",ALERT, $logFileName);   5    die(__FILE__."Cannot connect to $database_host");   }   X$sql = "SELECT * FROM atm_globals.atmosphere_schemas WHERE connection_type = 'GEN_IVR'";   ($result = mysql_query($sql,$mysql_conn);   if($result === FALSE)   {   �     $wg5UtilityObj->debug(__FILE__."**************************ERROR: Cannot Get MYSQL host parameters***********************************************", ALERT, $logFileName);   `     $wg5UtilityObj->debug(__FILE__.": Result of query [$sql] = [$result]", ALERT,$logFileName);   +	die("Result of query [$sql] = [$result]");   }   (while($row = mysql_fetch_assoc($result))   {   A    $db_conn[$row['connection_type']]['host'] = $row['hostname'];   A    $db_conn[$row['connection_type']]['user'] = $row['username'];   E    $db_conn[$row['connection_type']]['password'] = $row['password'];       }       {$mysql_conn_ivr = mysql_connect($db_conn['GEN_IVR']['host'], $db_conn['GEN_IVR']['user'], $db_conn['GEN_IVR']['password']);   if(!$mysql_conn_ivr)   {   j    $wg5UtilityObj->debug(__FILE__." Cannot connect to ".$db_conn['GEN_IVR']['host'],ALERT, $logFileName);   C    die(__FILE__."Cannot connect to ".$db_conn['GEN_IVR']['host']);   }  $sql_call_details = "SELECT a.uniqueid, a.totrans, DATE_FORMAT(a.startdate, '%m/%d/%Y') AS call_date, DATE_FORMAT(a.startdate, '%H:%i:%s') AS call_time, IF(a.totrans = '0', 'Other',if(a.dispo='ORDER' and a.Valid is not like '%Valid%','INQUIRY',a.dispo)) AS dispo,   �                            IF(a.totrans = '0', 'pending transcription', if(a.dispo = 'ORDER' and a.Valid not like '%Valid%','Other Inquiry', a.dispo_type)) AS dispo_type   0                     FROM boc1615.16150030flow a   7                     WHERE a.order_response_recon = 0";       F$result_call_details = mysql_query($sql_call_details,$mysql_conn_ivr);   "if($result_call_details === FALSE)   {   �     $wg5UtilityObj->debug(__FILE__."**************************ERROR: Query $sql_call_details failed***********************************************", ALERT, $logFileName);   }     $wg5UtilityObj->debug(__FILE__.": Result of query [$sql_call_details] = [$result_call_detailsall]", ALERT,$logFileName);   4     die(__FILE__."Query $sql_call_details failed");   }   Bwhile($row_call_details = mysql_fetch_assoc($result_call_details))   {   !   $orders[] = $row_call_details;   }   if(count($orders) == 0)   	exit();       $output_string = '';   $uniqueids = array();   foreach($orders as $order)   {     if($order['totrans'] != '0')   .    $uniqueids[] = "'".$order['uniqueid']."'";   "  $output_string .= "Advantone,";    �  $output_string .= "ADV_".$order['uniqueid'] . "," . "Inbound," . $order['dispo'] . "," . $order['dispo_type'] . "," . $order['call_date'] . "," . $order['call_time'] . "\n";   }       <$extractFileName = "ADVANTONE_RD_RECON_".date('Ymd').".csv";   )$tempFileName = "/tmp/".$extractFileName;   +$filePath = $fileDropPath.$extractFileName;   :$fh = fopen($tempFileName, 'w') or die("can't open file");   fputs($fh, $output_string);   fclose($fh);       %if (rename($tempFileName, $filePath))   �   $wg5UtilityObj->debug(__FILE__." | ".date("y-m-d h:i:s")." | ".getmypid().": successfully moved tmp file '$tempFileName' to final location '$filePath' ", DEBUG, $logFileName);   else   �   $wg5UtilityObj->debug(__FILE__." | ".date("Y-m-d H:i:s")." | ". getmypid().": Failure moving tmp file '$tempFileName' to final location '$filePath'!! ", ALERT, $logFileName);       if(count($uniqueids) != 0)   {   {	$sql_update = "UPDATE boc1615.16150030flow SET order_response_recon = 1 WHERE uniqueid IN (".implode(',', $uniqueids).")";   <	$result_update = mysql_query($sql_update, $mysql_conn_ivr);   	if($result_update === FALSE)   	{   �	   $wg5UtilityObj->debug(__FILE__." | ".date("Y-m-d H:i:s")." | ". getmypid().": Failure while updating order_conf status after emailing. Update them manually with query [$sql_update]", ALERT, $logFileName);   	}   	else   	{   �	   $wg5UtilityObj->debug(__FILE__." | ".date("Y-m-d H:i:s")." | ". getmypid().": Updated records with UIDS".implode(',',$uniqueids), DEBUG, $logFileName);   	}   }   ?>5��