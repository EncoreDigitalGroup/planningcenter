<?php

namespace Tests\Unit\Webhooks;

use EncoreDigitalGroup\PlanningCenter\PlanningCenter;
use EncoreDigitalGroup\PlanningCenter\Resources\WebhookSubscription;
use EncoreDigitalGroup\PlanningCenter\Support\Paginator;
use EncoreDigitalGroup\PlanningCenter\Support\PlanningCenterWebhookEvent;
use Illuminate\Support\Collection;
use Tests\Helpers\TestConstants;

describe("Webhook Subscription Tests", function (): void {
    test("PlanningCenterWebhookEvent: Identifies People Events", function (): void {
        foreach (PlanningCenterWebhookEvent::peopleEvents() as $event) {
            expect($event->isPeopleEvent())->toBeTrue()
                ->and($event->isGroupsEvent())->toBeFalse();
        }
    });

    test("PlanningCenterWebhookEvent: Identifies Groups Events", function (): void {
        foreach (PlanningCenterWebhookEvent::groupEvents() as $event) {
            expect($event->isGroupsEvent())->toBeTrue()
                ->and($event->isPeopleEvent())->toBeFalse();
        }
    });

    test("WebhookSubscription: Can List All Webhook Subscriptions", function (): void {
        $paginator = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->webhooks()
            ->all();

        expect($paginator)->toBeInstanceOf(Paginator::class)
            ->and($paginator->items())->toBeInstanceOf(Collection::class)
            ->and($paginator->items()->count())->toBe(1);
    });

    test("WebhookSubscription: Can Get Webhook Subscription By ID", function (): void {
        $subscription = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->webhooks()
            ->webhookSubscription()
            ->withId(WebhookMocks::WEBHOOK_SUBSCRIPTION_ID)
            ->get();

        expect($subscription)->toBeInstanceOf(WebhookSubscription::class)
            ->and($subscription->id())->toBe(WebhookMocks::WEBHOOK_SUBSCRIPTION_ID)
            ->and($subscription->name())->toBe(WebhookMocks::WEBHOOK_SUBSCRIPTION_NAME)
            ->and($subscription->url())->toBe(WebhookMocks::WEBHOOK_SUBSCRIPTION_URL)
            ->and($subscription->active())->toBeTrue();
    });

    test("WebhookSubscription: Can Create Webhook Subscription", function (): void {
        $subscription = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->webhooks()
            ->webhookSubscription()
            ->withName(PlanningCenterWebhookEvent::PeoplePersonCreated->value)
            ->withUrl(WebhookMocks::WEBHOOK_SUBSCRIPTION_URL)
            ->withActive(true)
            ->save();

        expect($subscription)->toBeInstanceOf(WebhookSubscription::class)
            ->and($subscription->id())->not()->toBeNull();
    });

    test("WebhookSubscription: Can Update Webhook Subscription", function (): void {
        $subscription = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->webhooks()
            ->webhookSubscription()
            ->withId(WebhookMocks::WEBHOOK_SUBSCRIPTION_ID)
            ->withActive(false)
            ->save();

        expect($subscription)->toBeInstanceOf(WebhookSubscription::class)
            ->and($subscription->id())->toBe(WebhookMocks::WEBHOOK_SUBSCRIPTION_ID);
    });

    test("WebhookSubscription: Can Delete Webhook Subscription", function (): void {
        $subscription = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->webhooks()
            ->webhookSubscription()
            ->withId(WebhookMocks::WEBHOOK_SUBSCRIPTION_ID);

        $result = $subscription->delete();

        expect($result)->toBeTrue();
    });

    test("WebhookSubscription: Can Rotate Secret", function (): void {
        $subscription = PlanningCenter::make()
            ->withBasicAuth(TestConstants::CLIENT_ID, TestConstants::CLIENT_SECRET)
            ->webhooks()
            ->webhookSubscription()
            ->withId(WebhookMocks::WEBHOOK_SUBSCRIPTION_ID);

        $result = $subscription->rotateSecret();

        expect($result)->toBeTrue();
    });
})->group("webhooks.subscription");
