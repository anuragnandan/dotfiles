Vim�UnDo� B��ݔ�����*�#
����o�ɒ���    e                                   W!\�    _�                             ����                                                                                                                                                                                                                                                                                                                                                             W!\�     �               �   <?php   /*   @author: Anurag Raperthy   M@Description: Hipcaht bot for clearing stuck calls for Atmosphere - Advantone   */   "$LOGFILE = "/var/log/hipchat.log";   $debug = true;   $app = 'hipchat';   8$message = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);    #$room = $message->item->room->name;   if($room == 'test')   4	$auth = 'xW9BsUhEK1iafUy6BtIwAnUilhqFvJCA2qVbq31G';   elseif($room == 'Operations')   4	$auth = 'eMDrCtwsU6d1APQwE4A6bvEssHJAwh8s0Yb4wEuU';   else   {   E	sendResponse("Unkown room","$auth","notification","$room_id","red");   	exit();   }       5//mylog($GLOBALS['message']->item->message->message);   3$actual_message = $message->item->message->message;   $$room_id = $message->item->room->id;   '	$uids = explode(" ", $actual_message);   //mylog(print_r($uids,1));   &if(!isset($uids[1]) || $uids[1] == '')   {   �	sendResponse("Empty call IDs given. Right way to use: /clrstuckcall <19 digit UID>. Eg: /clrstuckcall 1234567891234567890 ..You can seperate multiple call UIDs with a space or a (,).","$auth","notification","$room_id","red");   	die();   }   $final_uids = array();   )if(strpos($actual_message,",") === FALSE)   {   &	for($i = 1; $i < count($uids) ; $i++)   	{   -		//mylog(count($uids)." ".$i." ".$uids[$i]);   		$final_uids[] = $uids[$i];   	}   }   else   {   &	$final_uids = explode(",", $uids[1]);   }        //mylog(print_r($final_uids,1));   *for($i = 0; $i < count($final_uids); $i++)   {   ,	//if(strpos($final_uids[$i],"'") === FALSE)   F		$final_uids[$i] = "'".mysql_real_escape_string($final_uids[$i])."'";   }       ($uids_string = implode(',',$final_uids);    //mylog(print_r($final_uids,1));   M$conn = mysql_connect('flr5prosql01','bruce_dba','mysql_smokes!','asterisk');   
if(!$conn)   	mylog('Cant connect');   $sql = "DELETE    $		FROM asterisk.vicidial_auto_calls    ]		WHERE uniqueid IN ($uids_string) AND last_update_time < DATE_SUB(NOW(), INTERVAL 3 MINUTE)    				AND status = 'XFER'   '                AND stage LIKE 'LIVE-%'   &                AND call_type = 'IN'";   "$result = mysql_query($sql,$conn);   if(!$result)   {   u	sendResponse("Couldnt clear stuck call(s) form vicidial_auto_calls table.","$auth","notification","$room_id","red");   7	mylog("Query Error: $sql with error: ".mysql_error());   	die();   }   else   {   	mylog($sql);   t	$sql = "UPDATE asterisk.vicidial_vacd_callflow SET active = 'N' WHERE uniqueid IN ($uids_string) AND active = 'Y'";   $	$result = mysql_query($sql, $conn);   	if(!$result)   	{   w		sendResponse("Couldnt deactivate call(s) from vicidial_vacd_callflow table","$auth","notification","$room_id","red");   8		mylog("Query Error: $sql with error: ".mysql_error());   		die();   	}   O	sendResponse("Call(s) Cleared :) ","$auth","notification","$room_id","green");   }        function mylog($str){   $        global $debug,$LOGFILE,$app;           if($debug){   &            $fp = fopen($LOGFILE,"a");   R            fputs($fp,date("M:j:Y-H:i:s")."-PID=".getmypid()."[$app] ".$str."\n");               fclose($fp);   	        }       }       =function sendResponse($message, $token, $type, $room, $color)   {   �	$cmd = 'curl --insecure -d \'{"color":"'.$color.'","message":"'.$message.'","notify":false,"message_format":"text"}\' -H \'Content-Type: application/json\' https://advantone.hipchat.com/v2/room/'.$room.'/notification?auth_token='.$token;   	$ret = system($cmd,$return);   	mylog($cmd);   	mylog(print_r($return,1));       }   ?function callHipChatAPI($message, $token, $type, $room, $color)   {   '        if ($type == "notification" ) {   $                $post_data = array (   *                        "color" => $color,   3                        "message_format" => "text",   						"notify" => "false",   -                        "message" => $message                   );   	        }           else {   $                $post_data = array (   *                        "color" => $color,   3                        "message_format" => "text",   						"notify" => "false",   -                        "message" => $message                   );   	        }       @        $url="https://api.hipchat.com/v2/room/".$room."/".$type;               $curl = curl_init();       /        curl_setopt($curl, CURLOPT_POST, true);   .        curl_setopt($curl, CURLOPT_URL, $url);   2    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   /        curl_setopt($curl, CURLOPT_VERBOSE, 1);               //Key to be successful   .        curl_setopt($curl, CURLOPT_HTTPHEADER,   Y                array("Content-type: application/json","Authorization: Bearer ".$token));       H        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));       *        $curl_response = curl_exec($curl);               return $curl_response;   }           ?>5�_�                     e        ����                                                                                                                                                                                                                                                                                                                            �           e           v        W!\�    �   d           )   ?function callHipChatAPI($message, $token, $type, $room, $color)   {   '        if ($type == "notification" ) {   $                $post_data = array (   *                        "color" => $color,   3                        "message_format" => "text",   						"notify" => "false",   -                        "message" => $message                   );   	        }           else {   $                $post_data = array (   *                        "color" => $color,   3                        "message_format" => "text",   						"notify" => "false",   -                        "message" => $message                   );   	        }       @        $url="https://api.hipchat.com/v2/room/".$room."/".$type;               $curl = curl_init();       /        curl_setopt($curl, CURLOPT_POST, true);   .        curl_setopt($curl, CURLOPT_URL, $url);   2    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   /        curl_setopt($curl, CURLOPT_VERBOSE, 1);               //Key to be successful   .        curl_setopt($curl, CURLOPT_HTTPHEADER,   Y                array("Content-type: application/json","Authorization: Bearer ".$token));       H        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));       *        $curl_response = curl_exec($curl);               return $curl_response;   }           ?>5��