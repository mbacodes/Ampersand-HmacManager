<?php
/**
 * Interface following the implementation by Slim Framework
 *
 * Slim - a micro PHP 5 framework
 *
 * @author      Josh Lockhart <info@slimframework.com>
 * @copyright   2011 Josh Lockhart
 * @link        http://www.slimframework.com
 * @license     http://www.slimframework.com/license
 * @version     2.4.2
 * @package     Slim
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Ampersand\Http;

/**
 * HTTP Request Interface following the Slim Framework implementation
 *
 * This class provides a human-friendly interface to the Slim environment variables;
 * environment variables are passed by reference and will be modified directly.
 *
 * @package Slim
 * @author  Josh Lockhart
 * @since   1.0.0
 */
interface RequestInterface
{
    const METHOD_HEAD     = 'HEAD';
    const METHOD_GET      = 'GET';
    const METHOD_POST     = 'POST';
    const METHOD_PUT      = 'PUT';
    const METHOD_PATCH    = 'PATCH';
    const METHOD_DELETE   = 'DELETE';
    const METHOD_OPTIONS  = 'OPTIONS';
    const METHOD_OVERRIDE = '_METHOD';

    /**
     * Get HTTP method
     *
     * @return string
     */
    public function getMethod();

    /**
     * Is this a GET request?
     *
     * @return bool
     */
    public function isGet();

    /**
     * Is this a POST request?
     *
     * @return bool
     */
    public function isPost();

    /**
     * Is this a PUT request?
     *
     * @return bool
     */
    public function isPut();

    /**
     * Is this a PATCH request?
     *
     * @return bool
     */
    public function isPatch();

    /**
     * Is this a DELETE request?
     *
     * @return bool
     */
    public function isDelete();

    /**
     * Is this a HEAD request?
     *
     * @return bool
     */
    public function isHead();

    /**
     * Is this a OPTIONS request?
     *
     * @return bool
     */
    public function isOptions();

    /**
     * Is this an AJAX request?
     *
     * @return bool
     */
    public function isAjax();

    /**
     * Is this an XHR request? (alias of Slim_Http_Request::isAjax)
     *
     * @return bool
     */
    public function isXhr();

    /**
     * Fetch GET and POST data
     *
     * This method returns a union of GET and POST data as a key-value array, or the value
     * of the array key if requested; if the array key does not exist, NULL is returned,
     * unless there is a default value specified.
     *
     * @param  string $key
     * @param  mixed  $default
     *
     * @return array|mixed|null
     */
    public function params($key = null, $default = null);

    /**
     * Fetch GET data
     *
     * This method returns a key-value array of data sent in the HTTP request query string, or
     * the value of the array key if requested; if the array key does not exist, NULL is returned.
     *
     * @param  string $key
     * @param  mixed  $default Default return value when key does not exist
     *
     * @return array|mixed|null
     */
    public function get($key = null, $default = null);

    /**
     * Fetch POST data
     *
     * This method returns a key-value array of data sent in the HTTP request body, or
     * the value of a hash key if requested; if the array key does not exist, NULL is returned.
     *
     * @param  string $key
     * @param  mixed  $default Default return value when key does not exist
     *
     * @return array|mixed|null
     * @throws \RuntimeException If environment input is not available
     */
    public function post($key = null, $default = null);

    /**
     * Fetch PUT data (alias for \Slim\Http\Request::post)
     *
     * @param  string $key
     * @param  mixed  $default Default return value when key does not exist
     *
     * @return array|mixed|null
     */
    public function put($key = null, $default = null);

    /**
     * Fetch PATCH data (alias for \Slim\Http\Request::post)
     *
     * @param  string $key
     * @param  mixed  $default Default return value when key does not exist
     *
     * @return array|mixed|null
     */
    public function patch($key = null, $default = null);

    /**
     * Fetch DELETE data (alias for \Slim\Http\Request::post)
     *
     * @param  string $key
     * @param  mixed  $default Default return value when key does not exist
     *
     * @return array|mixed|null
     */
    public function delete($key = null, $default = null);

    /**
     * Fetch COOKIE data
     *
     * This method returns a key-value array of Cookie data sent in the HTTP request, or
     * the value of a array key if requested; if the array key does not exist, NULL is returned.
     *
     * @param  string $key
     *
     * @return array|string|null
     */
    public function cookies($key = null);

    /**
     * Does the Request body contain parsed form data?
     *
     * @return bool
     */
    public function isFormData();

    /**
     * Get Headers
     *
     * This method returns a key-value array of headers sent in the HTTP request, or
     * the value of a hash key if requested; if the array key does not exist, NULL is returned.
     *
     * @param  string $key
     * @param  mixed  $default The default value returned if the requested header is not available
     *
     * @return mixed
     */
    public function headers($key = null, $default = null);

    /**
     * Get Body
     *
     * @return string
     */
    public function getBody();

    /**
     * Get Content Type
     *
     * @return string|null
     */
    public function getContentType();

    /**
     * Get Media Type (type/subtype within Content Type header)
     *
     * @return string|null
     */
    public function getMediaType();

    /**
     * Get Media Type Params
     *
     * @return array
     */
    public function getMediaTypeParams();

    /**
     * Get Content Charset
     *
     * @return string|null
     */
    public function getContentCharset();

    /**
     * Get Content-Length
     *
     * @return int
     */
    public function getContentLength();

    /**
     * Get Host
     *
     * @return string
     */
    public function getHost();

    /**
     * Get Host with Port
     *
     * @return string
     */
    public function getHostWithPort();

    /**
     * Get Port
     *
     * @return int
     */
    public function getPort();

    /**
     * Get Scheme (https or http)
     *
     * @return string
     */
    public function getScheme();

    /**
     * Get Script Name (physical path)
     *
     * @return string
     */
    public function getScriptName();

    /**
     * LEGACY: Get Root URI (alias for Slim_Http_Request::getScriptName)
     *
     * @return string
     */
    public function getRootUri();

    /**
     * Get Path (physical path + virtual path)
     *
     * @return string
     */
    public function getPath();

    /**
     * Get Path Info (virtual path)
     *
     * @return string
     */
    public function getPathInfo();

    /**
     * LEGACY: Get Resource URI (alias for Slim_Http_Request::getPathInfo)
     *
     * @return string
     */
    public function getResourceUri();

    /**
     * Get URL (scheme + host [ + port if non-standard ])
     *
     * @return string
     */
    public function getUrl();

    /**
     * Get IP
     *
     * @return string
     */
    public function getIp();

    /**
     * Get Referrer
     *
     * @return string|null
     */
    public function getReferrer();

    /**
     * Get User Agent
     *
     * @return string|null
     */
    public function getUserAgent();
}
