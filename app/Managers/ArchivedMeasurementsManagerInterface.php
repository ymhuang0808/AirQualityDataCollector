<?php

namespace App\Managers;


interface ArchivedMeasurementsManagerInterface
{
    /**
     * Sets the the source type of archived measurements
     *
     * @param string $sourceType
     * @return self
     */
    public function setSourceType(string $sourceType);

    /**
     * Gets the source type of archived measurements
     *
     * @return string
     */
    public function getSourceType(): string ;

    /**
     * Execute the archived process
     *
     * @param int $chunkCount
     * @return boolean
     */
    public function process(int $chunkCount = 100): bool ;
}