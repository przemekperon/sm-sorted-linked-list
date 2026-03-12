# SortedLinkedList

A PHP library providing a linked list that maintains its elements in ascending sorted order. It accepts either `int` or `string` values, but not both in the same instance.

## Usage

### Basic operations

```php
use PrzemekPeron\SortedLinkedList;

$list = new SortedLinkedList();

$list->insert(5);
$list->insert(1);
$list->insert(3);

$list->toArray();    // [1, 3, 5]
$list->contains(3);  // true
$list->contains(99); // false
$list->isEmpty();    // false
count($list);        // 3
```

### Removing elements

```php
$list->insert(10);
$list->insert(20);
$list->insert(30);

$list->remove(20); // true
$list->remove(99); // false (not found)
$list->toArray();  // [10, 30]
```

### Iteration

The list implements `IteratorAggregate`, so it works with `foreach`:

```php
$list->insert(3);
$list->insert(1);
$list->insert(2);

foreach ($list as $value) {
    echo $value . PHP_EOL; // 1, 2, 3
}
```

### Strings

```php
$list = new SortedLinkedList();

$list->insert('cherry');
$list->insert('apple');
$list->insert('banana');

$list->toArray(); // ['apple', 'banana', 'cherry']
```

### Type safety

The list locks its type on the first insertion. Mixing types throws an `InvalidArgumentException`:

```php
$list = new SortedLinkedList();
$list->insert(1);
$list->insert('text'); // InvalidArgumentException: Cannot add string value to a list of int.
```

The type resets when the list becomes empty, allowing reuse with a different type.
