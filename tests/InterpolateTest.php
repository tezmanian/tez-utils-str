<?php

declare(strict_types=1);

namespace Tez\Utils\Tests\Str;

use PHPUnit\Framework\TestCase;
use Tez\Utils\Str\Interpolate;

final class InterpolateTest extends TestCase
{
    public function testBasicReplacement(): void
    {
        $result = Interpolate::render(
            'Hello {name}, you have {count} messages.',
            ['name' => 'Anna', 'count' => 5],
        );

        self::assertSame('Hello Anna, you have 5 messages.', $result);
    }

    public function testNoPlaceholdersReturnsTemplateUnchanged(): void
    {
        self::assertSame('Hello World', Interpolate::render('Hello World', []));
    }

    public function testCustomDelimiters(): void
    {
        $result = Interpolate::render(
            'Hello :name!',
            ['name' => 'Bob'],
            prefix: ':',
            suffix: '',
        );

        self::assertSame('Hello Bob!', $result);
    }

    public function testNestedKeyWithDotNotation(): void
    {
        $result = Interpolate::render(
            'Welcome, {user.name}!',
            ['user' => ['name' => 'Clara']],
        );

        self::assertSame('Welcome, Clara!', $result);
    }

    public function testDeeplyNestedKey(): void
    {
        $result = Interpolate::render(
            '{a.b.c}',
            ['a' => ['b' => ['c' => 'deep']]],
        );

        self::assertSame('deep', $result);
    }

    public function testMissingKeyThrowsByDefault(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing key "missing"');

        Interpolate::render('Hello {missing}!', []);
    }

    public function testMissingKeyUsesFallback(): void
    {
        $result = Interpolate::render('Hello {missing}!', [], fallback: 'N/A');

        self::assertSame('Hello N/A!', $result);
    }

    public function testEmptyStringFallback(): void
    {
        $result = Interpolate::render('{greeting} World', [], fallback: '');

        self::assertSame(' World', $result);
    }

    public function testIntegerValueIsStringified(): void
    {
        $result = Interpolate::render('Count: {n}', ['n' => 42]);

        self::assertSame('Count: 42', $result);
    }

    public function testEmptyTemplateReturnsEmpty(): void
    {
        self::assertSame('', Interpolate::render('', []));
    }

    public function testMissingNestedKeyThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Interpolate::render('{user.email}', ['user' => ['name' => 'Anna']]);
    }

    public function testMultiplePlaceholdersReplacedInOrder(): void
    {
        $result = Interpolate::render(
            '{a} {b} {a}',
            ['a' => 'foo', 'b' => 'bar'],
        );

        self::assertSame('foo bar foo', $result);
    }
}
