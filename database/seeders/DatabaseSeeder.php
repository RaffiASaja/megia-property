<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $uploadDirs = [
            public_path('uploads/foto'),
            public_path('uploads/video'),
            public_path('uploads/sertifikat'),
        ];

        foreach ($uploadDirs as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            foreach (File::allFiles($dir) as $file) {
                File::delete($file->getPathname());
            }
        }

        $this->call(UserSeeder::class);
        $this->call(TanahSeeder::class);
    }
}