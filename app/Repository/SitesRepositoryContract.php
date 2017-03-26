<?php
/**
 * Created by PhpStorm.
 * User: aming
 * Date: 2017/3/12
 * Time: 下午5:08
 */

namespace App\Repository;


interface SitesRepositoryContract
{
    /**
     * Get all sites
     * @return mixed
     */
    public function getAll(): array;
}
