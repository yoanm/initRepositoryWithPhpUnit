<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Script;

class ScriptListNormalizer implements DenormalizerInterface
{
    /**
     * @param Script[] $scriptList
     *
     * @return array
     */
    public function normalize(array $scriptList)
    {
        $normalizedList = [];
        foreach ($scriptList as $script) {
            $normalizedList[$script->getName()][] = $script->getCommand();
        }

        return $normalizedList;
    }

    /**
     * @param array $scriptList
     *
     * @return Script[]
     */
    public function denormalize(array $scriptList)
    {
        $denormalizedList = [];
        foreach ($scriptList as $scriptName => $scriptCommandList) {
            foreach ($scriptCommandList as $scriptCommand) {
                $denormalizedList[] = new Script($scriptName, $scriptCommand);
            }
        }

        return $denormalizedList;
    }
}
