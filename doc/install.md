# Test Machine (Biggy)
Describing how to host the json to xml application to run properly on the server.
---

## Install git.
First of all, check and see if git is installed already.

<code>git --version</code>

If you receive output similar to the following, Git is already installed. 

<code> git version 2.25.1 </code>

Otherwise,

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
Navigate to <code>/usr/local/src</code> using <code> cd /usr/local/src </code>

Clone the JsonToXML repo using ssh, generate ssh keys and deploy to the repo.

<code>git clone git@gitea:biggy/JsonToXML.git </code>

Enter the cloned repo using:

<code> cd JsonToXML </code>

Run composer install to install the dependencies of the application using:

<code> composer install </code>

## Create virtual host file and update it

Next, assign ownership of the directory with the $USER environment variable:

<code> sudo chown -R $USER:$USER usr/local/src/JsonToXML </code>

The permissions of your web roots should be correct if you haven’t modified your unmask value, but you can make sure by typing:

<code>sudo chmod -R 755 usr/local/src/JsonToXML
</code>


In order for Apache to serve this content, it’s necessary to create a virtual host file with the correct directives. Instead of modifying the default configuration file located at /etc/apache2/sites-available/000-default.conf directly, let’s make a symbolic link to use our file at /config/jsontoxml.conf and /config/testjsontoxml.conf.

<code> sudo ln -s /usr/local/src/JsonToXML/config/jsontoxml.conf /etc/apache2/sites-available/biggyjsontoxml.conf </code>

<code> sudo ln -s /usr/local/src/JsonToXML/config/testjsontoxml.conf /etc/apache2/sites-available/testbiggyjsontoxml.conf </code>

## Enable domain

Let’s enable the files with the a2ensite tool:

<code>sudo a2ensite biggyjsontoxml.conf </code>

<code>sudo a2ensite testbiggyjsontoxml.conf </code>

Disable the default site defined in 000-default.conf:


<code>sudo a2dissite 000-default.conf</code>

Restart Apache to implement your changes:

<code>sudo systemctl restart apache2
</code>

## Update /etc/hosts

Add the two domains to etc/hosts, open the file using 

<code> sudo nano /etc/hosts </code>

The file will look like this :

<code>

    127.0.1.1 1-biggy-dev-abcvyz 1-biggy-dev-abcvyz
    127.0.0.1 localhost
    10.235.153.44   gitea

    # The following lines are desirable for IPv6 capable hosts`
    ::1 ip6-localhost ip6-loopback
    fe00::0 ip6-localnet
    ff00::0 ip6-mcastprefix
    ff02::1 ip6-allnodes
    ff02::2 ip6-allrouters
    ff02::3 ip6-allhosts

</code>


Update the file and add the urls for test and the application.

<code>

    127.0.1.1 1-biggy-dev-abcvyz 1-biggy-dev-abcvyz
    127.0.0.1 localhost
    10.235.153.44   gitea
    206.189.122.11 biggyjsontoxml
    206.189.122.11 testbiggyjsontoxml



    # The following lines are desirable for IPv6 capable hosts
    ::1 ip6-localhost ip6-loopback
    fe00::0 ip6-localnet
    ff00::0 ip6-mcastprefix
    ff02::1 ip6-allnodes
    ff02::2 ip6-allrouters
    ff02::3 ip6-allhosts

</code>
