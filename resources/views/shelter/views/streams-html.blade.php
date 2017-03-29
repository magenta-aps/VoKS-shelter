<div id="streams-view" class="container__content streams">
    <div class="streams__unit" stream position="1" clients="clients" client="clients | filter:position(1) | limitTo: 1 | findFirst">
        <app-stream></app-stream>
    </div>
    <div class="streams__unit" stream position="2" clients="clients" client="clients | filter:position(2) | limitTo: 1 | findFirst">
        <app-stream></app-stream>
    </div>
    <div class="streams__unit" stream position="3" clients="clients" client="clients | filter:position(3) | limitTo: 1 | findFirst">
        <app-stream></app-stream>
    </div>
    <div class="streams__unit" stream position="4" clients="clients" client="clients | filter:position(4) | limitTo: 1 | findFirst">
        <app-stream></app-stream>
    </div>
    <div class="streams__unit" stream position="5" clients="clients" client="clients | filter:position(5) | limitTo: 1 | findFirst">
        <app-stream></app-stream>
    </div>
    <div class="streams__unit" stream position="6" clients="clients" client="clients | filter:position(6) | limitTo: 1 | findFirst">
        <app-stream></app-stream>
    </div>
    <div class="streams__unit" stream position="7" clients="clients" client="clients | filter:position(7) | limitTo: 1 | findFirst">
        <app-stream></app-stream>
    </div>
    <div class="streams__unit">
        <div class="streams__item">
            @if ( config('aruba.ale.coordinatesEnabled') )
		        <map id="streams"></map>
			@else
				<bc-map></bc-map>
			@endif
        </div>
    </div>
</div>