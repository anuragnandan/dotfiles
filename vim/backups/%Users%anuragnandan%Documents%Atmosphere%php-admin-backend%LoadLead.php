Vim�UnDo� GE[�hP�	5>�� z�,8H͑L+��#h��LZU  u   '        if (substr($USarea,0,2) =='61')  �   &                       W��-    _�                    �   	    ����                                                                                                                                                                                                                                                                                                                                                             W�ѷ     �              u   <?php   &    require("../../prosodie_def.php");   	require("Datasource.php");   	   	$error = "";   	   K	$LOGFILE="/var/log/atmosphere_webservices_LoadLead-".date("Y-m-d").".log";   	$debug=true;   	$app = "LoadLead";   +	mylog("Web Hit:".$_SERVER['REQUEST_URI']);   	   '	if (!isset($_REQUEST["phone_number"]))   $		$error .= "Missing phone_number ";   &	if (!isset($_REQUEST["external_id"]))   #		$error .= "Missing external_id ";   "	if (!isset($_REQUEST["list_id"]))   		$error .= "Missing list_id ";   '	if (!isset($_REQUEST["security_key"]))   $		$error .= "Missing security_key ";   			   	$LogString = "";   &	if(isset($_REQUEST["phone_number"])){   ;		$LogString .= " phone_number:".$_REQUEST["phone_number"];   	}   %	if(isset($_REQUEST["external_id"])){   9		$LogString .= " external_id:".$_REQUEST["external_id"];   	}   !	if(isset($_REQUEST["list_id"])){   1		$LogString .= " list_id:".$_REQUEST["list_id"];   	}   &	if(isset($_REQUEST["security_key"])){   ;		$LogString .= " security_key:".$_REQUEST["security_key"];   	}   $	if(isset($_REQUEST["phone_code"])){   7		$LogString .= " phone_code:".$_REQUEST["phone_code"];   	}   	   $	mylog("Web Info:".$app.$LogString);   		   	if ($error != ""){   		send_error($error);   		exit;   	}   	   B	$phone = preg_replace("/[^0-9]/", "", $_REQUEST["phone_number"]);   	$phone_code = "";   $	if (isset($_REQUEST['phone_code']))   (		$phone_code = $_REQUEST['phone_code'];   		   	if($phone_code != "")   	{   0		if($phone_code != "1" && $phone_code != "011")   		{   1			send_error("invalid phone code:".$phone_code);   
			return;   		}   	}   	   	if(substr($phone,0,1) == 1)   		$phone = substr($phone,1);   2	if(strlen($phone) != 10 && $phone_code != "011"){   '		mylog("Phone Error:".$app."".$phone);   -		send_error("invalid phone number:".$phone);   			return;   	}   $	$_REQUEST["phone_number"] = $phone;   	   !	$list_id = $_REQUEST["list_id"];   #	$client_id = substr($list_id,0,3);   +	$security_key = $_REQUEST["security_key"];   #	$_SESSION['user_id'] = $client_id;   			   	$conn = init();    	$conn->_connect(ATMOSPHERE_DB);   	if ($conn->errno != 0) {   		send_error($conn->err);   			return;   	}   	   )	/* check security key against account */   �	$sql = "select * from tbl_vpd_account_info where security_key = '$security_key' and clientnum = '$client_id' and is_active='1'";   	   !	$result = $conn->_execute($sql);   	if ($conn->errno != 0) {   		send_error($conn->err);   			return;   	}   	   6	if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   		$account = $row;   		$conn->_free_result($result);   	}else{   B		send_error("Invalid login information for account: $client_id");   		exit;   	}   		   	/* Get Account info */   I	$sql = "SELECT  shard_num,schema_name FROM tbl_vpd_account_info where ";    	$sql .= "clientnum=$client_id";   !	$result = $conn->_execute($sql);   	if ($conn->errno != 0) {   		send_error($conn->err);   			return;   *	}if ($row = $conn->_nextRowObj($result)){   		$shard_num = $row->shard_num;   #		$schema_name = $row->schema_name;   		$conn->_free_result($result);   	}else{   -		send_error("Account $client_id not found");   			return;   	}   	   	/* Get Shard Info */   F	$sql = "SELECT host,user_id,password FROM tbl_vpd_shard_info where ";    	$sql .= "shard_num=$shard_num";   !	$result = $conn->_execute($sql);   	if ($conn->errno != 0) {   		send_error($conn->err);   			return;   *	}if ($row = $conn->_nextRowObj($result)){   		$host = $row->host;   		$user_id = $row->user_id;   		$password = $row->password;   		$conn->_free_result($result);   	}else{   +		send_error("Shard $shard_num not found");   			return;   	}   	$conn->_close();   	$dsn = array(   		"host" => $host,   		"user" => $user_id,   		"pass" => $password,   		"schema" => $schema_name   	);   	$link = "";   	$conn = new Datasource();   	$conn->_connect($dsn);   	if ($conn->errno != 0)    	{   .		send_error($conn->err . print_r($dsn,TRUE));   			return;   	}       	checkList($list_id);   	    	$CallInfo = populateCallInfo();   	   U	$CallInfo['phone_number'] =	preg_replace("/[^0-9]/", "", $CallInfo['phone_number']);       %	if(!isset($CallInfo['phone_code'])){   		$CallInfo['phone_code'] = "";   	}else{   R		$CallInfo['phone_code'] = preg_replace("/[^0-9]/", "", $CallInfo['phone_code']);   	}   )	if (strlen($CallInfo['phone_code'])<1) {    		$CallInfo['phone_code'] = '1';   	}   	   	$ScheduleCall = false;   	   O	if(isset($_REQUEST["calldate"]) && $cdate = strtotime($_REQUEST["calldate"])){   		if(!$cdate){   5			send_error("Invalid date:".$_REQUEST["calldate"]);   
			return;   		}else{   *			$Calldate = date('Y-m-d H:i:s',$cdate);   			$ScheduleCall = true;   		}   	}   	   	   4	$USarea = substr($CallInfo['phone_number'], 0, 3);	   /	$CallInfo['entry_date'] = date("Y-m-d H:i:s");   0	$CallInfo['modify_date'] = date("Y-m-d H:i:s");   	if($ScheduleCall){   !		$CallInfo['status'] = "CBHOLD";   	}else{   		$CallInfo['status'] = "NEW";   	}   	   "	$CallInfo['user'] = "WEBSERVICE";   ,	$CallInfo['called_since_last_reset'] = "N";   d	$CallInfo['gmt_offset_now'] = lookup_gmt(isset($CallInfo['phone_code'])?$CallInfo['phone_code']:"",   
		$USarea,   2		isset($CallInfo['state'])?$CallInfo['state']:"",   >		isset($CallInfo['postal_code'])?$CallInfo['postal_code']:"",   		$CallInfo['phone_number']);       "	$Lead_ID = insertLead($CallInfo);   	   	if($ScheduleCall){   		   		   }		$sql = "insert into vicidial_callbacks (lead_id,list_id,campaign_id,status,entry_time,callback_time,user,recipient) VALUES    �		('$Lead_ID','$list_id',(select campaign_id from vicidial_lists where list_id = '$list_id'),'ACTIVE',NOW(),'$Calldate','WEB','ANYONE')";   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   %			send_error($conn->err . "($sql)");   
			return;   		}   	}   	   	$conn->_close();   	send_response($Lead_ID);   	   	function populateCallInfo(){   		global $fields;   		global $conn;   		   9		$sql  = "select field_name from vicidial_lists_fields";   		$fields = array();   		   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   %			send_error($conn->err . "($sql)");   
			return;   		}   ;		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   			$fields[] = $row;   		}   		if(!is_bool($result)){   			mysqli_free_result($result);   		}   		   		$CallInfo = array();   		foreach($fields as $field){   /			if (isset($_REQUEST[$field['field_name']])){   V				$CallInfo[$field['field_name']] = $conn->_escape($_REQUEST[$field['field_name']]);   			}   		}   		   		return $CallInfo;   	}   	   	function init(){   0		$pos = strpos(dirname(__FILE__), "/services");   		if ($pos === false)    			$iniPath = dirname(__FILE__);   		else   1			$iniPath = substr(dirname(__FILE__), 0, $pos);   7		$iniPath = str_replace(AMFPHP, "WG5/ini/", $iniPath);   #		$conn = new Datasource($iniPath);   		return($conn);   	}   	   	function send_error($reason){   		$msg = "<response>\n";   "		$msg .= "	<status>0</status>\n";   0		$msg .= "	<error_text>$reason</error_text>\n";   		$msg .= "</response>\n";   		   		print "$msg";   		mylog($msg);   	}   		    	function insertLead($CallInfo){   		global $conn;   		   		$SQLFields = "";   		$SQLValues = "";   2		foreach($CallInfo as $field_name=>$field_value){   			if($SQLFields != ""){   0				$SQLFields = $SQLFields . "," . $field_name;   				}else{   				$SQLFields = $field_name;   			}   			   			if($SQLValues != ""){   8				$SQLValues = $SQLValues . ",'" . $field_value . "'";   				}else{   *				$SQLValues = "'" . $field_value . "'";   			}   		}   		   G		$sql  = "INSERT INTO vicidial_list ($SQLFields) VALUES ($SQLValues)";   		   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   %			send_error($conn->err . "($sql)");   			exit;   		}   		   		$ID = $conn->_insert_id();   		   A		$sql = "Update agg_lead_info set import_count = import_count+1    �		WHERE import_method = 'web_create' AND import_list = '".$CallInfo['list_id']."' AND import_date = '".date("Y-m-d")."' LIMIT 1";   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   %			send_error($conn->err . "($sql)");   			exit;   		}   		   %		$AffRows = $conn->_affected_rows();   		   		if($AffRows < 1){   �			$sql = "insert into agg_lead_info(import_count,import_method,import_list,import_date) VALUES (1,'web_create','".$CallInfo['list_id']."','".date("Y-m-d")."')";   #			$result = $conn->_execute($sql);   			if ($conn->errno != 0) {   &				send_error($conn->err . "($sql)");   					exit;   			}	   		}   			   //		$conn->errno = 0;   W//		$ret = $conn->_writeAdminLog("WEBPOST_LOADLEAD","WEBPOST_LOADLEAD","ADD",$sql,$ID);   //		if($ret != 1){   ,//			$this->returnObject->error_text = $ret;    //			return $this->returnObject;   //		}   		   		return $ID;   		   	}   	   	function checkList($list_id){   		global $conn;   		   W		$sql  = "SELECT campaign_id,list_name from vicidial_lists where list_id='$list_id';";       "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   %			send_error($conn->err . "($sql)");   			exit;   		}   		   3		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   		if(!$row){   -			send_error("List $list_id doesn't exist");   			exit;   		}		   		mysqli_free_result($result);   	}   	   #	function send_response($response){   		$msg = "<response>\n";   "		$msg .= "	<status>1</status>\n";   )		$msg .= "	<error_text></error_text>\n";   ,		$msg .= "	<lead_id>$response</lead_id>\n";   		$msg .= "</response>\n";   		   		print "$msg";   		mylog($msg);   	}   	   "	function mys_query($query, $link)   	{   		global $conn;   		   "		$rslt = $conn->_execute($query);   		if ($conn->errno != 0) {   			send_error($conn->err);   		}   		return $rslt;   	}   	   		function mylog($str){   !	    global $debug,$LOGFILE,$app;   	    if($debug){   #	        $fp = fopen($LOGFILE,"a");   O	        fputs($fp,date("M:j:Y-H:i:s")."-PID=".getmypid()."[$app] ".$str."\n");   	        fclose($fp);   	    }   	}   	   L	function lookup_gmt($phone_code,$USarea,$state,$postal_code,$phone_number){   	global $link;   	   	$postalgmt = "AREA";   	$Shour = date("H");   	$Smin = date("i");   	$Ssec = date("s");   	$Smon = date("m");   	$Smday = date("d");   	$Syear = date("Y");   	$SERVER_GMT = date("O");   3	$SERVER_GMT = preg_replace("/\+/","",$SERVER_GMT);   !	$SERVER_GMT = ($SERVER_GMT + 0);   #	$SERVER_GMT = ($SERVER_GMT / 100);       	$LOCAL_GMT_OFF = $SERVER_GMT;   "	$LOCAL_GMT_OFF_STD = $SERVER_GMT;   		   	$postalgmt_found=0;   G	if ( (preg_match("/POSTAL/",$postalgmt)) && (strlen($postal_code)>4) )   		{   '		if (preg_match('/^1$/', $phone_code))   			{   �			$stmt="select postal_code,state,GMT_offset,DST,DST_range,country,country_code from vicidial_postal_codes where country_code='$phone_code' and postal_code LIKE \"$postal_code%\";";   !			$rslt=mys_query($stmt, $link);   %			$pc_recs = mysqli_num_rows($rslt);   			if ($pc_recs > 0)   				{   !				$row=mysqli_fetch_row($rslt);   N				$gmt_offset =	$row[2];	 $gmt_offset = preg_replace("/\+/","",$gmt_offset);   				$dst =			$row[3];   				$dst_range =	$row[4];   				$PC_processed++;   				$postalgmt_found++;   				$post++;   				mysqli_free_result($rslt);   				}   			}   		}   	if ($postalgmt_found < 1)   		{   		$PC_processed=0;   		### UNITED STATES ###   		if ($phone_code =='1')   			{   �			$stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code' and areacode='$USarea';";   !			$rslt=mys_query($stmt, $link);   %			$pc_recs = mysqli_num_rows($rslt);   			if ($pc_recs > 0)   				{   !				$row=mysqli_fetch_row($rslt);   N				$gmt_offset =	$row[4];	 $gmt_offset = preg_replace("/\+/","",$gmt_offset);   				$dst =			$row[5];   				$dst_range =	$row[6];   				$PC_processed++;   				mysqli_free_result($rslt);   				}   			}   		### MEXICO ###   '        if (substr($USarea,0,2) =='52')                   {   6                $cc = substr($USarea,0,2);  // 2 digit   =                $mac = substr($phone_number,2,3);   //  52NNN   �                $stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$cc' and areacode='$mac';";   .                $rslt=mys_query($stmt, $link);   2                $pc_recs = mysqli_num_rows($rslt);   !                if ($pc_recs > 0)                           {   5                        $row=mysqli_fetch_row($rslt);   k                        $gmt_offset =   $row[4];         $gmt_offset = preg_replace("/\+/","",$gmt_offset);   8                        $dst =                  $row[5];   0                        $dst_range =    $row[6];   (                        $PC_processed++;   2                        mysqli_free_result($rslt);                           }                   }   		### AUSTRALIA ###   '        if (substr($USarea,0,2) =='61')                   {   6                $cc = substr($USarea,0,2);  // 2 digit   6                $ast = substr($state,0,2);  // 2 digit   �                $stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$cc' and state='$ast';";   .                $rslt=mys_query($stmt, $link);   2                $pc_recs = mysqli_num_rows($rslt);   !                if ($pc_recs > 0)                           {   5                        $row=mysqli_fetch_row($rslt);   k                        $gmt_offset =   $row[4];         $gmt_offset = preg_replace("/\+/","",$gmt_offset);   8                        $dst =                  $row[5];   0                        $dst_range =    $row[6];   (                        $PC_processed++;   2                        mysqli_free_result($rslt);                           }                   }   !		### ALL OTHER COUNTRY CODES ###   		if (!$PC_processed)   			{   5			$cc = substr($USarea,0,2);		// Start with 2 digits   �			$stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$cc';";   !			$rslt=mys_query($stmt, $link);   %			$pc_recs = mysqli_num_rows($rslt);   			if ($pc_recs > 0)   				{   !				$row=mysqli_fetch_row($rslt);   N				$gmt_offset =	$row[4];	 $gmt_offset = preg_replace("/\+/","",$gmt_offset);   				$dst =			$row[5];   				$dst_range =	$row[6];   				$PC_processed++;   				mysqli_free_result($rslt);   				}   			}   		if (!$PC_processed)   			{   '			$cc = $USarea;		// Try with 3 digits   �			$stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$cc';";   !			$rslt=mys_query($stmt, $link);   %			$pc_recs = mysqli_num_rows($rslt);   			if ($pc_recs > 0)   				{   !				$row=mysqli_fetch_row($rslt);   N				$gmt_offset =	$row[4];	 $gmt_offset = preg_replace("/\+/","",$gmt_offset);   				$dst =			$row[5];   				$dst_range =	$row[6];   				$PC_processed++;   				mysqli_free_result($rslt);   				}   			}   		}   		   0	### Find out if DST to raise the gmt offset ###   3	$AC_GMT_diff = ($gmt_offset - $LOCAL_GMT_OFF_STD);   V	$AC_localtime = mktime(($Shour + $AC_GMT_diff), $Smin, $Ssec, $Smon, $Smday, $Syear);   "		$hour = date("H",$AC_localtime);   !		$min = date("i",$AC_localtime);   !		$sec = date("s",$AC_localtime);   !		$mon = date("m",$AC_localtime);   "		$mday = date("d",$AC_localtime);   "		$wday = date("w",$AC_localtime);   "		$year = date("Y",$AC_localtime);   5	$dsec = ( ( ($hour * 3600) + ($min * 60) ) + $sec );   	   	$AC_processed=0;   6	if ( (!$AC_processed) and ($dst_range == 'SSM-FSN') )   		{   I		#**********************************************************************   		# SSM-FSN   I		#     This is returns 1 if Daylight Savings Time is in effect and 0 if    %		#       Standard time is in effect.   F		#     Based on Second Sunday March to First Sunday November at 2 am.   		#     INPUTS:   .		#       mm              INTEGER       Month.   9		#       dd              INTEGER       Day of the month.   =		#       ns              INTEGER       Seconds into the day.   M		#       dow             INTEGER       Day of week (0=Sunday, to 6=Saturday)   		#     OPTIONAL INPUT:   Q		#       timezone        INTEGER       hour difference UTC - local standard time   ;		#                                      (DEFAULT is blank)   M		#                                     make calculations based on UTC time,    O		#                                     which means shift at 10:00 UTC in April   ?		#                                     and 9:00 UTC in October   		#     OUTPUT:    <		#                       INTEGER       1 = DST, 0 = not DST   		#   		# S  M  T  W  T  F  S   		# 1  2  3  4  5  6  7   		# 8  9 10 11 12 13 14   		#15 16 17 18 19 20 21   		#22 23 24 25 26 27 28   		#29 30 31   		#    		# S  M  T  W  T  F  S   		#    1  2  3  4  5  6   		# 7  8  9 10 11 12 13   		#14 15 16 17 18 19 20   		#21 22 23 24 25 26 27   		#28 29 30 31   		#    I		#**********************************************************************   	   			$USACAN_DST=0;   			$mm = $mon;   			$dd = $mday;   			$ns = $dsec;   			$dow= $wday;   	   			if ($mm < 3 || $mm > 11) {   			$USACAN_DST=0;      &			} elseif ($mm >= 4 and $mm <= 10) {   			$USACAN_DST=1;      			} elseif ($mm == 3) {   			if ($dd > 13) {   				$USACAN_DST=1;      			} elseif ($dd >= ($dow+8)) {   				if ($timezone) {   4				if ($dow == 0 and $ns < (7200+$timezone*3600)) {   					$USACAN_DST=0;      				} else {   					$USACAN_DST=1;      				}   				} else {   #				if ($dow == 0 and $ns < 7200) {   					$USACAN_DST=0;      				} else {   					$USACAN_DST=1;      				}   				}   			} else {   				$USACAN_DST=0;      			}   			} elseif ($mm == 11) {   			if ($dd > 7) {   				$USACAN_DST=0;      			} elseif ($dd < ($dow+1)) {   				$USACAN_DST=1;      			} elseif ($dow == 0) {   '				if ($timezone) { # UTC calculations   *				if ($ns < (7200+($timezone-1)*3600)) {   					$USACAN_DST=1;      				} else {   					$USACAN_DST=0;      				}   &				} else { # local time calculations   				if ($ns < 7200) {   					$USACAN_DST=1;      				} else {   					$USACAN_DST=0;      				}   				}   			} else {   				$USACAN_DST=0;      			}   			} # end of month checks   #		if ($USACAN_DST) {$gmt_offset++;}   		$AC_processed++;   		}   	   6	if ( (!$AC_processed) and ($dst_range == 'FSA-LSO') )   		{   I		#**********************************************************************   		# FSA-LSO   I		#     This is returns 1 if Daylight Savings Time is in effect and 0 if    %		#       Standard time is in effect.   J		#     Based on first Sunday in April and last Sunday in October at 2 am.   I		#**********************************************************************   			   			$USA_DST=0;   			$mm = $mon;   			$dd = $mday;   			$ns = $dsec;   			$dow= $wday;   	   			if ($mm < 4 || $mm > 10) {   			$USA_DST=0;   %			} elseif ($mm >= 5 and $mm <= 9) {   			$USA_DST=1;   			} elseif ($mm == 4) {   			if ($dd > 7) {   				$USA_DST=1;   			} elseif ($dd >= ($dow+1)) {   				if ($timezone) {   4				if ($dow == 0 and $ns < (7200+$timezone*3600)) {   					$USA_DST=0;   				} else {   					$USA_DST=1;   				}   				} else {   #				if ($dow == 0 and $ns < 7200) {   					$USA_DST=0;   				} else {   					$USA_DST=1;   				}   				}   			} else {   				$USA_DST=0;   			}   			} elseif ($mm == 10) {   			if ($dd < 25) {   				$USA_DST=1;   			} elseif ($dd < ($dow+25)) {   				$USA_DST=1;   			} elseif ($dow == 0) {   '				if ($timezone) { # UTC calculations   *				if ($ns < (7200+($timezone-1)*3600)) {   					$USA_DST=1;   				} else {   					$USA_DST=0;   				}   &				} else { # local time calculations   				if ($ns < 7200) {   					$USA_DST=1;   				} else {   					$USA_DST=0;   				}   				}   			} else {   				$USA_DST=0;   			}   			} # end of month checks   	    		if ($USA_DST) {$gmt_offset++;}   		$AC_processed++;   		}   	   6	if ( (!$AC_processed) and ($dst_range == 'LSM-LSO') )   		{   I		#**********************************************************************   C		#     This is s 1 if Daylight Savings Time is in effect and 0 if    %		#       Standard time is in effect.   I		#     Based on last Sunday in March and last Sunday in October at 1 am.   I		#**********************************************************************   			   			$GBR_DST=0;   			$mm = $mon;   			$dd = $mday;   			$ns = $dsec;   			$dow= $wday;   	   			if ($mm < 3 || $mm > 10) {   			$GBR_DST=0;   %			} elseif ($mm >= 4 and $mm <= 9) {   			$GBR_DST=1;   			} elseif ($mm == 3) {   			if ($dd < 25) {   				$GBR_DST=0;   			} elseif ($dd < ($dow+25)) {   				$GBR_DST=0;   			} elseif ($dow == 0) {   '				if ($timezone) { # UTC calculations   *				if ($ns < (3600+($timezone-1)*3600)) {   					$GBR_DST=0;   				} else {   					$GBR_DST=1;   				}   &				} else { # local time calculations   				if ($ns < 3600) {   					$GBR_DST=0;   				} else {   					$GBR_DST=1;   				}   				}   			} else {   				$GBR_DST=1;   			}   			} elseif ($mm == 10) {   			if ($dd < 25) {   				$GBR_DST=1;   			} elseif ($dd < ($dow+25)) {   				$GBR_DST=1;   			} elseif ($dow == 0) {   '				if ($timezone) { # UTC calculations   *				if ($ns < (3600+($timezone-1)*3600)) {   					$GBR_DST=1;   				} else {   					$GBR_DST=0;   				}   &				} else { # local time calculations   				if ($ns < 3600) {   					$GBR_DST=1;   				} else {   					$GBR_DST=0;   				}   				}   			} else {   				$GBR_DST=0;   			}   			} # end of month checks    		if ($GBR_DST) {$gmt_offset++;}   		$AC_processed++;   		}   6	if ( (!$AC_processed) and ($dst_range == 'LSO-LSM') )   		{   I		#**********************************************************************   C		#     This is s 1 if Daylight Savings Time is in effect and 0 if    %		#       Standard time is in effect.   I		#     Based on last Sunday in October and last Sunday in March at 1 am.   I		#**********************************************************************   			   			$AUS_DST=0;   			$mm = $mon;   			$dd = $mday;   			$ns = $dsec;   			$dow= $wday;   	   			if ($mm < 3 || $mm > 10) {   			$AUS_DST=1;   %			} elseif ($mm >= 4 and $mm <= 9) {   			$AUS_DST=0;   			} elseif ($mm == 3) {   			if ($dd < 25) {   				$AUS_DST=1;   			} elseif ($dd < ($dow+25)) {   				$AUS_DST=1;   			} elseif ($dow == 0) {   '				if ($timezone) { # UTC calculations   *				if ($ns < (3600+($timezone-1)*3600)) {   					$AUS_DST=1;   				} else {   					$AUS_DST=0;   				}   &				} else { # local time calculations   				if ($ns < 3600) {   					$AUS_DST=1;   				} else {   					$AUS_DST=0;   				}   				}   			} else {   				$AUS_DST=0;   			}   			} elseif ($mm == 10) {   			if ($dd < 25) {   				$AUS_DST=0;   			} elseif ($dd < ($dow+25)) {   				$AUS_DST=0;   			} elseif ($dow == 0) {   '				if ($timezone) { # UTC calculations   *				if ($ns < (3600+($timezone-1)*3600)) {   					$AUS_DST=0;   				} else {   					$AUS_DST=1;   				}   &				} else { # local time calculations   				if ($ns < 3600) {   					$AUS_DST=0;   				} else {   					$AUS_DST=1;   				}   				}   			} else {   				$AUS_DST=1;   			}    			} # end of month checks						    		if ($AUS_DST) {$gmt_offset++;}   		$AC_processed++;   		}   	   6	if ( (!$AC_processed) and ($dst_range == 'FSO-LSM') )   		{   I		#**********************************************************************   		#   TASMANIA ONLY   C		#     This is s 1 if Daylight Savings Time is in effect and 0 if    %		#       Standard time is in effect.   J		#     Based on first Sunday in October and last Sunday in March at 1 am.   I		#**********************************************************************   			   			$AUST_DST=0;   			$mm = $mon;   			$dd = $mday;   			$ns = $dsec;   			$dow= $wday;   	   			if ($mm < 3 || $mm > 10) {   			$AUST_DST=1;   %			} elseif ($mm >= 4 and $mm <= 9) {   			$AUST_DST=0;   			} elseif ($mm == 3) {   			if ($dd < 25) {   				$AUST_DST=1;   			} elseif ($dd < ($dow+25)) {   				$AUST_DST=1;   			} elseif ($dow == 0) {   '				if ($timezone) { # UTC calculations   *				if ($ns < (3600+($timezone-1)*3600)) {   					$AUST_DST=1;   				} else {   					$AUST_DST=0;   				}   &				} else { # local time calculations   				if ($ns < 3600) {   					$AUST_DST=1;   				} else {   					$AUST_DST=0;   				}   				}   			} else {   				$AUST_DST=0;   			}   			} elseif ($mm == 10) {   			if ($dd > 7) {   				$AUST_DST=1;   			} elseif ($dd >= ($dow+1)) {   				if ($timezone) {   4				if ($dow == 0 and $ns < (7200+$timezone*3600)) {   					$AUST_DST=0;   				} else {   					$AUST_DST=1;   				}   				} else {   #				if ($dow == 0 and $ns < 3600) {   					$AUST_DST=0;   				} else {   					$AUST_DST=1;   				}   				}   			} else {   				$AUST_DST=0;   			}    			} # end of month checks						   !		if ($AUST_DST) {$gmt_offset++;}   		$AC_processed++;   		}   	   6	if ( (!$AC_processed) and ($dst_range == 'FSO-FSA') )   		{   I		#**********************************************************************   		# FSO-FSA   ,		#   2008+ AUSTRALIA ONLY (country code 61)   I		#     This is returns 1 if Daylight Savings Time is in effect and 0 if    %		#       Standard time is in effect.   K		#     Based on first Sunday in October and first Sunday in April at 1 am.   I		#**********************************************************************   	       		$AUSE_DST=0;   		$mm = $mon;   		$dd = $mday;   		$ns = $dsec;   		$dow= $wday;   	   	    if ($mm < 4 or $mm > 10) {   		$AUSE_DST=1;      '	    } elseif ($mm >= 5 and $mm <= 9) {   		$AUSE_DST=0;      	    } elseif ($mm == 4) {   		if ($dd > 7) {   		    $AUSE_DST=0;      		} elseif ($dd >= ($dow+1)) {   		    if ($timezone) {   3			if ($dow == 0 and $ns < (3600+$timezone*3600)) {   			    $AUSE_DST=1;      			} else {   			    $AUSE_DST=0;      			}   		    } else {   "			if ($dow == 0 and $ns < 7200) {   			    $AUSE_DST=1;      			} else {   			    $AUSE_DST=0;      			}   		    }   
		} else {   		    $AUSE_DST=1;      		}   	    } elseif ($mm == 10) {   		if ($dd >= 8) {   		    $AUSE_DST=1;      		} elseif ($dd >= ($dow+1)) {   		    if ($timezone) {   3			if ($dow == 0 and $ns < (7200+$timezone*3600)) {   			    $AUSE_DST=0;      			} else {   			    $AUSE_DST=1;      			}   		    } else {   "			if ($dow == 0 and $ns < 3600) {   			    $AUSE_DST=0;      			} else {   			    $AUSE_DST=1;      			}   		    }   
		} else {   		    $AUSE_DST=0;      		}   	    } # end of month checks   !		if ($AUSE_DST) {$gmt_offset++;}   		$AC_processed++;   		}   	   6	if ( (!$AC_processed) and ($dst_range == 'FSO-TSM') )   		{   I		#**********************************************************************   C		#     This is s 1 if Daylight Savings Time is in effect and 0 if    %		#       Standard time is in effect.   K		#     Based on first Sunday in October and third Sunday in March at 1 am.   I		#**********************************************************************   			   			$NZL_DST=0;   			$mm = $mon;   			$dd = $mday;   			$ns = $dsec;   			$dow= $wday;   	   			if ($mm < 3 || $mm > 10) {   			$NZL_DST=1;   %			} elseif ($mm >= 4 and $mm <= 9) {   			$NZL_DST=0;   			} elseif ($mm == 3) {   			if ($dd < 14) {   				$NZL_DST=1;   			} elseif ($dd < ($dow+14)) {   				$NZL_DST=1;   			} elseif ($dow == 0) {   '				if ($timezone) { # UTC calculations   *				if ($ns < (3600+($timezone-1)*3600)) {   					$NZL_DST=1;   				} else {   					$NZL_DST=0;   				}   &				} else { # local time calculations   				if ($ns < 3600) {   					$NZL_DST=1;   				} else {   					$NZL_DST=0;   				}   				}   			} else {   				$NZL_DST=0;   			}   			} elseif ($mm == 10) {   			if ($dd > 7) {   				$NZL_DST=1;   			} elseif ($dd >= ($dow+1)) {   				if ($timezone) {   4				if ($dow == 0 and $ns < (7200+$timezone*3600)) {   					$NZL_DST=0;   				} else {   					$NZL_DST=1;   				}   				} else {   #				if ($dow == 0 and $ns < 3600) {   					$NZL_DST=0;   				} else {   					$NZL_DST=1;   				}   				}   			} else {   				$NZL_DST=0;   			}    			} # end of month checks						    		if ($NZL_DST) {$gmt_offset++;}   		$AC_processed++;   		}   	   6	if ( (!$AC_processed) and ($dst_range == 'LSS-FSA') )   	{   I		#**********************************************************************   		# LSS-FSA   )		#   2007+ NEW ZEALAND (country code 64)   I		#     This is returns 1 if Daylight Savings Time is in effect and 0 if    %		#       Standard time is in effect.   L		#     Based on last Sunday in September and first Sunday in April at 1 am.   I		#**********************************************************************   	       		$NZLN_DST=0;   		$mm = $mon;   		$dd = $mday;   		$ns = $dsec;   		$dow= $wday;   	   	    if ($mm < 4 || $mm > 9) {   		$NZLN_DST=1;      &	    } elseif ($mm >= 5 && $mm <= 9) {   		$NZLN_DST=0;      	    } elseif ($mm == 4) {   		if ($dd > 7) {   		    $NZLN_DST=0;      		} elseif ($dd >= ($dow+1)) {   		    if ($timezone) {   2			if ($dow == 0 && $ns < (3600+$timezone*3600)) {   			    $NZLN_DST=1;      			} else {   			    $NZLN_DST=0;      			}   		    } else {   !			if ($dow == 0 && $ns < 7200) {   			    $NZLN_DST=1;      			} else {   			    $NZLN_DST=0;      			}   		    }   
		} else {   		    $NZLN_DST=1;      		}   	    } elseif ($mm == 9) {   		if ($dd < 25) {   		    $NZLN_DST=0;      		} elseif ($dd < ($dow+25)) {   		    $NZLN_DST=0;      		} elseif ($dow == 0) {   )		    if ($timezone) { # UTC calculations   )			if ($ns < (3600+($timezone-1)*3600)) {   			    $NZLN_DST=0;      			} else {   			    $NZLN_DST=1;      			}   (		    } else { # local time calculations   			if ($ns < 3600) {   			    $NZLN_DST=0;      			} else {   			    $NZLN_DST=1;      			}   		    }   
		} else {   		    $NZLN_DST=1;      		}   	    } # end of month checks   !		if ($NZLN_DST) {$gmt_offset++;}   		$AC_processed++;   	}   	   7		if ( (!$AC_processed) and ($dst_range == 'TSO-LSF') )   		{   J			#**********************************************************************   			# TSO-LSF   J			#     This is returns 1 if Daylight Savings Time is in effect and 0 if    -			#       Standard time is in effect. Brazil   G			#     Based on Third Sunday October to Last Sunday February at 1 am.   J			#**********************************************************************   				   				$BZL_DST=0;   				$mm = $mon;   				$dd = $mday;   				$ns = $dsec;   				$dow= $wday;   	   				if ($mm < 2 || $mm > 10) {   				$BZL_DST=1;      &				} elseif ($mm >= 3 and $mm <= 9) {   				$BZL_DST=0;      				} elseif ($mm == 2) {   				if ($dd < 22) {   					$BZL_DST=1;       				} elseif ($dd < ($dow+22)) {   					$BZL_DST=1;      				} elseif ($dow == 0) {   (					if ($timezone) { # UTC calculations   +					if ($ns < (3600+($timezone-1)*3600)) {   						$BZL_DST=1;      					} else {   						$BZL_DST=0;      					}   '					} else { # local time calculations   					if ($ns < 3600) {   						$BZL_DST=1;      					} else {   						$BZL_DST=0;      					}   					}   				} else {   					$BZL_DST=0;      				}   				} elseif ($mm == 10) {   				if ($dd < 22) {   					$BZL_DST=0;       				} elseif ($dd < ($dow+22)) {   					$BZL_DST=0;      				} elseif ($dow == 0) {   (					if ($timezone) { # UTC calculations   +					if ($ns < (3600+($timezone-1)*3600)) {   						$BZL_DST=0;      					} else {   						$BZL_DST=1;      					}   '					} else { # local time calculations   					if ($ns < 3600) {   						$BZL_DST=0;      					} else {   						$BZL_DST=1;      					}   					}   				} else {   					$BZL_DST=1;      				}   				} # end of month checks   !			if ($BZL_DST) {$gmt_offset++;}   			$AC_processed++;   		}   	   		if (!$AC_processed)   		{   			$AC_processed++;   		}   	   		return $gmt_offset;   	}   	   ?>5�_�                   �   &    ����                                                                                                                                                                                                                                                                                                                                                             W���     �  �  �  u      '        if (substr($USarea,0,2) =='52')5�_�                   �   1    ����                                                                                                                                                                                                                                                                                                                                                             W���     �  �  �  u      3        if (substr($USarea,0,2) =='52' && strlen())5�_�                   �   ?    ����                                                                                                                                                                                                                                                                                                                                                             W���     �  �  �  u      @        if (substr($USarea,0,2) =='52' && strlen($phone_number))5�_�                   �   &    ����                                                                                                                                                                                                                                                                                                                                                             W��      �  �  �  u      '        if (substr($USarea,0,2) =='61')5�_�                   �   1    ����                                                                                                                                                                                                                                                                                                                                                             W��)     �  �  �  u      3        if (substr($USarea,0,2) =='61' && strlen())5�_�                    �   ?    ����                                                                                                                                                                                                                                                                                                                                                             W��,    �  �  �  u      @        if (substr($USarea,0,2) =='61' && strlen($phone_number))5��