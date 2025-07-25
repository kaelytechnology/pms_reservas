<?php

namespace Kaely\PmsHotel\Console\Commands;

use Illuminate\Console\Command;

class InstallPmsHotelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pms-hotel:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install PMS Hotel package';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Installing PMS Hotel package...');
        
        // Publish configuration
        $this->call('vendor:publish', [
            '--tag' => 'pms-hotel-config',
            '--force' => true
        ]);
        
        // Publish migrations
        $this->call('vendor:publish', [
            '--tag' => 'pms-hotel-migrations',
            '--force' => true
        ]);
        
        // Publish seeders
        $this->call('vendor:publish', [
            '--tag' => 'pms-hotel-seeders',
            '--force' => true
        ]);
        
        $this->info('PMS Hotel package installed successfully!');
        $this->info('Run "php artisan migrate" to create the database tables.');
        $this->info('Run "php artisan pms-hotel:seed" to seed the database with sample data.');
        
        return 0;
    }
}