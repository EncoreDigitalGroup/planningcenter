<?php

namespace Tests\Unit\People;

use EncoreDigitalGroup\PlanningCenter\PlanningCenter;
use EncoreDigitalGroup\PlanningCenter\Resources\Email;
use EncoreDigitalGroup\PlanningCenter\Support\Paginator;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use PHPGenesis\Http\HttpClient;
use Tests\Helpers\TestConstants;

describe("People Email Tests", function (): void {
    test("Email: Can List All Emails for Person", function (): void {
        $emailsPaginator = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId(PeopleMocks::PERSON_ID)
            ->emails();

        expect($emailsPaginator)->toBeInstanceOf(Paginator::class)
            ->and($emailsPaginator->items())->toBeInstanceOf(Collection::class)
            ->and($emailsPaginator->items()->count())->toBe(1);
    });

    test("Email: Can Get Email By ID", function (): void {
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->email()
            ->withId(PeopleMocks::EMAIL_ID)
            ->get();

        expect($email)->toBeInstanceOf(Email::class)
            ->and($email->address())->toBe(PeopleMocks::EMAIL_ADDRESS)
            ->and($email->id())->toBe(PeopleMocks::EMAIL_ID);
    });

    test("Email: Can Create Email for Person", function (): void {
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->email()
            ->withAddress("new@example.com")
            ->withLocation("home")
            ->withPrimary(true)
            ->save();

        expect($email)->toBeInstanceOf(Email::class)
            ->and($email->id())->not()->toBeNull();
    });

    test("Email: Can Create Email Through Saved Person", function (): void {
        $person = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withFirstName("John")
            ->withLastName("Smith")
            ->save();

        $email = $person
            ->email()
            ->withAddress("new@example.com")
            ->withLocation("home")
            ->withPrimary(true)
            ->save();

        expect($email)->toBeInstanceOf(Email::class)
            ->and($email->id())->toBe(PeopleMocks::EMAIL_ID);

        HttpClient::assertSent(function (Request $request): bool {
            return $request->method() === "POST"
                && $request->url() === "https://api.planningcenteronline.com/people/v2/people/1/emails";
        });
    });

    test("Email: Can Get Email Through Person", function (): void {
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId(PeopleMocks::PERSON_ID)
            ->email()
            ->withId(PeopleMocks::EMAIL_ID)
            ->get();

        expect($email)->toBeInstanceOf(Email::class)
            ->and($email->address())->toBe(PeopleMocks::EMAIL_ADDRESS);

        HttpClient::assertSent(function (Request $request): bool {
            return $request->method() === "GET"
                && $request->url() === "https://api.planningcenteronline.com/people/v2/people/1/emails/1";
        });
    });

    test("Email: Can Update Email Through Person", function (): void {
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId(PeopleMocks::PERSON_ID)
            ->email()
            ->withId(PeopleMocks::EMAIL_ID)
            ->withAddress("updated@example.com")
            ->save();

        expect($email->id())->toBe(PeopleMocks::EMAIL_ID);

        HttpClient::assertSent(function (Request $request): bool {
            return $request->method() === "PATCH"
                && $request->url() === "https://api.planningcenteronline.com/people/v2/people/1/emails/1";
        });
    });

    test("Email: Can Delete Email Through Person", function (): void {
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId(PeopleMocks::PERSON_ID)
            ->email()
            ->withId(PeopleMocks::EMAIL_ID);

        expect($email->delete())->toBeTrue();

        HttpClient::assertSent(function (Request $request): bool {
            return $request->method() === "DELETE"
                && $request->url() === "https://api.planningcenteronline.com/people/v2/people/1/emails/1";
        });
    });

    test("Email: Throws When Person Has No ID", function (): void {
        $person = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person();

        expect(fn (): Email => $person->email())
            ->toThrow(InvalidArgumentException::class);

        HttpClient::assertNothingSent();
    });

    test("Email: Throws When Creating Scoped Email Fails", function (): void {
        PeopleMocks::useFailedEmailCollection("POST", 422);
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId(PeopleMocks::PERSON_ID)
            ->email()
            ->withAddress("new@example.com");

        expect(fn (): Email => $email->save())->toThrow(RequestException::class);

        expect($email->response()?->status())->toBe(422)
            ->and($email->id())->toBeNull();
    });

    test("Email: Throws When Reading Scoped Email Fails", function (): void {
        PeopleMocks::useFailedSpecificEmail("GET", 404);
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId(PeopleMocks::PERSON_ID)
            ->email()
            ->withId(PeopleMocks::EMAIL_ID);

        expect(fn (): Email => $email->get())->toThrow(RequestException::class);

        expect($email->response()?->status())->toBe(404)
            ->and($email->address())->toBeNull();
    });

    test("Email: Throws When Updating Scoped Email Fails", function (): void {
        PeopleMocks::useFailedSpecificEmail("PATCH", 422);
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId(PeopleMocks::PERSON_ID)
            ->email()
            ->withId(PeopleMocks::EMAIL_ID)
            ->withAddress("updated@example.com");

        expect(fn (): Email => $email->save())->toThrow(RequestException::class);

        expect($email->response()?->status())->toBe(422)
            ->and($email->address())->toBe("updated@example.com");
    });

    test("Email: Throws When Creating Email Fails", function (): void {
        PeopleMocks::useFailedSpecificEmail("POST", 422);
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->email()
            ->withAddress("new@example.com")
            ->withLocation("home")
            ->withPrimary(true);

        expect(fn (): Email => $email->save())->toThrow(RequestException::class);

        expect($email->response()?->status())->toBe(422)
            ->and($email->id())->toBeNull();
    });

    test("Email: Throws When Reading Email Fails", function (): void {
        PeopleMocks::useFailedSpecificEmail("GET", 404);
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->email()
            ->withId(PeopleMocks::EMAIL_ID);

        expect(fn (): Email => $email->get())->toThrow(RequestException::class);

        expect($email->response()?->status())->toBe(404)
            ->and($email->address())->toBeNull();
    });

    test("Email: Can Update Email", function (): void {
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->email()
            ->withId(PeopleMocks::EMAIL_ID)
            ->withAddress("updated@example.com")
            ->save();

        expect($email)->toBeInstanceOf(Email::class)
            ->and($email->id())->toBe(PeopleMocks::EMAIL_ID);
    });

    test("Email: Throws When Updating Email Fails", function (): void {
        PeopleMocks::useFailedSpecificEmail("PATCH", 422);
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->email()
            ->withId(PeopleMocks::EMAIL_ID)
            ->withAddress("updated@example.com");

        expect(fn (): Email => $email->save())->toThrow(RequestException::class);

        expect($email->response()?->status())->toBe(422)
            ->and($email->address())->toBe("updated@example.com");
    });

    test("Email: Can Delete Email", function (): void {
        $email = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->email()
            ->withId(PeopleMocks::EMAIL_ID);

        $result = $email->delete();

        expect($result)->toBeTrue();
    });
})->group("people.email");
