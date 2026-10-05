<?php

namespace EncoreDigitalGroup\PlanningCenter\Support\Traits;

trait HasCreate
{
    /**
     * Create a new resource
     *
     * Sends the resource attributes to the API to create a new record.
     */
    public function create(): self
    {
        $response = $this->client()->post(
            $this->hostname() . $this->endpoint,
            $this->mapToPco()
        );

        $this->setResponse($response);
        $response->throw();
        $this->hydrateFromResponse($response);

        return $this;
    }
}
