Introduction
============

**RIMS** (**R**adio **I**nventory **M**anagement **S**ystem) is a modern web-based inventory, fault report and warehouse management solution for Telco providers. RIMS allows you to stay on top of your inventory levels and value across multiple locations, as well as multiple projects - pools and spares. The interface makes it easy to search and filter your inventory and to assess the state of your current stock. It also facilitates tracking of item fault report progresses for better recordkeeping.

Key features:
- Item bundling and registration
- Easy sorting and filtering
- Batch comma-separated values (CSV) file imports
- Multi-warehouse management
- Convenient fault reporting options
- LDAP integration

RIMS is based on **[AdminLTE.io](https://adminlte.io)**, which is a fully responsive administration template based on the **[Bootstrap 4](https://getbootstrap.com)** framework.
Highly customizable and easy to use. Fits many screen resolutions from small mobile devices to large desktops.

![RIMS](dist/img/RIMS.png)

#### Download:

Download from [Github releases](https://github.com/zammitjohn/RIMS/releases).

Documentation
-------------
- [API Reference](docs/api.md)

Configuration
---------------
1. Deploy DB using rims_db.sql from the corresponding release
2. Setup and connect DB in [database.php](api/config/database.php)
3. Modify LDAP server connection in [login.php](api/users/login.php)    

Browser Support
---------------
- Firefox
- Chrome
- Edge
- Safari
- Opera

Contribution
------------
Contribution are always **welcome and recommended**! Here is how:

- Fork the repository ([here is the guide](https://help.github.com/articles/fork-a-repo/)).
- Clone to your machine ```git clone https://github.com/zammitjohn/RIMS.git```
- Create a new branch
- Make your changes
- Create a pull request

Change log
----------
Visit the [releases](https://github.com/zammitjohn/RIMS/releases) page to view the changelog