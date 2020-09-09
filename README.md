# Laravel + Cypress Integration

> This package is a clone of [laracasts/cypress](https://github.com/laracasts/cypress) to support lower versions of php 7.2.5 or higher

This package provides the necessary boilerplate to quickly begin testing your Laravel applications using Cypress.

<img src="https://user-images.githubusercontent.com/183223/89684657-e2e5ef00-d8c8-11ea-825c-ed5b5acc37a4.png" width="300">

## Installation

Begin by installing the package as a Composer development-only dependency.

```bash
composer require thetechyhub/cypress --dev
```

If you haven't yet pulled in Cypress through npm, that's your next step:

```bash
npm install cypress --save-dev && npx cypress open
```

Next, generate the necessary Laravel Cypress boilerplate:

```bash
php artisan cypress:boilerplate
```

The final step is one you'll perform regardless of whether you use this package or not. Update your `cypress.json` file with the `baseUrl` of your application.

```json
{
  "baseUrl": "http://my-app.test"
}
```

When making requests in your Cypress tests, this `baseUrl` will be prepended to any relative URL you provide.

```js
cy.visit('/foo'); // http://my-app.test/foo
```

## Environment Handling

After running the `php artisan cypress:boilerplate` command, you'll now have a `.env.cypress`
file in your project root. To get you started, this file is a duplicate of `.env`. Feel free to update
it as needed to prepare your application for your Cypress tests.

Likely, you'll want to use a special database to ensure that your Cypress acceptance tests are isolated from your local database.

```
DB_CONNECTION=mysql
DB_DATABASE=cypress
```

When running your Cypress tests, this package will automatically back up your primary `.env` file, and swap it out with `env.cypress`.
Once complete, of course the environment files will be reset to how they originally were.

> All Cypress tests run according to the environment specified in `.env.cypress`.

## Usage

This package will add a variety of commands to your Cypress workflow to make for a more familiar Laravel testing environment.

We allow for this by exposing a handful of Cypress-specific endpoints in your application. Don't worry: these endpoints will **never** be accessible in production.

### cy.login()

Create a new user record matching the optional attributes provided and set it as the authenticated user for the test.

```js
test('authenticated users can see the dashboard', () => {
  cy.login({ name: 'John Doe' });

  cy.visit('/dashboard').contains('Welcome Back, John Doe!');
});
```

### cy.logout()

Log out the currently authenticated user. Equivalent to `auth()->logout()`.

```js
test('once a user logs out they cannot see the dashboard', () => {
  cy.login({ name: 'John Doe' });

  cy.visit('/dashboard').contains('Welcome Back, John Doe!');

  cy.logout();

  cy.visit('/dashboard').assertRedirect('/login');
});
```

### cy.create()

Use Laravel factories to create and persist a new Eloquent record.

```js
test('it shows blog posts', () => {
  cy.create('App\\Post', { title: 'My First Post' });

  cy.visit('/posts').contains('My First Post');
});
```

Note that the `cy.create()` call above is equivalent to:

```php
factory('App\Post')->create(['title' => 'My First Post']);
```

You may optionally specify the number of records you require as the second argument. If provided, the attributes
can be provided as the third argument.

```js
test('it shows blog posts', () => {
  cy.create('App\\Post', 3);

  //
});
```

### cy.refreshDatabase()

Trigger a `migrate:refresh` on your test database. Often, you'll apply this in a `beforeEach` call to ensure that,
before each new test in the file, your database is freshly migrated and cleaned up.

```js
beforeEach(() => {
  cy.refreshDatabase();
});

test('it does something', () => {
  // php artisan migrate:fresh has been
  // called at this point.
});
```

### cy.seed()

Run all database seeders, or a single class, in the current Cypress environment.

```js
test('it seeds the db', () => {
  cy.seed('PlansTableSeeder');
});
```

Assuming that `APP_ENV` in your `.env.cypress` file is set to "acceptance," the call above would be equivalent to:

```bash
php artisan db:seed --class=PlansTableSeeder --env=acceptance
```

### cy.artisan()

Trigger any Artisan command under the current environment for the Cypress test. Remember to proceed options with two dashes, as usual.

```js
test('it can create posts through the command line', () => {
  cy.artisan('post:make', {
    '--title': 'My First Post',
  });

  cy.visit('/posts').contains('My First Post');
});
```

This call is equivalent to:

```bash
php artisan post:make --title="My First Post"
```

### cy.php()

While not exactly in the spirit of acceptance testing, this command will allow you to trigger and evaluate arbitrary PHP.

```js
test('it can evaluate PHP', () => {
    cy.php(`
        App\\Plan::first();
    `).then(plan => {
        expect(plan.name).to.equal('Monthly'); 
    });
});
```

Be thoughtful when you reach for this command, but it might prove useful in instances where it's vital that you verify the state of the application or database in response to a certain action. It could also be used 
for setting up the "world" for your test. That said, a targeted database seeder - using `cy.seed()` - will typically be the better approach.

### Security

If you discover any security related issues, please email jeffrey@laracasts.com instead of using the issue tracker.

## Credits

- [Jeffrey Way](https://twitter.com/jeffrey_way)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
