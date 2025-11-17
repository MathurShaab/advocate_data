<?php
// functions.php
require_once 'config.php';

// Encrypt (returns base64(iv):base64(ciphertext))
function encrypt_field($plaintext) {
    $key = AES_KEY;
    $ivlen = openssl_cipher_iv_length('AES-256-CBC');
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv) . ':' . base64_encode($ciphertext);
}

// Decrypt
function decrypt_field($stored) {
    if (!$stored) return null;
    $key = AES_KEY;
    $parts = explode(':', $stored, 2);
    if (count($parts) !== 2) return null;
    $iv = base64_decode($parts[0]);
    $ciphertext = base64_decode($parts[1]);
    if ($iv === false || $ciphertext === false) return null;
    $plain = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return $plain;
}

// Validate enrollment number: pattern x1234xx1234 (x = alphabet)
function valid_enrollment($en) {
    return preg_match('/^[A-Za-z]\d{4}[A-Za-z]{2}\d{4}$/', $en);
}

function valid_mobile($mobile) {

    // Must start with 6–9 and must be exactly 10 digits
    if (!preg_match('/^[6-9][0-9]{9}$/', $mobile)) {
        return false;
    }

    // Reject numbers like 0000000000, 7777777777, 9999999999
    if (preg_match('/^([0-9])\1{9}$/', $mobile)) {
        return false;
    }

    return true;
}


function valid_pin($p) {
    return preg_match('/^\d{6}$/', $p);
}

// CSRF
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}