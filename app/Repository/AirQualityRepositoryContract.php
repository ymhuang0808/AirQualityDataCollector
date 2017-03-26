<?php

namespace App\Repository;


interface AirQualityRepositoryContract {
  /**
   * Get all remote
   * @return mixed
   */
  public function getAll(): array ;
}
