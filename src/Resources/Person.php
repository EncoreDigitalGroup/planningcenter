<?php

namespace EncoreDigitalGroup\PlanningCenter\Resources;

use Carbon\CarbonImmutable;
use EncoreDigitalGroup\PlanningCenter\Support\Paginator;
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
use InvalidArgumentException;

/** @phpstan-consistent-constructor */
class Person
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

    public const string ENDPOINT = "/people/v2/people";

    protected string $endpoint = self::ENDPOINT;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->attributes = new Collection;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->setApiVersion(PlanningCenterApiVersion::PEOPLE_DEFAULT);
    }

    // Setters
    public function withId(string $value): self
    {
        return $this->setAttribute("id", $value);
    }

    public function withGivenName(?string $value): self
    {
        return $this->setAttribute("given_name", $value);
    }

    public function withFirstName(?string $value): self
    {
        return $this->setAttribute("first_name", $value);
    }

    public function withNickname(?string $value): self
    {
        return $this->setAttribute("nickname", $value);
    }

    public function withMiddleName(?string $value): self
    {
        return $this->setAttribute("middle_name", $value);
    }

    public function withLastName(?string $value): self
    {
        return $this->setAttribute("last_name", $value);
    }

    public function withBirthdate(?CarbonImmutable $value): self
    {
        return $this->setAttribute("birthdate", $value);
    }

    public function withAnniversary(?CarbonImmutable $value): self
    {
        return $this->setAttribute("anniversary", $value);
    }

    public function withGender(?string $value): self
    {
        return $this->setAttribute("gender", $value);
    }

    public function withGrade(?int $value): self
    {
        return $this->setAttribute("grade", $value);
    }

    public function withChild(?bool $value): self
    {
        return $this->setAttribute("child", $value);
    }

    public function withGraduationYear(?int $value): self
    {
        return $this->setAttribute("graduation_year", $value);
    }

    public function withSiteAdministrator(?bool $value): self
    {
        return $this->setAttribute("site_administrator", $value);
    }

    public function withAccountingAdministrator(?bool $value): self
    {
        return $this->setAttribute("accounting_administrator", $value);
    }

    public function withPeoplePermissions(?string $value): self
    {
        return $this->setAttribute("people_permissions", $value);
    }

    public function withMembership(?string $value): self
    {
        return $this->setAttribute("membership", $value);
    }

    public function withInactivatedAt(?CarbonImmutable $value): self
    {
        return $this->setAttribute("inactivated_at", $value);
    }

    public function withMedicalNotes(?string $value): self
    {
        return $this->setAttribute("medical_notes", $value);
    }

    public function withMfaConfigured(?bool $value): self
    {
        return $this->setAttribute("mfa_configured", $value);
    }

    public function withCreatedAt(?CarbonImmutable $value): self
    {
        return $this->setAttribute("created_at", $value);
    }

    public function withUpdatedAt(?CarbonImmutable $value): self
    {
        return $this->setAttribute("updated_at", $value);
    }

    public function withAvatar(?string $value): self
    {
        return $this->setAttribute("avatar", $value);
    }

    public function withName(?string $value): self
    {
        return $this->setAttribute("name", $value);
    }

    public function withDemographicAvatarUrl(?string $value): self
    {
        return $this->setAttribute("demographic_avatar_url", $value);
    }

    public function withDirectoryStatus(?string $value): self
    {
        return $this->setAttribute("directory_status", $value);
    }

    public function withPassedBackgroundCheck(?bool $value): self
    {
        return $this->setAttribute("passed_background_check", $value);
    }

    public function withCanCreateForms(?bool $value): self
    {
        return $this->setAttribute("can_create_forms", $value);
    }

    public function withCanEmailLists(?bool $value): self
    {
        return $this->setAttribute("can_email_lists", $value);
    }

    public function withSchoolType(?string $value): self
    {
        return $this->setAttribute("school_type", $value);
    }

    public function withStatus(?string $value): self
    {
        return $this->setAttribute("status", $value);
    }

    public function withPrimaryCampusId(?int $value): self
    {
        return $this->setAttribute("primary_campus_id", $value);
    }

    public function withRemoteId(?int $value): self
    {
        return $this->setAttribute("remote_id", $value);
    }

    // Getters
    public function id(): ?string
    {
        return $this->getAttribute("id");
    }

    public function givenName(): ?string
    {
        return $this->getAttribute("given_name");
    }

    public function firstName(): ?string
    {
        return $this->getAttribute("first_name");
    }

    public function nickname(): ?string
    {
        return $this->getAttribute("nickname");
    }

    public function middleName(): ?string
    {
        return $this->getAttribute("middle_name");
    }

    public function lastName(): ?string
    {
        return $this->getAttribute("last_name");
    }

    public function birthdate(): ?CarbonImmutable
    {
        return $this->getAttribute("birthdate");
    }

    public function anniversary(): ?CarbonImmutable
    {
        return $this->getAttribute("anniversary");
    }

    public function gender(): ?string
    {
        return $this->getAttribute("gender");
    }

    public function grade(): ?int
    {
        return $this->getAttribute("grade");
    }

    public function child(): ?bool
    {
        return $this->getAttribute("child");
    }

    public function graduationYear(): ?int
    {
        return $this->getAttribute("graduation_year");
    }

    public function siteAdministrator(): ?bool
    {
        return $this->getAttribute("site_administrator");
    }

    public function accountingAdministrator(): ?bool
    {
        return $this->getAttribute("accounting_administrator");
    }

    public function peoplePermissions(): ?string
    {
        return $this->getAttribute("people_permissions");
    }

    public function membership(): ?string
    {
        return $this->getAttribute("membership");
    }

    public function inactivatedAt(): ?CarbonImmutable
    {
        return $this->getAttribute("inactivated_at");
    }

    public function medicalNotes(): ?string
    {
        return $this->getAttribute("medical_notes");
    }

    public function mfaConfigured(): ?bool
    {
        return $this->getAttribute("mfa_configured");
    }

    public function createdAt(): ?CarbonImmutable
    {
        return $this->getAttribute("created_at");
    }

    public function updatedAt(): ?CarbonImmutable
    {
        return $this->getAttribute("updated_at");
    }

    public function avatar(): ?string
    {
        return $this->getAttribute("avatar");
    }

    public function name(): ?string
    {
        return $this->getAttribute("name");
    }

    public function demographicAvatarUrl(): ?string
    {
        return $this->getAttribute("demographic_avatar_url");
    }

    public function directoryStatus(): ?string
    {
        return $this->getAttribute("directory_status");
    }

    public function passedBackgroundCheck(): ?bool
    {
        return $this->getAttribute("passed_background_check");
    }

    public function canCreateForms(): ?bool
    {
        return $this->getAttribute("can_create_forms");
    }

    public function canEmailLists(): ?bool
    {
        return $this->getAttribute("can_email_lists");
    }

    public function schoolType(): ?string
    {
        return $this->getAttribute("school_type");
    }

    public function status(): ?string
    {
        return $this->getAttribute("status");
    }

    public function primaryCampusId(): ?int
    {
        return $this->getAttribute("primary_campus_id");
    }

    public function remoteId(): ?int
    {
        return $this->getAttribute("remote_id");
    }

    public function email(): Email
    {
        $personId = $this->id();
        if ($personId === null) {
            throw new InvalidArgumentException("Cannot create an email for a person without an ID.");
        }

        $email = new Email($this->clientId, $this->clientSecret, $personId);
        $email->setAuthType($this->authType);

        return $email;
    }

    /**
     * Get emails for this person
     *
     * @param  array<string, mixed>  $query  Optional query parameters
     */
    public function emails(array $query = []): Paginator
    {
        $personId = $this->id();
        if ($personId === null) {
            throw new InvalidArgumentException("Cannot fetch emails for a person without an ID.");
        }

        $emailInstance = new Email($this->clientId, $this->clientSecret);
        $emailInstance->setAuthType($this->authType);

        $response = $emailInstance->client()->get(
            $emailInstance->hostname() . "/people/v2/people/{$personId}/emails",
            $this->mergeQueryParameters($query)
        );
        $response->throw();

        return $emailInstance->buildPaginatorFromResponse($response);
    }

    protected function dateAttributes(): array
    {
        return [
            "created_at",
            "updated_at",
            "inactivated_at",
        ];
    }

    protected function dateOnlyAttributes(): array
    {
        return [
            "birthdate",
            "anniversary",
        ];
    }

    protected function readOnlyAttributes(): array
    {
        return [
            "id",
            "created_at",
            "updated_at",
            "name",
            "demographic_avatar_url",
        ];
    }
}
