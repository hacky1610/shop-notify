#!/bin/bash 

docker stop woocomercenotice_wordpress_1
docker rm woocomercenotice_wordpress_1

docker-compose up -d
sleep 30
docker exec woocomercenotice_wordpress_1 wp core install --url=www.azurepipeline.hacky --title=hacky --admin_user=admin --admin_email=admin@hacky.de
docker exec woocomercenotice_wordpress_1 bash -c "cp -R /var/woocomerce-notice /var/www/html/wp-content/plugins"
docker exec woocomercenotice_wordpress_1 bash -c "sudo chmod -R 757 /var/www/html/wp-content/plugins/woocomerce-notice"
docker exec woocomercenotice_wordpress_1 bash -c "wp scaffold plugin-tests woocomerce-notice"
docker exec woocomercenotice_wordpress_1 sudo apt-get update
docker exec woocomercenotice_wordpress_1 sudo apt-get install -y curl php-cli php-mbstring git unzip composer
docker exec woocomercenotice_wordpress_1 composer global require phpunit/phpunit:5.*

