<?php

namespace Paynl\LaravelCashier\Concerns;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait ManagesCustomer
{
    public function payName(): ?string
    {
        return $this->name ?? null;
    }

    public function payEmail(): ?string
    {
        return $this->email ?? null;
    }

    public function payPhone(): ?string
    {
        return $this->phone ?? null;
    }

    public function payReference(): ?string
    {
        if ($this->getKey() === null) {
            return null;
        }

        return strtolower(class_basename($this)).$this->getKey();
    }
}
