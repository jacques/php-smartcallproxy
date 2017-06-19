<?php
/**
 * Client for Smartcall's Restful Proxy.
 *
 * @author    Jacques Marneweck <jacques@powertrip.co.za>
 * @copyright 2016-2017 Jacques Marneweck.  All rights strictly reserved.
 * @license   MIT
 */

namespace Jacques\SmartCallProxy;

class Client extends \GuzzleHttp\Client
{
    /**
     * @const string Version number
     */
    const VERSION = '0.1.1';

    /**
     * Defaults to expecting that Apache Tomcat runs on port 8080 on localhost
     * (127.0.0.1).
     *
     * @var array[]
     */
    protected $options = [
        'scheme'   => 'http',
        'hostname' => 'localhost',
        'port'     => '8080',
    ];

    /**
     * @param   $options array
     *
     * @return void
     */
    public function __construct($options = [])
    {
        /*
         * Allow on instantiation to overwrite the defaults
         */
        $this->options = array_merge(
            $this->options,
            $options
        );

        $config = [
            'base_uri' => sprintf(
                '%s://%s:%s/',
                $this->options['scheme'],
                $this->options['hostname'],
                $this->options['port']
            ),
            'headers' => [
                'User-Agent' => 'SmartcallRestfulProxyClient-PHP/'.self::VERSION.' '.\GuzzleHttp\default_user_agent(),
            ],
        ];

        parent::__construct($config);
    }

    /**
     * Facility provided to cancel recharges.  This does not work if the recharge
     * has been processed by the MNO.
     *
     * @param int $reference Order Reference from SmartCall
     *
     * @throws Exception
     *
     * @return array
     */
    public function cancelRecharge($reference)
    {
        try {
            $response = $this->post(
                '/SmartcallRestfulProxy/cancel_recharge_js',
                [
                    'json' => [
                        'orderReferenceId' => $reference,
                    ],
                ]
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $this->parseError($e);
        }
    }

    /**
     * Transfer funds to another SmartCall Dealer Account.
     *
     * @param int  $amount  Amount in rands (ZAR) to transfer to the recipients SmartCall Dealer Account
     * @param int  $msisdn  MSISDN of the account to receive the funds being transfered
     * @param bool $sendSms true = send sms | false do not send a sms
     *
     * @throws Exception
     *
     * @return array
     */
    public function fundsTransfer($amount, $msisdn, $sendSms)
    {
        try {
            $response = $this->post(
                '/SmartcallRestfulProxy/funds_transfer_js',
                [
                    'json' => [
                        'amount'          => $amount,
                        'recipientMsisdn' => $msisdn,
                        'sendSms'         => (bool) $sendSms,
                    ],
                ]
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $this->parseError($e);
        }
    }

    /**
     * Fetches the Dealer Balance from SmartCall.
     *
     * @throws Exception
     *
     * @return array
     */
    public function getDealerBalance()
    {
        try {
            $response = $this->get('/SmartcallRestfulProxy/balance');

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $this->parseError($e);
        }
    }

    /**
     * Fetches the Dealer Balance from SmartCall.
     *
     * @throws Exception
     *
     * @return array
     */
    public function isDealerRegistered($msisdn)
    {
        try {
            $response = $this->get(
                sprintf(
                    '/SmartcallRestfulProxy/registered/%s',
                    $msisdn
                )
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $this->parseError($e);
        }
    }

    /**
     * Fetches the Product from SmartCall.
     *
     * @param int $productId Product Identifier
     *
     * @throws Exception
     *
     * @return array
     */
    public function getProduct($productId)
    {
        try {
            $response = $this->get(
                sprintf(
                    '/SmartcallRestfulProxy/product_js/%d',
                    $productId
                )
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $this->parseError($e);
        }
    }

    /**
     * Fetches the Product List from SmartCall.
     *
     * @throws Exception
     *
     * @return array
     */
    public function getProducts()
    {
        try {
            $response = $this->get('/SmartcallRestfulProxy/all_networks_js');

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $this->parseError($e);
        }
    }

    /**
     * Fetches the details of the last transaction processed from SmartCall.
     *
     * @throws Exception
     *
     * @return array
     */
    public function getLastTransaction()
    {
        try {
            $response = $this->get('/SmartcallRestfulProxy/last_transaction_js');

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $this->parseError($e);
        }
    }

    /**
     * Fetches the Product List by the specified network identifier from SmartCall.
     *
     * @param int $networkId identifier for the network
     *
     * @throws Exception
     *
     * @return array
     */
    public function getProductsByNetwork($networkId)
    {
        try {
            $response = $this->get(
                sprintf(
                    '/SmartcallRestfulProxy/network_js/%d',
                    $networkId
                )
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $this->parseError($e);
        }
    }

    /**
     * Purchase a voucher or do a pinless recharge on SmartCall.
     *
     * @param int    $productId identifier for the product
     * @param int    $amount    amount in rands of the product
     * @param int    $msisdn    mobile number of the recipient of the product
     * @param int    $deviceId  mobile number or meter number of the device being recharged
     * @param string $clientRef Client Reference (use a UUID)
     * @param bool   $pinless   true = device will be recharged via the network's IN platform | false = pinbased virtual voucher
     * @param bool   $sendSms   true = SmartCall will send the voucher via SMS | false don't send the voucher via SMS
     *
     * @throws Exception
     *
     * @return array
     */
    public function purchaseProduct($productId, $amount, $msisdn, $deviceId, $clientRef, $pinless, $sendSms)
    {
        try {
            $response = $this->post(
                sprintf(
                    '/SmartcallRestfulProxy/recharge_js/%s',
                    $clientRef
                ),
                [
                    'json' => [
                        'amount'             => $amount,
                        'deviceId'           => $deviceId,
                        'pinless'            => $pinless,
                        'productId'          => $productId,
                        'sendSms'            => $sendSms,
                        'smsRecipientMsisdn' => $msisdn,
                    ],
                ]
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $this->parseError($e);
        }
    }

    /**
     * Searches SmartCall for a specified transaction using a specified key and string to search
     * against at SmartCall.
     *
     * @param string $field  client_ref | msisdn | order_reference
     * @param string $search Client Reference when client_ref or a users MSISDN when msisdn
     *
     * @throws Exception
     *
     * @return array
     */
    public function searchTransaction($field, $search)
    {
        /**
         * Map Smartcall's longer version of the url to shorter param to pass in.
         */
        $fields = [
            'client_ref' => 'client_reference',
            'msisdn'     => 'msisdn',
            'order_ref'  => 'order_reference',
        ];

        try {
            $response = $this->get(
                sprintf(
                    '/SmartcallRestfulProxy/last_transaction_%s_js/%s',
                    (string) $fields[$field],
                    (string) $search
                )
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return $this->parseError($e);
        }
    }

    /**
     * Parse the java exception that we receive from Smartcall's Tomcat's.
     *
     * @param \GuzzleHttp\Exception\ServerException $exception
     *
     * @return array
     */
    private function parseError(\GuzzleHttp\Exception\ServerException $exception)
    {
        $body = (string) $exception->getResponse()->getBody();

        preg_match('!<p><b>type</b> Exception report</p><p><b>message</b> <u>(.*[^</u>])</u></p><p><b>description</b>!', $body, $matches);

        return [
            'status'    => 'error',
            'http_code' => $exception->getResponse()->getStatusCode(),
            'body'      => $matches['1'],
        ];
    }
}
