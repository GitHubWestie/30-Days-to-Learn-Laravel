<x-layout>
    <x-slot:heading>📈 Jobs</x-slot:heading>
    <h1>Welcome to the Jobs Page! ℹ️</h1>
    <ul>
        @foreach($jobs as $job)
            <a href="jobs/{{ $job['id'] }}">
                <li><strong>{{ $job['title'] }}</strong>: Pays {{ $job['salary'] }} per year</li>
            </a>
        @endforeach
    </ul>
</x-layout>