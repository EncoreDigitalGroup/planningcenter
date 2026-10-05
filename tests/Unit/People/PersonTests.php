<?php

namespace Tests\Unit\People;

use EncoreDigitalGroup\PlanningCenter\PlanningCenter;
use EncoreDigitalGroup\PlanningCenter\Resources\Person;
use EncoreDigitalGroup\PlanningCenter\Support\Paginator;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Tests\Helpers\TestConstants;

describe("People Profile Tests", function (): void {
    test("People: Can Create Person", function (): void {
        $person = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withFirstName("John")
            ->withLastName("Smith")
            ->save();

        expect($person)->toBeInstanceOf(Person::class)
            ->and($person->firstName())->toBe(PeopleMocks::FIRST_NAME)
            ->and($person->lastName())->toBe(PeopleMocks::LAST_NAME)
            ->and($person->id())->not()->toBeNull();
    });

    test("People: Throws When Creating Person Fails", function (): void {
        PeopleMocks::useFailedProfileCollection("POST", 422);
        $person = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withFirstName("John")
            ->withLastName("Smith");

        expect(fn (): Person => $person->save())->toThrow(RequestException::class);

        expect($person->response()?->status())->toBe(422)
            ->and($person->id())->toBeNull();
    });

    test("People: Can List All", function (): void {
        $paginator = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->all();

        expect($paginator)->toBeInstanceOf(Paginator::class)
            ->and($paginator->items())->toBeInstanceOf(Collection::class)
            ->and($paginator->items()->count())->toBe(1);
    });

    test("People: Throws When Listing All Fails", function (): void {
        PeopleMocks::useFailedProfileCollection("GET", 500);

        expect(fn (): Paginator => PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->all())->toThrow(RequestException::class);
    });

    test("People: Can Get Person By ID", function (): void {
        $person = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->get();

        expect($person)->toBeInstanceOf(Person::class)
            ->and($person->firstName())->toBe(PeopleMocks::FIRST_NAME)
            ->and($person->lastName())->toBe(PeopleMocks::LAST_NAME)
            ->and($person->id())->toBe(PeopleMocks::PERSON_ID);
    });

    test("People: Can Update Person Profile", function (): void {
        $person = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->withFirstName("Jane")
            ->save();

        expect($person)->toBeInstanceOf(Person::class)
            ->and($person->id())->toBe(PeopleMocks::PERSON_ID);
    });

    test("People: Can Delete Person Profile", function (): void {
        $person = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1");

        $result = $person->delete();

        expect($result)->toBeTrue();
    });
})->group("people.profile");
