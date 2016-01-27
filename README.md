VoKS-shelter
============

This project constitutes the shelter part of the BComeSafe alarm system. Its purpose is to provide a webpage interface that lets the user:

* Receive audio and video streams from mobile and desktop clients
* Send and receive text messages to and from mobile and desktop clients
* Observe user positions on a map, select any group of users, and push messages to their devices
* Control the binary state of whether the police has been alerted
* Adjust relevant settings pertaining to the shelter and the system

Deploying the server
--------------------

Server runs on WAMP/LAMP stack. All configuration listed here is on Centos 7.0 OS.

###Webtatic repository###

    rpm -Uvh https://mirror.webtatic.com/yum/el6/latest.rpm
    yum update
    
### MySQL ###

    sudo yum install mysql mysql-server

### PHP ###
    sudo yum install php56w php56w-mysql php56w-pdo php56w-pecl-redis php56w-mbstring php56w-libxml php56w-ldap php56w-opcache php56w-pear php56-pecl-imagick php56w-mcrypt php56w-common
    

### NodeJS ###
remove old version, replace with your specific needs

    sudo yum remove nodejs
download the file

    sudo curl -O https://rpm.nodesource.com/pub_0.12/el/6/x86_64/nodejs-0.12.7-1nodesource.el6.x86_64.rpm
install the rpm

    sudo rpm -Uvh nodejs-0.12.7-1nodesource.el6.x86_64.rpm
remove downloaded file

    sudo rm nodejs-0.12.7-1nodesource.el6.x86_64.rpm
verify node works

    node -v

### Bower ###

    npm install bower -g

### Gulp ###

    npm install gulp -g

### Supervisor ###
Installation instructions located here: [http://supervisord.org/installing.html](http://supervisord.org/installing.html)
Composer
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer

### GIT ###

    yum install git

## Configuration ##
### Apache ###

Apache HTTP custom changes in configuration file located here: /etc/httpd/conf/httpd.conf

    DocumentRoot “/var/www/html/application/public”

    ##
    ## Server-Pool Size Regulation (MPM specific)
    ##
    
    # prefork MPM
    # StartServers: number of server processes to start
    # MinSpareServers: minimum number of server processes which are kept spare
    # MaxSpareServers: maximum number of server processes which are kept spare
    # ServerLimit: maximum value for MaxClients for the lifetime of the server
    # MaxClients: maximum number of server processes allowed to start
    # MaxRequestsPerChild: maximum number of requests a server process serves
    <IfModule prefork.c>
    StartServers   8
    MinSpareServers5
    MaxSpareServers   20
    ServerLimit  256
    MaxClients   256
    MaxRequestsPerChild  4000
    </IfModule>
    
    <VirtualHost *:443>
    ServerAdmin <email address to administrator email>
    DocumentRoot /var/www/html/application/public
    ServerName <URL address to VoKS server>
    SSLEngine on
    SSLCertificateFile /etc/httpd/ssl/<file name of VoKS SSL certificate>.crt
    SSLCertificateKeyFile /etc/httpd/ssl/<file name of VoKS SSL certificate>.key
    </VirtualHost>
    

### PHP ###
PHP custom changes in configuration file located here: /etc/httpd/conf/httpd.conf

    memory_limit  = 256M
    post_max_size  =  8M
    upload_max_size = 8M
    
### Supervisor ###
Append these lines at the bottom in configuration file located here: /etc/supervisord.conf

    [supervisord]
    logfile_maxbytes = 3145728
    logfile_backups = 1
    [include]
    files = /etc/supervisord/*.conf

Configuration file located here: /etc/supervisord/queue.conf
    [program:queue]
    command=php /var/www/html/application/artisan queue:listen --timeout=600 
    autostart=true  
    autorestart=true  
    stderr_logfile=/var/log/voks.queue.error.log
    stdout_logfile=/var/log/voks.queue.output.log
    
Restart supervisor service: 

    service supervisord restart


## Project source code deploy and framework installation ##

Get project source code from GIT repository:

    git clone <project git repository url> /var/www/html


Add recursive full read/write permissions on folder located here: /var/www/html/application/storage/
    chown –R apache:apache /var/www/html
    chmod 0540 –R /var/www/html/
    chmod 0755 –R /var/www/html/application/storage/
    
Create “uploads” folders by running command:

    mkdir -p /var/www/html/application/public/uploads/maps


Add recursive full read/write permissions on folder located here: /var/www/html/application/public/uploads/

    chmod 0755 –R /var/www/html/application/public/uploads/


Go to project folder located here: /var/www/html/application/
    cd /var/www/html/application


Create new file “.env” with lines inside:
    APP_ENV=local
    APP_DEBUG=false
    APP_KEY=
    
    DB_HOST=127.0.0.1
    DB_DATABASE=<voks database name>
    DB_USERNAME=<voks mysql user username>
    DB_PASSWORD=<voks mysql user password>
    
    CACHE_DRIVER=seconds
    SESSION_DRIVER=file
    QUEUE_DRIVER=database
    
    MAIL_DRIVER=mail
    MAIL_HOST=<mail server smtp>
    MAIL_PORT=<mail server smtp port>
    MAIL_USERNAME=
    MAIL_PASSWORD=
    
    SSL_ON=true
    PHP_WS_URL=<shelter domain name>
    WOL_PORT=7
    SHELTER_URL=<voks server ip address>
    
    AD_USERNAME=<ad server username>
    AD_GROUP=<ad parent crisis team group>
    AD_PASSWORD=<ad server pass>
    AD_ACCOUNT_SUFFIX=<account suffix>
    AD_HOST=
    AD_DN=
    AD_BIOS=
    AD_COMPANY_ID=
    
    ARUBA_ALE_URL=
    
    ARUBA_CLEARPASS_URL=
    ARUBA_CLEARPASS_USERNAME=
    ARUBA_CLEARPASS_PASSWORD=
    
    ARUBA_AIRWAVE_URL=
    ARUBA_AIRWAVE_USERNAME=
    ARUBA_AIRWAVE_PASSWORD=
    
    COORDINATES_TIME_INTERVAL=120
    COORDINATES_EXPIRATION_TIME=15
    
    UCP_USERNAME=<ucp username>
    UCP_PASSWORD=<ucp password>
    UCP_BASE_URI=<full ucp url to admin interface>
    UCP_API_USER=<ucp api username>
    UCP_API_PASS=<ucp api password>
    
Install Composer packages by running command:

    cd /var/www/html/application
    composer install

Install Database structure by running command:

    cd /var/www/html/application
    php artisan migrate
    
Install NPM packages by running command:

    cd /var/www/html/application 
    npm install
    npm install gulp
    
Install bower by running command:

    cd /var/www/html/application 
    bower install
    
Optimize and process assets by running command:

    cd /var/www/html/application 
    gulp --production
    
nano /var/www/html/websockets/configs/http.js

	(function(){
	    var config = {};
	    config.port = 9000;
	
	    config.secure = {};
	    config.secure.port = 9001;
	    config.secure.enabled = true;
	    config.secure.key = '<path to certificate .key file>';
	    config.secure.certificate = '<path to certificate .crt file>';
	
	    module.exports = config;
	})();



Install npm packages by running command:
	cd /var/www/html/websockets/
	npm install

### Cron tabs ###

Add as apache user: 

    crontab –u apache -e

Synchronizes schools and maps from Aruba. Every midnight.

	0 0 * * * php /var/www/html/application/artisan sync

Updates active aruba clients in Voks database. Every minute.

	* * * * * php /var/www/html/application/artisan aruba:sync:active 1>> /dev/null 2>&1

Synchronizes coordinates for all schools. Every 5 minutes.

	*/5 * * * * php /var/www/html/application/artisan aruba:sync:coordinates 1>> /dev/null 2>&1

Synchronizes coordinates for schools that triggered the alarm. Every minute.

	* * * * * php /var/www/html/application/artisan aruba:sync:coordinates --active=true 1>> /dev/null 2>&1
