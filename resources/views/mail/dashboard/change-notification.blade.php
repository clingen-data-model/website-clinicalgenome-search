@extends('mail.layout.default')

@section('heading')
Change Notification
@endsection

@section('content')
Hello,<br/>
You are receiving this email because we detected genes you are following have curations which have changed as of {{ $date }}. A full report can be found by clicking the link below or accessing your ClinGen Dashboard.
<br />
<br />
	<a href="https://search.clingen.info/reports/view/{{ $report }}" class="button">View Report</a>
	<br /><br />
	<hr />
	<br />
	<div style="margin-bottom: 6px;"><strong>Changes have occured on the following genes:</strong></div>
	@foreach ($genes as $gene)
	&nbsp;&nbsp; {{ $gene }}<br/>
	@endforeach
	<br /><br />
	<strong>Manage your email preferences</strong><br />
	Manage the notifications to receive by going to <a href="https://search.clingen.info/dashboard/">your ClinGen Dashboard. Click here</a>

@endsection

@section('boilerplate')
	<strong>About ClinGen - Clinical Genome Resource</strong><br/>
ClinGen is a National Institutes of Health (NIH)-funded resource dedicated to building an authoritative central resource that defines the clinical relevance of genes and variants for use in precision medicine and research.
@endsection