<?php

declare(strict_types=1);

namespace App\Modules\Shared\Concerns;

trait HasRouteParamValidationTrait
{
    private bool $captured_route_vars = false;

    final public function all($keys = null)
    {
        return $this->captureRouteVars(parent::all());
    }

    private function captureRouteVars(array $inputs): array
    {
        if ($this->captured_route_vars) {
            return $inputs;
        }

        $inputs += $this->route()->parameters();
        $inputs = self::numbers($inputs);

        $this->replace($inputs);
        $this->captured_route_vars = true;

        return $inputs;
    }

    private static function numbers(array $inputs): array
    {
        foreach ($inputs as $k => $input) {
            if (is_numeric($input) && !is_infinite($input * 1)) {
                $inputs[$k] *= 1;
            }
        }

        return $inputs;
    }
}
