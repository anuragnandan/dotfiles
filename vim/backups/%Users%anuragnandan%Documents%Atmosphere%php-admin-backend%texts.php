Vim�UnDo� 4�������h���n���uF�2�������W                                     Y%�h    _�                             ����                                                                                                                                                                                                                                                                                                                                                             Y%�e     �                 <?php       #    require_once("Datasource.php");   %    require_once("result_class.php");   $    require_once("texts_class.php");   #    require_once("Atmosphere.php");       class Texts extends Atmosphere   {               /*       * Get texts listing.       */       function get($valueObject)       {   7        $this->returnObject = new Result_Array_class();       3        if (!$this->_shard_connect($valueObject) ||   <            !$this->_check_param($valueObject, 'clientnum'))   -            return $this->returnObject;                      .        if (isset($valueObject['sort_field']))   K            $sort_field = $this->conn->_string($valueObject['sort_field']);           else   $            $sort_field = "text_id";       D        $sql = "SELECT text_id, text_name, active, campaign_type, ";   ,        $sql .= "created, text_changedate ";   0        $sql .= "FROM sms_email_campaign_text ";   P        $sql .= " WHERE (deleted <> 'Y' or deleted is null) and text_id like '";   L        $sql .= $this->conn->_string($valueObject['clientnum'])."%'";          (        $sql .= " ORDER BY $sort_field";   .        $result = $this->conn->_execute($sql);   '        if ($this->conn->errno != 0) {    6            $this->returnObject->status = 0;             ?            $this->returnObject->error_text = $this->conn->err;   '            return $this->returnObject;   	        }   @        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))   0            $this->returnObject->items[] = $row;                  /        $this->returnObject->status = 1;          +        $this->conn->_free_result($result);   /        return $this->returnObject;                   }               /*       * Get ActiveTexts.       */   )    function getActiveTexts($valueObject)       {   7        $this->returnObject = new Result_Array_class();       3        if (!$this->_shard_connect($valueObject) ||   <            !$this->_check_param($valueObject, 'clientnum'))   -            return $this->returnObject;                      .        if (isset($valueObject['sort_field']))   K            $sort_field = $this->conn->_string($valueObject['sort_field']);           else   $            $sort_field = "text_id";       ?        $sql = "SELECT * FROM sms_email_campaign_text";           P        $sql .= " WHERE (deleted <> 'Y' or deleted is null) and text_id like '";   L        $sql .= $this->conn->_string($valueObject['clientnum'])."%'";          9        $sql .= " AND active = 'Y' ORDER BY $sort_field";   .        $result = $this->conn->_execute($sql);   '        if ($this->conn->errno != 0) {    6            $this->returnObject->status = 0;             ?            $this->returnObject->error_text = $this->conn->err;   '            return $this->returnObject;   	        }   @        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))   0            $this->returnObject->items[] = $row;                  /        $this->returnObject->status = 1;          +        $this->conn->_free_result($result);   /        return $this->returnObject;                   }                  /*        * Get details of text.        */   %    function get_detail($valueObject)       {   6       $this->returnObject = new Result_Array_class();       3        if (!$this->_shard_connect($valueObject) ||   :            !$this->_check_param($valueObject, 'text_id'))   '            return $this->returnObject;   >        if (sscanf($valueObject['text_id'], "%d", $id) == 0) {   ,            $this->returnObject->status = 0;   =            $this->returnObject->error_text = 'error format';   '            return $this->returnObject;   	        }              8        $sql .= "select * FROM sms_email_campaign_text";   (        $sql .= " WHERE text_id = ".$id;   .        $result = $this->conn->_execute($sql);   &        if ($this->conn->errno != 0) {   ,            $this->returnObject->status = 0;   D            $this->returnObject->error_text = $this->conn->err.$sql;   '            return $this->returnObject;   	        }   @        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))   0            $this->returnObject->items[] = $row;   (        $this->returnObject->status = 1;   +        $this->conn->_free_result($result);   $        return $this->returnObject;        }              /*        * Save the Texts        */   $    function saveTexts($valueObject)       {   7        $this->returnObject = new Result_Array_class();   (        $this->returnObject->status = 0;   2        if (!$this->_shard_connect($valueObject)){   '            return $this->returnObject;   	        }   =        if (!$this->_check_param($valueObject, 'clientnum')){   D            $this->returnObject->error_text = "clientnum not found";   '            return $this->returnObject;   	        }   >        if (!$this->_check_param($valueObject, 'textToSave')){   E            $this->returnObject->error_text = "textToSave not found";   '            return $this->returnObject;   	        }   ;        if (!$this->_check_param($valueObject, 'text_id')){   B            $this->returnObject->error_text = "text_id not found";   '            return $this->returnObject;   	        }   =        if (!isset($valueObject['textToSave']['text_name'])){   D            $this->returnObject->error_text = "text_name not found";   '            return $this->returnObject;           }                                    &        $ID = $valueObject['text_id'];              -        $params = $valueObject['textToSave'];   8        $name = $valueObject['textToSave']['text_name'];               $update = 0;           if($ID != ""){               $update = 1;   	        }                  g        $sql = "Select * from sms_email_campaign_text where text_name = '".$this->conn->_escape($name).   U            "' and text_id like '".$valueObject['clientnum']."%' and deleted != 'Y'";   .        $result = $this->conn->_execute($sql);   &        if ($this->conn->errno != 0) {   ?            $this->returnObject->error_text = $this->conn->err;   '            return $this->returnObject;   	        }                      $count =0;   A        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   '            if($row['text_id'] != $ID){                   $count++;               }   	        }   '        if(is_bool($result) === false){   (            mysqli_free_result($result);   	        }                      if($count > 0){   E            $this->returnObject->error_text = "$name already exists";   '            return $this->returnObject;   	        }                         %        //Update sms_email_text table           if($update == 0){   Z        $sql = "Select CONCAT('".$valueObject['clientnum']."', if(max(text_id) is null,".    �            "'0000',if(Length(SUBSTR(max(text_id),4)+1) > 3, SUBSTR(max(text_id),4)+1,LPAD(SUBSTR(max(text_id),4)+1,4,0)))) as new_ID from sms_email_campaign_text";   P            $sql = $sql." where text_id like '".$valueObject['clientnum']."%'";                   2            $result = $this->conn->_execute($sql);   *            if ($this->conn->errno != 0) {   C                $this->returnObject->error_text = $this->conn->err;   +                return $this->returnObject;               }                  A            if($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){   %                $ID = $row['new_ID'];               }else{   I                $this->returnObject->error_text = "Couldn't create List";   +                return $this->returnObject;               }                  +            if(is_bool($result) === false){   ,                mysqli_free_result($result);               }                  $            $FieldNames = "text_id";               $FieldValues = $ID;   :            foreach($params as $param_name=>$param_value){   9                $FieldNames =  "$FieldNames,$param_name";   W                $FieldValues = "$FieldValues,'".$this->conn->_escape($param_value)."'";               }                  \            $sql = "insert into sms_email_campaign_text($FieldNames) Values ($FieldValues)";   2            $result = $this->conn->_execute($sql);   *            if ($this->conn->errno != 0) {   C                $this->returnObject->error_text = $this->conn->err;   +                return $this->returnObject;               }   +            if(is_bool($result) === false){   ,                mysqli_free_result($result);               }   V            $ret = $this->conn->_writeAdminLog("TEXT_SAVED","TEXT","CREATE",$sql,$ID);               if($ret != 1){   7                $this->returnObject->error_text = $ret;   +                return $this->returnObject;               }           }else{   9            $sql = "update sms_email_campaign_text set ";                              $fieldSQL = "";                  :            foreach($params as $param_name=>$param_value){   $                if($fieldSQL != ""){   .                    $fieldSQL = $fieldSQL.",";                   }   `                $fieldSQL = $fieldSQL.$param_name." = '".$this->conn->_escape($param_value)."'";               }                  ;            $sql = $sql.$fieldSQL." where text_id = '$ID'";                  2            $result = $this->conn->_execute($sql);   *            if ($this->conn->errno != 0) {   C                $this->returnObject->error_text = $this->conn->err;   +                return $this->returnObject;               }   +            if(is_bool($result) === false){   ,                mysqli_free_result($result);               }                              //Write Admin log   V            $ret = $this->conn->_writeAdminLog("TEXT_SAVED","TEXT","MODIFY",$sql,$ID);               if($ret != 1){   7                $this->returnObject->error_text = $ret;   +                return $this->returnObject;               }   	        }       2        $this->returnObject->items[0] = "";          /        $this->returnObject->items['ID'] = $ID;   (        $this->returnObject->status = 1;   #        return $this->returnObject;       }                 /*        * delete the Texts        */   &    function deleteTexts($valueObject)       {   =        $this->returnObject = new Result_Array_class();         3        if (!$this->_shard_connect($valueObject) ||   :            !$this->_check_param($valueObject, 'text_id'))   '            return $this->returnObject;                              *        $ID = $valueObject['text_id'];                      W        $sql = "update sms_email_campaign_text set deleted='Y' where text_id = '$ID'";                     .        $result = $this->conn->_execute($sql);   &        if ($this->conn->errno != 0) {   ?            $this->returnObject->error_text = $this->conn->err;   '            return $this->returnObject;   	        }              '        if(is_bool($result) === false){   (            mysqli_free_result($result);           }          #        return $this->returnObject;   	    }       }       ?>5�_�                     j        ����                                                                                                                                                                                                                                                                                                                                                             Y%�g    �   i   k          D            $this->returnObject->error_text = $this->conn->err.$sql;5��