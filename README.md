# COLORS Lab (for COP 4331C)

Colors web application based off of the assignment specifications. It consists of a basic login page alongside a main page which allows users to input color names and upload them to their profile, then be able to search for them via the search box.

To use the app, visit my website http://steffpcop4331.com or follow the setup instructions to use it on a remote host.

The program was built within a LAMP stack droplet on a Apache environment hosted on DigitalOcean alongside an instance of MySQL running. 

The setup process can be done via an ssh client such as Putty (www.chiark.greenend.org.uk/~sgtatham/putty/latest.html), though ssh file management programs such as Filezilla (filezilla-project.org/download.php?type=client#close) are also helpful.
(Note: Any instance of "placeholder" in the instructions can be replaced with any name/credentials.)

1. Register a LAMP droplet on DigitalOcean via https://marketplace.digitalocean.com/apps/lamp, and register a root password within the creation page.
   
2. Log on to the root with the credentials via the DigitalOcean web console or Putty. Then, use Filezilla to find the MySQL password (Directory location C:/root/).
   
3. Use command 
```bash
mysql -u root -p 
```
and log on with the password. Then, proceed to use 

```mysql
create database COP4331;
use COP4331;
```
And then create the login and color tables via 
```mysql
CREATE TABLE `COP4331`.`Users` ( `ID` INT NOT NULL AUTO_INCREMENT , `FirstName` VARCHAR(50) NOT NULL DEFAULT '' , `LastName` VARCHAR(50) NOT NULL DEFAULT '' , `Login` VARCHAR(50) NOT NULL DEFAULT '' , `Password` VARCHAR(50) NOT NULL DEFAULT '' , PRIMARY KEY (`ID`)) ENGINE = InnoDB;

insert into Users (FirstName,LastName,Login,Password) VALUES ('placeholder','placeholder','placeholder','placeholder');
```

```mysql
CREATE TABLE `COP4331`.`Colors` ( `ID` INT NOT NULL AUTO_INCREMENT , `Name` VARCHAR(50) NOT NULL DEFAULT '' , `UserID` INT NOT NULL DEFAULT '0' , PRIMARY KEY (`ID`)) ENGINE = InnoDB;

insert into Colors (Name,UserID) VALUES ('placeholder',1);
```
The insert functions can use any data within the same format.


4. Run the following command 
```mysql
create user 'placeholder' identified by 'placeholder';
grant all privileges on COP4331.* to 'placeholder'@'%';
```
in order to create a user for the PHP files to access.

5. With Filezilla, insert the contents of this repository into the server root directory (C:/var/www/html)
   
6. Use a Javascript enabled web browser to access the website using input information.

To use the application, use the username and password registered within the database to access the main menu. Then, enter the string you wish to search for into the search box for results to show. To add a color, write out the full color name and use the Add button. The new color will be accessible immediately via search.

AI Disclosure:
No AI tools of any kind were used during the creation of this assignment.

Limitations:
The program was only tested on a Apache environment hosted on DigitalOcean webservices, there is potential failure in other environments. HTML file may fail to run on Javascript unenabled browsers. Program was tested primarily on Google Chrome. User is assumed to be using the given said environments whilst deploying the application.
