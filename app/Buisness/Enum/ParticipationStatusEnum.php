<?php
namespace App\Buisness\Enum;

use App\ParticipationStatus;
use BenSampo\Enum\Enum;

class ParticipationStatusEnum extends Enum
{
    const Quiet  = 1;
    const Promised  = 2;
    const Canceled  = 3;

    public function getModel() : Role
    {
        return ParticipationStatus::findByName($this->key);
    }
}
