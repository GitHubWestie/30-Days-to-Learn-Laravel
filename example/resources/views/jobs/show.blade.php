<x-layout>
    <x-slot:heading>📈 {{ $job['title'] }}</x-slot:heading>

    <h2 class="font-bold text-lg">
        {{ $job['title'] }}
    </h2>
    
    <p>This job pays {{ $job['salary'] }} per year</p>
    
    <div class="mt-6">
        <x-button href="/jobs/{{ $job['id'] }}/edit">Edit Job</x-button>
    </div>
</x-layout>