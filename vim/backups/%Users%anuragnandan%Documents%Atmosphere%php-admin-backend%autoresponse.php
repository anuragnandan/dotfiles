Vim�UnDo� ��m�����Ho$�9��jb���C�u�6�                                     Y%�^    _�                             ����                                                                                                                                                                                                                                                                                                                                                             Y%�[     �                 <?php       #    require_once("Datasource.php");   %    require_once("result_class.php");   +    require_once("autoresponse_class.php");   #    require_once("Atmosphere.php");       %class Autoresponse extends Atmosphere   {               /*   *    * Get shortcodes and keywords listing.       */       function get($valueObject)       {   7        $this->returnObject = new Result_Array_class();       3        if (!$this->_shard_connect($valueObject) ||   <            !$this->_check_param($valueObject, 'clientnum'))   -            return $this->returnObject;                      .        if (isset($valueObject['sort_field']))   K            $sort_field = $this->conn->_string($valueObject['sort_field']);           else   )            $sort_field = "shortcode_id";       N        $sql = "SELECT a.shortcode_id, a.shortcode,b.keyword, b.options_id, ";           $sql .= "b.created";   .        $sql .= " FROM sms_email_shortcode a";   _        $sql .= " INNER JOIN sms_email_shortcode_options b on a.shortcode_id = b.shortcode_id";   �        $sql .= " WHERE (a.deleted <> 'Y' or a.deleted is null) and (b.deleted <> 'Y' or b.deleted is null) and b.options_id like '";   L        $sql .= $this->conn->_string($valueObject['clientnum'])."%'";          (        $sql .= " ORDER BY $sort_field";   .        $result = $this->conn->_execute($sql);   '        if ($this->conn->errno != 0) {    6            $this->returnObject->status = 0;             ?            $this->returnObject->error_text = $this->conn->err;   '            return $this->returnObject;   	        }   @        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))   0            $this->returnObject->items[] = $row;                  /        $this->returnObject->status = 1;          +        $this->conn->_free_result($result);   /        return $this->returnObject;                   }              /*        * Get details keyword.        */   %    function get_detail($valueObject)       {   6       $this->returnObject = new Result_Array_class();       3        if (!$this->_shard_connect($valueObject) ||   =            !$this->_check_param($valueObject, 'options_id'))   '            return $this->returnObject;   A        if (sscanf($valueObject['options_id'], "%d", $id) == 0) {   ,            $this->returnObject->status = 0;   =            $this->returnObject->error_text = 'error format';   '            return $this->returnObject;   	        }              �       $sql = "SELECT se.shortcode,so.active, so.keyword,so.options_id, so.description, so.keyword_lastChanged, so.text_id FROM sms_email_shortcode se, sms_email_shortcode_options so";   V        $sql .= " WHERE se.shortcode_id = so.shortcode_id AND so.options_id = ".$id  ;              .        $result = $this->conn->_execute($sql);   &        if ($this->conn->errno != 0) {   ,            $this->returnObject->status = 0;   D            $this->returnObject->error_text = $this->conn->err.$sql;   '            return $this->returnObject;   	        }   @        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))   0            $this->returnObject->items[] = $row;   (        $this->returnObject->status = 1;   +        $this->conn->_free_result($result);   $        return $this->returnObject;        }              /*        * Save the Key word        */   &    function saveKeyword($valueObject)       {   1	 $this->returnObject = new Result_Array_class();   (        $this->returnObject->status = 0;   		   ,	 if (!$this->_shard_connect($valueObject)){   '            return $this->returnObject;   	        }   =        if (!$this->_check_param($valueObject, 'clientnum')){   D            $this->returnObject->error_text = "clientnum not found";   '            return $this->returnObject;   	        }   A        if (!$this->_check_param($valueObject, 'keywordToSave')){   H            $this->returnObject->error_text = "keywordToSave not found";   '            return $this->returnObject;   	        }   >        if (!$this->_check_param($valueObject, 'options_id')){   E            $this->returnObject->error_text = "options_id not found";   '            return $this->returnObject;   	        }   >        if (!isset($valueObject['keywordToSave']['keyword'])){   B            $this->returnObject->error_text = "keyword not found";   '            return $this->returnObject;           }                                    )        $ID = $valueObject['options_id'];              0        $params = $valueObject['keywordToSave'];   <        $keyword = $valueObject['keywordToSave']['keyword'];   @		$shortcode_id = $valueObject['keywordToSave']['shortcode_id'];               $update = 0;           if($ID != ""){               $update = 1;   	        }                  l        $sql = "Select * from sms_email_shortcode_options where keyword = '".$this->conn->_escape($keyword).   �            "' and options_id like '".$valueObject['clientnum']."%' and deleted != 'Y' and shortcode_id = '".$this->conn->_escape($shortcode_id). "'";   .        $result = $this->conn->_execute($sql);   &        if ($this->conn->errno != 0) {   ?            $this->returnObject->error_text = $this->conn->err;   '            return $this->returnObject;   	        }                      $count =0;   A        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   *            if($row['options_id'] != $ID){                   $count++;               }   	        }   '        if(is_bool($result) === false){   (            mysqli_free_result($result);   	        }                      if($count > 0){   b            $this->returnObject->error_text = "$keyword and shortcode combination already exists";   '            return $this->returnObject;   	        }                                 //Update options table           if($update == 0){   ]        $sql = "Select CONCAT('".$valueObject['clientnum']."', if(max(options_id) is null,".    �            "'0000',if(Length(SUBSTR(max(options_id),4)+1) > 3, SUBSTR(max(options_id),4)+1,LPAD(SUBSTR(max(options_id),4)+1,4,0)))) as new_ID from sms_email_shortcode_options";   S            $sql = $sql." where options_id like '".$valueObject['clientnum']."%'";                   2            $result = $this->conn->_execute($sql);   *            if ($this->conn->errno != 0) {   C                $this->returnObject->error_text = $this->conn->err;   +                return $this->returnObject;               }                  A            if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   %                $ID = $row['new_ID'];               }else{   I                $this->returnObject->error_text = "Couldn't create List";   +                return $this->returnObject;               }                  +            if(is_bool($result) === false){   ,                mysqli_free_result($result);               }                  '            $FieldNames = "options_id";               $FieldValues = $ID;       '            $FieldNames .= ", created";   @            $FieldValues .= ", now()";    //date('Y-m-d h:i:s');       		    :            foreach($params as $param_name=>$param_value){   9                $FieldNames =  "$FieldNames,$param_name";   W                $FieldValues = "$FieldValues,'".$this->conn->_escape($param_value)."'";               }                  `            $sql = "insert into sms_email_shortcode_options($FieldNames) Values ($FieldValues)";       2            $result = $this->conn->_execute($sql);   *            if ($this->conn->errno != 0) {   C                $this->returnObject->error_text = $this->conn->err;   +                return $this->returnObject;               }   +            if(is_bool($result) === false){   ,                mysqli_free_result($result);               }   \            $ret = $this->conn->_writeAdminLog("KEYWORD_SAVED","KEYWORD","CREATE",$sql,$ID);               if($ret != 1){   7                $this->returnObject->error_text = $ret;   +                return $this->returnObject;               }           }else{   =            $sql = "update sms_email_shortcode_options set ";                              $fieldSQL = "";                  :            foreach($params as $param_name=>$param_value){   $                if($fieldSQL != ""){   .                    $fieldSQL = $fieldSQL.",";                   }   `                $fieldSQL = $fieldSQL.$param_name." = '".$this->conn->_escape($param_value)."'";               }                  >            $sql = $sql.$fieldSQL." where options_id = '$ID'";                  2            $result = $this->conn->_execute($sql);   *            if ($this->conn->errno != 0) {   C                $this->returnObject->error_text = $this->conn->err;   +                return $this->returnObject;               }   +            if(is_bool($result) === false){   ,                mysqli_free_result($result);               }                              //Write Admin log   \            $ret = $this->conn->_writeAdminLog("KEYWORD_SAVED","KEYWORD","MODIFY",$sql,$ID);               if($ret != 1){   7                $this->returnObject->error_text = $ret;   +                return $this->returnObject;               }   	        }       2        $this->returnObject->items[0] = "";          /        $this->returnObject->items['ID'] = $ID;   (        $this->returnObject->status = 1;   #        return $this->returnObject;       }                 /*        * delete the Keyword        */   (    function deleteKeyword($valueObject)       {   7        $this->returnObject = new Result_Array_class();   "	 $this->returnObject->status = 0;       3        if (!$this->_shard_connect($valueObject) ||   =            !$this->_check_param($valueObject, 'options_id'))   '            return $this->returnObject;                              4        $ID = $valueObject['options_id'];                             _        $sql = "update sms_email_shortcode_options set deleted='Y' where options_id = '$ID'" ;                     .        $result = $this->conn->_execute($sql);   &        if ($this->conn->errno != 0) {   ?            $this->returnObject->error_text = $this->conn->err;   '            return $this->returnObject;   	        }              '        if(is_bool($result) === false){   (            mysqli_free_result($result);   	        }   "	 $this->returnObject->status = 1;   #        return $this->returnObject;   	    }       }       ?>5�_�                     G        ����                                                                                                                                                                                                                                                                                                                                                             Y%�]    �   F   H          D            $this->returnObject->error_text = $this->conn->err.$sql;5��