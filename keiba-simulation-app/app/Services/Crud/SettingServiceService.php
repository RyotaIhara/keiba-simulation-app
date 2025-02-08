<?php

namespace App\Services\Crud;

use App\Entities\SettingService;
use App\Services\Crud\CrudBase;

class SettingServiceService extends CrudBase
{
    public function getSettingService($key) {
        return $this->entityManager->getRepository(SettingService::class)->findOneBy([
            'settingKey' => $key
        ]);
    }
}