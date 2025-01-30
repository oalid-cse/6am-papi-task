<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database and store it in the storage/backups directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fileName = 'db_' . now()->format('Y_m_d_H_i_s') . '.sql';
        $backupPath = storage_path('backups/' . $fileName);

        if (!is_dir(storage_path('backups'))) {
            mkdir(storage_path('backups'), 0755, true);
        }
        if(config('database.default') != 'mysql') {
            $this->error('This command only works for MySQL databases');
            return;
        }

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $command = sprintf(
            'mysqldump -u%s -p%s -h%s %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($database),
            escapeshellarg($backupPath)
        );

        $process = shell_exec($command);

        if (file_exists($backupPath)) {
            $this->info('Backup created successfully: ' . $fileName);

            if(config('app.backup_database_to_s3')) {
                // Bonus: Upload to AWS S3
                if (config('app.env') == 'production') {
                    $s3BasePath = 'production/backups/';
                } else {
                    $s3BasePath = 'local/backups/';
                }
                if (Storage::disk('s3')->put($s3BasePath . $fileName, file_get_contents($backupPath))) {
                    $this->info('Backup uploaded to S3 successfully.');
                } else {
                    $this->error('Backup upload to S3 failed.');
                }
            }
        } else {
            $this->error('Backup failed.');
        }
    }
}
