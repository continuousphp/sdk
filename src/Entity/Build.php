<?php

namespace Continuous\Sdk\Entity;

/**
 * Class Build
 * @package Continuous\Sdk\Entity
 */
class Build extends EntityAbstract
{
    const STATES = ['in-progress', 'complete', 'timeout', 'canceled'];
    const RESULTS = ['success', 'warning', 'failed'];

    public function hydrate(array $attributes)
    {
        $this->id = $attributes['buildId'];
        $this->attributes = $attributes;
    }

    public function getDuration()
    {
        $duration = (int)$this->get('duration');

        $durationSeconds = $duration - (60 * floor($duration / 60));
        $durationMinutes = floor($duration / 60);
        $durationHours = floor($duration / 3600);

        if ($durationMinutes > 60) {
            $durationMinutes -= 60;
        }

        if ($durationMinutes == 60) {
            $durationMinutes = 0;
        }

        return new \DateInterval("PT{$durationHours}H{$durationMinutes}M{$durationSeconds}S");
    }

    public function getLaunchUser()
    {
        $user = new User();
        $user->hydrate($this->attributes['_embedded']['launchedBy']);

        return $user;
    }
}
