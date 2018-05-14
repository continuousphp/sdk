<?php
/**
 * ExportDecoratorInterface.php
 *
 * @author    Pierre Tomasina <pierre.tomasina@continuousphp.com>
 * @copyright Copyright (c) 2018 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      PipelineExportDecorator.php
 * @link      http://github.com/continuousphp/sdk the canonical source repo
 */

namespace Continuous\Sdk\Decorator;

/**
 * ExportDecoratorInterface
 *
 * @package   Continuous\Sdk
 * @author    Pierre Tomasina <pierre.tomasina@continuousphp.com>
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
interface ExportDecoratorInterface
{
    public function toYaml(): string;
}
