<div class="col-md-3 col-xs-9 col-sm-8">
    <table class="mt-3 mb-4">
        <tr>
            <td class="valign-top"><img src="/images/adept-icon-circle-gene.png" width="40" height="40"></td>
            <td class="pl-2">
                <h1 class="h2 p-0 m-0">{{ $record->label }}</h1>
                @foreach ($vceps as $vcep)
                <a target='external' href="https://clinicalgenome.org/affiliation/{{ $vcep->href }}" class="badge-info badge pointer ml-2">VCEP <i class="fas fa-external-link-alt"></i></a>
                @endforeach
                <a class="btn btn-facts btn-outline-primary " role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    <i class="far fa-caret-square-down"></i> View Gene Facts
                </a>
            </td>
        </tr>
    </table>
</div>
