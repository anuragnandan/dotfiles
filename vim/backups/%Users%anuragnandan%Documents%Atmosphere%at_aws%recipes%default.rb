Vim�UnDo� #%�'[Z�ۭ�~�aF
#�{�6#�M:�q���                                      X��    _�                             ����                                                                                                                                                                                                                                                                                                                                                 v        X��     �                     :install_dir = node['atmosphere_general']['polly_lib_path']       directory install_dir.to_s do     mode '0755'     recursive true   *  not_if { ::Dir.exist? install_dir.to_s }   end       (include_recipe 'at_atmosphere::composer'       git install_dir.to_s do   U  repository 'http://bop6gitlab01.ftl.prosodieinteractive.local/atmosphere/Polly.git'     action :export   ;  not_if { ::File.exist? "#{install_dir}/Polly/Polly.php" }   end       bash 'install_aws_library' do     code <<-EOF     cd #{install_dir}/Polly     composer install     EOF   ?  not_if { ::File.exist? "#{install_dir}/Polly/composer.lock" }   end5�_�                             ����                                                                                                                                                                                                                                                                                                                                                  v        X��    �                 5��