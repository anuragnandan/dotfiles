Vim�UnDo� *�h9.�[�W�� kF�b��3�'̂��+�     T  echo 'SELECT count(carrier_id) from vicidial_server_carriers where carrier_id = "PREFIX#{node['hostname']}_DOM"' | mysql -u #{node['at_atmosphere_ivr']['VARDB_user']} -p#{node['at_atmosphere_ivr']['VARDB_pass']} -h#{node['at_atmosphere_ivr']['database_hostname']} --skip-column-names  #{node['at_atmosphere_ivr']['database']} | grep '^1$'      _                       W�^    _�                        /    ����                                                                                                                                                                                                                                                                                                                                                             W�-     �               T  command /usr/local/bin/newmachsetup.sh #{node['VARserver_ip']} #{node['hostname']}5�_�                       0    ����                                                                                                                                                                                                                                                                                                                                                             W�.     �               V  command /usr/local/bin/newmachsetup.sh #{node[]['VARserver_ip']} #{node['hostname']}5�_�                       1    ����                                                                                                                                                                                                                                                                                                                                                             W�.     �               X  command /usr/local/bin/newmachsetup.sh #{node['']['VARserver_ip']} #{node['hostname']}5�_�                       1    ����                                                                                                                                                                                                                                                                                                                                                             W�/     �               X  command /usr/local/bin/newmachsetup.sh #{node['']['VARserver_ip']} #{node['hostname']}5�_�                       H    ����                                                                                                                                                                                                                                                                                                                                                             W�2     �               h  command /usr/local/bin/newmachsetup.sh #{node['at_vagrant_setup']['VARserver_ip']} #{node['hostname']}5�_�                       Q    ����                                                                                                                                                                                                                                                                                                                                                             W�3     �               h  command /usr/local/bin/newmachsetup.sh #{node['at_vagrant_setup']['VARserver_ip']} #{node['hostname']}5�_�                       U    ����                                                                                                                                                                                                                                                                                                                                                             W�>     �               b  command /usr/local/bin/newmachsetup.sh #{node['at_vagrant_setup']['ivr_ip']} #{node['hostname']}5�_�      	                 X    ����                                                                                                                                                                                                                                                                                                                                                             W�@     �               e  command /usr/local/bin/newmachsetup.sh #{node['at_vagrant_setup']['ivr_ip']} #{node[''['hostname']}5�_�      
           	      W    ����                                                                                                                                                                                                                                                                                                                                                             W�B     �               f  command /usr/local/bin/newmachsetup.sh #{node['at_vagrant_setup']['ivr_ip']} #{node['']['hostname']}5�_�   	              
      n    ����                                                                                                                                                                                                                                                                                                                                                             W�E     �               v  command /usr/local/bin/newmachsetup.sh #{node['at_vagrant_setup']['ivr_ip']} #{node['at_vagrant_setup']['hostname']}5�_�   
                    s    ����                                                                                                                                                                                                                                                                                                                                                             W�G    �               v  command /usr/local/bin/newmachsetup.sh #{node['at_vagrant_setup']['ivr_ip']} #{node['at_vagrant_setup']['hostname']}5�_�                       _    ����                                                                                                                                                                                                                                                                                                                                                             W�Y     �              T  echo 'SELECT count(carrier_id) from vicidial_server_carriers where carrier_id = "PREFIX#{node['hostname']}_DOM"' | mysql -u #{node['at_atmosphere_ivr']['VARDB_user']} -p#{node['at_atmosphere_ivr']['VARDB_pass']} -h#{node['at_atmosphere_ivr']['database_hostname']} --skip-column-names  #{node['at_atmosphere_ivr']['database']} | grep '^1$'5�_�                       `    ����                                                                                                                                                                                                                                                                                                                                                             W�Z     �              V  echo 'SELECT count(carrier_id) from vicidial_server_carriers where carrier_id = "PREFIX#{node[]['hostname']}_DOM"' | mysql -u #{node['at_atmosphere_ivr']['VARDB_user']} -p#{node['at_atmosphere_ivr']['VARDB_pass']} -h#{node['at_atmosphere_ivr']['database_hostname']} --skip-column-names  #{node['at_atmosphere_ivr']['database']} | grep '^1$'5�_�                       a    ����                                                                                                                                                                                                                                                                                                                                                             W�Z     �              X  echo 'SELECT count(carrier_id) from vicidial_server_carriers where carrier_id = "PREFIX#{node['']['hostname']}_DOM"' | mysql -u #{node['at_atmosphere_ivr']['VARDB_user']} -p#{node['at_atmosphere_ivr']['VARDB_pass']} -h#{node['at_atmosphere_ivr']['database_hostname']} --skip-column-names  #{node['at_atmosphere_ivr']['database']} | grep '^1$'5�_�                       a    ����                                                                                                                                                                                                                                                                                                                                                             W�Z     �              X  echo 'SELECT count(carrier_id) from vicidial_server_carriers where carrier_id = "PREFIX#{node['']['hostname']}_DOM"' | mysql -u #{node['at_atmosphere_ivr']['VARDB_user']} -p#{node['at_atmosphere_ivr']['VARDB_pass']} -h#{node['at_atmosphere_ivr']['database_hostname']} --skip-column-names  #{node['at_atmosphere_ivr']['database']} | grep '^1$'5�_�                        u    ����                                                                                                                                                                                                                                                                                                                                                             W�]    �              h  echo 'SELECT count(carrier_id) from vicidial_server_carriers where carrier_id = "PREFIX#{node['at_vagrant_setup']['hostname']}_DOM"' | mysql -u #{node['at_atmosphere_ivr']['VARDB_user']} -p#{node['at_atmosphere_ivr']['VARDB_pass']} -h#{node['at_atmosphere_ivr']['database_hostname']} --skip-column-names  #{node['at_atmosphere_ivr']['database']} | grep '^1$'5��