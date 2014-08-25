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
    /*
     * #########################################
     * CLASS ATTRIBUTES
     * #########################################
     */
    /**
     * @var null|string
     */
    private $algorithm = 'sha256';

    /**
     * The Api-Key that is used to build the token
     *
     * @var null|string
     */
    private $apiKey = null;

    /**
     * The private key that is used for encryption
     * WARNING! Never send this key over unsecured lines.
     *
     * @var null|string
     */
    private $privateKey = null;

    /**
     * Time to live
     *
     * @see HMACInterface->check_timestamp
     * @var null|string
     */
    private $ttl = null;

    /**
     *
     * Timestamp to use against reply attacks
     *
     * null|string
     */
    private $timestamp = null;

    /**
     * Should the Hmac-Manager work with tokens
     *
     * @var bool
     */
    private $useToken = true;

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

    /** The payload that is|was used to build the hmac
     *
     * @var null|string
     */
    private $payload = null;

    /*
     * #########################################
     * GETTERS / SETTERS
     * #########################################
     */
    /**
     * @param null|string $algorithm
     */
    public function setAlgorithm($algorithm)
    {
        try {
            $this->checkValue($algorithm);
        } catch (Exception $e) {
            throw new \InvalidArgumentException ('$algorithm cant be a null or empty!');
        }
        $this->algorithm = $algorithm;
    }

    /**
     * @return null|string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @param null $apiKey
     *
     * @throws \InvalidArgumentException
     */
    public function setApiKey($apiKey)
    {
        try {
            $this->checkValue($apiKey);
        } catch (Exception $e) {
            throw new \InvalidArgumentException ('$apiKey cant be a null or empty!');
        }
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }


    /**
     * @param null|string $hmacHash
     *
     * @throws \InvalidArgumentException
     */
    public function setHmacHash($hmacHash)
    {
        try {
            $this->checkValue($hmacHash);
        } catch (Exception $e) {
            throw new \InvalidArgumentException ('$hmacHash cant be a null or empty!');
        }
        $this->hmacHash = $hmacHash;
    }

    /**
     * @return null|string
     */
    public function getHmacHash()
    {
        return $this->hmacHash;
    }

    /**
     * @param null|string $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return null|string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param null|string $privateKey
     *
     * @throws \InvalidArgumentException
     */
    public function setPrivateKey($privateKey)
    {
        try {
            $this->checkValue($privateKey);
        } catch (Exception $e) {
            throw new \InvalidArgumentException ('$privateKey cant be a null or empty!');
        }
        $this->privateKey = $privateKey;
    }

    /**
     * @return null|string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param integer $timestamp
     *
     * @throws \InvalidArgumentException
     */
    public function setTimestamp($timestamp)
    {
        try {
            $this->checkValue($timestamp);
        } catch (Exception $e) {
            throw new \InvalidArgumentException ('$timestamp cant be a null or empty!');
        }
        if ($timestamp < 0) {
            throw new \InvalidArgumentException ('$timestamp cant be a negative value negative!');
        }
        $this->timestamp = intval($timestamp);
    }

    /**
     * @return null|integer
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param null|string $token
     *
     * @throws \InvalidArgumentException
     */
    public function setToken($token)
    {
        try {
            $this->checkValue($token);
        } catch (Exception $e) {
            throw new \InvalidArgumentException ('$token cant be a null or empty!');
        }
        $this->token = $token;
    }

    /**
     * @return null|string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param null|integer $ttl
     *
     * @throws \InvalidArgumentException
     */
    public function setTtl($ttl)
    {
        try {
            $this->checkValue($ttl);
        } catch (Exception $e) {
            throw new \InvalidArgumentException ('$ttl cant be a null or empty!');
        }
        if ($ttl < 0) {
            throw new \InvalidArgumentException ('$ttl cant be a negative value negative!');
        }
        $this->ttl = intval($ttl);
    }

    /**
     * @return null|string
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @param boolean $useToken
     */
    public function setUseToken($useToken)
    {
        $this->useToken = $useToken;
    }

    /*
     * #########################################
     * PUBLIC METHODS
     * #########################################
     */
    /**
     * Check if the timestamp matches in the range of time to life for
     * Check if the token matches
     * Check the HMAC
     *
     * @throws \Exception
     * @return bool
     */
    public function isValid()
    {
        try {
            $isValid = false;
            // check if a token is used
            if ($this->useToken) {
                // check if timestamp is in ttl
                if ($this->checkTimestamp()) {
                    // check for a valid token
                    if ($this->check_token($this->getToken())) {
                        $isValid = $this->check_hmac_hash();
                    }
                }
            } else {
                $isValid = $this->check_hmac_hash();
            }

            return $isValid;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Check if the timestamp is in range of time to life
     *
     * @throws \Exception
     * @return bool
     */
    public function checkTimestamp()
    {

        try {
            $isValid = false;

            $ttl        = $this->getTtl();
            $clientTime = $this->getTimestamp();

            $this->checkValue($ttl);
            $this->checkValue($clientTime);

            $serverTime = time();
            $timeDiff   = $serverTime - $clientTime;

            if ($timeDiff <= $this->getTtl()) {
                $isValid = true;
            }

            return $isValid;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Check if the token is valid
     * This will build a token form the api key, timestamp and private key and check if it matches the passed token
     * This requires the api key and private key to be set, not null and not empty (@see $this->create_token())
     */
    public function check_token($token)
    {
        if ($token === $this->create_token()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Build a token from the api-key.timestamp and the private key
     * This requires the api key, private key and timestamp to be set, not null and not empty
     *
     *
     * @throws \Exception
     * @return string Token build from the api-key.timestamp and the private key
     */
    public function create_token()
    {
        try {
            $apiKey     = $this->getApiKey();
            $timestamp  = $this->getTimestamp();
            $privateKey = $this->getPrivateKey();

            $this->checkValue($privateKey);
            $this->checkValue($apiKey);
            $this->checkValue($timestamp);
            $token = $this->create_hash($apiKey . $timestamp, $privateKey);

            return $token;
        } catch (Exception $e) {
            throw $e;
        }

    }


    /**
     * Create Hash
     *
     * This method is where we'll recreate the hash coming from the client using the secret key to check_token the
     * request
     */
    public function create_hash($payload, $privateKey)
    {
        try {
            // don't accept empty private keys
            $this->checkValue($privateKey);
            $hmacHash = hash_hmac($this->getAlgorithm(), $payload, $privateKey);

            return $hmacHash;
        } catch (Exception $e) {
            throw $e;
        }

    }

    /**
     * Check if the HMAC from the client matches the on created on the server
     *
     * @throws \Exception
     * @return bool
     */
    public function check_hmac_hash()
    {
        try {
            $isValid = false;
            $privateKey = $this->getPrivateKey();
            $this->checkValue($privateKey);

            // get the data that was used to generate the client_hmac_hash
            $payload = $this->getPayload();
            // rebuild the hash on the server
            $server_hmac_hash = $this->create_hash($payload, $privateKey);

            if ($this->getHmacHash() === $server_hmac_hash) {
                $isValid = true;
            }

            return $isValid;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function create_and_set_hmac()
    {
        try {
            if ($this->useToken) {
                $timestamp = time();
                $this->setTimestamp($timestamp);
                $this->setToken($this->create_token_for_timestamp($timestamp));
            }
            $privateKey = $this->getPrivateKey();
            $this->checkValue($privateKey);
            $this->setHmacHash($this->create_hash($this->getPayload(), $privateKey));

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a token for the passed timestamp.
     * This requires the api key and private key to be set, not null and not empty
     *
     * @param $timestamp
     *
     * @return string
     * @throws \Exception
     */
    public function create_token_for_timestamp($timestamp)
    {
        try {
            $apiKey     = $this->getApiKey();
            $privateKey = $this->getPrivateKey();

            $this->checkValue($privateKey);
            $this->checkValue($apiKey);
            $this->checkValue($timestamp);

            $token = $this->create_hash($apiKey . $timestamp, $privateKey);

            return $token;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /*
     * #########################################
     * PRIVAT HELPERS
     * #########################################
     */
    /**
     * @param $value
     *
     * @throws \Exception
     */
    private function checkValue($value)
    {
        if ($value === null || $value === '') {
            throw new \Exception ('The passed value was not set, empty or null. Set before usage!');
        }
    }
}