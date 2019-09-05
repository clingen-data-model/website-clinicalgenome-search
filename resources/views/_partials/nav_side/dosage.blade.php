<div class="col-md-2 small">
    <ul class="list-group small text-muted">
      <a href="{{ route('dosage-index') }}" class="list-group-item @if ($navActive == "index") active @endif"><i class="fas fa-check-circle"></i> Dosage Sensitivity Curations
      </a>
      <a href="{{ route('dosage-reports') }}" class="list-group-item @if ($navActive == "reports") active @endif"><i class="fas fa-file"></i> Reports</a>
      <a href="{{ route('dosage-stats') }}" class="list-group-item @if ($navActive == "stats") active @endif"><i class="fas fa-chart-pie"></i> Statistics</a>
      <a href="{{ route('dosage-download') }}" class="list-group-item @if ($navActive == "download") active @endif">
        <i class="fas fa-cloud-download-alt"></i> Download (FTP)
        <div class="ml-1 pl-3 small"><span class="small">Updated: {{ date('Y-m-d') }}</div>
      </a>
      <li class="list-group-item"><strong class="text-muted">Additional Info</strong>
        <div class=""><a href="#"><i class="fas fa-users"></i> Working Group</a></div>
        <div class=""><a href="#"><i class="fas fa-users"></i> Members</a></div>
        <div class=""><a href="#"><i class="fas fa-file-alt pl-1 pr-1"></i> Procedures</a></div>
        <div class=""><a href="#"><i class="fas fa-comment pr-1"></i> Send Feedback</a></div>
      </li>
    </ul>
</div>