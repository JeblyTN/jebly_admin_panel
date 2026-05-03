<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\FirestoreHelper;

class TestFirestore extends Command
{
    protected $signature = 'test:firestore';
    protected $description = 'Test Firestore REST API connectivity (read + write)';

    public function handle()
    {
        $this->info('--- Firestore Connectivity Test ---');
        $this->info('project_id (config): ' . (config('firebase.project_id') ?: '(not set)'));
        $this->info('apikey (config):     ' . (config('firebase.apikey') ?: '(not set)'));
        $this->info('api_key (config):    ' . (config('firebase.api_key') ?: '(not set)'));

        // 1. Test write
        $this->info('');
        $this->info('[1] Writing test document to surgeStatus/_test ...');
        $testData = [
            'test' => true,
            'message' => 'Firestore connectivity test',
            'timestamp' => date('c'),
        ];
        $writeResult = FirestoreHelper::setDocument('surgeStatus/_test', $testData);
        if ($writeResult === null) {
            $this->error('WRITE FAILED — check storage/logs/laravel.log for details.');
            return 1;
        }
        $this->info('WRITE OK: ' . json_encode($writeResult));

        // 2. Test read
        $this->info('');
        $this->info('[2] Reading back surgeStatus/_test ...');
        $readResult = FirestoreHelper::getDocument('surgeStatus/_test');
        if ($readResult === null) {
            $this->error('READ FAILED — check storage/logs/laravel.log for details.');
            return 1;
        }
        $this->info('READ OK: ' . json_encode($readResult));

        // 3. Test read surgeStatus/current
        $this->info('');
        $this->info('[3] Reading surgeStatus/current ...');
        $current = FirestoreHelper::getDocument('surgeStatus/current');
        $this->info('Result: ' . ($current !== null ? json_encode($current) : '(null / does not exist)'));

        $this->info('');
        $this->info('All tests passed.');
        return 0;
    }
}
