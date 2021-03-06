<?php

namespace CoreShop\Component\Pimcore\BatchProcessing;

use \Iterator;
use \Countable;
use Pimcore\Model\Listing\AbstractListing;

final class BatchListing implements Iterator, Countable
{
    /**
     * @var AbstractListing
     */
    private $list;

    /**
     * @var int
     */
    private $batchSize;

    /**
     * @var int
     */
    private $index = 0;

    /**
     * @var int
     */
    private $loop = 0;

    /**
     * @var int
     */
    private $total = 0;

    /**
     * @var array
     */
    private $items = [];

    /**
     * @param AbstractListing $list
     * @param int             $batchSize
     */
    public function __construct(AbstractListing $list, int $batchSize)
    {
        $this->list = $list;
        $this->batchSize = $batchSize;

        $this->list->setLimit($batchSize);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->items[$this->index];
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->index++;

        if ($this->index >= $this->batchSize) {
            $this->index = 0;
            $this->loop++;

            $this->load();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return ($this->index + 1) * ($this->loop + 1);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->items[$this->index]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->index = 0;
        $this->loop = 0;

        $this->load();
    }

    public function count()
    {
        if (!$this->total) {
            if (!method_exists($this->list, 'count')) {
                throw new \InvalidArgumentException(sprintf('%s listing class does not support count.', get_class($this->list)));
            }

            $this->total = $this->list->getTotalCount();
        }
        return $this->total;
    }

    /**
     * Load all items based on current state
     */
    protected function load()
    {
        $this->list->setOffset($this->loop * $this->batchSize);
        $this->items = $this->list->load();
    }
}
