
### CreateDatabaseQuery

```php
use SQLBuilder\Universal\Query\CreateDatabaseQuery;

$q = new CreateDatabaseQuery;
$q->create('test')->characterSet('utf8');
// CREATE DATABASE `test` CHARACTER SET 'utf8'

$q = new CreateDatabaseQuery;
$q->create('test')
    ->characterSet('utf8');
$q->collate('en_US.UTF-8');
$q->toSql($driver, $args);
```

```sql
CREATE DATABASE "test" LC_COLLATE 'en_US.UTF-8'
```

### CreateIndexQuery 

```php
use SQLBuilder\Universal\Query\CreateIndexQuery;
$q = new CreateIndexQuery;
$q->create('idx_salary')
    ->on('employees', [ 'last_name', 'salary' ])
    ;
$q->toSql($driver, $args);
```

```sql
CREATE INDEX `idx_salary` ON `employees` (last_name,salary)
```

### DropIndexQuery

```php
use SQLBuilder\Universal\Query\DropIndexQuery;
$q = new DropIndexQuery;
$q->drop('idx_book')->on('books');
$q->toSql($driver, $args);
```

```sql
DROP INDEX `idx_book` ON `books`
```


### DeleteQuery

```php
use SQLBuilder\Universal\Query\DeleteQuery;

$query = new DeleteQuery;
$query->delete('users', 'u')->where()
    ->equal('id', 3);
$sql = $query->toSql($driver, $args);
```

```sql
DELETE users AS u WHERE id = 3'
```


### InsertQuery

```php
use SQLBuilder\Universal\Query\InsertQuery;

$query = new InsertQuery;
$query->option('LOW_PRIORITY', 'IGNORE');
$query->insert([ 'name' => new Bind('name', 'John'), 'confirmed' => new Bind('confirmed', true) ])->into('users');
$query->returning('id');
$sql = $query->toSql($driver, $args);
```

```sql
INSERT LOW_PRIORITY IGNORE INTO users (name,confirmed) VALUES (:name,:confirmed)
```


### SelectQuery

```php
use SQLBuilder\Universal\Query\SelectQuery;

$query = new SelectQuery;
$query->select(array('id', 'name', 'phone', 'address'))
    ->from('users', 'u')
    ->join('posts')
        ->as('p')
        ->on('p.user_id = u.id')
    ;
$query->where('u.name LIKE :name', [ ':name' => '%John%' ]);
```




