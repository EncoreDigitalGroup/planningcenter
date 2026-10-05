# Person

### All People

To get all people that exist in Planning Center:

```php
$people = PlanningCenter::make()
    ->withBasicAuth($clientId, $clientSecret)
    ->people()
    ->all();

foreach ($people->items() as $person) {
    echo $person->firstName();
}
```

### Get a Specific Person

To get a single Person from Planning Center, chain `withId()` and `get()`:

```php
$person = PlanningCenter::make()
    ->withBasicAuth($clientId, $clientSecret)
    ->people()
    ->person()
    ->withId('123')
    ->get();
```

### Create a Person

To create a new Person, set attributes using fluent setters and call `save()`:

```php
$person = PlanningCenter::make()
    ->withBasicAuth($clientId, $clientSecret)
    ->people()
    ->person()
    ->withFirstName('John')
    ->withLastName('Smith')
    ->save();
```

> You can pass more than just the first and last name fields when creating a new Person.
> This is just the minimum requirement.

### Update a Person

To update an existing person, provide the ID along with the fields to update:

```php
PlanningCenter::make()
    ->withBasicAuth($clientId, $clientSecret)
    ->people()
    ->person()
    ->withId('123')
    ->withFirstName('Jon')
    ->save();
```

> Only the fields you set will be included in the update request.

### Delete a Person

To delete a person from Planning Center, provide the ID and call `delete()`:

```php
PlanningCenter::make()
    ->withBasicAuth($clientId, $clientSecret)
    ->people()
    ->person()
    ->withId('123')
    ->delete();
```

> There is no way to recover a person once they have been deleted in Planning Center.

# Email

### Get Emails for a Person

Emails are available as a relationship on the Person resource:

```php
$person = PlanningCenter::make()
    ->withBasicAuth($clientId, $clientSecret)
    ->people()
    ->person()
    ->withId('123')
    ->get();

$emails = $person->emails();

foreach ($emails->items() as $email) {
    echo $email->address();
}
```

### Create an Email for a Person

Save the Person first, then create an Email through the Person's scoped factory:

```php
$person = PlanningCenter::make()
    ->withBasicAuth($clientId, $clientSecret)
    ->people()
    ->person()
    ->withFirstName('John')
    ->withLastName('Smith')
    ->save();

$email = $person
    ->email()
    ->withAddress('john@example.com')
    ->withLocation('home')
    ->withPrimary(true)
    ->save();
```

### Get an Email for a Person

Fetch a specific Email through its Person:

```php
$person = PlanningCenter::make()
    ->withBasicAuth($clientId, $clientSecret)
    ->people()
    ->person()
    ->withId('123')
    ->get();

$email = $person
    ->email()
    ->withId('456')
    ->get();
```

### Update an Email for a Person

Update a specific Email through its Person:

```php
$email = PlanningCenter::make()
    ->withBasicAuth($clientId, $clientSecret)
    ->people()
    ->person()
    ->withId('123')
    ->email()
    ->withId('456')
    ->withAddress('updated@example.com')
    ->save();
```

### Delete an Email for a Person

Delete a specific Email through its Person:

```php
PlanningCenter::make()
    ->withBasicAuth($clientId, $clientSecret)
    ->people()
    ->person()
    ->withId('123')
    ->email()
    ->withId('456')
    ->delete();
```

### Attributes

When retrieving people, the following attributes are available:

| Method                      | Type               | Description                                        |
|-----------------------------|--------------------|----------------------------------------------------|
| `id()`                      | `?string`          | Unique identifier                                  |
| `firstName()`               | `?string`          | First name                                         |
| `lastName()`                | `?string`          | Last name                                          |
| `givenName()`               | `?string`          | Given name                                         |
| `nickname()`                | `?string`          | Nickname                                           |
| `middleName()`              | `?string`          | Middle name                                        |
| `name()`                    | `?string`          | Full name (read-only, computed by Planning Center) |
| `birthdate()`               | `?CarbonImmutable` | Date of birth                                      |
| `anniversary()`             | `?CarbonImmutable` | Anniversary date                                   |
| `gender()`                  | `?string`          | Gender                                             |
| `grade()`                   | `?int`             | Grade level                                        |
| `child()`                   | `?bool`            | Whether this person is a child                     |
| `graduationYear()`          | `?int`             | Graduation year                                    |
| `siteAdministrator()`       | `?bool`            | Whether this person is a site administrator        |
| `accountingAdministrator()` | `?bool`            | Whether this person is an accounting administrator |
| `peoplePermissions()`       | `?string`          | People permissions level                           |
| `membership()`              | `?string`          | Membership type                                    |
| `inactivatedAt()`           | `?CarbonImmutable` | When the person was inactivated                    |
| `medicalNotes()`            | `?string`          | Medical notes                                      |
| `mfaConfigured()`           | `?bool`            | Whether MFA is configured                          |
| `avatar()`                  | `?string`          | Avatar URL                                         |
| `demographicAvatarUrl()`    | `?string`          | Demographic avatar URL (read-only)                 |
| `directoryStatus()`         | `?string`          | Directory status                                   |
| `passedBackgroundCheck()`   | `?bool`            | Whether a background check was passed              |
| `canCreateForms()`          | `?bool`            | Whether this person can create forms               |
| `canEmailLists()`           | `?bool`            | Whether this person can email lists                |
| `schoolType()`              | `?string`          | School type                                        |
| `status()`                  | `?string`          | Status                                             |
| `primaryCampusId()`         | `?int`             | Primary campus ID                                  |
| `remoteId()`                | `?int`             | Remote ID                                          |
| `createdAt()`               | `?CarbonImmutable` | When the record was created (read-only)            |
| `updatedAt()`               | `?CarbonImmutable` | When the record was last updated (read-only)       |
