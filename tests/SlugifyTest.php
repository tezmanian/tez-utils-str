<?php

declare(strict_types=1);

namespace Tezmanian\Utils\Tests\Str;

use PHPUnit\Framework\TestCase;
use Tezmanian\Utils\Str\Slugify;

final class SlugifyTest extends TestCase
{
    public function testBasicSlug(): void
    {
        self::assertSame('hello-world', Slugify::slug('Hello World'));
    }

    public function testGermanUmlauts(): void
    {
        self::assertSame('aerger-mit-umlauten', Slugify::slug('Ärger mit Umlauten!'));
    }

    public function testGermanSharpS(): void
    {
        self::assertSame('strasse', Slugify::slug('Straße'));
    }

    public function testFrenchAccents(): void
    {
        self::assertSame('creme-brulee', Slugify::slug('Crème brûlée'));
    }

    public function testMultipleSpacesCollapsed(): void
    {
        self::assertSame('multiple-spaces', Slugify::slug('  Multiple   Spaces  '));
    }

    public function testCustomSeparator(): void
    {
        self::assertSame('hello_world', Slugify::slug('Hello World', '_'));
    }

    public function testSpecialCharactersReplaced(): void
    {
        self::assertSame('foo-bar-baz', Slugify::slug('foo/bar::baz'));
    }

    public function testLeadingAndTrailingSeparatorsRemoved(): void
    {
        self::assertSame('hello', Slugify::slug('---hello---'));
    }

    public function testEmptyStringReturnsEmpty(): void
    {
        self::assertSame('', Slugify::slug(''));
    }

    public function testBlankStringReturnsEmpty(): void
    {
        self::assertSame('', Slugify::slug('   '));
    }

    public function testNordicCharacters(): void
    {
        self::assertSame('ae-o', Slugify::slug('Æ Ø'));
    }

    public function testSpanishN(): void
    {
        self::assertSame('canon', Slugify::slug('Cañon'));
    }

    public function testAlreadyValidSlugUnchanged(): void
    {
        self::assertSame('hello-world-123', Slugify::slug('hello-world-123'));
    }

    public function testNumbersPreserved(): void
    {
        self::assertSame('php-8-3', Slugify::slug('PHP 8.3'));
    }
}
