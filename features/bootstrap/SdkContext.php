<?php
/**
 * SdkContext.php
 *
 * @author    Frederic Dewinne <frederic@continuousphp.com>
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      SdkContext.php
 * @link      http://github.com/continuousphp/sdk the canonical source repo
 */

namespace Continuous\Features;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Continuous\Sdk\Client;
use Continuous\Sdk\Service;

/**
 * SdkContext
 *
 * @package   Continuous\Features\Sdk
 * @author    Frederic Dewinne <frederic@continuousphp.com>
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class SdkContext implements Context, SnippetAcceptingContext
{
    /**
     * @var Client
     */
    protected $sdk;
    
    protected $result;
    
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }
    
    /**
     * @Given I've instatiated the sdk with the following
     */
    public function iVeInstatiatedTheSdkWithTheFollowing(TableNode $table)
    {
        $config = array_column($table->getTable(), 1, 0);
        $this->sdk = Service::factory($config);
    }

    /**
     * @When I call the :operation operation
     */
    public function iAccessTheOperation($operation, TableNode $table = null)
    {
        if ($table) {
            $args = array_column($table->getTable(), 1, 0);
        } else {
            $args = [];
        }
        
        $this->result = call_user_func([$this->sdk, $operation], $args);
    }

    /**
     * @Then The response should be a :type collection
     */
    public function theResponseShouldBeACollection($type)
    {
        \PHPUnit_Framework_Assert::assertInternalType('array', $this->result);
        \PHPUnit_Framework_Assert::assertArrayHasKey('_embedded', $this->result);
        \PHPUnit_Framework_Assert::assertArrayHasKey($type . 's', $this->result['_embedded']);
    }
}
