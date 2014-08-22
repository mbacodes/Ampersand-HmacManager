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
     * @var string
     */
    private $publicHash = '';

    /**
     * @var string
     */
    private $privateHash = '';

    /**
     * @var string
     */
    private $payload = '';

    /**
     * @var string
     */
    private $algorithm = '';

    /**
     * nonce / token
     * against reply attacks
     *
     * @var string
     */
    private $nonce = '';

    /**
     * Time to live
     *
     * @see HMACInterface->check_timestamp
     * @var string
     */
    private $ttl = '';

    /**
     *
     * against reply attacks
     *
     * @var string
     */
    private $timestamp = '';

    /**
     * @param string $ttl
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    }

    /**
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
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
    }

    /**
     * @return string
     */
    public function getNonce()
    {
        return $this->nonce;
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
    public function setPrivateHash($privateHash)
    {
        $this->privateHash = $privateHash;
    }

    /**
     * @return string
     */
    public function getPrivateHash()
    {
        return $this->privateHash;
    }

    /**
     * @param string $publicHash
     */
    public function setPublicHash($publicHash)
    {
        $this->publicHash = $publicHash;
    }

    /**
     * @return string
     */
    public function getPublicHash()
    {
        return $this->publicHash;
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
     * @return mixed
     */
    public function isValid()
    {
        // TODO: Implement isValid() method.
    }
}