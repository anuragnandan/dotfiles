Vim�UnDo� ޖ�N��~����<��`�87�Y	s겣}�
   :   -  not_if { ::File.exist? '/usr/local/xymon' }   !   %                       VgZd    _�                     !       ����                                                                                                                                                                                                                                                                                                                                                             VgZ[     �               :   #   !# Cookbook Name:: advantone_xymon   # Recipe:: default   #   %# Copyright 2014, FiboConsulting LLC.   #   +# All rights reserved - Do Not Redistribute   #       package 'gcc' do     action :install   end       'user node['advantone_xymon']['user'] do     comment 'Xymon User'     system true     shell '/bin/false'     home '/opt/xymon'   end       tarfile = 'xymon-4.3.17.tar.gz'   &tardir = tarfile.gsub(/\.tar\.gz/, '')   bash 'download_xymon' do     cwd '/usr/local/src/'     user 'root'     code <<-EOF   J  curl -O -k http://scm.advantone.com/software/xymon/raw/master/#{tarfile}     tar -xvzf #{tarfile}     cd #{tardir}   H  curl -O -k http://scm.advantone.com/software/xymon/raw/master/Makefile     EOF     action :run   -  not_if { ::File.exist? '/usr/local/xymon' }   end       /template "/usr/local/src/#{tardir}/Makefile" do     source 'Makefile.erb'   end       bash 'install_xymon' do      cwd "/usr/local/src/#{tardir}"     user 'root'     code <<-EOF     make     make install     EOF     action :run   1  not_if { ::File.exist? '/opt/xymon/bin/xymon' }   end   &template '/etc/init.d/xymon-client' do     source 'xymon.erb'     mode '0755'   end       service 'xymon-client' do   1  supports restart: true, start: true, stop: true     action [:enable, :start]   end5�_�                    !   %    ����                                                                                                                                                                                                                                                                                                                                                             VgZ_     �       "   :      -  not_if { ::File.exist? '/usr/local/xymon' }5�_�                     !   "    ����                                                                                                                                                                                                                                                                                                                                                             VgZc    �       "   :      '  not_if { ::File.exist? '/opt/xymon' }5��