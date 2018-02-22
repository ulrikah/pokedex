In the process of patching the web application, it may be useful to run it locally on your own computer. Below is a set of working instructions for deploying and running the web application on a Windows machine. It has been tested on a fresh install of Windows 10 on a VM. The setup will likely be similar with Mac OS X, but I don't have a ready made procedure for deploying on Mac.

1. Download php7.0.x, the thread safe version, from http://windows.php.net/download#php-7.0
2. Extract the downloaded archive to <your_php_directory>
3. Open PowerShell, and type

                setx path "%path%;<your_php_directory>"
                
Restart PowerShell.

4. Download and install XAMPP to c:\xampp (or another directory without special permissions). Download XAMPP from here: https://www.apachefriends.org/xampp-files/7.2.2/xampp-win32-7.2.2-0-VC15-installer.exe
5. Delete the contents of C:\xampp\htdocs
6. Open XAMPP. Open Apache > Config > httpd.conf. Replace 'DocumentRoot "C:/xampp/apache/htdocs"' with  'DocumentRoot "C:/xampp/apache/htdocs/public"'. Replace '<Directory "C:/xampp/htdocs">' with '<Directory "C:/xampp/htdocs/public">'
7. Download and install Visual C++ Redistributable for Visual Studio 2015. https://www.microsoft.com/en-us/download/details.aspx?id=48145
8. Download this GitHub repository to c:\xampp\htdocs, so that all files are extracted directly to htdocs/
9. Download and install Composer. Click next on everything. https://getcomposer.org/Composer-Setup.exe
10. In PowerShell, enter 

                cd "c:\xampp\htdocs\" 
                composer install
                
11. Open XAMPP. Start Apache and MySQL. Open MySQL -> Admin -> SQL. Copy and paste this into the text field and execute:

                create database inventory;
                use inventory;
                [all the contents of sql.txt]

12. Go to http://localhost:80 in your web browser and enjoy.


You may try using another web server software, but the Pokedex AS application is a bit picky with server software configuration. The $_GET variable needs to be populated with the URL string, so that when you enter `http://localhost/thisIsAURLString`, `var_dump($_GET);` must return `array(1) { ["url"]=> string(16) "thisIsAURLString" }`. XAMPP Apache behaves like this by default, so it's practical and easy to use.
