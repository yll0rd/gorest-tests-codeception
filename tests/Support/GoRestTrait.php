<?php

namespace Tests\Support;

trait GoRestTrait
{
    protected function authenticate(ApiTester $I): void {
        $token = getenv('GOREST_TOKEN');
        if (!$token) {
            throw new \RuntimeException('Set the GOREST_TOKEN environment variable.');
        }
        $I->amBearerAuthenticated($token);
    }

    protected function newUser(array $overrides = []): array {
        return array_merge([
            'name' => 'Test User',
            'email' => 'user_' . uniqid() . '@example.com'
        ], $overrides);
    }
}