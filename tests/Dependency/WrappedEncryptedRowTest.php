<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Tests\Dependency;

use FediE2EE\PKDServer\Dependency\WrappedEncryptedRow;
use ParagonIE\CipherSweet\Backend\{
    BoringCrypto,
    FIPSCrypto,
    Key\SymmetricKey
};
use ParagonIE\CipherSweet\CipherSweet;
use ParagonIE\CipherSweet\Exception\{
    ArrayKeyException,
    CipherSweetException,
    CryptoOperationException,
    InvalidCiphertextException
};
use ParagonIE\CipherSweet\KeyProvider\StringProvider;
use PHPUnit\Framework\Attributes\{
    CoversClass,
    DataProvider
};
use PHPUnit\Framework\TestCase;
use Random\RandomException;
use SodiumException;

#[CoversClass(WrappedEncryptedRow::class)]
class WrappedEncryptedRowTest extends TestCase
{
    /**
     * @throws CryptoOperationException
     * @throws RandomException
     */
    public static function cipherSweetProvider(): array
    {
        return [
            [new CipherSweet(new StringProvider(random_bytes(32)), new FIPSCrypto())],
            [new CipherSweet(new StringProvider(random_bytes(32)), new BoringCrypto())]
        ];
    }

    /**
     * @throws ArrayKeyException
     * @throws CipherSweetException
     * @throws CryptoOperationException
     * @throws InvalidCiphertextException
     * @throws RandomException
     * @throws SodiumException
     */
    #[DataProvider("cipherSweetProvider")]
    public function testWER(CipherSweet $cs): void
    {
        $wer = new WrappedEncryptedRow($cs, 'phpunit');
        $wer->addTextField('foo');
        $map = ['foo' => new SymmetricKey(random_bytes(32))];
        $row = $wer->wrapBeforeEncrypt(['foo' => 'bar'], $map);
        $encrypted = $wer->encryptRow($row);

        $this->assertArrayHasKey('foo', $encrypted);
        $this->assertArrayHasKey('wrap_foo', $encrypted);
        $this->assertNotSame('bar', $encrypted['foo']);

        $decrypted = $wer->decryptRow($encrypted);
        $this->assertSame('bar', $decrypted['foo']);
    }
}
