<?php

namespace App\Grading;

class GraderFactory
{
    public static function make(?string $provider, ?string $level): ?GraderInterface
    {
        if (empty($provider) || empty($level)) {
            return null;
        }

        $providerClass = ucfirst(strtolower($provider));
        $levelClass = ucfirst(strtolower($level));
        $className = "App\\Grading\\{$providerClass}{$levelClass}Grader";

        if (class_exists($className)) {
            $grader = new $className;
            if ($grader instanceof GraderInterface) {
                return $grader;
            }
        }

        return null;
    }
}
