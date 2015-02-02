Post Merge DB Migration Hook [![Build Status](https://travis-ci.org/jackvial/db-migration.svg)](https://travis-ci.org/jackvial/db-migration)
=====================================================================================================================
### How to Use
- Copy migrate.php, rename it to to post-merge and move the file to ./git/hooks
- Configure the path to your migration directory
- Configure your database connection

### Running the Unit Tests
Run from the root directory
```bash 
composer install
```
```bash
vendors/bin/codecept run
```