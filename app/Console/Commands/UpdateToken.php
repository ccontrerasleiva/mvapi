<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renueva Token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::find(1);

        $ch = curl_init();

        $params = [
            'client_id' => 613442147446,
            'client_secret' => 'mfNpz4whhuekq2vDSFhOUTTl9TX5vfkgk5aB3lx778HVVAQHVG',
            'grant_type' => 'refresh_token',
            'refresh_token' => $user->refresh_token,
        ];

        curl_setopt($ch, CURLOPT_URL, 'https://app.multivende.com/oauth/access-token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

        $headers = array();
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $resp = json_decode($result);
        
        $user->current_token = $resp->token;
        $user->refresh_token = $resp->refreshToken;
        $user->save();
        return 0;
    }
}
