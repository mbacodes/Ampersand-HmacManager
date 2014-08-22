<?php
/**
 *
 * File         HMACInterface.php
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
 * Interface        HMAC
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
/**
 * Interface HMACInterface
 *
 * @package Ampersand\Auth
 */
interface HMACInterface
{
    /**
     * @param string $algorithm
     */
    public function setAlgorithm($algorithm);

    /**
     * @return string
     */
    public function getAlgorithm();

    /**
     * @return mixed
     */
    public function getPublicHash();

    /**
     * @param $publicHash
     */
    public function setPublicHash($publicHash);

    /**
     * @return mixed
     */
    public function getPrivateHash();

    /**
     *
     */
    public function setPrivateHash($privateHash);

    /**
     * @return mixed
     */
    public function getPayload();

    /**
     *
     */
    public function setPayload($payload);

    public function getNonce();

    public function setNonce($nonce);

    public function getTimestamp();

    public function setTimestamp($timestamp);


    /**
     * @return bool
     */
    public function isValid();
}