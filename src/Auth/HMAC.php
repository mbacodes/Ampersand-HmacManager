<?php
/**
 *
 * File         HMAC.php
 *
 * @author      Mathias Bauer <info@mbauer.eu>
 * @copyright   ${COPYRIGHT}
 * @link        ${LINK}
 * @license     ${LICENSE}
 * @version     ${VERSION}
 * @package     ${PACKAGE}
 */
namespace Ampersand\Auth;

use Exception;

/**
 *
 * Class        HMAC
 *
 * @package     Ampersand\Auth
 *
 * @author      Mathias Bauer <info@mbauer.eu>
 * @copyright   ${COPYRIGHT}
 * @link        ${LINK}
 * @license     ${LICENSE}
 * @version     ${VERSION}
 * @package     ${PACKAGE}
 */
class HMAC implements HMACInterface
{
    /**
     * Indicate where to find the hmac
     *
     * e.g 'request|header|X-HMAC'
     * tells HMAC to search for the HMAC_HASH in the request headers in the header X-HMAC
     *
     * @var null|string
     */
    private $hmacKey = null;

    /**
     * Indicate where to find the timestamp information
     *
     * e.g 'response|header|X-TIMESTAMP'
     * tells HMAC to search for the timestamp in the response headers in the header X-TIMESTAMP
     *
     * @var null|string
     */
    private $timestampKey = null;

    /**
     * Indicate where to find the token information
     *
     * @see $this->hmacKey
     *
     * @var null|string
     */
    private $tokenKey = null;

    /**
     * Indicate where to find the payload that was used to generate the HMAC
     *
     * @see $this->hmacKey
     *
     * @var null|string
     */
    private $payloadKey = null;

    /**
     * Indicate where to find the api-key used for authentication
     *
     * @see $this->hmacKey
     *
     * @var null|string
     */
    private $apiKey = null;

    /**
     * The private key that is used for encryption
     *
     * @see $this->hmacKey
     *
     * @var null|string
     */
    private $privateKey = null;

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
     * @var \Ampersand\Http\RequestInterface
     */
    private $request = null;

    /**
     * @var \Ampersand\Http\ResponseInterface
     */
    private $response = null;

    /**
     * @var null|\Ampersand\Http\CookiesInterface
     */
    private $cookies = null;

    /**
     * @param mixed $cookie
     */
    public function setCookies($cookie)
    {
        $this->cookies = $cookie;
    }

    /**
     * @return mixed
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param array $headerKeys
     *
     * @return mixed
     */
    public function setHeaderKeys(array $headerKeys)
    {
        // TODO: Implement setHeaderKeys() method.
    }

    /**
     * @return array
     */
    public function getHeaderKeys()
    {
        // TODO: Implement getHeaderKeys() method.
    }

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

    private function getKeyByPath($path)
    {
        $path = explode('|', $path);

        return $path;
    }
}