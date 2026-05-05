<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class FrontendApiTokenSeeder extends Seeder
{
    public function run(): void
    {
        $fullToken = '2|58SdxumWXB8Ggwfgvbcg94lGubnvyMpTiFznuTEw105a7e56';
        [$tokenId, $plainToken] = explode('|', $fullToken, 2);

        $user = User::firstOrCreate(
            ['email' => 'admin@web.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
            ]
        );

        PersonalAccessToken::query()
            ->where('tokenable_type', User::class)
            ->where('tokenable_id', $user->id)
            ->where('name', 'frontend-integration-token')
            ->delete();

        PersonalAccessToken::unguard();
        PersonalAccessToken::query()->updateOrCreate(
            ['id' => (int) $tokenId],
            [
                'tokenable_type' => User::class,
                'tokenable_id' => $user->id,
                'name' => 'frontend-integration-token',
                'token' => hash('sha256', $plainToken),
                'abilities' => ['*'],
                'last_used_at' => null,
                'expires_at' => null,
            ]
        );
        PersonalAccessToken::reguard();
    }
}

