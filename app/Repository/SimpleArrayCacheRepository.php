<?php

namespace App\Repository;


use App\Repository\Contracts\CacheableContract;

class SimpleArrayCacheRepository implements CacheableContract
{
    protected $items = [];

    /**
     * Set an item into cache by key name
     *
     * @param string $key
     * @param $item
     * @return mixed
     */
    public function setItemByKey(string $key, $item)
    {
        $this->items[$key] = $item;
    }

    /**
     * Get an cached item by key name. If there is no the key name in cache,
     * it returns null.
     *
     * @param string $key
     * @return mixed
     */
    public function getItemByKey(string $key)
    {
        if ($this->isHit($key)) {
            return $this->items[$key];
        }

        return null;
    }

    /**
     * Check if there is corresponded key in cache
     *
     * @param string $key
     * @return bool
     */
    public function isHit(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Get all cache data
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }
}