Vim�UnDo� �� ��j5�T��ap��@M��n�H�V�@9���   ;       	          #       #   #   #    VEJ    _�                            ����                                                                                                                                                                                                                                                                                                                                                             VE     �               7   #   $# Cookbook Name:: advantone_openfire   # Recipe:: default   #   6# Copyright (c) 2015 The Authors, All Rights Reserved.       package 'mysql'   "include_recipe 'openfire::default'       ,subject = node.chef_environment.slice(-5, 5)   Wnode.default['openfire']['config']['domain'] = "of.atmosphere.#{subject}.advantone.net"       �node.default['openfire']['config']['database']['server_url'] = "jdbc:mysql://#{node['atmosphere']['database_hostname']}:#{node['atmosphere']['database_port']}/#{node['openfire']['database']['schema']}"       unode.default['openfire']['database']['user'] = "#{data_bag_item(node.chef_environment, 'openfire_user')['username']}"       ynode.default['openfire']['database']['password'] = "#{data_bag_item(node.chef_environment, 'openfire_user')['password']}"       service 'openfirestop' do     service_name 'openfire'     supports :status => true,              :stop => true     action :stop   4  not_if 'grep serverURL /etc/openfire/openfire.xml'   end       >template '/opt/openfire/resources/database/openfireins.sql' do     group 'openfire'     mode '0600'     owner 'openfire'   end       bash 'create_schema' do     code <<-EOF   %  cd /opt/openfire/resources/database   �  echo 'create database #{node['openfire']['database']['schema']}' | mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']}   �  mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} #{node['openfire']['database']['schema']} < openfire_mysql.sql   �  mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} #{node['openfire']['database']['schema']} < openfireins.sql     EOF   �  not_if "mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} -e 'show databases' | grep openfire"   end       (template '/etc/openfire/openfire.xml' do     group 'openfire'     mode '0600'     owner 'openfire'   ?  not_if 'grep "username encrypted" /etc/openfire/openfire.xml'   end       service 'openfirestart' do     service_name 'openfire'     supports :status => true,              :stop => true     action :start   end5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             VE(     �      	   7    5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             VE-     �         8      package 'mysql'5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             VE/     �      
   :        �      	   9    �      	   8      package 'mysql'5�_�      	                     ����                                                                                                                                                                                                                                                                                                                                                             VE<     �      	   ;        action: install5�_�      
           	      	    ����                                                                                                                                                                                                                                                                                                                                                             VE=     �      	   ;        action: install5�_�   	              
      	    ����                                                                                                                                                                                                                                                                                                                                                             VE=     �      	   ;        action install5�_�   
                 	        ����                                                                                                                                                                                                                                                                                                                                                             VE?     �      
   ;       5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             VE\     �      
   ;      	  not_id 5�_�                    	   	    ����                                                                                                                                                                                                                                                                                                                                                             VE^    �      
   ;      	  not_if 5�_�                    	   	    ����                                                                                                                                                                                                                                                                                                                                                             VE�     �               ;   #   $# Cookbook Name:: advantone_openfire   # Recipe:: default   #   6# Copyright (c) 2015 The Authors, All Rights Reserved.       package 'mysql' do     action :install     not_if ''   end       "include_recipe 'openfire::default'       ,subject = node.chef_environment.slice(-5, 5)   Wnode.default['openfire']['config']['domain'] = "of.atmosphere.#{subject}.advantone.net"       �node.default['openfire']['config']['database']['server_url'] = "jdbc:mysql://#{node['atmosphere']['database_hostname']}:#{node['atmosphere']['database_port']}/#{node['openfire']['database']['schema']}"       unode.default['openfire']['database']['user'] = "#{data_bag_item(node.chef_environment, 'openfire_user')['username']}"       ynode.default['openfire']['database']['password'] = "#{data_bag_item(node.chef_environment, 'openfire_user')['password']}"       service 'openfirestop' do     service_name 'openfire'     supports :status => true,              :stop => true     action :stop   4  not_if 'grep serverURL /etc/openfire/openfire.xml'   end       >template '/opt/openfire/resources/database/openfireins.sql' do     group 'openfire'     mode '0600'     owner 'openfire'   end       bash 'create_schema' do     code <<-EOF   %  cd /opt/openfire/resources/database   �  echo 'create database #{node['openfire']['database']['schema']}' | mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']}   �  mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} #{node['openfire']['database']['schema']} < openfire_mysql.sql   �  mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} #{node['openfire']['database']['schema']} < openfireins.sql     EOF   �  not_if "mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} -e 'show databases' | grep openfire"   end       (template '/etc/openfire/openfire.xml' do     group 'openfire'     mode '0600'     owner 'openfire'   ?  not_if 'grep "username encrypted" /etc/openfire/openfire.xml'   end       service 'openfirestart' do     service_name 'openfire'     supports :status => true,              :stop => true     action :start   end5�_�                    	   	    ����                                                                                                                                                                                                                                                                                                                                                             VE�     �      
   ;        not_if ''5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             VE�     �      
   ;        not_if {}''5�_�                    	   
    ����                                                                                                                                                                                                                                                                                                                                                             VE�     �      
   ;        not_if {}5�_�                    	   
    ����                                                                                                                                                                                                                                                                                                                                                             VE�     �      
   ;        not_if {::}5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             VE�     �      
   ;        not_if { ::}5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             VE�     �      
   ;        not_if { ::File Exists? }5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             VE�     �      
   ;        not_if { ::File Exists? ''}5�_�                    	   &    ����                                                                                                                                                                                                                                                                                                                                                             VE�    �      
   ;      +  not_if { ::File Exists? '/usr/bin/musql'}5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             VE     �      
   ;      +  not_if { ::File Exists? '/usr/bin/mysql'}5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             VE     �      
   ;      +  not_if { ::File.exists? '/usr/bin/mysql'}5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             VE	     �      
   ;      +  not_if { ::File.exists? '/usr/bin/mysql'}5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             VE     �      
   ;      *  not_if { ::File.exist? '/usr/bin/mysql'}5�_�                    	   &    ����                                                                                                                                                                                                                                                                                                                                                             VE     �      
   ;      *  not_if { ::File.exist? '/usr/bin/mysql'}5�_�                    	   '    ����                                                                                                                                                                                                                                                                                                                                                             VE     �      
   ;      *  not_if { ::File.exist? '/usr/bin/mysql'}5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             VE     �      
   ;      *  not_if { ::File.exist? '/usr/bin/mysql'}5�_�                    	       ����                                                                                                                                                                                                                                                                                                                                                             VE1     �      
   ;      *  not_if { ::File.exist? '/usr/bin/mysql'}5�_�                     	       ����                                                                                                                                                                                                                                                                                                                                                             VE5     �      
   ;      +  not_if { ::File.exist?()'/usr/bin/mysql'}5�_�      !               	       ����                                                                                                                                                                                                                                                                                                                                                             VE6     �      
   ;      *  not_if { ::File.exist?('/usr/bin/mysql'}5�_�       "           !   	   &    ����                                                                                                                                                                                                                                                                                                                                                             VE7     �      
   ;      *  not_if { ::File.exist?('/usr/bin/mysql'}5�_�   !   #           "   	   )    ����                                                                                                                                                                                                                                                                                                                                                             VE9    �      
   ;      *  not_if { ::File.exist?('/usr/bin/mysql'}5�_�   "               #   	   "    ����                                                                                                                                                                                                                                                                                                                                                             VEI    �               ;   #   $# Cookbook Name:: advantone_openfire   # Recipe:: default   #   6# Copyright (c) 2015 The Authors, All Rights Reserved.       package 'mysql' do     action :install   ,  not_if { ::File.exist?('/usr/bin/mysql') }   end       "include_recipe 'openfire::default'       ,subject = node.chef_environment.slice(-5, 5)   Wnode.default['openfire']['config']['domain'] = "of.atmosphere.#{subject}.advantone.net"       �node.default['openfire']['config']['database']['server_url'] = "jdbc:mysql://#{node['atmosphere']['database_hostname']}:#{node['atmosphere']['database_port']}/#{node['openfire']['database']['schema']}"       unode.default['openfire']['database']['user'] = "#{data_bag_item(node.chef_environment, 'openfire_user')['username']}"       ynode.default['openfire']['database']['password'] = "#{data_bag_item(node.chef_environment, 'openfire_user')['password']}"       service 'openfirestop' do     service_name 'openfire'     supports :status => true,              :stop => true     action :stop   4  not_if 'grep serverURL /etc/openfire/openfire.xml'   end       >template '/opt/openfire/resources/database/openfireins.sql' do     group 'openfire'     mode '0600'     owner 'openfire'   end       bash 'create_schema' do     code <<-EOF   %  cd /opt/openfire/resources/database   �  echo 'create database #{node['openfire']['database']['schema']}' | mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']}   �  mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} #{node['openfire']['database']['schema']} < openfire_mysql.sql   �  mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} #{node['openfire']['database']['schema']} < openfireins.sql     EOF   �  not_if "mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} -e 'show databases' | grep openfire"   end       (template '/etc/openfire/openfire.xml' do     group 'openfire'     mode '0600'     owner 'openfire'   ?  not_if 'grep "username encrypted" /etc/openfire/openfire.xml'   end       service 'openfirestart' do     service_name 'openfire'     supports :status => true,              :stop => true     action :start   end5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             V=R     �       8       7   #   $# Cookbook Name:: advantone_openfire   # Recipe:: default   #   6# Copyright (c) 2015 The Authors, All Rights Reserved.       package 'mysql'   "include_recipe 'openfire::default'       ,subject = node.chef_environment.slice(-5, 5)   Wnode.default['openfire']['config']['domain'] = "of.atmosphere.#{subject}.advantone.net"       �node.default['openfire']['config']['database']['server_url'] = "jdbc:mysql://#{node['atmosphere']['database_hostname']}:#{node['atmosphere']['database_port']}/#{node['openfire']['database']['schema']}"       unode.default['openfire']['database']['user'] = "#{data_bag_item(node.chef_environment, 'openfire_user')['username']}"       ynode.default['openfire']['database']['password'] = "#{data_bag_item(node.chef_environment, 'openfire_user')['password']}"       service 'openfirestop' do     service_name 'openfire'     supports :status => true,              :stop => true     action :stop   4  not_if 'grep serverURL /etc/openfire/openfire.xml'   end       >template '/opt/openfire/resources/database/openfireins.sql' do     group 'openfire'     mode '0600'     owner 'openfire'   end       bash 'create_schema' do     code <<-EOF   %  cd /opt/openfire/resources/database   �  echo 'create database #{node['openfire']['database']['schema']}' | mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']}   �  mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} #{node['openfire']['database']['schema']} < openfire_mysql.sql   �  mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} #{node['openfire']['database']['schema']} < openfireins.sql     EOF   �  not_if "mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} -e 'show databases' | grep openfire"   end       (template '/etc/openfire/openfire.xml' do     group 'openfire'     mode '0600'     owner 'openfire'   ?  not_if 'grep "username encrypted" /etc/openfire/openfire.xml'   end       service 'openfirestart' do     service_name 'openfire'     supports :status => true,              :stop => true     action :start   end5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             V=l    �         7      package 'mariadb'5�_�                     7        ����                                                                                                                                                                                                                                                                                                                                                             V=f     �       8       7   #   $# Cookbook Name:: advantone_openfire   # Recipe:: default   #   6# Copyright (c) 2015 The Authors, All Rights Reserved.       package 'mariadb'   "include_recipe 'openfire::default'       ,subject = node.chef_environment.slice(-5, 5)   Wnode.default['openfire']['config']['domain'] = "of.atmosphere.#{subject}.advantone.net"       �node.default['openfire']['config']['database']['server_url'] = "jdbc:mysql://#{node['atmosphere']['database_hostname']}:#{node['atmosphere']['database_port']}/#{node['openfire']['database']['schema']}"       unode.default['openfire']['database']['user'] = "#{data_bag_item(node.chef_environment, 'openfire_user')['username']}"       ynode.default['openfire']['database']['password'] = "#{data_bag_item(node.chef_environment, 'openfire_user')['password']}"       service 'openfirestop' do     service_name 'openfire'     supports :status => true,              :stop => true     action :stop   4  not_if 'grep serverURL /etc/openfire/openfire.xml'   end       >template '/opt/openfire/resources/database/openfireins.sql' do     group 'openfire'     mode '0600'     owner 'openfire'   end       bash 'create_schema' do     code <<-EOF   %  cd /opt/openfire/resources/database   �  echo 'create database #{node['openfire']['database']['schema']}' | mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']}   �  mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} #{node['openfire']['database']['schema']} < openfire_mysql.sql   �  mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} #{node['openfire']['database']['schema']} < openfireins.sql     EOF   �  not_if "mysql -h#{node['atmosphere']['database_hostname']} -u#{node.default['openfire']['database']['user']} -p#{node.default['openfire']['database']['password']} -e 'show databases' | grep openfire"   end       (template '/etc/openfire/openfire.xml' do     group 'openfire'     mode '0600'     owner 'openfire'   ?  not_if 'grep "username encrypted" /etc/openfire/openfire.xml'   end       service 'openfirestart' do     service_name 'openfire'     supports :status => true,              :stop => true     action :start   end5��