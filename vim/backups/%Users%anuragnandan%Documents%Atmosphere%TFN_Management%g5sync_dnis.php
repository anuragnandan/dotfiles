Vim�UnDo� җr��Yf��ky��mEU�,�EK�վ�%dDs�                                     UA00    _�                             ����                                                                                                                                                                                                                                                                                                                                                             UA0/    �                5$STAG_APPBASE="/share/applications/asterisk/custom/";�                ?$STAG_DNISFILE="/share/applications/asterisk/custom/dnis.list";�                5$PROD_APPBASE="/share/applications/asterisk/custom/";�                ?$PROD_DNISFILE="/share/applications/asterisk/custom/dnis.list";5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             UA/U     �               #!/usr/bin/php -q   <?       .if(file_exists('/etc/atmosphere/general.ini'))   _     $general = parse_ini_string_arrows(file_get_contents('/etc/atmosphere/general.ini'),true);   else   ?     die('No conf. file found at /etc/atmosphere/general.ini');   �if(!isset($general['database_did']['hostname']) || !isset($general['database_did']['username']) || !isset($general['database_did']['password']))   {   �     die("ERROR: Not all of the DB connection parmeters are configured in /etc/atmosphere/general.ini! \n".print_r($general,1));   }       +$HOST=$general['database_did']['hostname'];   +$USER=$general['database_did']['username'];   +$PASS=$general['database_did']['password'];   $DBNAME="g4db";   $TMP_DNISFILE="/tmp/dnis.list";   ?$PROD_DNISFILE="/share/applications/asterisk/custom/dnis.list";   5$PROD_APPBASE="/share/applications/asterisk/custom/";   '$PROD_AGIBASE="/share/agi-bin/custom/";   &$PROD_AUDBASE="/share/sounds/custom/";   $$PROD_SCRIPTBASE="/share//scripts/";   7$PROD_ARCAPPBASE="/share/archive/applications/custom/";   1$PROD_ARCAUDBASE="/share/archive/sounds/custom/";   2$PROD_ARCAGIBASE="/share/archive/agi-bin/custom/";   ?$STAG_DNISFILE="/share/applications/asterisk/custom/dnis.list";   5$STAG_APPBASE="/share/applications/asterisk/custom/";   '$STAG_AGIBASE="/share/agi-bin/custom/";   &$STAG_AUDBASE="/share/sounds/custom/";   #$STAG_SCRIPTBASE="/share/scripts/";   7$STAG_ARCAPPBASE="/share/archive/applications/custom/";   1$STAG_ARCAUDBASE="/share/archive/sounds/custom/";   2$STAG_ARCAGIBASE="/share/archive/agi-bin/custom/";   $ID_FILE="/tmp/g5sync/id";   $AID_FILE="/tmp/g5sync/aid";       	$dofull = FALSE;   	$pid = getmypid();   	$script_count = 0;       L	if (isset($argv[1]) && $argv[1] == "F")	/* Force a re-sync of everything */   	{   		mylog("Full Sync Requested");   		$dofull = TRUE;   	}       "	$lockf = "/tmp/g5sync_dnis.lock";   !	if (file_exists($lockf) == TRUE)   	{   7		mylog("g5sync_dnis lock file $lockf exists. Exit.");    		syslog(LOG_LOCAL0,    E			"alert: g5sync_dnis.php lock file $lockf exists. Check the log!");   =		die('g5sync_dnis lock file ' . $lockf . ' exists. Exit.');    	}       (	$DB = mysql_connect($HOST,$USER,$PASS);   	if (!$DB)    	{    7		mylog('Could not connect: to $HOST' . mysql_error());   -		die('Could not connect: ' . mysql_error());   	}   .	$db_selected = mysql_select_db($DBNAME, $DB);   	if (!$db_selected)    	{   1		mylog('Can\'t use $DBNAME : ' . mysql_error());   9		die('Can\'t use : ' . $DBNAME . " - " . mysql_error());   	}       	/*    .	We will make 2 passes looking for work to do.   K	One will be from g4dids_history for work to do now (if sync request pend).   F	The 2nd will always be from g4dids for future work that has come due.   	*/       	touch($lockf);   0	$apprequests = $wrtdnisfile = $dnissync = TRUE;    	$rid = getLastRecord($ID_FILE);   "	$aid = getLastRecord($AID_FILE);	       ,	/* Write out the DNIS file if we need to */       	if ($wrtdnisfile == TRUE)   	{   -		mylog("Was told to update1 the DNIS List");   0		wrtdnisfile($DB,$TMP_DNISFILE,$PROD_DNISFILE);   	}       K	/* Perform and then Indicate that any current DNIS requests are handled */       	if ($dnissync == TRUE)   	{   		dodidrequests($DB);   	}       	if ($apprequests == TRUE)   	{   6		doapprequests($DB);	/* Do the individual requests */   	}       1	/* Do all Apps future work that just came due */           	if ($dofull)   4		doappfutrequests($DB,"R");		/* Full App Refresh */   	else   	{   :		doappfutrequests($DB,"A");		/* Future App Activations */   =		doappfutrequests($DB,"D");		/* Future App De-activations */   	}       9	/* If we had any requests indicate we're totally done */       	mysql_close($DB);     	@unlink($lockf);        function getLastRecord($id_file)   {   &	$file = @file_get_contents($id_file);   	if($file === FALSE)   	{   &		mylog("NO ID FILE FOUND: $id_file");   		$file = 0;   		//die("NO ID FILE FOUND");   	}   	return $file;   }       Bfunction parse_ini_string_arrows( $str, $ProcessSections = false )        {   '         $lines  = explode("\n", $str);            $return = Array();            $inSect = false;   !         foreach($lines as $line)   
         {   !             $line = trim($line);   =             if(!$line || $line[0] == "#" || $line[0] == ";")                    continue;   @             if($line[0] == "[" && $endIdx = strpos($line, "]"))                {   7                 $inSect = substr($line, 1, $endIdx-1);                    continue;                }   h             if(!strpos($line, '=>')) // (We don't use "=== false" because value 0 is not valid as well)                    continue;   ,             $tmp = explode("=>", $line, 2);   ,             if($ProcessSections && $inSect)   V                 $return[$inSect][trim($tmp[0])] = ltrim(str_replace('"','',$tmp[1]));                else   9                 $return[trim($tmp[0])] = ltrim($tmp[1]);   
         }            return $return;        }       function doapprequests($DB)   {   	global $aid,$AID_FILE;   >	$query = "select id,operation_type,account,subaccount,active,   6		flag_monitor,flag_bill,flag_maintenance,_type,_desc    ?		FROM g4apps_history where id > $aid  order by operation_dts";       #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}       
	$cnt = 0;   +	while ($row = @mysql_fetch_assoc($wrkcur))   	{   		$id = $row["id"];   !		$oper = $row["operation_type"];   		$acct = $row["account"];   		$sub = $row["subaccount"];   		$act = $row["active"];   $		$maint = $row["flag_maintenance"];   		$mon = $row["flag_monitor"];   		$bill = $row["flag_bill"];   		$type = $row["_type"];   		$desc = $row["_desc"];   		switch($oper)   		{   			case "A":   5				activate($acct,$sub);	/* Note FALLS THRU BELOW */   			case "U":   >				wrtappfile($acct,$sub,$type,$desc,$act,$mon,$bill,$maint);   
				break;   			case "D":   				deactivate($acct,$sub);   
				break;   		}   			$cnt++;   	}       	if ($cnt > 0)   	{   "		if(!@file_exists("/tmp/g5sync"))   			mkdir("/tmp/g5sync");   #		file_put_contents($AID_FILE,$id);   		@mysql_free_result($wrkcur);   	}   }       E/* Do individual requests for the dids in the g4dids_history table */       function dodidrequests($DB)   {   	global $rid,$ID_FILE;   D	$query = "select id,operation_type,account,subaccount,active,number   >		FROM g4dids_history where id > $rid order by operation_dts";       #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}       
	$cnt = 0;   +	while ($row = @mysql_fetch_assoc($wrkcur))   	{   		$id = $row["id"];   !		$oper = $row["operation_type"];   		$acct = $row["account"];   		$sub = $row["subaccount"];   		$act = $row["active"];   		$did = $row["number"];   		switch($oper)   		{   			case "A":   			case "U":   $				wrtdidfile($DB,$acct,$sub,$did);   
				break;   			case "D":    				deldidfile($acct,$sub,$did);   
				break;   		}   			$cnt++;   	}       	if ($cnt > 0)   	{   "		if(!@file_exists("/tmp/g5sync"))   			mkdir("/tmp/g5sync");   #		file_put_contents($ID_FILE, $id);   		@mysql_free_result($wrkcur);   	}   }       ,function wrtdidfile($DB,$acct,$subacct,$did)   {   	global $PROD_APPBASE;       	$tmp = "/tmp/$did.cfg";   (	$fpath = "$PROD_APPBASE$acct/$subacct";   	$final = "$fpath/$did.cfg";       
	$query =    F		"select var_name,var_value,goto_context,goto_extension,goto_priority   ?		FROM g4dids_variables where number='$did' order by var_name";           #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}       R	$str = "# THIS FILE IS GENERATED AUTOMATICALLY. DO NOT DELETE. DO NOT MODIFY.\n";   	$goto = $cnt = 0;   +	while ($row = @mysql_fetch_assoc($wrkcur))   	{   		$var = $row["var_name"];   		$val = $row["var_value"];   "		$context = $row["goto_context"];   "		$exten = $row["goto_extension"];   $		$priority = $row["goto_priority"];   1		if ($goto == 0 && strcasecmp($var,"goto") == 0)   		{   !			$str .= "_CONTEXT=$context\n";   !			$str .= "_EXTENSION=$exten\n";   #			$str .= "_PRIORITY=$priority\n";   		}   		else   			$str .= "$var=$val\n";       			$cnt++;   	}       	if ($cnt > 0)   	{   		@mysql_free_result($wrkcur);   		$fp = @fopen($tmp, 'w');   		if (!$fp)   		{   6			mylog('Error opening temp app file ('.$tmp.'): ');    4			die('Error opening temp app file ('.$tmp.'): ');    		}   		fputs($fp, $str);   		fclose($fp);       #		if (file_exists($fpath) == FALSE)   		{   $			mylog("Making directory $fpath");   $			$ret = mkdir($fpath, 0777, TRUE);   			if ($ret == FALSE)   			{   A				mylog('Error mkdir dnis cfg file directory ('.$fpath.'): ');    ?				die('Error mkdir dnis cfg file directory ('.$fpath.'): ');    			}   		}       *		if (files_are_same($tmp,$final) == TRUE)   P			mylog("Temp & Final dnis cfg file for $final are the same. No copy needed.");   		else   		{   %			if (@rename($tmp,$final) == FALSE)   			{   9				mylog("Unable to copy DNIS cfg file $tmp to $final");   C				syslog(LOG_LOCAL0, "alert: Unable to copy APP $tmp -> $final");   7				die('Error moving temp dnis cfg file ('.$tmp.')');    			}   0			mylog("Copied DNIS cfg file $tmp to $final");   		}   	}   }       (function deldidfile($acct,$subacct,$did)   {   	global $PROD_APPBASE;       (	$fpath = "$PROD_APPBASE$acct/$subacct";   	$final = "$fpath/$did.cfg";       !	if (file_exists($final) == TRUE)   	{   		$ret = @unlink($final);   		if ($ret == FALSE)   		{   >			mylog('Error del dnis cfg file directory ('.$final.'): ');    <			die('Error del dnis cfg file directory ('.$final.'): ');    		}   (		mylog("Removed DNIS CFG file $final");   	}   	else   =		mylog("DNIS CFG file $final does not exist to be removed");   }       %function doappfutrequests($DB,$rtype)   {   	$now = date("Y-m-d H:i:s");       "	if ($rtype == "A")	/* Activate */   	{   -		$query = "select account,subaccount,active,   6		flag_monitor,flag_bill,flag_maintenance,_type,_desc    C		FROM g4apps where active = 0 and activate_dts > '1970-01-01' and    		activate_dts <= '$now'";   	}   *	else if ($rtype == "D")	/* De-Activate */   	{   -		$query = "select account,subaccount,active,   6		flag_monitor,flag_bill,flag_maintenance,_type,_desc    E		FROM g4apps where active = 1 and deactivate_dts < '2036-12-31' and    		deactivate_dts <= '$now'";   	}   	else		/* Full Refresh */   	{   -		$query = "select account,subaccount,active,   6		flag_monitor,flag_bill,flag_maintenance,_type,_desc     		FROM g4apps where active = 1";   	}       #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   9		mylog('Invalid query ('.$query.'): ' . mysql_error());    7		die('Invalid query ('.$query.'): ' . mysql_error());    	}       
	$cnt = 0;   +	while ($row = @mysql_fetch_assoc($wrkcur))   	{   		$acct = $row["account"];   		$sub = $row["subaccount"];   		$act = $row["active"];   		$mon = $row["flag_monitor"];   $		$maint = $row["flag_maintenance"];   		$bill = $row["flag_bill"];   		$type = $row["_type"];   		$desc = $row["_desc"];   		switch($rtype)   		{   9			case "R":		/* Re-fresh activated ones -- falls thru */   			case "A":   -				mylog("Future activation of $acct $sub");   				activate($acct,$sub);   ;				wrtappfile($acct,$sub,$type,$desc,1,$mon,$bill,$maint);   
				break;   			case "D":   0				mylog("Future de-activation of $acct $sub");   				deactivate($acct,$sub);   
				break;   		}   			$cnt++;   	}       	if ($cnt > 0)   	{   		@mysql_free_result($wrkcur);   	}   }       function   copyscript($script)   {   	global $PROD_SCRIPTBASE;   	global $STAG_SCRIPTBASE;   	if (is_dir($PROD_SCRIPTBASE))       {   		$fname = basename($script);           $retval = FALSE;   ,        if ($dh = opendir($PROD_SCRIPTBASE))   	        {   4            while (($file = readdir($dh)) !== false)               {   +				if (substr($file,0,1) == ".") continue;   <				if (is_dir($PROD_SCRIPTBASE . $file) == FALSE) continue;   5				$final = $PROD_SCRIPTBASE . $file . "/" . $fname;   '				if (@copy($script,$final) == FALSE)   				{   ;					mylog("Unable to copy script file $script to $final");   					syslog(LOG_LOCAL0,   =						"alert: Unable to copy script file $script -> $final");   2					die('Error copy script file ('.$script.')');    				}   0				if (@rename($final, $final .".sh") == FALSE)   				{   @					mylog("Unable to rename script file $script to $final.sh");   					syslog(LOG_LOCAL0,   >					"alert: Unable to rename script file $script -> $final");   4					die('Error rename script file ('.$script.')');    				}   0				mylog("Copied script $script to $final.sh");               }               closedir($dh);   		}   	}       	if (is_dir($STAG_SCRIPTBASE))       {   		$fname = basename($script);           $retval = FALSE;   ,        if ($dh = opendir($STAG_SCRIPTBASE))   	        {   4            while (($file = readdir($dh)) !== false)               {   +				if (substr($file,0,1) == ".") continue;   <				if (is_dir($STAG_SCRIPTBASE . $file) == FALSE) continue;   5				$final = $STAG_SCRIPTBASE . $file . "/" . $fname;   '				if (@copy($script,$final) == FALSE)   				{   ;					mylog("Unable to copy script file $script to $final");   					syslog(LOG_LOCAL0,   =						"alert: Unable to copy script file $script -> $final");   2					die('Error copy script file ('.$script.')');    				}   0				if (@rename($final, $final .".sh") == FALSE)   				{   @					mylog("Unable to rename script file $script to $final.sh");   					syslog(LOG_LOCAL0,   >					"alert: Unable to rename script file $script -> $final");   4					die('Error rename script file ('.$script.')');    				}   0				mylog("Copied script $script to $final.sh");               }               closedir($dh);   		}   	}   }       Mfunction wrtappfile($acct,$subacct,$type,$desc,$active,$monitor,$bill,$maint)   {   	global $PROD_APPBASE;       	$tmp = "/tmp/$subacct.cfg";   (	$fpath = "$PROD_APPBASE$acct/$subacct";    	$final = "$fpath/$subacct.cfg";       $fp = @fopen($tmp, 'w');   
	if (!$fp)   	{   5		mylog('Error opening temp app file ('.$tmp.'): ');    3		die('Error opening temp app file ('.$tmp.'): ');    	}       R	$str = "# THIS FILE IS GENERATED AUTOMATICALLY. DO NOT DELETE. DO NOT MODIFY.\n";   !	$str = "# Application: $desc\n";   	$str .= "ACCOUNT=$acct\n";   !	$str .= "SUBACCOUNT=$subacct\n";   	$str .= "TYPE=$type\n";   4	$str .= "AGI=custom/$acct/$subacct/$subacct.php\n";   	$str .= "MONITOR=$monitor\n";   	$str .= "BILL=$bill\n";   	$str .= "ACTIVE=$active\n";    	$str .= "MAINTENANCE=$maint\n";   	fputs($fp, $str);       fclose($fp);       "	if (file_exists($fpath) == FALSE)   	{   #		mylog("Making directory $fpath");   #		$ret = mkdir($fpath, 0777, TRUE);   		if ($ret == FALSE)   		{   ;			mylog('Error mkdir app file directory ('.$fpath.'): ');    >			die('Error mkdir temp app file directory ('.$fpath.'): ');    		}   	}       )	if (files_are_same($tmp,$final) == TRUE)   J		mylog("Temp & Final app file for $final are the same. No copy needed.");   	else   	{   $		if (@rename($tmp,$final) == FALSE)   		{   5			mylog("Unable to rename APP file $tmp to $final");   G			syslog(LOG_LOCAL0, "alert: Unable to move APP file $tmp -> $final");   1			die('Error moving temp app file ('.$tmp.')');    		}   )		mylog("Moved APP file $tmp to $final");   	}   }       //* This function activates a new application */       !function activate($acct,$subacct)   {   %	global $PROD_AGIBASE, $PROD_AUDBASE;   &	global $STAG_AGIBASE, $STAG_AUDBASE;    	global $script_count, $pid;       D    $base = "/tmp/g5sync_dnis." . date("YmdHis") . "." . $pid . ".";       1	/* Make agi-bin directory if it doesn't exist */       1	$fpath = $PROD_AGIBASE . $acct . "/" . $subacct;   "	if (file_exists($fpath) == FALSE)   	{   #		mylog("Making directory $fpath");   #		$ret = mkdir($fpath, 0777, TRUE);   		if ($ret == FALSE)   		{   ;			mylog('Error mkdir agi file directory ('.$fpath.'): ');    9			die('Error mkdir agi file directory ('.$fpath.'): ');    		}   	}       /*   'Removed per CCD - 09/15/2009 - Tom Bell       1	$fpath = $STAG_AGIBASE . $acct . "/" . $subacct;   "	if (file_exists($fpath) == FALSE)   	{   #		mylog("Making directory $fpath");   #		$ret = mkdir($fpath, 0777, TRUE);   		if ($ret == FALSE)   		{   ;			mylog('Error mkdir agi file directory ('.$fpath.'): ');    9			die('Error mkdir agi file directory ('.$fpath.'): ');    		}   	}   */       /	/* Make audio directory if it doesn't exist */       1	$fpath = $PROD_AUDBASE . $acct . "/" . $subacct;   "	if (file_exists($fpath) == FALSE)   	{   #		mylog("Making directory $fpath");   #		$ret = mkdir($fpath, 0777, TRUE);   		if ($ret == FALSE)   		{   =			mylog('Error mkdir audio file directory ('.$fpath.'): ');    ;			die('Error mkdir audio file directory ('.$fpath.'): ');    		}   	}       1	$fpath = $STAG_AUDBASE . $acct . "/" . $subacct;   "	if (file_exists($fpath) == FALSE)   	{   #		mylog("Making directory $fpath");   #		$ret = mkdir($fpath, 0777, TRUE);   		if ($ret == FALSE)   		{   =			mylog('Error mkdir audio file directory ('.$fpath.'): ');    ;			die('Error mkdir audio file directory ('.$fpath.'): ');    		}   	}    	$tmp = $base . $script_count++;       $fp = @fopen($tmp, 'w');   
	if (!$fp)   	{   5		mylog('Error opening temp app file ('.$tmp.'): ');    3		die('Error opening temp app file ('.$tmp.'): ');    	}       R	$str = "# THIS FILE IS GENERATED AUTOMATICALLY. DO NOT DELETE. DO NOT MODIFY.\n";   >	$str .= "mkdir -p /var/log/asterisk/custom/$acct/$subacct\n";   <	$str .= "mkdir -p /usr/g5/captures/spool/$acct/$subacct\n";   	fputs($fp, $str);       fclose($fp);       <	copyscript($tmp);		/* Copy the script to all active IVRs */   7	@unlink($tmp);			/* Get rid of our temp script file */   }       6/* This function de-activates a current application */       #function deactivate($acct,$subacct)   {   4	global $PROD_AGIBASE, $PROD_APPBASE, $PROD_AUDBASE;   4	global $STAG_AGIBASE, $STAG_APPBASE, $STAG_AUDBASE;   =	global $PROD_ARCAPPBASE, $PROD_ARCAGIBASE, $PROD_ARCAUDBASE;   =	global $STAG_ARCAPPBASE, $STAG_ARCAGIBASE, $STAG_ARCAUDBASE;   	global $script_count, $pid;       	$now = date("YmdHis");   :    $base = "/tmp/g5sync_dnis." . $now . "." . $pid . ".";   <    $agiarchbase = $PROD_ARCAGIBASE . $subacct . "." . $now;       -	/* Archive agi-bin directory if it exists */       1	$fpath = $PROD_AGIBASE . $acct . "/" . $subacct;   !	if (file_exists($fpath) == TRUE)   	{   3		mylog("Moving directory $fpath to $agiarchbase");   ,		if (@rename($fpath,$agiarchbase) == FALSE)   		{   9			mylog("Unable to mv agi file $fpath to $agiarchbase");   			syslog(LOG_LOCAL0,    <			"alert: Unable to move agi file $fpath -> $agiarchbase");   .			die('Error moving agi file ('.$fpath.')');    		}   	}           +	/* Archive audio directory if it exists */       1	$fpath = $PROD_AUDBASE . $acct . "/" . $subacct;   <    $audarchbase = $PROD_ARCAUDBASE . $subacct . "." . $now;   !	if (file_exists($fpath) == TRUE)   	{   3		mylog("Moving directory $fpath to $audarchbase");   ,		if (@rename($fpath,$audarchbase) == FALSE)   		{   9			mylog("Unable to mv aud file $fpath to $audarchbase");   			syslog(LOG_LOCAL0,    <			"alert: Unable to move aud file $fpath -> $audarchbase");   .			die('Error moving agi file ('.$fpath.')');    		}   	}       *	/* Archive apps directory if it exists */       1	$fpath = $PROD_APPBASE . $acct . "/" . $subacct;   =    $apparchbase =  $PROD_ARCAPPBASE . $subacct . "." . $now;   !	if (file_exists($fpath) == TRUE)   	{   3		mylog("Moving directory $fpath to $apparchbase");   ,		if (@rename($fpath,$apparchbase) == FALSE)   		{   9			mylog("Unable to mv app file $fpath to $apparchbase");   			syslog(LOG_LOCAL0,    <			"alert: Unable to move app file $fpath -> $apparchbase");   .			die('Error moving app file ('.$fpath.')');    		}   	}        	$tmp = $base . $script_count++;       $fp = @fopen($tmp, 'w');   
	if (!$fp)   	{   5		mylog('Error opening temp app file ('.$tmp.'): ');    3		die('Error opening temp app file ('.$tmp.'): ');    	}       R	$str = "# THIS FILE IS GENERATED AUTOMATICALLY. DO NOT DELETE. DO NOT MODIFY.\n";   <	$str .= "rm -rf /var/log/asterisk/custom/$acct/$subacct\n";   	fputs($fp, $str);       fclose($fp);       <	copyscript($tmp);		/* Copy the script to all active IVRs */   7	@unlink($tmp);			/* Get rid of our temp script file */   }       6function wrtdnisfile($DB,$TMP_DNISFILE,$PROD_DNISFILE)   {       :	$query = "select number,subaccount,account, override_app    .		FROM g4dids where active=1 order by number";       #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}   %    $fp = @fopen($TMP_DNISFILE, 'w');   
	if (!$fp)   	{   ?		mylog('Error opening temp dnis file ('.$TMP_DNISFILE.'): ');    =		die('Error opening temp dnis file ('.$TMP_DNISFILE.'): ');    	}   
	$cnt = 0;   +	while ($row = @mysql_fetch_assoc($wrkcur))   	{   		$dnis = $row["number"];   		$acct = $row["account"];   		$sub = $row["subaccount"];   		$ap = $row["override_app"];   "		$str = "$dnis,$acct,$sub,$ap\n";   		fputs($fp, $str);   			$cnt++;   	}           fclose($fp);   	if ($cnt == 0)   	{   .		mylog("No Rows in DNIS file $TMP_DNISFILE");   		syslog(LOG_LOCAL0,    /		"alert: No Rows in DNIS file $TMP_DNISFILE");   3		die('No Rows in dnis file ('.$TMP_DNISFILE.')');    	}       	@mysql_free_result($wrkcur);       :	if (files_are_same($TMP_DNISFILE,$PROD_DNISFILE) == TRUE)   	{   B		mylog("Temp and Final DNIS file are the same. No move needed.");   		@unlink($TMP_DNISFILE);   			return;   	}       4	if (@rename($TMP_DNISFILE,$PROD_DNISFILE) == FALSE)   	{   F		mylog("Unable to rename DNIS file $TMP_DNISFILE to $PROD_DNISFILE");   		syslog(LOG_LOCAL0,    E		"alert: Unable to move DNIS file $TMP_DNISFILE -> $PROD_DNISFILE");   :		die('Error moving temp dnis file ('.$TMP_DNISFILE.')');    	}   :	mylog("Moved DNIS file $TMP_DNISFILE to $PROD_DNISFILE");   }   =/* See if a sync request is outstanding. Return TRUE if so */       function issyncreq($DB)   {   M	$query = "select count(*) as cnt from g4dnis_sync where sync_requested = 1";       #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}   (	if ($row = @mysql_fetch_assoc($wrkcur))   	{   		$cnt = $row['cnt'];   		if ($cnt == 0)   		{   			@mysql_free_result($wrkcur);   			return(FALSE);   		}   	}   	@mysql_free_result($wrkcur);   L	$query = "update1 g4dnis_sync set sync_requested=2 where sync_requested=1";   #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}   	return(TRUE);   }       function   isdnissyncreq($DB)   {   6	$query = "select count(*) as cnt from g4dids_history    		where operation_request = 1";       #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}   (	if ($row = @mysql_fetch_assoc($wrkcur))   	{   		$cnt = $row['cnt'];   		if ($cnt == 0)   		{   			@mysql_free_result($wrkcur);   			return(FALSE);   		}   	}   	@mysql_free_result($wrkcur);   :	$query = "update1 g4dids_history set operation_request=2    				where operation_request=1";   #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}   :	mylog("There are $cnt Dnis Sync Request(s) Outstanding");   	return(TRUE);   }       function   isapprequests($DB)   {   6	$query = "select count(*) as cnt from g4apps_history    		where operation_request = 1";       #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   9		mylog('Invalid query ('.$query.'): ' . mysql_error());    7		die('Invalid query ('.$query.'): ' . mysql_error());    	}   (	if ($row = @mysql_fetch_assoc($wrkcur))   	{   		$cnt = $row['cnt'];   		if ($cnt == 0)   		{   2			mylog("There are no App requests outstanding");   			@mysql_free_result($wrkcur);   			return(FALSE);   		}   	}   	@mysql_free_result($wrkcur);   :	$query = "update1 g4apps_history set operation_request=2    				where operation_request=1";   #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}   2	mylog("There are $cnt App requests outstanding");   	return(TRUE);   }       @/* Returns TRUE if any future dnis activations coming due now */       function   isdnisfutact($DB,$force_all)   {   	$now = date("Y-m-d H:i:s");   	if ($force_all)   	{   8		$query = "select number,account,subaccount from g4dids   			where active = 1";   	}   	else   	{   8		$query = "select number,account,subaccount from g4dids   8			where active = 0 and activate_dts > '1970-01-01' and    			activate_dts <= '$now'";   	}       #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   9		mylog('Invalid query ('.$query.'): ' . mysql_error());    7		die('Invalid query ('.$query.'): ' . mysql_error());    	}   
	$cnt = 0;   +	while ($row = @mysql_fetch_assoc($wrkcur))   	{   		$did = $row['number'];   		$account = $row['account'];    		$subacct = $row['subaccount'];   )		wrtdidfile($DB,$account,$subacct,$did);   			$cnt++;   	}       	if ($cnt == 0)   	{   3		mylog("Found NO DNIS that need to be activated");   		return(FALSE);   	}       	@mysql_free_result($wrkcur);   E	mylog("Found $cnt DNIS that need to be activated -- rebuild table");       	if ($force_all)   	{   3		mylog("Full DNIS activation refresh requested.");   		return(TRUE);   	}           A	$query = "update1 g4dids set active=1,activate_dts='1970-01-01'    @	where active = 0 and activate_dts > '1970-01-01' and              !	        activate_dts <= '$now'";   #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   9		mylog('Invalid query ('.$query.'): ' . mysql_error());    7		die('Invalid query ('.$query.'): ' . mysql_error());    	}   	return(TRUE);   }       C/* Returns TRUE if any future dnis de-activations coming due now */       function   isdnisfutdeact($DB,$force_all)   {   	$now = date("Y-m-d H:i:s");   	if ($force_all)   	{   8		$query = "select number,account,subaccount from g4dids   			where active = 0";   	}   	else   	{   8		$query = "select number,account,subaccount from g4dids   :			where active = 1 and deactivate_dts < '2036-12-31' and    			deactivate_dts <= '$now'";   	}       #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}   
	$cnt = 0;   +	while ($row = @mysql_fetch_assoc($wrkcur))   	{   		$did = $row['number'];   		$account = $row['account'];    		$subacct = $row['subaccount'];   %		deldidfile($account,$subacct,$did);   			$cnt++;   	}       	if ($cnt == 0)   	{   6		mylog("Found NO DNIS that need to be de-activated");   		return(FALSE);   	}   	@mysql_free_result($wrkcur);   H	mylog("Found $cnt DNIS that need to be de-activated -- rebuild table");       	if ($force_all)   	{   5		mylog("Full refresh DNIS de-activated requested.");   		return(TRUE);   	}       L	$query = "update1 g4dids set active=0,deactivate_dts='2036-12-31 23:59:59'    B	where active = 1 and deactivate_dts < '2036-12-31' and              #	        deactivate_dts <= '$now'";   #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   9		mylog('Invalid query ('.$query.'): ' . mysql_error());    7		die('Invalid query ('.$query.'): ' . mysql_error());    	}   	return(TRUE);   }       function   updsyncreq($DB)   {   4	$query = "update1 g4dnis_sync set sync_requested=0,   4			sync_complete_date=now() where sync_requested=2";   #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}   	return(TRUE);   }       function upddnissyncreq($DB)   {   :	$query = "update1 g4dids_history set operation_request=0,   2		operation_comp=now() where operation_request=2";   #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   :			mylog('Invalid query ('.$query.'): ' . mysql_error());    8			die('Invalid query ('.$query.'): ' . mysql_error());    	}   	return(TRUE);   }       function   updappssyncreq($DB)   {   :	$query = "update1 g4apps_history set operation_request=0,   6		operation_comp_dts=now() where operation_request=2";   #	$wrkcur = mysql_query($query,$DB);   	if (!$wrkcur)    	{   9		mylog('Invalid query ('.$query.'): ' . mysql_error());    7		die('Invalid query ('.$query.'): ' . mysql_error());    	}   	return(TRUE);   }       /*    1This function compares the contents of two files.   7If they are the same it returns TRUE else returns FALSE   */       function   files_are_same($f1,$f2)   {   	$s1 = @sha1_file($f1);   	$s2 = @sha1_file($f2);       "	if ($s1 == FALSE || $s2 == FALSE)   		return(FALSE);       	if ($s1 != $s2)   		return(FALSE);       	return(TRUE);   }        	function    mylog($str)    {       global  $pid;       5    $logfile="/var/log/g5sync_dnis.log.".date("Ymd");       $fp = fopen($logfile, 'a');   =    fputs($fp, date("Y-m-d H:i:s ")."[".$pid."] ".$str."\n");       fclose($fp);   }5�_�                             ����                                                                                                                                                                                                                                                                                                                                                             UA/�     �                0$PROD_DNISFILE="/etc/asterisk/custom/dnis.list";�                &$PROD_APPBASE="/etc/asterisk/custom/";�                0$STAG_DNISFILE="/etc/asterisk/custom/dnis.list";�                &$STAG_APPBASE="/etc/asterisk/custom/";5��