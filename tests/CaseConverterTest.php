<?php

declare(strict_types=1);

namespace Tezmanian\Utils\Tests\Str;

use PHPUnit\Framework\TestCase;
use Tezmanian\Utils\Str\CaseConverter;

final class CaseConverterTest extends TestCase
{
    // -------------------------------------------------------------------------
    // toSnake
    // -------------------------------------------------------------------------

    public function testCamelToSnake(): void
    {
        self::assertSame('user_profile_name', CaseConverter::toSnake('userProfileName'));
    }

    public function testPascalToSnake(): void
    {
        self::assertSame('user_profile_name', CaseConverter::toSnake('UserProfileName'));
    }

    public function testKebabToSnake(): void
    {
        self::assertSame('user_profile_name', CaseConverter::toSnake('user-profile-name'));
    }

    public function testScreamingSnakeToSnake(): void
    {
        self::assertSame('user_profile_name', CaseConverter::toSnake('USER_PROFILE_NAME'));
    }

    public function testSnakeToSnakeIsIdempotent(): void
    {
        self::assertSame('user_profile_name', CaseConverter::toSnake('user_profile_name'));
    }

    // -------------------------------------------------------------------------
    // toCamel
    // -------------------------------------------------------------------------

    public function testSnakeToCamel(): void
    {
        self::assertSame('userProfileName', CaseConverter::toCamel('user_profile_name'));
    }

    public function testKebabToCamel(): void
    {
        self::assertSame('userProfileName', CaseConverter::toCamel('user-profile-name'));
    }

    public function testPascalToCamel(): void
    {
        self::assertSame('userProfileName', CaseConverter::toCamel('UserProfileName'));
    }

    public function testCamelToCamelIsIdempotent(): void
    {
        self::assertSame('userProfileName', CaseConverter::toCamel('userProfileName'));
    }

    // -------------------------------------------------------------------------
    // toPascal
    // -------------------------------------------------------------------------

    public function testSnakeToPascal(): void
    {
        self::assertSame('UserProfileName', CaseConverter::toPascal('user_profile_name'));
    }

    public function testKebabToPascal(): void
    {
        self::assertSame('UserProfileName', CaseConverter::toPascal('user-profile-name'));
    }

    public function testCamelToPascal(): void
    {
        self::assertSame('UserProfileName', CaseConverter::toPascal('userProfileName'));
    }

    // -------------------------------------------------------------------------
    // toKebab
    // -------------------------------------------------------------------------

    public function testPascalToKebab(): void
    {
        self::assertSame('user-profile-name', CaseConverter::toKebab('UserProfileName'));
    }

    public function testSnakeToKebab(): void
    {
        self::assertSame('user-profile-name', CaseConverter::toKebab('user_profile_name'));
    }

    public function testCamelToKebab(): void
    {
        self::assertSame('user-profile-name', CaseConverter::toKebab('userProfileName'));
    }

    // -------------------------------------------------------------------------
    // toScreamingSnake
    // -------------------------------------------------------------------------

    public function testCamelToScreamingSnake(): void
    {
        self::assertSame('USER_ID', CaseConverter::toScreamingSnake('userId'));
    }

    public function testKebabToScreamingSnake(): void
    {
        self::assertSame('USER_PROFILE_NAME', CaseConverter::toScreamingSnake('user-profile-name'));
    }

    public function testSnakeToScreamingSnake(): void
    {
        self::assertSame('USER_PROFILE_NAME', CaseConverter::toScreamingSnake('user_profile_name'));
    }

    // -------------------------------------------------------------------------
    // Special cases
    // -------------------------------------------------------------------------

    public function testSingleWord(): void
    {
        self::assertSame('name', CaseConverter::toSnake('name'));
        self::assertSame('name', CaseConverter::toCamel('name'));
        self::assertSame('Name', CaseConverter::toPascal('name'));
        self::assertSame('NAME', CaseConverter::toScreamingSnake('name'));
    }

    public function testAcronymInPascal(): void
    {
        self::assertSame('html_parser', CaseConverter::toSnake('HTMLParser'));
    }

    public function testEmptyString(): void
    {
        self::assertSame('', CaseConverter::toCamel(''));
        self::assertSame('', CaseConverter::toSnake(''));
    }
}
