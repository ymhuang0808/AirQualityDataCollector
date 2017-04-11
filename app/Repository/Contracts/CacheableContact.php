<?php

namespace App\Repository\Contracts;


interface CacheableContact
{
    /**
     * Set an item into cache by key name
     *
     * @param string $key
     * @param $item
     * @return mixed
     */
    public function setItemByKey(string $key, $item);

    /**
     * Get an cached item by key name. If there is no the key name in cache,
     * it returns null.
     *
     * @param string $key
     * @return mixed
     */
    public function getItemByKey(string $key);

    /**
     * Check if there is corresponded key in cache
     *
     * @param string $key
     * @return bool
     */
    public function isHit(string $key): bool ;

    /**
     * Get all cache data
     *
     * @return array
     */
    public function getItems(): array ;
}