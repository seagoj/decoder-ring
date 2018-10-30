<?php declare(strict_types=1);

namespace Seago\DecoderRing\Ciphers;

abstract class Cipher
{
    public $name;

    abstract public function encrypt(string $message) : string;
    abstract public function decrypt(string $ciphertext) : string;
}
