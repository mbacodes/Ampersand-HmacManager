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
namespace Ampersand\Middlewares;

/**
 *
 * Interface        HMACMiddlewareInterface
 *
 * Interface for a middleware for HMAC Authorization
 *
 * @package     Ampersand\Middlewares
 *
 * @author      Mathias Bauer <info@mbauer.eu>
 * @copyright   ${COPYRIGHT}
 * @link        ${LINK}
 * @license     ${LICENSE}
 * @version     ${VERSION}
 * @package     Ampersand
 */
interface HMACMiddlewareInterface
{
    /**
     * Check HMAC
     *
     *
     * determine what kind of HMAC check is needed
     * check for request
     * check for response
     * check how the data is transfered header|cookies
     * define the mapping for the keys
     * use a factory to generate the HMAC-Object
     *
     */
    public function call();

}