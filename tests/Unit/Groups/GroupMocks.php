<?php

/*
 * Encore Digital Group - Planning Center PHP SDK
 * Copyright (c) 2024. Encore Digital Group
 */

namespace Tests\Unit\Groups;

use EncoreDigitalGroup\PlanningCenter\Resources\Group;
use EncoreDigitalGroup\PlanningCenter\Resources\GroupEnrollment;
use EncoreDigitalGroup\PlanningCenter\Resources\GroupEvent;
use EncoreDigitalGroup\PlanningCenter\Resources\GroupTagGroup as TagGroup;
use EncoreDigitalGroup\PlanningCenter\Support\Traits\HasClient;
use PHPGenesis\Http\HttpClient;
use Tests\Helpers\BaseMock;
use Tests\Helpers\ObjectType;
use Tests\Unit\People\PeopleMocks;

class GroupMocks extends BaseMock
{
    use HasClient;

    public const string GROUP_ID = "1";
    public const string GROUP_NAME = "Demo Group";
    public const string GROUP_HEADER_IMAGE_URL = "https://example.com/header.jpg";
    public const string MEMBERSHIP_ID = "1";
    public const string MEMBERSHIP_ROLE = "leader";
    public const string MEMBER_PROFILE_ID = "1";
    public const string MEMBER_FIRST_NAME = "John";
    public const string MEMBER_LAST_NAME = "Smith";
    public const string TAG_ID = "1";
    public const string TAG_NAME = "Demo Tag";
    public const int TAG_POSITION = 1;
    public const string TAG_GROUP_ID = "1";
    public const string TAG_GROUP_NAME = "Demo Tag Group";
    public const int ENROLLMENT_MEMBER_LIMIT = 1;
    public const bool ENROLLMENT_MEMBER_LIMIT_REACHED = true;
    public const string GROUP_EVENT_ID = "1";
    public const string GROUP_EVENT_NAME = "Demo Group Event";
    public const string GROUP_ENROLLMENT_ID = "1";

    public static function setup(): void
    {
        self::useGroupCollection();
        self::useSpecificGroup();
        self::useMembershipCollection();
        self::useGroupMemberPersonCollection();
        self::useGroupTagRelationshipCollection();
        self::useEnrollmentCollection();
        self::useTagGroupCollection();
        self::useSpecificTagGroup();
        self::useTagGroupTagsCollection();
        self::useGroupEventCollection();
        self::useSpecificGroupEvent();
        self::useGroupEnrollmentCollection();
        self::useSpecificGroupEnrollment();
    }

    public static function useGroupEventWithMixedCaseRelationship(): void
    {
        HttpClient::fake([
            self::HOSTNAME . GroupEvent::ENDPOINT . "/99" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response([
                        "data" => [[
                            "type" => "Event",
                            "id" => "99",
                            "attributes" => ["name" => "Test Event"],
                            "relationships" => [
                                "Group" => [
                                    "data" => ["type" => "Group", "id" => self::GROUP_ID],
                                ],
                            ],
                        ]],
                    ]),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useGroupCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Group::ENDPOINT => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::Group)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);

        HttpClient::fake([
            self::HOSTNAME . Group::ENDPOINT . "?filter=my_groups" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::Group)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useSpecificGroup(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Group::ENDPOINT . "/1" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useSingleResponse(ObjectType::Group)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useMembershipCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Group::ENDPOINT . "/1/memberships" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::GroupMembership)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useGroupMemberPersonCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Group::ENDPOINT . "/1/people" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::GroupMembers)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useGroupTagRelationshipCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Group::ENDPOINT . "/1/tags" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::Tag)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useEnrollmentCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . Group::ENDPOINT . "/1/enrollment" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::Enrollment)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useTagGroupCollection(): void
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

    protected static function useSpecificTagGroup(): void
    {
        HttpClient::fake([
            self::HOSTNAME . TagGroup::ENDPOINT . "/1" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useSingleResponse(ObjectType::TagGroup)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useTagGroupTagsCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . TagGroup::ENDPOINT . "/1/tags" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::Tag)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useGroupEventCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . GroupEvent::ENDPOINT => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::GroupEvent)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useSpecificGroupEvent(): void
    {
        HttpClient::fake([
            self::HOSTNAME . GroupEvent::ENDPOINT . "/1" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useSingleResponse(ObjectType::GroupEvent)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useGroupEnrollmentCollection(): void
    {
        HttpClient::fake([
            self::HOSTNAME . GroupEnrollment::ENDPOINT => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useCollectionResponse(ObjectType::GroupEnrollment)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function useSpecificGroupEnrollment(): void
    {
        HttpClient::fake([
            self::HOSTNAME . GroupEnrollment::ENDPOINT . "/1" => function ($request) {
                return match ($request->method()) {
                    "GET" => HttpClient::response(self::useSingleResponse(ObjectType::GroupEnrollment)),
                    default => HttpClient::response([], 405),
                };
            },
        ]);
    }

    protected static function group(): array
    {
        return [
            "type" => "Group",
            "id" => self::GROUP_ID,
            "attributes" => [
                "archived_at" => "2000-01-01T12:00:00Z",
                "can_create_conversation" => true,
                "contact_email" => "string",
                "created_at" => "2000-01-01T12:00:00Z",
                "description" => "string",
                "events_visibility" => "value",
                "header_image" => ["original" => self::GROUP_HEADER_IMAGE_URL],
                "leaders_can_search_people_database" => true,
                "location_type_preference" => "value",
                "memberships_count" => 1,
                "name" => self::GROUP_NAME,
                "public_church_center_web_url" => "string",
                "schedule" => "string",
                "virtual_location_url" => "string",
                "widget_status" => [],
            ],
            "relationships" => [
                "group_type" => [
                    "data" => [
                        "type" => "GroupType",
                        "id" => "1",
                    ],
                ],
                "location" => [
                    "data" => [
                        "type" => "Location",
                        "id" => "1",
                    ],
                ],
            ],
        ];
    }

    protected static function membership(): array
    {
        return [
            "type" => "Membership",
            "id" => self::MEMBERSHIP_ID,
            "attributes" => [
                "joined_at" => "2000-01-01T12:00:00Z",
                "role" => self::MEMBERSHIP_ROLE,
            ],
            "relationships" => [
                "group" => [
                    "data" => [
                        "type" => "Group",
                        "id" => self::GROUP_ID,
                    ],
                ],
                "person" => [
                    "data" => [
                        "type" => "Person",
                        "id" => PeopleMocks::PERSON_ID,
                    ],
                ],
            ],
        ];
    }

    protected static function people(): array
    {
        return [
            "type" => "Person",
            "id" => self::MEMBER_PROFILE_ID,
            "attributes" => [
                "addresses" => [],
                "avatar_url" => "string",
                "child" => true,
                "created_at" => "2000-01-01T12:00:00Z",
                "email_addresses" => [],
                "first_name" => self::MEMBER_FIRST_NAME,
                "last_name" => self::MEMBER_LAST_NAME,
                "permissions" => "string",
                "phone_numbers" => [],
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
                "name" => self::TAG_NAME,
                "position" => self::TAG_POSITION,
            ],
            "relationships" => [
                "tag_group" => [
                    "data" => [
                        "type" => "TagGroup",
                        "id" => "1",
                    ],
                ],
            ],
        ];
    }

    protected static function enrollment(): array
    {
        return [
            "type" => "Enrollment",
            "id" => "1",
            "attributes" => [
                "auto_closed" => true,
                "auto_closed_reason" => "string",
                "date_limit" => "string",
                "date_limit_reached" => true,
                "member_limit" => self::ENROLLMENT_MEMBER_LIMIT,
                "member_limit_reached" => self::ENROLLMENT_MEMBER_LIMIT_REACHED,
                "status" => "string",
                "strategy" => "string",
            ],
            "relationships" => [
                "group" => [
                    "data" => [
                        "type" => "Group",
                        "id" => self::GROUP_ID,
                    ],
                ],
            ],
        ];
    }

    protected static function tagGroup(): array
    {
        return [
            "type" => "TagGroup",
            "id" => self::TAG_GROUP_ID,
            "attributes" => [
                "display_publicly" => true,
                "multiple_options_enabled" => true,
                "name" => self::TAG_GROUP_NAME,
                "position" => 1,
            ],
            "relationships" => [],
        ];
    }

    protected static function groupEvent(): array
    {
        return [
            "type" => "Event",
            "id" => self::GROUP_EVENT_ID,
            "attributes" => [
                "attendance_requests_enabled" => true,
                "automated_reminder_enabled" => false,
                "canceled" => false,
                "canceled_at" => null,
                "description" => "string",
                "ends_at" => "2000-01-01T13:00:00Z",
                "location_type_preference" => "physical",
                "multi_day" => false,
                "name" => self::GROUP_EVENT_NAME,
                "reminders_sent" => false,
                "reminders_sent_at" => null,
                "repeating" => false,
                "starts_at" => "2000-01-01T12:00:00Z",
                "virtual_location_url" => null,
                "visitors_count" => 0,
            ],
            "relationships" => [
                "group" => [
                    "data" => [
                        "type" => "Group",
                        "id" => self::GROUP_ID,
                    ],
                ],
                "location" => [
                    "data" => null,
                ],
            ],
        ];
    }

    protected static function groupEnrollment(): array
    {
        return [
            "type" => "GroupEnrollment",
            "id" => self::GROUP_ENROLLMENT_ID,
            "attributes" => [
                "auto_closed" => false,
                "auto_closed_reason" => null,
                "date_limit" => null,
                "date_limit_reached" => false,
                "member_limit" => self::ENROLLMENT_MEMBER_LIMIT,
                "member_limit_reached" => self::ENROLLMENT_MEMBER_LIMIT_REACHED,
                "status" => "open",
                "strategy" => "request_to_join",
            ],
            "relationships" => [
                "group" => [
                    "data" => [
                        "type" => "Group",
                        "id" => self::GROUP_ID,
                    ],
                ],
            ],
        ];
    }
}
