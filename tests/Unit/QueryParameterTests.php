<?php

namespace Tests\Unit;

use EncoreDigitalGroup\PlanningCenter\PlanningCenter;
use EncoreDigitalGroup\PlanningCenter\Resources\Person;
use EncoreDigitalGroup\PlanningCenter\Support\Paginator;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use PHPGenesis\Http\HttpClient;
use Tests\Helpers\TestConstants;

describe("Query Parameter Tests", function (): void {
    beforeEach(function (): void {
        // Mock responses for all endpoints
        HttpClient::fake([
            "*" => HttpClient::response([
                "data" => [
                    [
                        "type" => "Person",
                        "id" => "1",
                        "attributes" => [
                            "first_name" => "John",
                            "last_name" => "Smith",
                        ],
                    ],
                ],
                "meta" => [
                    "total_count" => 1,
                    "per_page" => 25,
                ],
                "links" => [
                    "next" => null,
                    "prev" => null,
                ],
            ]),
        ]);
    });

    test("Query Parameters: Can use withInclude on get() method", function (): void {
        PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->withInclude("emails", "addresses")
            ->get();

        HttpClient::assertSent(function (Request $request): bool {
            return $request->url() === "https://api.planningcenteronline.com/people/v2/people/1?include=emails%2Caddresses";
        });
    })->group("query-parameters");

    test("Query Parameters: Can use withFilter on get() method", function (): void {
        PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->withFilter("status", "active")
            ->get();

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["where"]["status"]) && $params["where"]["status"] === "active";
        });
    })->group("query-parameters");

    test("Query Parameters: Can use withOrder on get() method", function (): void {
        PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->withOrder("created_at")
            ->get();

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["order"]) && $params["order"] === "created_at";
        });
    })->group("query-parameters");

    test("Query Parameters: Can use withPerPage on get() method", function (): void {
        PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->withPerPage(100)
            ->get();

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["per_page"]) && $params["per_page"] === "100";
        });
    })->group("query-parameters");

    test("Query Parameters: Can use withOffset on get() method", function (): void {
        PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->withOffset(50)
            ->get();

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["offset"]) && $params["offset"] === "50";
        });
    })->group("query-parameters");

    test("Query Parameters: Can chain multiple query parameters", function (): void {
        PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->withInclude("emails")
            ->withFilter("status", "active")
            ->withOrder("-created_at")
            ->withPerPage(50)
            ->withOffset(10)
            ->get();

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["include"])
                && $params["include"] === "emails"
                && isset($params["where"]["status"])
                && $params["where"]["status"] === "active"
                && isset($params["order"])
                && $params["order"] === "-created_at"
                && isset($params["per_page"])
                && $params["per_page"] === "50"
                && isset($params["offset"])
                && $params["offset"] === "10";
        });
    })->group("query-parameters");

    test("Query Parameters: Can pass direct array to get() method", function (): void {
        PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->get(["include" => "emails", "per_page" => 100]);

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["include"])
                && $params["include"] === "emails"
                && isset($params["per_page"])
                && $params["per_page"] === "100";
        });
    })->group("query-parameters");

    test("Query Parameters: Direct array overrides fluent parameters", function (): void {
        PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->withPerPage(50)
            ->get(["per_page" => 100]); // Should override 50

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["per_page"]) && $params["per_page"] === "100";
        });
    })->group("query-parameters");

    test("Query Parameters: Can use on all() method", function (): void {
        PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->all([
                "include" => "emails",
                "where" => ["status" => "active"],
                "per_page" => 100,
            ]);

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["include"])
                && $params["include"] === "emails"
                && isset($params["where"]["status"])
                && $params["where"]["status"] === "active"
                && isset($params["per_page"])
                && $params["per_page"] === "100";
        });
    })->group("query-parameters");

    test("Query Parameters: Can use multiple includes", function (): void {
        PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->withInclude("emails")
            ->withInclude("addresses")
            ->withInclude("phone_numbers")
            ->get();

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["include"])
                && str_contains($params["include"], "emails")
                && str_contains($params["include"], "addresses")
                && str_contains($params["include"], "phone_numbers");
        });
    })->group("query-parameters");

    test("Query Parameters: Can use multiple filters", function (): void {
        PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->withFilter("status", "active")
            ->withFilter("membership", "member")
            ->get();

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["where"]["status"])
                && $params["where"]["status"] === "active"
                && isset($params["where"]["membership"])
                && $params["where"]["membership"] === "member";
        });
    })->group("query-parameters");

    test("Query Parameters: Can clear query parameters", function (): void {
        $person = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1")
            ->withInclude("emails")
            ->withPerPage(100)
            ->clearQueryParameters();

        $person->get();

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);

            return $query === null || $query === "";
        });
    })->group("query-parameters");
});

describe("Relationship Query Parameter Tests", function (): void {
    beforeEach(function (): void {
        // Mock responses for relationship endpoints
        HttpClient::fake([
            "https://api.planningcenteronline.com/people/v2/people/1/emails?fail=1" => HttpClient::response([], 500),
            "*" => HttpClient::response([
                "data" => [
                    [
                        "type" => "Email",
                        "id" => "1",
                        "attributes" => [
                            "address" => "test@example.com",
                        ],
                    ],
                ],
                "meta" => [
                    "total_count" => 1,
                    "per_page" => 25,
                ],
                "links" => [
                    "next" => null,
                    "prev" => null,
                ],
            ]),
        ]);
    });

    test("Query Parameters: Can use on relationship methods with direct array", function (): void {
        $person = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1");

        // Set the person's ID
        $person->setAttribute("id", "1");

        $emails = $person->emails(["per_page" => 100, "order" => "created_at"]);

        expect($emails)->toBeInstanceOf(Paginator::class);

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return str_contains($request->url(), "/people/1/emails")
                && isset($params["per_page"])
                && $params["per_page"] === "100"
                && isset($params["order"])
                && $params["order"] === "created_at";
        });
    })->group("query-parameters.relationships");

    test("Query Parameters: Throws When Relationship List Fails", function (): void {
        $person = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1");

        expect(fn (): Paginator => $person->emails(["fail" => 1]))->toThrow(RequestException::class);
    })->group("query-parameters.relationships");

    test("Query Parameters: Sends Each Relationship Query", function (): void {
        $person = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->people()
            ->person()
            ->withId("1");

        $person->emails(["per_page" => 100]);
        $person->emails(["per_page" => 25]);

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["per_page"]) && $params["per_page"] === "100";
        });

        HttpClient::assertSent(function (Request $request): bool {
            $query = parse_url($request->url(), PHP_URL_QUERY);
            parse_str($query, $params);

            return isset($params["per_page"]) && $params["per_page"] === "25";
        });
    })->group("query-parameters.relationships");
});
