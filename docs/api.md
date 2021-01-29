# API Reference

This is a full JSON API reference which interfaces to the extensive WebIMS database. To authenticate against the RIMS API, you'll need to pass a UserSession cookie with each request.

The UserSession cookie contains a base64 value of the following JSON encoded array:

{
  "FullName": "<Name> <Surname>",
  "SessionId": "<sessionId>"
}


## Users

| **Action**           | **Method** | **Parameters**    | **Response**                                                              | **URL**                     |
|----------------------|------------|-------------------|---------------------------------------------------------------------------|-----------------------------|
| **login**            | `POST`     | username password | status message id username firstname lastname sessionId created           | /api/users/login            |
| **logout**           | `POST`     |                   | status message                                                            | /api/users/logout           |
| **read**             | `GET`      |                   | id firstname lastname lastLogin                                           | /api/users/read             |
| **validate_session** | `GET`      |                   | status message firstname lastname canUpdate canCreate canImport canDelete | /api/users/validate_session |

---

## Inventory

| **Action**      | **Method** | **Body Parameters**                                                                                | **Response**                                                                                                                                  | **URL**                    |
|-----------------|------------|----------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------|----------------------------|
| **create**      | `POST`     | SKU type category description qty qtyIn qtyOut supplier isGSM isUMTS isLTE ancillary toCheck notes | status message id SKU type category description qty qtyIn qtyOut supplier isGSM isUMTS isLTE ancillary toCheck notes                          | /api/inventory/create      |
| **delete**      | `POST`     | id                                                                                                 | status message                                                                                                                                | /api/inventory/delete      |
| **read_single** | `GET`      | id                                                                                                 | id SKU type category description qty qtyIn qtyOut supplier isGSM isUMTS isLTE ancillary toCheck notes inventoryDate lastChange                | /api/inventory/read_single |
| **read**        | `GET`      | type or category                                                                                   | id SKU type_id type_name type_altname category_id category_name description qty qtyIn qtyOut qty_projects_allocated supplier inventoryDate    | /api/inventory/read        |
| **update**      | `POST`     | id description qty isGSM isUMTS isLTE ancillary toCheck notes                                      | status message                                                                                                                                | /api/inventory/update      |

---

## Inventory Categories

| **Action** | **Method** | **Parameters** | **Response**          | **URL**                        |
|------------|------------|----------------|-----------------------|--------------------------------|
| **read**   | `GET`      | id             | id name supportImport | /api/inventory/categories/read |

---

## Inventory Types

| **Action** | **Method** | **Parameters** | **Response**                                 | **URL**                   |
|------------|------------|----------------|----------------------------------------------|---------------------------|
| **read**   | `GET`      | id or category | id name alt_name type_category category_name | /api/inventory/types/read |

---

## Registry

| **Action** | **Method** | **Parameters**                         | **Response**                                             | **URL**              |
|------------|------------|----------------------------------------|----------------------------------------------------------|----------------------|
| **create** | `POST`     | inventoryId serialNumber datePurchased | status message id inventoryId serialNumber datePurchased | /api/registry/create |
| **delete** | `POST`     | id                                     | status message                                           | /api/registry/delete |
| **read**   | `GET`      | inventoryId                            | id inventoryId serialNumber datePurchased state          | /api/registry/read   |

---

## Reports

| **Action**            | **Method** | **Parameters**                                                                                                                                                | **Response**                                                                                                                                                                 | **URL**                        |
|-----------------------|------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|--------------------------------|
| **create**            | `POST`     | inventoryId ticketNo name description reportNo userId faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes | status message inventoryId ticketNo name description reportNo userId faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes | /api/reports/create            |
| **read_single**       | `GET`      | id                                                                                                                                                            | id inventoryId ticketNo name description reportNo userId faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes isClosed    | /api/reports/read_single       |
| **read**              | `GET`      |                                                                                                                                                               | id inventoryId ticketNo name description reportNo userId faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes (isClosed)  | /api/reports/read              |
| **read_myreports**    | `GET`      |                                                                                                                                                               | id inventoryId ticketNo name description reportNo userId faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes (isClosed)  | /api/reports/read              |
| **update**            | `POST`     | inventoryId ticketNo name description reportNo userId faultySN replacementSN dateRequested dateLeavingRBS dateDispatched dateReturned AWB AWBreturn RMA notes | status message                                                                                                                                                               | /api/reports/update            |
| **toggle_repairable** | `POST`     | id                                                                                                                                                            | status message                                                                                                                                                               | /api/reports/toggle_repairable |

---

## Reports Comments

| **Action** | **Method** | **Parameters** | **Response**                                  | **URL**                      |
|------------|------------|----------------|-----------------------------------------------|------------------------------|
| **read**   | `GET`      | reportId       | id reportId firstname lastname text timestamp | /api/reports/comments/read   |
| **create** | `POST`     | reportId text  | status message id reportId text               | /api/reports/comments/create |

---

## Projects

| **Action**           | **Method** | **Parameters**                                   | **Response**                                                                                          | **URL**                        |
|----------------------|------------|--------------------------------------------------|-------------------------------------------------------------------------------------------------------|--------------------------------|
| **create**           | `POST`     | inventoryId type description qty notes           | status message id inventoryId type description qty notes                                              | /api/projects/create           |
| **delete**           | `POST`     | id                                               | status message                                                                                        | /api/projects/delete           |
| **read_single**      | `GET`      | id                                               | id inventoryId type description qty notes                                                             | /api/projects/read_single      |
| **read**             | `GET`      | type                                             | id inventoryId type_id type_name inventory_SKU inventory_category description qty notes user_fullname | /api/projects/read             |
| **read_allocations** | `GET`      | type                                             | inventoryId type_id type_name total_qty                                                               | /api/projects/read_allocations |
| **update**           | `POST`     | id inventoryId type description qty notes        | status message                                                                                        | /api/projects/update           |

---

## Projects Types

| **Action**          | **Method** | **Parameters** | **Response** | **URL**                    |
|---------------------|------------|----------------|--------------|----------------------------|
| **create**          | `POST`     | name           | name         | /api/projects/types/create |
| **read**            | `GET`      | id             | id name      | /api/projects/types/read   |
| **read_myprojects** | `GET`      | id             | id name      | /api/projects/types/read   |

---