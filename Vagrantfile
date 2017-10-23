VAGRANTFILE_API_VERSION = '2'

Vagrant.require_version ">= 1.8.0"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    # Box
    config.vm.box = "kuikui/modern-lamp"
    config.vm.box_version = ">= 3.0.3"

    config.vm.provider "virtualbox" do |v|
      v.memory = 2048
      v.customize ['modifyvm', :id, '--cableconnected1', 'on']
      v.customize ['modifyvm', :id, '--natdnshostresolver1', 'on']
    end

    # Hostname
    # Sur chrome, les domaines en .dev provoquent parfois des ERR_ICANN_NAME_COLLISION
    config.vm.hostname = 'politime.loc'
    if Vagrant.has_plugin?('landrush')
        config.landrush.enabled            = true
        config.landrush.tld                = config.vm.hostname
        config.landrush.host               'politime.loc'
        config.landrush.guest_redirect_dns = false
    end

    # Network
    config.vm.network 'private_network', type: 'dhcp'
    # Necessaire sous windows : à décommenter
    # config.vm.network 'forwarded_port', guest: 80, host: 8888

    # SSH
    config.ssh.forward_agent = true

    # Folders
    # A commenter sous windows (le partage par defaut est deja au bon endroit donc pas besoin de le preciser
    # et les mount_options definies ci-dessous sont incompatibles)
    config.vm.synced_folder '.', '/vagrant', type: 'nfs', mount_options: ['nolock', 'actimeo=1', 'fsc']
end

local_vagrantfile = File.expand_path('../Vagrantfile.local', __FILE__)
load local_vagrantfile if File.exists?(local_vagrantfile)
