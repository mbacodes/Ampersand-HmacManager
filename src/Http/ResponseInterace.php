<?php
/**
 *
 * File         ResponseInterface.php
 *
 * ResponseInterface following the implementation from Slim Framework
 *
 * @author      Mathias Bauer <info@mbauer.eu>
 * @copyright   ${COPYRIGHT}
 * @link        ${LINK}
 * @license     ${LICENSE}
 * @version     ${VERSION}
 * @package     ${PACKAGE}
 */
namespace Ampersand\Http;

/**
 *
 * Class        ResponseInterface
 * ResponseInterface following the implementation from Slim Framework
 *
 * @package     Ampersand\Http
 *
 * @author      Mathias Bauer <info@mbauer.eu>
 * @copyright   ${COPYRIGHT}
 * @link        ${LINK}
 * @license     ${LICENSE}
 * @version     ${VERSION}
 * @package     ${PACKAGE}
 */
interface ResponseInterface
{

    public function getStatus();

    public function setStatus($status);

    public function getBody();

    public function setBody($content);

    /**
     * Append HTTP response body
     *
     * @param  string $body    Content to append to the current HTTP response body
     * @param  bool   $replace Overwrite existing response body?
     *
     * @return string               The updated HTTP response body
     */
    public function write($body, $replace = false);

    public function getLength();

    /**
     * Finalize
     *
     * This prepares this response and returns an array
     * of [status, headers, body]. This array is passed to outer middleware
     * if available or directly to the Slim run method.
     *
     * @return array[int status, array headers, string body]
     */
    public function finalize();

    /**
     * Redirect
     *
     * This method prepares this response to return an HTTP Redirect response
     * to the HTTP client.
     *
     * @param string $url    The redirect destination
     * @param int    $status The redirect HTTP status code
     */
    public function redirect($url, $status = 302);

    /**
     * Helpers: Empty?
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Helpers: Informational?
     *
     * @return bool
     */
    public function isInformational();

    /**
     * Helpers: OK?
     *
     * @return bool
     */
    public function isOk();

    /**
     * Helpers: Successful?
     *
     * @return bool
     */
    public function isSuccessful();

    /**
     * Helpers: Redirect?
     *
     * @return bool
     */
    public function isRedirect();

    /**
     * Helpers: Redirection?
     *
     * @return bool
     */
    public function isRedirection();

    /**
     * Helpers: Forbidden?
     *
     * @return bool
     */
    public function isForbidden();

    /**
     * Helpers: Not Found?
     *
     * @return bool
     */
    public function isNotFound();

    /**
     * Helpers: Client error?
     *
     * @return bool
     */
    public function isClientError();

    /**
     * Helpers: Server Error?
     *
     * @return bool
     */
    public function isServerError();

    /**
     * Array Access: Offset Get
     */
    public function offsetGet($offset);

    /**
     * Array Access: Offset Set
     */
    public function offsetSet($offset, $value);

    /**
     * Array Access: Offset Unset
     */
    public function offsetUnset($offset);

    /**
     * DEPRECATION WARNING! Countable interface will be removed from \Slim\Http\Response.
     * Call `count` on `headers` or `cookies` properties directly.
     *
     * Countable: Count
     */
    public function count();


    /**
     * Get message for HTTP status code
     *
     * @param  int $status
     *
     * @return string|null
     */
    public static function getMessageForCode($status);
}