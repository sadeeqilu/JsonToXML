# Test Machine (Biggy)
Describing how to host the json to xml application to run properly on the server.
---

## Install git.
First of all, check and see if git is installed already.

<code>git --version</code>

If you receive output similar to the following, then Git is already installed. 

<code> git version 2.25.1 </code>

Then,

<code>sudo apt install git</code>


## Install apache

<code> sudo apt update </code>

<code> sudo apt install apache2</code>

## Update etc/hosts to add url for gitea
Open /etc/hosts and 
add <code>10.235.153.44  gitea</code>

## Join network
<code> zerotier-cli join `<network_id>` </code>
## Ask for access to the network

Contact admin to accept your request to join network.



## Install curl
<code>sudo apt install curl</code>

## Install php-cli

<code>sudo apt install php-cli</code>

<code>sudo apt install php libapache2-mod-php php-mysql</code>

## Install composer

* Now that we have php cli installed on our machine, we can download the composer installer with:

    <code>php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" </code>

* Next, we need to verify the data integrity of the script by comparing the script SHA-384 hash with the latest installer hash found on the Composer Public Keys / Signatures page.
We will use the following wget command to download the expected signature of the latest Composer installer from the Composer’s Github page and store it in a variable named HASH:

    <code> HASH="$(wget -q -O - https://composer.github.io/installer.sig)" </code>

    Now run the following command to verify that the installation script is not corrupted:

    <code> php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" </code>

    If the hashes match, you will see the following output:

    <code> Installer verified </code>

* The following command will install Composer in the /usr/local/bin directory:

    <code>sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer</code>
## Clone repository
Clone the JsonToXML repo using ssh, generate ssh keys and deploy to the repo.

<code>git clone git@gitea:biggy/JsonToXML.git </code>

Enter the cloned repo using:

<code> cd JsonToXML </code>

Run composer install to install the dependencies of the application using:

<code> composer install </code>

## Edit /etc/apache2/mods-enabled/dir.conf, move index.php to the beginning of the list
Type this to open and edit the file.

<code>sudo nano /etc/apache2/mods-enabled/dir.conf</code>

The contents of the file will look like this: 
<code>

    DirectoryIndex index.html index.cgi index.pl index.php 

    index.xhtml index.htm

</code>

Move the PHP index file (highlighted above) to the first position after the DirectoryIndex specification, like this:

<code>


    DirectoryIndex 
    index.php index.html index.cgi index.pl 
    
    index.xhtml index.htm


</code>

After this, restart the Apache web server in order for your changes to be recognized. Do this by typing this:

<code>sudo systemctl restart apache2</code>

## Set up virtual host
<code> sudo mkdir /JsonToXML </code>

 Next, assign ownership of the directory with the $USER environment variable:

<code> sudo chown -R $USER:$USER /JsonToXML </code>

The permissions of your web roots should be correct if you haven’t modified your unmask value, but you can make sure by typing:

<code>sudo chmod -R 755 /JsonToXML
</code>

Next, create a sample index.php page using nano or your favorite editor:

<code>nano /JsonToXML/index.php </code>

## Create virtual host file and update it

In order for Apache to serve this content, it’s necessary to create a virtual host file with the correct directives. Instead of modifying the default configuration file located at /etc/apache2/sites-available/000-default.conf directly, let’s make a new one at /etc/apache2/sites-available/biggyjsontoxml.conf:

<code> sudo nano /etc/apache2/sites-available/biggyjsontoxml.conf </code>

Paste in the following configuration block, which is similar to the default, but updated for our new directory and domain name:

<code>

`<VirtualHost *:80>`

    ServerAdmin webmaster@localhost
    ServerName biggyjsontoxml
    ServerAlias www.biggyjsontoxml.com
    DocumentRoot /var/www/biggyjsontoxml
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

`</VirtualHost>`
</code>

Notice that we’ve updated the DocumentRoot to our new directory and ServerAdmin to an email that the your_domain site administrator can access. We’ve also added two directives: ServerName, which establishes the base domain that should match for this virtual host definition, and ServerAlias, which defines further names that should match as if they were the base name.

Save and close the file when you are finished.

## Enable domain

Let’s enable the file with the a2ensite tool:

<code>sudo a2ensite /config/jsontoxml.conf </code>

Disable the default site defined in 000-default.conf:


<code>sudo a2dissite 000-default.conf</code>

Restart Apache to implement your changes:

<code>sudo systemctl restart apache2
</code>

