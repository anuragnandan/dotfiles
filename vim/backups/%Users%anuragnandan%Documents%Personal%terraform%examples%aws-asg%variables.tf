Vim�UnDo� �Z�t��ϧ_�ăRy(����Siq~�-}   (     default     = "2"   "                          Yo}d    _�                             ����                                                                                                                                                                                                                                                                                                                                                             Yo}!     �               )   variable "aws_region" {   5  description = "The AWS region to create things in."     default     = "us-east-1"   }       # ubuntu-trusty-14.04 (x64)   variable "aws_amis" {     default = {        "us-east-1" = "ami-5f709f34"        "us-west-2" = "ami-7f675e4f"     }   }       variable "availability_zones" {   =  default     = "us-east-1b,us-east-1c,us-east-1d,us-east-1e"   G  description = "List of availability zones, use AWS CLI to find your "   }       variable "key_name" {   &  description = "Name of AWS key pair"   }       variable "instance_type" {     default     = "t2.micro"   #  description = "AWS instance type"   }       variable "asg_min" {   /  description = "Min numbers of servers in ASG"     default     = "1"   }       variable "asg_max" {   /  description = "Max numbers of servers in ASG"     default     = "2"   }       variable "asg_desired" {   3  description = "Desired numbers of servers in ASG"     default     = "1"   }5�_�                    
        ����                                                                                                                                                                                                                                                                                                                                                             Yo}'     �   	   
               "us-west-2" = "ami-7f675e4f"5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Yo}8     �         (      =  default     = "us-east-1b,us-east-1c,us-east-1d,us-east-1e"5�_�                           ����                                                                                                                                                                                                                                                                                                                                         ;       v   ;    Yo}?    �         (      =  default     = "us-east-1a,us-east-1c,us-east-1d,us-east-1e"5�_�                     "       ����                                                                                                                                                                                                                                                                                                                                         ;       v   ;    Yo}c    �   !   #   (        default     = "2"5��