<?php

namespace EncoreDigitalGroup\PlanningCenter\Resources;

use Carbon\CarbonImmutable;
use EncoreDigitalGroup\PlanningCenter\Support\PlanningCenterApiVersion;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasApiMethods;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasAttributes;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasClient;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasCreate;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasDelete;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasQueryParameters;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasRead;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasResponse;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasSave;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasUpdate;
use Illuminate\Support\Collection;

/** @phpstan-consistent-constructor */
class Email
{
    use HasApiMethods;
    use HasAttributes;
    use HasClient;
    use HasCreate;
    use HasDelete;
    use HasQueryParameters;
    use HasRead;
    use HasResponse;
    use HasSave;
    use HasUpdate;

    public const string ENDPOINT = "/people/v2/emails";

    protected string $endpoint = self::ENDPOINT;

    public function __construct(string $clientId, string $clientSecret, ?string $personId = null)
    {
        $this->attributes = new Collection;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->endpoint = $personId === null
            ? self::ENDPOINT
            : "/people/v2/people/{$personId}/emails";
        $this->setApiVersion(PlanningCenterApiVersion::PEOPLE_DEFAULT);
    }

    // Setters
    public function withId(string $value): self
    {
        return $this->setAttribute("id", $value);
    }

    public function withAddress(?string $value): self
    {
        return $this->setAttribute("address", $value);
    }

    public function withLocation(?string $value): self
    {
        return $this->setAttribute("location", $value);
    }

    public function withPrimary(?bool $value): self
    {
        return $this->setAttribute("primary", $value);
    }

    // Getters
    public function id(): ?string
    {
        return $this->getAttribute("id");
    }

    public function address(): ?string
    {
        return $this->getAttribute("address");
    }

    public function location(): ?string
    {
        return $this->getAttribute("location");
    }

    public function primary(): ?bool
    {
        return $this->getAttribute("primary");
    }

    public function blocked(): mixed
    {
        return $this->getAttribute("blocked");
    }

    public function createdAt(): ?CarbonImmutable
    {
        return $this->getAttribute("created_at");
    }

    public function updatedAt(): ?CarbonImmutable
    {
        return $this->getAttribute("updated_at");
    }

    protected function dateAttributes(): array
    {
        return [
            "created_at",
            "updated_at",
        ];
    }

    protected function readOnlyAttributes(): array
    {
        return [
            "id",
            "created_at",
            "updated_at",
            "blocked",
        ];
    }
}
