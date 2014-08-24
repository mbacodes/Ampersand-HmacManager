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
     * Check if the timestamp is in range of 'time to life'
     *
     * @throws \Exception
     * @return bool
     */
    public function checkTimestamp();

    /**
     * Build a token from the api-key.timestamp and the private key
     *
     * @return string Token build from the api-key.timestamp and the private key
     */
    public function create_token();

    /**
     * Authenticate
     *
     * This is the authenticate method where we check the token from the client against
     * a token that we will recreate here on the sevrer. If the 2 match, it's a pass.
     */
    public function authenticate($token);

    /**
     * Create Hash
     *
     * This method is where we'll recreate the hash coming from the client using the private key to authenticate the
     * request
     */
    public function create_hash($payload, $timestamp);

    /**
     * Check if the HMAC from the client matches the on created on the server
     *
     * @return bool
     */
    public function check_hmac_hash();

    /**
     * Check if the timestamp matches in the range of time to life for
     * Check if the token matches
     * Check the HMAC
     *
     * @return bool
     */
    public function isValid();

}