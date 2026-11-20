<?php

declare(strict_types=1);

namespace Tests\Api;

use Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

final class AuthCest
{
    public function _before(ApiTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function createWithoutTokenIsUnauthorized(ApiTester $I): void
    {
        $I->haveHttpHeader("Content-Type","application/json");
        $I->sendPOST('/users', [
            'name' => 'Test User',
            'email' => 'user_' . uniqid() . '@example.com'
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function createWithBadTokenIsUnauthorized(ApiTester $I): void
    {
        $I->amBearerAuthenticated('definitely-not-a-valid-token');
        $I->haveHttpHeader("Content-Type","application/json");
        $I->sendPOST('/users', [
            'name' => 'Test User',
            'email' => 'user_' . uniqid() . '@example.com'
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
