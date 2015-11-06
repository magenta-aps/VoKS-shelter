@foreach($generalMenu as $item)
    <a href="{{ url($item['path']) }}" class="button -gray @if($item['current']) -active @endif">
        <span class="-text">{{ Lang::get($item['title']) }}</span>
    </a>
@endforeach