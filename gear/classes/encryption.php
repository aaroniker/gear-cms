<?php

class encryption {

    const CIPHER = 'aes-256-cbc';
    const HASH_FUNCTION = 'sha256';

    public static function encrypt($plain) {

        if(!function_exists('openssl_cipher_iv_length') || !function_exists('openssl_random_pseudo_bytes') || !function_exists('openssl_encrypt')) {
            throw new Exception("Encryption function don't exists");
        }

        $iv_size = openssl_cipher_iv_length(self::CIPHER);
        $iv = openssl_random_pseudo_bytes($iv_size);

        $key = mb_substr(hash(self::HASH_FUNCTION, config::get('encryptionKey').config::get('hmacSalt')), 0, 32, '8bit');

        $encrypted_string = openssl_encrypt($plain, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);

        $ciphertext = $iv . $encrypted_string;

        $hmac = hash_hmac('sha256', $ciphertext, $key);

        return $hmac . $ciphertext;

    }

    public static function decrypt($ciphertext) {

        if(!function_exists('openssl_cipher_iv_length') || !function_exists('openssl_decrypt')) {
            throw new Exception("Encryption function don't exists");
        }

        $key = mb_substr(hash(self::HASH_FUNCTION, config::get('encryptionKey').config::get('hmacSalt')), 0, 32, '8bit');

        $macSize = 64;
        $hmac = mb_substr($ciphertext, 0, $macSize, '8bit');
        $iv_cipher = mb_substr($ciphertext, $macSize, null, '8bit');

        $originalHmac = hash_hmac('sha256', $iv_cipher, $key);
        if (!self::hashEquals($hmac, $originalHmac)) {
            return false;
        }

        $iv_size = openssl_cipher_iv_length(self::CIPHER);
        $iv = mb_substr($iv_cipher, 0, $iv_size, '8bit');
        $cipher = mb_substr($iv_cipher, $iv_size, null, '8bit');

        return openssl_decrypt($cipher, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);

    }

    private static function hashEquals($hmac, $compare) {

        if (function_exists('hash_equals')) {
            return hash_equals($hmac, $compare);
        }

        $hashLength = mb_strlen($hmac, '8bit');
        $compareLength = mb_strlen($compare, '8bit');

        if ($hashLength !== $compareLength) {
            return false;
        }

        $result = 0;

        for ($i = 0; $i < $hashLength; $i++) {
            $result |= (ord($hmac[$i]) ^ ord($compare[$i]));
        }

        return $result === 0;

    }

}

?>
