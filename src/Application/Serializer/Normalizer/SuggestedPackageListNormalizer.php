<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Domain\Model\SuggestedPackage;

class SuggestedPackageListNormalizer implements DenormalizerInterface
{
    /**
     * @param SuggestedPackage[] $suggestedPackageList
     *
     * @return array
     */
    public function normalize(array $suggestedPackageList)
    {
        $normalizedList = [];
        foreach ($suggestedPackageList as $package) {
            $normalizedList[$package->getName()] = $package->getDescription();
        }

        return $normalizedList;
    }

    /**
     * @param array $suggestedPackageList
     *
     * @return SuggestedPackage[]
     */
    public function denormalize(array $suggestedPackageList)
    {
        $denormalizedList = [];
        foreach ($suggestedPackageList as $packageName => $packageDesc) {
            $denormalizedList[] = new SuggestedPackage($packageName, $packageDesc);
        }

        return $denormalizedList;
    }
}
