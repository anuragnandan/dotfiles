Vim�UnDo� :�_�V;uV'^v�>��3��X~���8'�   G                                  Zb�    _�                             ����                                                                                                                                                                                                                                                                                                                                                             ZZ�o     �               F   #   # Cookbook Name:: at_webserver   # Recipe:: default #   )# Copyright (C) 2014 Fibo Consulting LLC.   #   +# All rights reserved - Do Not Redistribute   #       'include_recipe 'at_atmosphere::default'       !subject = node['location_prefix']       include_recipe 'at_php'       $directory '/var/log/astguiclient' do     action :create     recursive true     owner 'root'     group 'root'   end       /file '/var/log/astguiclient/queries_agc.log' do     action :create     mode 0o777     owner 'root'     group 'root'   end       directory '/srv/www/default' do     owner 'apache'     group 'apache'     recursive true   end       )template '/srv/www/default/index.html' do     owner 'apache'     group 'apache'     source 'index.html.erb'   end       ;directory "/srv/www/atmosphere.#{subject}.advantone.net" do     owner 'apache'     group 'apache'     recursive true     action :create   end       Bdirectory "/srv/www/amfphp.atmosphere.#{subject}.advantone.net" do     owner 'apache'     group 'apache'     recursive true     action :create   end   Sdirectory "#{node['at_rsync']['base_path']}/g5/#{subject}/sounds/custom/atm/100" do     owner 'root'     group 'root'     mode 0o777     recursive true   end       Tdirectory "#{node['at_rsync']['base_path']}/g5/#{subject}/Atmosphere/inbound/100" do     owner 'root'     group 'root'     mode 0o777     recursive true   end       link '/share' do   +  to "/g5share/g5/#{node.chef_environment}"   end5�_�                    E       ����                                                                                                                                                                                                                                                                                                                                                             ZZͨ     �   E   G   F    �   E   F   F    5�_�                    F       ����                                                                                                                                                                                                                                                                                                                                                             ZZͪ     �   E   G   G      +  to "/g5share/g5/#{node.chef_environment}"5�_�                    F       ����                                                                                                                                                                                                                                                                                                                                                             ZZͯ     �   E   G   G      3  only_if {} "/g5share/g5/#{node.chef_environment}"5�_�                    F   @    ����                                                                                                                                                                                                                                                                                                                                                             ZZ��    �   E   G   G      @  only_if { ::File.exist? "/g5share/g5/#{node.chef_environment}"5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             Zb	�     �               G   #   # Cookbook Name:: at_webserver   # Recipe:: default #   )# Copyright (C) 2014 Fibo Consulting LLC.   #   +# All rights reserved - Do Not Redistribute   #       'include_recipe 'at_atmosphere::default'       !subject = node['location_prefix']       include_recipe 'at_php'       $directory '/var/log/astguiclient' do     action :create     recursive true     owner 'root'     group 'root'   end       /file '/var/log/astguiclient/queries_agc.log' do     action :create     mode 0o777     owner 'root'     group 'root'   end       directory '/srv/www/default' do     owner 'apache'     group 'apache'     recursive true   end       )template '/srv/www/default/index.html' do     owner 'apache'     group 'apache'     source 'index.html.erb'   end       ;directory "/srv/www/atmosphere.#{subject}.advantone.net" do     owner 'apache'     group 'apache'     recursive true     action :create   end       Bdirectory "/srv/www/amfphp.atmosphere.#{subject}.advantone.net" do     owner 'apache'     group 'apache'     recursive true     action :create   end   Sdirectory "#{node['at_rsync']['base_path']}/g5/#{subject}/sounds/custom/atm/100" do     owner 'root'     group 'root'     mode 0o777     recursive true   end       Tdirectory "#{node['at_rsync']['base_path']}/g5/#{subject}/Atmosphere/inbound/100" do     owner 'root'     group 'root'     mode 0o777     recursive true   end       link '/share' do   +  to "/g5share/g5/#{node.chef_environment}"   B  only_if { ::File.exist? "/g5share/g5/#{node.chef_environment}" }   end5�_�                             ����                                                                                                                                                                                                                                                                                                                                                             Zb�    �   1   3            group 'apache'�   0   2            owner 'apache'�   *   ,            group 'apache'�   )   +            owner 'apache'�   $   &            group 'apache'�   #   %            owner 'apache'�                   group 'apache'�                  owner 'apache'5��