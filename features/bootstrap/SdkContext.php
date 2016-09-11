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
use GuzzleHttp\Command\Exception\CommandClientException;

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
    
    /** @var CommandClientException */
    protected $exception;
    
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
        $config = $table->getRowsHash();

        $this->sdk = Service::factory($config);
    }

    /**
     * @Then a :code error should occurs
     */
    public function aErrorShouldOccurs($code)
    {
        if (!$this->exception || $this->exception->getCode()!=$code) {
            throw $this->exception;
        }
    }

    /**
     * @When I call the :operation operation
     * @When I call the :operation operation with
     */
    public function iCallTheOperation($operation, TableNode $table = null)
    {
        $this->result = null;
        $args = [];
        if ($table) {
            foreach ($table->getTable() as $row) {
                if (isset($query)) {
                    $query .= "&";
                } else {
                    $query = '';
                }
                $query.= $row[0] . '=' . $row[1];
            };
            if ($query) {
                parse_str($query, $args);
            }
        }
        
        try {
            $this->result = call_user_func([$this->sdk, $operation], $args);
        } catch (CommandClientException $e) {
            $this->exception = $e;
        }
    }

    /**
     * @Then The response should be a :type collection
     */
    public function theResponseShouldBeACollection($type)
    {
        if ($this->exception) {
            throw $this->exception;
        }
        if (substr($type, -1)=='y') {
            $type = substr_replace($type, 'ies', -1);
        } else {
            $type .= 's';
        }
        
        \PHPUnit_Framework_Assert::assertInternalType('array', $this->result);
        \PHPUnit_Framework_Assert::assertArrayHasKey('_embedded', $this->result);
        \PHPUnit_Framework_Assert::assertArrayHasKey($type, $this->result['_embedded']);
    }

    /**
     * @Then The response should be a :type resource
     */
    public function theResponseShouldBeAResource($type)
    {
        if ($this->exception) {
            if ($this->exception instanceof CommandClientException) {
                echo $this->exception->getResponse()->getBody();
            }
            throw $this->exception;
        }
        \PHPUnit_Framework_Assert::assertInternalType('array', $this->result);
        \PHPUnit_Framework_Assert::assertArrayHasKey($type . 'Id', $this->result);
    }
    
    /**
     * @Then The response should contain a :key
     */
    public function theResponseShouldContainA($key)
    {
        \PHPUnit_Framework_Assert::assertInternalType('array', $this->result);
        \PHPUnit_Framework_Assert::assertArrayHasKey($key, $this->result);
        \PHPUnit_Framework_Assert::assertNotEmpty($this->result[$key]);
    }
    
    /**
     * @Then The :key file should exists
     */
    public function theFileShouldExists($key)
    {
        \PHPUnit_Framework_Assert::assertFileExists($this->result[$key]);
        unlink($this->result[$key]);
    }
}
