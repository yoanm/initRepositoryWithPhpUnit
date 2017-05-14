<?php
namespace Yoanm\PhpUnitConfigManager\Infrastructure\Transformer;

use Symfony\Component\Serializer\SerializerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\FilesystemItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\Configuration;
use Yoanm\PhpUnitConfigManager\Domain\Model\ConfigurationFile;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\ExcludedWhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteListItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\Group;
use Yoanm\PhpUnitConfigManager\Domain\Model\Groups\GroupInclusion;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners;
use Yoanm\PhpUnitConfigManager\Domain\Model\Listeners\Listener;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging;
use Yoanm\PhpUnitConfigManager\Domain\Model\Logging\Log;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php;
use Yoanm\PhpUnitConfigManager\Domain\Model\Php\PhpItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\ExcludedTestSuiteItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\TestSuites\TestSuite\TestSuiteItem;
use Yoanm\PhpUnitConfigManager\Infrastructure\Serializer\Encoder\PhpUnitEncoder;

class InputTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testDummy()
    {
        
    }
}
