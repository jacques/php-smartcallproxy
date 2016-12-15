<?php
/**
 * Client for Smartcall's Restful Proxy.
 *
 * @author    Jacques Marneweck <jacques@powertrip.co.za>
 * @copyright 2016 Jacques Marneweck.  All rights strictly reserved.
 * @license   MIT
 */

namespace Jacques\SmartCallProxy;

class Client extends \GuzzleHttp\Client
{
    /**
     * @const string Version number
     */
    const VERSION = '0.0.1';

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
            return [
                'status'    => 'error',
                'http_code' => $e->getResponse()->getStatusCode(),
                'body'      => $e->getResponse()->getBody(true),
            ];
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
            return [
                'status'    => 'error',
                'http_code' => $e->getResponse()->getStatusCode(),
                'body'      => $e->getResponse()->getBody(true),
            ];
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
            return [
                'status'    => 'error',
                'http_code' => $e->getResponse()->getStatusCode(),
                'body'      => $e->getResponse()->getBody(true),
            ];
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
            return [
                'status'    => 'error',
                'http_code' => $e->getResponse()->getStatusCode(),
                'body'      => $e->getResponse()->getBody(true),
            ];
        }
    }

    /**
     * Fetches the Product List by the specified network identifier from SmartCall.
     *
     * @param int $network_id identifier for the network
     *
     * @throws Exception
     *
     * @return array
     */
    public function getProductsByNetwork($network_id)
    {
        try {
            $response = $this->get(
                sprintf(
                    '/SmartcallRestfulProxy/network_js/%d',
                    $network_id
                )
            );

            return [
                'status'    => 'ok',
                'http_code' => $response->getStatusCode(),
                'body'      => (string) $response->getBody(),
            ];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            return [
                'status'    => 'error',
                'http_code' => $e->getResponse()->getStatusCode(),
                'body'      => $e->getResponse()->getBody(true),
            ];
        }
    }

    /**
     * Searches SmartCall for a specified transaction using a specified key and string to search
     * against at SmartCall.
     *
     * @param string $field        client_ref | msisdn | order_reference
     * @param string $query_string Client Reference when client_ref or a users MSISDN when msisdn
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
            return [
                'status'    => 'error',
                'http_code' => $e->getResponse()->getStatusCode(),
                'body'      => $e->getResponse()->getBody(true),
            ];
        }
    }
}
