Vim�UnDo� ��%�d��y��{,QN8�q��K��`D�ݡ=1S   �                                  W��b    _�                             ����                                                                                                                                                                                                                                                                                                                                                             W��     �               �   <?php   &    require("../../prosodie_def.php");   	require("Datasource.php");   	   Q	$LOGFILE="/var/log/atmosphere_webservices_GetAgentStatus-".date("Y-m-d").".log";   	$debug=true;   	$app = "GetAgentStatus";   	   +	mylog("Web Hit:".$_SERVER['REQUEST_URI']);   #	$agent_id = $_REQUEST["username"];   $	$client_id = substr($agent_id,0,3);   	   	webStartup();       	checkAgent($agent_id);   &	$agentInfo = getAgentInfo($agent_id);   		   	$conn->_close();   	send_response($agentInfo);   	   	function webStartup(){   		global $conn;   		global $link;   		global $client_id;   		   		$error = "";       $		if (!isset($_REQUEST["username"]))   !			$error .= "Missing username ";   (		if (!isset($_REQUEST["security_key"]))   %			$error .= "Missing security_key ";   			   		if ($error != ""){   			send_error($error);   			exit;   		}   		   ,		$security_key = $_REQUEST["security_key"];   			   		$conn = init();   %		$conn->_connect_by_type('PRIMARY');   		if ($conn->errno != 0) {   			send_error($conn->err);   
			return;   		}   		   *		/* check security key against account */   �		$sql = "select * from atm_globals.tbl_vpd_account_info where security_key = '$security_key' and clientnum = '$client_id' and is_active='1'";   		   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   			send_error($conn->err);   
			return;   		}   		   7		if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   			$account = $row;    			$conn->_free_result($result);   		}else{   C			send_error("Invalid login information for account: $client_id");   			exit;   		}   		   		/* Get Account info */   V		$sql = "SELECT  shard_num,schema_name FROM atm_globals.tbl_vpd_account_info where ";   !		$sql .= "clientnum=$client_id";   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   			send_error($conn->err);   
			return;   +		}if ($row = $conn->_nextRowObj($result)){    			$shard_num = $row->shard_num;   $			$schema_name = $row->schema_name;    			$conn->_free_result($result);   		}else{   .			send_error("Account $client_id not found");   
			return;   		}   		   		$conn = new Datasource();   1		$conn->_connect_by_type('PRIMARY', $client_id);   		if ($conn->errno != 0)    		{   /			send_error($conn->err . print_r($dsn,TRUE));   
			return;   		}   		   	}   	   	function init(){   !    $iniPath = "/etc/atmosphere";   #		$conn = new Datasource($iniPath);   		return($conn);   	}   		   "	function getAgentInfo($agent_id){   		global $conn;   		global $Recording_URL;   		   �		$sql = "SELECT live.user,leads.*,CONCAT(sys.recording_home_url,recs.recording_id) as Recording_URL,live.uniqueid,live.campaign_id,dids.did_pattern,dids.did_description,   �              (SELECT GROUP_CONCAT(CONCAT(CONCAT('[',CONCAT(f.name,CONCAT('=>',c.value)),']')) SEPARATOR ' ') FROM vicidial_inbound_dids_cf c JOIN vicidial_inbound_custom_field f ON c.field_id = f.field_id WHERE did_id = dids.did_id) AS metadata   -            FROM vicidial_live_agents AS live   G            JOIN vicidial_list AS leads ON live.lead_id = leads.lead_id   I            JOIN vicidial_lists AS lists ON lists.list_id = leads.list_id   ]            LEFT JOIN vicidial_vacd_callflow AS callflow ON live.uniqueid = callflow.uniqueid   W            LEFT JOIN vicidial_inbound_dids AS dids ON callflow.dnis = dids.did_pattern   L            LEFT JOIN recording_log recs ON live.uniqueid = recs.vicidial_id   '            JOIN system_settings AS sys   -            WHERE live.user = '$agent_id'";     "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   %			send_error($conn->err . "($sql)");   			exit;   		}   		   3		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   		if($row){   			$LiveAgent = $row;   #			if($row['did_pattern'] != null){   $				$LiveAgent['gate_type'] = "ACD";   				}else{   !				$LiveAgent['gate_type'] = "";   			}   		}else{   +			$LiveAgent['live_agent_id'] = $agent_id;   #			$LiveAgent['gate_type'] = "NIC";   		}		   		mysqli_free_result($result);   		   		return $LiveAgent;   	}   		    	function checkAgent($agent_id){   		global $conn;   		   B		$sql  = "SELECT * from vicidial_users where user = '$agent_id'";       "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   %			send_error($conn->err . "($sql)");   			exit;   		}   		   3		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   		if(!$row){   			send_error("");   			exit;   		}		   		mysqli_free_result($result);   	}   	   	function send_error($result){   		print "$result";   E		print "ERROR|||||||||||||||||||||||||||||||||||||||||||||||||||||";   		mylog($result);   F		mylog("ERROR|||||||||||||||||||||||||||||||||||||||||||||||||||||");   	}   	   $	function send_response($agentInfo){   		global $agent_id;   		   		print "$agent_id|";   2		print printResponse($agentInfo,'gate_type')."|";   ]		print printResponse($agentInfo,'lead_id')."_".printResponse($agentInfo,'called_count')."|";   5		print printResponse($agentInfo,'phone_number')."|";   4		print printResponse($agentInfo,'did_pattern')."|";   8		print printResponse($agentInfo,'did_description')."|";   1		print printResponse($agentInfo,'metadata')."|";   2		print printResponse($agentInfo,'orig_dnis')."|";   6		print printResponse($agentInfo,'Recording_URL')."|";   4		print printResponse($agentInfo,'campaign_id')."|";   0		print printResponse($agentInfo,'lead_id')."|";   /		print printResponse($agentInfo,'status')."|";   7		print printResponse($agentInfo,'gmt_offset_now')."|";   .		print printResponse($agentInfo,'title')."|";   3		print printResponse($agentInfo,'first_name')."|";   4		print printResponse($agentInfo,'middle_name')."|";   2		print printResponse($agentInfo,'last_name')."|";   /		print printResponse($agentInfo,'suffix')."|";   1		print printResponse($agentInfo,'address1')."|";   1		print printResponse($agentInfo,'address2')."|";   -		print printResponse($agentInfo,'city')."|";   .		print printResponse($agentInfo,'state')."|";   4		print printResponse($agentInfo,'postal_code')."|";   /		print printResponse($agentInfo,'extra1')."|";   /		print printResponse($agentInfo,'extra2')."|";   /		print printResponse($agentInfo,'extra3')."|";   /		print printResponse($agentInfo,'extra4')."|";   /		print printResponse($agentInfo,'extra5')."|";   2		print printResponse($agentInfo,'alt_phone')."|";   1		print printResponse($agentInfo,'uniqueid')."|";   /		print printResponse($agentInfo,'extra6')."|";   /		print printResponse($agentInfo,'extra7')."|";   /		print printResponse($agentInfo,'extra8')."|";   /		print printResponse($agentInfo,'extra9')."|";   0		print printResponse($agentInfo,'extra10')."|";   0		print printResponse($agentInfo,'extra11')."|";   0		print printResponse($agentInfo,'extra12')."|";   0		print printResponse($agentInfo,'extra13')."|";   0		print printResponse($agentInfo,'extra14')."|";   0		print printResponse($agentInfo,'extra15')."|";   0		print printResponse($agentInfo,'extra16')."|";   0		print printResponse($agentInfo,'extra17')."|";   0		print printResponse($agentInfo,'extra18')."|";   0		print printResponse($agentInfo,'extra19')."|";   0		print printResponse($agentInfo,'extra20')."|";   0		print printResponse($agentInfo,'extra21')."|";   0		print printResponse($agentInfo,'extra22')."|";   0		print printResponse($agentInfo,'extra23')."|";   0		print printResponse($agentInfo,'extra24')."|";   0		print printResponse($agentInfo,'extra25')."|";   0		print printResponse($agentInfo,'extra26')."|";   0		print printResponse($agentInfo,'extra27')."|";   0		print printResponse($agentInfo,'extra28')."|";   0		print printResponse($agentInfo,'extra29')."|";   ,		print printResponse($agentInfo,'extra30');   	}   	   ,	function printResponse($agentInfo,$string){   <		mylog(!isset($agentInfo[$string])?"":$agentInfo[$string]);   >		return (!isset($agentInfo[$string])?"":$agentInfo[$string]);   	}   	   	function mylog($str){   !	    global $debug,$LOGFILE,$app;   	    if($debug){   #	        $fp = fopen($LOGFILE,"a");   O	        fputs($fp,date("M:j:Y-H:i:s")."-PID=".getmypid()."[$app] ".$str."\n");   	        fclose($fp);   	    }   	}   	   ?>5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             W��     �                &    require("../../prosodie_def.php");5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W��     �         �       �         �    5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W��     �         �        require_once()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W��     �         �        require_once(dirname())5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             W��     �         �      #  require_once(dirname($_SERVER[]))5�_�                       !    ����                                                                                                                                                                                                                                                                                                                                                             W��     �         �      %  require_once(dirname($_SERVER['']))5�_�      	                 5    ����                                                                                                                                                                                                                                                                                                                                                             W��    �         �      7  require_once(dirname($_SERVER['SCRIPT_FILENAME'])."")5�_�      
           	           ����                                                                                                                                                                                                                                                                                                                                                             W��_     �               �   <?php   I  require_once(dirname($_SERVER['SCRIPT_FILENAME'])."/prosodie_def.php");   	require("Datasource.php");   	   Q	$LOGFILE="/var/log/atmosphere_webservices_GetAgentStatus-".date("Y-m-d").".log";   	$debug=true;   	$app = "GetAgentStatus";   	   +	mylog("Web Hit:".$_SERVER['REQUEST_URI']);   #	$agent_id = $_REQUEST["username"];   $	$client_id = substr($agent_id,0,3);   	   	webStartup();       	checkAgent($agent_id);   &	$agentInfo = getAgentInfo($agent_id);   		   	$conn->_close();   	send_response($agentInfo);   	   	function webStartup(){   		global $conn;   		global $link;   		global $client_id;   		   		$error = "";       $		if (!isset($_REQUEST["username"]))   !			$error .= "Missing username ";   (		if (!isset($_REQUEST["security_key"]))   %			$error .= "Missing security_key ";   			   		if ($error != ""){   			send_error($error);   			exit;   		}   		   ,		$security_key = $_REQUEST["security_key"];   			   		$conn = init();   %		$conn->_connect_by_type('PRIMARY');   		if ($conn->errno != 0) {   			send_error($conn->err);   
			return;   		}   		   *		/* check security key against account */   �		$sql = "select * from atm_globals.tbl_vpd_account_info where security_key = '$security_key' and clientnum = '$client_id' and is_active='1'";   		   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   			send_error($conn->err);   
			return;   		}   		   7		if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   			$account = $row;    			$conn->_free_result($result);   		}else{   C			send_error("Invalid login information for account: $client_id");   			exit;   		}   		   		/* Get Account info */   V		$sql = "SELECT  shard_num,schema_name FROM atm_globals.tbl_vpd_account_info where ";   !		$sql .= "clientnum=$client_id";   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   			send_error($conn->err);   
			return;   +		}if ($row = $conn->_nextRowObj($result)){    			$shard_num = $row->shard_num;   $			$schema_name = $row->schema_name;    			$conn->_free_result($result);   		}else{   .			send_error("Account $client_id not found");   
			return;   		}   		   		$conn = new Datasource();   1		$conn->_connect_by_type('PRIMARY', $client_id);   		if ($conn->errno != 0)    		{   /			send_error($conn->err . print_r($dsn,TRUE));   
			return;   		}   		   	}   	   	function init(){   !    $iniPath = "/etc/atmosphere";   #		$conn = new Datasource($iniPath);   		return($conn);   	}   		   "	function getAgentInfo($agent_id){   		global $conn;   		global $Recording_URL;   		   �		$sql = "SELECT live.user,leads.*,CONCAT(sys.recording_home_url,recs.recording_id) as Recording_URL,live.uniqueid,live.campaign_id,dids.did_pattern,dids.did_description,   �              (SELECT GROUP_CONCAT(CONCAT(CONCAT('[',CONCAT(f.name,CONCAT('=>',c.value)),']')) SEPARATOR ' ') FROM vicidial_inbound_dids_cf c JOIN vicidial_inbound_custom_field f ON c.field_id = f.field_id WHERE did_id = dids.did_id) AS metadata   -            FROM vicidial_live_agents AS live   G            JOIN vicidial_list AS leads ON live.lead_id = leads.lead_id   I            JOIN vicidial_lists AS lists ON lists.list_id = leads.list_id   ]            LEFT JOIN vicidial_vacd_callflow AS callflow ON live.uniqueid = callflow.uniqueid   W            LEFT JOIN vicidial_inbound_dids AS dids ON callflow.dnis = dids.did_pattern   L            LEFT JOIN recording_log recs ON live.uniqueid = recs.vicidial_id   '            JOIN system_settings AS sys   -            WHERE live.user = '$agent_id'";     "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   %			send_error($conn->err . "($sql)");   			exit;   		}   		   3		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   		if($row){   			$LiveAgent = $row;   #			if($row['did_pattern'] != null){   $				$LiveAgent['gate_type'] = "ACD";   				}else{   !				$LiveAgent['gate_type'] = "";   			}   		}else{   +			$LiveAgent['live_agent_id'] = $agent_id;   #			$LiveAgent['gate_type'] = "NIC";   		}		   		mysqli_free_result($result);   		   		return $LiveAgent;   	}   		    	function checkAgent($agent_id){   		global $conn;   		   B		$sql  = "SELECT * from vicidial_users where user = '$agent_id'";       "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   %			send_error($conn->err . "($sql)");   			exit;   		}   		   3		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   		if(!$row){   			send_error("");   			exit;   		}		   		mysqli_free_result($result);   	}   	   	function send_error($result){   		print "$result";   E		print "ERROR|||||||||||||||||||||||||||||||||||||||||||||||||||||";   		mylog($result);   F		mylog("ERROR|||||||||||||||||||||||||||||||||||||||||||||||||||||");   	}   	   $	function send_response($agentInfo){   		global $agent_id;   		   		print "$agent_id|";   2		print printResponse($agentInfo,'gate_type')."|";   ]		print printResponse($agentInfo,'lead_id')."_".printResponse($agentInfo,'called_count')."|";   5		print printResponse($agentInfo,'phone_number')."|";   4		print printResponse($agentInfo,'did_pattern')."|";   8		print printResponse($agentInfo,'did_description')."|";   1		print printResponse($agentInfo,'metadata')."|";   2		print printResponse($agentInfo,'orig_dnis')."|";   6		print printResponse($agentInfo,'Recording_URL')."|";   4		print printResponse($agentInfo,'campaign_id')."|";   0		print printResponse($agentInfo,'lead_id')."|";   /		print printResponse($agentInfo,'status')."|";   7		print printResponse($agentInfo,'gmt_offset_now')."|";   .		print printResponse($agentInfo,'title')."|";   3		print printResponse($agentInfo,'first_name')."|";   4		print printResponse($agentInfo,'middle_name')."|";   2		print printResponse($agentInfo,'last_name')."|";   /		print printResponse($agentInfo,'suffix')."|";   1		print printResponse($agentInfo,'address1')."|";   1		print printResponse($agentInfo,'address2')."|";   -		print printResponse($agentInfo,'city')."|";   .		print printResponse($agentInfo,'state')."|";   4		print printResponse($agentInfo,'postal_code')."|";   /		print printResponse($agentInfo,'extra1')."|";   /		print printResponse($agentInfo,'extra2')."|";   /		print printResponse($agentInfo,'extra3')."|";   /		print printResponse($agentInfo,'extra4')."|";   /		print printResponse($agentInfo,'extra5')."|";   2		print printResponse($agentInfo,'alt_phone')."|";   1		print printResponse($agentInfo,'uniqueid')."|";   /		print printResponse($agentInfo,'extra6')."|";   /		print printResponse($agentInfo,'extra7')."|";   /		print printResponse($agentInfo,'extra8')."|";   /		print printResponse($agentInfo,'extra9')."|";   0		print printResponse($agentInfo,'extra10')."|";   0		print printResponse($agentInfo,'extra11')."|";   0		print printResponse($agentInfo,'extra12')."|";   0		print printResponse($agentInfo,'extra13')."|";   0		print printResponse($agentInfo,'extra14')."|";   0		print printResponse($agentInfo,'extra15')."|";   0		print printResponse($agentInfo,'extra16')."|";   0		print printResponse($agentInfo,'extra17')."|";   0		print printResponse($agentInfo,'extra18')."|";   0		print printResponse($agentInfo,'extra19')."|";   0		print printResponse($agentInfo,'extra20')."|";   0		print printResponse($agentInfo,'extra21')."|";   0		print printResponse($agentInfo,'extra22')."|";   0		print printResponse($agentInfo,'extra23')."|";   0		print printResponse($agentInfo,'extra24')."|";   0		print printResponse($agentInfo,'extra25')."|";   0		print printResponse($agentInfo,'extra26')."|";   0		print printResponse($agentInfo,'extra27')."|";   0		print printResponse($agentInfo,'extra28')."|";   0		print printResponse($agentInfo,'extra29')."|";   ,		print printResponse($agentInfo,'extra30');   	}   	   ,	function printResponse($agentInfo,$string){   <		mylog(!isset($agentInfo[$string])?"":$agentInfo[$string]);   >		return (!isset($agentInfo[$string])?"":$agentInfo[$string]);   	}   	   	function mylog($str){   !	    global $debug,$LOGFILE,$app;   	    if($debug){   #	        $fp = fopen($LOGFILE,"a");   O	        fputs($fp,date("M:j:Y-H:i:s")."-PID=".getmypid()."[$app] ".$str."\n");   	        fclose($fp);   	    }   	}   	   ?>5�_�   	              
           ����                                                                                                                                                                                                                                                                                                                                                             W��`     �                I  require_once(dirname($_SERVER['SCRIPT_FILENAME'])."/prosodie_def.php");5�_�   
                        ����                                                                                                                                                                                                                                                                                                                                                             W��`     �         �       �         �    5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W��a     �         �        require_once()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W��a     �         �        require_once(())5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W��a     �         �        require_once(($_SERVER[]))5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W��a     �         �        require_once(($_SERVER['']))5�_�                        ,    ����                                                                                                                                                                                                                                                                                                                                                             W��a    �         �      .  require_once(($_SERVER['DOCUMENT_ROOT'])."")5��