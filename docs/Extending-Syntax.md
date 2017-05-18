`SQLBuilder\SyntaxExtender` let you map customized method name to syntax class. It uses the magic `__call` method of PHP to handle the extended syntax.

Basically it passes the method arguments to the constructor of the syntax class that is registered to the query object, and returns the created syntax object to the caller.

You can add the code below to support syntax extender:

```php
class FooQuery {
  use SQLBuilder\SyntaxExtender;
  public function __call($methodName, $arguments = array()) {
    return $this->someProperty = $this->handleSyntax($methodName, $arguments);
  }
}
```

And here is the usage:

```php
$q = new AlterTableQuery('products');
$q->registerClass('setEngine', 'SQLBuilder\MySQL\Syntax\AlterTableSetEngine');
$q->setEngine('InnoDB');

$this->assertSqlStrings($q, [ 
    [new MySQLDriver, 'ALTER TABLE `products` ENGINE = \'InnoDB\''],
]);
```
