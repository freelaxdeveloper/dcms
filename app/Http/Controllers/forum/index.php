<?php

include_once '../sys/inc/start.php';
use App\{document,cache_counters,listing,pages,text};
use App\Models\{ForumCategory,ForumTheme,ForumMessage};
use Carbon\Carbon;
use App\App\App;

$doc = new document();
$doc->title = __('Форум - Категории');

$listing = new listing();

$post = $listing->post();
$post->url = 'search.php';
$post->title = __('Поиск');
$post->icon('forum.search');

if (false === ($new_themes = cache_counters::get('forum.new_themes.' . App::user()->group))) {
    $new_themes = ForumTheme::group()
    ->where('created_at', '>', Carbon::now()->subDay(1)->toDateTimeString())
    ->count();
    cache_counters::set('forum.new_themes.' . App::user()->group, $new_themes, 20);
}
$post = $listing->post();
$post->url = 'last.themes.php';
$post->title = __('Новые темы');
if ($new_themes) {
    $post->counter = '+' . $new_themes;
}
$post->icon('forum.lt');

if (false === ($new_posts = cache_counters::get('forum.new_posts.' . App::user()->group))) {
    $new_posts = ForumMessage::group()
    ->where('created_at', '>', Carbon::now()->subDay(1)->toDateTimeString())
    ->count();
    cache_counters::set('forum.new_posts.' . App::user()->group, $new_posts, 20);
}

$post = $listing->post();
$post->url = 'last.posts.php';
$post->title = __('Обновленные темы');
if ($new_posts) {
    $post->counter = '+' . $new_posts;
}
$post->icon('forum.lp');


if (App::user()->group) {
    if (false === ($my_themes = cache_counters::get('forum.my_themes.' . App::user()->id))) {
        # количество тем у которых есть новые сообщения не от автора
         $my_themes = ForumTheme::group()->where([
            ['updated_at', '>', Carbon::now()->subDay(1)->toDateTimeString()],
            ['id_last', '<>', App::user()->id],
            ['id_autor', App::user()->id],
        ])->count();
        cache_counters::set('forum.my_themes.' . App::user()->id, $my_themes, 20);
    }


    $post = $listing->post();
    $post->url = 'my.themes.php';
    $post->title = __('Мои темы');
    if ($my_themes) {
        $post->counter = '+' . $my_themes;
    }
    $post->icon('forum.my_themes');
}

$pages = new pages();
$pages->posts = ForumCategory::group()->count();

$listing->display(__('Доступных Вам категорий нет'));

$categories = ForumCategory::group()->get()->forPage($pages->this_page, App::user()->items_per_page);
view('forum.category', compact('categories'));

$pages->display('?'); // вывод страниц

if (App::user()->group >= 5) {
    $doc->act(__('Создать категорию'), 'category.new.php');
    $doc->act(__('Порядок категорий'), 'categories.sort.php');
    $doc->act(__('Статистика'), 'stat.php');
}