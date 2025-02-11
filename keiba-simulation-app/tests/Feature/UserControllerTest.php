<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Crud\UserService;

class UserControllerTest extends TestCase
{
    private $autoIncrement;

    protected function setUp(): void
    {
        parent::setUp();

        // 現在のオートインクリメントの値を取得
        $this->autoIncrement = $this->getAutoIncrementValue('user');

        // 特定のデータを削除する（テストに影響がでないようにする）
        $this->deleteTestData();

    }

    protected function tearDown(): void
    {
        // 特定のデータを削除する
        $this->deleteTestData();

        // オートインクリメントの値を元に戻す
        if ($this->autoIncrement !== null) {
            $this->setAutoIncrementValue('user', $this->autoIncrement);
        }

        parent::tearDown();
    }

    /** @test */
    // 正常テスト
    public function store_creates_a_new_user_and_redirects()
    {
        $response = $this->post(route('user.store'), [
            'code' => 'unitTest01',
            'username' => 'ユニットテストユーザー01',
            'password' => 'test001',
            'password_confirm' => 'test001',
        ]);

        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('success', '新しいデータの作成が完了しました');

        $this->assertDatabaseHas('user', [
            'code' => 'unitTest01',
            'username' => 'ユニットテストユーザー01',
        ]);
    }

    /** @test */
    // パスワードの不一致テスト
    public function store_fails_if_passwords_do_not_match()
    {
        $response = $this->post(route('user.store'), [
            'code' => 'unitTest02',
            'username' => 'ユニットテストユーザー02',
            'password' => 'test002',
            'password_confirm' => 'another002',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'パスワードが一致しません');

        // ユーザーが作成されていないことを確認
        $this->assertDatabaseMissing('user', [
            'code' => 'unitTest02',
            'username' => 'ユニットテストユーザー02',
        ]);
    }

    /** @test */
    // 必須パラメータの不足テスト
    public function store_fails_validation_if_required_fields_are_missing()
    {
        $response = $this->post(route('user.store'), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['code', 'username', 'password', 'password_confirm']);
    }

    private function deleteTestData() {
        $this->deleteUser('unitTest01');
        $this->deleteUser('unitTest02');
    }

    private function deleteUser($code) {
        $userService = app(UserService::class);
        $user = $userService->getUserByUniqueColumn(['code' => $code]);
        if (!empty($user)) {
            $userService->deleteUser($user->getId());
        }
    }
}
