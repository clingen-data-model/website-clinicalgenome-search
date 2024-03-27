
<!-- Report Summary -->
<div class="row mt-2 ">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body bg-light">
        <h3 class="h4 mt-0 mb-1 border-bottom-2 border-info">Dosage Sensitivity Summary
          @if ($record->issue_type == "ISCA Gene Curation")
          (Gene)
          @else
          (Region)
          @endif
          <!--<span class="float-right">Curation Status:  {{ $record->issue_status }}</span>-->
        </h3>
        <div class="row pt-3">
          <div class="col-sm-3 text-right mt-3">Dosage ID:</div>
          <div class="col-sm-3 border-left-4 border-info bold mt-3">
            {{  $record->key }}
            <!--<div class="small"><a href="https://dosage.clinicalgenome.org/clingen_gene.cgi?sym={{ $record->genesymbol }}">View legacy report...</a></div>
            -->
            </div>
          <div class="col-sm-4">
            <div id="ideogram"> </div>
          </div>
        </div>
        @if (!empty($slug->alias))
        <div class="row pt-3">
          <div class="col-sm-3 text-right mt-3">ClinGen Curation ID:</div>
          <div class="col-sm-7 border-left-4 border-info bold mt-3">
                  {{ $slug->alias ?? '' }} <span data-toggle="tooltip" data-placement="top" title="The ClinGen Curation ID is an abbreviated link that you can use in documente.  Click on the button to the left to copy the link into your clipboard."><i class="fas fa-question-circle ml-1"></i></span>

                    <button type="button" class="btn action-ccid-copy ml-4" data-toggle="tooltip" data-placement="right" data-html="true" title="<h5>Copied!</h5>" data-trigger="click" data-clipboard-text="{{ url('/') . '/' . ($slug->alias ?? '') }}">
                        <i class="far fa-copy"></i> Copy <u>{{ url('/') . '/' . ($slug->alias ?? '') }}</u> to clipboard
                    </button>
            </div>
        </div>
        @endif
        <div class="row pb-2 pt-3">
          <div class="col-sm-3 text-right">Curation Status:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            @if ($record->resolution != "Complete")
            <span class="text-danger">
            @else
            <span class="text-success">
            @endif
            {{ $record->issue_status }}</span>
          </div>
        </div>
        <div class="row pb-2 pt-3">
          <div class="col-sm-3 text-right">Issue Type:</div>
          <div class="col-sm-9 border-left-4 border-info bold">Dosage Curation -
            @if ($record->issue_type == "ISCA Gene Curation")
            Gene
            @else
            Region
            @endif
          </div>
        </div>
        @if ($record->issue_type == "ISCA Region Curation" && !empty($record->description))
        <div class="row pb-2 pt-3">
          <div class="col-sm-3 text-right">Description:</div>
          <div class="col-sm-9 border-left-4 border-info bold prewrap" >{!! App\Jira::formatDescription($record->description) !!}</div>
        </div>
        @endif
        @if ($record->issue_type == "ISCA Region Curation" && !empty($record->breakpoint))
        <div class="row pb-2 pt-3">
          <div class="col-sm-3 text-right"><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Options: Recurrent or Variable"></i> Breakpoint Type:</div>
          <div class="col-sm-9 border-left-4 border-info bold" >{{ $record->breakpoint }}</div>
        </div>
        @endif
        <div class="row pb-2 pt-3">
          <div class="col-sm-3 text-right">Haploinsufficiency:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            @if ($record->resolution == "Complete")
            {{ $record->haplo_assertion }}
            @if ($record->haplo_score != -5)
            ({{ $record->haplo_score }})
            @endif
            <div class="small"><a href="#report_details_haploinsufficiency">Read full report...</a></div>
            @else
            {{ $record->issue_status }}
            @endif
          </div>
        </div>
        <div class="row pb-2 pt-3">
          <div class="col-sm-3 text-right">Triplosensitivity:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            @if ($record->resolution == "Complete")
            {{  $record->triplo_assertion }}
            @if ($record->triplo_score != -5)
             ({{ $record->triplo_score }})
             @endif
            <div class="small"><a href="#report_details_triplosensitivity">Read full report...</a></div>
            @else
            {{ $record->issue_status }}
            @endif
          </div>
        </div>
        @if ($record->issue_type == "ISCA Region Curation" && !empty($record->allele))
        <div class="row pb-2 pt-3">
          <div class="col-sm-3 text-right">gnomAD Allele Frequency:</div>
          <div class="col-sm-9 border-left-4 border-info bold" >{{ $record->allele }}</div>
        </div>
        @endif
        @if (!empty($record->reduced_penetrance))
        <div class="row pb-2 pt-3">
            <div class="col-sm-3 text-right">Assoc. with Reduced Penetrance:</div>
            <div class="col-sm-9 border-left-4 border-info bold">
              @if ($record->resolution == "Complete")
              {{  $record->reduced_penetrance->value ?? 'Not Yet Evaluated' }}
              @else
              {{ $record->issue_status }}
              @endif
                <div class="font-weight-normal mt-1">{{ $record->reduced_penetrance_comment }}</div>
            </div>
        </div>
        @endif
        @if ($record->issue_type == "ISCA Region Curation" && !empty($record->knownhits))
        <div class="row pb-2 pt-3">
          <div class="col-sm-3 text-right">Contains Known HI/TS Region:</div>
          <div class="col-sm-9 border-left-4 border-info bold" >{{ $record->knownhits }}</div>
        </div>
        @endif
        @if (!empty($record->links))
        <div class="row pb-2 pt-3">
          <div class="col-sm-3 text-right">Related Links:</div>
          <div class="col-sm-9 border-left-4 border-info bold" >
              @foreach ($record->links as $link)
                @if ($link->type == 2)
                <div><a href="/kb/gene-dosage/region/{{ $link->key }}">{{ $link->label }}</a></div>
                @elseif ($link->type == 1)
                <div><a href="/kb/gene-dosage/{{ $link->label }}">{{ $link->label }}</a></div>
                @endif
              @endforeach
          </div>
        </div>
        @endif
        <div class="row pb-2 pt-3">
          <div class="col-sm-3 text-right">Last Evaluated:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            @if ($record->resolution == "Complete")
            {{ $record->date }}<br />
            @else
            {{ $record->issue_status }}
            @endif
          </div>
        </div>
        <!--
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Genomic Coordinates:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            {{ $record->cytoband }}<br />
            <div>
            GRCh37/hg19 {{ $record->grch37 }}
            <a href="{{ $record->formatNcbi($record->grch37, $record->GRCh37_seqid) }}" class="badge-info badge pointer ml-2"><i class="fas fa-external-link-alt"></i>   NCBI</a>
            <a href="{{ $record->formatEnsembl($record->grch37) }}" class="badge-info badge pointer ml-1"><i class="fas fa-external-link-alt"></i>   Ensembl</a>
            <a href="{{ $record->formatUcsc19($record->grch37) }}" class="badge-info badge pointer ml-1"><i class="fas fa-external-link-alt"></i>   UCSC</a>
            </div><div class="pt-2">
            GRCh38/hg38 {{ $record->grch38 }}
            <a href="{{ $record->formatNcbi($record->grch38, $record->GRCh38_seqid) }}" class="badge-info badge pointer ml-2"><i class="fas fa-external-link-alt"></i>   NCBI</a>
            <a href="{{ $record->formatEnsembl($record->grch38) }}" class="badge-info badge pointer ml-1"><i class="fas fa-external-link-alt"></i>   Ensembl</a>
            <a href="{{ $record->formatUcsc38($record->grch38) }}" class="badge-info badge pointer ml-1"><i class="fas fa-external-link-alt"></i>   UCSC</a>
            </div>
          </div>
        </div>
      -->
        <!--
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Location Relationship:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            Contained
          </div>
        </div>-->
        <!--
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Morbid:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            OMIM:114480, OMIM:155255, OMIM:176807, OMIM:194070, OMIM:605724, OMIM:612555, OMIM:613029, OMIM:613347
          </div>
        </div>-->
        <!--
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">%HI index:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            <div class="text-danger">{{ $record->hi }}</div>
            <a href="http://gnomad.broadinstitute.org/faq">Read more about Haploinsufficiency Index</a>
          </div>
        </div>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Loss Intolerance (pLI):</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            <div class="text-success">{{ $record->pli }}</div>
            <a href="http://gnomad.broadinstitute.org/faq">Read more about Loss of Function (LoF) mutation score.</a>
          </div>
        </div>-->
      </div>
    </div>
  </div>
</div>
