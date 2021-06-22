<div class="modal" id="modalBookmark" tabindex="-1" role="dialog" aria-labelledby="modalBookmarkTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
            <div class="modal-header section-heading-groups">
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title text-white" id="modalBookmarkTitle">Manage Page Preferences</h3>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
					  <form class="form-horizontal" role="form">
						@csrf
						<fieldset>

                            <!-- Text input-->
						  <div class="form-group">
							<h5 class="col-sm-11"><b>{{ $display_tabs['display'] ?? 'Current' }} Page</b></h5>
						  </div>

                          <!-- Text input-->
						  <div class="form-group">
							<label class="col-sm-6 control-label" for="textinput">Current Selected Preference:</label>
							<div class="col-sm-5 pt-2">
                                <span id="modal-current-bookmark">{{ empty($currentbookmark) ? 'No Preferences Selected' : $currentbookmark->name}}</span>
                            </div>
						  </div>

                          <hr />

                          <p>Manage all your preferences for this page my selecting from the "Choose Preference" list, select an action, then click on "Go!".
                          </p>
                            <div class="row">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Choose Preference </button>
                                        <ul id="preferences-menu" class="dropdown-menu dropdown-menu-right">
                                            @foreach ($bookmarks as $bookmark)
                                            @if ($bookmark->default == 1)
                                            <li ><a href="#" data-uuid="{{ $bookmark->ident }}" data-name="{{ $bookmark->name }}" class="bookmark-select-preference"><i class="fas fa-asterisk"></i>  {{ $bookmark->name }}</a></li>
                                            @elseif ($currentbookmark != null && $bookmark->ident == $currentbookmark->ident)
                                            <li ><a href="#" data-uuid="{{ $bookmark->ident }}" data-name="{{ $bookmark->name }}" class="bookmark-select-preference"><i class="fas fa-check"></i>  {{ $bookmark->name  }}</a></li>
                                            @else
                                            <li ><a href="#" data-uuid="{{ $bookmark->ident }}" data-name="{{ $bookmark->name }}" class="bookmark-select-preference"><i class="fas fa-asterisk fa-blank"></i>  {{ $bookmark->name  }}</a></li>
                                            @endif
                                            @endforeach
                                        </ul>
                                      </div><!-- /btn-group -->
                                      <input id="bookmark-selected-preference" type="text" class="form-control" aria-label="" value="" readonly>
                                  <div class="input-group-btn">
                                    <button type="button" class="rounded-0 btn btn-default dropdown-toggle bookmark-action-select" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="button-selected-action">Action </span> </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                      <li><a href="#" class="bookmark-modal-select action-restore-bookmark" data-action="select"><i class="fas fa-check"></i>  Select </a></li>
                                      <li><a href="#" class="bookmark-modal-select action-default-bookmark" data-action="default"><i class="fas fa-asterisk"></i>  Make Default </a></li>
                                      <li><a href="#" class="bookmark-modal-select action-update-bookmark" data-action="update"><i class="far fa-edit"></i>  Update </a></li>
                                      <li role="separator" class="divider"></li>
                                      <li><a href="#" class="bookmark-modal-select action-remove-bookmark" data-action="remove"><i class="far fa-trash-alt"></i>  Delete </a></li>
                                    </ul>
                                  </div><!-- /btn-group -->
                                  <div class="input-group-btn">
                                  <button type="button" class="btn btn-primary bookmark-action-go">Go!</button>
                                  </div>
                                </div><!-- /input-group -->
                              </div><!-- /.col-sm-10 -->
                              {{-- <div class="col-sm-1 pl-0">
                                <button type="button" class="btn btn-primary rounded-0 bookmark-action-go">Go!</button>
                              </div> --}}
                            </div><!-- /.row -->

                          <hr />

                        <p>To create a new preference comprised of the current settings, first enter a unique name, then click on "Go!".</p>
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon2">Preference Name:</span>
                            <input type="text" class="form-control" id="modal-new-bookmark" name="newbookmark" placeholder="Enter a name" aria-describedby="sizing-addon2">
                            <span class="input-group-btn">
                                <button class="btn btn-primary action-save-bookmark" type="button">Go!</button>
                              </span>
                          </div>

                </fieldset>
					  </form>
					</div><!-- /.col-lg-12 -->
				</div><!-- /.row -->
			</div>
            <div class="modal-footer">
                <h5 class="text-center"><span id="modal-bookmark-status" class="text-info"></span></h5>
			</div>
		</div>
	</div>
</div>
