# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  #==============#
  # CentOS nodes #
  #==============#
  
  #Ansible-Node01
  config.vm.define "ansible-node01" do |cfg|
    cfg.vm.box = "centos/7"
	  cfg.vm.provider "virtualbox" do |vb|
	    vb.name = "Ansible-Node01(github_SysNet4Admin)"
	  end
	  cfg.vm.host_name = "ansible-node01"
	# NIC1: Host-Only
	  cfg.vm.network "private_network", ip: "192.168.56.11"
	  cfg.vm.network "forwarded_port", guest: 22, host: 60011, auto_correct: true, id: "ssh"
	  cfg.vm.synced_folder "./data", "/vagrant", disabled: false
      cfg.vm.provision "shell", inline: "sed -i 's/mirrorlist/#mirrorlist/g' /etc/yum.repos.d/CentOS-*"
      cfg.vm.provision "shell", inline: "sed -i 's|#baseurl=http://mirror.centos.org|baseurl=http://vault.centos.org|g' /etc/yum.repos.d/CentOS-*"	 
	  cfg.vm.provision "shell", path: "bash_ssh_conf_4_CentOS.sh"
    end
  
  #Ansible-Node02	 
  config.vm.define "ansible-node02" do |cfg|
    cfg.vm.box = "centos/7"
	  cfg.vm.provider "virtualbox" do |vb|
	    vb.name = "Ansible-Node02(github_SysNet4Admin)"
	  end
	  cfg.vm.host_name = "ansible-node02"
	# NIC1: Host-Only
	  cfg.vm.network "private_network", ip: "192.168.56.12"
	  cfg.vm.network "forwarded_port", guest: 22, host: 60012, auto_correct: true, id: "ssh"
	  cfg.vm.synced_folder "./data", "/vagrant", disabled: false
      cfg.vm.provision "shell", inline: "sed -i 's/mirrorlist/#mirrorlist/g' /etc/yum.repos.d/CentOS-*"
      cfg.vm.provision "shell", inline: "sed -i 's|#baseurl=http://mirror.centos.org|baseurl=http://vault.centos.org|g' /etc/yum.repos.d/CentOS-*"
	  cfg.vm.provision "shell", path: "bash_ssh_conf_4_CentOS.sh"
    end

  #Ansible-Node03	 
  config.vm.define "ansible-node03" do |cfg|
    cfg.vm.box = "centos/7"
	  cfg.vm.provider "virtualbox" do |vb|
	    vb.name = "Ansible-Node03(github_SysNet4Admin)"
	  end
	  cfg.vm.host_name = "ansible-node03"
	# NIC1: Host-Only
	  cfg.vm.network "private_network", ip: "192.168.56.13"
	  cfg.vm.network "forwarded_port", guest: 22, host: 60013, auto_correct: true, id: "ssh"
	  cfg.vm.synced_folder "./data", "/vagrant", disabled: false
      cfg.vm.provision "shell", inline: "sed -i 's/mirrorlist/#mirrorlist/g' /etc/yum.repos.d/CentOS-*"
      cfg.vm.provision "shell", inline: "sed -i 's|#baseurl=http://mirror.centos.org|baseurl=http://vault.centos.org|g' /etc/yum.repos.d/CentOS-*"	 
	  cfg.vm.provision "shell", path: "bash_ssh_conf_4_CentOS.sh"
    end

  #==============#
  # Ubuntu nodes #
  #==============#
  
  #Ansible-Node04
  config.vm.define "ansible-node04" do |cfg|
    cfg.vm.box = "ubuntu/focal64"
	cfg.vm.provider "virtualbox" do |vb|
	    vb.name = "Ansible-Node04(github_SysNet4Admin)"
	end
	cfg.vm.host_name = "ansible-node04"
	# NIC1: Host-Only
	cfg.vm.network "private_network", ip: "192.168.56.14"
	cfg.vm.network "forwarded_port", guest: 22, host: 60014, auto_correct: true, id: "ssh"
	cfg.vm.synced_folder "./data", "/vagrant", disabled: false
	cfg.vm.provision "shell", path: "bash_ssh_conf_4_Ubuntu.sh"
  end



  #Ansible-Node05
  config.vm.define "ansible-node05" do |cfg|
    cfg.vm.box = "ubuntu/focal64"
	cfg.vm.provider "virtualbox" do |vb|
	    vb.name = "Ansible-Node05(github_SysNet4Admin)"
	end
	cfg.vm.host_name = "ansible-node05"
	# NIC1: Host-Only
	cfg.vm.network "private_network", ip: "192.168.56.15"
	cfg.vm.network "forwarded_port", guest: 22, host: 60015, auto_correct: true, id: "ssh"
	cfg.vm.synced_folder "./data", "/vagrant", disabled: false
	cfg.vm.provision "shell", path: "bash_ssh_conf_4_Ubuntu.sh"
  end  



  #Ansible-Node06
  config.vm.define "ansible-node06" do |cfg|
    cfg.vm.box = "ubuntu/focal64"
	cfg.vm.provider "virtualbox" do |vb|
	    vb.name = "Ansible-Node06(github_SysNet4Admin)"
	end
	cfg.vm.host_name = "ansible-node06"
	# NIC1: Host-Only
	cfg.vm.network "private_network", ip: "192.168.56.16"
	cfg.vm.network "forwarded_port", guest: 22, host: 60016, auto_correct: true, id: "ssh"
	cfg.vm.synced_folder "./data", "/vagrant", disabled: false
	cfg.vm.provision "shell", path: "bash_ssh_conf_4_Ubuntu.sh"
  end  


#   #==============#
#   # Windows nodes #
#   #==============#
  
#   #Ansible-Node07
#   config.vm.define "ansible-node07" do |cfg|
#     cfg.vm.box = "sysnet4admin/Windows2016"
# 	  cfg.vm.provider "virtualbox" do |vb|
# 	    vb.name = "Ansible-Node07(github_SysNet4Admin)"
# 		vb.customize ['modifyvm', :id, '--clipboard', 'bidirectional']
# 	    vb.gui = false
# 	  end
# 	  cfg.vm.host_name = "ansible-node07"
# 	# NIC1: Host-Only
# 	  cfg.vm.network "private_network", ip: "192.168.56.17"
# 	  cfg.vm.network "forwarded_port", guest: 22, host: 60017, auto_correct: true, id: "ssh"
# 	  cfg.vm.synced_folder "./data", "/vagrant", disabled: false
#       cfg.vm.provision "shell", inline: "netsh advfirewall set allprofiles state off"
#     end  

  #================#
  # Ansible Server #
  #================#
  
  config.vm.define "ansible-server" do |cfg|
    cfg.vm.box = "centos/7"
 	  cfg.vm.provider "virtualbox" do |vb|
	    vb.name = "Ansible-Server(github_SysNet4Admin)"
	  end
	  cfg.vm.host_name = "ansible-server"
	# NIC1: Host-Only
	  cfg.vm.network "private_network", ip: "192.168.56.10"
	  cfg.vm.network "forwarded_port", guest: 22, host: 60010, auto_correct: true, id: "ssh"
	  cfg.vm.synced_folder "./data", "/vagrant", disabled: false
      cfg.vm.provision "shell", inline: "sed -i 's/mirrorlist/#mirrorlist/g' /etc/yum.repos.d/CentOS-*"
      cfg.vm.provision "shell", inline: "sed -i 's|#baseurl=http://mirror.centos.org|baseurl=http://vault.centos.org|g' /etc/yum.repos.d/CentOS-*"
      cfg.vm.provision "shell", inline: "yum install epel-release -y"
	  cfg.vm.provision "shell", inline: "yum install ansible -y"
	  cfg.vm.provision "file", source: "ansible_env_ready.yml", 
	    destination: "ansible_env_ready.yml"
	  cfg.vm.provision "shell", inline: "ansible-playbook ansible_env_ready.yml"
	  cfg.vm.provision "file", source: "auto_pass.yml", destination: "auto_pass.yml"
	  cfg.vm.provision "shell", inline: "ansible-playbook auto_pass.yml", privileged: false
      cfg.vm.provision "shell", path: "bash_ssh_conf_4_CentOS.sh"
  end
end