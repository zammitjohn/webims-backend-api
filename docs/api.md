# API Reference

This is a full JSON API reference which interfaces to the extensive WebIMS database.

Explore [Postman Collection](WebIMS.postman_collection.json)

## Authorization
APIs use authorization to ensure that client requests access data securely. To authorize against this API, you'll need to pass the authorization key in the HTTP header ```Auth-Key``` with each request. ```Auth-Key``` must contain the user sessionId.

## Objects

### user

| **Action**           | **Method** | **Body**          | **Response**                                                              | **Path**                   |
|----------------------|------------|-------------------|---------------------------------------------------------------------------|----------------------------|
| **login**            | `POST`     | username password | status message id username firstName lastName *sessionId* created         | /api/user/login            |
| **logout**           | `PATCH`    |                   | status message                                                            | /api/user/logout           |
| **read**             | `GET`      |                   | id firstName lastName lastAvailable                                       | /api/user/read             |
| **validate_session** | `GET`      |                   | status message firstName lastName canUpdate canCreate canImport canDelete | /api/user/validate_session |

---

### inventory

| **Action**      | **Method** | **Parameters**                   | **Body**                                                                | **Response**                                                                                                                                                                             | **Path**                   |
|-----------------|------------|----------------------------------|-------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------------------------|
| **create**      | `POST`     |                                  | SKU warehouse_categoryId description qty qtyIn qtyOut supplier notes    | status message id SKU warehouse_categoryId description qty qtyIn qtyOut supplier notes                                                                                                   | /api/inventory/create      |
| **delete**      | `DELETE`   |                                  | id                                                                      | status message                                                                                                                                                                           | /api/inventory/delete      |
| **read_single** | `GET`      | id                               |                                                                         | id SKU warehouseId warehouse_categoryId description qty qtyIn qtyOut supplier notes importDate lastChange                                                                                | /api/inventory/read_single |
| **read**        | `GET`      | warehouseId warehouse_categoryId |                                                                         | id SKU warehouse_name warehouseId warehouse_categoryId warehouse_category_name warehouse_category_importName description qty qtyIn qtyOut qty_project_item_allocated supplier importDate | /api/inventory/read        |
| **search**      | `GET`      | term                             |                                                                         | value, label                                                                                                                                                                             | /api/inventory/search      |
| **update**      | `PUT`      |                                  | id SKU warehouse_categoryId description qty qtyIn qtyOut supplier notes | status message                                                                                                                                                                           | /api/inventory/update      |
| **import**      | `PUT`      |                                  | warehouseId file[^1]                                                    | status created_count updated_count conflict_count deleted_count                                                                                                                          | /api/inventory/import      |

---

### inventory_transaction

| **Action**   | **Method** | **Parameters** | **Body**                       | **Response**                                     | **Path**                            |
|--------------|------------|----------------|--------------------------------|--------------------------------------------------|-------------------------------------|
| **read**     | `GET`      |                |                                | id date user_fullName description                | /api/inventory/transaction/read     |
| **create**   | `POST`     |                | return items(item_id item_qty) | status message id returned_count requested_count | /api/inventory/transaction/create   |
| **download** | `GET`      | id             |                                | file                                             | /api/inventory/transaction/download |

---

### warehouse

| **Action** | **Method** | **Parameters** | **Response**          | **Path**            |
|------------|------------|----------------|-----------------------|---------------------|
| **read**   | `GET`      | id             | id name supportImport | /api/warehouse/read |

---

### warehouse_category

| **Action** | **Method** | **Parameters**  | **Response**                                  | **Path**                     |
|------------|------------|-----------------|-----------------------------------------------|------------------------------|
| **read**   | `GET`      | id warehouseId  | id name importName warehouseId warehouse_name | /api/warehouse/category/read |

---

### registry

| **Action** | **Method** | **Parameters** | **Body**                               | **Response**                                             | **Path**             |
|------------|------------|----------------|----------------------------------------|----------------------------------------------------------|----------------------|
| **create** | `POST`     |                | inventoryId serialNumber datePurchased | status message id inventoryId serialNumber datePurchased | /api/registry/create |
| **delete** | `DELETE`   |                | id                                     | status message                                           | /api/registry/delete |
| **read**   | `GET`      | inventoryId    |                                        | id inventoryId serialNumber datePurchased state          | /api/registry/read   |

---

### report

| **Action**            | **Method** | **Parameters** | **Body**                                                                                                                                                                                      | **Response**                                                                                                                                                                                                             | **Path**                      |
|-----------------------|------------|----------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|-------------------------------|
| **create**            | `POST`     |                | inventoryId ticketNumber name description reportNumber assignee_userId faulty_registryId replacement_registryId dateRequested dateLeaving dateDispatched dateReturned AWB AWBreturn RMA notes | status message inventoryId ticketNumber name description reportNumber assignee_userId faulty_registryId replacement_registryId dateRequested dateLeaving dateDispatched dateReturned AWB AWBreturn RMA notes             | /api/report/create            |
| **read_single**       | `GET`      | id             |                                                                                                                                                                                               | id inventoryId ticketNumber name description reportNumber assignee_userId faulty_registryId replacement_registryId dateRequested dateLeaving dateDispatched dateReturned AWB AWBreturn RMA notes isClosed isRepairable   | /api/report/read_single       |
| **read**              | `GET`      |                |                                                                                                                                                                                               | id inventoryId ticketNumber name description reportNumber assignee_userId faulty_registryId replacement_registryId dateRequested dateLeaving dateDispatched dateReturned AWB AWBreturn RMA notes (isClosed) isRepairable | /api/report/read              |
| **read_myreports**    | `GET`      |                |                                                                                                                                                                                               | id inventoryId ticketNumber name description reportNumber assignee_userId faulty_registryId replacement_registryId dateRequested dateLeaving dateDispatched dateReturned AWB AWBreturn RMA notes (isClosed) isRepairable | /api/report/read_myreports    |
| **update**            | `PUT`      |                | inventoryId ticketNumber name description reportNumber assignee_userId faulty_registryId replacement_registryId dateRequested dateLeaving dateDispatched dateReturned AWB AWBreturn RMA notes | status message                                                                                                                                                                                                           | /api/report/update            |
| **toggle_repairable** | `PATCH`    |                | id                                                                                                                                                                                            | status message                                                                                                                                                                                                           | /api/report/toggle_repairable |

---

### report_comment

| **Action** | **Method** | **Parameters** | **Body**      | **Response**                                  | **Path**                   |
|------------|------------|----------------|---------------|-----------------------------------------------|----------------------------|
| **read**   | `GET`      | reportId       |               | id reportId firstname lastname text timestamp | /api/report/comment/read   |
| **create** | `POST`     |                | reportId text | status message id reportId text               | /api/report/comment/create |


---

### project

| **Action**          | **Method** | **Parameters**      | **Body**                         | **Response**                                        | **Path**                    |
|---------------------|------------|---------------------|----------------------------------|-----------------------------------------------------|-----------------------------|
| **create**          | `POST`     |                     | name                             | id name status message                              | /api/project/create         |
| **delete**          | `DELETE`   |                     | id                               | status message                                      | /api/project/delete         |
| **read**            | `GET`      | id                  |                                  | id name                                             | /api/project/read           |
| **read_myprojects** | `GET`      |                     |                                  | id name                                             | /api/project/read_myproject |
| **download**        | `GET`      | id                  |                                  | file                                                | /api/project/download       |
| **import**          | `POST`     |                     | id warehouse_categoryId file[^1] | status created_count notfound_count additional_info | /api/project/import         |

---

### project_item

| **Action**           | **Method** | **Parameters**      | **Body**                                       | **Response**                                                                            | **Path**                           |
|----------------------|------------|---------------------|------------------------------------------------|-----------------------------------------------------------------------------------------|------------------------------------|
| **create**           | `POST`     |                     | inventoryId projectId description qty notes    | status message id inventoryId projectId description qty notes                           | /api/project/item/create           |
| **delete**           | `DELETE`   |                     | id                                             | status message                                                                          | /api/project/item/delete           |
| **read_single**      | `GET`      | id                  |                                                | id inventoryId projectId description qty notes                                          | /api/project/item/read_single      |
| **read**             | `GET`      | projectId           |                                                | id inventoryId projectId project_name inventory_SKU description qty notes user_fullName | /api/project/item/read             |
| **read_allocations** | `GET`      | inventoryId         |                                                | inventoryId projectId project_name total_qty                                            | /api/project/item/read_allocations |
| **update**           | `PUT`      |                     | id inventoryId projectId description qty notes | status message                                                                          | /api/project/item/update           |

---

[^1]: Base64-encoded file.