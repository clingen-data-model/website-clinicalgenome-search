@extends('mail.layout.default')

@section('heading')
Changes Notification
@endsection

@section('content')

Hello,<br/>
You are receiving this email because we detected genes you are following have curations which have changed as of XXXXXXXXX . A full reports can be found by clicking the link below or accessing your dashboard.
<br />
<br />
	<a href="https://search.clingen.info/reports/view/{{ $report }}" class="button">View Report</a>
	<br />
	<hr />
	<strong>Changes have occured on the following genes:</strong><br/>
	@foreach ($genes as $gene)
	<p>{{ $gene }}</p>
	<br>
	<a href="https://search.clingen.info/reports/view/{{ $report }}">Click here to view the entire report.</a>
@endsection

@section('boilerplate')
	<strong>About ClinGen - Clinical Genome Resource</strong><br/>
ClinGen is a National Institutes of Health (NIH)-funded resource dedicated to building an authoritative central resource that defines the clinical relevance of genes and variants for use in precision medicine and research.
@endsection