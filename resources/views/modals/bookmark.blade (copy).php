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
                                <span id="modal-current-bookmark">{{ empty($bookmark) ? 'No Preferences Selected' : $bookmark}}</span>
                            </div>
						  </div>

                          <hr />

                          Manage all you preferences for this page my selecting the preference, then selectiong the action, then click on "Go".
                          <div class="row">
                            <div class="col-sm-11">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">My Preferences <span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            @foreach ($bookmarks as $bookmark)
                                            <li class="bg-primary"><a href="#" data-uuid="{{ $bookmark->ident }}" class="bookmark-select-preference">{!! $bookmark->default ? '* ' : '&nbsp&nbsp' !!}{{ $bookmark->name }}</a></li>
                                            @endforeach
                                        </ul>
                                      </div><!-- /btn-group -->
                                      <input id="bookmark-selected-preference" type="text" class="form-control" aria-label="" value="">
                                  <div class="input-group-btn">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                      <li><a href="#" class="action-restore-bookmark"><i class="far fa-check-circle"></i>  Select</a></li>
                                      <li><a href="#" class="bookmark-modal-select">Make Default</a></li>
                                      <li><a href="#" class="bookmark-modal-select"><i class="far fa-edit"></i>  Update</a></li>
                                      <li role="separator" class="divider"></li>
                                      <li><a href="#" class="bookmark-modal-select"><i class="far fa-trash-alt"></i>  Delete</a></li>
                                    </ul>
                                    <button type="button" class="btn btn-default">Go</button>

                                  </div><!-- /btn-group -->
                                </div><!-- /input-group -->
                              </div><!-- /.col-lg-6 -->
                            </div><!-- /.row -->

						  <!-- Text input-->
						  <div class="form-group">
							<label class="col-sm-4 control-label" for="textinput">Saved Preferences:</label>
							<div class="col-sm-5 pt-1">
								<select name="bookmark" class="bookmark-modal-select">
									@foreach ($bookmarks as $bookmark)
									<option value="{{ $bookmark->ident }}" {{ $bookmark->default ? 'selected' : '' }}>{{ $bookmark->name }}</option>
									@endforeach
								</select>
							</div>
                            <div class="col-sm-2 pt-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary action-restore-bookmark">
                                    Select
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary action-default-bookmark">
                                    Make Default
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary action-update-bookmark">
                                    Update
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary action-remove-bookmark">
                                    Remove
                                </button>
                            </div>
						  </div>

                          <hr />

                        <p>Create a new preference with the current settings by entering a new name and click on Go.</p>
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon2">Create Preference</span>
                            <input type="text" class="form-control" id="modal-new-bookmark" name="newbookmark" placeholder="Enter a name" aria-describedby="sizing-addon2">
                            <span class="input-group-btn">
                                <button class="btn btn-default action-save-bookmark" type="button">Go!</button>
                              </span>
                          </div>

                </fieldset>
					  </form>
					</div><!-- /.col-lg-12 -->
				</div><!-- /.row -->
			</div>
            <div class="modal-footer">
				<!--<button type="submit" class="btn btn-primary mr-auto">{{ __('Submit') }}
				</button> -->
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
				Close
				</button>
			</div>
		</div>
	</div>
</div>
