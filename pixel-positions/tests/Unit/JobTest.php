<?php

namespace Tests\Unit;

use App\Models\Employer;
use App\Models\Job;
use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_job_belongs_to_employer(): void
    {
        // Arrange (Given)
        $employer = Employer::factory()->create(); // Create an employer
        $job = Job::factory()->create(['employer_id' => $employer->id]); // Create a job linked to the employer

        // Act (When)
        $associatedEmployer = $job->employer; // Access the relationship

        // Assert (Then)
        $this->assertInstanceOf(Employer::class, $associatedEmployer); // Check if it's an Employer instance
        $this->assertTrue($associatedEmployer->is($employer)); // Check if it's the *same* employer instance
        $this->assertEquals($employer->id, $job->employer->id); // Verify the employer ID
    }
}
