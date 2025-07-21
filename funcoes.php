<?php
$chave = getenv("LOCKMIND_DB_KEY");
function criptografarSenha($senha)
{
    global $chave;
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $senha_crip = openssl_encrypt($senha, 'aes-256-cbc', $chave, 0, $iv);
    return base64_encode($iv . $senha_crip);
}

function descriptografarSenha($senha_criptografada_base64)
{
    global $chave;
    $dados = base64_decode($senha_criptografada_base64);
    $iv = substr($dados, 0, openssl_cipher_iv_length('aes-256-cbc'));
    $senha_crip = substr($dados, openssl_cipher_iv_length('aes-256-cbc'));
    return openssl_decrypt($senha_crip, 'aes-256-cbc', $chave, 0, $iv);
}

?>