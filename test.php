#!/usr/bin/env php
<?php

require_once(__DIR__.'/vendor/autoload.php');

$attributeNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer();
$unmanagedNodeNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\UnmanagedNodeNormalizer();
$groupNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Groups\GroupNormalizer();
$filesystemItemNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\FilesystemItemNormalizer($attributeNormalizer);
$testSuiteItemNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites\TestSuite\TestSuiteItemNormalizer($attributeNormalizer, $filesystemItemNormalizer);
$testSuiteNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuites\TestSuiteNormalizer($attributeNormalizer, $testSuiteItemNormalizer, $unmanagedNodeNormalizer);
$testSuitesNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\TestSuitesNormalizer($testSuiteNormalizer, $unmanagedNodeNormalizer );
$groupInclusionNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Groups\GroupInclusionNormalizer($groupNormalizer, $unmanagedNodeNormalizer);
$groupsNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\GroupsNormalizer($groupInclusionNormalizer, $unmanagedNodeNormalizer);
$excludedWhiteListNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Filter\ExcludedWhiteListNormalizer($attributeNormalizer, $filesystemItemNormalizer, $unmanagedNodeNormalizer);
$whiteListEntryNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Filter\WhiteListEntryNormalizer($attributeNormalizer, $excludedWhiteListNormalizer, $filesystemItemNormalizer);
$whiteListNormalizer= new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Filter\WhiteListNormalizer($attributeNormalizer, $whiteListEntryNormalizer, $unmanagedNodeNormalizer);
$filterNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\FilterNormalizer($whiteListNormalizer, $unmanagedNodeNormalizer);
$logNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Logging\LogNormalizer($attributeNormalizer);
$loggingNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\LoggingNormalizer($logNormalizer, $unmanagedNodeNormalizer);
$listenerNormalize = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Listeners\ListenerNormalizer($attributeNormalizer, $unmanagedNodeNormalizer);
$listenersNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\ListenersNormalizer($listenerNormalize, $unmanagedNodeNormalizer);
$phpItemNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Php\PhpItemNormalizer($attributeNormalizer);
$phpNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\PhpNormalizer($phpItemNormalizer, $unmanagedNodeNormalizer);
$normalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\ConfigurationNormalizer($attributeNormalizer, $testSuitesNormalizer, $groupsNormalizer, $filterNormalizer, $loggingNormalizer, $listenersNormalizer, $phpNormalizer, $unmanagedNodeNormalizer);
$fileNormalizer = new Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\ConfigurationFileNormalizer($normalizer, $unmanagedNodeNormalizer);
$decoder = new Yoanm\PhpUnitConfigManager\Application\Serializer\Encoder\PhpUnitEncoder();

$content = file_get_contents('./phpunit.test.xml');

$configFile = $fileNormalizer->denormalize($decoder->decode($content));
//var_dump($configFile->getNodeList());die();
$newContent = $decoder->encode($fileNormalizer->normalize($configFile));
echo($newContent);die();
