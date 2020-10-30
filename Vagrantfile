# -*- mode: ruby -*-
# vi: set ft=ruby :

##########################################################################################
## Vagrant for development purpose only if you don't have docker on your local machine. ##
##########################################################################################
Vagrant.configure("2") do |config|
  config.vm.box = "debian/contrib-buster64"
  config.vm.box_check_update = true

  config.vm.network "forwarded_port", guest: 80, host: 80

  config.vm.synced_folder "./", "/vagrant_data"

  # config.vm.provider "virtualbox" do |vb|
  #   # Display the VirtualBox GUI when booting the machine
  #   vb.gui = true
  #
  #   # Customize the amount of memory on the VM:
  #   vb.memory = "1024"
  # end

  config.vm.provision "shell", inline: <<-SHELL
    apt-get update

    apt-get install -y \
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg-agent \
        software-properties-common

    curl -fsSL https://download.docker.com/linux/debian/gpg | sudo apt-key add -
    apt-key fingerprint 0EBFCD88

    add-apt-repository \
       "deb [arch=amd64] https://download.docker.com/linux/debian \
       $(lsb_release -cs) \
       stable"

    apt-get update
    apt-get install -y docker-ce docker-ce-cli containerd.io

    curl -L "https://github.com/docker/compose/releases/download/1.27.4/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    chmod +x /usr/local/bin/docker-compose
    ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose

    cd /vagrant_data/ && docker-compose up -d

  SHELL
end
