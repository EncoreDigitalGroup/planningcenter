<?php

namespace Tests\Unit\Calendar;

use EncoreDigitalGroup\PlanningCenter\PlanningCenter;
use EncoreDigitalGroup\PlanningCenter\Resources\CalendarEvent;
use EncoreDigitalGroup\PlanningCenter\Resources\CalendarTag;
use EncoreDigitalGroup\PlanningCenter\Resources\EventInstance;
use EncoreDigitalGroup\PlanningCenter\Support\Paginator;
use Illuminate\Support\Collection;
use Tests\Helpers\TestConstants;

describe("Calendar Event Tests", function (): void {
    test("CalendarEvent: Can List All Events (Read-Only)", function (): void {
        $paginator = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->calendar()
            ->all();

        expect($paginator)->toBeInstanceOf(Paginator::class)
            ->and($paginator->items())->toBeInstanceOf(Collection::class)
            ->and($paginator->items()->count())->toBe(1);
    });

    test("CalendarEvent: Can Get Event By ID (Read-Only)", function (): void {
        $event = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->calendar()
            ->event()
            ->withId(CalendarMocks::EVENT_ID)
            ->get();

        expect($event)->toBeInstanceOf(CalendarEvent::class)
            ->and($event->name())->toBe(CalendarMocks::EVENT_NAME)
            ->and($event->id())->toBe(CalendarMocks::EVENT_ID);
    });

    test("CalendarEvent: Can Get Tags For Event", function (): void {
        $event = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->calendar()
            ->event()
            ->withId(CalendarMocks::EVENT_ID)
            ->get();

        $tags = $event->tags();

        expect($tags)->toBeInstanceOf(Paginator::class)
            ->and($tags->items())->toBeInstanceOf(Collection::class)
            ->and($tags->items()->first())->toBeInstanceOf(CalendarTag::class)
            ->and($tags->items()->first()->id())->toBe(CalendarMocks::TAG_ID)
            ->and($tags->items()->first()->name())->toBe(CalendarMocks::TAG_NAME)
            ->and($tags->response())->not->toBeNull();
    });

    test("CalendarEvent: Can Get Event Instances For Event", function (): void {
        $event = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->calendar()
            ->event()
            ->withId(CalendarMocks::EVENT_ID)
            ->get();

        $instances = $event->eventInstances();

        expect($instances)->toBeInstanceOf(Paginator::class)
            ->and($instances->items()->first())->toBeInstanceOf(EventInstance::class)
            ->and($instances->response())->not->toBeNull();
    });
})->group("calendar.event");
