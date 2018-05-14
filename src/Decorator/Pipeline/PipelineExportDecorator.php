<?php
/**
 * PipelineExportDecorator.php
 *
 * @author    Pierre Tomasina <pierre.tomasina@continuousphp.com>
 * @copyright Copyright (c) 2018 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      PipelineExportDecorator.php
 * @link      http://github.com/continuousphp/sdk the canonical source repo
 */

namespace Continuous\Sdk\Decorator\Pipeline;

use Continuous\Sdk\Decorator\ExportDecoratorInterface;
use Continuous\Sdk\Entity\Pipeline;
use Symfony\Component\Yaml\Yaml;

/**
 * PipelineExportDecorator
 *
 * @package   Continuous\Sdk
 * @author    Pierre Tomasina <pierre.tomasina@continuousphp.com>
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class PipelineExportDecorator implements ExportDecoratorInterface
{
    /**
     * @var Pipeline
     */
    protected $pipeline;

    public function __construct(Pipeline $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    public function toYaml(): string
    {
        $sanitizer['containers'] = $this->getPhpVersions();

        $provisioning = $this->getProvisioning();
        $package = $this->getPackage();

        if (false === empty($provisioning)) {
            $sanitizer['provisioning'] = $provisioning;
        }

        if (false === empty($package)) {
            $sanitizer['package'] = $package;
        }

        if ($tests = $this->getTests()) {
            $sanitizer['tests'] = $tests;
        }

        $deployments = $this->getDeployments();
        if (false === empty($deployments)) {
            $sanitizer['deployments'] = $deployments;
        }

        $notifications = $this->getNotifications();
        if (false === empty($notifications)) {
            $sanitizer['notifications'] = $notifications;
        }

        $sanitizer['pipelines'] = $this->getYamlPipeline();

        return Yaml::dump($sanitizer, 8, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
    }

    protected function getPhpVersions()
    {
        return array_map(function ($version) {
            return 'php:' . $version;
        }, $this->pipeline->get('phpVersions'));
    }

    protected function getProvisioning()
    {
        $provisioning = [];

        if ($this->pipeline->get('composerPath')) {
            $provisioning['dependency_managers']['composer'] = $this->pipeline->get('composerPath');
        }

        if ($this->pipeline->get('phingPath')) {
            $provisioning['task_managers']['phing'] = $this->pipeline->get('phingPath');
        }

        return $provisioning;
    }

    protected function getPackage()
    {
        $test = [];
        $dist = [];

        if ($this->pipeline->get('composerPath') && $this->pipeline->get('runComposerHooksInPrepare')) {
            $test['run_composer_hooks'] = true;
        }

        if (false === empty($this->pipeline->get('preparePhingTargets'))) {
            foreach ($this->pipeline->get('preparePhingTargets') as $target) {
                $test['before_package']['phing']['targets'][] = $target;
            }

            foreach ($this->pipeline->get('preparePhingVariables') as $propertyKey => $propertyValue) {
                $test['before_package']['phing']['properties'][$propertyKey] = $propertyValue;
            }
        }

        if (false === empty($this->pipeline->get('prepareShellCommands'))) {
            $test['before_package']['shell'] = implode("\n", $this->pipeline->get('prepareShellCommands'));
        }

        if (false === empty($this->pipeline->get('prepareEnvironmentVariables'))) {
            $test['variables'] = array_map([
                static::class, 'mapEnvVariable'
            ], $this->pipeline->get('prepareEnvironmentVariables'));
        }

        if ($this->pipeline->get('composerPath') && $this->pipeline->get('runComposerHooksInPackage')) {
            $dist['run_composer_hooks'] = true;
        }

        if (false === empty($this->pipeline->get('preDeployPhingTargets'))) {
            foreach ($this->pipeline->get('preDeployPhingTargets') as $target) {
                $dist['before_package']['phing']['targets'][] = $target;
            }

            foreach ($this->pipeline->get('preDeployPhingVariables') as $propertyKey => $propertyValue) {
                $dist['before_package']['phing']['properties'][$propertyKey] = $propertyValue;
            }
        }

        if (false === empty($this->pipeline->get('preDeployShellCommands'))) {
            $dist['before_package']['shell'] = implode("\n", $this->pipeline->get('preDeployShellCommands'));
        }

        if (false === empty($this->pipeline->get('preDeployEnvironmentVariables'))) {
            $dist['variables'] = array_map([
                static::class, 'mapEnvVariable'
            ], $this->pipeline->get('preDeployEnvironmentVariables'));
        }

        $package = [];

        if (false === empty($test)) {
            $package['test'] = $test;
        }

        if (false === empty($dist)) {
            $package['dist'] = $dist;
        }

        return $package;
    }

    protected function getTests()
    {
        if (true === empty($this->pipeline->get('enabledTests'))) {
            return [];
        }

        $tests = [];

        foreach ($this->pipeline->get('enabledTests') as $test) {
            $type = $test['type'];
            $typeCaller = 'getTest' . ucfirst(strtolower($type));

            if (!method_exists($this, $typeCaller)) {
                file_put_contents(
                    'php://stderr',
                    sprintf("\nThe test type %s is not yet supported in Yaml. 
                    Please open an issue on ContinuousPHP/SDK\n", $type)
                );
                continue;
            }

            $tests[$type] = call_user_func([$this, $typeCaller], $test);
        }

        return $tests;
    }

    protected function getDeployments()
    {
        $deployment = $this->pipeline->get('deployment');
        $typeCaller = 'getDeployment' . ucfirst(strtolower($deployment['type']));

        if (!method_exists($this, $typeCaller)) {
            file_put_contents(
                'php://stderr',
                sprintf("\nThe deployment type %s is not yet supported in Yaml. 
                Please open an issue on ContinuousPHP/SDK\n", $deployment['type'])
            );
            return null;
        }

        return array_map([static::class, $typeCaller], $deployment['destinations']);
    }

    protected function getNotifications()
    {
        if (true === empty($this->pipeline->get('notificationHooks'))) {
            return [];
        }

        $notifications = [];

        foreach ($this->pipeline->get('notificationHooks') as $notif) {
            $type = $notif['type'];
            $typeCaller = 'getNotif' . ucfirst(strtolower($type));

            if (!method_exists($this, $typeCaller)) {
                file_put_contents(
                    'php://stderr',
                    sprintf("\nThe notification type %s is not yet supported in Yaml. 
                    Please open an issue on ContinuousPHP/SDK\n", $type)
                );
                continue;
            }

            $notifications[] = [
                'type' => $type,
                'events' => array_map([static::class, 'mapNotificationEvents'], array_keys($notif['events'])),
            ] + call_user_func([$this, $typeCaller], $notif);
        }

        return $notifications;
    }

    protected function getYamlPipeline()
    {
        $pipeline = [
            'reference' => $this->pipeline->get('settingId')
        ];

        if ($this->pipeline->get('createGitHubRelease')) {
            $pipeline['github_release'] = $this->pipeline->get('createGitHubRelease');
        }

        return $pipeline;
    }

    /**
     * @note pure function
     * @param $deployment
     * @return array
     */
    protected static function getDeploymentScript($deployment)
    {
        $result = [
            'type' => 'script',
            'name' => $deployment['name'],
            'script' => $deployment['script'],
        ];

        if (false === empty($deployment['pullRequest'])) {
            $result['on_pull_request'] = true;
        }

        if (false === empty($deployment['environmentVariables'])) {
            $result['variables'] = array_map([static::class, 'mapEnvVariable'], $deployment['environmentVariables']);
        }

        return $result;
    }

    /**
     * @note pure function
     * @param $test
     * @return array
     */
    protected static function getTestPhpunit($test)
    {
        return [
                'paths' => $test['paths'],
                'name' => $test['name'],
            ] + static::mapTestCommonProperties($test);
    }

    /**
     * @note pure function
     * @param $test
     * @return array
     */
    protected static function getTestBehat($test)
    {
        $behat = [
                'paths' => $test['paths'],
                'name' => $test['name'],
            ] + static::mapTestCommonProperties($test);

        if (false === empty($test['useProgressFormatter'])) {
            $behat['progress_format'] = true;
        }

        if (false === empty($test['useStrict'])) {
            $behat['strict'] = true;
        }

        if (false === empty($test['useVerbose'])) {
            $behat['verbose'] = true;
        }

        if (false === empty($test['stopOnFailure'])) {
            $behat['stop_on_failure'] = true;
        }

        return $behat;
    }

    protected static function getNotifSlack($notif)
    {
        return [
            'webhook' => $notif['url'],
        ];
    }

    /**
     * Map the common properties for test configuration into Yaml array
     *
     * @note pure function
     * @param array $test
     * @return array
     */
    protected static function mapTestCommonProperties(array $test)
    {
        $result = [];

        if (false === empty($test['blocking'])) {
            $result['blocking'] = true;
        }

        if (false === empty($test['requiredCoveragePercentage'])) {
            $result['coverage_threshold'] = $test['requiredCoveragePercentage'];
        }

        if (false === empty($test['environmentVariables'])) {
            $result['variables'] = array_map([static::class, 'mapEnvVariable'], $test['environmentVariables']);
        }

        if (false === empty($test['phingTargets'])) {
            foreach ($test['phingTargets'] as $target) {
                $result['before_test']['phing']['targets'][] = $target;
            }

            foreach ($test['phingVariables'] as $propertyKey => $propertyValue) {
                $result['before_test']['phing']['properties'][$propertyKey] = $propertyValue;
            }
        }

        if (false === empty($test['shellCommands'])) {
            $result['before_test']['shell'] = implode("\n", $test['shellCommands']);
        }

        return $result;
    }

    /**
     * @note pure function
     * @param $variable
     * @return array
     */
    protected static function mapEnvVariable($variable)
    {
        $setVar = [ 'name' => $variable['name'] ];

        if (false === empty($variable['writeOnly'])) {
            $setVar['encrypted'] = true;
        }

        if (false === empty($variable['value'])) {
            $setVar['value'] = $variable['value'];
        }

        return $setVar;
    }

    protected static function mapNotificationEvents($event)
    {
        switch ($event) {
            case 'createBuild':
                return 'start';

            case 'buildSuccess':
                return 'success';

            case 'buildFail':
                return 'failure';

            case 'buildDeployed':
                return 'deployed';
        }

        return null;
    }
}
