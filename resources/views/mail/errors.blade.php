@extends('mail.layout.default')

@section('heading')
ClinGen Run Check Update Error
@endsection

@section('content')
Hello,<br/><br/>
The following error has been detected:<br />
<br />
	
    The ClinGen Run Check script is blocking on an unfinished job.
	
	<br /><br />


@endsection

@section('boilerplate')
	<strong>About ClinGen - Clinical Genome Resource</strong><br/>
ClinGen is a National Institutes of Health (NIH)-funded resource dedicated to building an authoritative central
resource that defines the clinical relevance of genes and variants for use in precision medicine and research.
</br></br>
To learn more about ClinGen, visit <a href="https://clinicalgenome.org">www.clinicalgenome.org</a>
@endsection
