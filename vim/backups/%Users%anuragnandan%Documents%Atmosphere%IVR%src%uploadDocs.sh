Vim�UnDo� ҠF����z.��t��{�
�å�<K-G      #  php artisan s3:upload -F $f -B $1            %       %   %   %    ZU�    _�                             ����                                                                                                                                                                                                                                                                                                                                                             ZE,     �                   5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             ZE-     �                 �             �                   5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZEU     �               	  echo ""5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZE^    �                 echo "Porcessing $f file.."5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZEk     �                  #!/bin/bash       FILES=public/documentation/*   for f in $FILES   do     echo "Porcessing $f file.."     cat $f   done5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZEl    �                  cat $f5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             ZEp     �                  #!/bin/bash       FILES=public/documentation/*   for f in $FILES   do     echo "Porcessing $f file.."   done5�_�      	                      ����                                                                                                                                                                                                                                                                                                                                                             ZEr    �                 �             5�_�      
           	          ����                                                                                                                                                                                                                                                                                                                                                             ZE�     �                  #!/bin/bash       FILES=public/documentation/*   for f in $FILES   do     echo "Porcessing $f file.."     php artisan    done5�_�   	              
          ����                                                                                                                                                                                                                                                                                                                                                             ZE�    �                 php artisan 5�_�   
                    
    ����                                                                                                                                                                                                                                                                                                                                                             ZE�     �                  #!/bin/bash       FILES=public/documentation/*   for f in $FILES   do     echo "Porcessing $f file.."   #  php artisan s3:upload -f $f -b $0   done5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZE�     �               #  php artisan s3:upload -f $f -b $05�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZE�    �               #  php artisan s3:upload -F $f -b $05�_�                            ����                                                                                                                                                                                                                                                                                                                                                             ZF>     �                  #!/bin/bash       FILES=public/documentation/*   for f in $FILES   do     echo "Porcessing $f file.."   #  php artisan s3:upload -F $f -B $0   done5�_�                       "    ����                                                                                                                                                                                                                                                                                                                                                             ZF@    �               #  php artisan s3:upload -F $f -B $05�_�                       "    ����                                                                                                                                                                                                                                                                                                                                                             ZG     �                  #!/bin/bash       FILES=public/documentation/*   for f in $FILES   do     echo "Porcessing $f file.."   #  php artisan s3:upload -F $f -B $1   done5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZG     �         	       �             5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZG     �         
      if[]5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZG     �         
      if[]5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZG     �         
      if []5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZG     �                 �             �         
      if [ $# -eq 0 ]5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZG2     �                 die ""5�_�                          ����                                                                                                                                                                                                                                                                                                                                                             ZG^    �               	  echo ""5�_�                       4    ����                                                                                                                                                                                                                                                                                                                                                             ZGi    �                 �             5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZGn   
 �               	  exit 0;5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZH      �               if [ $# -eq 0 ]5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZH!     �               if [ ""$# -eq 0 ]5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZH"    �               if [ "$# -eq 0 ]5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZU     �                  #!/bin/bash       if [ "$#" -eq 0 ]   then   6  echo "Requires a documentation bucket to upload to."     exit 0   fi       FILES=public/documentation/*   for f in $FILES   do     echo "Porcessing $f file.."   #  php artisan s3:upload -F $f -B $1   done5�_�                             ����                                                                                                                                                                                                                                                                                                                                                             ZU�     �      
          �      	       5�_�      !               	   4    ����                                                                                                                                                                                                                                                                                                                                                             ZU�    �   	             �   	          5�_�       "           !          ����                                                                                                                                                                                                                                                                                                                                                             ZU�     �                  #!/bin/bash       if [ "$#" -eq 0 ]   then   6  echo "Requires a documentation bucket to upload to."     exit 0   fi       5DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )   	echo $DIR   exit 0       FILES=public/documentation/*   for f in $FILES   do     echo "Porcessing $f file.."   #  php artisan s3:upload -F $f -B $1   done5�_�   !   #           "   
        ����                                                                                                                                                                                                                                                                                                                                                             ZU�     �   	   
          	echo $DIR5�_�   "   $           #   
        ����                                                                                                                                                                                                                                                                                                                                                             ZU�     �   	   
          exit 05�_�   #   %           $          ����                                                                                                                                                                                                                                                                                                                                                             ZU�    �   
            FILES=public/documentation/*5�_�   $               %          ����                                                                                                                                                                                                                                                                                                                                                             ZU�    �               #  php artisan s3:upload -F $f -B $15�_�                           ����                                                                                                                                                                                                                                                                                                                                                             ZG5     �               '  echo "Requires a bucket to upload to"5��