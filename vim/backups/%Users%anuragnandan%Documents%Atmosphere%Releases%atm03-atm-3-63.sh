Vim�UnDo� �_�vBKB�ҋ��K���ڕC*=){OG�^�|   0                                  X"�    _�                             ����                                                                                                                                                                                                                                                                                                                                                             X"&     �               ,   atm03-atm-3-62   #Backup       aevp1arsyncd03a:   ccd /g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services   .tar cvfz Atmosphere.20160922.tar.gz Atmosphere   *tar cvfz atmagent.20160922.tar.gz atmagent        cd /g5share/g5/production_atm03/   (tar cvfz agi-bin.20160922.tar.gz agi-bin       �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf.20160922   �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/admin/VPDAdminUI.swf /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/admin/VPDAdminUI.swf.20160922       #Push       aevp1aatmsql03a:   �ALTER TABLE `vicidial_campaigns` ADD COLUMN `agent_pause_code_forceout` ENUM('Y','N') NOT NULL DEFAULT 'Y' AFTER `d4_capture_enabled`;       bop6atmadm01:   git checkout atm03/atm-3.62   git pull origin atm03/atm-3.62       �scp VACD_Strategies.php aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services/Atmosphere/   	#atmagent   �scp login.php aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services/atmagent/       #agi-bin   3scp cloud.php /g5share/g5/production_atm03/agi-bin/       qscp VPDAgentUI.swf aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/   qscp VPDAdminUI.swf aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/admin/       
#Roolback:   aevp1arsyncd03a:   ccd /g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services   #tar -xvf Atmosphere.20160922.tar.gz   !tar -xvf atmagent.20160922.tar.gz       cd /g5share/g5/production_atm03    tar -xvf agi-bin.20160922.tar.gz       �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf.20160922 /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf   �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/admin/VPDAdminUI.swf.20160922 /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAdminUI.swf5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             X"4     �   +   -          �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/admin/VPDAdminUI.swf.20160922 /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAdminUI.swf�   *   ,          �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf.20160922 /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf�   (   *           tar -xvf agi-bin.20160922.tar.gz�   %   '          !tar -xvf atmagent.20160922.tar.gz�   $   &          #tar -xvf Atmosphere.20160922.tar.gz�                �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/admin/VPDAdminUI.swf /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/admin/VPDAdminUI.swf.20160922�                �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf.20160922�   	             (tar cvfz agi-bin.20160922.tar.gz agi-bin�                *tar cvfz atmagent.20160922.tar.gz atmagent�                .tar cvfz Atmosphere.20160922.tar.gz Atmosphere5�_�                    	        ����                                                                                                                                                                                                                                                                                                                            	                      v        X"U     �      
   ,       cd /g5share/g5/production_atm03/   (tar cvfz agi-bin.20161108.tar.gz agi-bin       �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf.201611085�_�                            ����                                                                                                                                                                                                                                                                                                                                                  v        X"f     �         )      �ALTER TABLE `vicidial_campaigns` ADD COLUMN `agent_pause_code_forceout` ENUM('Y','N') NOT NULL DEFAULT 'Y' AFTER `d4_capture_enabled`;       bop6atmadm01:5�_�                            ����                                                                                                                                                                                                                                                                                                                                                  v        X"g     �         '    5�_�                            ����                                                                                                                                                                                                                                                                                                                                                  v        X"r     �         (       �         (    5�_�                           ����                                                                                                                                                                                                                                                                                                                                                  v        X"|     �         4      git checkout atm03/atm-3.625�_�      	                     ����                                                                                                                                                                                                                                                                                                                                                  v        X"}     �         4      git pull origin atm03/atm-3.625�_�      
           	       	    ����                                                                                                                                                                                                                                                                                                                                                  v        X"�     �      !   4      �scp VACD_Strategies.php aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services/Atmosphere/5�_�   	              
           ����                                                                                                                                                                                                                                                                                                                                                  v        X"�     �       "   4    �       !   4    5�_�   
                 !       ����                                                                                                                                                                                                                                                                                                                                                  v        X"�     �       "   5      �scp VACD_Statuses.php aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services/Atmosphere/5�_�                    !   �    ����                                                                                                                                                                                                                                                                                                                                                  v        X"�     �       "   5      �scp soapwebservices/AgentService.php/AgentService.php aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services/Atmosphere/5�_�                    #   �    ����                                                                                                                                                                                                                                                                                                                                                  v        X"�     �   #   %   5    �   #   $   5    5�_�                    $       ����                                                                                                                                                                                                                                                                                                                                                  v        X"�    �   #   %   6      �scp login.php aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services/atmagent/5�_�                    $       ����                                                                                                                                                                                                                                                                                                                                                             X"�     �               6   atm03-atm-3-62   #Backup       aevp1arsyncd03a:   ccd /g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services   .tar cvfz Atmosphere.20161108.tar.gz Atmosphere   *tar cvfz atmagent.20161108.tar.gz atmagent       �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf.20161108   �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/admin/VPDAdminUI.swf /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/admin/VPDAdminUI.swf.20161108       #Push       aevp1aatmsql03a:   oALTER TABLE asterisk.`vicidial_vacd_statuses` ADD `callback` ENUM('Y','N')  NOT NULL  DEFAULT 'N'  AFTER `dnc`;   �INSERT INTO asterisk.`report_column` (`report_column_id`, `report_output_id`, `report_column_type_name`, `sequence`, `header_text`, `data_field`, `gen_format`, `gen_sort`, `visible`, `renderer_type_name`, `field_id`, `tooltip`)   VALUES   d	(1913, 149, 'NUMBER', 35, 'Billable Seconds', 'Billable Seconds', 'N', 'Y', 'Y', NULL, NULL, NULL),   ^	(1912, 149, 'STRING', 34, 'Disposition Time', 'dispo_time', 'N', 'Y', 'Y', NULL, NULL, NULL),   _	(1911, 149, 'STRING', 33, 'Agent Group', 'agent_group_name', 'N', 'Y', 'Y', NULL, NULL, NULL),   Z	(1910, 149, 'STRING', 32, 'Hold Time', 'hold_duration', 'Y', 'Y', 'Y', NULL, NULL, NULL),   ^	(1909, 149, 'STRING', 31, 'Callback Time', 'callback_time', 'Y', 'Y', 'Y', NULL, NULL, NULL),   \	(1908, 149, 'STRING', 30, 'Available Time', 'avail_time', 'Y', 'Y', 'Y', NULL, NULL, NULL),   T	(1907, 149, 'STRING', 29, 'IVR Time', 'ivr_time', 'Y', 'Y', 'Y', NULL, NULL, NULL),   X	(1906, 149, 'STRING', 28, 'Session ID', 'session_id', 'Y', 'Y', 'Y', NULL, NULL, NULL);           bop6atmadm01:   git checkout atm03/atm-3.63   git pull origin atm03/atm-3.63       �scp VACD_Statuses.php aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services/Atmosphere/   �scp soapwebservices/AgentService.php/AgentService.php aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services/Atmosphere/soapwebservices/AgentService/   	#atmagent   �scp login.php aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services/atmagent/   �scp vdc_db_query.php aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services/atmagent/       #agi-bin   3scp cloud.php /g5share/g5/production_atm03/agi-bin/       qscp VPDAgentUI.swf aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/   qscp VPDAdminUI.swf aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/admin/       
#Roolback:   aevp1arsyncd03a:   ccd /g5share/webshare/production_atm03/sites/amfphp.atmosphere.atm03.advantone.net/services/services   #tar -xvf Atmosphere.20161108.tar.gz   !tar -xvf atmagent.20161108.tar.gz       cd /g5share/g5/production_atm03    tar -xvf agi-bin.20161108.tar.gz       �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf.20161108 /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAgentUI.swf   �cp -av /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/admin/VPDAdminUI.swf.20161108 /g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/VPDAdminUI.swf5�_�                    &        ����                                                                                                                                                                                                                                                                                                                            &           (           v        X"�     �   %   '   6      #agi-bin   3scp cloud.php /g5share/g5/production_atm03/agi-bin/       qscp VPDAgentUI.swf aevp1arsyncd03a:/g5share/webshare/production_atm03/sites/atmosphere.atm03.advantone.net/agent/5�_�                   /        ����                                                                                                                                                                                                                                                                                                                            &           &           v        X"�     �   .   /          cd /g5share/g5/production_atm03    tar -xvf agi-bin.20161108.tar.gz5�_�                     /        ����                                                                                                                                                                                                                                                                                                                            &           &           v        X"�    �   .   /           5�_�                    /        ����                                                                                                                                                                                                                                                                                                                            &           &           v        X"�     �   .   4        5��