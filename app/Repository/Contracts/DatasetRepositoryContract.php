<?php

namespace App\Repository\Contracts;


interface DatasetRepositoryContract
{
    /**
     * Get all dataset
     * @return mixed
     */
    public function getAll(): \stdClass;

    public function getBasedUrl(): string ;

    public function setPath(string $path);

    public function getPath(): string;

    public function setOptions(array $options);
}