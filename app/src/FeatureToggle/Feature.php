<?php

declare(strict_types=1);

namespace App\FeatureToggle;

class Feature implements FeatureFlag
{
    private array $features;

    public function __construct(array $features)
    {
        $this->features = $features;
    }

    public function isEnabled(string $name): bool
    {
        if (!array_key_exists($name, $this->features)) {
            return false;
        }
        return $this->features[$name];
    }
}
