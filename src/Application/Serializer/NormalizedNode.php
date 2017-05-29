<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute;

class NormalizedNode
{
    /** @var string|null */
    private $nodeName;
    /** @var Attribute[] */
    private $nodeAttributeList = [];
    /** @var string|\DOMNode|null */
    private $nodeContent;
    /** @var NormalizedNode[] */
    private $nodeChildList;
    /** @var AttributeNS[] */
    private $nodeAttributeNSList = [];

    /**
     * @param Attribute[]          $nodeAttributeList
     * @param NormalizedNode[]     $nodeChildList
     * @param string|null          $nodeName
     * @param string|\DOMNode|null $nodeContent
     * @param AttributeNS[]        $nodeAttributeNSList
     */
    public function __construct(
        array $nodeAttributeList = [],
        array $nodeChildList = [],
        $nodeName = null,
        $nodeContent = null,
        array $nodeAttributeNSList = []
    ) {
        $this->nodeAttributeList = $nodeAttributeList;
        $this->nodeChildList = $nodeChildList;
        $this->nodeName = $nodeName;
        $this->nodeContent = $nodeContent;
        $this->nodeAttributeNSList = $nodeAttributeNSList;
    }

    /**
     * @return null|string
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }

    /**
     * @return \Yoanm\PhpUnitConfigManager\Domain\Model\Common\Attribute[]
     */
    public function getNodeAttributeList()
    {
        return $this->nodeAttributeList;
    }

    /**
     * @return \DOMNode|null|string
     */
    public function getNodeContent()
    {
        return $this->nodeContent;
    }

    /**
     * @return NormalizedNode[]
     */
    public function getNodeChildList()
    {
        return $this->nodeChildList;
    }

    /**
     * @return AttributeNS[]
     */
    public function getNodeAttributeNSList()
    {
        return $this->nodeAttributeNSList;
    }
}
