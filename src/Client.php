<?php
/**
 * Client.php
 *
 * @author    Frederic Dewinne <frederic@continuousphp.com>
 * @copyright Copyright (c) 2015 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Client.php
 * @link      http://github.com/continuousphp/sdk the canonical source repo
 */

namespace Continuous\Sdk;

use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Stream\Stream;

/**
 * Service
 *
 * @package   Continuous\Sdk
 * @author    Frederic Dewinne <frederic@continuousphp.com>
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @method    array getProjects()
 * @method    array getProject(array $args = array())
 * @method    array getBuilds(array $args = array())
 * @method    array getBuild(array $args = array())
 * @method    array getPackage(array $args = array())
 */
class Client extends GuzzleClient
{
    /**
     * @param array $args
     * @return array
     */
    public function downloadPackage($args = array())
    {
        if (empty($args['destination'])) {
            throw new \InvalidArgumentException("destination argument is not provided");
        }
        $destination = $args['destination'];
        
        unset($args['destination']);
        $package = $this->getPackage($args);
        
        $url = $package['url'];
        $path = $destination . '/' . pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_BASENAME);
        
        $original = Stream::factory(fopen($url, 'r'));
        $local = Stream::factory(fopen($path, 'w'));
        $local->write($original->getContents());
        
        return ['path' => $path];
    }
}
