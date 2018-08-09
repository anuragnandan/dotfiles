Vim�UnDo� �E~x!*+QN��Ł��9U���A��Ò�@��  �   F            $timestamp = new DateTime('now', new DateTimeZone('UTC'));   �                          Vܶ    _�                     �       ����                                                                                                                                                                                                                                                                                                                                                             V�D     �              �   <?php       *require_once('AAGRouterServiceFault.php');   #require_once('../SoapService.php');   -require_once('../../../../prosodie_def.php');   %require_once('../../Datasource.php');       Rdefine('AAG_SERVICE_URL', 'https://aagreverse.secure.force.com/appapi/Advantone');       /**    *   C * @desc Service to modify agent skill rankings and global ranking.   $ * @author THarper (Copied Cgoust's)    *    **/   *class AAGRouterService extends SoapService   {       /**        *   {     * @desc The log file for this web service. This is picked up by syslog-ng on every IVR and aggregated to bop6promon01.        *        **/   U    protected $logFile = '/var/log/atmosphere_soap_webservices-AAGRouterService.log';           /**        *         * @param SoapServer $server        * @param mysqli     $conn        *        **/   4    public function AAGRouterService($server, $conn)       {   X        $this->writeLog('Starting new SoapServer process, service [' . __CLASS__ . ']');       7        // Was there an error creating the soap server?           if($server === NULL)   	        {   ;            $this->writeLog('Soap server creation error.');   ]            AAGRouterServiceFault::sendFault(AAGRouterServiceFault::SoapServerCreationError);               return;   	        }       2        // Do we have a valid database connection?           if($conn === NULL)   	        {   K            $this->writeLog('A valid database connection was not passed.');   S            AAGRouterServiceFault::sendFault(AAGRouterServiceFault::DatabaseError);               return;   	        }       *        // Grab the HTTP auth credentials:   .        $username = $_SERVER['PHP_AUTH_USER'];   ,        $password = $_SERVER['PHP_AUTH_PW'];               // Check 'em:   8        //if(!$this->authenticate($username, $password))           //{   N        //    $this->writeLog('Cannot authenticate user [' . $username . ']');   ]        //    AAGRouterServiceFault::sendFault(AAGRouterServiceFault::AuthentificationError);           //    return;           //}       "        $this->server   = $server;            $this->conn     = $conn;       }       '    public function routeCall($request)       {   5        $this->app = __CLASS__ . '::' . __FUNCTION__;       1        $this->writeLog(print_r($request, TRUE));               try   	        {   O            // ----------------------------------------------------------------               // Authenticate   O            // ----------------------------------------------------------------       )            // Do we have a security key?   O            if(!isset($request->security_key) || empty($request->security_key))               {   e                throw new Exception('No security key given.', AAGRouterServiceFault::BadSecurityKey);                   return FALSE;               }       0            // Does it actually map to a client?   @            $account = $this->getClient($request->security_key);               if(!$account)               {   �                throw new Exception('Security key [' . $request->security_key . '] does not map to a valid account.', AAGRouterServiceFault::InvalidAccountInformation);                   return FALSE;               }   O            // ----------------------------------------------------------------               // Lookup Unique ID   O            // ----------------------------------------------------------------       0            // We need to find the ANI and DNIS:   �            $sql = "SELECT ANI,DNIS,client_id FROM asterisk.generic_ivr_callflow WHERE uniqueid = '{$request->unique_id}' LIMIT 1";   ,            $result = $this->runQuery($sql);       �            if($result->num_rows < 1) throw new Exception('ERROR: Cannot find uniqueid [' . $request->unique_id . '] in generic_ivr_callflow!');   .            $result = $result->fetch_object();                   if($result->client_id != $account->clientnum) throw new Exception('ERROR: Client ID from generic_ivr_callflow does not match security key! ' . $account->clientnum . ' vs ' . $result->client_id, AAGRouterServiceFault::InvalidAccountInformation);       O            // ----------------------------------------------------------------   )            // Format lead search request   O            // ----------------------------------------------------------------       F            $timestamp = new DateTime('now', new DateTimeZone('UTC'));   "            $dnis = $result->DNIS;                    $ani = $result->ANI;   8            $dataset = $request->lead_source_dataset_id;   8            $tfn_dataset = $request->tfn_map_dataset_id;       =            $record_expiration = new DateTime('3 hours ago');   A            $record_expiration = $record_expiration->format('U');               // POST fields:               $fields = array(   =                'user'      => $request->salesforce_username,   A                'timestamp' => $timestamp->format('Y-m-d H:i:s'),   O                'key'       => md5($timestamp->format('Y-m-d H:i:s') . 'adva'),   $                'ani'       => $ani,   #                'tfn'      => $dnis               );       O            // ----------------------------------------------------------------               // Parse response   O            // ----------------------------------------------------------------                   // Run request:   A            $response = $this->webPost(AAG_SERVICE_URL, $fields);       4            // Parse response into SimpleXML object:   8            $response = new SimpleXmlElement($response);   c            $response->transfer_number = preg_replace('/[^0-9]/i', '', $response->transfer_number);                var_dump($response);   f            $this->writeLog('----------------------------------------------------------------------');       =            // First, do we even have a lead for this number?   }            if(!isset($response->transfer_number) || (isset($response->transfer_number) && $response->transfer_number == ''))               {   7                // If not, return a strategy ID of AAG:   V                $this->writeLog('No lead found. Routing to Lead Insertion Strategy.');   j                $this->writeLog('----------------------------------------------------------------------');   W                if(isset($response->lead_source_id) && $response->lead_source_id != '')                   {   =                    $lead_source = $response->lead_source_id;   -                    // Clear out old records:   j                    $sql = "DELETE FROM dataset.dataset_$dataset WHERE entry_time < {$record_expiration}";   *                    $this->runQuery($sql);       �                    $sql = "INSERT IGNORE INTO dataset.dataset_$dataset (lead_source, dnis, entry_time) VALUES ('$lead_source','$dnis',UNIX_TIMESTAMP())";   *                    $this->runQuery($sql);       O                    $this->writeLog('Lead_source: '.$response->lead_source_id);   G                    //$this->doDataSet($lead_source_id,$dnis,$dataset);   p                    return array('transfer_number' => 'New Lead','lead_source_id' => $response->lead_source_id);                   }                   else                   {   b                    throw new Exception("ERROR: No lead source id sent.[".print_r($response)."]");                   }               }       )            // How many leads do we have?   v            $this->writeLog('Lead_source: '.$response->lead_source_id." Transfer Number:".$response->transfer_number);   5            $lead_source = $response->lead_source_id;   &             // Clear out old records:   b            $sql = "DELETE FROM dataset.dataset_$dataset WHERE entry_time < {$record_expiration}";   "            $this->runQuery($sql);       �            $sql = "INSERT IGNORE INTO dataset.dataset_$dataset (lead_source, dnis, entry_time) VALUES ('$lead_source','$dnis',UNIX_TIMESTAMP())";   "            $this->runQuery($sql);       x            return array('transfer_number' => $response->transfer_number,'lead_source_id' => $response->lead_source_id);   	        }           catch (Exception $e)   	        {   D        	  $this->writeLog('Caught exception: ' . $e->getMessage());   I            return new SoapFault('Server', 'Error: ' . $e->getMessage());   	        }       }       (    public function insertLead($request)       {   5        $this->app = __CLASS__ . '::' . __FUNCTION__;               try   	        {   O            // ----------------------------------------------------------------               // Authenticate   O            // ----------------------------------------------------------------       )            // Do we have a security key?   O            if(!isset($request->security_key) || empty($request->security_key))               {   e                throw new Exception('No security key given.', AAGRouterServiceFault::BadSecurityKey);                   return FALSE;               }       0            // Does it actually map to a client?   @            $account = $this->getClient($request->security_key);               if(!$account)               {   �                throw new Exception('Security key [' . $request->security_key . '] does not map to a valid account.', AAGRouterServiceFault::InvalidAccountInformation);                   return FALSE;               }       O            // ----------------------------------------------------------------               // Lookup Unique ID   O            // ----------------------------------------------------------------       0            // We need to find the ANI and DNIS:   �            $sql = "SELECT ANI,DNIS,utctime,client_id,short_id FROM asterisk.vicidial_cloud_callflow WHERE uniqueid = '{$request->unique_id}' LIMIT 1";   ,            $result = $this->runQuery($sql);       �            if($result->num_rows < 1) throw new Exception('ERROR: Cannot find uniqueid [' . $request->unique_id . '] in cloud callflow!');   .            $result = $result->fetch_object();       �            if($result->client_id != $account->clientnum) throw new Exception('ERROR: Client ID from cloud callflow does not match security key! ' . $account->clientnum . ' vs ' . $result->client_id, AAGRouterServiceFault::InvalidAccountInformation);   "            $dnis = $result->DNIS;                $ani = $result->ANI;   O            // ----------------------------------------------------------------   !            // Lookup lead source   O            // ----------------------------------------------------------------                     8            if(!isset($request->lead_source_dataset_id))               {   ^                throw new Exception("ERROR: not all parameters set. ".print_r($request,true));               }               $lead_source = "";   8            $dataset = $request->lead_source_dataset_id;       O            // ----------------------------------------------------------------                // Cache lead source   O            // ----------------------------------------------------------------       =            $record_expiration = new DateTime('3 hours ago');   A            $record_expiration = $record_expiration->format('U');       I            $sql = "SELECT d.lead_source FROM dataset.dataset_$dataset d    -                    WHERE d.dnis = '$dnis' ";   8            $lead_source_result = $this->runQuery($sql);   "            $this->writeLog($sql);   1            if($lead_source_result->num_rows > 0)               {   ;                $row = $lead_source_result->fetch_object();   1                $lead_source = $row->lead_source;               }               else               {                 $fields = array(   ?                  'user'      => $request->salesforce_username,   C                  'timestamp' => $timestamp->format('Y-m-d H:i:s'),   Q                  'key'       => md5($timestamp->format('Y-m-d H:i:s') . 'adva'),   &                  'ani'       => $ani,   %                  'tfn'      => $dnis                 );       Q              // ----------------------------------------------------------------                 // Parse response   Q              // ----------------------------------------------------------------                     // Run request:   C              $response = $this->webPost(AAG_SERVICE_URL, $fields);       6              // Parse response into SimpleXML object:   :              $response = new SimpleXmlElement($response);   e              $response->transfer_number = preg_replace('/[^0-9]/i', '', $response->transfer_number);   "              var_dump($response);   h              $this->writeLog('----------------------------------------------------------------------');       ?              // First, do we even have a lead for this number?   U              if(isset($response->lead_source_id) && $response->lead_source_id != '')                 {   ;                  $lead_source = $response->lead_source_id;   +                  // Clear out old records:   h                  $sql = "DELETE FROM dataset.dataset_$dataset WHERE entry_time < {$record_expiration}";   (                  $this->runQuery($sql);       �                  $sql = "INSERT IGNORE INTO dataset.dataset_$dataset (lead_source, dnis, entry_time) VALUES ('$lead_source','$dnis',UNIX_TIMESTAMP())";   (                  $this->runQuery($sql);       M                  $this->writeLog('Lead_source: '.$response->lead_source_id);                 }                 else                 {   `                  throw new Exception("ERROR: No lead source id sent.[".print_r($response)."]");                 }                       }   [                        // ----------------------------------------------------------------   )            // Format lead insert request   O            // ----------------------------------------------------------------   "            if($lead_source != '')               {   U                $timestamp = new DateTime($result->utctime, new DateTimeZone('UTC'));                       // POST fields:                    $fields = array(   A                    'OwnerId'              => $request->owner_id,   <                    'rm__Lead_Source__c'    => $lead_source,   5                    'rm__Status__c'         => 'New',   A                    'Advantone_ID__c'       => $result->short_id,   Q                    'Adv_Call_Date_Time__c' => $timestamp->format('Y-m-d H:i:s'),   <                    'ANI__c'                => $result->ANI,                   );                       // Run request:   �                $response = $this->webPost("https://aagreverse.secure.force.com/appapi/AppApi2?r_objecttype=rm__RM_Lead__c&r_orgid=00D80000000LNmd", $fields);       8                // Parse response into SimpleXML object:   <                $response = new SimpleXmlElement($response);       j                $this->writeLog('----------------------------------------------------------------------');   $                var_dump($response);   j                $this->writeLog('----------------------------------------------------------------------');               }               else               {   l                throw new Exception("ERROR: No Lead source id found for uniqueid: ".$request->unique_id);                  }   >            return array("transfer_number"=>$transfer_number);   	        }           catch (Exception $e)   	        {   E            $this->writeLog('Caught exception: ' . $e->getMessage());   I            return new SoapFault('Server', 'Error: ' . $e->getMessage());   	        }       }       1    protected function webPost($url, $parameters)       {   #        // Instantiate curl object:           $ch = curl_init($url);               // set cURL options:           $options = array(   0            CURLOPT_FRESH_CONNECT       => TRUE,   0            CURLOPT_POST                => TRUE,   0            CURLOPT_RETURNTRANSFER      => TRUE,   0            CURLOPT_SSL_VERIFYPEER      => TRUE,   -            CURLOPT_CONNECTTIMEOUT      => 3,   -            CURLOPT_DNS_CACHE_TIMEOUT   => 3,   7            CURLOPT_POSTFIELDS          => $parameters,   
        );   )        curl_setopt_array($ch, $options);               // Run:   #        $response = curl_exec($ch);   +        $response_info = curl_getinfo($ch);       1        $this->writeLog(print_r($options, TRUE));   #        $this->writeLog($response);   7        $this->writeLog(print_r($response_info, TRUE));       0        if($response_info['http_code'] !== 200)    	        {   W            $this->writeLog('cURL error #' . curl_errno($ch) . ': ' . curl_error($ch));   K            throw new Exception('ERROR: non-200 response back from cURL!');   	        }               curl_close($ch);               return $response;       }           /**   �     * @desc This is a wrapper function over $this->conn->_execute() that does proper error checking and throws exceptions properly.        */   +    protected function runQuery($statement)       {   !        // Execute the statement:   =        $statementResult = $this->conn->_execute($statement);       %        // Did we receive any errors?   #        if($this->conn->errno != 0)   	        {   �            throw new Exception('SQL error on statement [' . $statement . '], message [' . $this->conn->err . ']', AAGRouterServiceFault::DatabaseError);               return FALSE;   	        }                return $statementResult;       }   }       function init()   {   2    $pos = strpos(dirname(__FILE__), "/services");       if ($pos === false)   %        $iniPath = dirname(__FILE__);       else   6        $iniPath = substr(dirname(__FILE__), 0, $pos);   9    $iniPath = str_replace(AMFPHP, "WG5/ini/", $iniPath);   %    $conn = new Datasource($iniPath);       return($conn);   }       O// ----------------------------------------------------------------------------   // Main Execution   O// ----------------------------------------------------------------------------       try   {   /    ini_set('soap.wsdl_cache_enabled',  FALSE);   /    ini_set('soap.wsdl_cache_ttl',      FALSE);       !    // Determine the WSDL to use:   "    $machineName = php_uname('n');   7    $machineName = ucfirst(substr($machineName, 2, 1));           switch($machineName)       {           case 'R':   C        case 'D': $wsdlFileName = 'AAGRouterService-D.wsdl'; break;   C        case 'S': $wsdlFileName = 'AAGRouterService-S.wsdl'; break;   C        case 'P': $wsdlFileName = 'AAGRouterService.wsdl';   break;   H        default: throw new Exception('Cannot get machine type!'); break;       }           // Instantiate SoapServer:   6    $soapServer = new SoapServer($wsdlFileName, array(   #        'encoding'      => 'UTF-8',   ,        'cache_wsdl'    => WSDL_CACHE_MEMORY       ));           $conn = init();   #    $conn->_connect(ATMOSPHERE_DB);       B    $soapServer->setClass('AAGRouterService', $soapServer, $conn);       $soapServer->handle();   }   catch (Exception $e)   {   1    echo 'Caught exception: ' . $e->getMessage();   }5�_�                    �       ����                                                                                                                                                                                                                                                                                                                                                             Vܲ     �   �   �  �    5�_�                    �        ����                                                                                                                                                                                                                                                                                                                                                             Vܳ     �   �     �    �   �   �  �    5�_�                     �       ����                                                                                                                                                                                                                                                                                                                                                             Vܵ    �   �     �      F            $timestamp = new DateTime('now', new DateTimeZone('UTC'));5��