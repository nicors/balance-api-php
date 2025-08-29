<?php

namespace Core\Domain\Entity\Traits;

trait MagicMethodsTrait
{
  public function __get($property)
  {
    if (property_exists($this, $property)) {
      return $this->$property;
    }

    $className = get_class($this);

    throw new \Exception("Property {$property} does not exist in class {$className}.");
  }
}
