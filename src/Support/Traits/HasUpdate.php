<?php

namespace EncoreDigitalGroup\PlanningCenter\Support\Traits;

use InvalidArgumentException;

trait HasUpdate
{
    /**
     * Update an existing resource
     *
     * Requires the resource to have an ID set before calling.
     */
    public function update(): self
    {
        if (!$this->getAttribute("id")) {
            throw new InvalidArgumentException("Cannot update resource without an ID.");
        }

        $response = $this->client()->patch(
            $this->hostname() . $this->endpoint . "/" . $this->getAttribute("id"),
            $this->mapToPco()
        );

        $this->setResponse($response);
        $response->throw();
        $this->hydrateFromResponse($response);

        return $this;
    }
}
