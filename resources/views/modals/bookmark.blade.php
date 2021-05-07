<div class="modal" id="modalBookmark" tabindex="-1" role="dialog" aria-labelledby="modalBookmarkTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalBookmarkTitle">Manage Bookmarks</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
					  <form class="form-horizontal" role="form">
						@csrf
						<fieldset>
				
						  <!-- Text input-->
						  <div class="form-group">
							<label class="col-sm-3 control-label" for="textinput">Name:</label>
							<div class="col-sm-8">
								<select name="bookmark" class="bookmark-modal-select">
									@foreach ($bookmarks as $bookmark)
									<option value="{{ $bookmark->ident }}" {{ $bookmark->default ? 'selected' : '' }}>{{ $bookmark->name }}</option>
									@endforeach
								</select>
							</div>
						  </div>
				
						  <!-- Text input-->
						  <div class="form-group">
							<label class="col-sm-3 control-label" for="textinput">Page:</label>
							<div class="col-sm-8">
								<input type="hidden" name="screen" value="{{ $display_tabs['scrid'] }}">
							  <input type="text" name="page" placeholder="Screen Page" value="{{ $display_tabs['display'] }}" class="form-control" disabled>
							</div>
						  </div>

						</fieldset>
					  </form>
					</div><!-- /.col-lg-12 -->
				</div><!-- /.row -->
			</div>
			<div class="modal-footer">
				<!--<button type="submit" class="btn btn-primary mr-auto">{{ __('Submit') }}
				</button> -->
				<button type="button" class="btn btn-danger float-left action-remove-bookmark">
					Remove
				</button>
				<button type="button" class="btn btn-primary action-restore-bookmark">
					Restore
				</button>
				<button type="button" class="btn btn-primary action-default-bookmark">
					Default
				</button>
				<button type="button" class="btn btn-success action-save-bookmark">
					Save
				</button>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
					Close
				</button>
			</div>
		</div>
	</div>
</div>
