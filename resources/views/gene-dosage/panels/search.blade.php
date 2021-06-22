    <div class='ml-5' style='float:left; width:300px;'>
        <form id="dosage-search-form" class="input-group" method="post" action="/kb/gene-dosage/region_search?rref=g{{ Str::random(8) }}">
            @csrf
        <div class="input-group-btn input-btn-region-prefix">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="action-select-text">{{  $type ?? 'GRCh37' }}</span> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="#" class="action-select-grch" data-uuid="GRCh37">GRCh37</a></li>
                  <li><a href="#" class="action-select-grch" data-uuid="GRCh38">GRCh38</a></li>
                </ul>
                <input id="select-gchr" type="hidden" name="type" value="{{  $type ?? 'GRCh37' }}">
        </div>
        <input class="form-control search-input" name="region" type="search" placeholder="Enter cytoband or genomic coordinates" autocomplete="off">
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
        </span>
    </form>
    </div>
