@component('mail::message')
# Hello {{ $student->first_name }},

Your registration has been approved successfully.

**Email:** {{ $student->email }}  
**Student ID:** {{ $student->id }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
