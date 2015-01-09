DB Migration Script [![Build Status](https://travis-ci.org/jackvial/db-migration.svg)](https://travis-ci.org/jackvial/db-migration)
===================================================================================================================================
- Performs a git diff on your migartions directory and only runs new scripts
- Can be triggered a git hook such as post-receive 

### Setup
Run: 
```bash 
composer install
```
### Run Tests
Run: 
```bash
vendors/bin/codecept run
``` from root directory