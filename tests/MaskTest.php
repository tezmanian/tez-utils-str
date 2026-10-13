<?php

declare(strict_types=1);

namespace Tezmanian\Utils\Tests\Str;

use PHPUnit\Framework\TestCase;
use Tezmanian\Utils\Str\Mask;

final class MaskTest extends TestCase
{
    // --- email ---

    public function testEmailMasksLocalPart(): void
    {
        self::assertSame('t***@example.com', Mask::email('test@example.com'));
    }

    public function testEmailKeepsOnlyFirstChar(): void
    {
        self::assertSame('a***@domain.org', Mask::email('administrator@domain.org'));
    }

    public function testEmailEmptyStringReturnsEmpty(): void
    {
        self::assertSame('', Mask::email(''));
    }

    public function testEmailWithoutAtSignReturnsOriginal(): void
    {
        self::assertSame('notanemail', Mask::email('notanemail'));
    }

    public function testEmailAtSignAtStartReturnsOriginal(): void
    {
        self::assertSame('@example.com', Mask::email('@example.com'));
    }

    // --- creditCard ---

    public function testCreditCardShowsLastFourDigits(): void
    {
        self::assertSame('**** **** **** 1111', Mask::creditCard('4111111111111111'));
    }

    public function testCreditCardStripsSpaces(): void
    {
        self::assertSame('**** **** **** 1234', Mask::creditCard('4000 0000 0000 1234'));
    }

    public function testCreditCardStripsHyphens(): void
    {
        self::assertSame('**** **** **** 9999', Mask::creditCard('4000-0000-0000-9999'));
    }

    public function testCreditCardTooShortReturnsOriginal(): void
    {
        self::assertSame('123', Mask::creditCard('123'));
    }

    // --- iban ---

    public function testIbanMasksMiddleSection(): void
    {
        self::assertSame('DE89 **** **** **** **** 00', Mask::iban('DE89370400440532013000'));
    }

    public function testIbanStripsExistingSpaces(): void
    {
        self::assertSame('DE89 **** **** **** **** 00', Mask::iban('DE89 3704 0044 0532 0130 00'));
    }

    public function testIbanTooShortReturnsOriginal(): void
    {
        self::assertSame('DE89', Mask::iban('DE89'));
    }

    // --- custom ---

    public function testCustomMasksMiddle(): void
    {
        self::assertSame('My*********et', Mask::custom('MySuperSecret', start: 2, end: 2));
    }

    public function testCustomStartOnly(): void
    {
        self::assertSame('He********', Mask::custom('HelloWorld', start: 2, end: 0));
    }

    public function testCustomEndOnly(): void
    {
        self::assertSame('********ld', Mask::custom('HelloWorld', start: 0, end: 2));
    }

    public function testCustomDifferentMaskChar(): void
    {
        self::assertSame('He######ld', Mask::custom('HelloWorld', start: 2, end: 2, char: '#'));
    }

    public function testCustomStartPlusEndEqualsLengthReturnsOriginal(): void
    {
        self::assertSame('Hello', Mask::custom('Hello', start: 3, end: 2));
    }

    public function testCustomEmptyStringReturnsEmpty(): void
    {
        self::assertSame('', Mask::custom('', start: 1, end: 1));
    }
}
