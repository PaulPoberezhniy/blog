<?php

namespace Bars\Blog\Controller;

use Bars\Blog\Model\PostFactory;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Url;
use Magento\Framework\UrlInterface;

/**
 * Class Router
 * @package Bars\Blog\Controller
 */
class Router implements RouterInterface
{
    const BLOG_NAME = 'blog';
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
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(RequestInterface $request)
    {
        $url_key = trim($request->getPathInfo(), DIRECTORY_SEPARATOR. self::BLOG_NAME. DIRECTORY_SEPARATOR);
        $url_key = rtrim($url_key, '/');

        /** @var \Bars\Blog\Model\Post $post */
        $post = $this->_pageFactory->create();
        $post_id = $post->checkUrlKey($url_key);
        if (!$post_id) {
            return null;
        }

        $request->setModuleName(self::BLOG_NAME)->setControllerName('view')->setActionName('index')->setParam('post_id', $post_id);
        $request->setAlias(Url::REWRITE_REQUEST_PATH_ALIAS, $url_key);

        return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
    }
}
