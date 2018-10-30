<?php namespace Seago\DecoderRing;

require_once '../vendor/autoload.php';

$decoder_ring = new DecoderRing(__DIR__ . '/../data/data.txt');
$decoder_ring->addCipher(new Ciphers\XChaCha20Poly1305);
$decoder_ring->addCipher(new Ciphers\XSalsa20Poly1305);
$decoder_ring->beginTest();
