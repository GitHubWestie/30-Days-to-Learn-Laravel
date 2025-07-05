@props(['job'])

<x-panel class="flex gap-x-6">
    <div>
        <x-employer-logo :employer="$job->employer" width="100" />
    </div>

    <div class="flex-1 flex flex-col">
        <a href="#" class="self-start text-sm text-gray-400">{{ $job->employer->name }}</a>

            <a href="{{ $job->url }}" target="_blank" class="mt-3 group-hover:text-blue-800 text-xl font-bold transition-colors duration-300">{{ $job->title }}</a>
            
            <p class="text-sm text-gray-400 mt-auto">{{ $job->schedule }} - From {{ $job->salary }}</p>
    </div>

    <div class="flex flex-col justify-between items-center">
        <div class="self-end">
            <span>{{ $job->location }}</span>
        </div>
        <div>
            @foreach ($job->tags as $tag)
                <x-tag :tag="$tag">Backend</x-tag>
            @endforeach
        </div>
    </div>
</x-panel>