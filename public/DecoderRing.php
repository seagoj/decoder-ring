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
    private function readFile() : \Generator
    {
        if (!file_exists($this->path)) {
            throw new \Exception('Missing datafile.');
        }
        $handle = fopen($this->path, 'r');
        while(!feof($handle)) {
            $line = fgets($handle);
            if (!is_string($line)) {
                continue;
            }
            yield trim($line);
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
            $enc_duration = 0.0;
            $dec_duration = 0.0;
            foreach ($this->readFile() as $row) {
                $ciphertext = $this->profile(function () use ($cipher, $row) {
                   return $cipher->encrypt($row);
                }, $enc_duration);

                $message = $this->profile(function () use ($cipher, $ciphertext) {
                    return $cipher->decrypt($ciphertext);
                }, $dec_duration);

                if ($message != $row) {
                    throw new \Exception('Invalid cipher.');
                }
            }
            echo "- encrypt:\t {$enc_duration}<br />";
            echo "- decrypt:\t {$dec_duration}<br />";
            echo "<br />";
        }
    }

    /**
     * @param callable $method
     * @param float $duration
     *
     * @return mixed
     */
    private function profile(callable $method, float &$duration) {
        $start = microtime(true);
        $response = $method();
        $stop = microtime(true);
        $duration += ($stop-$start);

        return $response;
    }

}
