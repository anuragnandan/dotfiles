Vim�UnDo� ��H[����"7��۳M�<�P�N^	�3s��      $cap deploy package=./frontend.tar.gz                             YRԊ    _�                             ����                                                                                                                                                                                                                                                                                                                                                             YR�\     �                  #!/usr/bin/env bash       if [ $# -eq 0 ]     then        echo "No arguments supplied"   
    exit 1   fi       DIST_DIR=dist   rm -rf ./${DIST_DIR}       Jecho "module.exports = { API_BASE_URL: '\"$1\"' }" > 'config/local.env.js'       npm install   npm run build       %tar -czvf frontend.tar.gz ${DIST_DIR}5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             YR�]     �                  �               5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 &cap deploy package=tmp/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 &cap deploy package=${}/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 &cap deploy package=${}/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��    �                 .cap deploy package=${DIST_DIR}/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR�w     �                  #!/usr/bin/env bash       if [ $# -eq 0 ]     then        echo "No arguments supplied"   
    exit 1   fi       DIST_DIR=dist   rm -rf ./${DIST_DIR}       Jecho "module.exports = { API_BASE_URL: '\"$1\"' }" > 'config/local.env.js'       npm install   npm run build       %tar -czvf frontend.tar.gz ${DIST_DIR}       bundle install   0cap deploy package=./${DIST_DIR}/frontend.tar.gz5�_�      	                     ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 0cap deploy package=./${DIST_DIR}/frontend.tar.gz5�_�      
           	          ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 /cap deploy package=./{DIST_DIR}/frontend.tar.gz5�_�   	              
          ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 .cap deploy package=./DIST_DIR}/frontend.tar.gz5�_�   
                        ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 -cap deploy package=./IST_DIR}/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 ,cap deploy package=./ST_DIR}/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 +cap deploy package=./T_DIR}/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 *cap deploy package=./_DIR}/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 )cap deploy package=./DIR}/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 (cap deploy package=./IR}/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 'cap deploy package=./R}/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��     �                 &cap deploy package=./}/frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR��    �                 %cap deploy package=.//frontend.tar.gz5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             YR�,     �   	             �   	          5�_�                       
    ����                                                                                                                                                                                                                                                                                                                                                             YR�A     �               %tar -czvf frontend.tar.gz ${DIST_DIR}5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR�F     �               (tar -czvf ${}frontend.tar.gz ${DIST_DIR}5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR�H    �               /tar -czvf ${TMP_DIR}frontend.tar.gz ${DIST_DIR}5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YR�}     �                  #!/usr/bin/env bash       if [ $# -eq 0 ]     then        echo "No arguments supplied"   
    exit 1   fi       DIST_DIR=dist   TMP_DIR=tmp   rm -rf ./${DIST_DIR}       Jecho "module.exports = { API_BASE_URL: '\"$1\"' }" > 'config/local.env.js'       npm install   npm run build       0tar -czvf ${TMP_DIR}/frontend.tar.gz ${DIST_DIR}       bundle install   $cap deploy package=./frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YRԄ     �                 $cap deploy package=./frontend.tar.gz5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             YRԆ     �                 'cap deploy package=./${}frontend.tar.gz5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             YRԉ    �                 .cap deploy package=./${TMP_DIR}frontend.tar.gz5��