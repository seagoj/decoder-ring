<?php declare(strict_types=1);

namespace Seago\DecoderRing\Ciphers;

class XChaCha20Poly1305 extends Cipher
{
    public $name = 'XChaCha20-Poly1305';
    private static $key;
    private static $ad = 'XChaCHa20-Poly1305';
    private static $nonce;

    public function __construct()
    {
        if (empty(static::$key)) {
            static::$key = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES);
        }

        if (empty(static::$nonce)) {
            static::$nonce = random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES );
        }
    }

    /**
     * @param string $ciphertext
     *
     * @return string
     */
    public function decrypt(string $ciphertext) : string
    {
        return sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
            $ciphertext,
            static::$ad,
            static::$nonce,
            static::$key
        );
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function encrypt(string $message) : string
    {
        return sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
            $message,
            static::$ad,
            static::$nonce,
            static::$key
        );
    }
}
