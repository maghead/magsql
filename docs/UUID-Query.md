SQLBuilder package provides an universal query class for querying UUID() on different platforms.

Currently UUIDQuery supports MySQL, PostgreSQL and SQLite:

```php
use SQLBuilder\Universal\UUIDQuery;
$query = new UUIDQuery;
$sql = $query->toSql($driver, $args);
```