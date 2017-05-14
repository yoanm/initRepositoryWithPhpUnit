<?php
namespace Yoanm\PhpUnitConfigManager\Application\Updater\Common;

use Yoanm\PhpUnitConfigManager\Domain\Model\Common\ConfigurationItemInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\UnmanagedNode;

class HeaderFooterHelper
{

    /**
     * @param UnmanagedNode[]|ConfigurationItemInterface[] $itemList
     *
     * @return UnmanagedNode[]
     */
    public function extractHeaderOrLeadingSpaceNode(array $itemList)
    {
        $headerNodeList = $this->extractNodeHeaderList($itemList);
        if (0 === count($headerNodeList)
            && $leadingSpaceNode = $this->extractLeadingSpace($itemList)
        ) {
            $headerNodeList = [$leadingSpaceNode];
            return $headerNodeList;
        }
        return $headerNodeList;
    }

    /**
     * @param UnmanagedNode[]|ConfigurationItemInterface[] $itemList
     *
     * @return UnmanagedNode[]
     */
    public function extractNodeHeaderList(array $itemList)
    {
        // Manage only Header comment => Comment is before the node and comment have new line before and after
        $potentialEndTextNode = array_pop($itemList);
        $potentialCommentNode = array_pop($itemList);
        $potentialStartTextNode = array_pop($itemList);
        if ($this->isTextNodeWithNewLine($potentialEndTextNode)
            && $this->isCommentNode($potentialCommentNode)
            && $this->isTextNodeWithNewLine($potentialStartTextNode)
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
    public function extractLeadingSpace(array $itemList)
    {
        $potentialEndTextNode = array_pop($itemList);
        if ($this->isTextNode($potentialEndTextNode)) {
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
    public function extractNodeFooterList(array $itemList, array $extractedHeaderBlockCommentNodeList)
    {
        // Manage 2 types of footer :
        // - comment that follow the node without new line between (=inline comment)
        // - comment that follow the node and with a new line between them (=end block comment)
        $commentOrText = array_shift($itemList);
        $potentialComment = array_shift($itemList);

        $list = $this->tryExtractBlockFooterComment(
            $extractedHeaderBlockCommentNodeList,
            $commentOrText,
            $potentialComment
        );
        if (0 === count($list)) {
            $list = $this->extractTrailingComment($itemList, $commentOrText, $potentialComment);
        }

        return $list;
    }

    /**
     * @param UnmanagedNode[]                              $groupedBaseHeaderNodeList
     * @param UnmanagedNode[]|ConfigurationItemInterface[] $updatedItemList
     *
     * @return UnmanagedNode[]|ConfigurationItemInterface[]
     */
    public function mergeHeaderNodeList(array $groupedBaseHeaderNodeList, array $updatedItemList)
    {
        foreach ($groupedBaseHeaderNodeList as $node) {
            $updatedItemList[] = $node;
        }

        return $updatedItemList;
    }

    /**
     * @param UnmanagedNode[]                              $groupedBaseFooterNodeList
     * @param UnmanagedNode[]|ConfigurationItemInterface[] $updatedItemList
     *
     * @return UnmanagedNode[]|ConfigurationItemInterface[]
     */
    public function mergeFooterNodeList(array $groupedBaseFooterNodeList, array $updatedItemList)
    {
        foreach ($groupedBaseFooterNodeList as $node) {
            $updatedItemList[] = $node;
        }

        return $updatedItemList;
    }

    /**
     * @param UnmanagedNode[] $headerNodeList
     * @return bool
     */
    public function hasHeaderBlockComment(array $headerNodeList)
    {
        return 3 === count($headerNodeList)
            && $this->isCommentNode($headerNodeList[1]);
    }

    /**
     * @param array $headerNodeList
     * @param array $itemList
     * @return array
     */
    public function updateListIfHeader(array $headerNodeList, array $itemList)
    {
        if (count($headerNodeList)) {
            // Remove the comment from the returned list
            $itemList = array_slice(
                $itemList,
                0,
                count($itemList) - count($headerNodeList)
            );
            return $itemList;
        }
        return $itemList;
    }

    /**
     * @param array $itemList
     * @param array $footerNodeList
     * @return array
     */
    public function updateListIfFooter(array $itemList, array $footerNodeList)
    {
        if (count($footerNodeList)) {
            // Remove the comment from the list (no need to manage it anymore)
            $itemList = array_slice($itemList, count($footerNodeList));
            return $itemList;
        }
        return $itemList;
    }

    /**
     * @param mixed $node
     * @param int   $type
     *
     * @return bool
     */
    protected function isUnmanagedNodeType($node, $type)
    {
        return $node
            && $node instanceof UnmanagedNode
            && $node->getValue()->nodeType === $type;
    }

    /**
     * @param mixed $node
     *
     * @return bool
     */
    protected function isTextNode($node)
    {
        return $this->isUnmanagedNodeType($node, XML_TEXT_NODE);
    }

    /**
     * @param mixed $node
     *
     * @return bool
     */
    protected function isCommentNode($node)
    {
        return $this->isUnmanagedNodeType($node, XML_COMMENT_NODE);
    }

    /**
     * @param mixed $node
     *
     * @return bool
     */
    protected function isTextNodeWithNewLine($node)
    {
        return $this->isTextNode($node)
            && false !== strpos($node->getValue()->nodeValue, "\n")
        ;
    }

    /**
     * @param array                      $itemList
     * @param ConfigurationItemInterface $commentOrText
     * @param ConfigurationItemInterface $potentialComment
     *
     * @return array
     */
    private function extractTrailingComment(array $itemList, $commentOrText, $potentialComment)
    {
        $list = [];
        if ($this->isCommentNode($commentOrText)) {
            $list = [$commentOrText];
        } elseif ($this->isTextNode($commentOrText) && $this->isCommentNode($potentialComment)) {
            // In case no CR => it's a trailing comment with space(s) before
            if (!$this->isTextNodeWithNewLine($commentOrText)) {
                $list = [$commentOrText, $potentialComment];
            } else {
                //Else, repush the two nodes and check if a header comment could be found
                // In this case, the comment will be managed later (for the following element node)
                $newItemList = $itemList;
                array_unshift($newItemList, $potentialComment);
                array_unshift($newItemList, $commentOrText);
                $potentialHeaderList = $this->extractNodeHeaderList($itemList);
                if (0 === count($potentialHeaderList)) {
                    // It's not the header block comment of a following element node => so add it as footer
                    $list = [$commentOrText, $potentialComment];
                }
            }
        }

        return $list;
    }

    /**
     * @param ConfigurationItemInterface[]    $extractedHeaderBlockCommentNodeList
     * @param ConfigurationItemInterface|null $commentOrText
     * @param ConfigurationItemInterface|null $potentialComment
     *
     * @return array
     */
    private function tryExtractBlockFooterComment(
        array $extractedHeaderBlockCommentNodeList,
        $commentOrText,
        $potentialComment
    ) {
        $list = [];
        // Search for an footer block comment node only if a header exist
        if ($this->hasHeaderBlockComment($extractedHeaderBlockCommentNodeList)
            && $this->isTextNode($commentOrText)
            && $this->isCommentNode($potentialComment)
            && $this->isTextNodeWithNewLine($commentOrText)
        ) {
            $list = [$commentOrText, $potentialComment];
        }

        return $list;
    }
}
