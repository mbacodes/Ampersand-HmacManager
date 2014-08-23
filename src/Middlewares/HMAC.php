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
     * Check HMAC
     *
     */
    public function call()
    {
        // determine what kind of HMAC check is needed
        // check for request
        // check for response
        // check how the data is transfered header|cookies
        // use a factory to generate the HMAC-Object
        // call HMAC->isValid to validate

        // TODO: Implement call() method.
    }
}