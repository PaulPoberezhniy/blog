<?php

namespace Bars\Blog\Api\Data;

/**
 * Interface PostInterface
 * @package Bars\Blog\Api\Data
 */
interface PostInterface
{
    /**
     * Constants for keys of data array
     */
    const POST_ID = 'post_id';
    const URL_KEY = 'url_key';
    const TITLE = 'title';
    const CONTENT = 'content';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME = 'update_time';
    const IS_ACTIVE = 'is_active';

    /**
     * Get ID
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     * @param int $id
     * @return void
     */
    public function setId($id);

    /**
     * Get URL Key
     * @return string
     */
    public function getUrlKey();

    /**
     * Set URL Key
     * @param string $url_key
     * @return void
     */
    public function setUrlKey($url_key);

    /**
     * Get title
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     * @param string $title
     * @return void
     */
    public function setTitle($title);

    /**
     * Get content
     * @return string|null
     */
    public function getContent();

    /**
     * Set content
     * @param string $content
     * @return void
     */
    public function setContent($content);

    /**
     * Get creation time
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Set creation time
     * @param string $creationTime
     * @return void
     */
    public function setCreationTime($creationTime);

    /**
     * Get update time
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Set update time
     * @param string $updateTime
     * @return void
     */
    public function setUpdateTime($updateTime);

    /**
     * Is active
     * @return bool|null
     */
    public function isActive();

    /**
     * Set is active
     * @param int|bool $isActive
     * @return void
     */
    public function setIsActive($isActive);

    /**
     * Return full URL including base url.
     * @return mixed
     */
    public function getUrl();

}
