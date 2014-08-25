<?php
/**
 *
 * File         HmacManager.php
 *
 * @author      Mathias Bauer <info@mbauer.eu>
 * @license     GPLv3
 *
 */
namespace Ampersand\Auth;

use Exception;

/**
 *
 * Class        HmacManager
 *
 * @package     Ampersand\Auth
 *
 * @author      Mathias Bauer <info@mbauer.eu>
 * @license     GPLv3
 *
 */
class HmacManager
{

    /** The payload that is|was used to build the hmac
     *
     * @var null|string
     */
    private $payload = null;

    /**
     * @var null|string
     */
    private $algorithm = 'sha256';

    /**
     * token / nonce against reply attacks
     * The token is build by creating a payload out of the 'api-key.timestamp' and then encrypt it with the private-key
     *
     * @var null|string
     */
    private $token = null;

    /**
     * The HMAC that is generated with the payload and the private-key
     *
     * @var null|string
     */
    private $hmacHash = null;

    /**
     * Time to live
     *
     * @see HMACInterface->check_timestamp
     * @var null|string
     */
    private $ttl = null;

    /**
     *
     * against reply attacks
     *
     * null|string
     */
    private $timestamp = null;

    /**
     * @param string $hmacHeaderKey
     */
    public function setHmacKey($hmacHeaderKey)
    {
        $this->hmacKey = $hmacHeaderKey;
    }

    /**
     * @return string
     */
    public function getHmacKey()
    {
        return $this->hmacKey;
    }

    /**
     * @param string $nonceHeaderKey
     */
    public function setTokenKey($nonceHeaderKey)
    {
        $this->tokenKey = $nonceHeaderKey;
    }

    /**
     * @return string
     */
    public function getTokenKey()
    {
        return $this->tokenKey;
    }

    /**
     * @param string $payloadHeaderKey
     */
    public function setPayloadKey($payloadHeaderKey)
    {
        $this->payloadKey = $payloadHeaderKey;
    }

    /**
     * @return string
     */
    public function getPayloadKey()
    {
        return $this->payloadKey;
    }

    /**
     * @param string $timestampHeaderKey
     */
    public function setTimestampKey($timestampHeaderKey)
    {
        $this->timestampKey = $timestampHeaderKey;
    }

    /**
     * @return string
     */
    public function getTimestampKey()
    {
        return $this->timestampKey;
    }


    /**
     * @param string $ttl
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    }

    /**
     * @throws \Exception
     * @return string
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @param string $algorithm
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @param string $nonce
     */
    public function setToken($nonce)
    {
        $this->token = $nonce;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $privateHash
     */
    public function setPrivateKey($privateHash)
    {
        $this->privateKey = $privateHash;
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param string $publicHash
     */
    public function setApiKey($publicHash)
    {
        $this->apiKey = $publicHash;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param null $hmacHash
     */
    public function setHmacHash($hmacHash)
    {
        $this->hmacHash = $hmacHash;
    }

    /**
     * @return null
     */
    public function getHmacHash()
    {
        if ($this->hmacHash === null && $this->getHmacKey() !== null) {
            // try to get it form $this->hmacHashKey
            $hmacHash       = $this->getKeyByPath($this->getHmacKey());
            $this->hmacHash = $hmacHash;
        }

        return $this->hmacHash;
    }

    /**
     * @throws \Exception
     * @return bool
     */
    public function checkTimestamp()
    {
        if ($this->getTtl() === null) {
            throw new \Exception('Time to life was not set, use setTtl($timeToLife) before');
        };

        $isValid = false;

        $clientTime = $this->getTimestamp();
        $serverTime = time();
        $timeDiff   = $serverTime - $clientTime;

        if ($timeDiff <= $this->getTtl()) {
            $isValid = true;
        }

        return $isValid;
    }

    /**
     * Build a token from the api-key.timestamp and the private key
     *
     * @return string Token build from the api-key.timestamp and the private key
     */
    public function create_token()
    {
        $apiKey     = $this->getApiKey();
        $timestamp  = $this->getTimestamp();
        $privateKey = $this->getPrivateKey();
        $token      = $this->create_hash($apiKey . $timestamp, $privateKey);

        return $token;
    }

    public function create_token_for_timestamp($timestamp)
    {
        $apiKey     = $this->getApiKey();
        $privateKey = $this->getPrivateKey();
        $token      = $this->create_hash($apiKey . $timestamp, $privateKey);

        return $token;
    }

    /**
     * Authenticate
     *
     * This is the authenticate method where we check the hash from the client against
     * a hash that we will recreate here on the server. If the 2 match, it's a pass.
     */
    public function authenticate($token)
    {
        if ($token === $this->create_token()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Create Hash
     *
     * This method is where we'll recreate the hash coming from the client using the secret key to authenticate the
     * request
     */
    public function create_hash($payload, $privateKey)
    {
        $hmacHash = hash_hmac($this->getAlgorithm(), $payload, $privateKey);

        return $hmacHash;
    }


    /**
     * Check if the HMAC from the client matches the on created on the server
     *
     * @return bool
     */
    public function check_hmac_hash()
    {
        $isValid = false;
        // get the data that was used to generate the client_hmac_hash
        $payload = $this->getPayload();
        // rebuild the hash on the server
        $server_hmac_hash = $this->create_hash($payload, $this->getPrivateKey());

        if ($this->getHmacHash() === $server_hmac_hash) {
            $isValid = true;
        }

        return $isValid;
    }

    /**
     * Check if the timestamp matches in the range of time to life for
     * Check if the token matches
     * Check the HMAC
     *
     * @return bool
     */
    public function isValid()
    {
        $isValid = false;
        // check if timestamp is in ttl
        if ($this->checkTimestamp()) {
            // check for a valid token
            if ($this->authenticate($this->getToken())) {
                $isValid = $this->check_hmac_hash();

            }
        }

        return $isValid;
    }

    public function create_and_set_hmac()
    {
        $timestamp = time();
        $this->setTimestamp($timestamp);
        $this->setToken($this->create_token_for_timestamp($timestamp));

        $this->setHmacHash($this->create_hash($this->getPayload(), $this->getPrivateKey()));
    }
}