Vim�UnDo� C�4��7��V�m�r�'�UXV��Y!}DI�y8                                      Z`��    _�                             ����                                                                                                                                                                                                                                                                                                                                                             Z`��     �                  .node['dotenv']['config'].each do |app, config|   1  location = node['dotenv']['doc_root'][app.to_s]         directory location.to_s do       recursive true     end         config_string = ''     config.each do |key, val|   &    config_string << "#{key}=#{val}\n"     end         file "#{location}/.env" do       action :create       content config_string     end   end5�_�                             ����                                                                                                                                                                                                                                                                                                                                                             Z`��    �                1  location = node['dotenv']['doc_root'][app.to_s]�                 .node['dotenv']['config'].each do |app, config|5��