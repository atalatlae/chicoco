<?php

namespace Chicoco\Core;

class Crypt
{
    private $key;
    private $iv;
    private $ivLen;
    private $cipherAlg;
    private $option;

    public function __construct(string $key, string $cipherAlg = 'aes-128-cbc')
    {
        $this->key = $key;
        $this->cipherAlg = $cipherAlg;
        $this->option = OPENSSL_RAW_DATA;

        if (!in_array($this->cipherAlg, openssl_get_cipher_methods())) {
            throw new Exception('Unknown cipher algorithm');
        }

        $this->ivLen = openssl_cipher_iv_length($this->cipherAlg);
    }

    public function crypt($data, bool $encoded = true)
    {
        $this->iv = openssl_random_pseudo_bytes($this->ivLen);
        $cipherData = openssl_encrypt(
            $data,
            $this->cipherAlg,
            $this->key,
            $this->option,
            $this->iv
        );

        if ($encoded) {
            return base64_encode($this->iv.$cipherData);
        }
        return $this->iv.$cipherData;
    }

    public function decrypt($data, bool $encoded = true)
    {
        if ($encoded) {
            $data = base64_decode($data);
        }

        $this->iv = substr($data, 0, $this->ivLen);
        $data = substr($data, $this->ivLen);

        $clearData = openssl_decrypt(
            $data,
            $this->cipherAlg,
            $this->key,
            $this->option,
            $this->iv
        );
        return $clearData;
    }
}

