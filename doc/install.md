# Test Machine (Biggy)
Describing how to host the json to xml application to run properly on the server.
---

## Change user password
To change password, you should enter the below code in the ssh terminal.

<code> sudo passwd biggy </code>

Then you will be prompted to enter the old password, then the new password and then finally confirm password.


## Install git.
First of all, check and see if git is installed already.

<code>git --version</code>

If you receive output similar to the following, then Git is already installed. 

<code> git version 2.25.1 </code>

Then,

<code>sudo apt install git</code>


## Install zerotier-cli

## Install apache

<code> sudo apt update </code>

<code> sudo apt install apache2</code>

## Update etc/hosts to add url for gitea
## Join network
## Ask for access to the network
## Install ssh
## Install php
## Install ssh
## Install curl
## Install php-cli
## Clone repository
## Edit /etc/apache2/mods-enabled/dir.conf, move index.php to the beginning of the list
## Set up virtual host
## Create virtual host file and update it
## Enable domain
## Disable default configuration