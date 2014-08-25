<?php
/**
 *
 * File         HmacManagerTest.php
 *
 * @author      Mathias Bauer <info@mbauer.eu>
 * @license     GPLv3
 *
 */
namespace Ampersand\Tests\Auth;

use Ampersand\Auth\HmacManager;
use Ampersand\Tests\HmacTestCase;

/**
 *
 * Class        HMACTest
 *
 * @package     Ampersand\Tests\Auth
 *
 * @author      Mathias Bauer <info@mbauer.eu>
 * @license     GPLv3
 */
class HMACTest extends HmacTestCase
{

    private $private_key = 'e249c439ed7697df2a4b045d97d4b9b7e1854c3ff8dd668c779013653913572e';
    private $api_key = '3441df0babc2a2dda551d7cd39fb235bc4e09cd1e4556bf261bb49188f548348';

    /**
     * @expectedException
     */
    public function testCheckTimestampThrowsExceptionWhenNoTTLSet()
    {
        // init HMAC
        $hmac = new HmacManager();
        // ttl is null as default
        $hmac->setTimestamp(time());
        $this->assertException(function () use ($hmac) {
            $hmac->checkTimestamp();
        });
    }

    public function testCheckTimestampFailsWhenNoClientTimestampSet()
    {
        // init HMAC
        $hmac = new HmacManager();
        $hmac->setTtl(10);
        $this->assertFalse($hmac->checkTimestamp());
    }

    public function testCreateHmacHash()
    {
        // payload
        $payload = json_encode(array(
                                   'test' => 'content'
                               ));

        $hmac = new HmacManager();
        $hmac->setPrivateKey($this->private_key);
        $hmac->setApiKey($this->api_key);
        $hash = $hmac->create_hash($payload, $this->private_key);
        // expected hash
        $hash_expected = 'b136a45e55f0d452dc9b7fb29bd1b5de5262e7eec4a50c4934fc503b3d8635c2';
        $this->assertEquals($hash_expected, $hash);
    }

    public function testCheckTimestamp()
    {
        // init HMAC
        $hmac = new HmacManager();
        // set time to life
        $hmac->setTimestamp(time());
        $hmac->setTtl(1000);
        $this->assertTrue($hmac->checkTimestamp());
    }

    public function testCreate_Token()
    {
        $token = $this->getTokenForTimestamp();
        // expected hash
        $token_expected = '85a71ef7f2fd57b68b09c57e4cabacaccc0af68afd3d5c780502e7e010f925ad';
        $this->assertEquals($token_expected, $token);
    }

    public function testAuthenticateSuccessForCorrectToken()
    {
        $timestamp = time();
        $token     = $this->getTokenForTimestamp($timestamp);

        $hmac = $this->initHmacWithKeys();
        $hmac->setTimestamp($timestamp);

        $this->assertTrue($hmac->authenticate($token));
    }

    public function testAuthenticateFailForNonCorrectToken()
    {
        $timestamp = time();
        $token     = $this->getTokenForTimestamp($timestamp);

        $hmac = $this->initHmacWithKeys();
        $hmac->setTimestamp(333666999);

        $this->assertFalse($hmac->authenticate($token));
    }

    public function testIsValidSuccess()
    {
        $timestamp = time();
        $hmac      = $this->initHmacWithKeys();
        $hmac->setTimestamp($timestamp);
        $hmac->setTtl(1000);
        $token = $this->getTokenForTimestamp($timestamp);
        $hmac->setToken($token);
        // payload
        $payload = json_encode(array(
                                   'test' => 'content'
                               ));
        $hmac->setPayload($payload);
        $hash = $hmac->create_hash($payload, $this->private_key);
        $hmac->setHmacHash($hash);
        $this->assertTrue($hmac->isValid());
    }

    public function testIsValidFailsOnTimestamp()
    {
        $timestamp = 333666999;
        $hmac      = $this->initHmacWithKeys();
        $hmac->setTimestamp($timestamp);
        $hmac->setTtl(1);
        $token = $this->getTokenForTimestamp($timestamp);
        $hmac->setToken($token);
        // payload
        $payload = json_encode(array(
                                   'test' => 'content'
                               ));
        $hmac->setPayload($payload);
        $hash = $hmac->create_hash($payload, $this->private_key);
        $hmac->setHmacHash($hash);
        $this->assertFalse($hmac->isValid());
    }

    public function testIsValidFailsOnToken()
    {
        $timestamp = time();
        $hmac      = $this->initHmacWithKeys();
        $hmac->setTimestamp($timestamp);
        $hmac->setTtl(1000);
        $token = $this->getTokenForTimestamp($timestamp + 10);
        $hmac->setToken($token);
        // payload
        $payload = json_encode(array(
                                   'test' => 'content'
                               ));
        $hmac->setPayload($payload);
        $hash = $hmac->create_hash($payload, $this->private_key);
        $hmac->setHmacHash($hash);
        $this->assertFalse($hmac->isValid());
    }

    public function testIsValidFailsOnWrongPrivateKey()
    {
        $timestamp = time();
        $hmac      = $this->initHmacWithKeys();
        $hmac->setTimestamp($timestamp);
        $hmac->setTtl(1000);
        $token = $this->getTokenForTimestamp($timestamp);
        $hmac->setToken($token);
        // payload
        $payload = json_encode(array(
                                   'test' => 'content'
                               ));
        $hmac->setPayload($payload);
        // hash payload with a wring key
        $hash = $hmac->create_hash($payload, 'abc');
        $hmac->setHmacHash($hash);
        $hmac->setPrivateKey($this->private_key);
        $this->assertFalse($hmac->isValid());
    }

    /**
     * @param int $timestamp
     *
     * @return string
     */
    private function getTokenForTimestamp($timestamp = 1408811262)
    {
        $hmac = new HmacManager();
        $hmac->setPrivateKey($this->private_key);
        $hmac->setApiKey($this->api_key);
        $hmac->setTimestamp($timestamp);

        $token = $hmac->create_token();

        return $token;
    }

    /**
     * @return HMAC
     */
    private function initHmacWithKeys()
    {
        $hmac = new HmacManager();
        $hmac->setApiKey($this->api_key);
        $hmac->setPrivateKey($this->private_key);

        return $hmac;
    }
}
