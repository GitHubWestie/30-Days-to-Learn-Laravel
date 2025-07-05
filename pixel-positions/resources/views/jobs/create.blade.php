<x-layout>
    <x-page-heading>Create a Job</x-page-heading>

    <x-forms.form action="/jobs" method="POST">
        <x-forms.input label="Title" name="title" placeholder="Director" />
        <x-forms.input label="Salary" name="salary" placeholder="90000" />
        <x-forms.input label="Location" name="location" placeholder="Remote" />

        <x-forms.select label="Schedule" name="schedule">
            <option>Full-time</option>
            <option>Part-time</option>
            <option>Flexible</option>
        </x-forms.select>

        <x-forms.input label="URL" name="url" placeholder="www.example.com/job-vacancy"/>
        
        <x-forms.checkbox label="Featured" name="featured" />
        
        <x-forms.divider />
        
        <x-forms.input label="Tags (comma separated)" name="tags" placeholder="Remote, Full-time, education" />
        
        <x-forms.button>Publish</x-forms.button>
    </x-forms.form>
</x-layout>
