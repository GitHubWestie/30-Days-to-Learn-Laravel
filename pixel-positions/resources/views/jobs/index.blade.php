<x-layout>
    <div class="space-y-10">
        {{-- Title and search --}}
        <section class="text-center pt-6">
            <h1 class="font-bold text-4xl">Let's Find Your Next Job</h1>

            <form action="" class="mt-8">
                <input
                    type="text" placeholder="Web developer..."
                    class="rounded-xl bg-white/5 border border-white/10 px-5 py-4 w-2xl focus-visible:outline-1 focus-visible:outline-white"
                    >
            </form>
        </section>
        {{-- Featured Jobs --}}
        <section class="pt-10">
            <x-section-heading>Featured Jobs</x-section-heading>
            <div class="grid lg:grid-cols-3 gap-8 mt-6">
                @foreach ($jobs as $job)
                    @if ($job->featured)
                        <x-job-card :job="$job" />
                    @endif
                @endforeach
            </div>
        </section>
        {{-- Tags --}}
        <section>
            <x-section-heading>Tags</x-section-heading>
            <div class="mt-6 space-x-1">
                @foreach ($tags as $tag)
                    <x-tag :tag="$tag" />
                @endforeach
            </div>
        </section>
        {{-- Recent Jobs --}}
        <section>
            <x-section-heading>Recent Jobs</x-section-heading>
            <div class="mt-6 space-y-6">
                @foreach ($jobs as $job)
                    <x-job-card-wide :job="$job" />
                @endforeach
            </div>
        </section>
    </div>
</x-layout>
