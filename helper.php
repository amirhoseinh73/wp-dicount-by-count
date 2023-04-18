<?php

if (!function_exists("exists")) {
  function exists($item)
  {
    return (isset($item) && !empty($item));
  }
}
