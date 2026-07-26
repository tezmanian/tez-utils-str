# tez-utils-str

Focused PHP string utilities: case conversion, template interpolation, masking, cryptographically secure random strings, URL slugs, and multibyte-aware truncation. PHP 8.3+, no dependencies.

## Installation

```bash
composer require tez/utils-str
```

## Components

| Class | Purpose |
|-------|---------|
| `CaseConverter` | Converts between camelCase, PascalCase, snake_case, kebab-case, SCREAMING_SNAKE_CASE |
| `Interpolate` | Replaces `{placeholders}` in template strings, supports dot-notation |
| `Mask` | Masks emails, credit cards, IBANs, and arbitrary strings |
| `Random` | Cryptographically secure hex tokens, alphanumeric, and custom-charset strings |
| `Slugify` | Converts text to URL-safe slugs with transliteration |
| `Truncate` | Multibyte-aware truncation with optional word-boundary snapping |

---

## CaseConverter

Converts any supported format (camelCase, PascalCase, snake_case, kebab-case, SCREAMING_SNAKE_CASE) into any other. All formats are interchangeable.

```php
use Tez\Utils\Str\CaseConverter;

CaseConverter::toSnake('userProfileName');    // 'user_profile_name'
CaseConverter::toSnake('UserProfileName');    // 'user_profile_name'
CaseConverter::toSnake('user-profile-name'); // 'user_profile_name'
CaseConverter::toSnake('USER_PROFILE_NAME'); // 'user_profile_name'

CaseConverter::toCamel('user_profile_name'); // 'userProfileName'
CaseConverter::toPascal('user-profile-name'); // 'UserProfileName'
CaseConverter::toKebab('userProfileName');   // 'user-profile-name'
CaseConverter::toScreamingSnake('userId');   // 'USER_ID'

// Acronyms are handled correctly
CaseConverter::toSnake('HTMLParser');        // 'html_parser'
```

---

## Interpolate

Replaces `{placeholders}` in a template string with values from an array. Supports dot-notation for nested keys. Throws `InvalidArgumentException` for missing keys by default; a fallback can be provided.

```php
use Tez\Utils\Str\Interpolate;

Interpolate::render(
    'Hello {name}, you have {count} messages.',
    ['name' => 'Anna', 'count' => 5],
);
// 'Hello Anna, you have 5 messages.'

// Nested keys via dot-notation
Interpolate::render(
    'Welcome, {user.name}!',
    ['user' => ['name' => 'Clara']],
);
// 'Welcome, Clara!'

// Fallback for missing keys instead of throwing
Interpolate::render('Hello {missing}!', [], fallback: 'N/A');
// 'Hello N/A!'

// Custom delimiters
Interpolate::render('Hello :name!', ['name' => 'Bob'], prefix: ':', suffix: '');
// 'Hello Bob!'
```

---

## Mask

Masks sensitive strings for display in logs, UIs, and API responses.

```php
use Tez\Utils\Str\Mask;

// Email — keeps first character of the local part
Mask::email('test@example.com');           // 't***@example.com'
Mask::email('administrator@domain.org');   // 'a***@domain.org'

// Credit card — keeps last 4 digits, strips spaces/hyphens
Mask::creditCard('4111111111111111');      // '**** **** **** 1111'
Mask::creditCard('4000-0000-0000-9999');   // '**** **** **** 9999'

// IBAN — keeps country code + check digits (first 4) and last 2 characters
Mask::iban('DE89370400440532013000');      // 'DE89 **** **** **** **** 00'
Mask::iban('DE89 3704 0044 0532 0130 00'); // 'DE89 **** **** **** **** 00'

// Custom — configurable visible characters at start and end
Mask::custom('MySuperSecret', start: 2, end: 2);         // 'My*********et'
Mask::custom('HelloWorld', start: 2, end: 0);            // 'He********'
Mask::custom('HelloWorld', start: 2, end: 2, char: '#'); // 'He######ld'
```

---

## Random

Generates cryptographically secure random strings using `random_bytes()`. Uses rejection sampling for uniform distribution — no modulo bias.

```php
use Tez\Utils\Str\Random;

// Hex token (lowercase [0-9a-f])
Random::token();      // 32-character hex string (default)
Random::token(64);    // 64-character hex string
Random::token(7);     // odd lengths are supported

// Alphanumeric [a-zA-Z0-9]
Random::alphanumeric();    // 32 characters (default)
Random::alphanumeric(16);  // 16 characters

// Custom character set
Random::charset(12, '0123456789');  // 12-digit numeric string
Random::charset(8, 'ABCDE');        // 8 characters from 'ABCDE'
```

Throws `InvalidArgumentException` when `$length < 1` or the charset is empty.

---

## Slugify

Converts text to a URL-safe slug. Transliterates common non-ASCII characters (German umlauts, French accents, Spanish ñ, Nordic æøå, and more) before lowercasing and replacing non-alphanumeric characters.

```php
use Tez\Utils\Str\Slugify;

Slugify::slug('Hello World');          // 'hello-world'
Slugify::slug('Ärger mit Umlauten!'); // 'aerger-mit-umlauten'
Slugify::slug('Straße');              // 'strasse'
Slugify::slug('Crème brûlée');        // 'creme-brulee'
Slugify::slug('Cañon');               // 'canon'
Slugify::slug('foo/bar::baz');        // 'foo-bar-baz'
Slugify::slug('PHP 8.3');             // 'php-8-3'
Slugify::slug('---hello---');         // 'hello'

// Custom separator
Slugify::slug('Hello World', '_');    // 'hello_world'
```

---

## Truncate

Multibyte-aware truncation. By default snaps back to the last complete word so no word is split. The total result length (including suffix) never exceeds `$limit`.

```php
use Tez\Utils\Str\Truncate;

// Word-boundary truncation (default)
Truncate::chars('The quick brown fox jumps over the lazy dog', 20);
// 'The quick brown fox…'

Truncate::chars('The quick brown fox', 15);
// 'The quick…'  ← snapped back to last complete word

// Exact cut (no word-boundary snapping)
Truncate::chars('The quick brown fox', 14, wordBoundary: false);
// 'The quick bro…'

// Custom suffix
Truncate::chars('The quick brown fox jumps', 22, suffix: ' [...]');
// 'The quick brown [...]'

// No suffix
Truncate::chars('The quick brown fox', 9, suffix: '');
// 'The quick'

// Multibyte-aware — characters, not bytes
Truncate::chars('Ärger mit Umlauten', 6);
// 'Ärger…'
```

Throws `InvalidArgumentException` when `$limit` is shorter than the suffix length.

---

## Requirements

- PHP 8.3+
- No runtime dependencies

## License

MIT
