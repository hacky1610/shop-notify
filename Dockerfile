# 
# Installs WordPress with wp-cli (wp.cli.org) installed
# Docker Hub: https://registry.hub.docker.com/u/conetix/wordpress-with-wp-cli/
# Github Repo: https://github.com/conetix/docker-wordpress-wp-cli

FROM conetix/wordpress-with-wp-cli

# Add sudo in order to run wp-cli as the www-data user 
RUN sudo apt update
RUN sudo apt install curl php7.0-cli php-mbstring git unzip

# Add WP-CLI 
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer


# Cleanup
RUN apt-get clean
#RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*