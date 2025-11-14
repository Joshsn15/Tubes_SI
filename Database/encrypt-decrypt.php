<?php

function encryptFunc(string $plainText, string $key): string
{
    $secretKey = hash('sha256', $key, true);
    $cipher = "AES-256-CBC";

    $iv = openssl_random_pseudo_bytes(16);

    $encrypted = openssl_encrypt(
        $plainText,
        $cipher,
        $secretKey,
        OPENSSL_RAW_DATA,
        $iv
    );

    return base64_encode($iv . $encrypted);
}

function decryptFunc(string $encoded, string $key): string|false
{
    $secretKey = hash('sha256', $key, true);
    $cipher = "AES-256-CBC";

    $data = base64_decode($encoded);

    $iv = substr($data, 0, 16);

    $cipherText = substr($data, 16);

    return openssl_decrypt(
        $cipherText,
        $cipher,
        $secretKey,
        OPENSSL_RAW_DATA,
        $iv
    );
}
