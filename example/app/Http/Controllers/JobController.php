<?php

namespace App\Http\Controllers;

use App\Mail\JobPosted;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with('employer')->latest()->simplePaginate(3);

        return view('jobs/index', [
            'jobs' => $jobs,
        ]);
    }

    public function create()
    {
        return view('jobs/create');
    }

    public function show(Job $job)
    {
        return view('jobs/show', [
            'job' => $job,
        ]);
    }

    public function store()
    {
        // validation...
        request()->validate([
            'title' => 'required|min:3',
            'salary' => 'required',
        ]);

        $job = Job::create([
            'title' => request('title'),
            'salary' => request('salary'),
            'employer_id' => 1 // Temporarily hardcoded
        ]);

        // Send confirmation email to user
        Mail::to($job->employer->user)->queue(new JobPosted($job));

        return redirect('/jobs');
    }

    public function edit(Job $job)
    {
        return view('jobs/edit', [
            'job' => $job,
        ]);
    }

    public function update(Job $job)
    {
        // Authorise - Not possible yet...

        // Validate
        request()->validate([
            'title' => 'required|min:3',
            'salary' => 'required'
        ]);

        $job->Update([
            'title' => request('title'),
            'salary' => request('salary'),
        ]);

        // Redirect back to jobs index
        return redirect('/jobs/' . $job->id);
    }

    public function destroy(Job $job)
    {
        // Authorise user

        // Destroy the job
        $job->delete();

        return redirect('/jobs');
    }
}
