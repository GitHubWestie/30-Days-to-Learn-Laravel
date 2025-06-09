<x-layout>
    <x-slot:heading>📈 {{ $job['title'] }}</x-slot:heading>
    <ul>
        <li><strong>Salary:</strong> {{ $job['salary'] }} per year</li>
    </ul>
</x-layout>