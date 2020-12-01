# API Reference

This is a full JSON API reference which interfaces to the extensive RIMS database.

## Users

| **Action**           | **Method** | **Headers**           | **Parameters**    | **Response**                                                              | **URL**                                      |
|----------------------|------------|-----------------------|-------------------|---------------------------------------------------------------------------|----------------------------------------------|
| **delete**           | `POST`     | Auth-Key Content-Type | id                | status message                                                            | \$host\$/rims/api/users/delete.php           |
| **login**            | `POST`     | Content-Type          | username password | status message id username firstname lastname *sessionId* created         | \$host\$/rims/api/users/login.php            |
| **read**             | `GET`      | Auth-Key Content-Type |                   | id firstname lastname lastLogin                                           | \$host\$/rims/api/users/read.php             |
| **validate_session** | `GET`      | Auth-Key Content-Type |                   | status message firstname lastname canUpdate canCreate canImport canDelete | \$host\$/rims/api/users/validate_session.php |

---

## Inventory

| **Action**      | **Method** | **Headers**           | **Body Parameters**                                                                                | **Response**                                                                                                                                                | **URL**                                     |
|-----------------|------------|-----------------------|----------------------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------|
| **create**      | `POST`     | Auth-Key Content-Type | SKU type category description qty qtyIn qtyOut supplier isGSM isUMTS isLTE ancillary toCheck notes | status message id SKU type category description qty qtyIn qtyOut supplier isGSM isUMTS isLTE ancillary toCheck notes          | \$host\$/rims/api/inventory/create.php      |
| **delete**      | `POST`     | Auth-Key Content-Type | id                                                                                                 | status message                                                                                                                                              | \$host\$/rims/api/inventory/delete.php      |
| **read_single** | `GET`      | Auth-Key Content-Type | id                                                                                                 | id SKU type category description qty qtyIn qtyOut supplier isGSM isUMTS isLTE ancillary toCheck notes inventoryDate lastChange                              | \$host\$/rims/api/inventory/read_single.php |
| **read**        | `GET`      | Auth-Key Content-Type | type or category                                                                                   | id SKU type_id type_name type_altname category_id category_name description qty qtyIn qtyOut supplier inventoryDate                                         | \$host\$/rims/api/inventory/read.php        |
| **update**      | `POST`     | Auth-Key Content-Type | id description qty isGSM isUMTS isLTE ancillary toCheck notes                                      | status message                                                                                                                                              | \$host\$/rims/api/inventory/update.php      |

---

## Inventory Categories

| **Action** | **Method** | **Headers**           | **Parameters** | **Response**          | **URL**                                         |
|------------|------------|-----------------------|----------------|-----------------------|-------------------------------------------------|
| **read**   | `GET`      | Auth-Key Content-Type | id             | id name supportImport | \$host\$/rims/api/inventory/categories/read.php |

---

## Inventory Types

| **Action** | **Method** | **Headers**           | **Parameters** | **Response**                                 | **URL**                                    |
|------------|------------|-----------------------|----------------|----------------------------------------------|--------------------------------------------|
| **read**   | `GET`      | Auth-Key Content-Type | id or category | id name alt_name type_category category_name | \$host\$/rims/api/inventory/types/read.php |

---

## Pools

| **Action**      | **Method** | **Headers**           | **Parameters**                                                      | **Response**                                                                       | **URL**                                 |
|-----------------|------------|-----------------------|---------------------------------------------------------------------|------------------------------------------------------------------------------------|-----------------------------------------|
| **create**      | `POST`     | Auth-Key Content-Type | inventoryId type pool name description qtyOrdered qtyStock notes    | status message id inventoryId type pool name description qtyOrdered qtyStock notes | \$host\$/rims/api/pools/create.php      |
| **delete**      | `POST`     | Auth-Key Content-Type | id                                                                  | status message                                                                     | \$host\$/rims/api/pools/delete.php      |
| **read_single** | `GET`      | Auth-Key Content-Type | id                                                                  | id inventoryId type pool name description qtyOrdered qtyStock notes                | \$host\$/rims/api/pools/read_single.php |
| **read**        | `GET`      | Auth-Key Content-Type | type pool                                                           | id tech_id tech_name pool name description qtyOrdered qtyStock notes               | \$host\$/rims/api/pools/read.php        |
| **update**      | `POST`     | Auth-Key Content-Type | id inventoryId type pool name description qtyOrdered qtyStock notes | status message                                                                     | \$host\$/rims/api/pools/update.php      |

---

## Pools Types

| **Action** | **Method** | **Headers**           | **Parameters** | **Response** | **URL**                                |
|------------|------------|-----------------------|----------------|--------------|----------------------------------------|
| **read**   | `GET`      | Auth-Key Content-Type | id             | id name qty  | \$host\$/rims/api/pools/types/read.php |

---

## Registry

| **Action** | **Method** | **Headers**           | **Parameters**                         | **Response**                                             | **URL**                               |
|------------|------------|-----------------------|----------------------------------------|----------------------------------------------------------|---------------------------------------|
| **create** | `POST`     | Auth-Key Content-Type | inventoryId serialNumber datePurchased | status message id inventoryId serialNumber datePurchased | \$host\$/rims/api/registry/create.php |
| **delete** | `POST`     | Auth-Key Content-Type | id                                     | status message                                           | \$host\$/rims/api/registry/delete.php |
| **read**   | `GET`      | Auth-Key Content-Type | inventoryId                            | id inventoryId serialNumber datePurchased                | \$host\$/rims/api/registry/read.php   |

---

## Reports

| **Action**        | **Method** | **Headers**           | **Parameters**                                                                                                                                                | **Response**                                                                                                                                                                 | **URL**                                     |
|-------------------|------------|-----------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------|
| **create**        | `POST`     | Auth-Key Content-Type | inventoryId ticketNo name description reportNo userId faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes | status message inventoryId ticketNo name description reportNo userId faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes | \$host\$/rims/api/reports/create.php        |
| **read_single**   | `GET`      | Auth-Key Content-Type | id                                                                                                                                                            | id inventoryId ticketNo name description reportNo userId faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes isClosed    | \$host\$/rims/api/reports/read_single.php   |
| **read**          | `GET`      | Auth-Key Content-Type | userId                                                                                                                                                        | id inventoryId ticketNo name description reportNo userId faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes (isClosed)  | \$host\$/rims/api/reports/read.php          |
| **update**        | `POST`     | Auth-Key Content-Type | inventoryId ticketNo name description reportNo userId faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes | status message                                                                                                                                                               | \$host\$/rims/api/reports/update.php        |

---

## Reports Comments

| **Action** | **Method** | **Headers**           | **Parameters**       | **Response**                                  | **URL**                                       |
|------------|------------|-----------------------|----------------------|-----------------------------------------------|-----------------------------------------------|
| **read**   | `GET`      | Auth-Key Content-Type | reportId             | id reportId firstname lastname text timestamp | \$host\$/rims/api/reports/comments/read.php   |
| **create** | `POST`     | Auth-Key Content-Type | reportId userId text | status message id reportId userId text        | \$host\$/rims/api/reports/comments/create.php |

---

## Collections

| **Action**      | **Method** | **Headers**           | **Parameters**                                        | **Response**                                                                   | **URL**                                       |
|-----------------|------------|-----------------------|-------------------------------------------------------|--------------------------------------------------------------------------------|-----------------------------------------------|
| **create**      | `POST`     | Auth-Key Content-Type | inventoryId type name description qty notes userId    | status message id inventoryId type name description qty notes                  | \$host\$/rims/api/collections/create.php      |
| **delete**      | `POST`     | Auth-Key Content-Type | id                                                    | status message                                                                 | \$host\$/rims/api/collections/delete.php      |
| **read_single** | `GET`      | Auth-Key Content-Type | id                                                    | id inventoryId type name description qty notes                                 | \$host\$/rims/api/collections/read_single.php |
| **read**        | `GET`      | Auth-Key Content-Type | type inventoryId                                      | id inventoryId type_id type_name name description qty notes firstname lastname | \$host\$/rims/api/collections/read.php        |
| **update**      | `POST`     | Auth-Key Content-Type | id inventoryId type name description qty notes userId | status message                                                                 | \$host\$/rims/api/collections/update.php      |

---

## Collections Types

| **Action** | **Method** | **Headers**           | **Parameters** | **Response** | **URL**                                        |
|------------|------------|-----------------------|----------------|--------------|------------------------------------------------|
| **create** | `POST`     | Auth-Key Content-Type | name userId    | name         | \$host\$/rims/api/collections/types/create.php |
| **read**   | `GET`      | Auth-Key Content-Type | id userId      | id name      | \$host\$/rims/api/collections/types/read.php   |

---