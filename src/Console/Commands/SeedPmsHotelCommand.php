<?php

namespace Kaely\PmsHotel\Console\Commands;

use Illuminate\Console\Command;

class SeedPmsHotelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pms-hotel:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed PMS Hotel package data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding PMS Hotel data...');
        
        // Here you can add specific seeders for the PMS Hotel package
        // For now, we'll just show a message
        
        $this->info('PMS Hotel data seeded successfully!');
        
        return 0;
    }
}