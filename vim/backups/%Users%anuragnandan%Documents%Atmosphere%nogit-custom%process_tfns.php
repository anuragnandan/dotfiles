Vim�UnDo� 7��藼[��0�*L�n��.�UryRL�!i]�=�   �           K      9       9   9   9    Wt	D    _�                             ����                                                                                                                                                                                                                                                                                                                                                             WdhB     �               �   <?php   /require_once dirname(__FILE__) . '/GetOpt.php';   $debug = true;   '$LOGFILE = "/var/log/process_tfns.log";   $app = "process_tfns";       $options = _getopt(NULL, array(             'file_to_process::',             'client_id::',           ));   |if(!isset($options['file_to_process']) || substr($options['file_to_process'],-3) != 'csv' || !isset($options['client_id']) )   ,	die("Invalid file to process provided \n");       h$replication_parameters = array("host"=>"bop6atmbil01","user"=>"bruce_dba","password"=>"mysql_smokes!");   /$file_to_process = $options['file_to_process'];   '$tfn_data = read_csv($file_to_process);   $tfns = array();   !foreach($tfn_data as $tfn=>$data)     $tfns[] = "'".$tfn."'";   #$client_id = $options['client_id'];       !$tfn_string = implode(",",$tfns);   "$sql = "SELECT did_id, did_pattern   +        FROM asterisk.vicidial_inbound_dids   G        WHERE did_pattern IN ($tfn_string) AND client_id = $client_id";       2$result = run_query($sql,$replication_parameters);   if($result == false)   #  die("Failed to get did info.\n");       foreach($result as $tfn_id)   {   B  $tfn_data[$tfn_id['did_pattern']]['did_id'] = $tfn_id['did_id'];   }       //var_dump($tfn_data);   $sql = "SELECT field_id,name   3        FROM asterisk.vicidial_inbound_custom_field   6        WHERE client_id = $client_id AND deleted = 0";   ;$metadata_fields = run_query($sql,$replication_parameters);   8//mylog("metadata fields:".print_r($metadata_fields,1));   $did_cf = array();   	$row = 0;   !foreach($tfn_data as $tfn=>$data)   {   .  foreach($metadata_fields as $metadata_field)     {   .    $did_cf[$row]['did_id'] = $data['did_id'];   <    $did_cf[$row]['field_id'] = $metadata_field['field_id'];   <    $did_cf[$row]['value'] = $data[$metadata_field['name']];       $row++;     }   }       c$sql_stmt = "REPLACE INTO asterisk.vicidial_inbound_dids_cf (`did_id`,`field_id`,`value`) VALUES ";   foreach($did_cf as $cf)   {   O  $sql_stmt .= "\n(".$cf['did_id'].",".$cf['field_id'].",'".$cf['value']."'),";   }       4$sql_stmt = substr($sql_stmt,0,strlen($sql_stmt)-1);       '$fp = fopen(date("YmdHis").".sql",'w');   fputs($fp,$sql_stmt);   fclose($fp);   echo "Done";       function run_query($sql,$ini)   {     global $LOGFILE, $app;   E  $conn = mysqli_connect($ini['host'],$ini['user'],$ini['password']);     if(!$conn)     {   G    mylog("Cannot connect to ".print_r($ini,1)." Reason:".mysql_error);       return false;     }     mylog("Running query $sql");   &  $result = mysqli_query($conn, $sql);     if($result === FALSE)     {   D    mylog("Query Error: ".mysqli_error($conn)." with Query : $sql");       mysqli_close($conn);       return false;     }     $rows = array();   .  if(substr(strtoupper($sql),0,6) == 'SELECT')     {   -    while($row = mysqli_fetch_assoc($result))   	{         $rows[] = $row;   	}     }     else   (    $rows = mysqli_affected_rows($conn);     mysqli_close($conn);     return $rows;       }       function read_csv($file)   {     $tfn_data = array();   /  if (($handle = fopen($file, "r")) !== FALSE)      {       $row = 0;       $lead_data = array();   <    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)        {         if($row == 0)         {           $fields = $data;         }   
      else         {           $lead = $data;   (        foreach($fields as $field=>$val)   	        {   4          $tfn_data[$lead[0]][$val] = $data[$field];   	        }         }         $row++;       }       fclose($handle);       return $tfn_data;     }     else     {   .    mylog('Cannot read file [' . $file . ']');       return FALSE;     }   }       function mylog($msg)   {      global $debug,$LOGFILE,$app;       if($debug)       {   "        $fp = fopen($LOGFILE,"a");   ;        fputs($fp,date("M:j:Y-H:i:s")."[$app] ".$str."\n");           fclose($fp);       }   }           ?>5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             Wdhd     �                h$replication_parameters = array("host"=>"bop6atmbil01","user"=>"bruce_dba","password"=>"mysql_smokes!");5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             Wdhe     �         �       �         �    5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      $config_file = ''5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      $ini = parse_ini_file()5�_�                       	    ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      
$new_ini[]5�_�                       
    ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      $new_ini['']5�_�      	                     ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      $new_ini['host'] = $ini[]5�_�      
           	          ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      $new_ini['host'] = $ini['']5�_�   	              
      	    ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      
$new_ini[]5�_�   
                    
    ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      $new_ini['']5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      $new_ini['user'] = $ini[]5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      $new_ini['user'] = $ini['']5�_�                       	    ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      
$new_ini[]5�_�                       
    ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      $new_ini['']5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Wdhf     �         �      $new_ini['password'] = $ini[]5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Wdhf    �         �      $new_ini['password'] = $ini['']5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             Wdhs     �         �       �         �    5�_�                       &    ����                                                                                                                                                                                                                                                                                                                                                             Wdhs     �         �      '$replication_params = get_replication()5�_�                    i        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   h   j   �       �   h   j   �    5�_�                    i       ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   h   k   �      function get_replication()5�_�                    j       ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   i   l   �      {}5�_�                    k   
    ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   j   n   �        $sql = ""}5�_�                    m   1    ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      4                        WHERE connection_type = ''"}5�_�                    m   R    ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   o   �      U                        WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = ''"}5�_�                    n       ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   m   p   �        return run_query()}5�_�                    l        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   k   m   �      1              FROM atm_globals.atmosphere_schemas5�_�                    l        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   k   m   �      0             FROM atm_globals.atmosphere_schemas5�_�                    l        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   k   m   �      /            FROM atm_globals.atmosphere_schemas5�_�                    l        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   k   m   �      .           FROM atm_globals.atmosphere_schemas5�_�                     m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      V                        WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�      !               m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      U                       WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�       "           !   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      T                      WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   !   #           "   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      S                     WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   "   $           #   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      R                    WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   #   %           $   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      Q                   WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   $   &           %   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      P                  WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   %   '           &   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      O                 WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   &   (           '   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      N                WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   '   )           (   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      M               WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   (   *           )   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      L              WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   )   +           *   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      K             WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   *   ,           +   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�     �   l   n   �      J            WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   +   -           ,   m        ����                                                                                                                                                                                                                                                                                                                                                             Wdh�    �   l   n   �      I           WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";5�_�   ,   .           -   2        ����                                                                                                                                                                                                                                                                                                                                                             Wt�     �               �   <?php   /require_once dirname(__FILE__) . '/GetOpt.php';   $debug = true;   '$LOGFILE = "/var/log/process_tfns.log";   $app = "process_tfns";       $options = _getopt(NULL, array(             'file_to_process::',             'client_id::',           ));   |if(!isset($options['file_to_process']) || substr($options['file_to_process'],-3) != 'csv' || !isset($options['client_id']) )   ,	die("Invalid file to process provided \n");       -$config_file = '/etc/atmosphere/general.ini';   $$ini = parse_ini_file($config_file);    $new_ini['host'] = $ini['host'];    $new_ini['user'] = $ini['user'];   $$new_ini['password'] = $ini['pass'];       0$replication_params = get_replication($new_ini);   /$file_to_process = $options['file_to_process'];   '$tfn_data = read_csv($file_to_process);   $tfns = array();   !foreach($tfn_data as $tfn=>$data)     $tfns[] = "'".$tfn."'";   #$client_id = $options['client_id'];       !$tfn_string = implode(",",$tfns);   "$sql = "SELECT did_id, did_pattern   +        FROM asterisk.vicidial_inbound_dids   G        WHERE did_pattern IN ($tfn_string) AND client_id = $client_id";       2$result = run_query($sql,$replication_parameters);   if($result == false)   #  die("Failed to get did info.\n");       foreach($result as $tfn_id)   {   B  $tfn_data[$tfn_id['did_pattern']]['did_id'] = $tfn_id['did_id'];   }       //var_dump($tfn_data);   $sql = "SELECT field_id,name   3        FROM asterisk.vicidial_inbound_custom_field   6        WHERE client_id = $client_id AND deleted = 0";   ;$metadata_fields = run_query($sql,$replication_parameters);   8//mylog("metadata fields:".print_r($metadata_fields,1));   $did_cf = array();   	$row = 0;   !foreach($tfn_data as $tfn=>$data)   {   .  foreach($metadata_fields as $metadata_field)     {   .    $did_cf[$row]['did_id'] = $data['did_id'];   <    $did_cf[$row]['field_id'] = $metadata_field['field_id'];   <    $did_cf[$row]['value'] = $data[$metadata_field['name']];       $row++;     }   }       c$sql_stmt = "REPLACE INTO asterisk.vicidial_inbound_dids_cf (`did_id`,`field_id`,`value`) VALUES ";   foreach($did_cf as $cf)   {   O  $sql_stmt .= "\n(".$cf['did_id'].",".$cf['field_id'].",'".$cf['value']."'),";   }       4$sql_stmt = substr($sql_stmt,0,strlen($sql_stmt)-1);       '$fp = fopen(date("YmdHis").".sql",'w');   fputs($fp,$sql_stmt);   fclose($fp);   echo "Done";       function run_query($sql,$ini)   {     global $LOGFILE, $app;   E  $conn = mysqli_connect($ini['host'],$ini['user'],$ini['password']);     if(!$conn)     {   G    mylog("Cannot connect to ".print_r($ini,1)." Reason:".mysql_error);       return false;     }     mylog("Running query $sql");   &  $result = mysqli_query($conn, $sql);     if($result === FALSE)     {   D    mylog("Query Error: ".mysqli_error($conn)." with Query : $sql");       mysqli_close($conn);       return false;     }     $rows = array();   .  if(substr(strtoupper($sql),0,6) == 'SELECT')     {   -    while($row = mysqli_fetch_assoc($result))   	{         $rows[] = $row;   	}     }     else   (    $rows = mysqli_affected_rows($conn);     mysqli_close($conn);     return $rows;       }   function get_replication($ini)   {   =  $sql = "SELECT hostname AS host, username AS user, password   -          FROM atm_globals.atmosphere_schemas   H          WHERE connection_type = 'PRIMARY_REPORTS' AND shard_id = '0'";     return run_query($sql,$ini);   }       function read_csv($file)   {     $tfn_data = array();   /  if (($handle = fopen($file, "r")) !== FALSE)      {       $row = 0;       $lead_data = array();   <    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)        {         if($row == 0)         {           $fields = $data;         }   
      else         {           $lead = $data;   (        foreach($fields as $field=>$val)   	        {   4          $tfn_data[$lead[0]][$val] = $data[$field];   	        }         }         $row++;       }       fclose($handle);       return $tfn_data;     }     else     {   .    mylog('Cannot read file [' . $file . ']');       return FALSE;     }   }       function mylog($msg)   {      global $debug,$LOGFILE,$app;       if($debug)       {   "        $fp = fopen($LOGFILE,"a");   ;        fputs($fp,date("M:j:Y-H:i:s")."[$app] ".$str."\n");           fclose($fp);       }   }           ?>5�_�   -   /           .           ����                                                                                                                                                                                                                                                                                                                                                             Wt�     �         �       �         �    5�_�   .   0           /          ����                                                                                                                                                                                                                                                                                                                                                             Wt�     �         �       *5�_�   /   1           0      L    ����                                                                                                                                                                                                                                                                                                                                                             Wt�     �         �      m *@purpose: Script to process tfn metadata from a csv file and load in into asterisk.vicidial_inbound_dids_cf5�_�   0   2           1          ����                                                                                                                                                                                                                                                                                                                                                             Wt�     �         �      -            asterisk.vicidial_inbound_dids_cf5�_�   1   3           2          ����                                                                                                                                                                                                                                                                                                                                                             Wt�    �         �      . *           asterisk.vicidial_inbound_dids_cf5�_�   2   4           3      -    ����                                                                                                                                                                                                                                                                                                                                                             Wt	     �         �      - *          asterisk.vicidial_inbound_dids_cf5�_�   3   5           4      G    ����                                                                                                                                                                                                                                                                                                                                                             Wt	     �         �      X *          asterisk.vicidial_inbound_dids_cf. You have to provide the --file_to_process5�_�   4   6           5          ����                                                                                                                                                                                                                                                                                                                                                             Wt	     �         �       *          --file_to_process5�_�   5   7           6          ����                                                                                                                                                                                                                                                                                                                                                             Wt	     �         �       *          --file_to_process5�_�   6   8           7      F    ����                                                                                                                                                                                                                                                                                                                                                             Wt	:     �         �      G *          asterisk.vicidial_inbound_dids_cf. You have to provide the 5�_�   7   9           8      K    ����                                                                                                                                                                                                                                                                                                                                                             Wt	>    �         �      L *@purpose: Script to process tfn metadata from a csv file and load in into 5�_�   8               9          ����                                                                                                                                                                                                                                                                                                                                                             Wt	C    �                 *5��