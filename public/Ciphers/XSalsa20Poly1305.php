<?php declare(strict_types=1);

namespace Seago\DecoderRing\Ciphers;

class XSalsa20Poly1305 extends Cipher
{
    public $name = 'XSalsa20-Poly1305';
    private static $key;
    private static $ad = 'XSalsa20-Poly1305';
    private static $nonce;

    public function __construct()
    {
        if (empty(static::$key)) {
            static::$key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        }

        if (empty(static::$nonce)) {
            static::$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        }
    }

    /**
     * @param string $ciphertext
     *
     * @return string
     */
    public function decrypt(string $ciphertext) : string
    {
        return sodium_crypto_secretbox_open($ciphertext, static::$nonce, static::$key);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function encrypt(string $message) : string
    {
        return sodium_crypto_secretbox($message, static::$nonce, static::$key);
    }
}
