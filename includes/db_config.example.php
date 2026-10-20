<?php
/**
 * db_config.example.php — copy this to db_config.php and fill in your credentials.
 * db_config.php is GITIGNORED — never commit real credentials.
 *
 * Hostinger values:  find them in hPanel → Databases → MySQL Databases
 * MAMP local values: host = 127.0.0.1, port = 8889, user = root, pass = root
 */
return [
    // --- Local (MAMP) ---
    'host'     => '127.0.0.1',
    'port'     => 8889,
    'dbname'   => 'uvora_clinic',
    'username' => 'uvora_dev',
    'password' => 'uvora_dev_2026',
    'socket'   => '/Applications/MAMP/tmp/mysql/mysql.sock', // set '' to use TCP instead

    // --- Hostinger (fill these before deploying) ---
    // 'host'     => 'localhost',
    // 'port'     => 3306,
    // 'dbname'   => 'uXXXXXXXX_uvora',
    // 'username' => 'uXXXXXXXX_uvora',
    // 'password' => 'YOUR_HOSTINGER_DB_PASSWORD',
    // 'socket'   => '',

    // --- Cloudflare Turnstile (opsional; kosongkan keduanya untuk menonaktifkan) ---
    // Buat widget di dash.cloudflare.com → Turnstile, tambahkan domain situs (mis. uvoraclinic.com).
    'turnstile_site'   => '',
    'turnstile_secret' => '',
];
