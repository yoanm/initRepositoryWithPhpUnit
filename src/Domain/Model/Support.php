<?php
namespace Yoanm\PhpUnitConfigManager\Domain\Model;

class Support implements ConfigurationItemInterface
{
    /** @var string */
    private $type;
    /** @var string */
    private $url;

    /**
     * @param string $type
     * @param string $url
     */
    public function __construct($type, $url)
    {
        $this->type = $type;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemId()
    {
        return $this->getType();
    }
}
