<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;

class AttributeNS
{
    /** @var string */
    private $namespaceURI;
    /** @var string */
    private $qualifiedName;
    /** @var string */
    private $value;

    /**
     * @param string $namespaceURI
     * @param string $qualifiedName
     * @param string $value
     */
    public function __construct($namespaceURI, $qualifiedName, $value)
    {
        $this->namespaceURI = $namespaceURI;
        $this->qualifiedName = $qualifiedName;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getQualifiedName()
    {
        return $this->qualifiedName;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getNamespaceURI()
    {
        return $this->namespaceURI;
    }
}
