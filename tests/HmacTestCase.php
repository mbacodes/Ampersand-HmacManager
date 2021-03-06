<?php
/**
 *
 * File         HmacTestCase.php
 *
 * @author      ${AUTHOR}
 * @copyright   ${COPYRIGHT}
 */
namespace Ampersand\Tests;

use Ampersand\Auth\HmacManager;
use Xpmock\TestCaseTrait;

/**
 *
 * Class        HmacTestCase
 *
 * @package     Ampersand\Tests
 * @author      Mathias Bauer <info@mbauer.eu>
 * @license     GPLv3
 *
 */
class HmacTestCase extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    // Run for each unit test to setup our app environment
    /**
     * Mock something
     */
    public function setup()
    {
        // set defaults
    }

    public function testHmacManagerInstanceOfHmacManager()
    {
        $this->assertInstanceOf('\Ampersand\Auth\HmacManager', new HmacManager());
    }

    /**
     * assertException extension from @see https://gist.github.com/VladaHejda/8826707
     *
     * @param callable $callback
     * @param string   $expectedException
     * @param null     $expectedCode
     * @param null     $expectedMessage
     */
    protected function assertException(callable $callback, $expectedException = 'Exception', $expectedCode = null, $expectedMessage = null)
    {
        if (!class_exists($expectedException) || interface_exists($expectedException)) {
            $this->fail("An exception of type '$expectedException' does not exist.");
        }

        try {
            $callback();
        } catch (\Exception $e) {
            $class   = get_class($e);
            $message = $e->getMessage();
            $code    = $e->getCode();

            $extraInfo = $message ? " (message was $message, code was $code)" : ($code ? " (code was $code)" : '');
            $this->assertInstanceOf($expectedException, $e, "Failed asserting the class of exception $extraInfo.");

            if (null !== $expectedCode) {
                $this->assertEquals($expectedCode, $code, "Failed asserting code of thrown $class.");
            }
            if (null !== $expectedMessage) {
                $this->assertContains($expectedMessage, $message, "Failed asserting the message of thrown $class.");
            }

            return;
        }

        $extraInfo = $expectedException !== 'Exception' ? " of type $expectedException" : '';
        $this->fail("Failed asserting that exception $extraInfo was thrown.");
    }


}