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
    private $algorithm = 'sha256';
    private $private_key = 'e249c439ed7697df2a4b045d97d4b9b7e1854c3ff8dd668c779013653913572e';
    private $api_key = '3441df0babc2a2dda551d7cd39fb235bc4e09cd1e4556bf261bb49188f548348';
    private $timestamp = 1408811262;

    /**
     * @expectedException
     */
    public function testCheckTimestampThrowsExceptionWhenNoTTLSet()
    {
        // init HMAC
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = new HmacManager();
        // ttl is null as default
        $hmac->setTimestamp(time());
        $this->assertException(function () use ($hmac) {
            $hmac->checkTimestamp();
        });
    }

    /**
     * @expectedException
     */
    public function testCheckTimestampThrowsExceptionWhenNoClientTimestampSet()
    {
        // init HMAC
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = new HmacManager();
        $hmac->setTtl(10);
        $this->assertException(function () use ($hmac) {
            $hmac->checkTimestamp();
        });
    }

    public function testCreateTokenThrowsExceptionWhenApiKeyNotSet()
    {
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = new HmacManager();
        $hmac->setTimestamp(time());
        $hmac->setPrivateKey($this->private_key);
        $this->assertException(function () use ($hmac) {
            $hmac->create_token();
        });
    }

    public function testCreateTokenThrowsExceptionWhenTimestampNotSet()
    {
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = new HmacManager();
        $hmac->setApiKey($this->api_key);
        $hmac->setPrivateKey($this->private_key);
        $this->assertException(function () use ($hmac) {
            $hmac->create_token();
        });
    }

    public function testCreateTokenThrowsExceptionWhenPrivateKeyNotSet()
    {
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = new HmacManager();
        $hmac->setTimestamp(time());
        $hmac->setApiKey($this->api_key);
        $this->assertException(function () use ($hmac) {
            $hmac->create_token();
        });
    }

    public function testCreateHmacHash()
    {
        // payload
        $payload = json_encode(array(
                                   'test' => 'content'
                               ));
        // use implementation in this class to create the hash
        $hash_expected = $this->generate_hash($this->algorithm, $payload, $this->private_key);

        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = new HmacManager();
        // expected hash
        $this->assertEquals($hash_expected, $hmac->create_hash($payload, $this->private_key));
    }

    public function testCreateHmacHashThrowsExceptionOnEmptyPrivateKey()
    {
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = new HmacManager();
        // expected hash
        $this->assertException(function () use ($hmac) {
            // payload
            $payload = json_encode(array(
                                       'test' => 'content'
                                   ));
            $hmac->create_hash($payload, null);
        });
    }

    public function testSetTtlThrowsExceptionOnTtlSmallerZero()
    {
        $this->assertException(function () {
            // init HMAC
            /** @var \Ampersand\Auth\HmacManager $hmac */
            $hmac = new HmacManager();
            // set time to life
            $hmac->setTtl(-1);
        });
    }

    public function testSetTimestampThrowsExceptionOnValueSmallerZero()
    {
        $this->assertException(function () {
            // init HMAC
            /** @var \Ampersand\Auth\HmacManager $hmac */
            $hmac = new HmacManager();
            // set time to life
            $hmac->setTimestamp(-1);
        });
    }

    /**
     * Check if a timstamp in rage of time to life returns true
     */
    public function testCheckTimestamp()
    {
        // init HMAC
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = new HmacManager();
        // set time to life
        $hmac->setTimestamp(time());
        $hmac->setTtl(1000);
        $this->assertTrue($hmac->checkTimestamp());
    }

    public function testCheckTimestampFails()
    {
        // init HMAC
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = new HmacManager();
        // set time to life
        $hmac->setTimestamp($this->timestamp);
        $hmac->setTtl(1);
        $this->assertFalse($hmac->checkTimestamp());
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

        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = $this->initHmacWithKeys();
        $hmac->setTimestamp($timestamp);

        $this->assertTrue($hmac->check_token($token));
    }

    public function testAuthenticateFailForNonCorrectToken()
    {
        $timestamp = time();
        $token     = $this->getTokenForTimestamp($timestamp);

        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = $this->initHmacWithKeys();

        $hmac->setTimestamp(333666999);

        $this->assertFalse($hmac->check_token($token));
    }

    public function testIsValidSuccess()
    {
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac      = $this->initHmacWithKeys();
        $timestamp = time();
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
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac      = $this->initHmacWithKeys();

        $timestamp = 333666999;
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
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac      = $this->initHmacWithKeys();

        $timestamp = time();
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
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac      = $this->initHmacWithKeys();

        $timestamp = time();
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
    private function getTokenForTimestamp($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = $this->timestamp;
        }
        $token = $this->generate_hash($this->algorithm, $this->api_key . $timestamp, $this->private_key);

        return $token;
    }

    /**
     * @return HMAC
     */
    private function initHmacWithKeys()
    {
        /** @var \Ampersand\Auth\HmacManager $hmac */
        $hmac = new HmacManager();
        $hmac->setApiKey($this->api_key);
        $hmac->setPrivateKey($this->private_key);

        return $hmac;
    }

    /**
     * Creates a HMAC hash
     *
     * @param $algorithm
     * @param $payload
     * @param $privateKey
     *
     * @return string
     */
    public function generate_hash($algorithm, $payload, $privateKey)
    {
        return hash_hmac($algorithm, $payload, $privateKey);
    }
}
