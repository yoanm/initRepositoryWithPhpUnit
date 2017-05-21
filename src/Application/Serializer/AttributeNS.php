<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;

class AttributeNS extends Attribute
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
        parent::__construct($qualifiedName, $value);
        $this->namespaceURI = $namespaceURI;
    }

    /**
     * @return string
     */
    public function getNamespaceURI()
    {
        return $this->namespaceURI;
    }
}
