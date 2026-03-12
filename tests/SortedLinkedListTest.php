<?php

declare(strict_types=1);

namespace PrzemekPeron\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PrzemekPeron\SortDirection;
use PrzemekPeron\SortedLinkedList;

final class SortedLinkedListTest extends TestCase
{
    #[Test]
    public function newListIsEmpty(): void
    {
        $list = new SortedLinkedList();

        $this->assertTrue($list->isEmpty());
        $this->assertCount(0, $list);
        $this->assertSame([], $list->toArray());
    }

    #[Test]
    public function insertsMaintainSortedOrderForIntegers(): void
    {
        $list = new SortedLinkedList();

        $list->insert(5);
        $list->insert(1);
        $list->insert(3);
        $list->insert(2);
        $list->insert(4);

        $this->assertSame([1, 2, 3, 4, 5], $list->toArray());
    }

    #[Test]
    public function insertsMaintainSortedOrderForStrings(): void
    {
        $list = new SortedLinkedList();

        $list->insert('cherry');
        $list->insert('apple');
        $list->insert('banana');

        $this->assertSame(['apple', 'banana', 'cherry'], $list->toArray());
    }

    #[Test]
    public function insertHandlesDuplicateValues(): void
    {
        $list = new SortedLinkedList();

        $list->insert(3);
        $list->insert(1);
        $list->insert(3);
        $list->insert(1);

        $this->assertSame([1, 1, 3, 3], $list->toArray());
    }

    #[Test]
    public function insertThrowsOnTypeMismatch(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot add string value to a list of int.');

        $list->insert('text');
    }

    #[Test]
    public function insertStringIntoIntListThrows(): void
    {
        $list = new SortedLinkedList();
        $list->insert('first');

        $this->expectException(InvalidArgumentException::class);

        $list->insert(42);
    }

    #[Test]
    public function removesExistingValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $this->assertTrue($list->remove(2));
        $this->assertSame([1, 3], $list->toArray());
    }

    #[Test]
    public function removesHeadValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $this->assertTrue($list->remove(1));
        $this->assertSame([2, 3], $list->toArray());
    }

    #[Test]
    public function removesTailValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $this->assertTrue($list->remove(3));
        $this->assertSame([1, 2], $list->toArray());
    }

    #[Test]
    public function removeReturnsFalseForNonExistingValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(3);

        $this->assertFalse($list->remove(2));
        $this->assertSame([1, 3], $list->toArray());
    }

    #[Test]
    public function removeFromEmptyListReturnsFalse(): void
    {
        $list = new SortedLinkedList();

        $this->assertFalse($list->remove(1));
    }

    #[Test]
    public function removeOnlyFirstOccurrenceOfDuplicate(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(2);
        $list->insert(3);

        $this->assertTrue($list->remove(2));
        $this->assertSame([1, 2, 3], $list->toArray());
    }

    #[Test]
    public function removeWithMismatchedTypeReturnsFalse(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);

        $this->assertFalse($list->remove('1'));
    }

    #[Test]
    public function containsFindsExistingValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $this->assertTrue($list->contains(2));
    }

    #[Test]
    public function containsReturnsFalseForNonExistingValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(3);

        $this->assertFalse($list->contains(2));
    }

    #[Test]
    public function containsReturnsFalseForEmptyList(): void
    {
        $list = new SortedLinkedList();

        $this->assertFalse($list->contains(1));
    }

    #[Test]
    public function containsWithMismatchedTypeReturnsFalse(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);

        $this->assertFalse($list->contains('1'));
    }

    #[Test]
    public function countReflectsInsertionsAndRemovals(): void
    {
        $list = new SortedLinkedList();

        $this->assertCount(0, $list);

        $list->insert(1);
        $list->insert(2);
        $this->assertCount(2, $list);

        $list->remove(1);
        $this->assertCount(1, $list);
    }

    #[Test]
    public function isIterableWithForeach(): void
    {
        $list = new SortedLinkedList();
        $list->insert(3);
        $list->insert(1);
        $list->insert(2);

        $values = [];

        foreach ($list as $value) {
            $values[] = $value;
        }

        $this->assertSame([1, 2, 3], $values);
    }

    #[Test]
    public function iteratorProvidesSequentialKeys(): void
    {
        $list = new SortedLinkedList();
        $list->insert(10);
        $list->insert(20);

        $keys = [];

        foreach ($list as $key => $value) {
            $keys[] = $key;
        }

        $this->assertSame([0, 1], $keys);
    }

    #[Test]
    public function typeResetsAfterRemovingAllElements(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);
        $list->insert(2);

        $list->remove(1);
        $list->remove(2);

        $this->assertTrue($list->isEmpty());

        $list->insert('hello');
        $this->assertSame(['hello'], $list->toArray());
    }

    #[Test]
    public function insertAtBeginningMiddleAndEnd(): void
    {
        $list = new SortedLinkedList();

        $list->insert(5);
        $list->insert(10);
        $list->insert(1);
        $list->insert(7);

        $this->assertSame([1, 5, 7, 10], $list->toArray());
    }

    #[Test]
    public function worksWithNegativeIntegers(): void
    {
        $list = new SortedLinkedList();

        $list->insert(0);
        $list->insert(-5);
        $list->insert(3);
        $list->insert(-2);

        $this->assertSame([-5, -2, 0, 3], $list->toArray());
    }

    #[Test]
    public function worksWithSingleElement(): void
    {
        $list = new SortedLinkedList();

        $list->insert(42);

        $this->assertSame([42], $list->toArray());
        $this->assertFalse($list->isEmpty());
        $this->assertCount(1, $list);
        $this->assertTrue($list->contains(42));

        $this->assertTrue($list->remove(42));
        $this->assertTrue($list->isEmpty());
    }

    #[Test]
    public function descendingOrderForIntegers(): void
    {
        $list = new SortedLinkedList(SortDirection::DESC);

        $list->insert(1);
        $list->insert(5);
        $list->insert(3);

        $this->assertSame([5, 3, 1], $list->toArray());
    }

    #[Test]
    public function descendingOrderForStrings(): void
    {
        $list = new SortedLinkedList(SortDirection::DESC);

        $list->insert('apple');
        $list->insert('cherry');
        $list->insert('banana');

        $this->assertSame(['cherry', 'banana', 'apple'], $list->toArray());
    }

    #[Test]
    public function descendingContainsFindsValue(): void
    {
        $list = new SortedLinkedList(SortDirection::DESC);

        $list->insert(10);
        $list->insert(20);
        $list->insert(30);

        $this->assertTrue($list->contains(20));
        $this->assertFalse($list->contains(99));
    }

    #[Test]
    public function descendingRemovesValue(): void
    {
        $list = new SortedLinkedList(SortDirection::DESC);

        $list->insert(1);
        $list->insert(2);
        $list->insert(3);

        $this->assertTrue($list->remove(2));
        $this->assertSame([3, 1], $list->toArray());
        $this->assertFalse($list->remove(99));
    }

    #[Test]
    public function descendingHandlesDuplicates(): void
    {
        $list = new SortedLinkedList(SortDirection::DESC);

        $list->insert(3);
        $list->insert(1);
        $list->insert(3);
        $list->insert(1);

        $this->assertSame([3, 3, 1, 1], $list->toArray());
    }

    #[Test]
    public function descendingWithNegativeIntegers(): void
    {
        $list = new SortedLinkedList(SortDirection::DESC);

        $list->insert(0);
        $list->insert(-5);
        $list->insert(3);
        $list->insert(-2);

        $this->assertSame([3, 0, -2, -5], $list->toArray());
    }
}
