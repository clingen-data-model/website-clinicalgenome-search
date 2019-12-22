<div>
    
	   <div id="section_search_wrapper" class="input-group input-group-xl">

	         

	         <span class="input-group-addon" id=""><i class="fas fa-search"></i></span>
	         <input type="text" class="form-control" aria-label="..." wire:model="searchBarQuery"  value="{{ $searchBarQuery }}" placeholder="Type in a query...">
	         <div class="input-group-btn">
	           <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Gene query...</button>
	           <ul class="dropdown-menu dropdown-menu-left">
	             <li><a href="#">Gene Symbol</a></li>
	             <li><a href="#">Disease Name</a></li>
	             <li><a href="#">HGVS Expression</a></li>
	             <li><a href="#">Genomic Coordinates</a></li>
	             <li><a href="#">CAid (Variant)</a></li>
	             <li role="separator" class="divider"></li>
	             <li><a href="#">Website Content</a></li>
	           </ul>
	         </div><!-- /btn-group -->
	         <span class="input-group-btn">
	                 <button class="btn btn-default btn-search-submit" type="button"> Search</button>
	               </span>
	       </div><!-- /input-group -->


		@if(count($queryResults))
	        <div class="" style="position: absolute; z-index: 10000;" >
	        	<div class="container">
	        <div class="row  ml-1 mr-5" style="box-shadow: 0px 5px 5px rgba(0, 0, 0, .5);">
	        	@foreach($queryResults as $result)
	          <a href='{{$result['href']}}' class="list-group-item d-flex justify-content-between align-items-center">
	            {{$result['label']}}
	            @if($result['curated'])
	            	<span class="badge badge-success badge-pill"><i class="fas fa-check"></i> ClinGen Curations</span>
	            @else
	            	<span class="badge badge-light badge-pill text-muted"> No curations</span>
	            @endif
	          </a>
	          @endforeach
	          </div>
	        </div>
	      </div>
	  
	  @endif

	  <small class="pl-2 ml-5 text-white-light"><strong>Supported Queries:</strong> Gene Symbol, Disease (MONDO, OMIM, DOID), HGVS, Genomic Coordinate, CAid, PMID, Full Text (Beta)</small>

</div>
