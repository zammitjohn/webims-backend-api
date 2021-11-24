# SQL Commands
This page describes SQL commands which provide further functionality.
	
## Create new Inventory category
	
```
INSERT INTO `webims_db`.`inventory_categories` (`name`) VALUES ('Storeroom');
```
	
## Create new Inventory type

```
INSERT INTO `webims_db`.`inventory_types` (`type_category`, `name`, `import_name`) VALUES ('1', 'General', 'generic');
```

## View Change log
View change log for the following objects:
- Projects
- Projects Types
- Registry
- Reports
- Inventory
	
```
select logs.id, logs.properties_before, logs.properties_after, logs.time, users.username,
	CASE WHEN (logs.properties_before IS NOT NULL) and (logs.properties_after IS NOT NULL) THEN 'UPDATE'
		WHEN (logs.properties_before IS NULL) and (logs.properties_after IS NOT NULL) THEN 'CREATE'
		WHEN (logs.properties_before IS NOT NULL) and (logs.properties_after IS NULL) THEN 'DELETE'
		ELSE 'UNKNOWN'
	END AS action
from logs
JOIN users ON logs.userId = users.id
```