<?php

/*
 * Encore Digital Group - Planning Center PHP SDK
 * Copyright (c) 2024. Encore Digital Group
 */

namespace EncoreDigitalGroup\PlanningCenter\Support;

enum PlanningCenterWebhookEvent: string
{
    case PeoplePersonCreated = "people.v2.events.person.created";
    case PeoplePersonUpdated = "people.v2.events.person.updated";
    case PeoplePersonMergerCreated = "people.v2.events.person_merger.created";
    case GroupsGroupCreated = "groups.v2.events.group.created";
    case GroupsGroupUpdated = "groups.v2.events.group.updated";
    case GroupsGroupDestroyed = "groups.v2.events.group.destroyed";
    case GroupsMembershipCreated = "groups.v2.events.membership.created";
    case GroupsMembershipUpdated = "groups.v2.events.membership.updated";
    case GroupsMembershipDestroyed = "groups.v2.events.membership.destroyed";

    public static function peopleEvents(): array
    {
        return [
            self::PeoplePersonCreated,
            self::PeoplePersonUpdated,
            self::PeoplePersonMergerCreated,
        ];
    }

    public static function groupEvents(): array
    {
        return [
            self::GroupsGroupCreated,
            self::GroupsGroupUpdated,
            self::GroupsGroupDestroyed,
            self::GroupsMembershipCreated,
            self::GroupsMembershipUpdated,
            self::GroupsMembershipDestroyed,
        ];
    }

    public function isPeopleEvent(): bool
    {
        return in_array($this, self::peopleEvents(), true);
    }

    public function isGroupsEvent(): bool
    {
        return in_array($this, self::groupEvents(), true);
    }
}
