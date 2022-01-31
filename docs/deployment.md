# Deployment
This page describes how to deploy webims-backend-api on a PHP server with Apache and MySQL.

## Preparation
- Download latest version of [webims-backend-api](https://github.com/zammitjohn/webims-backend-api/releases) (source code and database file).
- Valid SSL keys and certificates.

## Steps
1. Stop Apache and MySQL services.
2. Copy webims-backend-api ```api``` folder to the Apache server root directory. ```DocumentRoot``` directory specfied in the httpd.conf file.
3. Open PHP configration file \php\php.ini and uncomment ```extension=ldap```.
4. Copy Certificate Signing Request, Key, Certificate files to \apache\conf\ssl.csr, ssl.key and ssl.crt accordingly.
5. Deploy database with the database file downloaded. Connect to database in [database.php](../api/config/database.php).
6. Modify LDAP server connection in [login.php](../api/users/login.php).
7. Start Apache and MySQL services.
8. Confirm webserver is operating with your browser.