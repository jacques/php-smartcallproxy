<?php
/**
 * Client for SmartCall's Restful Proxy Integration Tests.
 *
 * @author    Jacques Marneweck <jacques@siberia.co.za>
 * @copyright 2016 Jacques Marneweck.  All rights strictly reserved.
 */

namespace Jacques\SmartCallProxy\Tests\Integration;

use Jacques\SmartCallProxy\Client;

/**
 * Tests for Jacques\SmartCallProxy\Client.
 *
 * @group integration
 * @group smartcall
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var null|\Jacques\SmartCallProxy\Client
     */
    protected $client = null;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->client = new Client([
            'hostname' => 'demo.dev01.kaizengarden.co',
            'port'     => '8080',
        ]);
        $this->assertNotNull($this->client);
        $this->assertInstanceOf('\Jacques\SmartCallProxy\Client', $this->client);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Jacques\SmartCallProxy\Client::__construct
     */
    public function testEmptyContructor()
    {
        $client = new \Jacques\SmartCallProxy\Client();
        $this->assertNotNull($client);
        $this->assertInstanceOf('\Jacques\SmartCallProxy\Client', $client);
    }

    public function testConstructorWithOptions()
    {
        $client = new Client(['hostname'=>'demo.dev01.kaizengarden.co', 'port'=>'8080']);
        $this->assertNotNull($client);
        $this->assertInstanceOf('\Jacques\SmartCallProxy\Client', $client);
    }

    /**
     * @covers \Jacques\SmartCallProxy\Client::getDealerBalance
     * @vcr unittest_smartcallproxy_balance
     */
    public function testGetDealerBalance()
    {
        $response = $this->client->getDealerBalance();

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals(200, $response['http_code']);
        $this->assertEquals('R999999.99', $response['body']);
    }

    /**
     * @covers \Jacques\SmartCallProxy\Client::getProducts
     * @vcr unittest_smartcallproxy_all_networks_js
     */
    public function testGetProducts()
    {
        $response = $this->client->getProducts();

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals(200, $response['http_code']);

        $json = json_decode($response['body']);

        $this->assertCount(11, $json);

        /*
         * 0
         */
        $this->assertEquals('Electricity-Municipalities', $json['0']->description);
        $this->assertEquals(8, $json['0']->id);
        $this->assertCount(1, $json['0']->productTypes);

        /*
         * 1
         */
        $this->assertEquals('Vodacom Mozambique', $json['1']->description);
        $this->assertEquals(11, $json['1']->id);
        $this->assertCount(2, $json['1']->productTypes);

        $this->assertInternalType('array', $json['1']->productTypes);

        for ($i = 0; $i < 2; $i++) {
            $this->assertObjectHasAttribute('code', $json['1']->productTypes[$i]);
            $this->assertObjectHasAttribute('description', $json['1']->productTypes[$i]);
            $this->assertObjectHasAttribute('fixedAmount', $json['1']->productTypes[$i]);
            $this->assertObjectHasAttribute('id', $json['1']->productTypes[$i]);
            $this->assertObjectHasAttribute('products', $json['1']->productTypes[$i]);
        }

        /*
         * 1 - ProductTypes - 0
         */
        $this->assertEquals('V ', $json['1']->productTypes['0']->code);
        $this->assertEquals('Airtime Voucher', $json['1']->productTypes['0']->description);
        $this->assertTrue($json['1']->productTypes['0']->fixedAmount);
        $this->assertEquals('13', $json['1']->productTypes['0']->id);
        $this->assertCount(1, $json['1']->productTypes['0']->products);

        for ($i = 0; $i < 1; $i++) {
            $this->assertObjectHasAttribute('description', $json['1']->productTypes['0']->products[$i]);
            $this->assertObjectHasAttribute('discountPercentage', $json['1']->productTypes['0']->products[$i]);
            $this->assertObjectHasAttribute('id', $json['1']->productTypes['0']->products[$i]);
            $this->assertObjectHasAttribute('maxAmount', $json['1']->productTypes['0']->products[$i]);
            $this->assertObjectHasAttribute('minAmount', $json['1']->productTypes['0']->products[$i]);
            $this->assertObjectHasAttribute('name', $json['1']->productTypes['0']->products[$i]);
            $this->assertObjectHasAttribute('pinIndicator', $json['1']->productTypes['0']->products[$i]);
            $this->assertObjectHasAttribute('retailValue', $json['1']->productTypes['0']->products[$i]);
            $this->assertObjectHasAttribute('smsIndicator', $json['1']->productTypes['0']->products[$i]);
        }

        /*
         * 1 - ProductTypes - 0 - Products
         */
        $this->assertEquals('100 MT-R40 Voucher', $json['1']->productTypes['0']->products['0']->description);
        $this->assertEquals(10, $json['1']->productTypes['0']->products['0']->discountPercentage);
        $this->assertEquals(188, $json['1']->productTypes['0']->products['0']->id);
        $this->assertEquals(40, $json['1']->productTypes['0']->products['0']->maxAmount);
        $this->assertEquals(40, $json['1']->productTypes['0']->products['0']->minAmount);
        $this->assertEquals('100 MT-R40 Voucher', $json['1']->productTypes['0']->products['0']->name);
        $this->assertTrue($json['1']->productTypes['0']->products['0']->pinIndicator);
        $this->assertEquals(40, $json['1']->productTypes['0']->products['0']->retailValue);
        $this->assertFalse($json['1']->productTypes['0']->products['0']->smsIndicator);

        /*
         * 1 - ProductTypes - 1
         */
        $this->assertEquals('A ', $json['1']->productTypes['1']->code);
        $this->assertEquals('Airtime', $json['1']->productTypes['1']->description);
        $this->assertTrue($json['1']->productTypes['1']->fixedAmount);
        $this->assertEquals('1', $json['1']->productTypes['1']->id);
        $this->assertCount(4, $json['1']->productTypes['1']->products);

        for ($i = 0; $i < 4; $i++) {
            $this->assertObjectHasAttribute('description', $json['1']->productTypes['1']->products[$i]);
            $this->assertObjectHasAttribute('discountPercentage', $json['1']->productTypes['1']->products[$i]);
            $this->assertObjectHasAttribute('id', $json['1']->productTypes['1']->products[$i]);
            $this->assertObjectHasAttribute('maxAmount', $json['1']->productTypes['1']->products[$i]);
            $this->assertObjectHasAttribute('minAmount', $json['1']->productTypes['1']->products[$i]);
            $this->assertObjectHasAttribute('name', $json['1']->productTypes['1']->products[$i]);
            $this->assertObjectHasAttribute('pinIndicator', $json['1']->productTypes['1']->products[$i]);
            $this->assertObjectHasAttribute('retailValue', $json['1']->productTypes['1']->products[$i]);
            $this->assertObjectHasAttribute('smsIndicator', $json['1']->productTypes['1']->products[$i]);
        }
    }

    /**
     * @covers \Jacques\SmartCallProxy\Client::getLastTransaction
     * @vcr unittest_smartcallproxy_last_transaction_js
     */
    public function testGetLastTransaction()
    {
        $response = $this->client->getLastTransaction();

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals(200, $response['http_code']);

        $json = json_decode($response['body']);

        $this->assertObjectHasAttribute('additionalVoucherPin', $json);
        $this->assertObjectHasAttribute('amount', $json);
        $this->assertObjectHasAttribute('batchNumber', $json);
        $this->assertObjectHasAttribute('boxNumber', $json);
        $this->assertObjectHasAttribute('cost', $json);
        $this->assertObjectHasAttribute('description', $json);
        $this->assertObjectHasAttribute('discount', $json);
        $this->assertObjectHasAttribute('expiryDate', $json);
        $this->assertObjectHasAttribute('network', $json);
        $this->assertObjectHasAttribute('recipientMsisdn', $json);
        $this->assertObjectHasAttribute('reference', $json);
        $this->assertObjectHasAttribute('status', $json);
        $this->assertObjectHasAttribute('statusDate', $json);
        $this->assertObjectHasAttribute('statusId', $json);
        $this->assertObjectHasAttribute('ticketNumber', $json);
        $this->assertObjectHasAttribute('voucherPin', $json);

        $this->assertNull($json->additionalVoucherPin);
        $this->assertEquals('R25.00', $json->amount);
        $this->assertNull($json->batchNumber);
        $this->assertNull($json->boxNumber);
        $this->assertEquals('R25.00', $json->cost);
        $this->assertEquals('Order', $json->description);
        $this->assertEquals('0.00%', $json->discount);
        $this->assertNull($json->expiryDate);
        $this->assertEquals('Electricity-Municipalities', $json->network);
        $this->assertEquals('27833530837', $json->recipientMsisdn);
        $this->assertEquals('109372759', $json->reference);
        $this->assertEquals('Successful', $json->status);
        $this->assertEquals('1479473184113', $json->statusDate);
        $this->assertEquals('SUCESSFUL', $json->statusId);
        $this->assertNull($json->ticketNumber);
        $this->assertNull($json->voucherPin);
    }

    /**
     * @covers \Jacques\SmartCallProxy\Client::searchTransaction
     * @vcr unittest_smartcallproxy_last_transaction_msisdn_js_with_no_transaction
     */
    public function testSearchTransactionByMSISDNWithNoTransaction()
    {
        $response = $this->client->searchTransaction('msisdn', '0833530837');

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals(200, $response['http_code']);

        $json = json_decode($response['body']);

        $this->assertObjectHasAttribute('error', $json);
        $this->assertObjectHasAttribute('responseCode', $json);
        $this->assertObjectHasAttribute('transaction', $json);

        $this->assertNull($json->transaction);
    }

    /**
     * @covers \Jacques\SmartCallProxy\Client::searchTransaction
     * @vcr unittest_smartcallproxy_last_transaction_msisdn_js_07123456789
     */
    public function testSearchTransactionByMSISDNWithMeterNo()
    {
        $response = $this->client->searchTransaction('msisdn', '07123456789');

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals(200, $response['http_code']);

        $json = json_decode($response['body']);

        $this->assertObjectHasAttribute('error', $json);
        $this->assertObjectHasAttribute('responseCode', $json);
        $this->assertObjectHasAttribute('transaction', $json);

        $this->assertObjectHasAttribute('additionalVoucherPin', $json->transaction);
        $this->assertObjectHasAttribute('amount', $json->transaction);
        $this->assertObjectHasAttribute('batchNumber', $json->transaction);
        $this->assertObjectHasAttribute('boxNumber', $json->transaction);
        $this->assertObjectHasAttribute('cost', $json->transaction);
        $this->assertObjectHasAttribute('description', $json->transaction);
        $this->assertObjectHasAttribute('discount', $json->transaction);
        $this->assertObjectHasAttribute('expiryDate', $json->transaction);
        $this->assertObjectHasAttribute('network', $json->transaction);
        $this->assertObjectHasAttribute('recipientMsisdn', $json->transaction);
        $this->assertObjectHasAttribute('reference', $json->transaction);
        $this->assertObjectHasAttribute('status', $json->transaction);
        $this->assertObjectHasAttribute('statusDate', $json->transaction);
        $this->assertObjectHasAttribute('statusId', $json->transaction);
        $this->assertObjectHasAttribute('ticketNumber', $json->transaction);
        $this->assertObjectHasAttribute('voucherPin', $json->transaction);

        $this->assertNull($json->transaction->additionalVoucherPin);
        $this->assertEquals('R25.00', $json->transaction->amount);
        $this->assertEquals(0, $json->transaction->batchNumber);
        $this->assertEquals(0, $json->transaction->boxNumber);
        $this->assertEquals('R25.00', $json->transaction->cost);
        $this->assertEquals('Order', $json->transaction->description);
        $this->assertEquals('0.00%', $json->transaction->discount);
        $this->assertNull($json->transaction->expiryDate);
        $this->assertEquals('Electricity-Municipalities', $json->transaction->network);
        $this->assertEquals('27833530837', $json->transaction->recipientMsisdn);
        $this->assertEquals('109372759', $json->transaction->reference);
        $this->assertEquals('Successful', $json->transaction->status);
        $this->assertEquals('1479473184113', $json->transaction->statusDate);
        $this->assertEquals('SUCESSFUL', $json->transaction->statusId);
        $this->assertNull($json->transaction->ticketNumber);
        $this->assertNull($json->transaction->voucherPin);
    }

    /**
     * @covers \Jacques\SmartCallProxy\Client::searchTransaction
     * @vcr unittest_smartcallproxy_last_transaction_order_reference_js
     */
    public function testSearchTransactionByOrderRef()
    {
        $response = $this->client->searchTransaction('order_ref', '109372759');

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals(200, $response['http_code']);

        $json = json_decode($response['body']);

        $this->assertObjectHasAttribute('error', $json);
        $this->assertObjectHasAttribute('responseCode', $json);
        $this->assertObjectHasAttribute('transaction', $json);

        $this->assertObjectHasAttribute('additionalVoucherPin', $json->transaction);
        $this->assertObjectHasAttribute('amount', $json->transaction);
        $this->assertObjectHasAttribute('batchNumber', $json->transaction);
        $this->assertObjectHasAttribute('boxNumber', $json->transaction);
        $this->assertObjectHasAttribute('cost', $json->transaction);
        $this->assertObjectHasAttribute('description', $json->transaction);
        $this->assertObjectHasAttribute('discount', $json->transaction);
        $this->assertObjectHasAttribute('expiryDate', $json->transaction);
        $this->assertObjectHasAttribute('network', $json->transaction);
        $this->assertObjectHasAttribute('recipientMsisdn', $json->transaction);
        $this->assertObjectHasAttribute('reference', $json->transaction);
        $this->assertObjectHasAttribute('status', $json->transaction);
        $this->assertObjectHasAttribute('statusDate', $json->transaction);
        $this->assertObjectHasAttribute('statusId', $json->transaction);
        $this->assertObjectHasAttribute('ticketNumber', $json->transaction);
        $this->assertObjectHasAttribute('voucherPin', $json->transaction);

        $this->assertNull($json->transaction->additionalVoucherPin);
        $this->assertEquals('R25.00', $json->transaction->amount);
        $this->assertEquals(0, $json->transaction->batchNumber);
        $this->assertEquals(0, $json->transaction->boxNumber);
        $this->assertEquals('R25.00', $json->transaction->cost);
        $this->assertEquals('Order', $json->transaction->description);
        $this->assertEquals('0.00%', $json->transaction->discount);
        $this->assertNull($json->transaction->expiryDate);
        $this->assertEquals('Electricity-Municipalities', $json->transaction->network);
        $this->assertEquals('27833530837', $json->transaction->recipientMsisdn);
        $this->assertEquals('109372759', $json->transaction->reference);
        $this->assertEquals('Successful', $json->transaction->status);
        $this->assertEquals('1479473184113', $json->transaction->statusDate);
        $this->assertEquals('SUCESSFUL', $json->transaction->statusId);
        $this->assertEquals(946876584, $json->transaction->ticketNumber);
        $this->assertEquals('6559 0769 8171 3340 3636', $json->transaction->voucherPin);
    }
}
