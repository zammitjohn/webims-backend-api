# API Reference

This is a full JSON API reference which interfaces to the extensive RIMS database.

## Users

| **Action**           | **Method** | **Headers**           | **Parameters**    | **Response**                                                                          | **URL**                                      |
|----------------------|------------|-----------------------|-------------------|---------------------------------------------------------------------------------------|----------------------------------------------|
| **delete**           | `POST`     | Auth-Key Content-Type | id                | status message                                                                        | \$host\$/rims/api/users/delete.php           |
| **login**            | `POST`     | Content-Type          | username password | status message id\* username\* firstname\* lastname\* *sessionId\** created\*         | \$host\$/rims/api/users/login.php            |
| **read**             | `GET`      | Auth-Key Content-Type |                   | id firstname lastname                                                                 | \$host\$/rims/api/users/read.php             |
| **validate_session** | `GET`      | Auth-Key Content-Type |                   | status message firstname\* lastname\* canUpdate\* canCreate\* canImport\* canDelete\* | \$host\$/rims/api/users/validate_session.php |

---

## Inventory

| **Action**      | **Method** | **Headers**           | **Body Parameters**                                                                       | **Response**                                                                                                                            | **URL**                                     |
|-----------------|------------|-----------------------|-------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------|
| **create**      | `POST`     | Auth-Key Content-Type | SKU type description qty qtyIn qtyOut supplier isGSM isUMTS isLTE ancillary toCheck notes | status message id\* SKU\* type\* description\* qty\* qtyIn\* qtyOut\* supplier\* isGSM\* isUMTS\* isLTE\* ancillary\* toCheck\* notes\* | \$host\$/rims/api/inventory/create.php      |
| **delete**      | `POST`     | Auth-Key Content-Type | id                                                                                        | status message                                                                                                                          | \$host\$/rims/api/inventory/delete.php      |
| **read_single** | `GET`      | Auth-Key Content-Type | id                                                                                        | id SKU type description qty qtyIn qtyOut supplier isGSM isUMTS isLTE ancillary toCheck notes inventoryDate lastChange                   | \$host\$/rims/api/inventory/read_single.php |
| **read**        | `GET`      | Auth-Key Content-Type | type\*\*\*                                                                                | id SKU type_id type_name type_altname description qty qtyIn qtyOut supplier inventoryDate                                               | \$host\$/rims/api/inventory/read.php        |
| **update**      | `POST`     | Auth-Key Content-Type | id description qty isGSM isUMTS isLTE ancillary toCheck notes                             | status message                                                                                                                          | \$host\$/rims/api/inventory/update.php      |

---

## Inventory Types

| **Action**      | **Method** | **Headers**           | **Parameters** | **Response**     | **URL**                                    |
|-----------------|------------|-----------------------|----------------|------------------|--------------------------------------------|
| **read**        | `GET`      | Auth-Key Content-Type | id\*\*\*       | id name alt_name | \$host\$/rims/api/inventory/types/read.php |

---

## Pools Types

| **Action**      | **Method** | **Headers**           | **Parameters** | **Response** | **URL**                                |
|-----------------|------------|-----------------------|----------------|--------------|----------------------------------------|
| **read**        | `GET`      | Auth-Key Content-Type | id\*\*\*       | id name qty  | \$host\$/rims/api/pools/types/read.php |

---

## Spares Types

| **Action**      | **Method** | **Headers**           | **Parameters** | **Response** | **URL**                                 |
|-----------------|------------|-----------------------|----------------|--------------|-----------------------------------------|
| **read**        | `GET`      | Auth-Key Content-Type | id\*\*\*       | id name      | \$host\$/rims/api/spares/types/read.php |

---

## Pools

| **Action**      | **Method** | **Headers**           | **Parameters**                                                              | **Response**                                                                                         | **URL**                                 |
|-----------------|------------|-----------------------|-----------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------|-----------------------------------------|
| **create**      | `POST`     | Auth-Key Content-Type | inventoryId\*\* tech\*\* pool name description qtyOrdered qtyStock notes    | status message id\* inventoryId\* tech\* pool\* name\* description\* qtyOrdered\* qtyStock\* notes\* | \$host\$/rims/api/pools/create.php      |
| **delete**      | `POST`     | Auth-Key Content-Type | id                                                                          | status message                                                                                       | \$host\$/rims/api/pools/delete.php      |
| **read_single** | `GET`      | Auth-Key Content-Type | id                                                                          | id inventoryId tech pool name description qtyOrdered qtyStock notes                                  | \$host\$/rims/api/pools/read_single.php |
| **read**        | `GET`      | Auth-Key Content-Type | tech\*\* pool                                                               | id tech_id tech_name pool name description qtyOrdered qtyStock notes                                 | \$host\$/rims/api/pools/read.php        |
| **update**      | `POST`     | Auth-Key Content-Type | id inventoryId\*\* tech\*\* pool name description qtyOrdered qtyStock notes | status message                                                                                       | \$host\$/rims/api/pools/update.php      |

---

## Registry

| **Action** | **Method** | **Headers**           | **Parameters**                             | **Response**                                             | **URL**                               |
|------------|------------|-----------------------|--------------------------------------------|----------------------------------------------------------|---------------------------------------|
| **create** | `POST`     | Auth-Key Content-Type | inventoryId\*\* serialNumber datePurchased | status message id inventoryId serialNumber datePurchased | \$host\$/rims/api/registry/create.php |
| **delete** | `POST`     | Auth-Key Content-Type | id                                         | status message                                           | \$host\$/rims/api/registry/delete.php |
| **read**   | `GET`      | Auth-Key Content-Type | inventoryId\*\*                            | id inventoryId serialNumber datePurchased                | \$host\$/rims/api/registry/read.php   |

---

## Reports

| **Action**      | **Method** | **Headers**           | **Parameters**                                                                                                                                                         | **Response**                                                                                                                                                                                        | **URL**                                   |
|-----------------|------------|-----------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|-------------------------------------------|
| **create**      | `POST`     | Auth-Key Content-Type | inventoryId\*\* ticketNo name reportNo requestedBy\*\* faultySN\*\* replacementSN\*\* dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes | status message inventoryId\* ticketNo\* name\* reportNo\* requestedBy\* faultySN\* replacementSN\* dateRequested\* dateLeavingRBS\* dateDispatched\* dateReturned\* AWB\* AWBreturn\* RMA\* notes\* | \$host\$/rims/api/reports/create.php      |
| **delete**      | `POST`     | Auth-Key Content-Type | id                                                                                                                                                                     | status message                                                                                                                                                                                      | \$host\$/rims/api/reports/delete.php      |
| **read_single** | `GET`      | Auth-Key Content-Type | id                                                                                                                                                                     | id inventoryId ticketNo name reportNo requestedBy faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes                                           | \$host\$/rims/api/reports/read_single.php |
| **read**        | `GET`      | Auth-Key Content-Type |                                                                                                                                                                        | id inventoryId ticketNo name reportNo requestedBy faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes                                           | \$host\$/rims/api/reports/read.php        |
| **update**      | `POST`     | Auth-Key Content-Type | inventoryId\*\* ticketNo name reportNo requestedBy\*\* faultySN\*\* replacementSN\*\* dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes | status message                                                                                                                                                                                      | \$host\$/rims/api/reports/update.php      |

---

## Spares

| **Action**      | **Method** | **Headers**           | **Parameters**                                         | **Response**                                                                | **URL**                                  |
|-----------------|------------|-----------------------|--------------------------------------------------------|-----------------------------------------------------------------------------|------------------------------------------|
| **create**      | `POST`     | Auth-Key Content-Type | inventoryId\*\* type\*\* name description qty notes    | status message id\* inventoryId\* type\* name\* description\* qty\* notes\* | \$host\$/rims/api/spares/create.php      |
| **delete**      | `POST`     | Auth-Key Content-Type | id                                                     | status message                                                              | \$host\$/rims/api/spares/delete.php      |
| **read_single** | `GET`      | Auth-Key Content-Type | id                                                     | id inventoryId type name description qty notes                              | \$host\$/rims/api/spares/read_single.php |
| **read**        | `GET`      | Auth-Key Content-Type | type\*\*                                               | id inventoryId type_id type_name name description qty notes                 | \$host\$/rims/api/spares/read.php        |
| **update**      | `POST`     | Auth-Key Content-Type | id inventoryId\*\* type\*\* name description qty notes | status message                                                              | \$host\$/rims/api/spares/update.php      |

---

\* included in response only if query is successful.

\*\* referenced id.

\*\*\* optional parameter.