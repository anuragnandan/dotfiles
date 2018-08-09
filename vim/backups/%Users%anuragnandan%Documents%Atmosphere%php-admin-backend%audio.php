Vim�UnDo� dѐM?�]d�>ٺQr3"�����x:����  �                                   Y%�    _�                             ����                                                                                                                                                                                                                                                                                                                                                             Y%�     �              �   <?php       B	/* $Id: audio.php,v 1.25 2014/01/28 18:00:35 FTL\smcniff Exp $ */       (	require_once("../../prosodie_def.php");    	require_once("Datasource.php");   "	require_once("result_class.php");   !	require_once("audio_class.php");    	require_once("Atmosphere.php");   	   G	if (!defined("SyslogSeverity")) define("SyslogSeverity", LOG_WARNING);           class Audio extends Atmosphere   {       T	protected $version = '$Id: audio.php,v 1.25 2014/01/28 18:00:35 FTL\smcniff Exp $';           	function get($valueObject)   	{   w		//syslog(LOG_WARNING, "Function ".__FUNCTION__."(): The value valueObject is [" . print_r($valueObject, true) . "]");   x		$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value valueObject is [" . print_r($valueObject, true) . "]");   		   1		$this->returnObject = new Result_Array_class();   		   6		if (!$this->_check_param($valueObject, 'clientnum'))   		{   #			$this->returnObject->status = 0;   C			$this->returnObject->error_text = "Missing parameter clientnum";   			return $this->returnObject;   		}       *		$lclientnum = $valueObject['clientnum'];   5		$dir = "/share/sounds/custom/atm/".$lclientnum."/";   		   		$count = 0;   		if (is_dir($dir))    		{   			if ($dh = opendir($dir))    			{   .				if (!$this->_shard_connect($valueObject)){    					return $this->returnObject;   
				}else{   					$conn = $this->conn;   				}       �				$sql = "SELECT value FROM asterisk.vicidial_override_ids WHERE CUSTID = $lclientnum AND id_table = 'customer_id_gen_ivr' AND LENGTH(value) = 8";   P				$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): Executing SQL [$sql]");   $				$result = $conn->_execute($sql);   				if ($conn->errno != 0) {   %					$this->returnObject->status = 0;   2					$this->returnObject->error_text = $conn->err;    					return $this->returnObject;   				}	   				$ownerFilter = "";			   #				if ( mysqli_num_rows($result) )   				{   6					$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   r					$ownerFilter = "OR l.OWNER LIKE '%".$row['value']."%' OR l.OWNER like '%".substr($row['value'],0,4)."0000%'";   				}       				   �				$sql  = " SELECT l.audio_id, l.client_id, l.audio_name, l.audio_filename, l.audio_description, l.audio_size, l.audio_duration, l.insert_datetime, l.OWNER,";   �				$sql .= " 			 l.audio_uploaded_datetime, l.update_datetime, GROUP_CONCAT(c.audio_category_name ORDER BY c.audio_category_id SEPARATOR', ')";   a				$sql .= " 			 AS audio_categories, a.audio_language_id, a.audio_language_name, l.s3_wav_url";   D				$sql .= " FROM asterisk.vicidial_audio_languages a CROSS JOIN ";   H				$sql .= " 		 asterisk.vicidial_audio_category_config g INNER JOIN ";   v				$sql .= " 		 asterisk.vicidial_audio_category c ON (g.audio_category_id = c.audio_category_id) RIGHT OUTER JOIN ";   Q				$sql .= " 		 asterisk.vicidial_audio_library l ON (l.audio_id = g.audio_id)";   ?				$sql .= " WHERE l.audio_language_id = a.audio_language_id";   #				$sql .= " 	AND l.deleted = 0 ";   U				$sql .= " 	AND (l.client_id = '$lclientnum' OR l.OWNER = 'GLOBAL' $ownerFilter)";   $				$sql .= " GROUP BY l.audio_id ";   )				$sql .= " ORDER BY l.audio_name ASC";   				   P				$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): Executing SQL [$sql]");   								   $				$result = $conn->_execute($sql);   				if ($conn->errno != 0) {   %					$this->returnObject->status = 0;   2					$this->returnObject->error_text = $conn->err;    					return $this->returnObject;   				}   								   <				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))   				{   (					$currFile = $row['audio_filename'];   B					$currDir = "/share/sounds/custom/atm/".$row['client_id']."/";   					   H					if ( file_exists($currDir.$currFile) || $row['s3_wav_url'] != null)   					{   *						$this->returnObject->items[] = $row;   �						//$this->mylog(LOG_WARNING, __FILE__ . ":" . __FUNCTION__ . "(): >>>>>>> file size from DB is ".print_r($this->returnObject->audio_duration[],true));   					}   
					else    |						$this->mylog(LOG_WARNING, __FILE__ . ":" . __FUNCTION__ . "(): WARNING: File $currFile in DB but NOT in $currDir!!!");   				}   				   				   `				//$this->mylog(LOG_INFO, "Function " . __FUNCTION__ . "(): the clientnum is '$lclientnum'");   w				$sql = "SELECT MAX(user_level) AS user_level FROM atm_globals.tbl_vpd_user_accounts WHERE clientnum = $lclientnum";   $				$result = $conn->_execute($sql);   				if ($conn->errno != 0)   				{   %					$this->returnObject->status = 0;   \					$this->returnObject->error_text = "DB connection issue in get_user_level().$conn->err";    					return $this->returnObject;   				}   				   				   5				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   $				$userlevel = $row['user_level'];   c				//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value returnObject is  $userlevel");   				   d				$sql = "SELECT Generic_IVR FROM atm_globals.tbl_vpd_account_info WHERE clientnum = $lclientnum";   $				$result = $conn->_execute($sql);   				if ($conn->errno != 0)   				{   %					$this->returnObject->status = 0;   \					$this->returnObject->error_text = "DB connection issue in get_user_level().$conn->err";    					return $this->returnObject;   				}   				   				   5				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   .				$generic_ivr_access = $row['Generic_IVR'];   l				//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value ivr access is  '$generic_ivr_access'");   				   @				$this->returnObject->extra = $userlevel.$generic_ivr_access;   �				//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value returnObject is  [" . print_r($this->returnObject, true) . "].");   			}   			else   ?				$this->returnObject->error_text = "'$dir' can't be opened";   			closedir($dh);   		}   		else   A			$this->returnObject->error_text = "'$dir' is not a directory";   			   			   0		if ( strlen($this->returnObject->error_text) )   		{   #			$this->returnObject->status = 0;   			return $this->returnObject;   		}		   		   "		$this->returnObject->status = 1;   u		//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): Returning [" . print_r($this->returnObject, true) . "].");		   		return $this->returnObject;   	}   	       	function fileOp($valueObject)   	{   +		$this->returnObject = new Result_class();   				   8		if (!$this->_check_param($valueObject, 'clientnum') ||   0			!$this->_check_param($valueObject, 'file') ||   ,			!$this->_check_param($valueObject, 'op'))   			return $this->returnObject;       *		$lclientnum = $valueObject['clientnum'];       5		$SRC_DIR = "/share/sounds/custom/atm/$lclientnum/";   		switch($valueObject['op'])   		{   			case "Convert2wav":   ;				$destfile = substr($valueObject['file'],0,-4) . ".wav";   0				$out_file = TEMP_FILE_DIR_AUDIO . $destfile;   /				$src_fil = $SRC_DIR . $valueObject['file'];       E				//$cmd = "/usr/bin/sox -tul -b -r8000 $src_fil -twav $out_file";    ]				//Added the "-w" (word) option so that the ouptut file will be in 16-bit (or word) format            $ini_settings = array();   6        if(file_exists("/etc/atmosphere/general.ini"))   M          $ini_settings = parse_ini_file("/etc/atmosphere/general.ini",true);       y        if(isset($ini_settings['general']['sox_version']) && substr($ini_settings['general']['sox_version'],0,2) == '14')   R          $cmd = "/usr/bin/sox -tul -c1 -b8 -r8000 $src_fil -twav -b16 $out_file";           else   K          $cmd = "/usr/bin/sox -tul -b -r8000 $src_fil -twav -w $out_file";   ;				//$this->mylog(LOG_INFO, "Executing command: [$cmd].");   				   				system($cmd);   -				$this->returnObject->error_text = "$cmd";   $				$this->returnObject->status = 1;   
				break;   			case "Rename":   .				if (isset($valueObject['dfile']) == FALSE)   				{   <					$this->returnObject->error_text = "Dest. name not set";   %					$this->returnObject->status = 0;   					break;   				}   1				$fin_path = $SRC_DIR . $valueObject['dfile'];   &				$fin_name = $valueObject['dfile'];   				if (file_exists($fin_path))   				{   '					$this->returnObject->error_text =    3									"$fin_name already exists. Delete first.";   %					$this->returnObject->status = 0;   					break;   				}   0				$src_path = $SRC_DIR . $valueObject['file'];   .				if (@rename($src_path,$fin_path) == FALSE)   				{   '					$this->returnObject->error_text =    1										"Rename $src_path -> $fin_path failed";   %					$this->returnObject->status = 0;   				}   
				break;   			case "Delete":   0				$fin_path = $SRC_DIR . $valueObject['file'];   %				$fin_name = $valueObject['file'];   (				if (file_exists($fin_path) == FALSE)   				{   '					$this->returnObject->error_text =    2									"$fin_name doesn't exist. Can't Delete.";   %					$this->returnObject->status = 0;   					break;   				}   $				if (@unlink($fin_path) == FALSE)   				{   '					$this->returnObject->error_text =    )												"Delete of $fin_path failed";   %					$this->returnObject->status = 0;   					break;   				}   				   
				break;   			default:   &				$this->returnObject->error_text =    2									"Unknown Operation: ".$valueObject['op'];   $				$this->returnObject->status = 0;   
				break;   		}   		return $this->returnObject;   	}   	   	   (	function get_audio_detail($valueObject)   	{   x		$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value valueObject is [" . print_r($valueObject, true) . "]");   		   1		$this->returnObject = new Result_Array_class();   		   7		if ( !$this->_check_param($valueObject, 'audio_id') )   		{   #			$this->returnObject->status = 0;   B			$this->returnObject->error_text = "Missing parameter audio_id";   !			return $this->returnObject;			   		}   		   ,		if (!$this->_shard_connect($valueObject)){   			return $this->returnObject;   		}else{   			$conn = $this->conn;   		}	   			   '		$laudioId = $valueObject['audio_id'];       		//Get info   v		$sql  = " SELECT audio_id, client_id, audio_name, audio_filename, audio_description, audio_language_id,s3_wav_url,";   c        $sql .= " 		 audio_size, insert_datetime, audio_uploaded_datetime, update_datetime, OWNER";   8        $sql .= " FROM asterisk.vicidial_audio_library";   0        $sql .= " WHERE audio_id = $laudioId";		   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0)    		{   #			$this->returnObject->status = 0;   0			$this->returnObject->error_text = $conn->err;   			return $this->returnObject;   		}		   3		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   '		$this->returnObject->items[0] = $row;   		//Get categories   o		$sql  = " SELECT audio_category_id FROM asterisk.vicidial_audio_category_config WHERE audio_id = $laudioId ";   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0)    		{   #			$this->returnObject->status = 0;   0			$this->returnObject->error_text = $conn->err;   			return $this->returnObject;   		}	   		$tmpCatArray = array();   :		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))   .			$tmpCatArray[] = $row['audio_category_id'];   P		$this->returnObject->items[0]['audio_categories'] = implode(",",$tmpCatArray);   			   		$conn->_free_result($result);   "		$this->returnObject->status = 1;   		return $this->returnObject;   	}   	   	   "	function save_audio($valueObject)   	{   x		$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value valueObject is [" . print_r($valueObject, true) . "]");   		   1		$this->returnObject = new Result_Array_class();   		   U		if(isset($valueObject['audio_upload']) &&  $valueObject['audio_upload'] == 'true'){   "			$this->conn = new Datasource();   F			$this->conn->_connect_by_type('PRIMARY',$valueObject['clientnum']);    			if($this->conn->errno != 0) {   $				$this->returnObject->status = 0;   7				$this->returnObject->error_text = $this->conn->err;   				return false;   			}   		}else{   -			if (!$this->_shard_connect($valueObject)){   				return $this->returnObject;   			}   		}   		$conn = $this->conn;   		   9		if (!$this->_check_param($valueObject, 'clientnum') ||    4			!$this->_check_param($valueObject, 'audio_id') ||   6			!$this->_check_param($valueObject, 'audio_name') ||   =			!$this->_check_param($valueObject, 'audio_description') ||   <			!$this->_check_param($valueObject, 'audio_categories') ||   6			!$this->_check_param($valueObject, 'audio_size') ||   9			!$this->_check_param($valueObject, 'file_attached') ||   :			!$this->_check_param($valueObject, 'audio_language') ||   0			!$this->_check_param($valueObject, 'owner'))	   		{   #			$this->returnObject->status = 0;   C			$this->returnObject->error_text = "Missing audio parameter(s).";   			return $this->returnObject;   		}   			   ;		$lclientnum = $conn->_string($valueObject['clientnum']);	   8		$laudioId = $conn->_string($valueObject['audio_id']);	   <		$laudioName = $conn->_string($valueObject['audio_name']);	   C		$laudioDesc = $conn->_string($valueObject['audio_description']);	   G		$laudioCats = $conn->_string(trim($valueObject['audio_categories']));   D		$laudioCatsArray = explode(",",$valueObject['audio_categories']);	   ;		$laudioSize = $conn->_string($valueObject['audio_size']);   A		$lfileAttached = $conn->_string($valueObject['file_attached']);   C		$laudioLanguage = $conn->_string($valueObject['audio_language']);   1		$owner = $conn->_string($valueObject['owner']);   '		$DateTimeStamp = date("Y-m-d H:i:s");   +		$this->returnObject->fname = $laudioName;   		   		   1		//getting language_id for the selected language   		   �		$sql = "SELECT vicidial_audio_languages.* FROM asterisk.vicidial_audio_languages vicidial_audio_languages WHERE vicidial_audio_languages.audio_language_name = '$laudioLanguage'";   N		$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): Executing SQL [$sql]");   "		$result = $conn->_execute($sql);   		    	    if ($conn->errno != 0)    			{   $				$this->returnObject->status = 0;   1				$this->returnObject->error_text = $conn->err;   				return $this->returnObject;   			}   3		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   +		$language_id = $row['audio_language_id'];   W		//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): language id is $language_id");   _	   //Checking if there is already records existing with same language, filename for the client  k			$sql = "SELECT vicidial_audio_library.* FROM asterisk.vicidial_audio_library WHERE (vicidial_audio_library.client_id = '$lclientnum' AND vicidial_audio_library.audio_name = '$laudioName' AND vicidial_audio_library.audio_language_id = '$language_id' AND deleted = 0 AND vicidial_audio_library.audio_id != $laudioId AND vicidial_audio_library.OWNER = '$owner')";   e			//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The sql is [" . print_r($sql, true) . "]");   #			$result = $conn->_execute($sql);   '			$numRows = mysqli_num_rows($result);   e			//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The sql is [" . print_r($sql, true) . "]");   P			//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The owner is $owner");   			if( $numRows > 0)   			{       c				//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): There are duplicates for $laudioName" );   $				$this->returnObject->status = 2;       �					$this->returnObject->error_text = " A File with name '$laudioName' with '$laudioLanguage' language already exists. Please choose different file name or language";    					return $this->returnObject;   			}   		   		//AUDIO LIBRARY   		if ( $laudioId == 0 )    		{   		   			//INSERT   ;			$sql  = " INSERT INTO asterisk.vicidial_audio_library ";   �			$sql .= " (client_id, audio_name, audio_description, audio_language_id, audio_size, audio_uploaded_datetime, insert_datetime, OWNER) ";    �			$sql .= " VALUES ('$lclientnum', '$laudioName', '$laudioDesc', '$language_id','$laudioSize', '$DateTimeStamp', '$DateTimeStamp', '$owner') ";   			   e			//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The sql is [" . print_r($sql, true) . "]");   #			$result = $conn->_execute($sql);   			if ($conn->errno != 0)    			{   $				$this->returnObject->status = 0;   1				$this->returnObject->error_text = $conn->err;   				return $this->returnObject;   			}	   			else    			{   %				$laudioId = $conn->_insert_id();	   )				$tmpAudioFilename = $laudioId.".pcm";   �				$sql = " UPDATE asterisk.vicidial_audio_library SET audio_filename = '$tmpAudioFilename', audio_language_id = '$language_id', OWNER = '$owner'  WHERE audio_id = $laudioId";	   $				$result = $conn->_execute($sql);   				if ($conn->errno != 0)    				{   %					$this->returnObject->status = 0;   2					$this->returnObject->error_text = $conn->err;    					return $this->returnObject;   				}						   			}		   		}   		else //UPDATE   		{   6			$sql  = " UPDATE asterisk.vicidial_audio_library ";   �			$sql .= " SET audio_name = '$laudioName', audio_description = '$laudioDesc', audio_language_id = '$language_id', OWNER = '$owner'";   			if ($lfileAttached)   U				$sql .= " ,audio_size = $laudioSize, audio_uploaded_datetime = '$DateTimeStamp'";   +			$sql .= " WHERE audio_id = $laudioId";		   #			$result = $conn->_execute($sql);   			   s			//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value sql in else is [" . print_r($sql, true) . "]");   			if ($conn->errno != 0)    			{   $				$this->returnObject->status = 0;   1				$this->returnObject->error_text = $conn->err;   				return $this->returnObject;   				}					   		}   		   		   		//AUDIO CATEGORIES   		//remove current   Z		$sql = "DELETE FROM asterisk.vicidial_audio_category_config WHERE audio_id = $laudioId";   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0)    		{   #			$this->returnObject->status = 0;   0			$this->returnObject->error_text = $conn->err;   			return $this->returnObject;   		}	   /		//if they chose nothing, default to 'Generic'   !		if ( strlen($laudioCats) == 0 )   			$laudioCatsArray[0] = 1;   		//add new   0		for($i=0; $i < count($laudioCatsArray); $i++)    		{   2			$aCatId = $conn->_string($laudioCatsArray[$i]);   z			$sql = "INSERT INTO asterisk.vicidial_audio_category_config (audio_id, audio_category_id) VALUES ($laudioId, $aCatId)";   #			$result = $conn->_execute($sql);   			if ($conn->errno != 0)    			{   $				$this->returnObject->status = 0;   1				$this->returnObject->error_text = $conn->err;   				return $this->returnObject;   			}		   		}   		   !		if(is_bool($result) === false){   			mysqli_free_result($result);   		}   		   				   
		//RETURN   		if ( $lfileAttached )   		{   ,			$this->returnObject->items[] = $laudioId;   #			$this->returnObject->status = 1;   !			return $this->returnObject;			   		}   		else    		{   (			$valueObject['audio_id'] = $laudioId;   1			return $this->get_audio_detail($valueObject);	   		}   	}          &	//AUDIO LANGUAGES																				   +	function get_audio_languages($valueObject)   	{   x		$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value valueObject is [" . print_r($valueObject, true) . "]");   4	    $this->returnObject = new Result_Array_class();   				   ,		if (!$this->_shard_connect($valueObject)){   			return $this->returnObject;   		}else{   			$conn = $this->conn;   		}   		   ;		$sql = "SELECT * FROM asterisk.vicidial_audio_languages";   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0)    		{   #			$this->returnObject->status = 0;   _			$this->returnObject->error_text = "DB connection issue in get_audio_languages().$conn->err";   			return $this->returnObject;   		}   		   9		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))   &		$this->returnObject->items[] = $row;   		   �		//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value valueObject is [" . print_r($this->returnObject->items, true) . "]");   		$conn->_free_result($result);   "		$this->returnObject->status = 1;   		return $this->returnObject;   		   	}   	   $	function delete_audio($valueObject)   	{   x		$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value valueObject is [" . print_r($valueObject, true) . "]");   		   1		$this->returnObject = new Result_Array_class();   		   j		//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value  ".is_array($valueObject["audio_id"]));   		   		   P		if( is_array($valueObject["audio_id"]) && !isset($valueObject["audio_id"][0]))   		{   #			$this->returnObject->status = 0;   D			$this->returnObject->error_text = "Missing parameters audio_id.";   			return $this->returnObject;   		}   		   !		$this->conn = new Datasource();   U		if(isset($valueObject['audio_upload']) &&  $valueObject['audio_upload'] == 'true'){   F			$this->conn->_connect_by_type('PRIMARY',$valueObject['clientnum']);       }       else   0			$this->conn->_connect_by_type('PRIMARY','0');       !    if($this->conn->errno != 0) {   &      $this->returnObject->status = 0;   9      $this->returnObject->error_text = $this->conn->err;         return false;       }   		   		$conn = $this->conn;       i		//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value  ".trim($valueObject['audio_id'][0]));   		   0		for($i = 0; $i < $valueObject["length"]; $i++)   		{   2			$laudioid = trim($valueObject['audio_id'][$i]);   i			$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value  ".trim($valueObject['audio_id'][$i]));   E			//Check if any of the audio is being USED by any other application  			$sql = "SELECT * FROM atm_ivr.atmosphere_ivr_interaction_prompts, atm_ivr.atmosphere_ivr_application WHERE ( prompt_type = 'AUDIO_ID' AND prompt_type_id = $laudioid AND atmosphere_ivr_interaction_prompts.application_id = atmosphere_ivr_application.application_id AND deleted = '0')";   L			//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value  $sql");   #			$result = $conn->_execute($sql);   '			$numRows = $conn->_nextRow($result);   			   		    if ($conn->errno != 0) {   $				$this->returnObject->status = 0;   1				$this->returnObject->error_text = $conn->err;   				return $this->returnObject;   			}   			else if($numRows){   5				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   4				$applicationName = $numRows["application_name"];   �				//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value  application name is '$applicationName' and num rows is $numRows[application_name]");   v				$sql = "SELECT vicidial_audio_library.audio_name from asterisk.vicidial_audio_library WHERE audio_id = $laudioid";   $				$result = $conn->_execute($sql);   M				//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value  $sql");   5				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   #				$fileName = $row["audio_name"];   $				$this->returnObject->status = 0;   |				$this->returnObject->error_text = "Selected audio file '$fileName' is being used by IVR application '$applicationName'";   				return $this->returnObject;   			}   		}	   +			//Flag all the audio record as 'deleted'           0		for($i = 0; $i < $valueObject["length"]; $i++)   		{   2			$laudioid = trim($valueObject['audio_id'][$i]);   |			$sql  = " UPDATE asterisk.vicidial_audio_library SET deleted = 1, deleted_datetime = Now() WHERE audio_id = $laudioid";		   							   #			$result = $conn->_execute($sql);   			if ($conn->errno != 0) {   $				$this->returnObject->status = 0;   1				$this->returnObject->error_text = $conn->err;   				return $this->returnObject;   			}   			   			//Delete audio categories   ^			$sql = "DELETE FROM asterisk.vicidial_audio_category_config WHERE audio_id = $laudioid";			   J			$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value  $sql");   #			$result = $conn->_execute($sql);   			if ($conn->errno != 0) {   $				$this->returnObject->status = 0;   1				$this->returnObject->error_text = $conn->err;   				return $this->returnObject;   			}   			   >			if ( $this->_check_param($valueObject, 'upload_deletion') )   			{    				return $this->returnObject;	   			}   			else   			{   >				$valueObjectTemp['clientnum'] = $valueObject['clientnum'];   -				$valueObjectTemp['audio_id'] = $laudioid;   :				$valueObjectTemp['user_id'] = $valueObject['user_id'];   6				$valueObjectTemp['token'] = $valueObject['token'];   +				$valueObjectTemp['sync_op'] = "delete";   (				$this->sync_audio($valueObjectTemp);   			}   		}   #		return $this->get($valueObject);	   	}	   	   	   "	function sync_audio($valueObject)   	{   x		$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value valueObject is [" . print_r($valueObject, true) . "]");   		   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 1;   		   �		if ( !$this->_check_param($valueObject, 'audio_id') || !$this->_check_param($valueObject, 'clientnum') || !$this->_check_param($valueObject, 'sync_op') || (trim($valueObject['sync_op']) != "save" && trim($valueObject['sync_op']) != "delete") )   		{   #			$this->returnObject->status = 0;   I			$this->returnObject->error_text = "Missing parameter for audio sync.";   			return $this->returnObject;   		}   		   U		if(isset($valueObject['audio_upload']) &&  $valueObject['audio_upload'] == 'true'){   "			$this->conn = new Datasource();   F			$this->conn->_connect_by_type('PRIMARY',$valueObject['clientnum']);    			if($this->conn->errno != 0) {   $				$this->returnObject->status = 0;   7				$this->returnObject->error_text = $this->conn->err;   				return false;   			}   		}else{   -			if (!$this->_shard_connect($valueObject)){   				return $this->returnObject;   			}   		}   		$conn = $this->conn;	   			   		//Set local vars   ,		$audioId = trim($valueObject['audio_id']);   .		$clientId = trim($valueObject['clientnum']);   *		$syncOp = trim($valueObject['sync_op']);   		   		//Set command string   		if ($syncOp == "delete")   O			$cmdStr = "rm /var/lib/asterisk/sounds/custom/atm/$clientId/$audioId.pcm";		   		else   s			$cmdStr = "cp -p /share/sounds/custom/atm/$clientId/$audioId.pcm /var/lib/asterisk/sounds/custom/atm/$clientId";   			   ^		$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): cmdStr = $cmdStr   syncOp =  $syncOp");   				   		//Get hostnames   W		$sql  = "SELECT server_id FROM asterisk.servers WHERE active_asterisk_server = 'Y'";	   		   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   P			$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): SQL ERROR: sql = $sql");   #			$this->returnObject->status = 0;   0			$this->returnObject->error_text = $conn->err;   			return $this->returnObject;   		}   		   :		//Loop over hostnames distribute the script command file   		$opErrCnt = 0;   :		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))   		{   !			$hostname = $row['server_id'];   			//Create the command file   F			$fileName = $hostname."_".getmypid()."_".date('Y-m-d-h-i-s').".sh";   .			$tmpFilePath = "/share/scripts/".$fileName;   P			//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): SQL is $tmpFilePath");   #			$fh = fopen($tmpFilePath, 'w');	   			fwrite($fh, $cmdStr);   			fclose($fh);	   			chmod($tmpFilePath, 0755);   2			//Move the command file to the 'hostname' dir		   T			$tmpFileMvCmd = "mv /share/scripts/$fileName /share/scripts/$hostname/$fileName";   Q			//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): SQL is $tmpFileMvCmd");   0			//$ret = exec($tmpFileMvCmd,$output,$retval);   V			$retval = rename("/share/scripts/$fileName", "/share/scripts/$hostname/$fileName");   K			//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): SQL is $retval");   			if (false == $retval)   			{   				$opErrCnt++;			   			}   m			$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): tmpFileMvCmd = $tmpFileMvCmd, retval = '$retval'");		   		}   		   		//See if we had any errors   		if ($opErrCnt > 0)   		{   #			$this->returnObject->status = 0;   j			$this->returnObject->error_text = "Problems syncing audio($audioId), please contact the Support team.";   		}			   		   
		//Return   		if ($syncOp == "delete")   		{   T			$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): Returning from 'delete'");		   %			//return $this->get($valueObject);   		}   		else    		{   t			$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): Returning [" . print_r($this->returnObject, true) . "].");		   			return $this->returnObject;   		}	   	}	   	   ,	//get language voice talents for a language   1	function get_language_voicetalents($valueObject)   	{   x		$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value valueObject is [" . print_r($valueObject, true) . "]");   1		$this->returnObject = new Result_Array_class();   		   ,		if (!$this->_shard_connect($valueObject)){   			return $this->returnObject;   		}else{   			$conn = $this->conn;   		}   		   -		$language_id = $valueObject["language_id"];   a		//$this->mylog(LOG_INFO, "Function " . __FUNCTION__ . "(): the language_id is '$language_id'");   n		$sql = "SELECT * FROM asterisk.vicidial_audio_language_voicetalents WHERE audio_language_id = $language_id";   "		$result = $conn->_execute($sql);   s		//$this->mylog(LOG_INFO, "Function " . __FUNCTION__ . "(): the result stmt is [" . print_r($result, true) . "]");   		if ($conn->errno != 0)   		{   #			$this->returnObject->status = 0;   d			$this->returnObject->error_text = "DB execution issue in get_language_voicetalents().$conn->err";   			return $this->returnObject;   		}   		   9		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))   '			$this->returnObject->items[] = $row;   		   �		//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value returnObject is [" . print_r($this->returnObject->items, true) . "]");   		$conn->_free_result($result);   "		$this->returnObject->status = 1;   		return $this->returnObject;   		   	}   	   "	//get sub accounts for the client   '	function get_sub_account($valueObject)   	{   9		syslog(LOG_WARNING, "Inside function " . __FUNCTION__);   x		$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value valueObject is [" . print_r($valueObject, true) . "]");   1		$this->returnObject = new Result_Array_class();   	   '		if(!isset($valueObject["clientnum"]))   		{   #			$this->returnObject->status = 0;   9			$this->returnObject->error_text = "Missing clientnum";   			return $this->returnObject;   		}   		   ,		if (!$this->_shard_connect($valueObject)){   			return $this->returnObject;   		}else{   			$conn = $this->conn;   		}   		   *		$lclientnum = $valueObject["clientnum"];   		$sql  = " SELECT value";   1		$sql .= " FROM asterisk.vicidial_override_ids";   4		$sql .= " WHERE id_table = 'customer_id_gen_ivr'";   &		$sql .= " AND CUSTID = $lclientnum";   		   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   #			$this->returnObject->status = 0;   5			$this->returnObject->error_text = $conn->err.$sql;   			return $this->returnObject;   		}   3		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);   #		$account = intval($row['value']);   		   Z		//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value account is  $account");   		   ;		$myServer = "ftlw03sql004.ftl.prosodieinteractive.local";   		$myUser = "sa";   		$myPass = "wassuphomes";    		$myDB = "Prosodie_Operations";   		   !		//connection to MS-SQL database   9		$dbhandle = mssql_connect($myServer, $myUser, $myPass);   		if(!$dbhandle)   		{   #			$this->returnObject->status = 0;   A			$this->returnObject->error_text = "Error connecting to MSSQL";   ]			//$this->mylog(LOG_WARNING, "Function " . __FUNCTION__ . "(): Error connecting to MSSQL");   			return $this->returnObject;   		}   		   '		//select MS-SQL database to work with   (		if(!mssql_select_db($myDB, $dbhandle))   		{   #			$this->returnObject->status = 0;   ]			//$this->mylog(LOG_WARNING, "Function " . __FUNCTION__ . "(): Could not select MSSQL DB");   A			$this->returnObject->error_text = "Could not select MSSQL DB";   			mssql_close($dbhandle);   			return $this->returnObject;   		}   		   �		$sql  = " SELECT Project_ID, convert(varchar(8), Project_ID) + ' - ' + Project_Name as Project_Name";                                //This is the SUB-ACCOUNT NUM   7		$sql .= " FROM Prosodie_Operations.dbo.tbl_Projects";   X		$sql .= " WHERE Client_ID = $account";                       //This is the ACCOUNT NUM   �		$sql .= " AND (Shut_down_date is null or shut_down_date > CURRENT_TIMESTAMP)";                       //This means that it is active   !		$sql .= " ORDER BY Project_ID";   		   #		$mssqlResult = mssql_query($sql);   		   R		//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value sql is  $sql");   		   		if (!$mssqlResult)   		{   #			$this->returnObject->status = 0;   >			$this->returnObject->error_text = mssql_get_last_message();   			//close the connection   			mssql_close($dbhandle);   �			//$this->mylog(LOG_WARNING, "Function " . __FUNCTION__ . "(): Error executing MSSQL query [$sql] - " . mssql_get_last_message());   			return $this->returnObject;   		}   (		$this->returnObject->extra = $account;   5		while ($msSqlRow = mssql_fetch_array($mssqlResult))   		{   z			//$this->mylog(LOG_INFO, "Function " . __FUNCTION__ . "(): Row returned from MSSQL [". print_r($msSqlRow, true) . "]");   ,			$this->returnObject->items[] = $msSqlRow;   		}   		   		mssql_close($dbhandle);   "		$this->returnObject->status = 1;   		$conn->_free_result($result);   		return $this->returnObject;   	}   	   2	//get all accounts fro multiple account selection   (	function get_all_accounts($valueObject)   	{   x		$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value valueObject is [" . print_r($valueObject, true) . "]");   1		$this->returnObject = new Result_Array_class();   	   ,		if (!$this->_shard_connect($valueObject)){   			return $this->returnObject;   		}else{   			$conn = $this->conn;   		}   		   *		$lclientnum = $valueObject["clientnum"];   G		$sql  = " SELECT DISTINCT value,  main_account_name as Account_Name";   3		$sql .= " FROM asterisk.vicidial_override_ids m";   R		$sql .= " LEFT JOIN amt_ivr.tbl_prosodie_clients c ON m.value = c.main_account";   I		$sql .= " WHERE m.id_table = 'customer_id_gen_ivr' AND m.active = '1'";   				   Q		//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The sql value is $sql");   "		$result = $conn->_execute($sql);   		if ($conn->errno != 0) {   #			$this->returnObject->status = 0;   5			$this->returnObject->error_text = $conn->err.$sql;   			return $this->returnObject;   		}   9		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))   &		$this->returnObject->items[] = $row;   		   �		//$this->mylog(LOG_INFO, "Function ".__FUNCTION__."(): The value returnObject acc names is [" . print_r($this->returnObject->items, true) . "]");   "		$this->returnObject->status = 1;   		$conn->_free_result($result);   		return $this->returnObject;   	}   	   	   8	private function mylog($severity=LOG_INFO, $logMessage)   	{   1		$errorType = array(	LOG_EMERG=>		'EMERGENCY  ',   "							LOG_ALERT=>		'ALERT      ',   !							LOG_CRIT=>		'CRITICAL   ',    							LOG_ERR=>		'ERROR      ',   #							LOG_WARNING=>	'LOG_WARNING',   "							LOG_NOTICE=>	'NOTICE     ',   "							LOG_INFO=>		'INFO       ',    							LOG_DEBUG=>		'DEBUG		');       		// Only log on RnD Web Server    		$machineName = php_uname('n');   W		if ((substr($machineName, 0, 3) == "fld") || (substr($machineName, 0, 3) == "flr") )    		{   r			error_log(Date("Y-m-d H:i:s") . "[$errorType[$severity]] : $logMessage\n", 3, '/var/log/atmosphere_audio.log');   		}   		   "		if ($severity <= SyslogSeverity)   		{   "			syslog($severity, $logMessage);   		}   	}   	   	   	function init_db()   	{   0		$pos = strpos(dirname(__FILE__), "/services");   		if ($pos === false)    			$iniPath = dirname(__FILE__);   		else   1			$iniPath = substr(dirname(__FILE__), 0, $pos);   7		$iniPath = str_replace(AMFPHP, "WG5/ini/", $iniPath);   #		$conn = new Datasource($iniPath);   		return($conn);   	}	       }   ?>5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             Y%�    �  Z  \          5			$this->returnObject->error_text = $conn->err.$sql;�              5			$this->returnObject->error_text = $conn->err.$sql;5��