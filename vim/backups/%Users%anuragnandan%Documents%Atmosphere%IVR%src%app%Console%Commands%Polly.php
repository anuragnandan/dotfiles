Vim�UnDo� �QX�I���E����˪� �����]?���<   F   %          'region'    => 'us-east-1',                            Y�-8   	 _�                     )        ����                                                                                                                                                                                                                                                                                                                                                             Y�,     �               R   <?php   'require __DIR__.'/vendor/autoload.php';       class Polly   {         private $client;     private $debug_msg;       )  function __construct($params = array())     {       $this->debug_msg = "";       try       {         if(count($params) == 0)         {   6        if(file_exists('/etc/atmosphere/general.ini'))   	        {   K          $ini_params = parse_ini_file('/etc/atmosphere/general.ini',true);   e          if(!isset($ini_params['AWS']['VARAWS_key']) || !isset($ini_params['AWS']['VARAWS_secret']))   g            throw new Exception("VARAWS_key and VARAWS_secret not set in /etc/atmosphere/general.ini");                 $params = ([             'credentials' => [   >              'key'       => $ini_params['AWS']['VARAWS_key'],   @              'secret'    => $ini_params['AWS']['VARAWS_secret']               ],   %          'region'    => 'us-east-1',   "          'version'   => 'latest',             ]);   	        }           else   	        {   P          throw new Exception("File /etc/atmosphere/general.ini doesnt exist.");   	        }         }   ,      elseif(!isset($params['credentials']))   H        throw new Exception("No credentials been set for the request.");       7    $this->client = new Aws\Polly\PollyClient($params);           }       catch( Exception $e )       {   *      $this->debug_msg = $e->getMessage();         return false;       }     }         /*   ,   * method that converts the file from TTS.   8   * reuires an $input array with requred keys as below    %   *     'OutputFormat' => 'mp3|pcm',   4   *     'Text' => 'testing tts on polly by anurag',      *     'VoiceId' => 'Geraint'   :   * requires $path_to_save for putting the tts as a file.      */       *  function get_file($input, $path_to_save)     {       try       {   8      $result = $this->client->synthesizeSpeech($input);   S      file_put_contents($path_to_save, $result->get('AudioStream')->getContents());   %      if(!file_exists($path_to_save))   C        throw new Exception("Failed to create file $path_to_save");       }       catch( Exception $e)       {   *      $this->debug_msg = $e->getMessage();         return false;       }       return true;     }         function get_debug_message()     {       return $this->debug_msg;     }   }       ?>5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             Y�,+    �                'require __DIR__.'/vendor/autoload.php';5�_�                           ����                                                                                                                                                                                                                                                                                                                                      "          v       Y�,R     �         Q      	        }           else   	        {   P          throw new Exception("File /etc/atmosphere/general.ini doesnt exist.");   	        }5�_�                           ����                                                                                                                                                                                                                                                                                                                                                v       Y�,T     �                        5�_�                           ����                                                                                                                                                                                                                                                                                                                                                v       Y�,W     �                6        if(file_exists('/etc/atmosphere/general.ini'))5�_�                           ����                                                                                                                                                                                                                                                                                                                                                v       Y�,X     �                	        {5�_�                       
    ����                                                                                                                                                                                                                                                                                                                                                v       Y�,d     �                K          $ini_params = parse_ini_file('/etc/atmosphere/general.ini',true);5�_�      	                 
    ����                                                                                                                                                                                                                                                                                                                                                v       Y�,d     �                e          if(!isset($ini_params['AWS']['VARAWS_key']) || !isset($ini_params['AWS']['VARAWS_secret']))5�_�      
           	          ����                                                                                                                                                                                                                                                                                                                                                v       Y�,e     �                g            throw new Exception("VARAWS_key and VARAWS_secret not set in /etc/atmosphere/general.ini");5�_�   	              
           ����                                                                                                                                                                                                                                                                                                                                                v       Y�,f     �                 5�_�   
                        ����                                                                                                                                                                                                                                                                                                                                                v       Y�,i     �         F      >              'key'       => $ini_params['AWS']['VARAWS_key'],5�_�                           ����                                                                                                                                                                                                                                                                                                                                                v       Y�,k     �         F                    'key'       => $5�_�                       !    ����                                                                                                                                                                                                                                                                                                                                                v       Y�,m     �         F      "              'key'       => env()5�_�                       "    ����                                                                                                                                                                                                                                                                                                                                                v       Y�,s     �         F      $              'key'       => env('')5�_�                           ����                                                                                                                                                                                                                                                                                                                                                v       Y�,x     �         F      @              'secret'    => $ini_params['AWS']['VARAWS_secret']5�_�                           ����                                                                                                                                                                                                                                                                                                                                                v       Y�,z     �         F                    'secret'    => 5�_�                       !    ����                                                                                                                                                                                                                                                                                                                                                v       Y�,{     �         F      "              'secret'    => env()5�_�                       "    ����                                                                                                                                                                                                                                                                                                                                                v       Y�,}     �         F      $              'secret'    => env('')5�_�                       +    ����                                                                                                                                                                                                                                                                                                                                                v       Y�,�    �         F      +              'key'       => env('AWS_KEY')5�_�                       *    ����                                                                                                                                                                                                                                                                                                                                                v       Y�,�     �         F      ,              'key'       => env('AWS_KEY'),5�_�                       -    ����                                                                                                                                                                                                                                                                                                                                                v       Y�,�   	 �         F      .              'secret'    => env('AWS_SECRET')5�_�                    1       ����                                                                                                                                                                                                                                                                                                                                                             Y�-8     �       G       F   <?php       class Polly   {         private $client;     private $debug_msg;       )  function __construct($params = array())     {       $this->debug_msg = "";       try       {         if(count($params) == 0)         {             $params = ([             'credentials' => [   3              'key'       => env('AWS_KEY', false),   5              'secret'    => env('AWS_SECRET', false)               ],   %          'region'    => 'us-east-1',   "          'version'   => 'latest',             ]);         }   ,      elseif(!isset($params['credentials']))   H        throw new Exception("No credentials been set for the request.");       7    $this->client = new Aws\Polly\PollyClient($params);           }       catch( Exception $e )       {   *      $this->debug_msg = $e->getMessage();         return false;       }     }         /*   ,   * method that converts the file from TTS.   8   * reuires an $input array with requred keys as below    %   *     'OutputFormat' => 'mp3|pcm',   4   *     'Text' => 'testing tts on polly by anurag',      *     'VoiceId' => 'Geraint'   :   * requires $path_to_save for putting the tts as a file.      */       *  function get_file($input, $path_to_save)     {       try       {   8      $result = $this->client->synthesizeSpeech($input);   S      file_put_contents($path_to_save, $result->get('AudioStream')->getContents());   %      if(!file_exists($path_to_save))   C        throw new Exception("Failed to create file $path_to_save");       }       catch( Exception $e)       {   *      $this->debug_msg = $e->getMessage();         return false;       }       return true;     }         function get_debug_message()     {       return $this->debug_msg;     }   }       ?>5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             Y�-;    �         F       �         G          namespace App\Console\Commands;5�_�                           ����                                                                                                                                                                                                                                                                                                                                         
       v   
    Y���     �         H      *          'region'    => env()'us-east-1',5�_�                           ����                                                                                                                                                                                                                                                                                                                                         
       v   
    Y���     �         H      )          'region'    => env('us-east-1',5�_�                           ����                                                                                                                                                                                                                                                                                                                                         
       v   
    Y���     �         H      5          'region'    => env('AWS_REGION',us-east-1',5�_�                       *    ����                                                                                                                                                                                                                                                                                                                                         
       v   
    Y���     �         H      6          'region'    => env('AWS_REGION','us-east-1',5�_�                       *    ����                                                                                                                                                                                                                                                                                                                                         
       v   
    Y���     �         H      7          'region'    => env('AWS_REGION', 'us-east-1',5�_�                       2    ����                                                                                                                                                                                                                                                                                                                                         
       v   
    Y���     �         H      7          'region'    => env('AWS_REGION', 'us-east-1',5�_�                        6    ����                                                                                                                                                                                                                                                                                                                                         
       v   
    Y���    �         H      8          'region'    => env('AWS_REGION', 'us-east-1'),5��