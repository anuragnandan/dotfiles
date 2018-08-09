Vim�UnDo� �*J�4��ox�Ҹm�]�)@S�i�{T���`�  I   :    $campaign_sql = " AND c.campaign_id = '$campaign_id'";   �                          XA��    _�                             ����                                                                                                                                                                                                                                                                                                                                                             XA�-     �              :   #!/bin/env php   <?php   /*    * @author: Anurag Raperthy   @ * @purpose: Script to generate a csv file with counts per day,    I *           and per week cumulative for each dispoition of all the calls    *           for Unbridled.    *    */   /require_once dirname(__FILE__) . '/GetOpt.php';   -require_once dirname(__FILE__) . '/Mail.php';   2require_once dirname(__FILE__) . '/Mail/mime.php';       $options = _getopt(NULL, array(             'campaign::',             'ftp::',             'destination_dir::',             'emailaddress::',             'start_date::',             'end_date::',             'campaign_name::',           ));        if(!isset($options['campaign']))     $campaign_id = "ALL";   else   &  $campaign_id = $options['campaign'];       if(!isset($options['ftp']))     $ftp = false;   else     $ftp = $options['ftp'];       .date_default_timezone_set('America/New_York');   $file_format = ".csv";   $debug = true;   /$LOGFILE = "/var/log/leads_attempted_rate.log";   $app = "leads_attempted_rate";   -$config_file = '/etc/atmosphere/general.ini';   $$ini = parse_ini_file($config_file);    $new_ini['host'] = $ini['host'];    $new_ini['user'] = $ini['user'];   $$new_ini['password'] = $ini['pass'];   <$csv_file_name = "Leads_Attempted_Rate-".date("m.d").".csv";   +$last_friday = new DateTime("this friday");   +$last_monday = new DateTime("this friday");   :$last_monday = $last_monday->sub(new DateInterval('P6D'));   8$start_date = $last_monday->format("Y-m-d")." 00:00:00";   6$end_date = $last_friday->format("Y-m-d")." 23:59:59";   'if(!isset($options['destination_dir']))   5  $file_path = "/CustomerArchive/17720000/17720010/";   else   +  $file_path = $options['destination_dir'];   $ftp_directory = "";   $ftp_location = '338';   4$emailRecipients = array("araperthy@advantone.com");   #if(isset($options['emailaddress']))   5  $emailRecipients = array($options['emailaddress']);   !if(isset($options['start_date']))   .  $custom_start_date = $options['start_date'];   if(isset($options['end_date']))   *  $custom_end_date = $options['end_date'];   $if(isset($options['campaign_name']))   -  $campaign_name = $options['campaign_name'];   else     $campaign_name = "";   E$csv_file_name = "$campaign_name.SpeedToAttempt.".date("m.d").".csv";       0$replication_params = get_replication($new_ini);    if($replication_params == false)   {   7  mylog("Failed to get replication connection params");   6  die("Could not finish the process. Check $LOGFILE");   }       -$replication_params = $replication_params[0];   $counts = get_counts();   &//$counts = calculate_totals($counts);   create_csv($counts);   *if(file_exists($file_path.$csv_file_name))   {     if($ftp !== false)   '    ftp_csv($file_path,$csv_file_name);   }   else   Q  die("Cannot find file $file_path$csv_file_name to ftp. Please investigate.\n");   %email_csv($file_path,$csv_file_name);       (function email_csv($filepath, $filename)   {   *  global $emailRecipients, $campaign_name;   .  foreach($emailRecipients as $emailRecipient)     {   	      try         {   +          // Instantiate PEAR's Mail class:   )          $Mail =& Mail::factory('mail');                 // Build mail:   8          $Mime = new Mail_mime(array('eol' => "\r\n"));                 $headers = array(   5              'From'      => 'noreply@advantone.com',   R              'Subject'   => 'Speed To Attempt- '.$campaign_name."-".date("m.d") ,             );       K          $Mime->addAttachment($filepath.$filename, 'text/csv', $filename);                 $body = $Mime->get();   .          $headers = $Mime->headers($headers);                 // Send:   E          if($Mail->send($emailRecipient, $headers, $body) !== TRUE)    Z            throw new Exception('Error while sending mail to [' . $emailRecipient . ']!');             else   R            mylog("File $filepath$filename emailed to ".print_r($emailRecipient));         }         catch (Exception $e)         {   L          mylog('Caught exception while sending mail: ' . $e->getMessage());   d          syslog(LOG_ERR, 'Cannot send Leads_Attempted_Rate Report to: [' . $emailRecipient . ']!');             continue;         }     }   }       %function ftp_csv($filepath,$filename)   {   ;  global $new_ini, $ftp_location, $ftp_directory, $LOGFILE;    $sql = "INSERT INTO asterisk.`vicidial_scheduler_ftp_requests` ( `scheduler_history_run_id`, `filename`, `schedule_id`, `schedule_name`, `schedule_type`, `create_date`, `last_update`, `ftp_loc_id`, `directory`, `status`, `failure_description`, `current_try`, `max_tries`)   
    VALUES   q      (0, '$filepath.$filename', 0, '', '', NOW(), NOW(), $ftp_location, '$ftp_directory', 'PENDING', '', 0, 3)";   %  $result = run_query($sql,$new_ini);     if($result == false)   F    mylog("Failed to insert into scheduler table. Check $LOGFILE \n");   }       function create_csv($rows)   {   K  global $app,$LOGFILE, $columns,$file_path,$csv_file_name, $campaign_name;   )  $fp = fopen("/tmp/$csv_file_name","w");     $row_count = 0;   ;  $output_string = "$campaign_name,% of Leads Attempted\n";   .  $output_string .= "5m,".$rows[0]['5m']."\n";   0  $output_string .= "15m,".$rows[0]['15m']."\n";   0  $output_string .= "30m,".$rows[0]['30m']."\n";   .  $output_string .= "1h,".$rows[0]['1h']."\n";   .  $output_string .= "2h,".$rows[0]['2h']."\n";   .  $output_string .= "4h,".$rows[0]['4h']."\n";   .  $output_string .= "6h,".$rows[0]['6h']."\n";   0  $output_string .= "12h,".$rows[0]['12h']."\n";   0  $output_string .= "24h,".$rows[0]['24h']."\n";   1  $output_string .= ">24h,".$rows[0]['25h']."\n";     fputs($fp, $output_string);     fclose($fp);     if(!file_exists($file_path))        mkdir($file_path,0727,true);   ;  @rename("/tmp/$csv_file_name",$file_path.$csv_file_name);     if(sizeof($rows) <= 0)     {   Y    mylog("$file_path.$csv_file_name not emailed. Check the file. Ivalid data for $app");   b    syslog(LOG_WARNING,"$file_path.$csv_file_name not emailed. Check the file. Ivalid data $app");   W    die("$file_path.$csv_file_name not emailed. Check the file. Ivalid data for $app");     }   }       function get_counts()   {   �  global $replication_params, $start_date,$end_date,$app,$LOGFILE, $columns, $campaign_id, $custom_end_date, $custom_start_date;       if(date('N') > 5)    $last_this = "last";   else    $last_this = "this";       3  $last_friday = new DateTime("$last_this friday");   3  $last_monday = new DateTime("$last_this friday");   <  $last_monday = $last_monday->sub(new DateInterval('P4D'));   <    $start_date = $last_monday->format("Y-m-d")." 00:00:00";   :    $end_date = $last_friday->format("Y-m-d")." 23:59:59";     if(isset($custom_start_date))   %    $start_date = $custom_start_date;     if(isset($custom_end_date))   !    $end_date = $custom_end_date;     $rows = array();     $campaign_sql = "";     $campaign_vlog_sql = "";     if($campaign_id != "ALL")     {   :    $campaign_sql = " AND c.campaign_id = '$campaign_id'";   =    $campaign_vlog_sql = " AND campaign_id = '$campaign_id'";     }   (  $client_id = substr($campaign_id,0,3);       H  $sql = "SELECT ROUND(SUM(a.5MIN)/SUM(a.leads_accepted)*100,2) as '5m',   J                 ROUND(SUM(a.15MIN)/SUM(a.leads_accepted)*100,2) as '15m',   J                 ROUND(SUM(a.30MIN)/SUM(a.leads_accepted)*100,2) as '30m',   G                 ROUND(SUM(a.1HR)/SUM(a.leads_accepted)*100,2) as '1h',   G                 ROUND(SUM(a.2HR)/SUM(a.leads_accepted)*100,2) as '2h',   G                 ROUND(SUM(a.4HR)/SUM(a.leads_accepted)*100,2) as '4h',   G                 ROUND(SUM(a.6HR)/SUM(a.leads_accepted)*100,2) as '6h',   I                 ROUND(SUM(a.12HR)/SUM(a.leads_accepted)*100,2) as '12h',   I                 ROUND(SUM(a.24HR)/SUM(a.leads_accepted)*100,2) as '24h',   I                 ROUND(SUM(a.25HR)/SUM(a.leads_accepted)*100,2) as '25h',   8                 SUM(a.leads_accepted) as leads_accepted   .          FROM (SELECT log.*,lr.leads_accepted              FROM   4           (Select date(l.entry_date) as event_date,   S                       SUM(if(TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) <=5    �                          OR (l.status like 'DNC%' AND l.modify_date < DATE_ADD(l.entry_date, INTERVAL 5 MINUTE)),1,0)) as '5MIN',   T                       SUM(if(TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) <=15    V                              and TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 5   7                              OR (l.status like 'DNC%'    a                                  AND l.modify_date < DATE_ADD(l.entry_date, INTERVAL 15 MINUTE)    Z                                  AND TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 5   5                                  ),1,0)) as '15MIN',   �                       SUM(if(TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) <=30 and TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 15   �                          OR (l.status like 'DNC%' AND l.modify_date < DATE_ADD(l.entry_date, INTERVAL 30 MINUTE) AND TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 15),1,0)) as '30MIN',   �                       SUM(if(TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) <= 60 and TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 30   �                         OR (l.status like 'DNC%' AND l.modify_date < DATE_ADD(l.entry_date, INTERVAL 60 MINUTE) AND TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 30),1,0)) as '1HR',   �                       SUM(if(TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) <=120 and TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 60   �                          OR (l.status like 'DNC%' AND l.modify_date < DATE_ADD(l.entry_date, INTERVAL 120 MINUTE) AND TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 60),1,0)) as '2HR',   �                          SUM(if(TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) <= 240 and TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 120   �                          OR (l.status like 'DNC%' AND l.modify_date < DATE_ADD(l.entry_date, INTERVAL 240 MINUTE) AND TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 120),1,0)) as '4HR',   �                       SUM(if(TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) <= 360 and TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 240   �                          OR (l.status like 'DNC%' AND l.modify_date < DATE_ADD(l.entry_date, INTERVAL 360 MINUTE) AND TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 240),1,0)) as '6HR',   �                       SUM(if(TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) <= 720 and TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 360   �                          OR (l.status like 'DNC%' AND l.modify_date < DATE_ADD(l.entry_date, INTERVAL 720 MINUTE) AND TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 360),1,0)) as '12HR',   �                       SUM(if(TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) <=1440 and TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 720   �                         OR (l.status like 'DNC%' AND l.modify_date < DATE_ADD(l.entry_date, INTERVAL 1440 MINUTE) AND TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 720),1,0)) as '24HR',   G					   SUM(if(TIMESTAMPDIFF(MINUTE,l.entry_date ,ll.call_date) > 1440    �                         OR (l.status like 'DNC%' AND l.modify_date > DATE_ADD(l.entry_date, INTERVAL 1440 MINUTE)),1,0)) as '25HR'       -                FROM asterisk.vicidial_list l   O                JOIN asterisk.vicidial_lists lists on lists.list_id = l.list_id   W                JOIN asterisk.vicidial_campaigns c on c.campaign_id = lists.campaign_id   G                LEFT JOIN ( select min(call_date) as call_date, lead_id   6                            from asterisk.vicidial_log   _                            where call_date > '$start_date' AND campaign_id like '$client_id%'    4                                 $campaign_vlog_sql    J                            group by lead_id) ll on l.lead_id = ll.lead_id   L                WHERE l.entry_date BETWEEN '$start_date' AND '$end_date' AND   g                      l.list_id like '$client_id%' AND c.campaign_id LIKE '$client_id%'  $campaign_sql    0                GROUP BY date(l.entry_date)) log                   JOIN    ^                (SELECT sum(l.leads_accepted) as leads_accepted,date(event_date) as event_date   =                 FROM asterisk.vicidial_lists_load_requests l   Q                  JOIN asterisk.vicidial_lists lists on lists.list_id = l.list_id   W                JOIN asterisk.vicidial_campaigns c on c.campaign_id = lists.campaign_id   M                WHERE l.event_date BETWEEN '$start_date' AND '$end_date' AND    g                      l.list_id like '$client_id%' AND c.campaign_id LIKE '$client_id%'  $campaign_sql    0                      group by date(event_date)    7                 ) lr on lr.event_date = log.event_date                   ) a";       0  $result = run_query($sql,$replication_params);   ,  if(!is_array($result) && $result == false)   L    die("Failed to get counts for $app with [ $sql ]. \n Check $LOGFILE\n");         $rows = $result;     return $rows;   }       function get_replication($ini)   {   =  $sql = "SELECT hostname AS host, username AS user, password   -          FROM atm_globals.atmosphere_schemas   H          WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";     return run_query($sql,$ini);   }       function run_query($sql,$ini)   {     global $LOGFILE, $app;   E  $conn = mysqli_connect($ini['host'],$ini['user'],$ini['password']);     if(!$conn)     {   O    mylog("Cannot connect to ".print_r($ini,1)." Reason:".mysqli_error($conn));       return false;     }     mylog("Running query $sql");   &  $result = mysqli_query($conn, $sql);     if($result === FALSE)     {   D    mylog("Query Error: ".mysqli_error($conn)." with Query : $sql");       mysqli_close($conn);       return false;     }     $rows = array();   .  if(substr(strtoupper($sql),0,6) == 'SELECT')     {   -    while($row = mysqli_fetch_assoc($result))   	{         $rows[] = $row;   	}     }     else   (    $rows = mysqli_affected_rows($conn);     mysqli_close($conn);     return $rows;       }       function mylog($str)   {        global $debug,$LOGFILE,$app;       if($debug)       {   "        $fp = fopen($LOGFILE,"a");   N        fputs($fp,date("M:j:Y-H:i:s")."-PID=".getmypid()."[$app] ".$str."\n");           fclose($fp);       }   }   ?>5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             XA�2     �        :    �        :    5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             XA�3     �        ;                'campaign::',5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             XA�>     �      )  ;       �        ;    5�_�                    (        ����                                                                                                                                                                                                                                                                                                                            (           -           v        XA�B     �   '   )  K           if(!isset($options['campaign']))     $campaign_id = "ALL";   else   &  $campaign_id = $options['campaign'];       if(!isset($options['ftp']))5�_�                    "        ����                                                                                                                                                                                                                                                                                                                            (           (           v        XA�H     �   !   "            $dataset = 89;5�_�                            ����                                                                                                                                                                                                                                                                                                                            '           '           v        XA�I     �                  $dataset = 72;5�_�      	              �       ����                                                                                                                                                                                                                                                                                                                            &           &           v        XA�Q     �   �   �  C      if(date('N') > 5)5�_�      
           	   �   *    ����                                                                                                                                                                                                                                                                                                                            &           &           v        XA�U     �   �   �  C      3  $last_friday = new DateTime("$last_this friday");5�_�   	              
   �   *    ����                                                                                                                                                                                                                                                                                                                            &           &           v        XA�X     �   �   �  C      3  $last_monday = new DateTime("$last_this friday");5�_�   
                 �   6    ����                                                                                                                                                                                                                                                                                                                            &           &           v        XA�\     �   �   �  C      <  $last_monday = $last_monday->sub(new DateInterval('P4D'));5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            &           &           v        XA�b     �   �   �  C        if($campaign_id != "ALL")5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            &           &           v        XA�g     �   �   �          (  $client_id = substr($campaign_id,0,3);5�_�                    �        ����                                                                                                                                                                                                                                                                                                                            &           &           v        XA�h     �   �   �  B    �   �   �  B    5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            &           &           v        XA�j     �   �   �  C      (  $client_id = substr($campaign_id,0,3);5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA�p     �   �   �  D        �   �   �  C    5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA�r     �   �   �  E        {}5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA�t     �   �   �  F        }�   �   �  F    5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA�u     �   �   �  I      ' $client_id = substr($campaign_id,0,3);5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA�u     �   �   �  I      )   $client_id = substr($campaign_id,0,3);5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA�v     �   �   �  I      +     $client_id = substr($campaign_id,0,3);5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA�}     �   �   �  I      �  global $replication_params, $start_date,$end_date,$app,$LOGFILE, $columns, $campaign_id, $custom_end_date, $custom_start_date;5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA��     �   �   �  I      *    $client_id = substr($campaign_id,0,3);5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA��     �   �   �  I      =    $campaign_vlog_sql = " AND campaign_id = '$campaign_id'";5�_�                    �   +    ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA��     �   �   �  I      9    $campaign_vlog_sql = " AND list_id = '$campaign_id'";5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA��     �   �   �  I      :    $campaign_sql = " AND c.campaign_id = '$campaign_id'";5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA��     �   �   �  I      ;    $campaign_sql = " AND l..campaign_id = '$campaign_id'";5�_�                    �       ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA��     �   �   �  I      :    $campaign_sql = " AND l.campaign_id = '$campaign_id'";5�_�                     �   (    ����                                                                                                                                                                                                                                                                                                                            �          �   =       v���    XA��    �   �   �  I      6    $campaign_sql = " AND l.list_id = '$campaign_id'";5��