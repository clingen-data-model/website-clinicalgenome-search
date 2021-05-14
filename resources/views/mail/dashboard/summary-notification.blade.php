@extends('mail.layout.default')

@section('heading')
Followed Genes {{ $period }} Summary
@endsection

@section('content')
Hello,<br/><br/>
To view your {{ $period }} Summary Report, click on the link below or log into your ClinGen Dashboard.
<br />
<br />
	<center><a href="{{ config('app.url') }}/reports/view/{{ $report }}" class="button">View Summary Report</a></center>
	<br /><br />
	<hr />
	<br />
	<div style="margin-bottom: 6px;"><strong>The following genes were updated:</strong></div>
	@foreach ($genes as $gene)
	&nbsp;&nbsp; {{ $gene }}<br/>
	@endforeach
	<br /><br />
	<strong>Manage your email preferences</strong><br />
	Manage the frequency and scope of these notifications through the <a href="{{ config('app.url') }}/dashboard/">ClinGen Dashboard.</a>

@endsection

@section('boilerplate')
	<strong>About ClinGen - Clinical Genome Resource</strong><br/>
ClinGen is a National Institutes of Health (NIH)-funded resource dedicated to building an authoritative central
resource that defines the clinical relevance of genes and variants for use in precision medicine and research.
</br></br>
To learn more about ClinGen, visit <a href="https://clinicalgenome.org">www.clinicalgenome.org</a>
@endsection