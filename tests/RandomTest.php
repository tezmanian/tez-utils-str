<?php

declare(strict_types=1);

namespace Tezmanian\Utils\Tests\Str;

use PHPUnit\Framework\TestCase;
use Tezmanian\Utils\Str\Random;

final class RandomTest extends TestCase
{
    // -------------------------------------------------------------------------
    // token()
    // -------------------------------------------------------------------------

    public function testTokenReturnsCorrectLength(): void
    {
        self::assertSame(32, strlen(Random::token(32)));
        self::assertSame(64, strlen(Random::token(64)));
    }

    public function testTokenDefaultLengthIs32(): void
    {
        self::assertSame(32, strlen(Random::token()));
    }

    public function testTokenReturnsOnlyHexChars(): void
    {
        self::assertMatchesRegularExpression('/^[0-9a-f]+$/', Random::token(64));
    }

    public function testTokenOddLengthWorks(): void
    {
        self::assertSame(7, strlen(Random::token(7)));
    }

    public function testTokenThrowsOnZeroLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Random::token(0);
    }

    public function testTokenThrowsOnNegativeLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Random::token(-1);
    }

    // -------------------------------------------------------------------------
    // alphanumeric()
    // -------------------------------------------------------------------------

    public function testAlphanumericReturnsCorrectLength(): void
    {
        self::assertSame(16, strlen(Random::alphanumeric(16)));
    }

    public function testAlphanumericDefaultLengthIs32(): void
    {
        self::assertSame(32, strlen(Random::alphanumeric()));
    }

    public function testAlphanumericReturnsOnlyAlphanumericChars(): void
    {
        self::assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', Random::alphanumeric(128));
    }

    public function testAlphanumericThrowsOnZeroLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Random::alphanumeric(0);
    }

    // -------------------------------------------------------------------------
    // charset()
    // -------------------------------------------------------------------------

    public function testCharsetReturnsCorrectLength(): void
    {
        self::assertSame(12, strlen(Random::charset(12, '0123456789')));
    }

    public function testCharsetReturnsOnlyCharsFromAlphabet(): void
    {
        $alphabet = 'ABCDE';
        $result   = Random::charset(100, $alphabet);

        self::assertMatchesRegularExpression('/^[ABCDE]+$/', $result);
    }

    public function testCharsetWithSingleCharReturnsRepeatedChar(): void
    {
        self::assertSame('aaaa', Random::charset(4, 'a'));
    }

    public function testCharsetThrowsOnZeroLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Random::charset(0, 'abc');
    }

    public function testCharsetThrowsOnEmptyAlphabet(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Random::charset(8, '');
    }

    // -------------------------------------------------------------------------
    // Randomness sanity check
    // -------------------------------------------------------------------------

    public function testTwoTokensAreDifferent(): void
    {
        self::assertNotSame(Random::token(64), Random::token(64));
    }

    public function testTwoAlphanumericStringsAreDifferent(): void
    {
        self::assertNotSame(Random::alphanumeric(64), Random::alphanumeric(64));
    }
}
