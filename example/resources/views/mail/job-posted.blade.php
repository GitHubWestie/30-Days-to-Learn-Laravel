<h2>{{ $job->title }}</h2>

<p>
    Awesome! Your new job listing is now live and ready to view on our site.
</p>

<p>
    <a href="{{ url('/jobs/' . $job->id) }}">View your new job listing</a>
</p>