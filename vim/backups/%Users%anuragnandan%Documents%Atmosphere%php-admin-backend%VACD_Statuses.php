Vim�UnDo� �a���L PމKL�ݙT�`��c�֭@< pE  w                  	       	   	   	    Y%Շ    _�                             ����                                                                                                                                                                                                                                                                                                                                                             Y%�A     �              �   <?php        	require_once("Datasource.php");   "	require_once("result_class.php");   $	require_once("campaign_class.php");    	require_once("Atmosphere.php");           &class VACD_Statuses extends Atmosphere   {   #	function getStatuses($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       +		if (!$this->_shard_connect($valueObject))   			return $this->returnObject;   			   )		$clientnum = $valueObject['clientnum'];       o		$sql = "SELECT s.id, s.name, s.description, m.WebServiceName, s.webservice,s.dnc,fa.customFlags,s.callback,";   K		$sql .= " CONCAT(s.notinterested, ',', s.survey, ',', s.sale) as flags ";   /		$sql .= " FROM vicidial_vacd_statuses as s ";   Z		$sql .= " left join vicidial_webservices_methods as m on (s.webservice=m.MethodRecnum)";   R		$sql .= " left join (select group_concat(flag_id) as customFlags,status_id from    V				vicidial_status_flag_assignments a group by status_id) fa on s.id = fa.status_id";   C		$sql .= " where s.client_id = '$clientnum' and s.deleted != 'Y'";   	   		   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   #			$this->returnObject->status = 0;   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}   2		while ($row = $this->conn->_nextRowObj($result))   '			$this->returnObject->items[] = $row;   	    		if(is_bool($result) === false)   &			$this->conn->_free_result($result);       "		$this->returnObject->status = 1;   		return $this->returnObject;   	}       "	function saveStatus($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       +		if (!$this->_shard_connect($valueObject))   			return $this->returnObject;       +		if (!isset($valueObject['status_name'])){   =			$this->returnObject->error_text = "status_name not found";   			return $this->returnObject;   		}       2		if (!isset($valueObject['status_description'])){   D			$this->returnObject->error_text = "status_description not found";   			return $this->returnObject;   		}       *		if (!isset($valueObject['webservice'])){   C			$this->returnObject->error_text = "status_webservice not found";   			return $this->returnObject;   		}       |		$sql = "insert into vicidial_vacd_statuses(name,description,sale,notinterested,survey,dnc,callback,client_id,webservice)";   P		$sql .= " values('" . $this->conn->_string($valueObject['status_name']) . "'";   Q		$sql .= ", '" . $this->conn->_string($valueObject['status_description']) . "'";   C		$sql .= ", '" . $this->conn->_string($valueObject['sale']) . "'";   L		$sql .= ", '" . $this->conn->_string($valueObject['notinterested']) . "'";   E		$sql .= ", '" . $this->conn->_string($valueObject['survey']) . "'";   B		$sql .= ", '" . $this->conn->_string($valueObject['dnc']) . "'";   G		$sql .= ", '" . $this->conn->_string($valueObject['callback']) . "'";   H		$sql .= ", '" . $this->conn->_string($valueObject['clientnum']) . "'";   J		$sql .= ", '" . $this->conn->_string($valueObject['webservice']) . "')";       (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}            		if(is_bool($result) === false)   			mysqli_free_result($result);       				   )		$status_id = $this->conn->_insert_id();   		   -		$this->returnObject->items[0] = $status_id;   			   \		$ret = $this->conn->_writeAdminLog("VACD_STATUS_ADD","VACD_STATUS","ADD",$sql,$status_id);   		if($ret != 1){   *			$this->returnObject->error_text = $ret;   			return $this->returnObject;   		}   		   ?		$return = $this->syncFlags($status_id,$valueObject['flags']);   		if($return !== true){   			return $this->returnObject;   		}       +		if(isset($valueObject['assigned_gates']))   		{   4			$assigned_gates = $valueObject['assigned_gates'];   			$retval = -1;   H			$retval = $this->updateAssignedGates($status_id, $assigned_gates, 0);   			if($retval == -1)   			{   				return $this->returnObject;   			}   		}   		   "		$this->returnObject->status = 1;   		return $this->returnObject;   	}   	   &	function syncFlags($status_id,$flags)   	{   <<<<<<< HEAD   s		$sql = "delete from vicidial_status_flag_assignments where status_id = '$status_id' and flag_id not in ($flags)";   =======   		$flagSQL = '';   		if($flags != ''){   -			$flagSQL = " and flag_id not in ($flags)";   		}	   			   `		$sql = "delete from vicidial_status_flag_assignments where status_id = '$status_id' $flagSQL";   <>>>>>>> 43d0bd6... fix to allow removing all reporting flags   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   ;			$this->returnObject->error_text = $this->conn->err.$sql;   			return $this->returnObject;   		}   		   <<<<<<< HEAD   #		$flagArray = explode(',',$flags);   "		foreach($flagArray as $flag_id){   W			$sql = "insert ignore into vicidial_status_flag_assignments (`flag_id`,`status_id`)    $				values ($flag_id,'$status_id')";   )			$result = $this->conn->_execute($sql);   !			if ($this->conn->errno != 0) {   <				$this->returnObject->error_text = $this->conn->err.$sql;   				return $this->returnObject;   =======   		if($flags != ''){   $			$flagArray = explode(',',$flags);   #			foreach($flagArray as $flag_id){   X				$sql = "insert ignore into vicidial_status_flag_assignments (`flag_id`,`status_id`)    %					values ($flag_id,'$status_id')";   *				$result = $this->conn->_execute($sql);   "				if ($this->conn->errno != 0) {   =					$this->returnObject->error_text = $this->conn->err.$sql;    					return $this->returnObject;   				}   <>>>>>>> 43d0bd6... fix to allow removing all reporting flags   			}   		}   		   		return true;   	}       $	function deleteStatus($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       +		if (!$this->_shard_connect($valueObject))   			return $this->returnObject;       )		if (!isset($valueObject['status_id'])){   ;			$this->returnObject->error_text = "status_id not found";   			return $this->returnObject;   		}       f		$sql = "update vicidial_vacd_statuses set deleted='Y' where id='" . $valueObject['status_id'] . "'";   	   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}   		   q		$ret = $this->conn->_writeAdminLog("VACD_STATUS_DELETE","VACD_STATUS","DELETE",$sql,$valueObject['status_id']);   		if($ret != 1){   *			$this->returnObject->error_text = $ret;   			return $this->returnObject;   		}        		if(is_bool($result) === false)    				mysqli_free_result($result);   				   l		$sql = "Delete From vicidial_vacd_status_assignments Where status_id='" . $valueObject['status_id'] . "'";   	   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}        		if(is_bool($result) === false)    				mysqli_free_result($result);       "		$this->returnObject->status = 1;   		return $this->returnObject;   	}       $	function updateStatus($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       +		if (!$this->_shard_connect($valueObject))   			return $this->returnObject;       +		if (!isset($valueObject['status_name'])){   =			$this->returnObject->error_text = "status_name not found";   			return $this->returnObject;   		}       )		if (!isset($valueObject['status_id'])){   ;			$this->returnObject->error_text = "status_id not found";   			return $this->returnObject;   		}       *		if (!isset($valueObject['webservice'])){   C			$this->returnObject->error_text = "status_webservice not found";   			return $this->returnObject;   		}       n		$sql = "update vicidial_vacd_statuses set name='" . $this->conn->_string($valueObject['status_name']) . "'";   ]		$sql .= ", description='" . $this->conn->_string($valueObject['status_description']) . "'";   T		$sql .= ", webservice='" . $this->conn->_string($valueObject['webservice']) . "'";   L		$sql .= ", survey='" . $this->conn->_string($valueObject['survey']) . "'";   Z		$sql .= ", notinterested='" . $this->conn->_string($valueObject['notinterested']) . "'";   F		$sql .= ", dnc='" . $this->conn->_string($valueObject['dnc']) . "'";   H		$sql .= ", sale='" . $this->conn->_string($valueObject['sale']) . "'";   P		$sql .= ", callback='" . $this->conn->_string($valueObject['callback']) . "'";   P		$sql .= " where id='" . $this->conn->_string($valueObject['status_id']) . "'";   	   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}   		   q		$ret = $this->conn->_writeAdminLog("VACD_STATUS_MODIFY","VACD_STATUS","MODIFY",$sql,$valueObject['status_id']);   		if($ret != 1){   *			$this->returnObject->error_text = $ret;   			return $this->returnObject;   		}        		if(is_bool($result) === false)    				mysqli_free_result($result);       N		$return = $this->syncFlags($valueObject['status_id'],$valueObject['flags']);   		if($return !== true){   			return $this->returnObject;   		}       +		if(isset($valueObject['assigned_gates']))   		{   4			$assigned_gates = $valueObject['assigned_gates'];   			$retval = -1;   W			$retval = $this->updateAssignedGates($valueObject['status_id'], $assigned_gates, 1);   			if($retval == -1)   			{   				return $this->returnObject;   			}   		}   				   "		$this->returnObject->status = 1;   		return $this->returnObject;   	}       +	function get_available_gates($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       -		if (!$this->_shard_connect($valueObject) ||   5			!$this->_check_param($valueObject, 'clientnum') ||   0			!$this->_check_param($valueObject, 'status'))   			return $this->returnObject;       *		/*if (isset($valueObject['sort_field']))   B			$sort_field = $this->conn->_string($valueObject['sort_field']);   		else   			$sort_field = "user_id";*/       9		$status = $this->conn->_string($valueObject['status']);   ?		$clientnum = $this->conn->_string($valueObject['clientnum']);       %		$sql = "SELECT group_id, group_name    			FROM vicidial_vacd_profile   �		 	Where client_id = '$clientnum' and deleted != 'Y' and group_id not in (Select group_id From vicidial_vacd_status_assignments Where status_id='$status' and group_id is not null)   		 	Order By group_name";   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   #			$this->returnObject->status = 0;   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}   2		while ($row = $this->conn->_nextRowObj($result))   '			$this->returnObject->items[] = $row;   "		$this->returnObject->status = 1;   %		$this->conn->_free_result($result);   		return $this->returnObject;   	}       *	function get_assigned_gates($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       -		if (!$this->_shard_connect($valueObject) ||   5			!$this->_check_param($valueObject, 'clientnum') ||   0			!$this->_check_param($valueObject, 'status'))   			return $this->returnObject;       *		/*if (isset($valueObject['sort_field']))   B			$sort_field = $this->conn->_string($valueObject['sort_field']);   		else   			$sort_field = "user_id";*/       9		$status = $this->conn->_string($valueObject['status']);   ?		$clientnum = $this->conn->_string($valueObject['clientnum']);       G		$sql = "SELECT a.id as recnum, a.group_id, b.group_name as group_name   n			 FROM vicidial_vacd_status_assignments as a LEFT JOIN vicidial_vacd_profile as b ON a.group_id = b.group_id   5			  Where a.status_id='$status' and b.deleted != 'Y'   			  Order By group_name";   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   #			$this->returnObject->status = 0;   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}   2		while ($row = $this->conn->_nextRowObj($result))   '			$this->returnObject->items[] = $row;   "		$this->returnObject->status = 1;   %		$this->conn->_free_result($result);   		return $this->returnObject;   	}       8	function updateAssignedGates($status, $gates, $update){   		foreach($gates as $row){   G			if(isset($row['recnum']) && $row['recnum'] != "" && $update == "1"){       				$recnum = $row['recnum'];   F				if(isset($row['changedFlag']) && $row['changedFlag'] == "DELETED")   				{   L					$sql = "Delete From vicidial_vacd_status_assignments Where id=$recnum";   +					$result = $this->conn->_execute($sql);   #					if ($this->conn->errno != 0) {   >						$this->returnObject->error_text = $this->conn->err.$sql;   						return "-1";   					}   $					if(is_bool($result) === false){   "						mysqli_free_result($result);   					}   				}   				}else{   g				if((isset($row['changedFlag']) && $row['changedFlag'] != "DELETED") || !isset($row['changedFlag']))   				{   "					$group_id = $row['group_id'];   ;					$sql = "Insert Into vicidial_vacd_status_assignments";   -					$sql .= " (status_id, group_id) VALUES";   )					$sql .= " ('$status', '$group_id')";   +					$result = $this->conn->_execute($sql);   #					if ($this->conn->errno != 0) {   >						$this->returnObject->error_text = $this->conn->err.$sql;   						return "-1";   					}   $					if(is_bool($result) === false){   "						mysqli_free_result($result);   					}   				}   			}   		}       		return "1";   	}           }   ?>5�_�                    �        ����                                                                                                                                                                                                                                                                                                                                                             Y%�N     �   �   �          <>>>>>>> 43d0bd6... fix to allow removing all reporting flags5�_�                    {        ����                                                                                                                                                                                                                                                                                                                                                             Y%�P     �   z   {          <<<<<<< HEAD5�_�                    {       ����                                                                                                                                                                                                                                                                                                                                                             Y%�Q     �   z   {          s		$sql = "delete from vicidial_status_flag_assignments where status_id = '$status_id' and flag_id not in ($flags)";5�_�                    {        ����                                                                                                                                                                                                                                                                                                                                                             Y%�Q     �   z   {          =======5�_�                    �        ����                                                                                                                                                                                                                                                                                                                            �           �          v���    Y%�Z     �   �   �  �      <<<<<<< HEAD   #		$flagArray = explode(',',$flags);   "		foreach($flagArray as $flag_id){   W			$sql = "insert ignore into vicidial_status_flag_assignments (`flag_id`,`status_id`)    $				values ($flag_id,'$status_id')";   )			$result = $this->conn->_execute($sql);   !			if ($this->conn->errno != 0) {   <				$this->returnObject->error_text = $this->conn->err.$sql;   				return $this->returnObject;   =======   		if($flags != ''){5�_�                    �        ����                                                                                                                                                                                                                                                                                                                            �           �          v���    Y%�\    �   �   �          <>>>>>>> 43d0bd6... fix to allow removing all reporting flags5�_�      	              �        ����                                                                                                                                                                                                                                                                                                                                                             Y%Մ     �              w   <?php        	require_once("Datasource.php");   "	require_once("result_class.php");   $	require_once("campaign_class.php");    	require_once("Atmosphere.php");           &class VACD_Statuses extends Atmosphere   {   #	function getStatuses($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       +		if (!$this->_shard_connect($valueObject))   			return $this->returnObject;   			   )		$clientnum = $valueObject['clientnum'];       o		$sql = "SELECT s.id, s.name, s.description, m.WebServiceName, s.webservice,s.dnc,fa.customFlags,s.callback,";   K		$sql .= " CONCAT(s.notinterested, ',', s.survey, ',', s.sale) as flags ";   /		$sql .= " FROM vicidial_vacd_statuses as s ";   Z		$sql .= " left join vicidial_webservices_methods as m on (s.webservice=m.MethodRecnum)";   R		$sql .= " left join (select group_concat(flag_id) as customFlags,status_id from    V				vicidial_status_flag_assignments a group by status_id) fa on s.id = fa.status_id";   C		$sql .= " where s.client_id = '$clientnum' and s.deleted != 'Y'";   	   		   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   #			$this->returnObject->status = 0;   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}   2		while ($row = $this->conn->_nextRowObj($result))   '			$this->returnObject->items[] = $row;   	    		if(is_bool($result) === false)   &			$this->conn->_free_result($result);       "		$this->returnObject->status = 1;   		return $this->returnObject;   	}       "	function saveStatus($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       +		if (!$this->_shard_connect($valueObject))   			return $this->returnObject;       +		if (!isset($valueObject['status_name'])){   =			$this->returnObject->error_text = "status_name not found";   			return $this->returnObject;   		}       2		if (!isset($valueObject['status_description'])){   D			$this->returnObject->error_text = "status_description not found";   			return $this->returnObject;   		}       *		if (!isset($valueObject['webservice'])){   C			$this->returnObject->error_text = "status_webservice not found";   			return $this->returnObject;   		}       |		$sql = "insert into vicidial_vacd_statuses(name,description,sale,notinterested,survey,dnc,callback,client_id,webservice)";   P		$sql .= " values('" . $this->conn->_string($valueObject['status_name']) . "'";   Q		$sql .= ", '" . $this->conn->_string($valueObject['status_description']) . "'";   C		$sql .= ", '" . $this->conn->_string($valueObject['sale']) . "'";   L		$sql .= ", '" . $this->conn->_string($valueObject['notinterested']) . "'";   E		$sql .= ", '" . $this->conn->_string($valueObject['survey']) . "'";   B		$sql .= ", '" . $this->conn->_string($valueObject['dnc']) . "'";   G		$sql .= ", '" . $this->conn->_string($valueObject['callback']) . "'";   H		$sql .= ", '" . $this->conn->_string($valueObject['clientnum']) . "'";   J		$sql .= ", '" . $this->conn->_string($valueObject['webservice']) . "')";       (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}            		if(is_bool($result) === false)   			mysqli_free_result($result);       				   )		$status_id = $this->conn->_insert_id();   		   -		$this->returnObject->items[0] = $status_id;   			   \		$ret = $this->conn->_writeAdminLog("VACD_STATUS_ADD","VACD_STATUS","ADD",$sql,$status_id);   		if($ret != 1){   *			$this->returnObject->error_text = $ret;   			return $this->returnObject;   		}   		   ?		$return = $this->syncFlags($status_id,$valueObject['flags']);   		if($return !== true){   			return $this->returnObject;   		}       +		if(isset($valueObject['assigned_gates']))   		{   4			$assigned_gates = $valueObject['assigned_gates'];   			$retval = -1;   H			$retval = $this->updateAssignedGates($status_id, $assigned_gates, 0);   			if($retval == -1)   			{   				return $this->returnObject;   			}   		}   		   "		$this->returnObject->status = 1;   		return $this->returnObject;   	}   	   &	function syncFlags($status_id,$flags)   	{   		$flagSQL = '';   		if($flags != ''){   -			$flagSQL = " and flag_id not in ($flags)";   		}	   			   `		$sql = "delete from vicidial_status_flag_assignments where status_id = '$status_id' $flagSQL";   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   ;			$this->returnObject->error_text = $this->conn->err.$sql;   			return $this->returnObject;   		}   		   		if($flags != ''){   $			$flagArray = explode(',',$flags);   #			foreach($flagArray as $flag_id){   X				$sql = "insert ignore into vicidial_status_flag_assignments (`flag_id`,`status_id`)    %					values ($flag_id,'$status_id')";   *				$result = $this->conn->_execute($sql);   "				if ($this->conn->errno != 0) {   =					$this->returnObject->error_text = $this->conn->err.$sql;    					return $this->returnObject;   				}   			}   		}   		   		return true;   	}       $	function deleteStatus($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       +		if (!$this->_shard_connect($valueObject))   			return $this->returnObject;       )		if (!isset($valueObject['status_id'])){   ;			$this->returnObject->error_text = "status_id not found";   			return $this->returnObject;   		}       f		$sql = "update vicidial_vacd_statuses set deleted='Y' where id='" . $valueObject['status_id'] . "'";   	   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}   		   q		$ret = $this->conn->_writeAdminLog("VACD_STATUS_DELETE","VACD_STATUS","DELETE",$sql,$valueObject['status_id']);   		if($ret != 1){   *			$this->returnObject->error_text = $ret;   			return $this->returnObject;   		}        		if(is_bool($result) === false)    				mysqli_free_result($result);   				   l		$sql = "Delete From vicidial_vacd_status_assignments Where status_id='" . $valueObject['status_id'] . "'";   	   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}        		if(is_bool($result) === false)    				mysqli_free_result($result);       "		$this->returnObject->status = 1;   		return $this->returnObject;   	}       $	function updateStatus($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       +		if (!$this->_shard_connect($valueObject))   			return $this->returnObject;       +		if (!isset($valueObject['status_name'])){   =			$this->returnObject->error_text = "status_name not found";   			return $this->returnObject;   		}       )		if (!isset($valueObject['status_id'])){   ;			$this->returnObject->error_text = "status_id not found";   			return $this->returnObject;   		}       *		if (!isset($valueObject['webservice'])){   C			$this->returnObject->error_text = "status_webservice not found";   			return $this->returnObject;   		}       n		$sql = "update vicidial_vacd_statuses set name='" . $this->conn->_string($valueObject['status_name']) . "'";   ]		$sql .= ", description='" . $this->conn->_string($valueObject['status_description']) . "'";   T		$sql .= ", webservice='" . $this->conn->_string($valueObject['webservice']) . "'";   L		$sql .= ", survey='" . $this->conn->_string($valueObject['survey']) . "'";   Z		$sql .= ", notinterested='" . $this->conn->_string($valueObject['notinterested']) . "'";   F		$sql .= ", dnc='" . $this->conn->_string($valueObject['dnc']) . "'";   H		$sql .= ", sale='" . $this->conn->_string($valueObject['sale']) . "'";   P		$sql .= ", callback='" . $this->conn->_string($valueObject['callback']) . "'";   P		$sql .= " where id='" . $this->conn->_string($valueObject['status_id']) . "'";   	   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}   		   q		$ret = $this->conn->_writeAdminLog("VACD_STATUS_MODIFY","VACD_STATUS","MODIFY",$sql,$valueObject['status_id']);   		if($ret != 1){   *			$this->returnObject->error_text = $ret;   			return $this->returnObject;   		}        		if(is_bool($result) === false)    				mysqli_free_result($result);       N		$return = $this->syncFlags($valueObject['status_id'],$valueObject['flags']);   		if($return !== true){   			return $this->returnObject;   		}       +		if(isset($valueObject['assigned_gates']))   		{   4			$assigned_gates = $valueObject['assigned_gates'];   			$retval = -1;   W			$retval = $this->updateAssignedGates($valueObject['status_id'], $assigned_gates, 1);   			if($retval == -1)   			{   				return $this->returnObject;   			}   		}   				   "		$this->returnObject->status = 1;   		return $this->returnObject;   	}       +	function get_available_gates($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       -		if (!$this->_shard_connect($valueObject) ||   5			!$this->_check_param($valueObject, 'clientnum') ||   0			!$this->_check_param($valueObject, 'status'))   			return $this->returnObject;       *		/*if (isset($valueObject['sort_field']))   B			$sort_field = $this->conn->_string($valueObject['sort_field']);   		else   			$sort_field = "user_id";*/       9		$status = $this->conn->_string($valueObject['status']);   ?		$clientnum = $this->conn->_string($valueObject['clientnum']);       %		$sql = "SELECT group_id, group_name    			FROM vicidial_vacd_profile   �		 	Where client_id = '$clientnum' and deleted != 'Y' and group_id not in (Select group_id From vicidial_vacd_status_assignments Where status_id='$status' and group_id is not null)   		 	Order By group_name";   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   #			$this->returnObject->status = 0;   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}   2		while ($row = $this->conn->_nextRowObj($result))   '			$this->returnObject->items[] = $row;   "		$this->returnObject->status = 1;   %		$this->conn->_free_result($result);   		return $this->returnObject;   	}       *	function get_assigned_gates($valueObject)   	{   1		$this->returnObject = new Result_Array_class();   "		$this->returnObject->status = 0;       -		if (!$this->_shard_connect($valueObject) ||   5			!$this->_check_param($valueObject, 'clientnum') ||   0			!$this->_check_param($valueObject, 'status'))   			return $this->returnObject;       *		/*if (isset($valueObject['sort_field']))   B			$sort_field = $this->conn->_string($valueObject['sort_field']);   		else   			$sort_field = "user_id";*/       9		$status = $this->conn->_string($valueObject['status']);   ?		$clientnum = $this->conn->_string($valueObject['clientnum']);       G		$sql = "SELECT a.id as recnum, a.group_id, b.group_name as group_name   n			 FROM vicidial_vacd_status_assignments as a LEFT JOIN vicidial_vacd_profile as b ON a.group_id = b.group_id   5			  Where a.status_id='$status' and b.deleted != 'Y'   			  Order By group_name";   (		$result = $this->conn->_execute($sql);    		if ($this->conn->errno != 0) {   #			$this->returnObject->status = 0;   6			$this->returnObject->error_text = $this->conn->err;   			return $this->returnObject;   		}   2		while ($row = $this->conn->_nextRowObj($result))   '			$this->returnObject->items[] = $row;   "		$this->returnObject->status = 1;   %		$this->conn->_free_result($result);   		return $this->returnObject;   	}       8	function updateAssignedGates($status, $gates, $update){   		foreach($gates as $row){   G			if(isset($row['recnum']) && $row['recnum'] != "" && $update == "1"){       				$recnum = $row['recnum'];   F				if(isset($row['changedFlag']) && $row['changedFlag'] == "DELETED")   				{   L					$sql = "Delete From vicidial_vacd_status_assignments Where id=$recnum";   +					$result = $this->conn->_execute($sql);   #					if ($this->conn->errno != 0) {   >						$this->returnObject->error_text = $this->conn->err.$sql;   						return "-1";   					}   $					if(is_bool($result) === false){   "						mysqli_free_result($result);   					}   				}   				}else{   g				if((isset($row['changedFlag']) && $row['changedFlag'] != "DELETED") || !isset($row['changedFlag']))   				{   "					$group_id = $row['group_id'];   ;					$sql = "Insert Into vicidial_vacd_status_assignments";   -					$sql .= " (status_id, group_id) VALUES";   )					$sql .= " ('$status', '$group_id')";   +					$result = $this->conn->_execute($sql);   #					if ($this->conn->errno != 0) {   >						$this->returnObject->error_text = $this->conn->err.$sql;   						return "-1";   					}   $					if(is_bool($result) === false){   "						mysqli_free_result($result);   					}   				}   			}   		}       		return "1";   	}           }   ?>5�_�                  	   �        ����                                                                                                                                                                                                                                                                                                                                                             Y%Ն    �  g  i          >						$this->returnObject->error_text = $this->conn->err.$sql;�  W  Y          >						$this->returnObject->error_text = $this->conn->err.$sql;�   �   �          =					$this->returnObject->error_text = $this->conn->err.$sql;�   �   �          ;			$this->returnObject->error_text = $this->conn->err.$sql;5��