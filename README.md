# my-blog
My fifth OpenClassRooms Project with PHP

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/2d39dd182b99412597f9118c7f04e387)](https://www.codacy.com/app/kevinmulot/my-blog?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=kevinmulot/my-blog&amp;utm_campaign=Badge_Grade)
<a href="https://codeclimate.com/github/kevinmulot/my-blog/maintainability"><img src="https://api.codeclimate.com/v1/badges/f7932e8cad48b61dcbf6/maintainability" /></a>
![OenClassRooms](https://img.shields.io/badge/OpenClassRooms-DA_PHP/SF-blue.svg)
![Project](https://img.shields.io/badge/Project-5-blue.svg)

## Download


`git clone https://github.com/kevinmulot/my-blog.git`  
  
[![Repo Size](https://img.shields.io/github/repo-size/kevinmulot/my-blog?label=Repo+Size)](https://github.com/kevinmulot/my-blog/tree/master)


## Installation

**Step 1**

Installation of composer => [https://getcomposer.org/download/](https://getcomposer.org/download/)

Once composer is installed, launch terminal command : "*composer* *dump-autoload* *-o*" in order to load all classes.

**Step 2**

Import the *blog.sql* file located in the */database/* folder to your database.

**Step 3**

Rename the file located in the */config/* folder.

*config.dev.php* to *config.php*

Edit this file and fill the empty fields with your database connection information.

**Step 4**

Edit the HomeController.php file located in */src/Controller*.

Read the comments and indicate your email address in order to receive emails sent via the Blog.

**YOU ARE ALL SET !**

Connect to the blog as an Admin for the first time :

- **email** : admin@blog.com
- **password** : root

You can edit this information via the profile page.

**ENJOY !**
