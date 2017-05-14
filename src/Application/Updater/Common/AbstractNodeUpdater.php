<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

abstract class AbstractNodeUpdater
{
    /** @var AbstractNodeUpdater[] */
    private $updateDelegateList;

    /**
     * @param AbstractNodeUpdater[] $updaterDelegateList
     */
    public function __construct(array $updaterDelegateList = [])
    {
        $this->updateDelegateList = $updaterDelegateList;
    }


    /**
     * @param ConfigurationItemInterface $baseItem
     * @param ConfigurationItemInterface $newItem
     *
     * @return ConfigurationItemInterface
     */
    abstract public function merge(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem);

    /**
     * @param ConfigurationItemInterface $item
     *
     * @return bool
     */
    abstract public function supports(ConfigurationItemInterface $item);

    /**
     * @param ConfigurationItemInterface $baseItem
     * @param ConfigurationItemInterface $newItem
     *
     * @return bool
     */
    abstract public function isSameNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem);

    /**
     * @param ConfigurationItemInterface[] $itemList
     *
     * @return ConfigurationItemInterface
     */
    public function update(array $itemList)
    {
        $newItem = array_pop($itemList);
        while ($baseItem = array_pop($itemList)) {
            $newItem = $this->merge($baseItem, $newItem);
        }

        return $newItem;
    }

    /**
     * @param ConfigurationItemInterface[] $baseItemList
     * @param ConfigurationItemInterface[] $newItemList
     *
     * @return ConfigurationItemInterface[]
     */
    protected function mergeItemList(array $baseItemList, array $newItemList)
    {
        $groupedBaseNodeList = $this->groupItemList($baseItemList);
        $supportedNewNodeList = $this->groupItemList($newItemList, true);

        $updatedItemList = [];
        while($groupedBaseNode = array_shift($groupedBaseNodeList)) {
            if ($groupedBaseNode instanceof Block) {
                $newNodeFound = false;
                $updater = $this->getUpdater($groupedBaseNode->getItem(), false);
                if ($updater) {
                    $supportedNewNode = null;
                    foreach ($supportedNewNodeList as $supportedNewNodeKey => $potentialNewNode) {
                        if ($updater->isSameNode($groupedBaseNode->getItem(), $potentialNewNode->getItem())) {
                            $supportedNewNode = $potentialNewNode;
                            unset($supportedNewNodeList[$supportedNewNodeKey]);
                            break;
                        }
                    }
                    if ($supportedNewNode) {
                        $newNodeFound = true;
                        $updatedItemList = $this->mergeBlock($groupedBaseNode, $updatedItemList, $supportedNewNode);
                    }
                }
                if (false === $newNodeFound) {
                    $updatedItemList = $this->mergeBlock($groupedBaseNode, $updatedItemList);
                }
            } else {
                $updatedItemList[] = $groupedBaseNode;
            }
        }
        if (count($supportedNewNodeList)) {
            // 1 - Remove trailing non block object (spaces and comments)
            $trailingNonBlockNodeList = [];
            while($node = array_pop($updatedItemList)) {
                if (!$node instanceof \DOMNode
                    || $node->nodeType === XML_ELEMENT_NODE
                ) {
                    $trailingNonBlockNodeList[] = $node;
                    break;
                }
                $trailingNonBlockNodeList[] = $node;
            }
            // 2 - Append remaining new node
            foreach ($supportedNewNodeList as $supportedNewNode) {
                $updatedItemList = $this->mergeBlock($supportedNewNode, $updatedItemList);
            }
            // 3 - Re append previously removed trailing non block objects
            foreach (array_reverse($trailingNonBlockNodeList) as $trailingNonBlockNode) {
                $updatedItemList[] = $trailingNonBlockNode;
            }
        }


        return $updatedItemList;
    }

    /**
     * @param ConfigurationItemInterface[] $itemList
     * @param bool|false                   $supportedOnly
     *
     * @return \DOMNode[]|Block[]
     */
    protected function groupItemList(array $itemList, $supportedOnly = false)
    {
        $groupedItemList = [];
        while($item = array_shift($itemList)) {
            if ($this->getUpdater($item, false)) {
                // Check if header comment exist if previous nodes
                $headerNodeList = $this->extractNodeHeaderList($groupedItemList);
                if (0 === count($headerNodeList)
                    && $leadingSpaceNode = $this->extractLeadingSpace($groupedItemList)
                ) {
                    $headerNodeList = [$leadingSpaceNode];
                }

                // Check if footer comment exist if base node list
                $footerNodeList = $this->extractNodeFooterList($itemList, $headerNodeList);
                if (count($headerNodeList)) {
                    // Remove the comment from the returned list
                    $groupedItemList = array_slice($groupedItemList, 0, count($groupedItemList)-count($headerNodeList));
                }

                if (count($footerNodeList)) {
                    // Remove the comment from the list (no need to manage it anymore)
                    $itemList = array_slice($itemList, count($footerNodeList));
                }

                $groupedItemList[] = new Block($item, $headerNodeList, $footerNodeList);
            } else {
                $groupedItemList[] = $item;
            }
        }

        if (true === $supportedOnly) {
            return array_filter($groupedItemList, function ($item) {
                return $item instanceof Block;
            });
        }

        return $groupedItemList;
    }

    /**
     * @param UnmanagedNode[]|ConfigurationItemInterface[] $itemList
     *
     * @return UnmanagedNode[]
     */
    private function extractNodeHeaderList(array $itemList)
    {
        // Manage only Header comment => Comment is before the node and comment have new line before and after
        $potentialEndTextNode = array_pop($itemList);
        $potentialCommentNode = array_pop($itemList);
        $potentialStartTextNode = array_pop($itemList);
        if ($this->isUnmanagedNodeType($potentialEndTextNode, XML_TEXT_NODE)
            && $this->isUnmanagedNodeType($potentialCommentNode, XML_COMMENT_NODE)
            && $this->isUnmanagedNodeType($potentialStartTextNode, XML_TEXT_NODE)
            && false !== strpos($potentialEndTextNode->getValue()->nodeValue, "\n")
            && false !== strpos($potentialStartTextNode->getValue()->nodeValue, "\n")
        ) {
            return [
                $potentialStartTextNode,
                $potentialCommentNode,
                $potentialEndTextNode
            ];
        }

        return [];
    }

    /**
     * @param UnmanagedNode[]|ConfigurationItemInterface[] $itemList
     *
     * @return UnmanagedNode|null
     */
    private function extractLeadingSpace(array $itemList)
    {
        $potentialEndTextNode = array_pop($itemList);
        if ($this->isUnmanagedNodeType($potentialEndTextNode, XML_TEXT_NODE)) {
            return $potentialEndTextNode;
        }

        return null;
    }

    /**
     * @param UnmanagedNode[]|ConfigurationItemInterface[] $itemList
     * @param UnmanagedNode[]                              $extractedHeaderBlockCommentNodeList
     *
     * @return UnmanagedNode[]
     */
    private function extractNodeFooterList(array $itemList, array $extractedHeaderBlockCommentNodeList)
    {
        // Manage 2 types of footer :
        // - comment that follow the node without new line between (=inline comment)
        // - comment that follow the node and with a new line between them (=end block comment)
        $commentOrText = array_shift($itemList);
        $potentialComment = array_shift($itemList);
        // Search for an footer block comment node only if a header exist
        if ($this->hasHeaderBlockComment($extractedHeaderBlockCommentNodeList)
            && $this->isUnmanagedNodeType($commentOrText, XML_TEXT_NODE)
            && $this->isUnmanagedNodeType($potentialComment, XML_COMMENT_NODE)
            && false !== strpos($commentOrText->getValue()->nodeValue, "\n")
        ) {
            return [$commentOrText, $potentialComment];
        } elseif ($this->isUnmanagedNodeType($commentOrText, XML_COMMENT_NODE)) {
            return [$commentOrText];
        } elseif ($this->isUnmanagedNodeType($commentOrText, XML_TEXT_NODE)
            && $this->isUnmanagedNodeType($potentialComment, XML_COMMENT_NODE)
        ) {
            // In case no CR => it's a trailing comment with space(s) before
            if (false === strpos($commentOrText->getValue()->nodeValue, "\n")) {
                return [$commentOrText, $potentialComment];
            } else {
                //Else, repush the two nodes and check if a header comment could be found
                // In this case, the comment will be managed later (for the following element node)
                $newItemList = $itemList;
                array_unshift($newItemList, $potentialComment);
                array_unshift($newItemList, $commentOrText);
                $potentialHeaderList = $this->extractNodeHeaderList($itemList);
                if (0 === count($potentialHeaderList)) {
                    // It's not the header block comment of a following element node => so add it as footer
                    return [$commentOrText, $potentialComment];
                }
            }
        }

        return [];
    }

    /**
     * @param ConfigurationItemInterface $baseItem
     * @param ConfigurationItemInterface $newItem
     *
     * @return bool
     */
    protected function isSameNodeOrUnmanagedNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        if ($newItem instanceof UnmanagedNode && $baseItem instanceof UnmanagedNode) {
            if ($this->isUnmanagedNodeType($newItem, XML_COMMENT_NODE)) {
                return ($newItem->getValue()->nodeValue === $baseItem->getValue()->nodeValue);
            }

            return true;
        }

        return $this->isSameNode($baseItem, $newItem);
    }

    /**
     * @param ConfigurationItemInterface $baseItem
     * @param ConfigurationItemInterface $newItem
     *
     * @return ConfigurationItemInterface
     */
    protected function mergeNode(ConfigurationItemInterface $baseItem, ConfigurationItemInterface $newItem)
    {
        if ($newItem instanceof UnmanagedNode) {
            return $newItem;
        }
        try {
            $updater = $this->getUpdater($newItem);
        } catch (\Exception $exception) {
            return $newItem;
        }

        return $updater->update([$baseItem, $newItem]);
    }

    /**
     * @param ConfigurationItemInterface[] $nodeList
     *
     * @return bool|ConfigurationItemInterface
     */
    protected function isFollowedByManagedNode(array $nodeList)
    {
        foreach ($nodeList as $node) {
            if ($this->isUnmanagedNodeType($node, XML_TEXT_NODE)) {
                continue;
            } elseif ($this->supports($node)) {
                return $node;
            }
            break;
        }

        return false;
    }

    /**
     * @param ConfigurationItemInterface $item
     *
     * @return AbstractNodeUpdater|null
     *
     * @throws \Exception
     */
    protected function getUpdater(ConfigurationItemInterface $item, $throwException = true) {
        foreach ($this->updateDelegateList as $delegate) {
            if ($delegate->supports($item)) {
                return $delegate;
            }
        }

        if (true !== $throwException) {
            return null;
        }

        throw new \Exception(sprintf(
            'No update found for item %s',
            get_class($item)
        ));
    }

    /**
     * @param Block $supportedNewNode
     * @param Block $groupedBaseNode
     * @param array $updatedItemList
     * @return array
     */
    protected function mergeBlock(Block $groupedBaseNode, array $updatedItemList, Block $supportedNewNode = null)
    {
        $updatedItemList = $this->mergeHeaderNodeList(
            $groupedBaseNode->getHeaderNodeList(),
            $groupedBaseNode->getFooterNodeList(),
            $updatedItemList,
            $supportedNewNode ? $supportedNewNode->getHeaderNodeList() : [],
            $supportedNewNode ? $supportedNewNode->getFooterNodeList() : []
        );
        $updatedItemList[] = $supportedNewNode
            ? $this->mergeNode(
                $groupedBaseNode->getItem(),
                $supportedNewNode->getItem()
            )
            : $groupedBaseNode->getItem();

        //return $updatedItemList;
        return $this->mergeFooterNodeList(
            $groupedBaseNode->getHeaderNodeList(),
            $groupedBaseNode->getFooterNodeList(),
            $updatedItemList,
            $supportedNewNode ? $supportedNewNode->getHeaderNodeList() : [],
            $supportedNewNode ? $supportedNewNode->getFooterNodeList() : []
        );
    }

    /**
     * @param UnmanagedNode[]                              $groupedBaseHeaderNodeList
     * @param UnmanagedNode[]                              $groupedBaseFooterNodeList
     * @param UnmanagedNode[]|ConfigurationItemInterface[] $updatedItemList
     * @param UnmanagedNode[]                              $supportedHeaderNewNodeList
     * @param UnmanagedNode[]                              $supportedFooterNewNodeList
     *
     * @return UnmanagedNode[]|ConfigurationItemInterface[]
     */
    protected function mergeHeaderNodeList(
        array $groupedBaseHeaderNodeList,
        array $groupedBaseFooterNodeList,
        array $updatedItemList,
        array $supportedHeaderNewNodeList = [],
        array $supportedFooterNewNodeList = []
    ) {
        /*
        $baseHasBlockComments = $this->hasHeaderBlockComment($groupedBaseHeaderNodeList)
            && $this->hasPotentialFooterBlockComment($groupedBaseFooterNodeList);
        $newHasBlockComments = $this->hasHeaderBlockComment($supportedHeaderNewNodeList)
            && $this->hasPotentialFooterBlockComment($supportedFooterNewNodeList);

        // Merge block comments if defined
        if ($newHasBlockComments) {
            // Use the new one if it exist (in case old one exist it is overrided)
            $nodeList = $supportedHeaderNewNodeList;
        } elseif ($baseHasBlockComments) {
            // Keep old one
            $nodeList = $groupedBaseHeaderNodeList;
        } else { //No block headers => means that if header exist it's just a text node
            // Keep old header except if a new one exists
            $nodeList = count($supportedHeaderNewNodeList)
                ? $supportedHeaderNewNodeList
                : $groupedBaseHeaderNodeList
            ;
        }
*/
        foreach ($groupedBaseHeaderNodeList as $node) {
            $updatedItemList[] = $node;
        }

        return $updatedItemList;
    }

    /**
     * @param UnmanagedNode[]                              $groupedBaseHeaderNodeList
     * @param UnmanagedNode[]                              $groupedBaseFooterNodeList
     * @param UnmanagedNode[]|ConfigurationItemInterface[] $updatedItemList
     * @param UnmanagedNode[]                              $supportedHeaderNewNodeList
     * @param UnmanagedNode[]                              $supportedFooterNewNodeList
     *
     * @return UnmanagedNode[]|ConfigurationItemInterface[]
     */
    protected function mergeFooterNodeList(
        array $groupedBaseHeaderNodeList,
        array $groupedBaseFooterNodeList,
        array $updatedItemList,
        array $supportedHeaderNewNodeList = [],
        array $supportedFooterNewNodeList = []
    ) {

        /*
        $baseHasBlockComments = $this->hasHeaderBlockComment($groupedBaseHeaderNodeList)
            && $this->hasPotentialFooterBlockComment($groupedBaseFooterNodeList);
        $newHasBlockComments = $this->hasHeaderBlockComment($supportedHeaderNewNodeList)
            && $this->hasPotentialFooterBlockComment($supportedFooterNewNodeList);

        // Merge block comments if defined
        if ($newHasBlockComments) {
            // Use the new one if it exist (in case old one exist it is overrided)
            $nodeList = $supportedFooterNewNodeList;
        } elseif ($baseHasBlockComments) {
            // Keep old one
            $nodeList = $groupedBaseFooterNodeList;
        } else { //No block footers => means that if footer exist it's just a text node or a trailing comment
            // Keep old footer except if a new one exists
            $nodeList = count($supportedFooterNewNodeList)
                ? $supportedFooterNewNodeList
                : $groupedBaseFooterNodeList
            ;
        }
*/
        foreach ($groupedBaseFooterNodeList as $node) {
            $updatedItemList[] = $node;
        }

        return $updatedItemList;
    }

    /**
     * @param mixed $node
     * @param int   $type
     *
     * @return bool
     */
    private function isUnmanagedNodeType($node, $type)
    {
        return $node instanceof UnmanagedNode && $node->getValue()->nodeType === $type;
    }

    /**
     * @param UnmanagedNode[] $headerNodeList
     * @return bool
     */
    private function hasHeaderBlockComment(array $headerNodeList)
    {
        return 3 === count($headerNodeList)
            && $this->isUnmanagedNodeType($headerNodeList[1], XML_COMMENT_NODE);
    }

    /**
     * @param UnmanagedNode[] $footerNodeList
     * @return bool
     */
    private function hasPotentialFooterBlockComment(array $footerNodeList)
    {
        return 2 === count($footerNodeList)
            && $this->isUnmanagedNodeType($footerNodeList[0], XML_TEXT_NODE)
            && false !== strpos($footerNodeList[0]->getValue()->nodeValue, "\n")
        ;
    }
}
