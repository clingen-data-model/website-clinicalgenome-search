<div class="modal" id="modalFollowEp" tabindex="-1" role="dialog" aria-labelledby="modalFollowEpTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
            <div class="modal-header section-heading-groups">
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
                <div class="btn-group float-right mr-4" role="group" aria-label="...">
                    <button type="button" class="btn btn-default active action-show-gcep">Show GCEPs</button>
                    <button type="button" class="btn btn-default action-show-vcep">Show VCEPs</button>
                  </div>
				<h3 class="modal-title text-white" id="modalFollowEpTitle">Follow Expert Panels</h3>
			</div>
			<form id="follow_ep_form" method="POST" action="" class="form-horizontal">
				@csrf
				<div class="modal-body">
                    <div class="row">
                        <div class="col-12 gcep-content">
                            <ul class="ep-list list-unstyled">
                                @foreach ($gceps as $gcep)
                                <li>

                                    <input name="select[]" type="checkbox" value="{{ $gcep->ident }}">
                                    <span>{{ $gcep->name }}</span>

                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-12 vcep-content display-hide">
                            <ul class="ep-list list-unstyled">
                                @foreach ($vceps as $vcep)
                                <li>

                                    <input name="select[]" type="checkbox" value="{{ $vcep->ident }}">
                                    <span>{{ $vcep->name }}</span>

                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
