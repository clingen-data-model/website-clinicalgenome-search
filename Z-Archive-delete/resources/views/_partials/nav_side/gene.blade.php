<div class="col-md-2 small">
    <ul class="list-group small text-muted">
      <a href="{{ route('gene-show') }}" class="list-group-item @if ($navActive == "show") active @endif"><i class="fas fa-file"></i> Gene Summary</a>
      <a href="{{ route('dosage-show') }}" class="list-group-item @if ($navActive == "dosage") active @endif">Dosage Sensitivity Report
      </a>
      <a href="#" class="list-group-item @if ($navActive == "stats") active @endif">Gene-Disease Validity Report</a>
      <a href="#" class="list-group-item @if ($navActive == "stats") active @endif">Genomic Browser</a>
      <li class="list-group-item"><strong class="text-muted">Additional Info</strong>
        <div class=""><a href="#"><i class="fas fa-comment pr-1"></i> Send Feedback</a></div>
      </li>
    </ul>
</div>