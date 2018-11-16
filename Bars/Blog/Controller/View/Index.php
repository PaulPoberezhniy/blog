<?php

namespace Bars\Blog\Controller\View;

use \Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;

class Index extends Action
{
    /** @var ForwardFactory */
    protected $resultForwardFactory;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory
    )
    {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of posts.
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $post_id = $this->getRequest()->getParam('post_id', $this->getRequest()->getParam('id', false));
        /** @var \Bars\Blog\Helper\Post $post_helper */
        $post_helper = $this->_objectManager->get('Bars\Blog\Helper\Post');
        $result_page = $post_helper->prepareResultPost($this, $post_id);
        if (!$result_page) {
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }
        return $result_page;
    }
}
