<?php

/*
 * Encore Digital Group - Planning Center PHP SDK
 * Copyright (c) 2024. Encore Digital Group
 */

namespace Tests\Unit\Calendar;

use EncoreDigitalGroup\PlanningCenter\Resources\CalendarEvent as Event;
use EncoreDigitalGroup\PlanningCenter\Resources\CalendarTagGroup as TagGroup;
use EncoreDigitalGroup\PlanningCenter\Resources\EventInstance;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasClient;
use PHPGenesis\Http\HttpClient;
use Tests\Helpers\BaseMock;
use Tests\Helpers\ObjectType;

class CalendarMocks extends BaseMock
{
    use HasClient;

    /** @var array<string, mixed>|null */
    private static ?array $eventInstanceCollectionResponse = null;

    public const string EVENT_ID = "1";
    public const string EVENT_NAME = "Sample Event";
    public const string EVENT_INSTANCE_ID = "1";
    public const string TAG_GROUP_ID = "1";
    public const string TAG_NAME = "Demo Tag";
    public const string TAG_ID = "1";

    public static function setup(): void
    {
        self::$eventInstanceCollectionResponse = null;

        self::useEventCollection();
        self::useSpecificEvent();
        self::useEventInstanceCollection();
        self::useEventInstanceRelationshipCollection();
        self::useSpecificEventInstance();
        self::useTagGroupCollection();
        self::useSpecificTagGroup();
        self::useEventTagRelationshipCollection();
    }

    public static function useEventCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Event::ENDPOINT => function ($request) {
                return match ($request->method()) {
                    "POST" => HttpClient::response(self::useSingleResponse(ObjectType::Event)),
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::Event)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    public static function useSpecificEvent(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Event::ENDPOINT . "/1" => function ($request) {
                return match ($request->method()) {
                    "PUT", "PATCH", "GET" => HttpClient::response(self::useSingleResponse(ObjectType::Event)),
                    "DELETE" => HttpClient::response(self::deleteResponse()),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    public static function useEventInstanceCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . EventInstance::ENDPOINT => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::$eventInstanceCollectionResponse ?? self::useCollectionResponse(ObjectType::EventInstance)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    public static function useEventInstanceRelationshipCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Event::ENDPOINT . "/1/event_instances" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::EventInstance)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    public static function useSpecificEventInstance(): void
    {
        HttpClient::fake([
            self::HOSTNAME . EventInstance::ENDPOINT . "/1" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useSingleResponse(ObjectType::EventInstance)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    public static function useNullEventInstance(): void
    {
        HttpClient::fake([
            self::HOSTNAME . EventInstance::ENDPOINT . "/999" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(["data" => null], 200),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    public static function useEventInstanceCollectionWithNulls(): void
    {
        self::$eventInstanceCollectionResponse = [
            "data" => [
                self::eventInstance(),
                null,
                self::eventInstance(),
            ],
            "meta" => ["total_count" => 2, "per_page" => 25],
        ];
    }

    public static function useTagGroupCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . TagGroup::ENDPOINT => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::TagGroup)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    public static function useSpecificTagGroup(): void
    {
        HttpClient::fake([
            self::HOSTNAME . TagGroup::ENDPOINT . "/1" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useSingleResponse(ObjectType::TagGroup)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);

        HttpClient::fake([
            self::HOSTNAME . TagGroup::ENDPOINT . "/1/tags" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::Tag)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    public static function useEventTagRelationshipCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Event::ENDPOINT . "/1/tags" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::Tag)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function event(): array
    {
        return [
            "type" => "Event",
            "id" => "1",
            "attributes" => [
                "approval_status" => "string",
                "created_at" => "2000-01-01T12:00:00Z",
                "description" => "string",
                "featured" => true,
                "image_url" => "string",
                "name" => self::EVENT_NAME,
                "percent_approved" => 1,
                "percent_rejected" => 1,
                "registration_url" => "string",
                "summary" => "string",
                "updated_at" => "2000-01-01T12:00:00Z",
                "visible_in_church_center" => true,
            ],
            "relationships" => [
                "owner" => [
                    "data" => [
                        "type" => "Person",
                        "id" => "1",
                    ],
                ],
            ],
        ];
    }

    protected static function eventInstance(): array
    {
        return [
            "type" => "EventInstance",
            "id" => "1",
            "attributes" => [
                "all_day_event" => true,
                "compact_recurrence_description" => "string",
                "created_at" => "2000-01-01T12:00:00Z",
                "ends_at" => "2000-01-01T12:00:00Z",
                "location" => "string",
                "recurrence" => "string",
                "recurrence_description" => "string",
                "starts_at" => "2000-01-01T12:00:00Z",
                "updated_at" => "2000-01-01T12:00:00Z",
                "church_center_url" => "string",
                "published_starts_at" => "string",
                "published_ends_at" => "string",
            ],
            "relationships" => [
                "event" => [
                    "data" => [
                        "type" => "Event",
                        "id" => "1",
                    ],
                ],
            ],
        ];
    }

    protected static function tagGroup(): array
    {
        return [
            "type" => "TagGroup",
            "id" => "1",
            "attributes" => [
                "created_at" => "2000-01-01T12:00:00Z",
                "name" => "Demo Tag Group",
                "updated_at" => "2000-01-01T12:00:00Z",
                "required" => true,
            ],
            "relationships" => [],
        ];
    }

    protected static function tag(): array
    {
        return [
            "type" => "Tag",
            "id" => self::TAG_ID,
            "attributes" => [
                "church_center_category" => true,
                "color" => "string",
                "created_at" => "2000-01-01T12:00:00Z",
                "name" => self::TAG_NAME,
                "position" => 142,
                "updated_at" => "2000-01-01T12:00:00Z",
            ],
            "relationships" => [],
        ];
    }
}
