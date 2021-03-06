<!DOCTYPE html>
<html lang="en" ng-app="Dcms">
<head>
    <link rel="shortcut icon" href="/favicon.ico"/>

    <link rel="stylesheet" href="{{ elixir('default/css/core.css') }}" type="text/css"/>

    <script charset="utf-8" src="{{ elixir('default/js/core.js') }}" type="text/javascript"></script>
    <script charset="utf-8" src="{{ elixir('default/js/highcharts.js') }}" type="text/javascript"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', $title)</title>

    <script>
        user = {!! $current_user !!};
        translates = {
            bbcode_b: @__('\'Текст жирным шрифтом\''),
            bbcode_i: @__('\'Текст курсивом\''),
            bbcode_u: @__('\'Подчеркнутый текст\''),
            bbcode_img: @__('\'Вставка изображения\''),
            bbcode_php: @__('\'Выделение PHP-кода\''),
            bbcode_big: @__('\'Увеличенный размер шрифта\''),
            bbcode_small: @__('\'Уменьшенный размер шрифта\''),
            bbcode_gradient: @__('\'Цветовой градиент\''),
            bbcode_hide: @__('\'Скрытый текст\''),
            bbcode_spoiler: @__('\'Свернутый текст\''),
            smiles: @__('\'Смайлы\''),
            form_submit_error: @__('\'Ошибка связи...\''),
            auth: @__('\'Авторизация\''),
            reg: @__('\'Регистрация\''),
            friends: @__('\'Друзья\''),
            mail: @__('\'Почта\''),
            error: @__('\'Неизвестная ошибка\''),
            rating_down_message: @__('\'Подтвердите понижение рейтинга сообщения.\nБудет списано баллов: 50\'')
        };
        codes = [
            {Text: 'B', Title: translates.bbcode_b, Prepend: '[b]', Append: '[/b]'},
            {Text: 'I', Title: translates.bbcode_i, Prepend: '[i]', Append: '[/i]'},
            {Text: 'U', Title: translates.bbcode_u, Prepend: '[u]', Append: '[/u]'},
            {Text: 'BIG', Title: translates.bbcode_big, Prepend: '[big]', Append: '[/big]'},
            {Text: 'Small', Title: translates.bbcode_small, Prepend: '[small]', Append: '[/small]'},
            {Text: 'IMG', Title: translates.bbcode_img, Prepend: '[img]', Append: '[/img]'},
            {Text: 'PHP', Title: translates.bbcode_php, Prepend: '[php]', Append: '[/php]'},
            {Text: 'SPOILER', Title: translates.bbcode_spoiler, Prepend: '[spoiler title=""]', Append: '[/spoiler]'},
            {Text: 'HIDE', Title: translates.bbcode_hide, Prepend: '[hide group="1" balls="1"]', Append: '[/hide]'}
        ];
    </script>
    <style type="text/css">
        .ng-hide {
            display: none !important;
        }
    </style>
</head>
<body class="theme_light_full theme_light" ng-controller="DcmsCtrl">
    <div id="main">
        <div id="top_part">
            <div id="header">
                <div class="body_width_limit clearfix">
                    <h1 id="title">{{$title}}</h1>

                    <div id="navigation" class="clearfix @if ( $is_main ) ng-hide @endif">
                        <a class="nav_item" href='/'>@__('Главная')</a>
                         @foreach ($returns as $link)
                            <a class="nav_item" href="{{$link->url}}">{{$link->name}}</a>
                         @endforeach
                        <span class="nav_item">{{$title}}</span>
                    </div>
                    <div id="tabs" class=" !$tabs ? 'ng-hide' : '' ?>">
                        @foreach ($tabs as $link)
                            <a class="tab sel{{$link->selected}}" href="{{$link->url}}">{{$link->name}}</a>
                        @endforeach
                    </div>
                </div>
                <div id="navigation_user">
                    <div class="body_width_limit clearfix">
                        <a ng-show="+user.group" class="@if ( !$user->group )ng-hide @endif"
                           href="{{route('user:menu')}}" ng-bind="user.login">{{$user->login}}</a>
                        <a ng-show="+user.friend_new_count" class='ng-hide'
                           href='/my.friends.php' ng-bind="str.friends">@__('Друзья')</a>
                        <a ng-show="+user.mail_new_count" class='ng-hide'
                           href='/my.mail.php?only_unreaded' ng-bind="str.mail">@__('Почта')</a>
                        <a ng-hide="+user.group" class="ng-hide"
                           href="{{route('auth:login')}}" ng-bind="translates.auth">@__('Авторизаци')</a>
                        <a ng-hide="+user.group" class="ng-hide"
                           href="{{route('auth:register')}}" ng-bind="translates.reg">@__('Регистрация')</a>
    
                        @foreach ($actions as $link)
                           <a class="action" href="{{$link->url}}">{{$link->name}}</a>
                        @endforeach
                    </div>
                </div>
                 {{$header}}
            </div>
            <div class="body_width_limit clearfix">
                <div id="left_column">
                    @section('left_column')
                        {!! $left_column !!}
                    @show
                </div>
                <div id="content">
                    <div id="messages">
                         @foreach($errors as $error)
                            <div class="error">{{$error->text}}</div>
                         @endforeach
                         @foreach($messages as $message)
                            <div class="info">{{$message->text}}</div>
                         @endforeach
                    </div>
                    @yield('content')
                </div>
            </div>
            <div id="empty"></div>
        </div>
        <div id="footer">
            <div class="body_width_limit">
                        <span id="copyright">
                             @toOutput($dcms->copyright)
                        </span>
                        <span id="language">
                             @__('Язык'):<a href='/language.php?return={{URL}}'
                                                 style='background-image: url({{$lang->icon}}); background-repeat: no-repeat; background-position: 5px 2px; padding-left: 23px;'> {{$lang->name}}</a>
                        </span>
                        <span id="generation">
                             @__('Время генерации страницы: %s сек', $document_generation_time)
                        </span>
            </div>
        </div>
    </div>
</body>
</html>