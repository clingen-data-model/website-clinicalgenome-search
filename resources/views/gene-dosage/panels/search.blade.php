    <div class='input-group ml-5' style='float:left; width:300px;'>
        <form class="input-group" method="post" action="/gene-dosage/region_search">
            @csrf
        <div class="input-group-btn">
                <input id="select-gchr" type="hidden" name="type" value="{{  $type ?? 'GRCh37' }}">
                <button type="button" class="btn btn-default dropdown-toggle bg-info text-white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="action-select-text">{{  $type ?? 'GRCh37' }}</span> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="#" class="action-select-grch" data-uuid="GRCh37">GRCh37</a></li>
                  <li><a href="#" class="action-select-grch" data-uuid="GRCh38">GRCh38</a></li>
                </ul>
        </div>
        <input class="form-control search-input" name="region" type="search" placeholder="Enter cytoband or genomic coordinates" autocomplete="off">
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Go!</button>
        </span>
    </form>
    </div>
