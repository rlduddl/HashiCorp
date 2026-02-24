#! /usr/bin/env bash

now=$(date +"%m_%d_%Y")
cp /etc/ssh/sshd_config /etc/ssh/sshd_config_$now.backup
sed -i -e 's/PasswordAuthentication no/PasswordAuthentication yes/g' /etc/ssh/sshd_config
systemctl restart sshd
cat <<EOT > /etc/profile.d/prompt.sh
export PS1="[\[\e[1;31m\]\u\[\e[m\]@\[\e[1;32m\]\h\[\e[m\]: \[\e[1;36m\]\w\[\e[m\]]#"
EOT
source /etc/profile