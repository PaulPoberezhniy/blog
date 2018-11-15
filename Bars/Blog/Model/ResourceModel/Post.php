<?php

namespace Bars\Blog\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Blog post mysql resource
 */
class Post extends AbstractDb
{

    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * Construct
     * @param Context $context
     * @param DateTime $date
     * @param string|null $resourcePrefix
     */
    public function __construct(
        Context $context,
        DateTime $date,
        $resourcePrefix = null
    )
    {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }

    /**
     * Initialize resource model
     * @return void
     */
    protected function _construct()
    {
        $this->_init('bars_blog_post', 'post_id');
    }

    /**
     * Process post data before saving
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {

        if (!$this->isValidPostUrlKey($object)) {
            throw new LocalizedException(
                __('The post URL key contains capital letters or disallowed symbols.')
            );
        }

        if ($this->isNumericPostUrlKey($object)) {
            throw new LocalizedException(
                __('The post URL key cannot be made of only numbers.')
            );
        }

        if ($object->isObjectNew() && !$object->hasCreationTime()) {
            $object->setCreationTime($this->_date->gmtDate());
        }

        $object->setUpdateTime($this->_date->gmtDate());

        return parent::_beforeSave($object);
    }

    /**
     * Load an object using 'url_key' field if there's no field specified and value is not numeric
     * @param AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return $this
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'url_key';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Retrieve select object for load object data
     * @param string $field
     * @param mixed $value
     * @param \Bars\Blog\Model\Post $object
     * @return \Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {

            $select->where(
                'is_active = ?',
                1
            )->limit(
                1
            );
        }

        return $select;
    }

    /**
     * Retrieve load select with filter by url_key and activity
     * @param string $url_key
     * @param int $isActive
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadByUrlKeySelect($url_key, $isActive = null)
    {
        $select = $this->getConnection()->select()->from(
            ['bp' => $this->getMainTable()]
        )->where(
            'bp.url_key = ?',
            $url_key
        );

        if (!is_null($isActive)) {
            $select->where('bp.is_active = ?', $isActive);
        }

        return $select;
    }

    /**
     *  Check whether post url key is numeric
     * @param AbstractModel $object
     * @return bool
     */
    protected function isNumericPostUrlKey(AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('url_key'));
    }

    /**
     *  Check whether post url key is valid
     * @param AbstractModel $object
     * @return bool
     */
    protected function isValidPostUrlKey(AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('url_key'));
    }

    /**
     * Check if post url key exists
     * return post id if post exists
     * @param string $url_key
     * @return int
     */
    public function checkUrlKey($url_key)
    {
        $select = $this->_getLoadByUrlKeySelect($url_key, 1);
        $select->reset(\Zend_Db_Select::COLUMNS)->columns('bp.post_id')->limit(1);

        return $this->getConnection()->fetchOne($select);
    }
}
