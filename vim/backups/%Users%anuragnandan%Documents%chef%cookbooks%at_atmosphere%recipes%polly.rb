Vim�UnDo� +c��'�К3��-\2��lS���t�@R                    L       L   L   L    X��    _�                             ����                                                                                                                                                                                                                                                                                                                                                             X���     �                   5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X���     �                  install_dir5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X���     �                  install_dir = node[]5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X���     �                  install_dir = node['']5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��     �                   install_dir = node['atmosphere']5�_�                       #    ����                                                                                                                                                                                                                                                                                                                                                             X��     �                  #install_dir = node['at_atmosphere']5�_�                       $    ����                                                                                                                                                                                                                                                                                                                                                             X��	     �                  %install_dir = node['at_atmosphere'][]5�_�      	                 %    ����                                                                                                                                                                                                                                                                                                                                                             X��     �                  'install_dir = node['at_atmosphere']['']5�_�      
           	      2    ����                                                                                                                                                                                                                                                                                                                                                             X��2     �                  �               5�_�   	              
          ����                                                                                                                                                                                                                                                                                                                                                             X��>     �                 directory ""5�_�   
                        ����                                                                                                                                                                                                                                                                                                                                                             X��@     �                 directory "#{}"5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��B     �                 directory "#{}"5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��E     �                 directory "#{install_dir}"5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             X��J     �                 �             �                 directory "#{install_dir}"    �                  5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��S     �               	  mode ''5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��s     �                 �             5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��     �                 �             5�_�                       
    ����                                                                                                                                                                                                                                                                                                                                                             X���     �                 not_if {}5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X���     �                 not_if { ::Dir.exist? ""}5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X���     �                 not_if { ::Dir.exist? "#{}"}5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X���     �                 not_if { ::Dir.exist? "#{}"}5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��     �                5install_dir = node['at_atmosphere']['polly_lib_path']5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��     �                4install_dir = node['t_atmosphere']['polly_lib_path']5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��     �                3install_dir = node['_atmosphere']['polly_lib_path']5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��     �                2install_dir = node['atmosphere']['polly_lib_path']5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��     �                  �               5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             X���     �                 include_recipe ''5�_�                   	   &    ����                                                                                                                                                                                                                                                                                                                                                             X��d     �   	               �   	            5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             X��e     �   
               �               5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             X��m     �   
            bash 'install_composer' do5�_�      !                      ����                                                                                                                                                                                                                                                                                                                                                             X��    �   
            bash 'install_aws' do5�_�       "           !          ����                                                                                                                                                                                                                                                                                                                                                             X��Z     �   
             �   
          5�_�   !   #           "          ����                                                                                                                                                                                                                                                                                                                                                             X��c     �   
            git ''5�_�   "   $           #           ����                                                                                                                                                                                                                                                                                                                                                             X��     �   
             remote_file ''5�_�   #   %           $           ����                                                                                                                                                                                                                                                                                                                                                             X��     �   
             �   
          5�_�   $   &           %          ����                                                                                                                                                                                                                                                                                                                                                             X��     �   
            git ''5�_�   %   '           &          ����                                                                                                                                                                                                                                                                                                                                                             X��     �   
            git ''5�_�   &   (           '          ����                                                                                                                                                                                                                                                                                                                                                             X��     �   
            git ""5�_�   '   )           (          ����                                                                                                                                                                                                                                                                                                                                                             X��     �   
            	git "#{}"5�_�   (   *           )          ����                                                                                                                                                                                                                                                                                                                                                             X��     �   
            	git "#{}"5�_�   )   +           *          ����                                                                                                                                                                                                                                                                                                                                                             X��     �                 �             �   
            git "#{install_dir}"5�_�   *   ,           +          ����                                                                                                                                                                                                                                                                                                                                                             X��     �                 repository ''5�_�   +   -           ,          ����                                                                                                                                                                                                                                                                                                                                                             X��     �               R  repository 'git@bop6gitlab01.ftl.prosodieinteractive.local:atmosphere/Polly.git'5�_�   ,   .           -          ����                                                                                                                                                                                                                                                                                                                                                             X��     �                 repository '5�_�   -   /           .          ����                                                                                                                                                                                                                                                                                                                                                             X��      �                 repository ''5�_�   .   0           /          ����                                                                                                                                                                                                                                                                                                                                                             X��*     �                 �             5�_�   /   1           0      
    ����                                                                                                                                                                                                                                                                                                                                                             X��O     �                 not_if {}5�_�   0   2           1          ����                                                                                                                                                                                                                                                                                                                                                             X��V     �                 not_if { ::File.exist ''}5�_�   1   3           2          ����                                                                                                                                                                                                                                                                                                                                                             X��X     �                 not_if { ::File.exist? ''}5�_�   2   4           3          ����                                                                                                                                                                                                                                                                                                                                                             X��[     �                 not_if { ::File.exist? '' }5�_�   3   5           4          ����                                                                                                                                                                                                                                                                                                                                                             X��]     �                  not_if { ::File.exist? '#{}' }5�_�   4   6           5          ����                                                                                                                                                                                                                                                                                                                                                             X��a     �                 not_if { ::File.exist? "" }5�_�   5   7           6          ����                                                                                                                                                                                                                                                                                                                                                             X��a     �                  not_if { ::File.exist? "#{}" }5�_�   6   8           7          ����                                                                                                                                                                                                                                                                                                                                                             X��b     �                  not_if { ::File.exist? "#{}" }5�_�   7   9           8      )    ����                                                                                                                                                                                                                                                                                                                                                             X��i     �               +  not_if { ::File.exist? "#{install_dir}" }5�_�   8   :           9      (    ����                                                                                                                                                                                                                                                                                                                                                             X��k     �               +  not_if { ::File.exist? "#{install_dir}" }5�_�   9   ;           :          ����                                                                                                                                                                                                                                                                                                                                                             X��     �             5�_�   :   <           ;          ����                                                                                                                                                                                                                                                                                                                                                             X��     �               bash 'install_aws_library' do5�_�   ;   =           <          ����                                                                                                                                                                                                                                                                                                                                                             X��     �                 cd /usr/local/bin5�_�   <   >           =          ����                                                                                                                                                                                                                                                                                                                                                             X��     �                 cd 5�_�   =   ?           >          ����                                                                                                                                                                                                                                                                                                                                                             X��     �                 cd #{}5�_�   >   @           ?          ����                                                                                                                                                                                                                                                                                                                                                             X��     �                2  curl -sS https://getcomposer.org/installer | php5�_�   ?   A           @          ����                                                                                                                                                                                                                                                                                                                                                             X��     �                '  chmod +x /usr/local/bin/composer.phar5�_�   @   B           A          ����                                                                                                                                                                                                                                                                                                                                                             X��     �                9  mv /usr/local/bin/composer.phar /usr/local/bin/composer5�_�   A   C           B          ����                                                                                                                                                                                                                                                                                                                                                             X��    �                 �             5�_�   B   D           C          ����                                                                                                                                                                                                                                                                                                                                                             X��     �                 action: export5�_�   C   E           D          ����                                                                                                                                                                                                                                                                                                                                                             X��    �                 action : export5�_�   D   F           E          ����                                                                                                                                                                                                                                                                                                                                                             X���     �               4  not_if { ::File.exist? '/usr/local/bin/composer' }5�_�   E   G           F          ����                                                                                                                                                                                                                                                                                                                                                             X���     �                 not_if { ::File.exist? 5�_�   F   H           G          ����                                                                                                                                                                                                                                                                                                                                                             X���     �                 not_if { ::File.exist? "" 5�_�   G   I           H          ����                                                                                                                                                                                                                                                                                                                                                             X���     �                 not_if { ::File.exist? "#{}" 5�_�   H   J           I      (    ����                                                                                                                                                                                                                                                                                                                                                             X���     �               *  not_if { ::File.exist? "#{install_dir}" 5�_�   I   K           J      7    ����                                                                                                                                                                                                                                                                                                                                                             X���     �               8  not_if { ::File.exist? "#{install_dir}/composer.lock" 5�_�   J   L           K      :    ����                                                                                                                                                                                                                                                                                                                                                             X���    �               :  not_if { ::File.exist? "#{install_dir}/composer.lock" } 5�_�   K               L           ����                                                                                                                                                                                                                                                                                                                                                             X��     �                  :install_dir = node['atmosphere_general']['polly_lib_path']       directory "#{install_dir}" do     mode '0755'     recursive true   )  not_if { ::Dir.exist? "#{install_dir}"}   end       (include_recipe 'at_atmosphere::composer'       git "#{install_dir}" do   U  repository 'http://bop6gitlab01.ftl.prosodieinteractive.local/atmosphere/Polly.git'     action :export   5  not_if { ::File.exist? "#{install_dir}/Polly.php" }   end       bash 'install_aws_library' do     code <<-EOF     cd #{install_dir}     composer install     EOF   9  not_if { ::File.exist? "#{install_dir}/composer.lock" }   end5�_�                    	   &    ����                                                                                                                                                                                                                                                                                                                                                             X���     �   	   
           �   	              �                -include_recipe 'at_atmosphere::composer',tdef    5��