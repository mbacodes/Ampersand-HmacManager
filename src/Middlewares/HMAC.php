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
namespace Ampersand\Middlewares;

/**
 *
 * Class        HMAC
 *
 * @package     Ampersand\Middlewares
 *
 * @author      Mathias Bauer <info@mbauer.eu>
 * @copyright   ${COPYRIGHT}
 * @link        ${LINK}
 * @license     ${LICENSE}
 * @version     ${VERSION}
 * @package     ${PACKAGE}
 */
class HMAC implements HMACMiddlewareInterface
{

    /**
     * Deny Access
     *
     */
    public function deny_access()
    {
        // TODO: Implement deny_access() method.
    }

    /**
     * Check Timestamp
     *
     * Uses the header value timestamp to check against the current timestamp
     * If the request was made within a reasonable amount of time (@see HMAC->ttl, e.g. 10 sec),
     */
    public function check_timestamp()
    {
        // TODO: Implement check_timestamp() method.
    }

    /**
     * Authenticate
     *
     * This is the authenticate method where we check the hash from the client against
     * a hash that we will recreate here on the sevrer. If the 2 match, it's a pass.
     */
    public function authenticate($nonce)
    {
        // TODO: Implement authenticate() method.
    }

    /**
     * Create Hash
     *
     * This method is where we'll recreate the hash coming from the client using the secret key to authenticate the
     * request
     */
    public function create_hash($payload, $timestamp)
    {
        // TODO: Implement create_hash() method.
    }
}