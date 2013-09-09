<?php

use G\IDisposable;

/**
 * @param G\IDisposable|G\IDisposable[] $inputs
 * @param callable $callback
 * @throws Exception
 */
function using($inputs, callable $callback=null)
{
    if (!is_array($inputs)) {
        $inputs = [$inputs];
    }

    $disponser = function($inputs) {
        foreach ($inputs as $input) {
            if ($input instanceof IDisposable) {
                $input->dispose();
            }
        }
    };

    try {
        call_user_func_array($callback, $inputs);
        $disponser($inputs);
    } catch (\Exception $e) {
        $disponser($inputs);

        throw $e;
    }
}