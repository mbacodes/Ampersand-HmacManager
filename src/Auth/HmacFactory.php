<?php
/**
 *
 * File         HmacFactory.php
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
 * Class        HmacFactory
 * A factory class the initiates the HMAC-object for the use cases eg. a request, repsonse
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
class HmacFactory
{
    /**
     * @var \Ampersand\Auth\HMAC
     */
    public static $hmac;

    public static function initHMACFromSettings($settings)
    {
        // we need the places where to find the various information
    }
}