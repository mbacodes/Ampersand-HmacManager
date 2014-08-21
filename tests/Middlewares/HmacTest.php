<?php
/**
 *
 * File         HmacTest.php
 *
 * @author      Mathias Bauer <info@mbauer.eu>
 */
namespace Ampersand\Tests\Middlewares;

use Ampersand\Tests\HmacTestCase;


/**
 *
 * Class        HmacTest
 *
 * @author      ${AUTHOR}
 * @copyright   ${COPYRIGHT}
 */
class HmacTest extends HmacTestCase
{

    public function testNonValidHmacThrowsUnauthorizedException()
    {
        //
    }

    public function testCallsNextMidllewareOnValidHmac()
    {
        // init request and headers
    }
}
