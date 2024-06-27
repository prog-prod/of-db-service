<?php

namespace App\Console\Commands;

use App\Models\Proxy;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class CheckProxies extends Command
{
    protected $signature = 'check:proxies';
    protected $description = 'Check a list of proxies and return working ones';
    protected $fromFile = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info("Clearing proxies table...");
        if($this->fromFile){
            $proxyList = file(storage_path('/http_proxies.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        } else {
            $client = new Client(['timeout' => 10]);

            try {
                $response = $client->request('GET', 'https://raw.githubusercontent.com/TheSpeedX/SOCKS-List/master/http.txt');
                $proxyList = explode(PHP_EOL, $response->getBody()->getContents());
            } catch (\Exception $e) {
                $this->error('Failed to fetch the proxy list from the provided URL.');
                return;
            }
        }


        $workingProxies = [];

        $client = new Client(['timeout' => 3]);
        $progressBar = $this->output->createProgressBar(count($proxyList));
        $progressBar->start();
        foreach ($proxyList as $proxy) {
            $active = false;
            $proxy = trim($proxy);
            list($ip, $port, $username, $password) = explode(':', $proxy);
            $proxy = $username && $password
                ? 'http://' . $username . ':' . $password . '@' . $ip . ':' . $port
                : 'http://' . $ip . ':' . $port;

            try {
                $response = $client->request('GET', 'https://onlyfans.com', [
                    'proxy' => $proxy
                ]);
                $this->info("Status: ".$response->getStatusCode());
                if ($response->getStatusCode() == 200) {
                    $workingProxies[] = $ip.":".$port;
                    $active = true;
                }
            } catch (\Exception $e) {
                $this->info("Proxy $proxy failed.");
            }
            Proxy::query()->updateOrCreate([
                'ip' => $ip,
                'port' => $port,
                'username' => $username,
                'password' => $password,
                'active' => $active,
            ]);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->info('Working proxies are: ' . implode(', ', $workingProxies)." and were imported to DB.");
    }
}
