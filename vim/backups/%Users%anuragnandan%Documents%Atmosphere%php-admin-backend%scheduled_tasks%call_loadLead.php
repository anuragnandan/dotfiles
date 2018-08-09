Vim�UnDo� �����|f����l�X&��]�f����8   p   $debug = true;             %       %   %   %    W0��   	 _�                             ����                                                                                                                                                                                                                                                                                                                                                             W0�_     �               m   <?php   /*    *@author: Anurag Rpaerthy   I @purpose: This script monitors a FTP directory for any CSV files sitting   D           in the directory for more than 5 mins. If yes, it should    =           call LoadLeads webservice for the data in the file    */       /require_once dirname(__FILE__) . '/GetOpt.php';       $options = _getopt(NULL, array(             'dir_to_scan::',           ));   #if(!isset($options['dir_to_scan']))   *	die("No directory mentioned to scan \n");   -$directory_to_scan = $options['dir_to_scan'];   $file_format = ".csv";   |$load_lead_url = "http://amfphp.prosodieinteractive.com/services/Atmosphere/soapwebservices/LeadService/LeadService-S.wsdl";   $mtime = 0;   $debug = true;   )$LOGFILE = "/var/log/call_loadLeads.log";   $app = "call_loadLeads";   $de_dupe = "1";   $multiple_leads = "1";           3$scan = glob($directory_to_scan."/*".$file_format);   if(count($scan) > 0)   {     foreach($scan as $file)     {       $file_stats = stat($file);       //var_dump($file_stats);   .    if($file_stats['mtime'] < time() - $mtime)       {         $leads = read_csv($file);   K	  if(!isset($leads[0]['security_key']) || $leads[0]['security_key'] == '')   	  {   6		mylog('No security key sent => '.print_r($leads,1));   			exit();   	  }   -	  $security_key = $leads[0]['security_key'];         //var_dump($leads);         //exit();         $lead_count = 1;   �      $client = new SoapClient($load_lead_url,array('login'=>'advantone_client','password'=>'2a0d3fc35605c2c41168ecda4c0d9cfe'));         //var_dump($client);   �      $request = array("security_key"=>$security_key,"de_dupe"=>$de_dupe,"multi_leads_per_house"=>$multiple_leads,'leads'=>$leads);   5      $output = $client->LoadLeads((object)$request);         //var_dump($output);   R	  mylog("Response: ".print_r($output,1)." \n for lead data: ".print_r($leads,1));           }       else       {   		mylog("Too soon for $file");       }     }   }   else   {   /	mylog("No files found in directory to load.");   }   function read_csv($file)   {   /  if (($handle = fopen($file, "r")) !== FALSE)      {       $row = 0;       $lead_data = array();   <    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)        {         if($row == 0)         {           $fields = $data;           //var_dump($fields);         }   
      else         {           $lead = $data;   .        for($i = 0; $i < count($fields); $i++)   	        {             if(!isset($lead[$i]))               $lead[$i] = "";   6          $lead_data[$row-1][$fields[$i]] = $lead[$i];   	        }   5        if(!isset($lead_data[$row-1]['external_id']))   3          $lead_data[$row-1]['external_id'] = $row;         }         $row++;       }       fclose($handle);       return $lead_data;     }     else   	die("Cant read file $file");    }   function mylog($str)   {        global $debug,$LOGFILE,$app;       if($debug)       {   "        $fp = fopen($LOGFILE,"a");   N        fputs($fp,date("M:j:Y-H:i:s")."-PID=".getmypid()."[$app] ".$str."\n");           fclose($fp);       }   }           ?>5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0�k    �         m       *@author: Anurag Rpaerthy5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0�y    �         m      I @purpose: This script monitors a FTP directory for any CSV files sitting5�_�                       
    ����                                                                                                                                                                                                                                                                                                                                                             W0΍    �         m      $mtime = 0;5�_�                       	    ����                                                                                                                                                                                                                                                                                                                                                             W0Κ     �         n      
          �         m    5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0Μ     �         n                ''5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0Ν     �         n                ''5�_�      	                     ����                                                                                                                                                                                                                                                                                                                                                             W0Ρ     �         n                'mtime'5�_�      
           	          ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         o       �         n    5�_�   	              
          ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         o      if()5�_�   
                        ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         o      if()5�_�                       	    ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         o      if(isset())5�_�                       	    ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         o      if(isset())5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         o      if(isset($options[]))5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         o      if(isset($options['']))5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         o      if(isset($options['mtime']))5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         p        $mtime = $options[]5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         p        $mtime = $options[]5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         p        $mtime = $options['']5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         p        $mtime = $options['']5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0��     �         p        $mtime = $options['mtime']5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0��    �         p      #if(!isset($options['dir_to_scan']))�         p      *	die("No directory mentioned to scan \n");5�_�                       #    ����                                                                                                                                                                                                                                                                                                                                                             W0�N     �         p      *	die("No directory mentioned to scan \n");5�_�                       Y    ����                                                                                                                                                                                                                                                                                                                                                             W0�|     �         p      c	die("No directory mentioned to scan. Right way to use: php call_loadLeads --dir_to_scan /tmp \n");5�_�                       \    ����                                                                                                                                                                                                                                                                                                                                                             W0�}     �         p      c	die("No directory mentioned to scan. Right way to use: php call_loadLeads --dir_to_scan=/tmp \n");5�_�                       ^    ����                                                                                                                                                                                                                                                                                                                                                             W0π    �         p      c	die("No directory mentioned to scan. Right way to use: php call_loadLeads --dir_to_scan=/tmp \n");5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             W0Ϛ     �               p   <?php   /*    *@author: Anurag Raperthy   J  @purpose: This script monitors a FTP directory for any CSV files sitting   D           in the directory for more than 5 mins. If yes, it should    =           call LoadLeads webservice for the data in the file    */       /require_once dirname(__FILE__) . '/GetOpt.php';       $options = _getopt(NULL, array(             'dir_to_scan::',             'mtime::'           ));   #if(!isset($options['dir_to_scan']))   �	die("No directory mentioned to scan. Right way to use: php call_loadLeads --dir_to_scan=/tmp --mtime=5 . mtime is in minutes.\n");   if(isset($options['mtime']))     $mtime = $options['mtime'];   -$directory_to_scan = $options['dir_to_scan'];   $file_format = ".csv";   |$load_lead_url = "http://amfphp.prosodieinteractive.com/services/Atmosphere/soapwebservices/LeadService/LeadService-S.wsdl";   $mtime = 5;   $debug = true;   )$LOGFILE = "/var/log/call_loadLeads.log";   $app = "call_loadLeads";   $de_dupe = "1";   $multiple_leads = "1";           3$scan = glob($directory_to_scan."/*".$file_format);   if(count($scan) > 0)   {     foreach($scan as $file)     {       $file_stats = stat($file);       //var_dump($file_stats);   .    if($file_stats['mtime'] < time() - $mtime)       {         $leads = read_csv($file);   K	  if(!isset($leads[0]['security_key']) || $leads[0]['security_key'] == '')   	  {   6		mylog('No security key sent => '.print_r($leads,1));   			exit();   	  }   -	  $security_key = $leads[0]['security_key'];         //var_dump($leads);         //exit();         $lead_count = 1;   �      $client = new SoapClient($load_lead_url,array('login'=>'advantone_client','password'=>'2a0d3fc35605c2c41168ecda4c0d9cfe'));         //var_dump($client);   �      $request = array("security_key"=>$security_key,"de_dupe"=>$de_dupe,"multi_leads_per_house"=>$multiple_leads,'leads'=>$leads);   5      $output = $client->LoadLeads((object)$request);         //var_dump($output);   R	  mylog("Response: ".print_r($output,1)." \n for lead data: ".print_r($leads,1));           }       else       {   		mylog("Too soon for $file");       }     }   }   else   {   /	mylog("No files found in directory to load.");   }   function read_csv($file)   {   /  if (($handle = fopen($file, "r")) !== FALSE)      {       $row = 0;       $lead_data = array();   <    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)        {         if($row == 0)         {           $fields = $data;           //var_dump($fields);         }   
      else         {           $lead = $data;   .        for($i = 0; $i < count($fields); $i++)   	        {             if(!isset($lead[$i]))               $lead[$i] = "";   6          $lead_data[$row-1][$fields[$i]] = $lead[$i];   	        }   5        if(!isset($lead_data[$row-1]['external_id']))   3          $lead_data[$row-1]['external_id'] = $row;         }         $row++;       }       fclose($handle);       return $lead_data;     }     else   	die("Cant read file $file");    }   function mylog($str)   {        global $debug,$LOGFILE,$app;       if($debug)       {   "        $fp = fopen($LOGFILE,"a");   N        fputs($fp,date("M:j:Y-H:i:s")."-PID=".getmypid()."[$app] ".$str."\n");           fclose($fp);       }   }           ?>5�_�                       
    ����                                                                                                                                                                                                                                                                                                                                                             W0Ϟ     �         q       �         p    5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             W0Ϡ     �                else5�_�                            ����                                                                                                                                                                                                                                                                                                                                                 v���    W0ϧ     �         p      if(isset($options['mtime']))     $mtime = $options['mtime'];   -$directory_to_scan = $options['dir_to_scan'];5�_�                             ����                                                                                                                                                                                                                                                                                                                                                 v���    W0ϫ    �         n      $debug = true;�         n    5�_�      !                      ����                                                                                                                                                                                                                                                                                                                                                 v���    W0Ϲ     �         p      if(isset($options['mtime']))5�_�       "           !      *    ����                                                                                                                                                                                                                                                                                                                                                 v���    W0Ͼ     �         p      ,if(isset($options['mtime']) && is_numeric())5�_�   !   #           "      3    ����                                                                                                                                                                                                                                                                                                                                                 v���    W0��     �         p      6if(isset($options['mtime']) && is_numeric($options[]))5�_�   "   $           #      3    ����                                                                                                                                                                                                                                                                                                                                                 v���    W0��     �         p      6if(isset($options['mtime']) && is_numeric($options[]))5�_�   #   %           $      4    ����                                                                                                                                                                                                                                                                                                                                                 v���    W0��     �         p      8if(isset($options['mtime']) && is_numeric($options['']))5�_�   $               %      4    ����                                                                                                                                                                                                                                                                                                                                                 v���    W0��   	 �         p      8if(isset($options['mtime']) && is_numeric($options['']))5��