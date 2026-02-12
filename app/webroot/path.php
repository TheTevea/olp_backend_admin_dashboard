<?php
// Url
define('LINK_URL', 'http://localhost/');
define('LINK_URL_SSL', '--no-check-certificate');
define('SERVER_ID', 'OLM');
define('SERVER_API', 'http://localhost:8095/camticketapi/');
define('SERVER_TYPE', '1'); // 1: Master; 2: Slave
define('LINK_SYNC_TIME', 'https://trans.utlog.net/v1/transaction/gettime');
define('SCRIPT_PATH', '/mnt/ticketSync/');
// UPLOAD URL
// define('PHOTO_PATH', 'http://localhost/'); // Local
define('PHOTO_PATH', 'https://qacl.udaya-tech.com/'); // QA
// define('PHOTO_PATH', 'https://oc.utlog.net/'); // Production

// PAYMENT URL REFERENCE
// define('PAYMENT_URL_REF', 'http://localhost/payments/index.php'); // Local
define('PAYMENT_URL_REF', 'https://qacl.udaya-tech.com/1035_OlongpichT/payments/index.php'); // QA
// define('PAYMENT_URL_REF', 'https://olpexpress.com'); // Production

// WEBSITE SUCCESS PAGE
// define('WEB_BUS_SUCCESS_PAGE', 'http://localhost:3000/'); // Local
define('WEB_BUS_SUCCESS_PAGE', 'https://qaolongpichminiapp.udaya-tech.com/'); // QA
// define('WEB_BUS_SUCCESS_PAGE', 'https://olpexpress.com/'); // Production

// PAYMENT URL
// define('PAYMENT_URL', 'http://localhost/payments/'); // Local
define('PAYMENT_URL', 'https://qacl.udaya-tech.com/1035_OlongpichT/payments/'); // QA
// define('PAYMENT_URL', 'https://olongpicts.utebi.com/payments/'); // Production

?>