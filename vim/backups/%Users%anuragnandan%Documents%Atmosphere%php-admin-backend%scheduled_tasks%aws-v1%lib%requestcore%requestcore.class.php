Vim�UnDo� s)z�ܜڽ�W��UU��b��6�~��R���                                     YV�2    _�                             ����                                                                                                                                                                                                                                                                                                                                                             YV�/     �                 <?php   /**   B * Handles all HTTP requests using cURL and manages the responses.    *    * @version 2012.01.17   # * @copyright 2006-2011 Ryan Parman   # * @copyright 2006-2010 Foleeo Inc.   ; * @copyright 2010-2011 Amazon.com, Inc. or its affiliates.   $ * @copyright 2008-2011 Contributors   Q * @license http://opensource.org/licenses/bsd-license.php Simplified BSD License    */   class RequestCore   {   	/**   	 * The URL being requested.   	 */   	public $request_url;       	/**   *	 * The headers being sent in the request.   	 */   	public $request_headers;       	/**   '	 * The body being sent in the request.   	 */   	public $request_body;       	/**   )	 * The response returned by the request.   	 */   	public $response;       	/**   (	 * The headers returned by the request.   	 */   	public $response_headers;       	/**   %	 * The body returned by the request.   	 */   	public $response_body;       	/**   1	 * The HTTP status code returned by the request.   	 */   	public $response_code;       	/**   	 * Additional response data.   	 */   	public $response_info;       	/**   #	 * The handle for the cURL object.   	 */   	public $curl_handle;       	/**   2	 * The method by which the request is being made.   	 */   	public $method;       	/**   5	 * Stores the proxy settings to use for the request.   	 */   	public $proxy = null;       	/**   (	 * The username to use for the request.   	 */   	public $username = null;       	/**   (	 * The password to use for the request.   	 */   	public $password = null;       	/**   	 * Custom CURLOPT settings.   	 */   	public $curlopts = null;       	/**   	 * The state of debug mode.   	 */   	public $debug_mode = false;       	/**   K	 * The default class to use for HTTP Requests (defaults to <RequestCore>).   	 */   '	public $request_class = 'RequestCore';       	/**   M	 * The default class to use for HTTP Responses (defaults to <ResponseCore>).   	 */   )	public $response_class = 'ResponseCore';       	/**   $	 * Default useragent string to use.   	 */   )	public $useragent = 'RequestCore/1.4.4';       	/**   )	 * File to read from while streaming up.   	 */   	public $read_file = null;       	/**   1	 * The resource to read from while streaming up.   	 */   	public $read_stream = null;       	/**   (	 * The size of the stream to read from.   	 */   !	public $read_stream_size = null;       	/**   ,	 * The length already read from the stream.   	 */   	public $read_stream_read = 0;       	/**   *	 * File to write to while streaming down.   	 */   	public $write_file = null;       	/**   2	 * The resource to write to while streaming down.   	 */   	public $write_stream = null;       	/**   /	 * Stores the intended starting seek position.   	 */   	public $seek_position = null;       	/**   /	 * The location of the cacert.pem file to use.   	 */   !	public $cacert_location = false;       	/**   .	 * The state of SSL certificate verification.   	 */   !	public $ssl_verification = true;       	/**   J	 * The user-defined callback function to call when a stream is read from.   	 */   3	public $registered_streaming_read_callback = null;       	/**   K	 * The user-defined callback function to call when a stream is written to.   	 */   4	public $registered_streaming_write_callback = null;       	/**   @	 * Whether or not the set_time_limit function should be called.   	 */   %	public $allow_set_time_limit = true;       	/**   <	 * Whether or not to use gzip encoding via CURLOPT_ENCODING   	 */   #	public $use_gzip_enconding = true;           a	/*%******************************************************************************************%*/   	// CONSTANTS       	/**   	 * GET HTTP Method   	 */   	const HTTP_GET = 'GET';       	/**   	 * POST HTTP Method   	 */   	const HTTP_POST = 'POST';       	/**   	 * PUT HTTP Method   	 */   	const HTTP_PUT = 'PUT';       	/**   	 * DELETE HTTP Method   	 */   	const HTTP_DELETE = 'DELETE';       	/**   	 * HEAD HTTP Method   	 */   	const HTTP_HEAD = 'HEAD';           a	/*%******************************************************************************************%*/   	// CONSTRUCTOR/DESTRUCTOR       	/**   ,	 * Constructs a new instance of this class.   	 *   R	 * @param string $url (Optional) The URL to request or service endpoint to query.   �	 * @param string $proxy (Optional) The faux-url to use for proxy settings. Takes the following format: `proxy://user:pass@hostname:port`   �	 * @param array $helpers (Optional) An associative array of classnames to use for request, and response functionality. Gets passed in automatically by the calling class.   6	 * @return $this A reference to the current instance.   	 */   I	public function __construct($url = null, $proxy = null, $helpers = null)   	{   		// Set some default values.   		$this->request_url = $url;   !		$this->method = self::HTTP_GET;   #		$this->request_headers = array();   		$this->request_body = '';       .		// Determine if set_time_limit can be called   G		if (strpos(ini_get('disable_functions'), 'set_time_limit') !== false)   		{   '			$this->allow_set_time_limit = false;   		}       ,		// Set a new Request class if one was set.   @		if (isset($helpers['request']) && !empty($helpers['request']))   		{   .			$this->request_class = $helpers['request'];   		}       ,		// Set a new Request class if one was set.   B		if (isset($helpers['response']) && !empty($helpers['response']))   		{   0			$this->response_class = $helpers['response'];   		}       		if ($proxy)   		{   			$this->set_proxy($proxy);   		}       		return $this;   	}       	/**   7	 * Destructs the instance. Closes opened file handles.   	 *   6	 * @return $this A reference to the current instance.   	 */   	public function __destruct()   	{   ;		if (isset($this->read_file) && isset($this->read_stream))   		{   			fclose($this->read_stream);   		}       =		if (isset($this->write_file) && isset($this->write_stream))   		{   			fclose($this->write_stream);   		}       		return $this;   	}           a	/*%******************************************************************************************%*/   	// REQUEST METHODS       	/**   3	 * Sets the credentials to use for authentication.   	 *   E	 * @param string $user (Required) The username to authenticate with.   E	 * @param string $pass (Required) The password to authenticate with.   6	 * @return $this A reference to the current instance.   	 */   .	public function set_credentials($user, $pass)   	{   		$this->username = $user;   		$this->password = $pass;   		return $this;   	}       	/**   2	 * Adds a custom HTTP header to the cURL request.   	 *   @	 * @param string $key (Required) The custom HTTP header to set.   Q	 * @param mixed $value (Required) The value to assign to the custom HTTP header.   6	 * @return $this A reference to the current instance.   	 */   )	public function add_header($key, $value)   	{   (		$this->request_headers[$key] = $value;   		return $this;   	}       	/**   1	 * Removes an HTTP header from the cURL request.   	 *   @	 * @param string $key (Required) The custom HTTP header to set.   6	 * @return $this A reference to the current instance.   	 */   $	public function remove_header($key)   	{   *		if (isset($this->request_headers[$key]))   		{   '			unset($this->request_headers[$key]);   		}   		return $this;   	}       	/**   (	 * Set the method type for the request.   	 *   �	 * @param string $method (Required) One of the following constants: <HTTP_GET>, <HTTP_POST>, <HTTP_PUT>, <HTTP_HEAD>, <HTTP_DELETE>.   6	 * @return $this A reference to the current instance.   	 */   $	public function set_method($method)   	{   &		$this->method = strtoupper($method);   		return $this;   	}       	/**   1	 * Sets a custom useragent string for the class.   	 *   =	 * @param string $ua (Required) The useragent string to use.   6	 * @return $this A reference to the current instance.   	 */   #	public function set_useragent($ua)   	{   		$this->useragent = $ua;   		return $this;   	}       	/**   (	 * Set the body to send in the request.   	 *   `	 * @param string $body (Required) The textual content to send along in the body of the request.   6	 * @return $this A reference to the current instance.   	 */    	public function set_body($body)   	{   		$this->request_body = $body;   		return $this;   	}       	/**   '	 * Set the URL to make the request to.   	 *   A	 * @param string $url (Required) The URL to make the request to.   6	 * @return $this A reference to the current instance.   	 */   &	public function set_request_url($url)   	{   		$this->request_url = $url;   		return $this;   	}       	/**   `	 * Set additional CURLOPT settings. These will merge with the default settings, and override if   	 * there is a duplicate.   	 *   �	 * @param array $curlopts (Optional) A set of key-value pairs that set `CURLOPT` options. These will merge with the existing CURLOPTs, and ones passed here will override the defaults. Keys should be the `CURLOPT_*` constants, not strings.   6	 * @return $this A reference to the current instance.   	 */   (	public function set_curlopts($curlopts)   	{   		$this->curlopts = $curlopts;   		return $this;   	}       	/**   H	 * Sets the length in bytes to read from the stream while streaming up.   	 *   P	 * @param integer $size (Required) The length in bytes to read from the stream.   6	 * @return $this A reference to the current instance.   	 */   ,	public function set_read_stream_size($size)   	{   "		$this->read_stream_size = $size;       		return $this;   	}       	/**   g	 * Sets the resource to read from while streaming up. Reads the stream from its current position until   k	 * EOF or `$size` bytes have been read. If `$size` is not given it will be determined by <php:fstat()> and   	 * <php:ftell()>.   	 *   L	 * @param resource $resource (Required) The readable resource to read from.   C	 * @param integer $size (Optional) The size of the stream to read.   6	 * @return $this A reference to the current instance.   	 */   9	public function set_read_stream($resource, $size = null)   	{   !		if (!isset($size) || $size < 0)   		{   			$stats = fstat($resource);       %			if ($stats && $stats['size'] >= 0)   			{   !				$position = ftell($resource);       .				if ($position !== false && $position >= 0)   				{   (					$size = $stats['size'] - $position;   				}   			}   		}       !		$this->read_stream = $resource;       ,		return $this->set_read_stream_size($size);   	}       	/**   2	 * Sets the file to read from while streaming up.   	 *   J	 * @param string $location (Required) The readable location to read from.   6	 * @return $this A reference to the current instance.   	 */   )	public function set_read_file($location)   	{   		$this->read_file = $location;   ,		$read_file_handle = fopen($location, 'r');       3		return $this->set_read_stream($read_file_handle);   	}       	/**   7	 * Sets the resource to write to while streaming down.   	 *   L	 * @param resource $resource (Required) The writeable resource to write to.   6	 * @return $this A reference to the current instance.   	 */   ,	public function set_write_stream($resource)   	{   "		$this->write_stream = $resource;       		return $this;   	}       	/**   3	 * Sets the file to write to while streaming down.   	 *   J	 * @param string $location (Required) The writeable location to write to.   6	 * @return $this A reference to the current instance.   	 */   *	public function set_write_file($location)   	{    		$this->write_file = $location;   -		$write_file_handle = fopen($location, 'w');       5		return $this->set_write_stream($write_file_handle);   	}       	/**   -	 * Set the proxy to use for making requests.   	 *   �	 * @param string $proxy (Required) The faux-url to use for proxy settings. Takes the following format: `proxy://user:pass@hostname:port`   6	 * @return $this A reference to the current instance.   	 */   "	public function set_proxy($proxy)   	{   		$proxy = parse_url($proxy);   A		$proxy['user'] = isset($proxy['user']) ? $proxy['user'] : null;   A		$proxy['pass'] = isset($proxy['pass']) ? $proxy['pass'] : null;   A		$proxy['port'] = isset($proxy['port']) ? $proxy['port'] : null;   		$this->proxy = $proxy;   		return $this;   	}       	/**   ,	 * Set the intended starting seek position.   	 *   ^	 * @param integer $position (Required) The byte-position of the stream to begin reading from.   6	 * @return $this A reference to the current instance.   	 */   -	public function set_seek_position($position)   	{   G		$this->seek_position = isset($position) ? (integer) $position : null;       		return $this;   	}       	/**   U	 * Register a callback function to execute whenever a data stream is read from using   +	 * <CFRequest::streaming_read_callback()>.   	 *   E	 * The user-defined callback function should accept three arguments:   	 *   	 * <ul>   �	 * 	<li><code>$curl_handle</code> - <code>resource</code> - Required - The cURL handle resource that represents the in-progress transfer.</li>   �	 * 	<li><code>$file_handle</code> - <code>resource</code> - Required - The file handle resource that represents the file on the local file system.</li>   �	 * 	<li><code>$length</code> - <code>integer</code> - Required - The length in kilobytes of the data chunk that was transferred.</li>   		 * </ul>   	 *   �	 * @param string|array|function $callback (Required) The callback function is called by <php:call_user_func()>, so you can pass the following values: <ul>   K	 * 	<li>The name of a global function to execute, passed as a string.</li>   [	 * 	<li>A method to execute, passed as <code>array('ClassName', 'MethodName')</code>.</li>   4	 * 	<li>An anonymous function (PHP 5.3+).</li></ul>   6	 * @return $this A reference to the current instance.   	 */   <	public function register_streaming_read_callback($callback)   	{   8		$this->registered_streaming_read_callback = $callback;       		return $this;   	}       	/**   V	 * Register a callback function to execute whenever a data stream is written to using   ,	 * <CFRequest::streaming_write_callback()>.   	 *   C	 * The user-defined callback function should accept two arguments:   	 *   	 * <ul>   �	 * 	<li><code>$curl_handle</code> - <code>resource</code> - Required - The cURL handle resource that represents the in-progress transfer.</li>   �	 * 	<li><code>$length</code> - <code>integer</code> - Required - The length in kilobytes of the data chunk that was transferred.</li>   		 * </ul>   	 *   �	 * @param string|array|function $callback (Required) The callback function is called by <php:call_user_func()>, so you can pass the following values: <ul>   K	 * 	<li>The name of a global function to execute, passed as a string.</li>   [	 * 	<li>A method to execute, passed as <code>array('ClassName', 'MethodName')</code>.</li>   4	 * 	<li>An anonymous function (PHP 5.3+).</li></ul>   6	 * @return $this A reference to the current instance.   	 */   =	public function register_streaming_write_callback($callback)   	{   9		$this->registered_streaming_write_callback = $callback;       		return $this;   	}           a	/*%******************************************************************************************%*/   &	// PREPARE, SEND, AND PROCESS REQUEST       	/**   A	 * A callback function that is invoked by cURL for streaming up.   	 *   L	 * @param resource $curl_handle (Required) The cURL handle for the request.   J	 * @param resource $file_handle (Required) The open file handle resource.   J	 * @param integer $length (Required) The maximum number of bytes to read.   -	 * @return binary Binary data from a stream.   	 */   M	public function streaming_read_callback($curl_handle, $file_handle, $length)   	{   9		// Once we've sent as much as we're supposed to send...   9		if ($this->read_stream_read >= $this->read_stream_size)   		{   			// Send EOF   			return '';   		}       ?		// If we're at the beginning of an upload and need to seek...   x		if ($this->read_stream_read == 0 && isset($this->seek_position) && $this->seek_position !== ftell($this->read_stream))   		{   =			if (fseek($this->read_stream, $this->seek_position) !== 0)   			{   �				throw new RequestCore_Exception('The stream does not support seeking and is either not at the requested position or the position is unknown.');   			}   		}       �		$read = fread($this->read_stream, min($this->read_stream_size - $this->read_stream_read, $length)); // Remaining upload data or cURL's requested chunk size   +		$this->read_stream_read += strlen($read);       &		$out = $read === false ? '' : $read;       		// Execute callback function   0		if ($this->registered_streaming_read_callback)   		{   _			call_user_func($this->registered_streaming_read_callback, $curl_handle, $file_handle, $out);   		}       		return $out;   	}       	/**   C	 * A callback function that is invoked by cURL for streaming down.   	 *   L	 * @param resource $curl_handle (Required) The cURL handle for the request.   5	 * @param binary $data (Required) The data to write.   0	 * @return integer The number of bytes written.   	 */   >	public function streaming_write_callback($curl_handle, $data)   	{   		$length = strlen($data);   		$written_total = 0;   		$written_last = 0;       "		while ($written_total < $length)   		{   N			$written_last = fwrite($this->write_stream, substr($data, $written_total));       			if ($written_last === false)   			{   				return $written_total;   			}       #			$written_total += $written_last;   		}       		// Execute callback function   1		if ($this->registered_streaming_write_callback)   		{   \			call_user_func($this->registered_streaming_write_callback, $curl_handle, $written_total);   		}       		return $written_total;   	}       	/**   l	 * Prepares and adds the details of the cURL request. This can be passed along to a <php:curl_multi_exec()>   	 * function.   	 *   4	 * @return resource The handle for the cURL object.   	 */   	public function prep_request()   	{   		$curl_handle = curl_init();       		// Set default options.   =		curl_setopt($curl_handle, CURLOPT_URL, $this->request_url);   4		curl_setopt($curl_handle, CURLOPT_FILETIME, true);   :		curl_setopt($curl_handle, CURLOPT_FRESH_CONNECT, false);   V		curl_setopt($curl_handle, CURLOPT_CLOSEPOLICY, CURLCLOSEPOLICY_LEAST_RECENTLY_USED);   2		curl_setopt($curl_handle, CURLOPT_MAXREDIRS, 5);   2		curl_setopt($curl_handle, CURLOPT_HEADER, true);   :		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);   6		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5184000);   9		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 120);   4		curl_setopt($curl_handle, CURLOPT_NOSIGNAL, true);   A		curl_setopt($curl_handle, CURLOPT_REFERER, $this->request_url);   A		curl_setopt($curl_handle, CURLOPT_USERAGENT, $this->useragent);   [		curl_setopt($curl_handle, CURLOPT_READFUNCTION, array($this, 'streaming_read_callback'));       !		// Verification of the SSL cert   		if ($this->ssl_verification)   		{   ;			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, true);   8			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 2);   		}   		else   		{   <			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);   <			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);   		}       		// chmod the file as 0755   &		if ($this->cacert_location === true)   		{   P			curl_setopt($curl_handle, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');   		}   ,		elseif (is_string($this->cacert_location))   		{   E			curl_setopt($curl_handle, CURLOPT_CAINFO, $this->cacert_location);   		}       		// Debug mode   		if ($this->debug_mode)   		{   4			curl_setopt($curl_handle, CURLOPT_VERBOSE, true);   		}       $		// Handle open_basedir & safe mode   8		if (!ini_get('safe_mode') && !ini_get('open_basedir'))   		{   ;			curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);   		}       ,		// Enable a proxy connection if requested.   		if ($this->proxy)   		{   <			curl_setopt($curl_handle, CURLOPT_HTTPPROXYTUNNEL, true);        			$host = $this->proxy['host'];   E			$host .= ($this->proxy['port']) ? ':' . $this->proxy['port'] : '';   3			curl_setopt($curl_handle, CURLOPT_PROXY, $host);       B			if (isset($this->proxy['user']) && isset($this->proxy['pass']))   			{   g				curl_setopt($curl_handle, CURLOPT_PROXYUSERPWD, $this->proxy['user'] . ':' . $this->proxy['pass']);   			}   		}       :		// Set credentials for HTTP Basic/Digest Authentication.   )		if ($this->username && $this->password)   		{   =			curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_ANY);   W			curl_setopt($curl_handle, CURLOPT_USERPWD, $this->username . ':' . $this->password);   		}       #		// Handle the encoding if we can.   <		if ($this->use_gzip_enconding && extension_loaded('zlib'))   		{   @			curl_setopt($curl_handle, CURLOPT_ENCODING, 'gzip, deflate');   		}       		// Process custom headers   E		if (isset($this->request_headers) && count($this->request_headers))   		{   			$temp_headers = array();       ;			if (!array_key_exists('Expect', $this->request_headers))   			{   *				$this->request_headers['Expect'] = '';   			}       /			foreach ($this->request_headers as $k => $v)   			{   %				$temp_headers[] = $k . ': ' . $v;   			}       @			curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $temp_headers);   		}       		switch ($this->method)   		{   			case self::HTTP_PUT:   <				curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'PUT');   "				if (isset($this->read_stream))   				{   H					if (!isset($this->read_stream_size) || $this->read_stream_size < 0)   					{   h						throw new RequestCore_Exception('The stream size for the streaming upload cannot be determined.');   					}       L					curl_setopt($curl_handle, CURLOPT_INFILESIZE, $this->read_stream_size);   5					curl_setopt($curl_handle, CURLOPT_UPLOAD, true);   				}   				else   				{   H					curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $this->request_body);   				}   
				break;       			case self::HTTP_POST:   2				curl_setopt($curl_handle, CURLOPT_POST, true);   G				curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $this->request_body);   
				break;       			case self::HTTP_HEAD:   F				curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, self::HTTP_HEAD);   1				curl_setopt($curl_handle, CURLOPT_NOBODY, 1);   
				break;       			default: // Assumed GET   D				curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, $this->method);   #				if (isset($this->write_stream))   				{   `					curl_setopt($curl_handle, CURLOPT_WRITEFUNCTION, array($this, 'streaming_write_callback'));   6					curl_setopt($curl_handle, CURLOPT_HEADER, false);   				}   				else   				{   H					curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $this->request_body);   				}   
				break;   		}       		// Merge in the CURLOPTs   <		if (isset($this->curlopts) && sizeof($this->curlopts) > 0)   		{   (			foreach ($this->curlopts as $k => $v)   			{   &				curl_setopt($curl_handle, $k, $v);   			}   		}       		return $curl_handle;   	}       	/**   e	 * Take the post-processed cURL data and break it down into useful header/body/info chunks. Uses the   g	 * data stored in the `curl_handle` and `response` properties unless replacement data is passed in via   	 * parameters.   	 *   _	 * @param resource $curl_handle (Optional) The reference to the already executed cURL request.   b	 * @param string $response (Optional) The actual response content itself that needs to be parsed.   S	 * @return ResponseCore A <ResponseCore> object containing a parsed HTTP response.   	 */   H	public function process_response($curl_handle = null, $response = null)   	{   (		// Accept a custom one if it's passed.    		if ($curl_handle && $response)   		{   %			$this->curl_handle = $curl_handle;   			$this->response = $response;   		}       5		// As long as this came back as a valid resource...   &		if (is_resource($this->curl_handle))   		{   			// Determine what's what.   I			$header_size = curl_getinfo($this->curl_handle, CURLINFO_HEADER_SIZE);   F			$this->response_headers = substr($this->response, 0, $header_size);   @			$this->response_body = substr($this->response, $header_size);   O			$this->response_code = curl_getinfo($this->curl_handle, CURLINFO_HTTP_CODE);   ;			$this->response_info = curl_getinfo($this->curl_handle);       			// Parse out the headers   P			$this->response_headers = explode("\r\n\r\n", trim($this->response_headers));   @			$this->response_headers = array_pop($this->response_headers);   F			$this->response_headers = explode("\r\n", $this->response_headers);   (			array_shift($this->response_headers);       ,			// Loop through and split up the headers.   			$header_assoc = array();   /			foreach ($this->response_headers as $header)   			{   !				$kv = explode(': ', $header);   /				$header_assoc[strtolower($kv[0])] = $kv[1];   			}       4			// Reset the headers to the appropriate property.   +			$this->response_headers = $header_assoc;   ;			$this->response_headers['_info'] = $this->response_info;   >			$this->response_headers['_info']['method'] = $this->method;       !			if ($curl_handle && $response)   			{   ~				return new $this->response_class($this->response_headers, $this->response_body, $this->response_code, $this->curl_handle);   			}   		}       		// Return false   		return false;   	}       	/**   Y	 * Sends the request, calling necessary utility functions to update built-in properties.   	 *   \	 * @param boolean $parse (Optional) Whether to parse the response with ResponseCore or not.   @	 * @return string The resulting unparsed data from the request.   	 */   -	public function send_request($parse = false)   	{   "		if ($this->allow_set_time_limit)   		{   			set_time_limit(0);   		}       '		$curl_handle = $this->prep_request();   ,		$this->response = curl_exec($curl_handle);        		if ($this->response === false)   		{  			throw new cURL_Exception('cURL resource: ' . (string) $curl_handle . '; cURL error: ' . curl_error($curl_handle) . ' (cURL error code ' . curl_errno($curl_handle) . '). See http://curl.haxx.se/libcurl/c/libcurl-errors.html for an explanation of error codes.');   		}       L		$parsed_response = $this->process_response($curl_handle, $this->response);       		curl_close($curl_handle);       		if ($parse)   		{   			return $parsed_response;   		}       		return $this->response;   	}       	/**   k	 * Sends the request using <php:curl_multi_exec()>, enabling parallel requests. Uses the "rolling" method.   	 *   `	 * @param array $handles (Required) An indexed array of cURL handles to process simultaneously.   j	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>  	 * 	<li><code>callback</code> - <code>string|array</code> - Optional - The string name of a function to pass the response data to. If this is a method, pass an array where the <code>[0]</code> index is the class and the <code>[1]</code> index is the method name.</li>   �	 * 	<li><code>limit</code> - <code>integer</code> - Optional - The number of simultaneous requests to make. This can be useful for scaling around slow server responses. Defaults to trusting cURLs judgement as to how many to use.</li></ul>   0	 * @return array Post-processed cURL responses.   	 */   :	public function send_multi_request($handles, $opt = null)   	{   "		if ($this->allow_set_time_limit)   		{   			set_time_limit(0);   		}       8		// Skip everything if there are no handles to process.   ,		if (count($handles) === 0) return array();       		if (!$opt) $opt = array();       #		// Initialize any missing options   5		$limit = isset($opt['limit']) ? $opt['limit'] : -1;       		// Initialize   		$handle_list = $handles;   %		$http = new $this->request_class();   $		$multi_handle = curl_multi_init();   		$handles_post = array();   		$added = count($handles);   		$last_handle = null;   		$count = 0;   			$i = 0;       T		// Loop through the cURL handles and add as many as it set by the limit parameter.   		while ($i < $added)   		{   )			if ($limit > 0 && $i >= $limit) break;   ?			curl_multi_add_handle($multi_handle, array_shift($handles));   			$i++;   		}       		do   		{   			$active = false;       .			// Start executing and wait for a response.   [			while (($status = curl_multi_exec($multi_handle, $active)) === CURLM_CALL_MULTI_PERFORM)   			{   X				// Start looking for possible responses immediately when we have to add more handles   #				if (count($handles) > 0) break;   			}       )			// Figure out which requests finished.   			$to_process = array();       6			while ($done = curl_multi_info_read($multi_handle))   			{   �				// Since curl_errno() isn't reliable for handles that were in multirequests, we check the 'result' of the info read, which contains the curl error number, (listed here http://curl.haxx.se/libcurl/c/libcurl-errors.html )   				if ($done['result'] > 0)   				{  					throw new cURL_Multi_Exception('cURL resource: ' . (string) $done['handle'] . '; cURL error: ' . curl_error($done['handle']) . ' (cURL error code ' . $done['result'] . '). See http://curl.haxx.se/libcurl/c/libcurl-errors.html for an explanation of error codes.');   				}       �				// Because curl_multi_info_read() might return more than one message about a request, we check to see if this request is already in our array of completed requests   7				elseif (!isset($to_process[(int) $done['handle']]))   				{   0					$to_process[(int) $done['handle']] = $done;   				}   			}       $			// Actually deal with the request   *			foreach ($to_process as $pkey => $done)   			{   a				$response = $http->process_response($done['handle'], curl_multi_getcontent($done['handle']));   =				$key = array_search($done['handle'], $handle_list, true);   $				$handles_post[$key] = $response;       				if (count($handles) > 0)   				{   A					curl_multi_add_handle($multi_handle, array_shift($handles));   				}       =				curl_multi_remove_handle($multi_handle, $done['handle']);    				curl_close($done['handle']);   			}   		}   3		while ($active || count($handles_post) < $added);       "		curl_multi_close($multi_handle);       %		ksort($handles_post, SORT_NUMERIC);   		return $handles_post;   	}           a	/*%******************************************************************************************%*/   	// RESPONSE METHODS       	/**   3	 * Get the HTTP response headers from the request.   	 *   `	 * @param string $header (Optional) A specific header value to return. Defaults to all headers.   7	 * @return string|array All or selected header values.   	 */   4	public function get_response_header($header = null)   	{   		if ($header)   		{   7			return $this->response_headers[strtolower($header)];   		}   !		return $this->response_headers;   	}       	/**   0	 * Get the HTTP response body from the request.   	 *   %	 * @return string The response body.   	 */   $	public function get_response_body()   	{   		return $this->response_body;   	}       	/**   0	 * Get the HTTP response code from the request.   	 *   *	 * @return string The HTTP response code.   	 */   $	public function get_response_code()   	{   		return $this->response_code;   	}   }           /**   . * Container for all response-related methods.    */   class ResponseCore   {   	/**   '	 * Stores the HTTP header information.   	 */   	public $header;       	/**   "	 * Stores the SimpleXML response.   	 */   	public $body;       	/**   "	 * Stores the HTTP response code.   	 */   	public $status;       	/**   ,	 * Constructs a new instance of this class.   	 *   �	 * @param array $header (Required) Associative array of HTTP headers (typically returned by <RequestCore::get_response_header()>).   C	 * @param string $body (Required) XML-formatted response from AWS.   Q	 * @param integer $status (Optional) HTTP response status code from the request.   �	 * @return object Contains an <php:array> `header` property (HTTP headers as an associative array), a <php:SimpleXMLElement> or <php:string> `body` property, and an <php:integer> `status` code.   	 */   <	public function __construct($header, $body, $status = null)   	{   		$this->header = $header;   		$this->body = $body;   		$this->status = $status;       		return $this;   	}       	/**   /	 * Did we receive the status code we expected?   	 *   �	 * @param integer|array $codes (Optional) The status code(s) to expect. Pass an <php:integer> for a single acceptable value, or an <php:array> of integers for multiple acceptable values.   H	 * @return boolean Whether we received the expected status code or not.   	 */   9	public function isOK($codes = array(200, 201, 204, 206))   	{   		if (is_array($codes))   		{   *			return in_array($this->status, $codes);   		}       "		return $this->status === $codes;   	}   }       )class cURL_Exception extends Exception {}   4class cURL_Multi_Exception extends cURL_Exception {}   0class RequestCore_Exception extends Exception {}5�_�                    r       ����                                                                                                                                                                                                                                                                                                                                                             YV�1    �  q  r          V		curl_setopt($curl_handle, CURLOPT_CLOSEPOLICY, CURLCLOSEPOLICY_LEAST_RECENTLY_USED);5��