<?php

use Illuminate\Support\Facades\Route;
use App\Models\Job;

Route::get('/', function () {
    return view('home');
});

// Index
Route::get('/jobs', function () {
    $jobs = Job::with('employer')->latest()->simplePaginate(3);

    return view('jobs/index', [
        'jobs' => $jobs,
    ]);
});

// Create
Route::get('/jobs/create', function () {
    return view('jobs.create');
});

// Show
Route::get('/jobs/{id}', function ($id) {

    $job = Job::find($id);

    return view('jobs/show', [
        'job' => $job,
    ]);
});

// Store
Route::post('/jobs', function () {
    // validation...
    request()->validate([
        'title' => 'required|min:3',
        'salary' => 'required',
    ]);

    Job::create([
        'title' => request('title'),
        'salary' => request('salary'),
        'employer_id' => 1 // Temporarily hardcoded
    ]);

    return redirect('/jobs');
});

// Edit
Route::get('/jobs/{id}/edit', function ($id) {

    $job = Job::find($id);

    return view('jobs/edit', [
        'job' => $job,
    ]);
});

// Update
Route::patch('/jobs/{id}', function ($id) {
    // Validate
    request()->validate([
        'title' => 'required|min:3',
        'salary' => 'required'
    ]);

    // Authorise - Not possible yet...

    // Update the record
    $job = Job::findOrFail($id);

    $job->Update([
        'title' => request('title'),
        'salary' => request('salary'),
    ]);

    // Redirect back to jobs index
    return redirect('/jobs/' . $job->id);
});

// Delete
Route::delete('/jobs/{id}', function ($id) {
    // Authorise user

    // Get the job
    $job = Job::findOrFail($id);

    // Destroy the job
    $job->delete();

    return redirect('/jobs');
});

Route::get('/contact', function () {
    return view('contact');
});
