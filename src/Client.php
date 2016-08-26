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
 * @method    array createProject(array $args = array())
 * @method    array getRepositories()
 * @method    array getPipelines(array $args = array())
 * @method    array putPipeline(array $args = array())
 * @method    array getBuilds(array $args = array())
 * @method    array getBuild(array $args = array())
 * @method    array getPackage(array $args = array())
 */
class Client extends GuzzleClient
{
    public function createProject($args = array())
    {
        if (isset($args['repository'])) {
            $args['name'] = $args['repository'];
        }

        return $this->execute(
            $this->getCommand(
                'createProject',
                $args
            )
        );
    }
    
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
        
        $fp = fopen($path, 'w+');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($ch);
        
        if (!$result) {
            $exception = new Exception("The download failed with the following error: " . curl_error($ch));
        }
        
        curl_close($ch);
        fclose($fp);
        
        if (isset($exception)) {
            throw $exception;
        }
        
        return ['path' => $path];
    }
}
