# SQL Commands
This page describes SQL commands which provide further functionality.

## List users, permissions and sessions
	
```
SELECT * FROM user
```
	
## Create a new Warehouse
	
```
INSERT INTO `webims_db`.`warehouse` (`name`) VALUES ('Storeroom');
```
	
## Create a new Warehouse Category

```
INSERT INTO `webims_db`.`warehouse_category` (`warehouseId`, `name`, `importName`) VALUES ('1', 'General', 'generic');
```

## View Change log
View change log for the following objects:
- project
- project_item
- registry
- report
- inventory
	
```
select log.id, log.object, log.propertiesBefore, log.propertiesAfter, log.time, user.username,
	CASE WHEN (log.propertiesBefore IS NOT NULL) and (log.propertiesAfter IS NOT NULL) THEN 'UPDATE'
		WHEN (log.propertiesBefore IS NULL) and (log.propertiesAfter IS NOT NULL) THEN 'CREATE'
		WHEN (log.propertiesBefore IS NOT NULL) and (log.propertiesAfter IS NULL) THEN 'DELETE'
		ELSE 'UNKNOWN'
	END AS action
from log
JOIN user ON log.userId = user.id
```