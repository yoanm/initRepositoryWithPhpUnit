<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Autoload;

class AutoloadListNormalizer implements DenormalizerInterface
{
    /**
     * @param Autoload[] $autoloadList
     *
     * @return array
     */
    public function normalize(array $autoloadList)
    {
        $normalizedList = [];
        foreach ($autoloadList as $autoload) {
            $normalizedList[$autoload->getType()][$autoload->getNamespace()] = $autoload->getPath();
        }

        return $normalizedList;
    }

    /**
     * @param array $autoloadList
     *
     * @return Autoload[]
     */
    public function denormalize(array $autoloadList)
    {
        $denormalizedList = [];
        foreach ($autoloadList as $autoloadType => $entryList) {
            foreach ($entryList as $namespace => $path) {
                $denormalizedList[] = new Autoload($autoloadType, $path, $namespace);
            }
        }

        return $denormalizedList;
    }
}
