#! /usr/bin/env bash
now=$(date +"%m_%d_%Y")
rm -f /etc/ssh/sshd_config.d/60-cloudimg-settings.conf
cp /etc/ssh/sshd_config /etc/ssh/sshd_config_$now.backup
sed -i -e 's/#PasswordAuthentication yes/PasswordAuthentication yes/g' /etc/ssh/sshd_config
systemctl restart sshd
cat <<EOT >> /home/vagrant/.bashrc
export PS1="[\[\e[1;31m\]\u\[\e[m\]@\[\e[1;32m\]\h\[\e[m\]: \[\e[1;36m\]\w\[\e[m\]]#"
EOT

source /home/vagrant/.bashrc