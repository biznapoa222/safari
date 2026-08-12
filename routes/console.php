<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\ProposalWorkflowService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('proposals:sync-workflow', function (ProposalWorkflowService $workflow) {
    $workflow->synchronize();
    $this->info('Proposal dates, trip stages and itinerary completion are synchronized.');
})->purpose('Advance proposal and trip workflow from live dates and itinerary completion');

Schedule::command('proposals:sync-workflow')->hourly()->withoutOverlapping();
