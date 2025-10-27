<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobListing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class JobListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(storage_path('app/private/jobs.json'));
        $data = json_decode($json);

        foreach ($data->jobs as $job) {
            $company = Company::firstOrCreate(
                ['name' => $job->company->name],
                [
                    'description' => $job->company->description,
                    'contactEmail' => $job->company->contactEmail,
                    'contactPhone' => $job->company->contactPhone,
                ]
            );

            JobListing::create([
                'title' => $job->title,
                'type' => $job->type,
                'description' => $job->description,
                'responsibilities' => $job->responsibilities,
                'location' => $job->location,
                'salary' => $job->salary,
                'company_id' => $company->id,
            ]);
        }
    }
}