<?php

namespace RyanLHolt\Infused;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RyanLHolt\Infused\Infused
 */
class InfusedFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'infused';
    }
}
