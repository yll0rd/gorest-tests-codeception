<?php

declare(strict_types=1);

namespace Tests\Api;

use Tests\Support\ApiTester;
use Tests\Support\GoRestTrait;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use Codeception\Util\HttpCode;

final class UserCrudCest
{
    use GoRestTrait;

    private array $user = [];
    private ?string $userId = null;

    public function _before(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $this->authenticate($I);

        // Arrange: every test starts with its own fresh user
        $this->user = $this->newUser();
        $I->sendPost('/users', $this->user);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $this->userId = $I->grabDataFromResponseByJsonPath('$.id')[0];
        echo "Created user with ID {$this->userId}\n";
    }

    public function createdUserCanBeFetched(ApiTester $I): void
    {
        $I->sendGet("/users/{$this->userId}");

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($this->user);
    }

    public function patchChangesOnlyGivenFields(ApiTester $I): void
    {
        $I->sendPatch("/users/{$this->userId}", ['name' => 'Patched Name']);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'name' => 'Patched Name',
            'email' => $this->user['email'], // untouched
        ]);
    }

    public function deleteRemovesTheUser(ApiTester $I): void
    {
        $I->sendDelete("/users/{$this->userId}");
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);

        $I->sendGet("/users/{$this->userId}");
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    public function duplicateEmailIsRejected(ApiTester $I): void
    {
        $I->sendPost('/users', $this->user); // same email again

        $I->seeResponseCodeIs(HttpCode::CONFLICT);
        $I->seeResponseContainsJson(['code' => 'conflict']);
    }

    #[DataProvider('invalidUsers')]
    public function invalidPayloadsAreRejected(ApiTester $I, Example $example): void
    {
        $I->sendPost('/users', $this->newUser($example['override']));

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    protected function invalidUsers(): array
    {
        return [
            'blank name' => ['override' => ['name' => ''] ],
            'bad email' => ['override' => ['email' => 'not-email'] ],
            'bad gender' => ['override' => ['gender' => 'robot'] ],
            'bad status' => ['override' => ['status' => 'maybe'] ],
        ];
    }

    public function _after(ApiTester $I): void
    {
        // Cleanup: delete the user if it still exists
        if ($this->userId !== null) {
            $I->sendDelete("/users/{$this->userId}");
        }
    }
}
