Used Classes:

```php
use SQLBuilder\Universal\Syntax\Column;
use SQLBuilder\Universal\Query\CreateTableQuery;
use SQLBuilder\Universal\Query\DropTableQuery;
use SQLBuilder\Universal\Query\AlterTableQuery;
```


### dropColumnByName

```php
$q = new AlterTableQuery('products');
$q->dropColumnByName('name');
```

### renameColumn

AlterTableQuery class supports cross-platform column renaming, the premise is that you have to provide Column object,
since MySQL database doesn't support rename syntax, this query class generates "change column" instead "rename column",
hence the column object must have the full definitions for the change column query.

```php
$q = new AlterTableQuery('products');
$q->renameColumn(new Column('name'), new Column('title', 'varchar(30)'));
```

### modifyColumn

`modifyColumn` builds the same syntax as MySQL's modify column, therefore you need to provide the column definition by using the column object:

```php
// Column type is required for MySQL to modify
$column = new Column('name', 'text');
$column->null();

$q = new AlterTableQuery('products');
$q->modifyColumn($column);
```

**[MySQL Only]**

### orderBy

The `orderBy` method only supports MySQL, it builds the same syntax as MySQL's `ALTER TABLE ... ORDERR BY`.

```php
$q = new AlterTableQuery('products');
$q->orderBy([ 'name', 'price', 'quantity' ]);
```

**[MySQL Only]**

### rename

The `rename` method renames the table name:

```php
$q = new AlterTableQuery('products');
$q->rename('products_new');
```


### dropPrimaryKey

`dropPrimaryKey` drops the primary key from the table. 

**[MySQL Only]**

### dropIndex($indexName)

`dropIndex` drops the index from the table.

**[MySQL Only]**