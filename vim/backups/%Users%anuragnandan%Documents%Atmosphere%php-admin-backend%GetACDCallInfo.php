Vim�UnDo� �$(p%�F�e.%/˹la��жXs���  [   1			$whereClause .= " and uniqueID IN ($ShortID)";   �                          X4[[    _�                     �       ����                                                                                                                                                                                                                                                                                                                                                             X4Z,     �              [   <?php   &    require("../../prosodie_def.php");   	require("Datasource.php");   	   Q	$LOGFILE="/var/log/atmosphere_webservices_GetACDCallInfo-".date("Y-m-d").".log";   	$debug=true;   	$app = "GetACDCallInfo";   	   +	mylog("Web Hit:".$_SERVER['REQUEST_URI']);   "	header("content-type: text/xml");   5	print"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";   	print "<response>\n";   		           	webStartup();       	$CallInfo = getCallInfo();   		   	$conn->_close();   	send_response($CallInfo);   	   	function webStartup(){   		global $conn;   		global $link;   		global $client_id;   		global $TryingDR;   		global $CallInfo;   		global $ShortIDCount;   		global $CallInfoIndex;   		global $dest_idSQL;   		global $app;   		   		$error = "";   		$TryingDR = false;   		   		$LogString = "";   $		if(isset($_REQUEST["Unique_id"])){   6			$LogString .= " Unique_id:".$_REQUEST["Unique_id"];   K			if(!is_numeric($_REQUEST["Unique_id"]) || $_REQUEST["Unique_id"] == ""){   *				$error .= "Unique_id must be numeric";   			}   		}   (		if(isset($_REQUEST["StartDateTime"])){   >			$LogString .= " StartDateTime:".$_REQUEST["StartDateTime"];   		}   &		if(isset($_REQUEST["EndDateTime"])){   :			$LogString .= " EndDateTime:".$_REQUEST["EndDateTime"];   		}   '		if(isset($_REQUEST["security_key"])){   <			$LogString .= " security_key:".$_REQUEST["security_key"];   		}   !		if(isset($_REQUEST["Client"])){   0			$LogString .= " Client:".$_REQUEST["Client"];   		}   		   %		mylog("Web Info:".$app.$LogString);   		   &		if (!isset($_REQUEST["Unique_ID"])){   *			if(!isset($_REQUEST["StartDateTime"])){   '				$error .= "Missing StartDateTime ";   			}   (			if(!isset($_REQUEST["EndDateTime"])){   %				$error .= "Missing EndDateTime ";   			}   			if ($error != ""){   &				$error .= "or Missing Unique_ID ";   			}   		}   (		if (!isset($_REQUEST["security_key"]))   %			$error .= "Missing security_key ";   			   		if ($error != ""){   			send_error($error);   			exit;   		}   			   		$conn = init();   %		$conn->_connect_by_type('PRIMARY');   		if ($conn->errno != 0) {   "			send_error("DataBase Error:1");   
			return;   		}   				   		$validKey = false;   		   <		$security_key = $conn->_string($_REQUEST["security_key"]);   		   *		/* check security key against account */   q		$sql = "select * from atm_globals.tbl_vpd_account_info where security_key = '$security_key' and is_active='1'";   		   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   "			send_error("DataBase Error:2");   
			return;   		}   		   7		if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   "			$client_id = $row['clientnum'];    			$conn->_free_result($result);   			$validKey = true;   		}   		   		/* Get Account info */   		if($validKey){   V			$sql = "SELECT shard_num,schema_name FROM atm_globals.tbl_vpd_account_info where ";   "			$sql .= "clientnum=$client_id";   		}else{   W			$sql = "SELECT shard_num,schema_name FROM atm_globals.tbl_vpd_account_info limit 1";   		}   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   "			send_error("DataBase Error:3");   
			return;   +		}if ($row = $conn->_nextRowObj($result)){    			$shard_num = $row->shard_num;   $			$schema_name = $row->schema_name;    			$conn->_free_result($result);   		}else{   .			send_error("Account $client_id not found");   
			return;   		}   		   		$conn = new Datasource();   1		$conn->_connect_by_type('PRIMARY', $client_id);   		if ($conn->errno != 0)    		{   "			send_error("DataBase Error:5");   
			return;   		}   		   		if(!$validKey){   A			send_error("No Account found for security key=$security_key");   			exit;   		}   		   		   	}   	   	function init(){   0		$pos = strpos(dirname(__FILE__), "/services");   		if ($pos === false)    			$iniPath = dirname(__FILE__);   		else   1			$iniPath = substr(dirname(__FILE__), 0, $pos);   7		$iniPath = str_replace(AMFPHP, "WG5/ini/", $iniPath);   #		$conn = new Datasource($iniPath);   		return($conn);   	}   		   	function getCallInfo(){   		global $conn;   		global $client_id;   		global $Recording_URL;   		global $ShortIDCount;   		global $CallInfoIndex;   		global $dest_idSQL;   		   		$whereClause = "";   "		if(isset($_REQUEST["Gate_ID"])){   Y			$whereClause .= " and callflow.group_id = '".$conn->_string($_REQUEST["Gate_ID"])."'";   		}   		$ShortIDCount = 0;   		   $		if(isset($_REQUEST["Unique_ID"])){   G			$ShortIDArray = explode(",",$conn->_string($_REQUEST["Unique_ID"]));   			$ShortID = "";   			   4			for($cnt = 0;$cnt < count($ShortIDArray);$cnt++){   				if($ShortID != ""){   					$ShortID .= ",";   				}   (				$ShortID .= "'$ShortIDArray[$cnt]'";   			}   			   			$ShortIDCount = $cnt;   			   1			$whereClause .= " and uniqueID IN ($ShortID)";   		}   		   (		if(isset($_REQUEST["StartDateTime"])){   X			$whereClause .= " and starttime >= '".$conn->_string($_REQUEST["StartDateTime"])."'";   		}   		   &		if(isset($_REQUEST["EndDateTime"])){   V			$whereClause .= " and starttime <= '".$conn->_string($_REQUEST["EndDateTime"])."'";   		}   		   a    $sql = "Select callflow.*,user.full_name as agent_name,user.user as agent_id,prof.group_name,   j                   transfer.answered_time as ag_transfer_duration, transfer.call_date as ag_transfer_time,   >                   transfer.phone_number as ag_transfer_number   :            from asterisk.vicidial_vacd_callflow callflow    _            left join asterisk.vicidial_vacd_profile prof ON callflow.group_id = prof.group_id)   p            left join asterisk.vicidial_users user on user.user = CAST(callflow.connected_agent_num AS CHAR(20))   �            left join asterisk.vicidial_billing_log transfer on transfer.orig_uniqueid = callflow.uniqueid and transfer.type = 'XFER'   B            where callflow.Client_id = '$client_id' $whereClause";   		   		$connLoop = init();   <		$connLoop->_connect_by_type('PRIMARY_REPORTS',$client_id);   		if ($connLoop->errno != 0) {   $			send_error("DataBase Error:101");   
			return;   		}   		   *		$resultLoop = $connLoop->_execute($sql);   		if ($connLoop->errno != 0) {   #			send_error("Database Error 11");   '			_atm_log_critical("$connLoop->err");   			exit;   		}   		   		$record = false;   		$cnt = 0;   		$CallInfo = array();   		   >		while($row = mysqli_fetch_array($resultLoop, MYSQLI_ASSOC)){   			$CallInfo[0] = $row;   			$record = true;   			   			print "<CallData>\n";   ?			print printResponse("Unique_ID","UNIQUEID",$CallInfo[$cnt]);   4			print printResponse("ANI","ANI",$CallInfo[$cnt]);   6			print printResponse("DNIS","DNIS",$CallInfo[$cnt]);   =			print printResponse("Gate_ID","GROUP_ID",$CallInfo[$cnt]);   A			print printResponse("Gate_Name","group_name",$CallInfo[$cnt]);   >			print printResponse("Short_ID","short_id",$CallInfo[$cnt]);   			   A			print printResponse("Start_Time","STARTTIME",$CallInfo[$cnt]);   F			print printResponse("Enqueue_Time","ENQUEUE_TIME",$CallInfo[$cnt]);   F			print printResponse("Dequeue_Time","DEQUEUE_TIME",$CallInfo[$cnt]);   ?			print printResponse("Stop_Time","STOPTIME",$CallInfo[$cnt]);   B			print printResponse("Queue_Time","QUEUE_TIME",$CallInfo[$cnt]);   C			print printResponse("Call_Duration","DURATION",$CallInfo[$cnt]);   N			print printResponse("Last_Pseudo_Dnis","last_pseudo_dnis",$CallInfo[$cnt]);   L			print printResponse("Final_Call_Status","CALL_STATUS",$CallInfo[$cnt]);		   @			print printResponse("Agent_ID","agent_id",$CallInfo[$cnt]);		   D			print printResponse("Agent_Name","agent_name",$CallInfo[$cnt]);		   			   			   B			print printResponse("Attempts","CALL_ATTEMPT",$CallInfo[$cnt]);   L			print printResponse("Connected_Route","CONNECTED_ROUTE",$CallInfo[$cnt]);   K			print printResponse("Connected_Duration","BRIDGE_TIME",$CallInfo[$cnt]);   M			print printResponse("ACDTran_Time","agent_transfer_time",$CallInfo[$cnt]);   [			print printResponse("ACDTran_Destination","agent_transfer_destination",$CallInfo[$cnt]);   X			print printResponse("ACDTran_Duration","agent_transfer_bridge_time",$CallInfo[$cnt]);   O      print printResponse("AgentTran_Time","ag_transfer_time",$CallInfo[$cnt]);   U			print printResponse("AgentTran_Destination","ag_transfer_number",$CallInfo[$cnt]);   T			print printResponse("AgentTran_Duration","ag_transfer_duration",$CallInfo[$cnt]);   	   �			$sql = "select CONCAT(sys.recording_home_url,recs.recording_id) as Recording_URL, filename,dest_id from recording_log recs join system_settings as sys where vicidial_id = '".   #			$CallInfo[$cnt]['UNIQUEID']."'";   			   #			$result = $conn->_execute($sql);   			if ($conn->errno != 0) {   $				send_error("Database Error 21");   $				_atm_log_critical("$conn->err");   					exit;   			}   			   			$recordingCount = 0;   ;			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   				$recordingCount++;   				   ?				print printResponse("Recording_URL","Recording_URL",$row);	   >				print printResponse("Recording_filename","filename",$row);   9				if($row['dest_id'] != null && $row['dest_id'] != ""){   H					print printResponse("Inbound_Recording_URL","Recording_URL",$row);	   H					print printResponse("Inbound_Recording_filename","filename",$row);	          	 		}	   @       	 		if($row['dest_id'] == null || $row['dest_id'] == ""){   I					print printResponse("Transfer_Recording_URL","Recording_URL",$row);	   I					print printResponse("Transfer_Recording_filename","filename",$row);	          	 		}	   			}   			   			   			if($recordingCount < 1){   K					print printResponse("Recording_URL","Recording_URL",$CallInfo[$cnt]);	   L					print printResponse("Recording_filename","filename",$CallInfo[$cnt]);		   S					print printResponse("Inbound_Recording_URL","Recording_URL",$CallInfo[$cnt]);	   S					print printResponse("Inbound_Recording_filename","filename",$CallInfo[$cnt]);	   T					print printResponse("Transfer_Recording_URL","Recording_URL",$CallInfo[$cnt]);	   T					print printResponse("Transfer_Recording_filename","filename",$CallInfo[$cnt]);	   			}   			   5			if(is_bool($result) === false && $result != null){   !				$conn->_free_result($result);   			}   			   			print "</CallData>\n";   		}   		   		if(!$record){   )			send_error("Not Found/Access Denied");   		}		   "		mysqli_free_result($resultLoop);   		   		return $CallInfo;   	}   	   	function send_error($result){   '		$msg = "	<status>FAILURE</status>\n";   0		$msg .= "	<error_text>$result</error_text>\n";   		$msg .= "</response>\n";   		   		print "$msg";   		mylog($msg);   		exit;   	}   	   &	function send_response($InfoArray){		       '		$msg = "	<status>SUCCESS</status>\n";            $msg .= "</response>\n";                      print "$msg";   		mylog($msg);           exit;   	}   	   	function mylog($str){   !	    global $debug,$LOGFILE,$app;   	    if($debug){   #	        $fp = fopen($LOGFILE,"a");   O	        fputs($fp,date("M:j:Y-H:i:s")."-PID=".getmypid()."[$app] ".$str."\n");   	        fclose($fp);   	    }   	}       !	function xml_entities($string) {   	    return strtr(   	        $string,    	        array(   	            "<" => "&lt;",   	            //">" => "&gt;",   	            //'"' => "&quot;",   	            //"'" => "&apos;",   	            "&" => "&amp;",   
	        )   	    );   	}   	   2	function printResponse($string,$field,$CallInfo){   c		$msg = "<$string>".(!isset($CallInfo[$field])?"":xml_entities($CallInfo[$field]))."</$string>\n";   		mylog($msg);   		return $msg;   	}   	   ?>5�_�                    �   ^    ����                                                                                                                                                                                                                                                                                                                                                             X4ZO    �   �   �  [      _            left join asterisk.vicidial_vacd_profile prof ON callflow.group_id = prof.group_id)5�_�                    �   ]    ����                                                                                                                                                                                                                                                                                                                                                             X4[M     �              [   <?php   &    require("../../prosodie_def.php");   	require("Datasource.php");   	   Q	$LOGFILE="/var/log/atmosphere_webservices_GetACDCallInfo-".date("Y-m-d").".log";   	$debug=true;   	$app = "GetACDCallInfo";   	   +	mylog("Web Hit:".$_SERVER['REQUEST_URI']);   "	header("content-type: text/xml");   5	print"<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";   	print "<response>\n";   		           	webStartup();       	$CallInfo = getCallInfo();   		   	$conn->_close();   	send_response($CallInfo);   	   	function webStartup(){   		global $conn;   		global $link;   		global $client_id;   		global $TryingDR;   		global $CallInfo;   		global $ShortIDCount;   		global $CallInfoIndex;   		global $dest_idSQL;   		global $app;   		   		$error = "";   		$TryingDR = false;   		   		$LogString = "";   $		if(isset($_REQUEST["Unique_id"])){   6			$LogString .= " Unique_id:".$_REQUEST["Unique_id"];   K			if(!is_numeric($_REQUEST["Unique_id"]) || $_REQUEST["Unique_id"] == ""){   *				$error .= "Unique_id must be numeric";   			}   		}   (		if(isset($_REQUEST["StartDateTime"])){   >			$LogString .= " StartDateTime:".$_REQUEST["StartDateTime"];   		}   &		if(isset($_REQUEST["EndDateTime"])){   :			$LogString .= " EndDateTime:".$_REQUEST["EndDateTime"];   		}   '		if(isset($_REQUEST["security_key"])){   <			$LogString .= " security_key:".$_REQUEST["security_key"];   		}   !		if(isset($_REQUEST["Client"])){   0			$LogString .= " Client:".$_REQUEST["Client"];   		}   		   %		mylog("Web Info:".$app.$LogString);   		   &		if (!isset($_REQUEST["Unique_ID"])){   *			if(!isset($_REQUEST["StartDateTime"])){   '				$error .= "Missing StartDateTime ";   			}   (			if(!isset($_REQUEST["EndDateTime"])){   %				$error .= "Missing EndDateTime ";   			}   			if ($error != ""){   &				$error .= "or Missing Unique_ID ";   			}   		}   (		if (!isset($_REQUEST["security_key"]))   %			$error .= "Missing security_key ";   			   		if ($error != ""){   			send_error($error);   			exit;   		}   			   		$conn = init();   %		$conn->_connect_by_type('PRIMARY');   		if ($conn->errno != 0) {   "			send_error("DataBase Error:1");   
			return;   		}   				   		$validKey = false;   		   <		$security_key = $conn->_string($_REQUEST["security_key"]);   		   *		/* check security key against account */   q		$sql = "select * from atm_globals.tbl_vpd_account_info where security_key = '$security_key' and is_active='1'";   		   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   "			send_error("DataBase Error:2");   
			return;   		}   		   7		if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   "			$client_id = $row['clientnum'];    			$conn->_free_result($result);   			$validKey = true;   		}   		   		/* Get Account info */   		if($validKey){   V			$sql = "SELECT shard_num,schema_name FROM atm_globals.tbl_vpd_account_info where ";   "			$sql .= "clientnum=$client_id";   		}else{   W			$sql = "SELECT shard_num,schema_name FROM atm_globals.tbl_vpd_account_info limit 1";   		}   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   "			send_error("DataBase Error:3");   
			return;   +		}if ($row = $conn->_nextRowObj($result)){    			$shard_num = $row->shard_num;   $			$schema_name = $row->schema_name;    			$conn->_free_result($result);   		}else{   .			send_error("Account $client_id not found");   
			return;   		}   		   		$conn = new Datasource();   1		$conn->_connect_by_type('PRIMARY', $client_id);   		if ($conn->errno != 0)    		{   "			send_error("DataBase Error:5");   
			return;   		}   		   		if(!$validKey){   A			send_error("No Account found for security key=$security_key");   			exit;   		}   		   		   	}   	   	function init(){   0		$pos = strpos(dirname(__FILE__), "/services");   		if ($pos === false)    			$iniPath = dirname(__FILE__);   		else   1			$iniPath = substr(dirname(__FILE__), 0, $pos);   7		$iniPath = str_replace(AMFPHP, "WG5/ini/", $iniPath);   #		$conn = new Datasource($iniPath);   		return($conn);   	}   		   	function getCallInfo(){   		global $conn;   		global $client_id;   		global $Recording_URL;   		global $ShortIDCount;   		global $CallInfoIndex;   		global $dest_idSQL;   		   		$whereClause = "";   "		if(isset($_REQUEST["Gate_ID"])){   Y			$whereClause .= " and callflow.group_id = '".$conn->_string($_REQUEST["Gate_ID"])."'";   		}   		$ShortIDCount = 0;   		   $		if(isset($_REQUEST["Unique_ID"])){   G			$ShortIDArray = explode(",",$conn->_string($_REQUEST["Unique_ID"]));   			$ShortID = "";   			   4			for($cnt = 0;$cnt < count($ShortIDArray);$cnt++){   				if($ShortID != ""){   					$ShortID .= ",";   				}   (				$ShortID .= "'$ShortIDArray[$cnt]'";   			}   			   			$ShortIDCount = $cnt;   			   1			$whereClause .= " and uniqueID IN ($ShortID)";   		}   		   (		if(isset($_REQUEST["StartDateTime"])){   X			$whereClause .= " and starttime >= '".$conn->_string($_REQUEST["StartDateTime"])."'";   		}   		   &		if(isset($_REQUEST["EndDateTime"])){   V			$whereClause .= " and starttime <= '".$conn->_string($_REQUEST["EndDateTime"])."'";   		}   		   a    $sql = "Select callflow.*,user.full_name as agent_name,user.user as agent_id,prof.group_name,   j                   transfer.answered_time as ag_transfer_duration, transfer.call_date as ag_transfer_time,   >                   transfer.phone_number as ag_transfer_number   :            from asterisk.vicidial_vacd_callflow callflow    ^            left join asterisk.vicidial_vacd_profile prof ON callflow.group_id = prof.group_id   p            left join asterisk.vicidial_users user on user.user = CAST(callflow.connected_agent_num AS CHAR(20))   �            left join asterisk.vicidial_billing_log transfer on transfer.orig_uniqueid = callflow.uniqueid and transfer.type = 'XFER'   B            where callflow.Client_id = '$client_id' $whereClause";   		   		$connLoop = init();   <		$connLoop->_connect_by_type('PRIMARY_REPORTS',$client_id);   		if ($connLoop->errno != 0) {   $			send_error("DataBase Error:101");   
			return;   		}   		   *		$resultLoop = $connLoop->_execute($sql);   		if ($connLoop->errno != 0) {   #			send_error("Database Error 11");   '			_atm_log_critical("$connLoop->err");   			exit;   		}   		   		$record = false;   		$cnt = 0;   		$CallInfo = array();   		   >		while($row = mysqli_fetch_array($resultLoop, MYSQLI_ASSOC)){   			$CallInfo[0] = $row;   			$record = true;   			   			print "<CallData>\n";   ?			print printResponse("Unique_ID","UNIQUEID",$CallInfo[$cnt]);   4			print printResponse("ANI","ANI",$CallInfo[$cnt]);   6			print printResponse("DNIS","DNIS",$CallInfo[$cnt]);   =			print printResponse("Gate_ID","GROUP_ID",$CallInfo[$cnt]);   A			print printResponse("Gate_Name","group_name",$CallInfo[$cnt]);   >			print printResponse("Short_ID","short_id",$CallInfo[$cnt]);   			   A			print printResponse("Start_Time","STARTTIME",$CallInfo[$cnt]);   F			print printResponse("Enqueue_Time","ENQUEUE_TIME",$CallInfo[$cnt]);   F			print printResponse("Dequeue_Time","DEQUEUE_TIME",$CallInfo[$cnt]);   ?			print printResponse("Stop_Time","STOPTIME",$CallInfo[$cnt]);   B			print printResponse("Queue_Time","QUEUE_TIME",$CallInfo[$cnt]);   C			print printResponse("Call_Duration","DURATION",$CallInfo[$cnt]);   N			print printResponse("Last_Pseudo_Dnis","last_pseudo_dnis",$CallInfo[$cnt]);   L			print printResponse("Final_Call_Status","CALL_STATUS",$CallInfo[$cnt]);		   @			print printResponse("Agent_ID","agent_id",$CallInfo[$cnt]);		   D			print printResponse("Agent_Name","agent_name",$CallInfo[$cnt]);		   			   			   B			print printResponse("Attempts","CALL_ATTEMPT",$CallInfo[$cnt]);   L			print printResponse("Connected_Route","CONNECTED_ROUTE",$CallInfo[$cnt]);   K			print printResponse("Connected_Duration","BRIDGE_TIME",$CallInfo[$cnt]);   M			print printResponse("ACDTran_Time","agent_transfer_time",$CallInfo[$cnt]);   [			print printResponse("ACDTran_Destination","agent_transfer_destination",$CallInfo[$cnt]);   X			print printResponse("ACDTran_Duration","agent_transfer_bridge_time",$CallInfo[$cnt]);   O      print printResponse("AgentTran_Time","ag_transfer_time",$CallInfo[$cnt]);   U			print printResponse("AgentTran_Destination","ag_transfer_number",$CallInfo[$cnt]);   T			print printResponse("AgentTran_Duration","ag_transfer_duration",$CallInfo[$cnt]);   	   �			$sql = "select CONCAT(sys.recording_home_url,recs.recording_id) as Recording_URL, filename,dest_id from recording_log recs join system_settings as sys where vicidial_id = '".   #			$CallInfo[$cnt]['UNIQUEID']."'";   			   #			$result = $conn->_execute($sql);   			if ($conn->errno != 0) {   $				send_error("Database Error 21");   $				_atm_log_critical("$conn->err");   					exit;   			}   			   			$recordingCount = 0;   ;			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   				$recordingCount++;   				   ?				print printResponse("Recording_URL","Recording_URL",$row);	   >				print printResponse("Recording_filename","filename",$row);   9				if($row['dest_id'] != null && $row['dest_id'] != ""){   H					print printResponse("Inbound_Recording_URL","Recording_URL",$row);	   H					print printResponse("Inbound_Recording_filename","filename",$row);	          	 		}	   @       	 		if($row['dest_id'] == null || $row['dest_id'] == ""){   I					print printResponse("Transfer_Recording_URL","Recording_URL",$row);	   I					print printResponse("Transfer_Recording_filename","filename",$row);	          	 		}	   			}   			   			   			if($recordingCount < 1){   K					print printResponse("Recording_URL","Recording_URL",$CallInfo[$cnt]);	   L					print printResponse("Recording_filename","filename",$CallInfo[$cnt]);		   S					print printResponse("Inbound_Recording_URL","Recording_URL",$CallInfo[$cnt]);	   S					print printResponse("Inbound_Recording_filename","filename",$CallInfo[$cnt]);	   T					print printResponse("Transfer_Recording_URL","Recording_URL",$CallInfo[$cnt]);	   T					print printResponse("Transfer_Recording_filename","filename",$CallInfo[$cnt]);	   			}   			   5			if(is_bool($result) === false && $result != null){   !				$conn->_free_result($result);   			}   			   			print "</CallData>\n";   		}   		   		if(!$record){   )			send_error("Not Found/Access Denied");   		}		   "		mysqli_free_result($resultLoop);   		   		return $CallInfo;   	}   	   	function send_error($result){   '		$msg = "	<status>FAILURE</status>\n";   0		$msg .= "	<error_text>$result</error_text>\n";   		$msg .= "</response>\n";   		   		print "$msg";   		mylog($msg);   		exit;   	}   	   &	function send_response($InfoArray){		       '		$msg = "	<status>SUCCESS</status>\n";            $msg .= "</response>\n";                      print "$msg";   		mylog($msg);           exit;   	}   	   	function mylog($str){   !	    global $debug,$LOGFILE,$app;   	    if($debug){   #	        $fp = fopen($LOGFILE,"a");   O	        fputs($fp,date("M:j:Y-H:i:s")."-PID=".getmypid()."[$app] ".$str."\n");   	        fclose($fp);   	    }   	}       !	function xml_entities($string) {   	    return strtr(   	        $string,    	        array(   	            "<" => "&lt;",   	            //">" => "&gt;",   	            //'"' => "&quot;",   	            //"'" => "&apos;",   	            "&" => "&amp;",   
	        )   	    );   	}   	   2	function printResponse($string,$field,$CallInfo){   c		$msg = "<$string>".(!isset($CallInfo[$field])?"":xml_entities($CallInfo[$field]))."</$string>\n";   		mylog($msg);   		return $msg;   	}   	   ?>5�_�                     �       ����                                                                                                                                                                                                                                                                                                                            �          �                 X4[Z    �   �   �  [      1			$whereClause .= " and uniqueID IN ($ShortID)";5��