<header id="navigation">
    <div class="container">
        <div class="nav mt-3 mb-3 row navbar">
            <div class="logo col-md-2"></div>
            <nav class="col-md-8">
                <a href="{{ route('home') }}">Главная</a>
                <a href="#">О площадке</a>
                <a href="#">Работа</a>
                <a href="#">Картины</a>
                <a href="{{ route('genres') }}">Жанры</a>
                @if(auth()->user())
                    <a href="{{ route('logout') }}">Выйти</a>
                    <a href="{{ route('profile') }}">Личный кабинет</a>
                @else
                    <a href="{{ route('user.create') }}">Регистрация</a>
                    <a href="{{ route('user.login') }}">Войти</a>
                @endif
            </nav>
        </div>
    </div>
</header>

