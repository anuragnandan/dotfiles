Vim�UnDo� ;�<f�}Ԙ�0k�n!KJ� E�k4+ڐ\�7�7                                    W���    _�                             ����                                                                                                                                                                                                                                                                                                                                                             W���     �                 <?php   (	require_once("../../prosodie_def.php");   	require("Datasource.php");   	require_once("audio.php");	   	   G	if (!defined("SyslogSeverity")) define("SyslogSeverity", LOG_WARNING);   	   �	mylog(LOG_INFO, "File: " . __FILE__ . ": REQUEST  variable [" . print_r($_REQUEST, true) . "]\n, FILES [ " . print_r($_FILES, true) ."]");   		   	   +	//$DB = "dev__maxdial__client_web__mysql";   ,	$MAXIMUM_FILESIZE = 1024 * 100000; // 100MB   ?	$MAXIMUM_FILE_COUNT = 100; // keep maximum 100 files on server   /	$lfname = $lfsize = $lfcreated = $lftype = "";   L	$error = $luser_id = $lclientnum = $lmaster_account = $audio_language = "";   	if (isset($_REQUEST["fname"]))   		$lfname = $_REQUEST["fname"];   	else   		$error .= "Missing fname ";   		   	if (isset($_REQUEST["fsize"]))   		$lfsize = $_REQUEST["fsize"];   	else   		$error .= "Missing fize ";   		   "	if (isset($_REQUEST["fcreated"]))   %		$lfcreated = $_REQUEST["fcreated"];   	else    		$error .= "Missing fcreated ";   		   	if (isset($_REQUEST["ftype"]))   		$lftype = $_REQUEST["ftype"];   	else   		$error .= "Missing ftype ";   		   #	if (isset($_REQUEST["clientnum"]))   '		$lclientnum = $_REQUEST["clientnum"];   	else   		$error .= "Missing account ";   		   	if (isset($_REQUEST["ANI"])){   		$ANI = $_REQUEST["ANI"];   	}else{   		$error .= "Missing ANI ";   	}   		   '	if(isset($_FILES['Filedata']['name']))   .		$tempFilename = $_FILES['Filedata']['name'];       	if ($error != "")   	{   		send_error($error);   		exit;   	}       	$conn = init();   $	$conn->_connect_by_type('PRIMARY');   	if ($conn->errno != 0)    	{   		send_error($conn->err);   			return;   	}   	   	/* Get Account info */   		   U	$sql = "SELECT  shard_num,schema_name FROM atm_globals.tbl_vpd_account_info where ";   !	$sql .= "clientnum=$lclientnum";   !	$result = $conn->_execute($sql);   	if ($conn->errno != 0)    	{   		send_error($conn->err);   			return;   	}   (	if ($row = $conn->_nextRowObj($result))   	{   		$shard_num = $row->shard_num;   #		$schema_name = $row->schema_name;   		$conn->_free_result($result);   	}   	else   	{   .		send_error("Account $lclientnum not found");   			return;   	}       	$conn = new Datasource();   0	$conn->_connect_by_type('PRIMARY', $client_id);   	if ($conn->errno != 0)    	{   .		send_error($conn->err . print_r($dsn,TRUE));   			return;   	}       	/* Get system_settings info */   I	$sql = "SELECT recordings_url,recordings_base_dir FROM system_settings";   !	$result = $conn->_execute($sql);   	   	if ($conn->errno != 0)    	{   		send_error($conn->err);   			return;   	}   (	if ($row = $conn->_nextRowObj($result))   	{   )		$recordings_url = $row->recordings_url;   3		$recordings_base_dir = $row->recordings_base_dir;   		$conn->_free_result($result);   	}   	else   	{   .		send_error("Account $lclientnum not found");   			return;   	}   	   		//INSERT   7	$sql  = " INSERT INTO asterisk.recording_import_log ";   +	$sql .= " (client_id, start_time, ANI) ";    3	$sql .= " VALUES ('$lclientnum', NOW(), '$ANI') ";   	   c	//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The sql is [" . print_r($sql, true) . "]");   !	$result = $conn->_execute($sql);   	if ($conn->errno != 0)    	{   		send_error($conn->err);   			return;   	}	   	   	$ID = $conn->_insert_id();   	   #	// Now Move the file into position   	$TMP_DIR = TEMP_FILE_DIR;   \	$FINAL_DIR = "$recordings_base_dir/$lclientnum/".date('Y')."/".date('m')."/".date('d')."/";   %	$size = $_FILES['Filedata']['size'];   	   	$NoExtenName = "imp_".$ID;   !	$fin_name = $NoExtenName.".wav";   $	$fin_path = $FINAL_DIR . $fin_name;   	   e	$LOC_FINAL_DIR = "$recordings_url/$lclientnum/".date('Y')."/".date('m')."/".date('d')."/".$fin_name;   	   #	$exists = file_exists($FINAL_DIR);   	if(!$exists){   .		$mkdirreturn = mkdir($FINAL_DIR,0777,false);   	}   	   d	//mylog(LOG_WARNING, "File '$lfname' is  ($lfsize bytes) to be uploaded. On Linux, size is $size");   	   \	if ( ($_FILES['Filedata']['size'] <= $MAXIMUM_FILESIZE) && ($lfsize <= $MAXIMUM_FILESIZE) )   	{   �		//mylog(LOG_INFO, "File: " . __FILE__ . ":  >>>>>>>>><<<<<<<<<<<<<<<<<<XXXXXXXXXXX\n".$conn->_escape($_FILES['Filedata']['name']).print_r($conn,true));   		$now = date("YmdHis");   		   O		if (preg_match("/[^a-zA-Z0-9_.-]/", $_FILES['Filedata']['name'], $matches)) {   g			mylog(LOG_INFO, "File: " . __FILE__ . ":  File name has special characters".print_r($matches,true));   !			foreach ($matches as $splchar)   [				$_FILES['Filedata']['name'] = str_replace("$splchar", "", $_FILES['Filedata']['name']);   		}   U// 		$_FILES['Filedata']['name'] = str_replace("'", "", $_FILES['Filedata']['name']);   8// 		str_replace("\.", "", $_FILES['Filedata']['name']);   V 		$tmp_name = $now . "_$luser_id" . "_" .$conn->_escape($_FILES['Filedata']['name']);   %		$tmp_path = ($TMP_DIR . $tmp_name);   0		$fileToMove = $_FILES['Filedata']['tmp_name'];   c		mylog(LOG_INFO, "File: " . __FILE__ . ": calling [move_uploaded_file($fileToMove, $tmp_path) ]");   $		//Move file to temporary directory   4		$ret = move_uploaded_file($fileToMove, $tmp_path);   ;		mylog(LOG_INFO, "File: " . __FILE__ . ": ret = [$ret]");	   	}   	else    	{   ~		//mylog(LOG_WARNING, "File '$lfname' is too big ($lfsize bytes) to be uploaded. Max file size is $MAXIMUM_FILESIZE bytes.");   x		send_error("File '$audio_name' is too big ($lfsize bytes) to be uploaded. Max file size is $MAXIMUM_FILESIZE bytes.");   			return;   	}   	   	//If move fails   	if ($ret == FALSE)   	{   N		//mylog(LOG_WARNING, "File '$fileToMove' can't be moved to: " .  $tmp_path);   8		send_error("File: '$fileToMove' can't be moved to: ");   		exit;   	}   			   3	if (strtoupper(substr($tmp_path, -3, 3)) == "MP3")   	{   -		// Convert/move WAV file to final directory   �		$cmd = "/usr/bin/mpg123 -b 10000 -s --stereo '$tmp_path' | /usr/bin/sox -t raw -r 44100 -s -w -c2 - -t wav -r 8000 -c 1 -w $fin_path"; //convert to .pcm format and Rename file   	}   7	else if(strtoupper(substr($tmp_path, -3, 3)) == "WAV")   	{   -		// Convert/move WAV file to final directory   w		//$cmd = "/usr/local/bin/sox -twav '$tmp_path' -tul -b -r8000  '$fin_path'"; //convert to .pcm format and Rename file   B		$cmd = "/usr/local/bin/sox '$tmp_path' -t wav -b16 '$fin_path'";   o		//$cmd = "/usr/bin/sox -twav $tmp_path -tul -b8 -r8000  $fin_path"; //convert to .pcm format and Rename file	   	}   	   	else    	{   (		//Move the PCM file to final directory   B		$cmd = "/usr/local/bin/sox '$tmp_path' -t wav -b16 '$fin_path'";   		   	}   	   P	mylog(LOG_INFO, "FILE ".__FILE__.":" . __LINE__ ." Executing command [$cmd]");	   #	$ret = exec($cmd,$output,$retval);   v	mylog(LOG_INFO, "FILE ".__FILE__.":" . __LINE__ ." Executing command [$cmd] = [".print_r($output,true)."], $retval");   	   	$stat = stat($fin_path);   $	$final_audio_size = $stat['size'];    *	$audio_duration = $final_audio_size/8000;   1	//mylog(LOG_INFO, "Executing command: [$cmd].");   E	//mylog(LOG_INFO, "Stat command and the file size is: $stat[size]");   	// If convert/move fails   	if ($retval)   	{   �		mylog(LOG_WARNING, "AFTER Executing command, there was an ERROR: " . __FILE__ . ": ret = [$ret], outputFile= ".print_r($output,TRUE).", retval=".print_r($retval,TRUE).", FILE: '$tmp_path' can't be converted/moved to: ".$fin_path);	   +		send_error("$lfname - Incorrect format");   			return;   	}       	//UPDATE file name   P	$sql  = " update asterisk.recording_import_log set location = '$LOC_FINAL_DIR',   q	filename = '$NoExtenName',length_in_sec = ROUND($audio_duration,0), length_in_min = ROUND($audio_duration/60,2)    	where recording_id = '$ID'";   	   !	$result = $conn->_execute($sql);   	if ($conn->errno != 0)    	{   		send_error($conn->err);   			return;   	}	   	   &	//RETURN response to calling proogram   6	send_response("$lfname - uploaded successfully",$ID);       	   	function init()   	{   		$conn = new Datasource();   		return($conn);   	}   	   	   *	function send_error($reason, $status = 0)   	{   		print "<response>\n";   %		print"	<status>$status</status>\n";   -		print"	<error_text>$reason</error_text>\n";   		print "</response>\n";   	}   	   	   -	function send_response($reason,$audio_id='')   	{   		print "<response>\n";    		print "	<status>1</status>\n";   .		print "	<error_text>$reason</error_text>\n";   -		print"	<audio_id>$audio_id</audio_id>\n";		   		print "</response>\n";   	}   	   0	function mylog($severity=LOG_INFO, $logMessage)   	{   1		$errorType = array(	LOG_EMERG=>		'EMERGENCY  ',   "							LOG_ALERT=>		'ALERT      ',   !							LOG_CRIT=>		'CRITICAL   ',    							LOG_ERR=>		'ERROR      ',   #							LOG_WARNING=>	'LOG_WARNING',   "							LOG_NOTICE=>	'NOTICE     ',   "							LOG_INFO=>		'INFO       ',    							LOG_DEBUG=>		'DEBUG		');   	   		// Only log on RnD Web Server    		$machineName = php_uname('n');   ,		if ((substr($machineName, 0, 3) == "fld"))   		{   r			error_log(Date("Y-m-d H:i:s") . "[$errorType[$severity]] : $logMessage\n", 3, '/var/log/atmosphere_audio.log');   		}   		   "		if ($severity <= SyslogSeverity)   		{   "			syslog($severity, $logMessage);   		}   	}       ?>5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             W���     �                (	require_once("../../prosodie_def.php");5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W���     �               �            5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W���     �                require_once()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W���     �                require_once(dirname())5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             W���     �              #  require_once(dirname($_SERVER[]))5�_�                       !    ����                                                                                                                                                                                                                                                                                                                                                             W���     �              %  require_once(dirname($_SERVER['']))5�_�      	                 5    ����                                                                                                                                                                                                                                                                                                                                                             W���    �              7  require_once(dirname($_SERVER['SCRIPT_FILENAME'])."")5�_�      
           	           ����                                                                                                                                                                                                                                                                                                                                                             W���     �                 <?php   I  require_once(dirname($_SERVER['SCRIPT_FILENAME'])."/prosodie_def.php");   	require("Datasource.php");   	require_once("audio.php");	   	   G	if (!defined("SyslogSeverity")) define("SyslogSeverity", LOG_WARNING);   	   �	mylog(LOG_INFO, "File: " . __FILE__ . ": REQUEST  variable [" . print_r($_REQUEST, true) . "]\n, FILES [ " . print_r($_FILES, true) ."]");   		   	   +	//$DB = "dev__maxdial__client_web__mysql";   ,	$MAXIMUM_FILESIZE = 1024 * 100000; // 100MB   ?	$MAXIMUM_FILE_COUNT = 100; // keep maximum 100 files on server   /	$lfname = $lfsize = $lfcreated = $lftype = "";   L	$error = $luser_id = $lclientnum = $lmaster_account = $audio_language = "";   	if (isset($_REQUEST["fname"]))   		$lfname = $_REQUEST["fname"];   	else   		$error .= "Missing fname ";   		   	if (isset($_REQUEST["fsize"]))   		$lfsize = $_REQUEST["fsize"];   	else   		$error .= "Missing fize ";   		   "	if (isset($_REQUEST["fcreated"]))   %		$lfcreated = $_REQUEST["fcreated"];   	else    		$error .= "Missing fcreated ";   		   	if (isset($_REQUEST["ftype"]))   		$lftype = $_REQUEST["ftype"];   	else   		$error .= "Missing ftype ";   		   #	if (isset($_REQUEST["clientnum"]))   '		$lclientnum = $_REQUEST["clientnum"];   	else   		$error .= "Missing account ";   		   	if (isset($_REQUEST["ANI"])){   		$ANI = $_REQUEST["ANI"];   	}else{   		$error .= "Missing ANI ";   	}   		   '	if(isset($_FILES['Filedata']['name']))   .		$tempFilename = $_FILES['Filedata']['name'];       	if ($error != "")   	{   		send_error($error);   		exit;   	}       	$conn = init();   $	$conn->_connect_by_type('PRIMARY');   	if ($conn->errno != 0)    	{   		send_error($conn->err);   			return;   	}   	   	/* Get Account info */   		   U	$sql = "SELECT  shard_num,schema_name FROM atm_globals.tbl_vpd_account_info where ";   !	$sql .= "clientnum=$lclientnum";   !	$result = $conn->_execute($sql);   	if ($conn->errno != 0)    	{   		send_error($conn->err);   			return;   	}   (	if ($row = $conn->_nextRowObj($result))   	{   		$shard_num = $row->shard_num;   #		$schema_name = $row->schema_name;   		$conn->_free_result($result);   	}   	else   	{   .		send_error("Account $lclientnum not found");   			return;   	}       	$conn = new Datasource();   0	$conn->_connect_by_type('PRIMARY', $client_id);   	if ($conn->errno != 0)    	{   .		send_error($conn->err . print_r($dsn,TRUE));   			return;   	}       	/* Get system_settings info */   I	$sql = "SELECT recordings_url,recordings_base_dir FROM system_settings";   !	$result = $conn->_execute($sql);   	   	if ($conn->errno != 0)    	{   		send_error($conn->err);   			return;   	}   (	if ($row = $conn->_nextRowObj($result))   	{   )		$recordings_url = $row->recordings_url;   3		$recordings_base_dir = $row->recordings_base_dir;   		$conn->_free_result($result);   	}   	else   	{   .		send_error("Account $lclientnum not found");   			return;   	}   	   		//INSERT   7	$sql  = " INSERT INTO asterisk.recording_import_log ";   +	$sql .= " (client_id, start_time, ANI) ";    3	$sql .= " VALUES ('$lclientnum', NOW(), '$ANI') ";   	   c	//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The sql is [" . print_r($sql, true) . "]");   !	$result = $conn->_execute($sql);   	if ($conn->errno != 0)    	{   		send_error($conn->err);   			return;   	}	   	   	$ID = $conn->_insert_id();   	   #	// Now Move the file into position   	$TMP_DIR = TEMP_FILE_DIR;   \	$FINAL_DIR = "$recordings_base_dir/$lclientnum/".date('Y')."/".date('m')."/".date('d')."/";   %	$size = $_FILES['Filedata']['size'];   	   	$NoExtenName = "imp_".$ID;   !	$fin_name = $NoExtenName.".wav";   $	$fin_path = $FINAL_DIR . $fin_name;   	   e	$LOC_FINAL_DIR = "$recordings_url/$lclientnum/".date('Y')."/".date('m')."/".date('d')."/".$fin_name;   	   #	$exists = file_exists($FINAL_DIR);   	if(!$exists){   .		$mkdirreturn = mkdir($FINAL_DIR,0777,false);   	}   	   d	//mylog(LOG_WARNING, "File '$lfname' is  ($lfsize bytes) to be uploaded. On Linux, size is $size");   	   \	if ( ($_FILES['Filedata']['size'] <= $MAXIMUM_FILESIZE) && ($lfsize <= $MAXIMUM_FILESIZE) )   	{   �		//mylog(LOG_INFO, "File: " . __FILE__ . ":  >>>>>>>>><<<<<<<<<<<<<<<<<<XXXXXXXXXXX\n".$conn->_escape($_FILES['Filedata']['name']).print_r($conn,true));   		$now = date("YmdHis");   		   O		if (preg_match("/[^a-zA-Z0-9_.-]/", $_FILES['Filedata']['name'], $matches)) {   g			mylog(LOG_INFO, "File: " . __FILE__ . ":  File name has special characters".print_r($matches,true));   !			foreach ($matches as $splchar)   [				$_FILES['Filedata']['name'] = str_replace("$splchar", "", $_FILES['Filedata']['name']);   		}   U// 		$_FILES['Filedata']['name'] = str_replace("'", "", $_FILES['Filedata']['name']);   8// 		str_replace("\.", "", $_FILES['Filedata']['name']);   V 		$tmp_name = $now . "_$luser_id" . "_" .$conn->_escape($_FILES['Filedata']['name']);   %		$tmp_path = ($TMP_DIR . $tmp_name);   0		$fileToMove = $_FILES['Filedata']['tmp_name'];   c		mylog(LOG_INFO, "File: " . __FILE__ . ": calling [move_uploaded_file($fileToMove, $tmp_path) ]");   $		//Move file to temporary directory   4		$ret = move_uploaded_file($fileToMove, $tmp_path);   ;		mylog(LOG_INFO, "File: " . __FILE__ . ": ret = [$ret]");	   	}   	else    	{   ~		//mylog(LOG_WARNING, "File '$lfname' is too big ($lfsize bytes) to be uploaded. Max file size is $MAXIMUM_FILESIZE bytes.");   x		send_error("File '$audio_name' is too big ($lfsize bytes) to be uploaded. Max file size is $MAXIMUM_FILESIZE bytes.");   			return;   	}   	   	//If move fails   	if ($ret == FALSE)   	{   N		//mylog(LOG_WARNING, "File '$fileToMove' can't be moved to: " .  $tmp_path);   8		send_error("File: '$fileToMove' can't be moved to: ");   		exit;   	}   			   3	if (strtoupper(substr($tmp_path, -3, 3)) == "MP3")   	{   -		// Convert/move WAV file to final directory   �		$cmd = "/usr/bin/mpg123 -b 10000 -s --stereo '$tmp_path' | /usr/bin/sox -t raw -r 44100 -s -w -c2 - -t wav -r 8000 -c 1 -w $fin_path"; //convert to .pcm format and Rename file   	}   7	else if(strtoupper(substr($tmp_path, -3, 3)) == "WAV")   	{   -		// Convert/move WAV file to final directory   w		//$cmd = "/usr/local/bin/sox -twav '$tmp_path' -tul -b -r8000  '$fin_path'"; //convert to .pcm format and Rename file   B		$cmd = "/usr/local/bin/sox '$tmp_path' -t wav -b16 '$fin_path'";   o		//$cmd = "/usr/bin/sox -twav $tmp_path -tul -b8 -r8000  $fin_path"; //convert to .pcm format and Rename file	   	}   	   	else    	{   (		//Move the PCM file to final directory   B		$cmd = "/usr/local/bin/sox '$tmp_path' -t wav -b16 '$fin_path'";   		   	}   	   P	mylog(LOG_INFO, "FILE ".__FILE__.":" . __LINE__ ." Executing command [$cmd]");	   #	$ret = exec($cmd,$output,$retval);   v	mylog(LOG_INFO, "FILE ".__FILE__.":" . __LINE__ ." Executing command [$cmd] = [".print_r($output,true)."], $retval");   	   	$stat = stat($fin_path);   $	$final_audio_size = $stat['size'];    *	$audio_duration = $final_audio_size/8000;   1	//mylog(LOG_INFO, "Executing command: [$cmd].");   E	//mylog(LOG_INFO, "Stat command and the file size is: $stat[size]");   	// If convert/move fails   	if ($retval)   	{   �		mylog(LOG_WARNING, "AFTER Executing command, there was an ERROR: " . __FILE__ . ": ret = [$ret], outputFile= ".print_r($output,TRUE).", retval=".print_r($retval,TRUE).", FILE: '$tmp_path' can't be converted/moved to: ".$fin_path);	   +		send_error("$lfname - Incorrect format");   			return;   	}       	//UPDATE file name   P	$sql  = " update asterisk.recording_import_log set location = '$LOC_FINAL_DIR',   q	filename = '$NoExtenName',length_in_sec = ROUND($audio_duration,0), length_in_min = ROUND($audio_duration/60,2)    	where recording_id = '$ID'";   	   !	$result = $conn->_execute($sql);   	if ($conn->errno != 0)    	{   		send_error($conn->err);   			return;   	}	   	   &	//RETURN response to calling proogram   6	send_response("$lfname - uploaded successfully",$ID);       	   	function init()   	{   		$conn = new Datasource();   		return($conn);   	}   	   	   *	function send_error($reason, $status = 0)   	{   		print "<response>\n";   %		print"	<status>$status</status>\n";   -		print"	<error_text>$reason</error_text>\n";   		print "</response>\n";   	}   	   	   -	function send_response($reason,$audio_id='')   	{   		print "<response>\n";    		print "	<status>1</status>\n";   .		print "	<error_text>$reason</error_text>\n";   -		print"	<audio_id>$audio_id</audio_id>\n";		   		print "</response>\n";   	}   	   0	function mylog($severity=LOG_INFO, $logMessage)   	{   1		$errorType = array(	LOG_EMERG=>		'EMERGENCY  ',   "							LOG_ALERT=>		'ALERT      ',   !							LOG_CRIT=>		'CRITICAL   ',    							LOG_ERR=>		'ERROR      ',   #							LOG_WARNING=>	'LOG_WARNING',   "							LOG_NOTICE=>	'NOTICE     ',   "							LOG_INFO=>		'INFO       ',    							LOG_DEBUG=>		'DEBUG		');   	   		// Only log on RnD Web Server    		$machineName = php_uname('n');   ,		if ((substr($machineName, 0, 3) == "fld"))   		{   r			error_log(Date("Y-m-d H:i:s") . "[$errorType[$severity]] : $logMessage\n", 3, '/var/log/atmosphere_audio.log');   		}   		   "		if ($severity <= SyslogSeverity)   		{   "			syslog($severity, $logMessage);   		}   	}       ?>5�_�   	              
           ����                                                                                                                                                                                                                                                                                                                                                             W���     �                I  require_once(dirname($_SERVER['SCRIPT_FILENAME'])."/prosodie_def.php");5�_�   
                        ����                                                                                                                                                                                                                                                                                                                                                             W���     �               �            5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W���     �                require_once()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W���     �                require_once(())5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W���     �                require_once(($_SERVER[]))5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W���     �                require_once(($_SERVER['']))5�_�                        ,    ����                                                                                                                                                                                                                                                                                                                                                             W���    �              .  require_once(($_SERVER['DOCUMENT_ROOT'])."")5��