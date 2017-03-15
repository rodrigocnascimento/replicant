<?php
/**
 * Carregando os arquivos de configuração
 * em variáveis de ambiente
 */

if (false === getenv('BOT_VERIFY_TOKEN')) {
    $fileContent = file_get_contents(".env");

    foreach (json_decode($fileContent, true) as $name => $value) {
        putenv("{$name}={$value}");
    }    
}