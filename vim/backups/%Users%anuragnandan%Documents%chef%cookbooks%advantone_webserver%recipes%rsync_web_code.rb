Vim�UnDo� W!��OG��BR=�����f��~Ac�$�(�vO�      �    rsync -a --delete #{node["advantone_rsync"]["server"]}::webshare-#{node.chef_environment}-frontend /srv/www/atmosphere.advantone.net/      {                       U8
�    _�                             ����                                                                                                                                                                                                                                                                                                                                                             U8
c     �                  package 'rsync'       ]["/srv/www/amfphp.atmosphere.advantone.net", "/srv/www/atmosphere.advantone.net"].each do |d|     directory d do       owner "apache"       group "apache"       recursive true     end   end       cron "sync-code-backend" do     minute '*/15'     user "root"     home "/root"     command <<-EOF   �    rsync -a --delete #{node["advantone_rsync"]["server"]}::webshare-#{node.chef_environment}-backend /srv/www/amfphp.atmosphere.advantone.net/     EOF   end       cron "sync-code-frontend" do     minute '*/15'     user "root"     home "/root"     command <<-EOF   �    rsync -a --delete #{node["advantone_rsync"]["server"]}::webshare-#{node.chef_environment}-frontend /srv/www/atmosphere.advantone.net/     EOF   end        5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             U8
j     �                5�_�                       &    ����                                                                                                                                                                                                                                                                                                                                                             U8
t     �               'subject = node.chef_environment.slice()5�_�                       &    ����                                                                                                                                                                                                                                                                                                                                                             U8
v     �               'subject = node.chef_environment.slice()5�_�                       +    ����                                                                                                                                                                                                                                                                                                                                                             U8
w     �               +subject = node.chef_environment.slice(-5,5)5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             U8
z     �               ]["/srv/www/amfphp.atmosphere.advantone.net", "/srv/www/atmosphere.advantone.net"].each do |d|5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             U8
z     �               ]["/srv/www/amfphp.atmosphere.advantone.net", "/srv/www/atmosphere.advantone.net"].each do |d|5�_�      	                     ����                                                                                                                                                                                                                                                                                                                                                             U8
{     �               ]["/srv/www/amfphp.atmosphere.advantone.net", "/srv/www/atmosphere.advantone.net"].each do |d|5�_�      
           	          ����                                                                                                                                                                                                                                                                                                                                                             U8
}     �               ]["/srv/www/amfphp.atmosphere.advantone.net", "/srv/www/atmosphere.advantone.net"].each do |d|5�_�   	              
          ����                                                                                                                                                                                                                                                                                                                                                             U8
�     �               a["/srv/www/amfphp.atmosphere.#{}.advantone.net", "/srv/www/atmosphere.advantone.net"].each do |d|5�_�   
                    +    ����                                                                                                                                                                                                                                                                                                                                                             U8
�     �               h["/srv/www/amfphp.atmosphere.#{subject}.advantone.net", "/srv/www/atmosphere.advantone.net"].each do |d|5�_�                       =    ����                                                                                                                                                                                                                                                                                                                                                             U8
�     �               h["/srv/www/amfphp.atmosphere.#{subject}.advantone.net", "/srv/www/atmosphere.advantone.net"].each do |d|5�_�                       E    ����                                                                                                                                                                                                                                                                                                                                                             U8
�     �               h["/srv/www/amfphp.atmosphere.#{subject}.advantone.net", "/srv/www/atmosphere.advantone.net"].each do |d|5�_�                       L    ����                                                                                                                                                                                                                                                                                                                                                             U8
�     �               h["/srv/www/amfphp.atmosphere.#{subject}.advantone.net", "/srv/www/atmosphere.advantone.net"].each do |d|5�_�                       O    ����                                                                                                                                                                                                                                                                                                                                                             U8
�     �               l["/srv/www/amfphp.atmosphere.#{subject}.advantone.net", "/srv/www/atmosphere.#{}.advantone.net"].each do |d|5�_�                       �    ����                                                                                                                                                                                                                                                                                                                                                             U8
�     �               �    rsync -a --delete #{node["advantone_rsync"]["server"]}::webshare-#{node.chef_environment}-backend /srv/www/amfphp.atmosphere.advantone.net/5�_�                       �    ����                                                                                                                                                                                                                                                                                                                                                             U8
�     �               �    rsync -a --delete #{node["advantone_rsync"]["server"]}::webshare-#{node.chef_environment}-backend /srv/www/amfphp.atmosphere.#{}.advantone.net/5�_�                       {    ����                                                                                                                                                                                                                                                                                                                                                             U8
�     �               �    rsync -a --delete #{node["advantone_rsync"]["server"]}::webshare-#{node.chef_environment}-frontend /srv/www/atmosphere.advantone.net/5�_�                       �    ����                                                                                                                                                                                                                                                                                                                                                             U8
�     �               �    rsync -a --delete #{node["advantone_rsync"]["server"]}::webshare-#{node.chef_environment}-frontend /srv/www/atmosphere..#{subject}.advantone.net/5�_�                       {    ����                                                                                                                                                                                                                                                                                                                                                             U8
�     �               �    rsync -a --delete #{node["advantone_rsync"]["server"]}::webshare-#{node.chef_environment}-frontend /srv/www/atmosphere..#{subject}.advantone.net/5�_�                        |    ����                                                                                                                                                                                                                                                                                                                                                             U8
�    �               �    rsync -a --delete #{node["advantone_rsync"]["server"]}::webshare-#{node.chef_environment}-frontend /srv/www/atmosphere..#{subject}.advantone.net/5��