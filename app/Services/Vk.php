<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Jobs\GetFriendsJob;

class Vk
{
    public function getFriend(int $id, $recursive = false)
    {
        $friends = $this->callApi($id);
        foreach ($friends ?? [] as $friend) {
            $user = User::where('vk_id', $friend['id'])->exists();
            if (!$user) {
                $user =  User::create([
                    'first_name' => $friend['first_name'],
                    'last_name' => $friend['last_name'],
                    'vk_id' => $friend['id'],
                ]);
            }
            if ($recursive) GetFriendsJob::dispatch($user);
        }
    }


    /**
     * @param $id
     * @return array|null
     */
    private function callApi($id): ?array
    {
        $response = $this->getResponse($id);
        return $response->json()['response']['items'] ?? null;
    }

    private function getResponse(int $id): Response
    {
        $params = [
            'fields' => 'first_name,last_name,id',
            'user_id' => $id,
            'access_token' => config('services.vk.token'),
            'v' => config('services.vk.version')
        ];
        $method = 'friends.get';
        return Http::get(config('services.vk.url') . $method, $params);
    }
}
