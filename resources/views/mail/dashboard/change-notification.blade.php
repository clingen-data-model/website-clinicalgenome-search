@extends('mail.layout.default')

@section('heading')
Followed Genes Update Notification
@endsection

@section('content')
Hello,<br/><br/>
You are receiving this email because one or more genes you are following have been updated as of {{ $date }}. A full report can be found by clicking the link below or accessing your ClinGen Dashboard.
<br />
<br />
	<center><a href="{{ config('app.url') }}/reports/view/{{ $report }}" class="button">View Report</a></center>
	<br /><br />
	<hr />
	<br />
	<div style="margin-bottom: 6px;"><strong>The following genes have been updated:</strong></div>
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