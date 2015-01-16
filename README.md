DB Migration Script [![Build Status](https://travis-ci.org/jackvial/db-migration.svg)](https://travis-ci.org/jackvial/db-migration)
===================================================================================================================================
- Performs a git diff on your migartions directory and only runs new scripts
- Can be triggered by a git hook such as post-receive 

### Setup
Run: 
```bash 
composer install
```
### Run Tests
Run: 
```bash
vendors/bin/codecept run
``` 
from the project root directory.


### Naming Migration Files
All migration files must be prefixed with a number so that they run in the correct order:
```bash 
includes/22_migration_script.sql
```