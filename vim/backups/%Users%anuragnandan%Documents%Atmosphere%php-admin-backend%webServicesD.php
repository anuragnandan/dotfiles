Vim�UnDo� �S���Tǿ.�'��M@�b�'#���c":��+  �           9         
       
   
   
    Xm@�    _�                    �       ����                                                                                                                                                                                                                                                                                                                                                             Xm@L     �              �   #!/usr/bin/php.soap   <?   /*   WDaemon that is designed to run in parellel that process web service requests and writes   Nthe response to a txt file for time sensitive requests or back to the database       Programmer: Steven Mathers   Date: Nov 15/2011   */       //basic log function   //       function mylog($str)   {       global $debug,$logPrefix;   :	$LOGFILE = "/var/log/webServicesD-".date("Y-m-d").".log";       if($debug)   	{   	    $fp = fopen($LOGFILE,"a");   L	    fputs($fp,date("M:j:Y")."-".date("H:i:s")."-$logPrefix"." ".$str."\n");   	    fclose($fp);       }   }       &function explodeX($delimiters,$string)   {   :    $return_array = Array($string); // The array to return       $d_count = 0;   O    while (isset($delimiters[$d_count])) // Loop to loop through all delimiters       {   "	    $new_return_array = Array();    b	    foreach($return_array as $el_to_split) // Explode all returned elements by the next delimiter   	    {   M		    $put_in_new_return_array = explode($delimiters[$d_count],$el_to_split);   f		    foreach($put_in_new_return_array as $substr) // Put all the exploded elements in array to return   		    {   %			    $new_return_array[] = $substr;   			}   		}   ]		$return_array = $new_return_array; // Replace the previous return array by the next version   	    $d_count++;   	}   9    return $return_array; // Return the exploded elements   }       /**    *     * Call a Soap Service   * * @param $url				The url of the wsdl file   t * @param $params			The parameter that we need to pass. Those parameters are already serialised as an complex array.   B * @param $function			The name of the method that we need to call.   % * @param $timeout			The timout time.   c * @param $webserviceId		The webservice id related to that call on the vicidial_webservice_methods.   a * @param $leadId			The id of the lead who could have some values updated from the soap response.    */			   qfunction soapCall($url, $params, $function, $timeout, $webserviceId, $leadId, $username = null, $password = null)   {   	// Try to connect to host   	try	{   7		mylog("SoapClient try to connect to SoapServer ...");   .		ini_set('default_socket_timeout', $timeout);   		   $		// Check for password and username   .		if ($username != null && $password != null){   #			$options['login'] 	 = $username;   $			$options['password'] = $password;   \			mylog("SoapServer required authentification : Trying with ".$username." and ".$password);   		}   		else{   ,			mylog("No authentification required...");   		}   			    		$client = new SoapClient($url,   				array(   				'exceptions'			=> 1,    				'trace'					=> 1,   .				'features' 				=> SOAP_USE_XSI_ARRAY_TYPE,   '				'cache_wsdl' 			=> WSDL_CACHE_DISK,   '				'connection_timeout'    => $timeout   			)   		);   	}   	catch(Exception $e) {   		   		$ret['success'] = false;   		$err = $e->getMessage();   		   E		if(strpos(strtolower($err), "error fetching http headers")!==FALSE)   0			$err = "Response Timeout: ".$e->getMessage();   			   *		mylog(__FUNCTION__."SOAP ERROR: ".$err);   		   		$ret['output']="$err";   		return $ret;   	}   	    	mylog("SoapClient connected!");   4	mylog("Parameter sended: ".print_r($params, true));       	// Try to call   	try   	{   <		mylog(__FUNCTION__." Try to call the method: ".$function);   F		$resp = $client->__soapCall($function, array($function => $params));   		   		$ret['success'] = true;   1		$ret['output'] 	= $client->__getLastResponse();       )		// Analyse the result and get lead data   G		mylog("Results received, begining deserialization of the answer...");   		$resp = objectToArray($resp);   9		deserializeResultValues($resp, $webserviceId, $leadId);       1		mylog("request: ".$client->__getLastRequest());   2		mylog("response:".$client->__getLastResponse());       		return $ret;   	}   	catch (SoapFault $fault)   	{   		$err = $fault->getMessage();   D		if(strpos(strtolower($err),"error fetching http headers")!==FALSE)   4			$err = "Response Timeout :".$fault->getMessage();   			   8		mylog("FAULT: request: ".$client->__getLastRequest());   <		mylog("FAULT: response:".$client->__getLastResponse());			   			   *		mylog(__FUNCTION__."SOAP ERROR: ".$err);   		$ret['success'] = false;   		$ret['output'] 	= $err;   		return $ret;   	}   }           /**    *    k * Serialize the result for a specific soapmethod. The return object is an array and it will be mapped with   j * the field expected by the user (settled on the Admin GUI->Webservices Manager). The fill which could be   e * updated are only for lead purposes, that the reason why there is only an update for vicidial_list.   # * @param $array			the result data.   1 * @param $webserviceId		the id of the webservice   & * @param $leadId			the id of the lead    */   Afunction deserializeResultValues($array, $webserviceId, $leadId){   	   	global $link;   	   H	// Get the result that we really care, mapped on the webservice screen.   U	// Note : AND b.lead_field_id IS NOT NULL because we only care about the lead_field.   	   	$sql  = "SELECT ";   U	$sql .= "a.ParameterRecnum, a.MethodRecnum, a.ParameterName, a.ParameterFullName, ";   *	$sql .= "b.field_name, b.lead_field_id ";   F	$sql .= "FROM asterisk.vicidial_webservices_methods_resultparams a ";   =	$sql .= "LEFT JOIN asterisk.vicidial_webservices_fields b ";   (	$sql .= "ON a.MappedField=b.field_id ";   R	$sql .= "WHERE MethodRecnum='".$webserviceId."' AND b.lead_field_id IS NOT NULL";   	   	mylog("SELECT SQL : ".$sql);   	   .	$expectedResults = mysqli_query($link, $sql);   	   3	if (!$expectedResults || empty($expectedResults)){   			mylog("ERROR SQL : ".$sql);   
			return;   	}   	   2	// Get linear result from the call of the method.   	$linearizedResult = array();   +	recursive_dump($array, $linearizedResult);   	ksort($linearizedResult);   	   	// Begining the SQL Update   -	$sql = "UPDATE asterisk.vicidial_list SET ";   	$parameterFound = false;   	   ,	// Looping the result that we are expecting   @	while ($expectedResult = mysqli_fetch_assoc($expectedResults)){   1		foreach ($linearizedResult as $key => $value) {   C			if ($expectedResult['ParameterFullName'] == $value['nodeName']){   /				if (!empty($expectedResult['field_name'])){   ,					$name  = $expectedResult['field_name'];   @					$value = mysqli_real_escape_string($link, $value['value']);   					   					if (!empty($value)){   						$parameterFound = true;   &						$sql .= $name."='".$value."' ,";   					}   				}   			}   		}   	}   	   	if (!$parameterFound){   #		mylog("No lead value to update");   			return;   	}   	   	// Delete the last comma.   	$sql = substr($sql, 0, -1);   '	$sql .= "WHERE lead_id='".$leadId."'";   	   	// Debug sql   	mylog("UPDATE SQL : ".$sql);   	   	// Execute the update.   %	$result = mysqli_query($link, $sql);   	   !	if (!$result || empty($result)){   		mylog("ERROR SQL : ".$sql);   			return;   	}	   }           /**    *    F * Browse an array and add all the end branch with the fullnodename to    * an array.    *    % * @param $array		The array to browse   " * @param $dump			The result array   . * @param $curlevel		The level (not used here)   5 * @param $nodeName		The nodeName (at the begining 0)    */   Ifunction recursive_dump(&$array, &$dump, $curlevel = 0, $nodeName = '') {   	$oldNodeName = $nodeName;   	if (is_array($array)){   "		foreach ($array as $k => $v) {		   			if (is_array($v)) {   				if (!is_numeric($k)){   					$nodeName .= $k.".";   				}   9				recursive_dump($v, $dump, $curlevel + 1, $nodeName);	   				$nodeName = $oldNodeName;   				   			}    				else {   T				array_push($dump, array('nodeName' => $nodeName.$k,'key' => $k, 'value' => $v));   			}   		}   	}   }   	       /**    *     * Enter description here ...    * @param $d    */   function objectToArray($d) {   	if (is_object($d)) {   		$d = get_object_vars($d);   	}       	if (is_array($d)) {   (		return array_map('objectToArray', $d);   	}   	else {   		return $d;   	}   }   	   	   	   ,function curlCall($url,$timeout,$post=false)   {   	//global $post_string;   	$ch=curl_init();   	   	   	//mylog("post=$post_string");   	mylog("url=$url");   
	if($post)   	{   		$tmp = explode("?",$url);   		$url = $tmp[0];   )		//$url = substr($url,0,strlen($url)-1);   %		curl_setopt($ch, CURLOPT_POST, 1);    0		curl_setopt($ch, CURLOPT_POSTFIELDS, $tmp[1]);   	}   	   #	curl_setopt($ch,CURLOPT_URL,$url);   1	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);	   '	curl_setopt($ch,CURLOPT_HEADER,false);   .	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);   +	curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);   3	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, $timeout);   	$ret=curl_exec($ch);   	   	if($ret===false)   	{   		$error=curl_error($ch);   >		mylog(__FUNCTION__." : Curl post failed, Reason -> $error");   		$resp['success'] = false;   		$resp['output'] = $error;   		curl_close($ch);   		return $resp;   	}   	else   	{   		$resp['success'] = true;   		$resp['output'] = $ret;   		curl_close($ch);   		return $resp;   	}   }       .function chkMysql($code,$ret,$link, $sql = "")   {   
	if(!$ret)   	{   T		mylog("[ERROR] Invalid sql statement:$code for sql : $sql :".mysqli_error($link));   		mysqli_close($link);   		sleep(2);   		exit;   	}   }   &function file_exists2($path,$filename)   {       if ($dh = opendir($path))       {   -	    while (($file = readdir($dh)) !== false)   	    {   		    if($file==$filename)   		    {   			    return true;   			}   	    }   	    closedir($dh);   	}   	return false;   }           function getLeadData($request)   {   	global $link,$vacdSwitch;       4	if($request['lead_id']==""||$request['lead_id']==0)   	{   		if(!$vacdSwitch)   		{   			$theSql = "".   @				"SELECT l.*,lead.*,c.campaign_description,c.campaign_name,".   P				"c.campaign_cid,li.campaign_cid_override,li.list_name,li.list_description,".   `				"u.full_name as agent_name,agent_external_id,li.agent_script_override,c.campaign_script,  ".   c				"u.custom_one,u.custom_two,u.custom_three,u.custom_four,u.custom_five,u.email as agent_email ".   M				"FROM vicidial_log l join vicidial_list lead on l.lead_id=lead.lead_id ".   E				"left join vicidial_campaigns c on l.campaign_id=c.campaign_id ".   6				"join vicidial_lists li on l.list_id=li.list_id ".   3				"left join vicidial_users u on l.user=u.user ".   2				"where uniqueid ='".$request['record_id']."'";   		}   		else   		{   			$theSql = "".   @				"SELECT l.*,lead.*,c.campaign_description,c.campaign_name,".   P				"c.campaign_cid,li.campaign_cid_override,li.list_name,li.list_description,".   `				"u.full_name as agent_name,agent_external_id,li.agent_script_override,c.campaign_script,  ".   c				"u.custom_one,u.custom_two,u.custom_three,u.custom_four,u.custom_five,u.email as agent_email ".   				"FROM vicidial_log l ".   9				"join vicidial_list lead on l.lead_id=lead.lead_id ".   I				"left join vicidial_vacd_callflow vacd on vacd.lead_id = l.lead_id ".   H				"left join vicidial_campaigns c on vacd.campaign_id=c.campaign_id ".   6				"join vicidial_lists li on l.list_id=li.list_id ".   3				"left join vicidial_users u on l.user=u.user ".   4				"where l.uniqueid ='".$request['record_id']."'";   		}   	}       4	if($request['lead_id']!=""&&$request['lead_id']!=0)   	{   		if(!$vacdSwitch)   		{   			$theSql = "".   I				"select l.*, c.campaign_id, c.campaign_description,c.campaign_name,".   P				"c.campaign_cid,li.campaign_cid_override,li.list_name,li.list_description,".   _				"u.full_name as agent_name,agent_external_id,li.agent_script_override,c.campaign_script, ".   c				"u.custom_one,u.custom_two,u.custom_three,u.custom_four,u.custom_five,u.email as agent_email ".   				"from vicidial_list l ".   6				"join vicidial_lists li on l.list_id=li.list_id ".   F				"left join vicidial_campaigns c on li.campaign_id=c.campaign_id ".   3				"left join vicidial_users u on l.user=u.user ".   2				"where l.lead_id = '".$request['lead_id']."'";   		}   		else   		{   			$theSql = "".   I				"select l.*, c.campaign_id, c.campaign_description,c.campaign_name,".   P				"c.campaign_cid,li.campaign_cid_override,li.list_name,li.list_description,".   _				"u.full_name as agent_name,agent_external_id,li.agent_script_override,c.campaign_script, ".   c				"u.custom_one,u.custom_two,u.custom_three,u.custom_four,u.custom_five,u.email as agent_email ".   				"from vicidial_list l ".   6				"join vicidial_lists li on l.list_id=li.list_id ".   I				"left join vicidial_vacd_callflow vacd on vacd.lead_id = l.lead_id ".   H				"left join vicidial_campaigns c on vacd.campaign_id=c.campaign_id ".   3				"left join vicidial_users u on l.user=u.user ".   2				"where l.lead_id = '".$request['lead_id']."'";   		}           9		if($request['record_id']==""||$request['record_id']==0)   		{   2			//create a blank associated array if using lead   ?			$row = mysqli_query($link,"show columns from vicidial_log");   			chkMysql("022",$row,$link);   			$blank = array();       )			while($tmp = mysqli_fetch_assoc($row))   			{   				$blank[$tmp['Field']] = "";   			}   		}   		else   		{   C			$uid_past = (substr($request['unique_id'],0,12) - 60).'0000000';   C			$uid_post = (substr($request['unique_id'],0,12) + 60).'0000000';   �			$row = mysqli_query($link,"SELECT * FROM vicidial_log WHERE lead_id = '".$request['lead_id']."' AND uniqueid BETWEEN '$uid_past' AND '$uid_post' ORDER BY uniqueid DESC LIMIT 1");   �			mylog(__FUNCTION__."():Running query : select * from vicidial_log where lead_id = '".$request['lead_id']."' AND uniqueid BETWEEN $uid_past AND $uid_post order by uniqueid desc limit 1");   			chkMysql("023",$row,$link);       %			$blank = mysqli_fetch_assoc($row);   4			mylog(__FUNCTION__."(): Got:".print_r($blank,1));   		}   	}       	mylog("$theSql");       %	$data = mysqli_query($link,$theSql);   	chkMysql("012",$data,$link);   #	$data = mysqli_fetch_assoc($data);       @	if($request['lead_id']!=0&&$request['lead_id']!=""&&$blank!="")   	{   #		foreach($blank as $key => $value)   		{   			if(!isset($data[$key]))   				$data[$key] = $value;   		}   	}       4	if($request['lead_id']==""||$request['lead_id']==0)   v		$recording = mysqli_query($link,"select filename from recording_log where vicidial_id='".$request['record_id']."'");   	else   p		$recording = mysqli_query($link,"select filename from recording_log where lead_id='".$request['lead_id']."'");       "	chkMysql("020",$recording,$link);   "	$data['recording_filename'] = "";   -	while($row = mysqli_fetch_assoc($recording))   	{   Q		$data['recording_filename'] = $data['recording_filename']."|".$row['filename'];   	}   ~	if($data['recording_filename']!="")$data['recording_filename'] = substr($data['recording_filename'],1);//strip off extra pipe       ?	//setup change campaign caller id if there is override present   -	if(trim($data['campaign_cid_override'])!="")   	{   9		$data['campaign_cid'] = $data['campaign_cid_override'];   	}       -	//get status name if status name is not null   	$data['status_name'] = "";   2	if($data['campaign_id']!=""&&$data['status']!="")   	{  		$statusName = mysqli_query($link,"select * from (select status,status_name from vicidial_statuses union select status,status_name from vicidial_campaign_statuses where campaign_id='".$data['campaign_id']."') a where status='".$data['status']."' limit 1");   $		chkMysql("021",$statusName,$link);   0		$statusName = mysqli_fetch_assoc($statusName);   		   '		if(isset($statusName['status_name']))   3			$data['status_name']=$statusName['status_name'];   	}       �	$callback = mysqli_query($link,"Select callback_time, comments From vicidial_callbacks Where lead_id = '".$request['lead_id']."' order by callback_time DESC limit 1");   !	chkMysql("024",$callback,$link);   +	$callback = mysqli_fetch_assoc($callback);       4	$data['callback_comments'] = $callback['comments'];   5	$data['callback_time'] = $callback['callback_time'];       '	if($data['agent_script_override']=="")   	{   2		$data['script_used'] = $data['campaign_script'];   	}   	else   	{   8		$data['script_used'] = $data['agent_script_override'];   	}   	if($data['script_used']!="")   	{   s		$script_used = mysqli_query($link,"select name from phpesp.phpesp_survey where id = '".$data['script_used']."'");   %		chkMysql("033",$script_used,$link);   5    	$script_used = mysqli_fetch_assoc($script_used);   .		$data['script_used'] = $script_used['name'];   	}       	mylog(print_r($data,true));       	return $data;   }																									       $post_string="";   $PID=getmypid();   $debug = true;   )$iniPath = "/etc/atmosphere/general.ini";   +$iniParams = parse_ini_file($iniPath,true);   '$dbip = $iniParams['database']['host'];   ($dbuser =$iniParams['database']['user'];   )$dbpass = $iniParams['database']['pass'];   '$db = $iniParams['database']['schema'];   $request_id = $argv[1];   ;$responsePath = "/share/Atmosphere/web_services_responses";       if(count($argv)<1)   {   J	mylog("Wrong arguments entered:[Usage] php webServicesD.php request_id");   J	echo "Wrong arguments entered:[Usage] php webServicesD.php request_id\n";   	exit;   }       #mylog("webServiceD starting ....");       $idleCount = 0;   $dbConnected = false;           2	$complexObject = array();//used for soap services   ?	//check to see if there is a kill file, if so end this process   )	if(file_exists("/tmp/webservices.$PID"))   	{   "		unlink("/tmp/webservices.$PID");   %		mylog("Kill file found, Stopping");   		break;   	}   	   	if(!$dbConnected)   	{   7		$link = mysqli_connect($dbip, $dbuser, $dbpass, $db);       		if (!$link) {   H	    	mylog("Can't connect to $dbip. [ERROR]: ".mysqli_connect_error());   	    	sleep(10);   	    	exit;   		}   		else   		{   			$dbConnected = true;   		}   	}   	   �	$sql = "SELECT request_id, unique_id, method_id, timeout, record_id, num_attempts, retry_count, write_response_file, retry_interval, extra_data, lead_id   ,          FROM vicidial_webservices_requests   :          WHERE request_id = $request_id AND pending = 2";           %	$request = mysqli_query($link,$sql);       A	// If no deadlock, check for other errors that may have occured:   &	chkMysql("001",$request,$link, $sql);   #	$rows = mysqli_num_rows($request);       	if($rows > 0)   	{   *		$request = mysqli_fetch_assoc($request);       5		$logPrefix = "PID-$PID:RQ-".$request['request_id'];       7		if($request['num_attempts']>=$request['retry_count'])   		{   e			//update the the pending to being 0 so the records does not get processed again, retries exhausted   b			mylog("Reached max attempts for request_id = ".$request['request_id']." setting pending to 0");   �			$updateStatus = mysqli_query($link,"update vicidial_webservices_requests set pending = 0, status = 'FAILED' where request_id = ".$request['request_id']);   '			chkMysql("002",$updateStatus,$link);   		}   		else   		{   I			//process the request, update records to lock it from other daemons			   Q			mylog("Updating request_id = ".$request['request_id']." to begin processing");   �			$updateStatus = mysqli_query($link,"update vicidial_webservices_requests set pending = 0, num_attempts = num_attempts + 1, dequeue_dts = NOW(), status = 'Processing' where request_id = ".$request['request_id']);   '			chkMysql("003",$updateStatus,$link);   		       		 $extra=array();   *		//check to see if there is custom fields    		if($request['extra_data']!="")   		{   1			if(strpos($request['extra_data'],"|")!==false)   			{   +				$y=explode("|",$request['extra_data']);   				foreach($y as $value)   				{   					$x=explode("=",$value);   %					$extra[strtolower($x[0])]=$x[1];   				}   			}   			else   			{   -				$y = explode("=",$request['extra_data']);   $				$extra[strtolower($y[0])]=$y[1];   			}   		}   		   #		//grab data about the web service       �		$method = mysqli_query($link,"select request_type, WebserviceURI, MethodName, CallType from vicidial_webservices_methods where MethodRecnum = ".$request['method_id']);    		chkMysql("004",$method,$link);   (		$method = mysqli_fetch_assoc($method);       �		$methodParams = mysqli_query($link,"select ParameterName, MappedField, CustomValue,isCustomField,ParameterFullName,allowMapAndStatic from vicidial_webservices_methods_params where MethodRecnum = ".$request['method_id']);   &		chkMysql("005",$methodParams,$link);   		$row2 = array();   
	    $i=0;   5	    while ($row = mysqli_fetch_assoc($methodParams))   	    {   	        $row2[$i] = $row;   		    $i++;   		}   		$methodParams = $row2;   		       		$callflowTable = "";   		$primaryKey = "";   		$vpdSwitch = false;   		$cloudSwitch = false;   		$vacdSwitch = false;       .		switch (strtoupper($method['request_type']))   		{   			case "ROUTING":   				$cloudSwitch =true;   /				$callflowTable = "vicidial_cloud_callflow";   				$primaryKey = "UNIQUEID";   
				break;   			case "VACD";   				$cloudSwitch =true;   .				$callflowTable = "vicidial_vacd_callflow";   				$primaryKey = "UNIQUEID";   				$vacdSwitch = true;   
				break;   			case "VPD";   				$vpdSwitch = true;   				$primaryKey = "lead_id";   (				$callflowTable = "vicidial_list";			   
				break;   		}       5		//look up each of the params in the callflow tables   		$params = array();   *		$httpUrl = $method['WebserviceURI']."?";       		if($vpdSwitch)   		{   !			$data = getLeadData($request);   		}           		if($cloudSwitch)   		{   2			if($callflowTable == "vicidial_cloud_callflow")   			{   				$sql = "   					select *    					from ".$callflowTable." v   M					LEFT OUTER JOIN vicidial_cloud_profiles prof ON prof.prof_id = v.prof_id   L					LEFT OUTER JOIN vicidial_inbound_dids dids ON v.DNIS = dids.did_pattern   8					where ".$primaryKey." = '".$request['record_id']."'   					";   %				$data = mysqli_query($link,$sql);    				chkMysql("007",$data,$link);   &				$data = mysqli_fetch_assoc($data);       �				$dest = mysqli_query($link,"select dest_name from vicidial_cloud_dests where dest_id='".$data['last_connected_dest_id']."'");    				chkMysql("026",$dest,$link);   &				$dest = mysqli_fetch_assoc($dest);       8				$data['last_destination_name'] = $dest['dest_name'];       ?				if($data['ANI2']==61||$data['ANI2']==62||$data['ANI2']==63)   					$data['ANI2'] = "Yes";   				else   					$data['ANI2'] = "No";	       +				if($data['last_connected_prof_id']!="")   				{   �					$prof = mysqli_query($link,"select prof_name from vicidial_cloud_profiles where prof_id='".$data['last_connected_prof_id']."'");   !					chkMysql("027",$prof,$link);   3	                $prof = mysqli_fetch_assoc($prof);   <					$data['last_connected_prof_name'] = $prof['prof_name'];   				}   				else   				{   ,					$data['last_connected_prof_name'] = "";   				}   '				$data['destid'] = $data['DEST_ID'];       a				//stop warnings when customer use field that require extra data but extra data doesn;t exists   "				if($request['extra_data']=="")   				{   &					$data['destination_number'] = "";   "					$data['destination_id'] = "";   				}        				$data['dtmf_captured'] = "";       \				$sql = "select name,data from vicidial_cloud_dtmf_data where uid='$request[record_id]'";   $				$chk = mysqli_query($link,$sql);   				chkMysql("036",$chk,$link);   				   				$row2 = array();   					$i=0;   +				while ($row = mysqli_fetch_assoc($chk))   				{   					$row2[$i] = $row;   
					$i++;   				}       				if($i!=0)   				{   					$captured = "";   					foreach($row2 as $row)   					{   3						$captured.=$row['name'].":".$row['data']."|";   					}   9					$captured = substr($captured,0,strlen($captured)-1);   &					$data['dtmf_captured']=$captured;   				}   			}   			else//VACD   			{   				$sql ="   &					select *,u.email as agent_email     					from ".$callflowTable." v   V					LEFT OUTER JOIN vicidial_users u ON CONVERT(v.connected_agent_num, CHAR) = u.user   L					LEFT OUTER JOIN vicidial_inbound_dids dids ON v.DNIS = dids.did_pattern   8					where ".$primaryKey." = '".$request['record_id']."'   					";       %				$data = mysqli_query($link,$sql);    				chkMysql("015",$data,$link);   /				$data = mysqli_fetch_assoc($data);									       +				$request['lead_id'] = $data['lead_id'];       "				$lead = getLeadData($request);       %				$data = array_merge($data,$lead);       ?				if($data['ANI2']==61||$data['ANI2']==62||$data['ANI2']==63)   					$data['ANI2'] = "Yes";   				else   					$data['ANI2'] = "No";        				if($data['status_name']=="")   				{   l					$status = mysqli_query($link,"select name from vicidial_vacd_statuses where id='".$data['status']."'");   #					chkMysql("043",$status,$link);   (					$res = mysqli_fetch_assoc($status);   )					$data['status_name'] = $res['name'];   				}   			}   		}       ;		$data['web_call_date'] = date("Y-m-d")."T".date("H:i:s");       #		foreach ($data as $key => $value)   		{   			$t = strtolower($key);   			$data[$t] = $value;   		}       (		for($i=0;$i<count($methodParams);$i++)   		{   A	        $c = strtoupper($methodParams[$i]['allowMapAndStatic']);   	        if($c=="Y")   		        $c=true;   
		    else   		       $c=false;   																			   *			if($methodParams[$i]['MappedField']!=0)   			{   >				if(strtoupper($methodParams[$i]['isCustomField'])=="NONE")   				{   �					$fields = mysqli_query($link,"select field_name from vicidial_webservices_fields where field_id = ".$methodParams[$i]['MappedField']);   #					chkMysql("006",$fields,$link);   1	    	    	$fields = mysqli_fetch_assoc($fields);   				}   E				elseif(strtoupper($methodParams[$i]['isCustomField'])=="INBOUND")   				{   					$sql = "   /					select value from vicidial_inbound_dids_cf   `					where field_id ='".$methodParams[$i]['MappedField']."' and did_id = '".$data['did_id']."'";   .					$customFields = mysqli_query($link,$sql);   )					chkMysql("018",$customFields,$link);   7					$customFields = mysqli_fetch_assoc($customFields);       @					$fields['field_name'] = $methodParams[$i]['ParameterName'];   ,					if(!isset($customFields['value'])&&!$c)   					{   ,						$methodParams[$i]['CustomValue'] = "";   					}   M					elseif($c&&(!isset($customFields['value'])||$customFields['value']==""))   					{   &						//skip, use default custom value   					}   						else   					{   @						$methodParams[$i]['CustomValue'] = $customFields['value'];   					}   					unset($customFields);   				}   			}   			else   			{   ?				$fields['field_name'] = $methodParams[$i]['ParameterName'];   			}       =			$fields['field_name'] = strtolower($fields['field_name']);       �//			mylog("field: ".$fields['field_name']."  data: ".$data[$fields['field_name']]." MAPPED: ".$methodParams[$i]['MappedField']);       +			if(!isset($data[$fields['field_name']]))   &				$data[$fields['field_name']] = "";       '			$tmp = $data[$fields['field_name']];       B			//check to see if the data is in the extra field of the request   �			if($methodParams[$i]['MappedField']!=0&&strtoupper($methodParams[$i]['isCustomField'])!="INBOUND"&&!($c&&$data[$fields['field_name']]==""))   			{   ,				if(isset($extra[$fields['field_name']]))   				{   @					$data[$fields['field_name']]=$extra[$fields['field_name']];   T					mylog("extra field->".$fields['field_name']."=".$extra[$fields['field_name']]);   				}       (				if($data[$fields['field_name']]!="")   				{   				/*   5					if(strtoupper($fields['field_name'])=="PROF_ID")   					{   �						$name = mysqli_query($link,"select prof_name from vicidial_cloud_profiles where prof_id = ".$data[$fields['field_name']]);   +	    	    	    chkMysql("010",$name,$link);   1	        	    	$name = mysqli_fetch_assoc($name);   8						$data[$fields['field_name']] = $name['prof_name'];   					}   					*/   5					if(strtoupper($fields['field_name'])=="DEST_ID")   					{   }						$name = mysqli_query($link,"select dest_name from vicidial_cloud_dests where dest_id = ".$data[$fields['field_name']]);   .	            	    chkMysql("011",$name,$link);   4    	            	$name = mysqli_fetch_assoc($name);   D	        	        $data[$fields['field_name']] = $name['dest_name'];   					}   				}   			}   			else   			{   <				if(strpos($methodParams[$i]['CustomValue'],"[")!==false)   				{   L					$tmpValues = explodeX(array("[","]"),$methodParams[$i]['CustomValue']);   (					for($z=0;$z<count($tmpValues);$z++)   					{   I						if($z%2!=0)//if its an odd number its a field to populate with data   						{   t							if(ctype_digit($tmpValues[$z]))//if all characters are numeric, then we are using meta data based off did/tfn   							{   								$sql = "   3									select value from vicidial_inbound_dids_cf   R									where field_id ='".$tmpValues[$z]."' and did_id = '".$data['did_id']."'";   1								$customFields = mysqli_query($link,$sql);   ,								chkMysql("034",$customFields,$link);   :								$customFields = mysqli_fetch_assoc($customFields);   0								$tmpValues[$z] = $customFields['value'];   							}   l							elseif(array_key_exists(strtolower($tmpValues[$z]),$data))//check to see if the field exists in array   							{   ;								$tmpValues[$z] = $data[strtolower($tmpValues[$z])];   							}   1							else//if the field in brackets dont exists   							{   0								$tmpValues[$z] = "[".$tmpValues[$z]."]";   							}   						}   					}   ;					$data[$fields['field_name']] = implode("",$tmpValues);       				}   				else   				{   C					$data[$fields['field_name']]=$methodParams[$i]['CustomValue'];   				}   			}       			mylog("field: ".$fields['field_name']."  data: ".$data[$fields['field_name']]." MAPPED: ".$methodParams[$i]['MappedField']);       *			switch(strtoupper($method['CallType']))   			{   				case "SOAP":   o					if(strtoupper($methodParams[$i]['ParameterFullName'])=="NULL"||$methodParams[$i]['ParameterFullName']=="")   					{   R						$params[$methodParams[$i]['ParameterName']] = $data[$fields['field_name']];	   					}   						else   					{   G						$webObject = explode(".",$methodParams[$i]['ParameterFullName']);   						$arr = &$complexObject;   						$count 	= 0;   			   $						foreach ($webObject as $key) {   -							if ($count < (count($webObject) - 1)){    								if (!isset($arr[$key])){   									$arr[$key] = array();   
								}    								$arr = &$arr[$key];   							}   							else{   |								$parameterValue = preg_replace('/[^(\x20-\x7F)]*/','',$data[$fields['field_name']]);//strip off non ascii characters   				   0								// If the value is empty ... don't care!   -								if (empty($parameterValue)) continue;   				   								// Attribute the value   %								$arr[$key] = $parameterValue;   								$arr = &$arr[$key];   							}   							   						   	$count ++;   						}   					}   					break;   				case "HTTP GET":   				case "HTTP POST":   L					$data[$fields['field_name']] = urlencode($data[$fields['field_name']]);   Y					$httpUrl .= $methodParams[$i]['ParameterName']."=".$data[$fields['field_name']]."&";   					break;   			}   '			$data[$fields['field_name']] = $tmp;   		}       		   )		switch(strtoupper($method['CallType']))   	        {   	        case "SOAP":   0				foreach($complexObject as $key => $topLevel)   				{   					$params[$key] = $topLevel;   				}   �		        $results = soapCall($method['WebserviceURI'],$params,$method['MethodName'],$request['timeout'], $request['method_id'], $request['lead_id']);   (				$httpUrl = $method['WebserviceURI'];   	    	    break;   		    case "HTTP GET":   G				$httpUrl = substr($httpUrl,0,strlen($httpUrl)-1);//strip off last &   A	              $results = curlCall($httpUrl,$request['timeout']);                   break;       		    case "HTTP POST":   G				$httpUrl = substr($httpUrl,0,strlen($httpUrl)-1);//strip off last &   ;				$results = curlCall($httpUrl,$request['timeout'],true);   	            break;   	    }       		   )		//check to see if the webservice failed   .		if(!$results['success'])//webservicce failed   		{  W			$updateStatus = mysqli_query($link,"update vicidial_webservices_requests set pending = 1, next_retry_dts = DATE_ADD(NOW(), INTERVAL ".$request['retry_interval']." SECOND), error_reason = '".addslashes($results['output'])."', response_dts = NOW(), final_url = '$httpUrl', priority = priority + 1 where request_id = ".$request['request_id']);   '			chkMysql("008",$updateStatus,$link);       +			if($request['write_response_file']=="Y")               {   R				//file_put_contents($responsePath."/".$request['unique_id'].".resp","FAILED");   �				$insertResp =  mysqli_query($link,"insert into vicidial_webservices_responses (response_date,response,uniqueid,request_id) values (NOW(),'FAILED','$request[record_id]','$request[request_id]')");   &				chkMysql("025",$insertResp,$link);   			}       		}    		else//webservice was a success   		{   �			$updateStatus = mysqli_query($link,"update vicidial_webservices_requests set response_dts = NOW(), response = '".addslashes($results['output'])."', status = 'COMPLETE', final_url = '$httpUrl' where request_id = ".$request['request_id']);	   (			 chkMysql("009",$updateStatus,$link);   ,			 if($request['write_response_file']=="Y")   			 {   \//				file_put_contents($responsePath."/".$request['unique_id'].".resp",$results['output']);   �				$insertResp =  mysqli_query($link,"insert into vicidial_webservices_responses (response_date,response,uniqueid,request_id) values (NOW(),'$results[output]','$request[record_id]','$request[request_id]')");   2                chkMysql("026",$insertResp,$link);   								   			 }       		}           *	}//end else if retries were not exhausted   	}//end if $request!=""   	else   	{   		mysqli_commit($link);   !		mysqli_autocommit($link, TRUE);   	}	           	usleep(250000);//sleep 250ms	   //	$idleCount++;   &	if($idleCount>=1200)//idle for 5 mins   	{   		$idleCount=0;   		mysqli_close($link);   		$dbConnected = false;   	}                   exit;       ?>    5�_�                          ����                                                                                                                                                                                                                                                                                                                                              v       Xm@d     �      �      ?	//check to see if there is a kill file, if so end this process   )	if(file_exists("/tmp/webservices.$PID"))   	{   "		unlink("/tmp/webservices.$PID");   %		mylog("Kill file found, Stopping");   		break;   	}5�_�                           ����                                                                                                                                                                                                                                                                                                                                              v       Xm@e     �              	5�_�                   �       ����                                                                                                                                                                                                                                                                                                                                              v       Xm@�     �  �  �          *	}//end else if retries were not exhausted5�_�                   ;       ����                                                                                                                                                                                                                                                                                                                                              v       Xm@�     �  :  ;          		{5�_�                   :       ����                                                                                                                                                                                                                                                                                                                                              v       Xm@�     �  9  :          		else5�_�                   8       ����                                                                                                                                                                                                                                                                                                                                              v       Xm@�     �  8  :  �            �  8  :  �    5�_�      	             9       ����                                                                                                                                                                                                                                                                                                                                              v       Xm@�    �  8  :  �            exit()5�_�      
           	  9   
    ����                                                                                                                                                                                                                                                                                                                                              v       Xm@�     �  8  :  �            exit();5�_�   	               
  9   
    ����                                                                                                                                                                                                                                                                                                                                              v       Xm@�    �  8  :  �            exit);5��