<?php

namespace Bars\Blog\Block;

use Bars\Blog\Model\Post;
use Bars\Blog\Model\PostFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class PostView
 * @package Bars\Blog\Block
 */
class PostView extends Template implements IdentityInterface
{
    /**
     * @param Context $context
     * @param Post $post
     * @param PostFactory $postFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Post $post,
        PostFactory $postFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_post = $post;
        $this->_postFactory = $postFactory;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        if (!$this->hasData('post')) {
            if ($this->getPostId()) {
                /** @var Post $page */
                $post = $this->_postFactory->create();
            } else {
                $post = $this->_post;
            }
            $this->setData('post', $post);
        }
        return $this->getData('post');
    }

    /**
     * Return identifiers for produced content
     * @return array
     */
    public function getIdentities()
    {
        return [Post::CACHE_TAG . '_' . $this->getPost()->getId()];
    }

}
