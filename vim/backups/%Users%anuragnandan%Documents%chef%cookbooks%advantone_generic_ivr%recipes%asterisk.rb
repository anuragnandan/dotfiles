Vim�UnDo�  ���� !�K���D�3󵤱��;��0�   �   /var/spool/asterisk   0                          URe�    _�                             ����                                                                                                                                                                                                                                                                                                                                                             URe�     �               �   %w[     libcap-devel     popt-devel     gsm-devel     libogg-devel     libvorbis-devel     openssl-devel   ].each do |p|     package p do       action :install     end   end       *tarfile = 'asterisk-1.4.31.patched.tar.gz'   :tardir = tarfile.gsub(/\.tar\.gz/,'').gsub(/\.patched/,'')   "bash "download_source_asterisk" do     cwd  '/usr/local/src/'     user 'root'     code <<-EOF   m  curl -O -k http://bop6gitlab01.ftl.prosodieinteractive.local/software/asterisk-1-4-31/raw/master/#{tarfile}     tar -xvzf #{tarfile}     cd #{tardir}     EOF     action :run   5  not_if { ::File.exists? "/usr/local/src/#{tardir}"}   end       /template "/usr/local/src/#{tardir}/makeopts" do      source "asterisk_makeopts.erb"   end           bash "make_compile_asterisk" do   !  cwd  "/usr/local/src/#{tardir}"     user 'root'     code <<-EOF     make clean     make     make install     EOF     action :run   /  not_if { ::File.exists? "/usr/sbin/asterisk"}   end       %w[   /etc/asterisk   /var/lib/asterisk   /var/spool/asterisk   /etc/env/asterisk   ].each do |d|     directory d do       owner "root"       group "root"       mode 0775       recursive true     end   end       directory "/usr/g5"       git "/usr/g5/captures" do   \  repository "http://bop6gitlab01.ftl.prosodieinteractive.local/software/move_capture_d.git"     action :export   -  not_if { ::File.exists? "/usr/g5/captures"}   end       +template "/etc/init/move_capture_d.conf" do   $    source "move_capture_d.conf.erb"   end       git "/usr/g5/dbproxy" do   U  repository "http://bop6gitlab01.ftl.prosodieinteractive.local/software/dbproxy.git"     action :sync   end       $template "/etc/init/dbproxy.conf" do       source "dbproxy.conf.erb"   end       %w[   G4_00_Phrase_Player.conf.erb   !G4_00_Phrase_Recorder_V1.conf.erb    G4_00_Survey_Test_Logic.conf.erb   G4_00_Voice_Message1.conf.erb   acdaccounts.conf.erb   adsi.conf.erb   adtranvofr.conf.erb   agents.conf.erb   alarmreceiver.conf.erb   alsa.conf.erb   amd.conf.erb   asterisk.conf.erb   astmanproxy.conf.erb   cdr.conf.erb   cdr_custom.conf.erb   cdr_manager.conf.erb   cdr_odbc.conf.erb   cdr_pgsql.conf.erb   cdr_tds.conf.erb   codecs.conf.erb   dnsmgr.conf.erb   dundi.conf.erb   enum.conf.erb   extconfig.conf.erb   extensions.conf.erb   features.conf.erb   festival.conf.erb   followme.conf.erb   func_odbc.conf.erb   gtalk.conf.erb   h323.conf.erb   http.conf.erb   iax.conf.erb   iaxprov.conf.erb   indications.conf.erb   jabber.conf.erb   logger.conf.erb   lumenvox.conf.erb   manager.conf.erb   meetme.conf.erb   mgcp.conf.erb   misdn.conf.erb   modules.conf.erb   musiconhold.conf.erb   muted.conf.erb   osp.conf.erb   oss.conf.erb   outbound_call_gen.conf.erb   phone.conf.erb   phpagi.conf.erb   privacy.conf.erb   prosodiealert.conf.erb   prosodieops.conf.erb   queues.conf.erb   res_mrcp.conf.erb   res_odbc.conf.erb   res_pgsql.conf.erb   res_snmp.conf.erb   rpt.conf.erb   rtp.conf.erb   say.conf.erb   sip_notify.conf.erb   skinny.conf.erb   sla.conf.erb   smdi.conf.erb   tnarce.conf.erb   udptl.conf.erb   users.conf.erb   voicemail.conf.erb   vpb.conf.erb   zapata.conf.erb   ].each do |t|     file=t.gsub(/\.erb$/, "")   %  template "/etc/asterisk/#{file}" do     source "asterisk/#{t}"     end   end       /template "/etc/env/asterisk/extensions.conf" do   +  source "env/asterisk/extensions.conf.erb"   end5�_�                     0       ����                                                                                                                                                                                                                                                                                                                                                             URe�    �   /   1   �      /var/spool/asterisk5��