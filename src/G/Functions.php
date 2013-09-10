<?php
use G\DisposableInterface;

/**
 * @param G\DisposableInterface $input1,...,$inputN
 * @param callable $callback
 * @throws Exception
 */
function using(/* $input1, $input2, ... $inputN, $callback */)
{
    $params = func_get_args();

    if (count($params) < 2)
        throw new Exception('using() requires at least 2 parameters');

    $callback = array_pop($params);

    if (!is_callable($callback))
        throw new Exception('using() requires the last parameter to be a callable');

    try {
        call_user_func_array($callback, $params);
    } catch (Exception $ex) {
      throw $ex;
    } finally {
        foreach ($params as $p) {
            if ($p instanceof DisposableInterface)
                $p->dispose();
        }
    }
}
