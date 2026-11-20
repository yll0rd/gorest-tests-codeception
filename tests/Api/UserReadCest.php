<?php

declare(strict_types=1);

namespace Tests\Api;

use Codeception\Util\HttpCode;
use Tests\Support\ApiTester;

final class UserReadCest
{
    public function _before(ApiTester $I): void
    {
        $I->haveHttpHeader('Accept', 'application/json');
    }

    public function listUsersReturnsArrayOfUsers(ApiTester $I): void
    {
        $I->sendGet('/users');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'string',
            'name'=> 'string',
            'email'=> 'string:regex(/@/)',
            'status'=> 'string:regex(/^(active|inactive)$/)',
            'read_only'=> 'boolean',
            'created_at'=> 'string',
            'updated_at'=> 'string',
        ], '$.data[0]');
    }

    public function filterByStatus(ApiTester $I): void
    {
        $I->sendGet('/users', ['status' => 'active']);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->dontSeeResponseJsonMatchesJsonPath('$[?(@.status=="inactive")]');
    }

    public function unknownUserReturns404(ApiTester $I): void
    {
        $I->sendGet('/users/0190a8e4-0000-7000-8000-000000000001');

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
