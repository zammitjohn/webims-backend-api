# WebIMS Deployment
This page describes how to deploy WebIMS on a PHP server with Apache and MySQL.

## Preparation
- Download latest version of [WebIMS](https://github.com/zammitjohn/WebIMS/releases) (source code and database file).
- Prepare valid SSL keys and certificates.

## Steps
1. Stop Apache and MySQL services.
3. Copy WebIMS files to the Apache htdocs root server directory.
4. Open PHP configration file \php\php.ini and uncomment ```extension=ldap```.
5. Copy Certificate Signing Request, Key, Certificate files to \apache\conf\ssl.csr, ssl.key and ssl.crt accordingly.
6. Deploy database using the database file downloaded.
7. Setup and connect database in [database.php](../api/config/database.php).
8. Modify LDAP server connection in [login.php](../api/users/login.php).
9. Modify branding logo in [img folder](../dist/img).
10. Start Apache and MySQL services.
11. Confirm webserver is operating with your browser.