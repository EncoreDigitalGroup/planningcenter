<?php

namespace EncoreDigitalGroup\PlanningCenter\Support\Traits;

use EncoreDigitalGroup\PlanningCenter\Support\AuthType;
use EncoreDigitalGroup\PlanningCenter\Support\Paginator;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

trait HasApiMethods
{
    /**
     * List all resources with pagination
     *
     * @return Paginator<static>
     */
    public static function all(
        string $clientId,
        string $clientSecret,
        array $query = [],
        AuthType $authType = AuthType::Basic
    ): Paginator {
        $instance = new static($clientId, $clientSecret);
        $instance->setAuthType($authType);

        $response = $instance->client()->get(
            $instance->hostname() . $instance->endpoint,
            $query
        );
        $response->throw();

        return $instance->buildPaginatorFromResponse($response);
    }

    /**
     * Create and hydrate a new instance from array data
     *
     * @param  array<string, mixed>  $data
     */
    protected static function createFromArray(array $data, string $clientId, string $clientSecret, AuthType $authType = AuthType::Basic): static
    {
        $instance = new static($clientId, $clientSecret);
        $instance->setAuthType($authType);
        $instance->hydrateFromArray($data);

        return $instance;
    }

    /**
     * Build a Paginator from an API response
     *
     * @return Paginator<static>
     *
     * @internal This method is for internal SDK use only.
     */
    public function buildPaginatorFromResponse(Response $response): Paginator
    {
        /** @var array<int, array<string, mixed>|null>|null $jsonData */
        $jsonData = $response->json("data");

        /** @var Collection<int, array<string, mixed>> $filtered */
        $filtered = collect($jsonData)->filter(fn ($item): bool => is_array($item));

        /** @var Collection<int, static> $data */
        $data = $filtered->map(
            fn (array $item): static => static::createFromArray($item, $this->clientId, $this->clientSecret, $this->authType)
        );

        $meta = $response->json("meta");

        return new Paginator(
            response: $response,
            data: $data,
            nextUrl: $response->json("links.next"),
            prevUrl: $response->json("links.prev"),
            totalCount: $meta["total_count"] ?? 0,
            perPage: $meta["per_page"] ?? 25
        );
    }
}
