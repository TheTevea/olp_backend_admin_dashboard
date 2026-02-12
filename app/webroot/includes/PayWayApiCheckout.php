<?php
/*
|--------------------------------------------------------------------------
| ABA PayWay API URL
|--------------------------------------------------------------------------
| API URL that is provided by PayWay must be required in your post form
|
*/
// Dev
// define('ABA_PAYWAY_API_URL', 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/purchase');
// define('ABA_CHECK_TRANSACTION_API_URL', 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/check-transaction-2');
// define('ABA_CHECK_TRANSACTION_LIST_API_URL', 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/transaction-list');
// Production
define('ABA_PAYWAY_API_URL', 'https://checkout.payway.com.kh/api/payment-gateway/v1/payments/purchase');
define('ABA_CHECK_TRANSACTION_API_URL', 'https://checkout.payway.com.kh/api/payment-gateway/v1/payments/check-transaction-2');
define('ABA_CHECK_TRANSACTION_LIST_API_URL', 'https://checkout.payway.com.kh/api/payment-gateway/v1/payments/transaction-list');

/*
|--------------------------------------------------------------------------
| ABA PayWay API KEY
|--------------------------------------------------------------------------
| API KEY that is generated and provided by PayWay must be required in your post form
|
*/
// Dev
// define('ABA_PAYWAY_API_KEY', '978ae058-9967-4e61-b3db-52bd418264bb');
// Production
define('ABA_PAYWAY_API_KEY', 'ec6705ab-df8e-4156-a2ba-f01a214568f6');

/*
|--------------------------------------------------------------------------
| ABA PayWay Merchant ID
|--------------------------------------------------------------------------
| Merchant ID that is generated and provided by PayWay must be required in your post form
|
*/
// Dev
// define('ABA_PAYWAY_MERCHANT_ID', 'olpexpress');
// Production
define('ABA_PAYWAY_MERCHANT_ID', 'olpexpress');


class PayWayApiCheckout {

    /**
     * Returns the getHash
     * For PayWay security, you must follow the way of encryption for hash.
     *
     * @param string $transactionId
     * @param string $amount
     *
     * @return string getHash
     */
    public static function getHash($req_time, $merchant_id, $transactionId, $amount, $payment_option, $lifeTime, $apiKey) {
        $hash = base64_encode(hash_hmac('sha512', $req_time .$merchant_id . $transactionId . $amount .$payment_option.$lifeTime, $apiKey, true));
        return $hash;
    }

    /**
     * Returns the getApiUrl
     *
     * @return string getApiUrl
     */
    public static function getApiUrl() {
        return ABA_PAYWAY_API_URL;
    }
}
