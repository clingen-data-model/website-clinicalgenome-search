@extends('mail.layout.default')

@section('heading')
Gene-Disease Validity Evidence Feedback
@endsection

@section('content')
Hello,<br/><br/>
The following feedback has been submitted:<br />
<br />
	From:  {{ $fullname }}
	<br /><br />
    GCEP Member:  {{ $gcep }}
	<br /><br />
    Email:  {{ $email }}
	<br /><br />
    Institution:  {{ $company }}
	<br /><br />
    Position:  {{ $title }}
	<br /><br />
    <u>Types:</u><br />
    @foreach ($classifications as $v)
        {{ $v }} <br />
    @endforeach
	<br /><br />
    <u>Comments:</u>
    <div style="white-space: pre-wrap;">
    {{ $comment }}
    </div>
	<br /><br />
    Gene:  {{ $gene }}
	<br /><br />
    Page Link:<br />
    {{ $link }}
	<br /><br />


@endsection

@section('boilerplate')
	<strong>About ClinGen - Clinical Genome Resource</strong><br/>
ClinGen is a National Institutes of Health (NIH)-funded resource dedicated to building an authoritative central
resource that defines the clinical relevance of genes and variants for use in precision medicine and research.
</br></br>
To learn more about ClinGen, visit <a href="https://clinicalgenome.org">www.clinicalgenome.org</a>
@endsection
