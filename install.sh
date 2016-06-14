#!/bin/bash
sudo apt-get update
sudo apt-get install curl php5-cli git
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
sudo apt-get install php5-curl
cd /var/www
cd html
cd webstore
composer install
