<?php declare(strict_types=1);

namespace Seago\DecoderRing;

class DecoderRing
{
    private $path;
    private $ciphers;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->ciphers = [];
    }

    /**
     * @param mixed $cipher
     *
     * @return void
     */
    public function addCipher($cipher) : void
    {
        $this->ciphers[] = $cipher;
    }

    /**
     * @param string $path
     *
     * @return \Generator
     */
    private function readFile(string $path) : \Generator
    {
        $handle = fopen($path, 'r');
        while(!feof($handle)) {
            if (!is_bool($handle)) {
                break;
            }
            yield trim(fgets($handle));
        }
        fclose($handle);
    }

    /**
     * @return void
     */
    public function beginTest() : void
    {
        foreach ($this->ciphers as $cipher) {
            echo "## {$cipher->name}:<br \>";
            echo "- encrypt:\t";
            $start = microtime(true);
            foreach ($this->readFile($this->path) as $row) {
                $cipher->encrypt($row);
            }
            echo microtime(true) - $start . "<br />";

            echo "- roundtrip:\t";
            $start = microtime(true);
            foreach ($this->readFile($this->path) as $row) {
                $decrypted = $cipher->decrypt(
                    $cipher->encrypt($row)
                );
                if ($row != $decrypted) {
                    throw new \Exception('Not two way.');
                }
            }
            echo microtime(true) - $start . "<br /><br />";
        }
    }
}
