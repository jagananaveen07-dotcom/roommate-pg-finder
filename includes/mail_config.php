<?php

$config = [
    'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'smtp_port' => (int)(getenv('SMTP_PORT') ?: 587),
    'smtp_secure' => getenv('SMTP_SECURE') ?: 'tls',
    'smtp_auth' => true,

    'smtp_user' => getenv('SMTP_USER'),
    'smtp_pass' => getenv('SMTP_PASS'),

    'from_email' => getenv('MAIL_FROM') ?: getenv('SMTP_USER'),
    'from_name' => getenv('MAIL_FROM_NAME') ?: 'Roommate & PG Finder',
    'reply_to' => getenv('MAIL_REPLY_TO') ?: getenv('SMTP_USER')
];

if (empty($config['smtp_user']) || empty($config['smtp_pass'])) {
    $localConfig = __DIR__ . '/mail_config.local.php';

    if (file_exists($localConfig)) {
        $local = require $localConfig;

        $config['smtp_user'] = $local['smtp_user'] ?? null;
        $config['smtp_pass'] = $local['smtp_pass'] ?? null;

        $config['from_email'] = $local['from_email'] ?? $config['smtp_user'];
        $config['reply_to'] = $local['reply_to'] ?? $config['smtp_user'];
    }
}

return $config;