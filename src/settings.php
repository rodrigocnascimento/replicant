<?php
/**
 * Carregando os arquivos de configuração
 * em variáveis de ambiente
 */
$fileContent = file_get_contents(".env");

foreach (json_decode($fileContent, true) as $name => $value) {
    putenv("{$name}={$value}");
}

return [
    'botConfigs' => [
        'Facebook' => [
            'hub_verify_token' => getenv('BOT_VERIFY_TOKEN'),
            'Bots' => [
                getenv('BOT_ID') => [
                    'id' => getenv('BOT_ID'),
                    'className' => getenv('BOT_CLASS_NAME'),
                    'token' => getenv('BOT_TOKEN')
                ]
            ]
        ]
    ]
];
