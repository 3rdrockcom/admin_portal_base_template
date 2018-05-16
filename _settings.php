<?php
//ENV: LOCAL/STAGING/DEV/PROD
$url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
define('ENV', file_exists('c:/Windows/hh.exe')? 'LOCAL': (strstr($url,'epointjetdev')? 'DEV': 'PROD'));
define('MLOC_NAMESPACE', '');

define('PROGRAM', 'MLOC');
settings::setEnv(ENV);

class settings {
    static $core_db;
    static $email_recipients;
    static $sms_recipients;
    public static function setEnv($environment) {
        switch ($environment) {
            case 'LOCAL':
                define('BASE_URL', 'http://localhost/portal/');
                define('EPOINT_API', 'http://epointjetdev.epointserver.com/api/merchant/');
                self::$core_db = array(
                    'host'  => 'localhost',
                    'user'  => 'root',
                    'pass'  => '',
                    'name'  => 'base_db',
                );
                define('EPOINT_MTID', '65626');
                define('EPOINT_USER', 'mlocmtusr01');
                define('EPOINT_PASSWORD', '1h6W8C6H20V4');
                break;
            case 'STAGING':
            case 'DEV':
                define('BASE_URL', 'http://mlocdev.epointserver.com/');
                define('EPOINT_API', 'http://epointjetdev.epointserver.com/api/merchant/');
                self::$core_db = array(
                    'host'  => 'localhost',
                    'user'  => 'superadmin',
                    'pass'  => '123456Xx.',
                    'name'  => 'mloc',
                );
                define('EPOINT_MTID', '65626');
                define('EPOINT_USER', 'mlocmtusr01');
                define('EPOINT_PASSWORD', '1h6W8C6H20V4');
                break;
            case 'PROD':
                define('BASE_URL', 'http://mlocdev.epointserver.com/');//test URL
                define('EPOINT_API', 'http://epointjetdev.epointserver.com/api/merchant/');//test URL
                self::$core_db = array(
                    'host'  => 'localhost',
                    'user'  => 'superadmin',
                    'pass'  => '123456Xx.',
                    'name'  => 'mloc',
                );
                define('EPOINT_MTID', '65626');
                define('EPOINT_USER', 'mlocmtusr01');
                define('EPOINT_PASSWORD', '1h6W8C6H20V4');
                break;
        }
        self::$email_recipients = array(
            'from'      => 'noreply@epointwallet.com',
            'bcc'       => 'bom.lim@worldonwireless.com',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_user' => 'noreply@epointwallet.com',
            'smtp_pass' => '10reB2017',
            'protocol'  => 'smtp',
            'smtp_port' => '465',
        );

        self::$sms_recipients = array(
            'user'      => 'epointsms',
            'password'  => 'Rarvab4u',
            'sender'    => 'epointsms',
            'send_url'  => 'http://api2.infobip.com/api/v3/sendsms/plain?',
        );
    }

}

