Vim�UnDo� s��|K����aN���4��=� ���L��   a                                   U-w�    _�                              ����                                                                                                                                                                                                                                                                                                                                                             U-w�    �               a   class AwsSubnetManager       &  attr_reader :ec2, :subject, :subnets         def initialize(options)       @ec2 = options.fetch(:ec2)   &    @subject = options.fetch(:subject)   %    @subnets = initialize_subnet_hash     end         def initialize_subnet_hash       Hash.new     end         def next_subnet       subnets = []   &    ec2.vpcs.first.subnets.each do |s|   3      subnets << NetAddr::CIDR.create(s.cidr_block)       end   !    subnets.sort.last.next_subnet     end       /  def find_or_create_security_group(group_name)   [    security_group = @ec2.vpcs.first.security_groups.filter('group-name', group_name).first   ^    security_group = @ec2.vpcs.first.security_groups.create(group_name) if security_group.nil?       security_group     end       '  def find_subnets_by_name(subnet_name)       subnets = {}   +    ec2.vpcs.first.subnets.each do |subnet|          name = subnet.tags["Name"]   &      if name.start_with?(subnet_name)   6        subnets[name.gsub(/#{subnet_name}\s+/,'')] = {   #          "subnet_id" => subnet.id,   &          "cidr" => subnet.cidr_block,   !          "route_table_id" => '',   7          "zone_name" => subnet.availability_zone.name,   	        }   	      end       end       @subnets = subnets       subnets     end         def subnet_template       [         {           name: "Elastic 1d",   '        route_table_id: "rtb-35bc3459",           zone_name: "us-east-1d"         },         {           name: "Trusted 1a",   '        route_table_id: "rtb-fe42599c",           zone_name: "us-east-1a"         },         {           name: "Trusted 1d",   '        route_table_id: "rtb-fe42599c",           zone_name: "us-east-1d"         },         {           name: "Elastic 1a",   '        route_table_id: "rtb-35bc3459",           zone_name: "us-east-1a"         },         {            name: "Semi-Trusted 1d",   '        route_table_id: "rtb-fe42599c",           zone_name: "us-east-1d"         },         {            name: "Semi-Trusted 1a",   '        route_table_id: "rtb-fe42599c",           zone_name: "us-east-1a"         }       ]            end         def create_subnets   "    subnet_template.each do |zone|   ^      subnet = ec2.vpcs.first.subnets.create(next_subnet, availability_zone: zone[:zone_name])   m      ec2.client.create_tags(resources: [subnet.id], tags: [key: "Name", value: "#{subject} #{zone[:name]}"])   i      ec2.client.associate_route_table(:subnet_id => subnet.id, :route_table_id => zone[:route_table_id])                 subnets[zone[:name]] = {   !        "subnet_id" => subnet.id,           "cidr" => next_subnet,   2        "route_table_id" => zone[:route_table_id],   '        "zone_name" => zone[:zone_name]         }       end     end   end5��