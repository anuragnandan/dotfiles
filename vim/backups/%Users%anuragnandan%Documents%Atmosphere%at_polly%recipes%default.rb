Vim�UnDo� {�> �'�.�4�`����;X�y%�R�q=�      ;  not_if { ::File.exist? "#{install_dir}/Polly/Polly.php" }      '                       Y=�    _�                             ����                                                                                                                                                                                                                                                                                                                                                             X���     �               �               5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X���    �                 !include_recipe 'at_aws::composer'5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             X��     �               5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             X��     �                  �               5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             X��    �                include_recipe 'at_aws::polly'5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             X��    �             5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             X��1     �                  !include_recipe 'at_aws::composer'       :install_dir = node['atmosphere_general']['polly_lib_path']       directory install_dir.to_s do     mode '0755'     recursive true   *  not_if { ::Dir.exist? install_dir.to_s }   end       git install_dir.to_s do   U  repository 'http://bop6gitlab01.ftl.prosodieinteractive.local/atmosphere/Polly.git'     action :export   ;  not_if { ::File.exist? "#{install_dir}/Polly/Polly.php" }   end       bash 'install_aws_library' do     code <<-EOF     cd #{install_dir}/Polly     composer install     EOF   ?  not_if { ::File.exist? "#{install_dir}/Polly/composer.lock" }   end5�_�      	                     ����                                                                                                                                                                                                                                                                                                                                                             X��7    �                !include_recipe 'at_aws::composer'5�_�      
           	          ����                                                                                                                                                                                                                                                                                                                                                             Ys�    �                #include_recipe 'at_polly::composer'5�_�   	              
           ����                                                                                                                                                                                                                                                                                                                                                             Y5�     �                  (include_recipe 'at_atmosphere::composer'       :install_dir = node['atmosphere_general']['polly_lib_path']       directory install_dir.to_s do     mode '0755'     recursive true   *  not_if { ::Dir.exist? install_dir.to_s }   end       git install_dir.to_s do   U  repository 'http://bop6gitlab01.ftl.prosodieinteractive.local/atmosphere/Polly.git'     action :export   ;  not_if { ::File.exist? "#{install_dir}/Polly/Polly.php" }   end       bash 'install_aws_library' do     code <<-EOF     cd #{install_dir}/Polly     composer install     EOF   ?  not_if { ::File.exist? "#{install_dir}/Polly/composer.lock" }   end5�_�   
                         ����                                                                                                                                                                                                                                                                                                                                                             Y5�     �             �             5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             Y5�   	 �                 (include_recipe 'at_atmosphere::composer'5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             Y=&     �                  (include_recipe 'at_atmosphere::composer'       :install_dir = node['atmosphere_general']['polly_lib_path']       directory install_dir.to_s do     mode '0755'     recursive true   *  not_if { ::Dir.exist? install_dir.to_s }   end       git install_dir.to_s do   U  repository 'http://bop6gitlab01.ftl.prosodieinteractive.local/atmosphere/Polly.git'     action :export   ;  not_if { ::File.exist? "#{install_dir}/Polly/Polly.php" }   end       bash 'install_aws_library' do     code <<-EOF     cd #{install_dir}/Polly     composer install     EOF   ?  not_if { ::File.exist? "#{install_dir}/Polly/composer.lock" }   end5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Y=;     �   
            git install_dir.to_s do5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Y=C     �   
            git "#{install_dir.to_s do5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Y=G     �   
            git "#{install_dir.to_s}" do5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Y=   
 �                 cd #{install_dir}/Polly5�_�                       '    ����                                                                                                                                                                                                                                                                                                                                                             Y=�    �               ?  not_if { ::File.exist? "#{install_dir}/Polly/composer.lock" }5�_�                        '    ����                                                                                                                                                                                                                                                                                                                                                             Y=�    �               ;  not_if { ::File.exist? "#{install_dir}/Polly/Polly.php" }5��