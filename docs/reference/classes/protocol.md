# Protocol

Namespace: `FediE2EE\PKDServer\Protocol`

## Classes

- [KeyWrapping](#keywrapping) - class
- [Payload](#payload) - class
- [RewrapConfig](#rewrapconfig) - class

---

## KeyWrapping

**class** `FediE2EE\PKDServer\Protocol\KeyWrapping`

**File:** [`src/Protocol/KeyWrapping.php`](../../../src/Protocol/KeyWrapping.php)

**Uses:** `FediE2EE\PKDServer\Traits\ConfigTrait`, `FediE2EE\PKD\Crypto\UtilTrait`

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `$config` | `?FediE2EE\PKDServer\ServerConfig` |  |

### Methods

#### [`__construct`](../../../src/Protocol/KeyWrapping.php#L56-L62)

Returns `void`

**Parameters:**

- `$config`: `?FediE2EE\PKDServer\ServerConfig` = null

**Throws:** `DependencyException`

#### [`rewrapSymmetricKeys`](../../../src/Protocol/KeyWrapping.php#L76-L106)

Returns `void`

Initiate a rewrapping of the symmetric keys associated with a record.

**Parameters:**

- `$merkleRoot`: `string`
- `$keyMap`: `?FediE2EE\PKD\Crypto\AttributeEncryption\AttributeKeyMap` = null

**Throws:** `CacheException`, `CryptoException`, `DateMalformedStringException`, `DependencyException`, `HPKEException`, `JsonException`, `SodiumException`, `TableException`

#### [`retrieveLocalWrappedKeys`](../../../src/Protocol/KeyWrapping.php#L113-L124)

Returns `FediE2EE\PKD\Crypto\AttributeEncryption\AttributeKeyMap`

**Parameters:**

- `$merkleRoot`: `string`

**Throws:** `HPKEException`, `JsonException`, `TableException`

#### [`hpkeWrapSymmetricKeys`](../../../src/Protocol/KeyWrapping.php#L126-L132)

Returns `string`

**Parameters:**

- `$keyMap`: `FediE2EE\PKD\Crypto\AttributeEncryption\AttributeKeyMap`

#### [`hpkeUnwrap`](../../../src/Protocol/KeyWrapping.php#L137-L141)

Returns `string`

**Parameters:**

- `$ciphertext`: `string`

**Throws:** `HPKEException`

#### [`serializeKeyMap`](../../../src/Protocol/KeyWrapping.php#L146-L160)

Returns `string`

**Parameters:**

- `$keyMap`: `FediE2EE\PKD\Crypto\AttributeEncryption\AttributeKeyMap`

**Throws:** `BaseJsonException`

#### [`deserializeKeyMap`](../../../src/Protocol/KeyWrapping.php#L165-L179)

Returns `FediE2EE\PKD\Crypto\AttributeEncryption\AttributeKeyMap`

**Parameters:**

- `$plaintextJsonString`: `string`

**Throws:** `JsonException`

#### [`decryptAndGetRewrapped`](../../../src/Protocol/KeyWrapping.php#L196-L225)

Returns `array`

Usage:

[$message, $rewrappedKeys] = $keyWrapping->decryptAndRewrapp

**Parameters:**

- `$merkleRoot`: `string`
- `$wrappedKeys`: `?string` = null

**Throws:** `BundleException`, `CryptoException`, `DependencyException`, `HPKEException`, `InputException`, `InvalidArgumentException`, `JsonException`, `SodiumException`

#### [`unwrapLocalMessage`](../../../src/Protocol/KeyWrapping.php#L235-L242)

Returns `array`

**Parameters:**

- `$encryptedMessage`: `string`
- `$wrappedKeys`: `string`

**Throws:** `BundleException`, `CryptoException`, `HPKEException`, `InputException`, `JsonException`

#### [`getRewrappedFor`](../../../src/Protocol/KeyWrapping.php#L248-L279)

Returns `array`

**Parameters:**

- `$merkleRoot`: `string`

**Throws:** `InputException`

#### [`appCache`](../../../src/Protocol/KeyWrapping.php#L54-L57)

Returns `FediE2EE\PKDServer\AppCache`

**Parameters:**

- `$namespace`: `string`
- `$defaultTTL`: `int` = 60

**Throws:** `DependencyException`

#### [`table`](../../../src/Protocol/KeyWrapping.php#L64-L87)

Returns `FediE2EE\PKDServer\Table`

**Parameters:**

- `$tableName`: `string`

**Throws:** `CacheException`, `DependencyException`, `TableException`

#### [`injectConfig`](../../../src/Protocol/KeyWrapping.php#L89-L92)

Returns `void`

**Parameters:**

- `$config`: `FediE2EE\PKDServer\ServerConfig`

#### [`config`](../../../src/Protocol/KeyWrapping.php#L97-L107)

Returns `FediE2EE\PKDServer\ServerConfig`

**Throws:** `DependencyException`

#### [`setWebFinger`](../../../src/Protocol/KeyWrapping.php#L112-L116)

Returns `self`

This is intended for mocking in unit tests

**Parameters:**

- `$wf`: `FediE2EE\PKDServer\ActivityPub\WebFinger`

#### [`webfinger`](../../../src/Protocol/KeyWrapping.php#L123-L130)

Returns `FediE2EE\PKDServer\ActivityPub\WebFinger`

**Parameters:**

- `$http`: `?GuzzleHttp\Client` = null

**Throws:** `CertaintyException`, `DependencyException`, `SodiumException`

#### [`parseUrlHost`](../../../src/Protocol/KeyWrapping.php#L136-L143)

static · Returns `?string`

**Parameters:**

- `$url`: `string`

#### [`assertArray`](../../../src/Protocol/KeyWrapping.php#L151-L157)

static · Returns `array`

**Parameters:**

- `$result`: `object|array`

**Throws:** `TypeError`

#### [`assertString`](../../../src/Protocol/KeyWrapping.php#L162-L168)

static · Returns `string`

**Parameters:**

- `$value`: `mixed`

**Throws:** `TypeError`

#### [`assertStringOrNull`](../../../src/Protocol/KeyWrapping.php#L170-L179)

static · Returns `?string`

**Parameters:**

- `$value`: `mixed`

#### [`assertInt`](../../../src/Protocol/KeyWrapping.php#L184-L193)

static · Returns `int`

**Parameters:**

- `$value`: `mixed`

**Throws:** `TypeError`

#### [`rowToStringArray`](../../../src/Protocol/KeyWrapping.php#L200-L210)

static · Returns `array`

**Parameters:**

- `$row`: `object|array`

**Throws:** `TypeError`

#### [`decryptedString`](../../../src/Protocol/KeyWrapping.php#L216-L226)

static · Returns `string`

**Parameters:**

- `$row`: `array`
- `$key`: `string`

**Throws:** `TypeError`

#### [`blindIndexValue`](../../../src/Protocol/KeyWrapping.php#L233-L243)

static · Returns `string`

**Parameters:**

- `$blindIndex`: `array|string`
- `$key`: `?string` = null

#### [`jsonDecode`](../../../src/Protocol/KeyWrapping.php#L16-L19)

static · Returns `array`

**Parameters:**

- `$json`: `string`

**Throws:** `BaseJsonException`

#### [`jsonDecodeObject`](../../../src/Protocol/KeyWrapping.php#L24-L27)

static · Returns `stdClass`

**Parameters:**

- `$json`: `string`

**Throws:** `BaseJsonException`

#### [`jsonEncode`](../../../src/Protocol/KeyWrapping.php#L33-L39)

static · Returns `string`

**Parameters:**

- `$data`: `mixed`

**Throws:** `BaseJsonException`

#### [`assertAllArrayKeysExist`](../../../src/Protocol/KeyWrapping.php#L29-L34)

static · Returns `void`

This method throws an InputException if any of the expected keys are absent.

It does not return anything.

**Parameters:**

- `$target`: `array`
- `...$arrayKeys`: `string`

**Throws:** `InputException`

#### [`allArrayKeysExist`](../../../src/Protocol/KeyWrapping.php#L42-L49)

static · Returns `bool`

This method returns true if every expected array key is found in the target array.

Otherwise, it returns false. This is useful for input validation.

**Parameters:**

- `$target`: `array`
- `...$arrayKeys`: `string`

#### [`constantTimeSelect`](../../../src/Protocol/KeyWrapping.php#L64-L81)

Returns `string`

This is a constant-time conditional select. It should be read like a ternary operation.

$result = ClassWithTrait::constantTimeSelect(1, $left, $right); -> $result === $left. $result = ClassWithTrait::constantTimeSelect(0, $left, $right); -> $result === $right.

**Parameters:**

- `$select`: `int`
- `$left`: `string`
- `$right`: `string`

**Throws:** `CryptoException`

#### [`dos2unix`](../../../src/Protocol/KeyWrapping.php#L91-L94)

static · Returns `string`

Normalize line-endings to UNIX-style (LF rather than CRLF).

This is mostly used for PEM-encoded strings.

**Parameters:**

- `$in`: `string`

#### [`preAuthEncode`](../../../src/Protocol/KeyWrapping.php#L112-L125)

static · Returns `string`

This is an implementation of PAE() from PASETO. It encodes an array of strings into a flat string consisting of:

1. The number of pieces. 2. For each piece: 1. The length of the piece (in bytes). 2. The contents of the piece. This allows multipart messages to have an injective canonical representation before passing ot a hash function (or other cryptographic function).

**Parameters:**

- `$pieces`: `array`

#### [`sortByKey`](../../../src/Protocol/KeyWrapping.php#L132-L140)

static · Returns `void`

This sorts the target array in-place, by its keys, including child arrays.

Used for ensuring arrays are sorted before JSON encoding.

**Parameters:**

- `$arr`: `array`

#### [`LE64`](../../../src/Protocol/KeyWrapping.php#L147-L150)

static · Returns `string`

Mostly used by preAuthEncode() above. This packs an integer as 8 bytes.

**Parameters:**

- `$n`: `int`

#### [`stringToByteArray`](../../../src/Protocol/KeyWrapping.php#L155-L162)

Returns `array`

Get an array of bytes representing the input string.

**Parameters:**

- `$str`: `string`

#### [`stripNewlines`](../../../src/Protocol/KeyWrapping.php#L170-L204)

static · Returns `string`

Strip all newlines (CR, LF) characters from a string.

**Parameters:**

- `$input`: `string`

---

## Payload

**class** `FediE2EE\PKDServer\Protocol\Payload`

**File:** [`src/Protocol/Payload.php`](../../../src/Protocol/Payload.php)

**Uses:** `FediE2EE\PKDServer\Traits\JsonTrait`

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `$message` | `FediE2EE\PKD\Crypto\Protocol\ProtocolMessageInterface` | (readonly)  |
| `$keyMap` | `FediE2EE\PKD\Crypto\AttributeEncryption\AttributeKeyMap` | (readonly)  |
| `$rawJson` | `string` | (readonly)  |

### Methods

#### [`__construct`](../../../src/Protocol/Payload.php#L18-L22)

Returns `void`

**Parameters:**

- `$message`: `FediE2EE\PKD\Crypto\Protocol\ProtocolMessageInterface`
- `$keyMap`: `FediE2EE\PKD\Crypto\AttributeEncryption\AttributeKeyMap`
- `$rawJson`: `string`

#### [`decrypt`](../../../src/Protocol/Payload.php#L24-L30)

Returns `FediE2EE\PKD\Crypto\Protocol\ProtocolMessageInterface`

#### [`decode`](../../../src/Protocol/Payload.php#L36-L39)

Returns `array`

**Throws:** `JsonException`

#### [`getMerkleTreePayload`](../../../src/Protocol/Payload.php#L44-L58)

Returns `string`

**Throws:** `JsonException`

#### [`jsonDecode`](../../../src/Protocol/Payload.php#L16-L19)

static · Returns `array`

**Parameters:**

- `$json`: `string`

**Throws:** `BaseJsonException`

#### [`jsonDecodeObject`](../../../src/Protocol/Payload.php#L24-L27)

static · Returns `stdClass`

**Parameters:**

- `$json`: `string`

**Throws:** `BaseJsonException`

#### [`jsonEncode`](../../../src/Protocol/Payload.php#L33-L39)

static · Returns `string`

**Parameters:**

- `$data`: `mixed`

**Throws:** `BaseJsonException`

---

## RewrapConfig

**class** `FediE2EE\PKDServer\Protocol\RewrapConfig`

**File:** [`src/Protocol/RewrapConfig.php`](../../../src/Protocol/RewrapConfig.php)

**Implements:** `JsonSerializable`

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `$cs` | `string` | (readonly)  |
| `$encapsKey` | `string` | (readonly)  |

### Methods

#### [`__construct`](../../../src/Protocol/RewrapConfig.php#L23-L26)

Returns `void`

**Parameters:**

- `$cs`: `string`
- `$encapsKey`: `string`

#### [`from`](../../../src/Protocol/RewrapConfig.php#L31-L42)

static · Returns `self`

**Parameters:**

- `$cs`: `ParagonIE\HPKE\HPKE`
- `$encapsKey`: `ParagonIE\HPKE\Interfaces\EncapsKeyInterface`

**Throws:** `DependencyException`

#### [`fromJson`](../../../src/Protocol/RewrapConfig.php#L44-L57)

static · Returns `self`

**Parameters:**

- `$json`: `string`

#### [`jsonSerialize`](../../../src/Protocol/RewrapConfig.php#L63-L69)

Returns `array`

**Attributes:** `#[Override]`

#### [`getCipherSuite`](../../../src/Protocol/RewrapConfig.php#L74-L77)

Returns `ParagonIE\HPKE\HPKE`

**Throws:** `HPKEException`

#### [`getEncapsKey`](../../../src/Protocol/RewrapConfig.php#L84-L91)

Returns `ParagonIE\HPKE\Interfaces\EncapsKeyInterface`

**Throws:** `DependencyException`, `HPKEException`

---

