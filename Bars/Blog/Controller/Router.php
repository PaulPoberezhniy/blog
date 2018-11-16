<?php

namespace Bars\Blog\Controller;

use Bars\Blog\Model\PostFactory;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\UrlInterface;

/**
 * Cms Controller Router
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * Event manager
     * @var ManagerInterface
     */
    protected $_eventManager;

    /**
     * Post factory
     * @var PostFactory
     */
    protected $_postFactory;

    /**
     * Config primary
     * @var \Magento\Framework\App\State
     */
    protected $_appState;

    /**
     * Url
     * @var UrlInterface
     */
    protected $_url;

    /**
     * Response
     * @var ResponseInterface
     */
    protected $_response;

    /**
     * @param ActionFactory $actionFactory
     * @param ManagerInterface $eventManager
     * @param UrlInterface $url
     * @param PostFactory $postFactory
     * @param ResponseInterface $response
     */
    public function __construct(
        ActionFactory $actionFactory,
        ManagerInterface $eventManager,
        UrlInterface $url,
        PostFactory $postFactory,
        ResponseInterface $response
    )
    {
        $this->actionFactory = $actionFactory;
        $this->_eventManager = $eventManager;
        $this->_url = $url;
        $this->_pageFactory = $postFactory;
        $this->_response = $response;
    }

    /**
     * Validate and Match Cms Page and modify request
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $url_key = trim($request->getPathInfo(), '/blog/');
        $url_key = rtrim($url_key, '/');

        /** @var \Bars\Blog\Model\Post $post */
        $post = $this->_pageFactory->create();
        $post_id = $post->checkUrlKey($url_key);
        if (!$post_id) {
            return null;
        }

        $request->setModuleName('blog')->setControllerName('view')->setActionName('index')->setParam('post_id', $post_id);
        $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $url_key);

        return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
    }
}
