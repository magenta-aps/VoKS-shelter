<header class="header">
    <div class="header__container">
        <div class="table-style__table">
            <div class="table-style__cell">
                <a href="/system/general" class="header__logo" title="{{ Lang::get('header.logo.title') }}">
                    <img src="/images/logo.png" alt="{{ Lang::get('header.logo.title') }}" />
                </a>
                <ul class="menu -header">
                    @foreach($nav as $item)
                        <li class="menu__unit">
                            <a href="{{ url('/'.$item['path']) }}" class="menu__item icons @if($item['current']) -active @endif"><span class="-title"> {{ Lang::get($item['title']) }}</span></a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="table-style__cell">
            </div>

            <div class="table-style__cell header__is-logout">
                <a href="{{url('/auth/logout')}}" title="Log out" class="header__button icons -logout">
                    <span class="-icon"></span>
                </a>
            </div>
        </div>
    </div>
</header>