Vim�UnDo� ;��9ƓV�ɥ;�)�A��<y�°��c�>�   %   N         "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",      L      :       :   :   :    V���   
 _�                             ����                                                                                                                                                                                                                                                                                                                                                             V��r     �                   5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             V��r     �                   5�_�      #                     ����                                                                                                                                                                                                                                                                                                                                                             V��t     �                  <>5�_�      $          #           ����                                                                                                                                                                                                                                                                                                                                                             V�܉    �      $          5�_�   #   %           $   #       ����                                                                                                                                                                                                                                                                                                                                                             V�ܪ     �               %   <?php    ini_set('display_errors', 'On');     error_reporting(E_ALL);          '  $post_data = http_build_query(array (         "color" => "red",   !      "message_format" => "text",   >      "message" => "Another test!!! This is Hobbit Alert test"     ));          $headers = array (   )    'Content-type' => 'application/json',   H    'Authorization' => 'Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J'     );           $apiCallOpts = array('http' =>         array(             'method'  => 'POST',   9         // 'header' => 'Content-type: application/json',   ;         'header'  => "Content-type: application/json\r\n".   U                "Authorization: Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J\r\n",         'content' => $post_data         )     );        3  // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);   3  // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);        i  //$apiCallOpts['http']['header']' = ("Authorization: Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J");        :  $contextHipChat  = stream_context_create( $apiCallOpts);       t  $resultUCS = file_get_contents("https://api.hipchat.com/v2/room/Operations/notification", false, $contextHipChat);          echo "$resultUCS";       ?>5�_�   $   &           %      '    ����                                                                                                                                                                                                                                                                                                                                                             V�ܲ     �         %      U                "Authorization: Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J\r\n",5�_�   %   '           &          ����                                                                                                                                                                                                                                                                                                                                                             V�ܹ    �         %      H    'Authorization' => 'Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J'5�_�   &   (           '      F    ����                                                                                                                                                                                                                                                                                                                                                             V���     �               %   <?php    ini_set('display_errors', 'On');     error_reporting(E_ALL);          '  $post_data = http_build_query(array (         "color" => "red",   !      "message_format" => "text",   >      "message" => "Another test!!! This is Hobbit Alert test"     ));          $headers = array (   )    'Content-type' => 'application/json',   H    'Authorization' => 'Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N'     );           $apiCallOpts = array('http' =>         array(             'method'  => 'POST',   9         // 'header' => 'Content-type: application/json',   ;         'header'  => "Content-type: application/json\r\n".   U                "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",         'content' => $post_data         )     );        3  // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);   3  // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);        i  //$apiCallOpts['http']['header']' = ("Authorization: Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J");        :  $contextHipChat  = stream_context_create( $apiCallOpts);       t  $resultUCS = file_get_contents("https://api.hipchat.com/v2/room/Operations/notification", false, $contextHipChat);          echo "$resultUCS";       ?>5�_�   '   )           (   !   <    ����                                                                                                                                                                                                                                                                                                                                                             V��L     �       "   %      t  $resultUCS = file_get_contents("https://api.hipchat.com/v2/room/Operations/notification", false, $contextHipChat);5�_�   (   *           )   !   @    ����                                                                                                                                                                                                                                                                                                                                                             V��M     �       "   %      t  $resultUCS = file_get_contents("https://api.hipchat.com/v1/room/Operations/notification", false, $contextHipChat);5�_�   )   +           *   !   E    ����                                                                                                                                                                                                                                                                                                                                                             V��M     �       "   %      t  $resultUCS = file_get_contents("https://api.hipchat.com/v1/room/Operations/notification", false, $contextHipChat);5�_�   *   ,           +   !   P    ����                                                                                                                                                                                                                                                                                                                                                             V��N     �       "   %      t  $resultUCS = file_get_contents("https://api.hipchat.com/v1/room/Operations/notification", false, $contextHipChat);5�_�   +   -           ,   !   Y    ����                                                                                                                                                                                                                                                                                                                                                             V��P    �       "   %      t  $resultUCS = file_get_contents("https://api.hipchat.com/v1/room/Operations/notification", false, $contextHipChat);5�_�   ,   .           -   !   S    ����                                                                                                                                                                                                                                                                                                                                                             V��^     �               %   <?php    ini_set('display_errors', 'On');     error_reporting(E_ALL);          '  $post_data = http_build_query(array (         "color" => "red",   !      "message_format" => "text",   >      "message" => "Another test!!! This is Hobbit Alert test"     ));          $headers = array (   )    'Content-type' => 'application/json',   H    'Authorization' => 'Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N'     );           $apiCallOpts = array('http' =>         array(             'method'  => 'POST',   9         // 'header' => 'Content-type: application/json',   ;         'header'  => "Content-type: application/json\r\n".   U                "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",         'content' => $post_data         )     );        3  // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);   3  // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);        i  //$apiCallOpts['http']['header']' = ("Authorization: Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J");        :  $contextHipChat  = stream_context_create( $apiCallOpts);       o  $resultUCS = file_get_contents("https://api.hipchat.com/v1/room/Operations/message", false, $contextHipChat);          echo "$resultUCS";       ?>5�_�   -   /           .   !   ;    ����                                                                                                                                                                                                                                                                                                                                                             V��b    �       "   %      o  $resultUCS = file_get_contents("https://api.hipchat.com/v1/room/Operations/message", false, $contextHipChat);5�_�   .   0           /   !   ;    ����                                                                                                                                                                                                                                                                                                                                                             V��h     �               %   <?php    ini_set('display_errors', 'On');     error_reporting(E_ALL);          '  $post_data = http_build_query(array (         "color" => "red",   !      "message_format" => "text",   >      "message" => "Another test!!! This is Hobbit Alert test"     ));          $headers = array (   )    'Content-type' => 'application/json',   H    'Authorization' => 'Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N'     );           $apiCallOpts = array('http' =>         array(             'method'  => 'POST',   9         // 'header' => 'Content-type: application/json',   ;         'header'  => "Content-type: application/json\r\n".   U                "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",         'content' => $post_data         )     );        3  // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);   3  // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);        i  //$apiCallOpts['http']['header']' = ("Authorization: Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J");        :  $contextHipChat  = stream_context_create( $apiCallOpts);       o  $resultUCS = file_get_contents("https://api.hipchat.com/v2/room/Operations/message", false, $contextHipChat);          echo "$resultUCS";       ?>5�_�   /   1           0      5    ����                                                                                                                                                                                                                                                                                                                                                             V�ݨ    �         %      ;         'header'  => "Content-type: application/json\r\n".5�_�   0   2           1      E    ����                                                                                                                                                                                                                                                                                                                                                             V�ݼ     �               %   <?php    ini_set('display_errors', 'On');     error_reporting(E_ALL);          '  $post_data = http_build_query(array (         "color" => "red",   !      "message_format" => "text",   >      "message" => "Another test!!! This is Hobbit Alert test"     ));          $headers = array (   )    'Content-type' => 'application/json',   H    'Authorization' => 'Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N'     );           $apiCallOpts = array('http' =>         array(             'method'  => 'POST',   9         // 'header' => 'Content-type: application/json',   L         'header'  => "Content-type: application/x-www-form-urlencoded\r\n".   U                "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",         'content' => $post_data         )     );        3  // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);   3  // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);        i  //$apiCallOpts['http']['header']' = ("Authorization: Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J");        :  $contextHipChat  = stream_context_create( $apiCallOpts);       o  $resultUCS = file_get_contents("https://api.hipchat.com/v2/room/Operations/message", false, $contextHipChat);          echo "$resultUCS";       ?>5�_�   1   3           2          ����                                                                                                                                                                                                                                                                                                                                                             V���     �         %      U                "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",5�_�   2   4           3          ����                                                                                                                                                                                                                                                                                                                                                             V���     �         %      T               "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",5�_�   3   5           4          ����                                                                                                                                                                                                                                                                                                                                                             V���     �         %      S              "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",5�_�   4   6           5          ����                                                                                                                                                                                                                                                                                                                                                             V���     �         %      R             "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",5�_�   5   7           6          ����                                                                                                                                                                                                                                                                                                                                                             V���     �         %      Q            "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",5�_�   6   8           7          ����                                                                                                                                                                                                                                                                                                                                                             V���     �         %      P           "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",5�_�   7   9           8          ����                                                                                                                                                                                                                                                                                                                                                             V���     �         %      O          "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",5�_�   8   :           9      J    ����                                                                                                                                                                                                                                                                                                                                                             V���     �         %      L         'header'  => "Content-type: application/x-www-form-urlencoded\r\n".5�_�   9               :      L    ����                                                                                                                                                                                                                                                                                                                                                             V���   
 �         %      N         "Authorization: Bearer sOL31pkhWcTaYnIdkxBDqVlD7z6q7zv2FqGczx5N\r\n",5�_�             #              ����                                                                                                                                                                                                                                                                                                                                                             V��w     �               	ini_set()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��w     �               ini_set('')5�_�                       	    ����                                                                                                                                                                                                                                                                                                                                                             V��w     �               ini_set('display_errors', '')5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��w     �                ini_set('display_errors', 'On');     error_reporting()5�_�      	                     ����                                                                                                                                                                                                                                                                                                                                                             V��w     �                 error_reporting(E_ALL);          !  $post_data = http_build_query()5�_�      
           	           ����                                                                                                                                                                                                                                                                                                                                                             V��x     �               )  $post_data = http_build_query(array ())5�_�   	              
      '    ����                                                                                                                                                                                                                                                                                                                                                             V��x     �               '  $post_data = http_build_query(array (           ""))5�_�   
                    	    ����                                                                                                                                                                                                                                                                                                                                                             V��x     �                       "color" => ""))5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��x     �                       "color" => "red",               ""))5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��x     �         	      $            "message_format" => ""))5�_�                       !    ����                                                                                                                                                                                                                                                                                                                                                             V��x     �         	      '            "message_format" => "text",                   ""))5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��x     �      	   
      !                "message" => ""))5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��x     �      	   
      H                "message" => "Another test!!! This is Hobbit Alert test"                   ));              $headers = array ()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��y     �   
                $headers = array (           '')5�_�                       	    ����                                                                                                                                                                                                                                                                                                                                                             V��y     �                       'Content-type' => '')5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��y     �               -        'Content-type' => 'application/json',             '')5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��y     �                          'Authorization' => '')5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��y     �               N          'Authorization' => 'Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J'             );              $apiCallOpts = array()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��y     �                   $apiCallOpts = array('')5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��y     �               "    $apiCallOpts = array('http' =>             array())5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��y     �                         array(                       ''))5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V��y     �               %                    'method'  => ''))5�_�                       "    ����                                                                                                                                                                                                                                                                                                                                                             V��y     �               (                    'method'  => 'POST',   .                           // 'header' => ''))5�_�                       +    ����                                                                                                                                                                                                                                                                                                                                                             V��y     �               K                           // 'header' => 'Content-type: application/json',   0                    //         'header'  => ""))5�_�                       -    ����                                                                                                                                                                                                                                                                                                                                                             V��z     �            
   Q                    //         'header'  => "Content-type: application/json\r\n".   s                    //                        "Authorization: Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J\r\n",   K                    //                              'content' => $post_data   ;                    //                                    )   >                    //                                      );   >                    //                                           q                    //                                          // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);   s                    //                                            // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);   D                    //                                                 W                    //                                                //$apiCallOpts[''5�_�                       V    ����                                                                                                                                                                                                                                                                                                                                                             V��{     �               _                    //                                                //$apiCallOpts['http'][''5�_�                       ^    ����                                                                                                                                                                                                                                                                                                                                                             V��{     �               h                    //                                                //$apiCallOpts['http']['header']''5�_�                        g    ����                                                                                                                                                                                                                                                                                                                                                             V��{     �               m                    //                                                //$apiCallOpts['http']['header']' = ()'5�_�      !                  k    ����                                                                                                                                                                                                                                                                                                                                                             V��{     �               o                    //                                                //$apiCallOpts['http']['header']' = ("")'5�_�       "           !      l    ����                                                                                                                                                                                                                                                                                                                                                             V��{     �               �                    //                                                //$apiCallOpts['http']['header']' = ("Authorization: Bearer q2I5csRmcvUId6W90SUPAMMjC3NDsFmmL5wdvv7J");   H                    //                                                     �                    //                                                    $contextHipChat  = stream_context_create( $apiCallOpts);                       //   n                    //                                                      $resultUCS = file_get_contents(""'5�_�   !               "   !   l    ����                                                                                                                                                                                                                                                                                                                                                             V��{     �       "   #      �                    //                                                      $resultUCS = file_get_contents("https://api.hipchat.com/v2/room/Operations/notification", false, $contextHipChat);   N                    //                                                           c                    //                                                          echo "$resultUCS";'5��