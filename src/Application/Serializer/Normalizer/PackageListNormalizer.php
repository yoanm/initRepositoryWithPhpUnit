<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Package;

class PackageListNormalizer implements DenormalizerInterface
{
    /**
     * @param Package[] $packageList
     *
     * @return array
     */
    public function normalize(array $packageList)
    {
        $normalizedList = [];
        foreach ($packageList as $package) {
            $normalizedList[$package->getName()] = $package->getVersionConstraint();
        }

        return $normalizedList;
    }

    /**
     * @param array $packageList
     *
     * @return Package[]
     */
    public function denormalize(array $packageList)
    {
        $normalizedList = [];
        foreach ($packageList as $packageName => $packageVersion) {
            $normalizedList[] = new Package($packageName, $packageVersion);
        }

        return $normalizedList;
    }
}
