@foreach($adminMenu as $item)
    <a href="{{ url($item['path']) }}" class="button -gray @if($item['current']) -active @endif">
        <span class="-text">{{ Lang::get($item['title']) }}</span>
    </a>
@endforeach
<a reset-shelter="{{ $id }}" class="button -gray">
    <span class="-text">{{ Lang::get('admin.tabs.reset') }}</span>
</a>