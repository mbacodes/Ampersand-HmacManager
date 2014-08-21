<?php
/**
 *
 * File         HmacTestCase.php
 *
 * @author      ${AUTHOR}
 * @copyright   ${COPYRIGHT}
 */
namespace Ampersand\Tests;

/**
 *
 * Class        HmacTestCase
 *
 * @package     Ampersand\Tests
 *
 */
class HmacTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Ampersand\Http\HeadersInterface
     */
    protected $headers;

    /**
     * @var \Ampersand\Http\RequestInterface
     */
    protected $request;

    /**
     * @var \Ampersand\Http\ResponseInterface
     */
    protected $response;


    // We support these methods for testing. These are available via
    // `this->get()` and `$this->post()`. This is accomplished with the
    // `__call()` magic method below.
    private $testingMethods = array('get', 'post', 'patch', 'put', 'delete', 'head');

    // Run for each unit test to setup our slim app environment
    /**
     * Mock headers, request and response
     */
    public function setup()
    {
        // Mock the Headers
        $this->headers = $this->getMock('\Ampersand\Http\HeadersInterface');

        // Mock the Request
        $this->request = $this->getMock('\Ampersand\Http\RequestInterface');

        // Mock the Response
        $this->response = $this->getMock('\Ampersand\Http\ResponseInterface');

    }

    /**
     * @todo-comment
     */
    public function testHeadersIsInstanceOfHeadersInterface()
    {
        $this->assertInstanceOf('\Ampersand\Http\HeadersInterface', $this->headers);
    }

    /**
     * @todo-comment
     */
    public function testRequestIsInstanceOfRequestInterface()
    {
        $this->assertInstanceOf('\Ampersand\Http\RequestInterface', $this->request);
    }

    /**
     * @todo-comment
     */
    public function testResponseIsInstanceOfResponseInterface()
    {
        $this->assertInstanceOf('\Ampersand\Http\ResponseInterface', $this->response);
    }

    /**
     * Test if the Data / Payload for HMAC encryption read
     *
     * @todo-comment
     * @todo-implement
     */
    public function testCanGetPayload()
    {

    }

    // Abstract way to make a request to SlimPHP, this allows us to mock the
    // slim environment
    /**
     * @todo-comment
     * @todo-implement
     */
    private function setRequest($method, $path, $formVars = array(), $optionalHeaders = array())
    {

    }

    /**
     * @todo-comment
     * @todo-implement
     */
    public function setResponse()
    {

    }

    // Implement our `get`, `post`, and other http operations
    public function __call($method, $arguments)
    {
        if (in_array($method, $this->testingMethods)) {
            list($path, $formVars, $headers) = array_pad($arguments, 3, array());

            return $this->setRequest($method, $path, $formVars, $headers);
        }
        throw new \BadMethodCallException(strtoupper($method) . ' is not supported');
    }


}