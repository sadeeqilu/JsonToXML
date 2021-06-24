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
## Install ssh

The procedure to install a ssh server in Ubuntu Linux is as follows:

1. Open the terminal application for Ubuntu desktop.
For remote Ubuntu server you must use BMC or KVM or IPMI tool to get console access.

2. Type <code>sudo apt-get install openssh-server</code> .

3. Enable the ssh service by typing <code>sudo systemctl enable ssh</code> .

4. Start the ssh service by typing <code>sudo systemctl start ssh</code> .

5. Test it by login into the system using <code>ssh user@server-name</code>


## Install curl
<code>sudo apt install curl</code>

## Install php-cli

<code>sudo apt install php-cli</code>

<code>sudo apt install php libapache2-mod-php php-mysql</code>

## Clone repository
Clone the repo using ssh, generate ssh keys and deploy to the repo.
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
<code> sudo mkdir /var/www/your_domain </code>

 Next, assign ownership of the directory with the $USER environment variable:

<code> sudo chown -R $USER:$USER /var/www/your_domain </code>

The permissions of your web roots should be correct if you haven’t modified your unmask value, but you can make sure by typing:

<code>sudo chmod -R 755 /var/www/your_domain
</code>

Next, create a sample index.php page using nano or your favorite editor:

<code>nano /var/www/biggyjsontoxml/index.php </code>

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

<code>sudo a2ensite jsontoxml.conf </code>

Disable the default site defined in 000-default.conf:


<code>sudo a2dissite 000-default.conf</code>

Restart Apache to implement your changes:

<code>sudo systemctl restart apache2
</code>

