<?php

namespace Bars\Blog\Block;

use Bars\Blog\Api\Data\PostInterface;
use Bars\Blog\Model\Post;
use Bars\Blog\Model\ResourceModel\Post\Collection as PostCollection;
use Bars\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class PostList extends Template implements IdentityInterface
{
    /**
     * @var CollectionFactory
     */
    protected $_postCollectionFactory;

    /**
     * @param Context $context
     * @param CollectionFactory $postCollectionFactory ,
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $postCollectionFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_postCollectionFactory = $postCollectionFactory;
    }

    /**
     * @return \Bars\Blog\Model\ResourceModel\Post\Collection
     */
    public function getPosts()
    {
        if (!$this->hasData('posts')) {
            $posts = $this->_postCollectionFactory
                ->create()
                ->addFilter('is_active', 1)
                ->addOrder(
                    PostInterface::CREATION_TIME,
                    PostCollection::SORT_ORDER_DESC
                );
            $this->setData('posts', $posts);
        }
        return $this->getData('posts');
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [Post::CACHE_TAG . '_' . 'list'];
    }

}
