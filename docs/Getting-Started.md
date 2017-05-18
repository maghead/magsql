### Installation

To install sqlbuilder, simply run composer require:

```
composer require corneltek/sqlbuilder 4.0.x-dev
```

### Defining Driver Object

SQLBuilder detects the database platform (mysql, pgsql,
sqlite) by checking the driver object instance you pass to.
There are 3 pre-defined driver classes:

- `SQLBuilder\Driver\MySQLDriver`
- `SQLBuilder\Driver\PgSQLDriver`
- `SQLBuilder\Driver\SQLiteDriver`

The internal implementation uses the code below to check
the driver type, and it's faster than comparing strings:

```php
if ($driver instanceof MySQLDriver) {
   // .. do something with MySQL
}
```

To initialize a driver object is easy:

```php
use SQLBuilder\Driver\MySQLDriver;
$driver = new MySQLDriver;
```

And it's done.

These pre-defined driver classes derives from
`SQLBuilder\Driver\BaseDriver`, and
`SQLBuilder\Driver\BaseDriver` defines the core methods that
can quote strings, quote identifiers, deflate values and
so on...

You can also use the PDO* Drivers if you already have PDO connection object:

```php
use SQLBuilder\Driver\PDOMySQLDriver;
use PDO;
$pdo = new PDO('mysql;....');
$driver = new PDOMySQLDriver($pdo);
```

The constructor of PDO*Driver classes accepts PDO object as its first parameter. it will also setup `PDO::quote` method as the default quote handler.

### Setting up string quote handler

To quote strings into SQL queries, you can specify the quote handler to your
driver object, for example, PDO::quote method:

```php
$driver->setQuoter(array($pdo, 'quote'));
```

But if you're binding your string value with parameter marker, you don't actually need it. we will describe how to bind a value the later section.

### Preparing ArgumentArray

SQLBuilder deflates your variables into the parameter markers, hence you need
to provide an object that can register arguments, that is, `ArgumentArray`.

`Driver` object and `ArgumentArray` is only used when calling `toSQl` method.

```php
use SQLBuilder\ArgumentArray;
$args = new ArgumentArray;

$sql = $query->toSql($driver, $args);
```


### Building Select Query

```php
use SQLBuilder\Universal\Query\SelectQuery;
use SQLBuilder\Driver\MySQLDriver;
use SQLBuilder\ArgumentArray;

$args = new ArgumentArray;
$driver = new MySQLDriver;

$query = new SelectQuery;
$query->select(array('name', 'phone', 'address'))
      ->from('contacts')
            ;
$query->where('name LIKE :name', [ ':name' => '%John%' ]);
$sql = $query->toSql($driver, $args);
```

And the generated SQL:

```sql
SELECT name, phone, address FROM contacts WHERE name LIKE :name
```

### Defining Conditions

The `where()` method we used in the above code can take a
string and an array as its arguments, and you can call this
method more than once to append conditions, e.g.,

```php
$query->where('name LIKE :name', [ ':name' => '%John%' ])
      ->where('address LIKE :address', [ ':address' => '%223%' ])
      ;
```

By default, conditions are appended with `AND` operator. if
you want to append a condition with `OR` operator, you can
use:

```php
$query->where()...
      ->or()->where('...', ...);
```

You can also explicitly specify `AND` operator:


```php
$query->where()...
      ->and()->where('...', ...);
```

The `where()` method also returns a
`SQLBuilder\Universal\Syntax\Conditions` object, and the
cascading method calls will be on this object as you
continue the fluent method calls. e.g.,

```php
$query->where()
      ->equal('id', 3)
      ->is('confirmed' , true)
      ->in('code', [ 609, 3323, 689 ])
      ;
```

And the above code would generate the following corresponding SQL:

```sql
... WHERE id = 3 AND confirmed IS TRUE AND code IN (609,3323,689)
```

But don't worry about the injected parameters, these
parameters were inserted via strict type checking, if you're
worrying about security, you may check the source code here:
<https://github.com/c9s/SQLBuilder/blob/master/src/SQLBuilder/Driver/BaseDriver.php#L246>

Even the parameter is deflating into the SQL query string, I
would still suggest you use parameter binding, we will
describe the magical `Bind` class in the next section.

Here is the table of the operator mapping:

|    Operator                      |  Method                                 |
|:--------------------------------:|:---------------------------------------:|
|   `=`                            |  `equal($expr, $val)`                   |
|   `<>`                           |  `notEqual($expr, $val)`                |
|   `<`                            |  `lessThan($expr, $val)`                |
|   `<=`                           |  `lessThanOrEqual($expr, $val)`         |
|   `>`                            |  `greaterThan($expr, $val)`             |
|   `>=`                           |  `greaterThanOrEqual($expr, $val)`      |
|   `IS`                           |  `is($expr, $boolean)`                  |
|   `IS NOT`                       |  `isNot($expr, $boolean)`               |
|   `IN (...)`                     |  `in($expr, array $values)`             |
|   `NOT IN (...)`                 |  `notIn($expr, array $values)`          |
|   `LIKE`                         |  `like($expr, $pattern, $criteria)`     |
|   `NOT LIKE`                     |  `notLike($expr, $pattern, $criteria)`  |
|   `BETWEEN {min} AND {max}`      |  `between($expr, $min, $max)`           |
|   `( expr )`                     |  `group()`                              |
|   `AND`                          |  `and()`                                |
|   `OR`                           |  `or()`                                 |
|   `REGEXP`                       |  `regexp()`                                 |

### String Matching With Criteria

Using Criteria for `LIKE` expr:

```php
use SQLBuilder\Criteria;

$query->where()
      ->like('name', $pat, Criteria::CONTAINS);

$query->where()
      ->like('name', $pat, Criteria::STARTS_WITH);

$query->where()
      ->like('name', $pat, Criteria::ENDS_WITH);
```



### Binding Parameters

SQLBuilder provides a `Bind` class that helps you define an
argument that requires parameter binding, here is a short
example of using `Bind` class:

```php
$query = new UpdateQuery;
$query->options('LOW_PRIORITY', 'IGNORE')->update('users')->set([ 
    'name' => new Bind('name','Mary'),
]);
$query->where()
    ->equal('id', new Bind('id', 3));
$sql = $query->toSql($driver, $args);
// UPDATE LOW_PRIORITY IGNORE users SET name = :name WHERE id = :id'
```

If you want to build a query with question mark, you can pass `ParamMarker` class to the query:

```php
$query = new UpdateQuery;
$query->options('LOW_PRIORITY', 'IGNORE')->update('users')->set([ 
    'name' => new ParamMarker('John'),
]);
$query->where()
    ->equal('id', new ParamMarker(3));
$sql = $query->toSql($driver, $args);
// UPDATE LOW_PRIORITY IGNORE users SET name = ? WHERE id = ?'
```

### toSql method

The `toSql` method is actually an shared interface in `SQLBuilder\ToSqlInterface`, the prototype is:

```php
public function toSql(BaseDriver $driver, ArgumentArray $args);
```

That is, anything implemented with `ToSqlInterface` can be
deflated into SQL string through the driver object.


### Inserting Raw SQL string

If you want to append raw SQL string with complex function call or expression,
you can use `SQLBuilder\Raw` to wrap your SQL expression up and pass it to the
query object like just you pass the arguments:

```php
use SQLBuilder\Raw;
$query->update([ 'count' => new Raw('count + 3') ]);
```


### SQLBuilder\ToSqlInterface interface

Anything implemented with `SQLBuilder\ToSqlInterface` interface can be passed to `BaseDriver::deflate()` method to generate SQL string and query arguments.

