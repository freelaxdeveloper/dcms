<?php
namespace Dcms\Models;

use Illuminate\Database\Eloquent\Model;
use Dcms\Models\{User,ForumTopic,ForumCategory,ForumMessage,ForumView};
use App\App\App;
use Carbon\Carbon;

class ForumTheme extends Model{
    protected $guarded = ['id'];

    /**
     * автор темы
     */
    public function autor()
    {
        return $this->hasOne(User::class, 'id', 'id_autor');
    }
    /**
     * пользователь оставивший последнее сообщение
     */
    public function lastUser()
    {
        return $this->hasOne(User::class, 'id', 'id_last');
    }
    /**
     * топик темы
     */
    public function topic()
    {
        return $this->hasOne(ForumTopic::class, 'id', 'id_topic');
    }
    /**
     * категория темы
     */
    public function category()
    {
        return $this->hasOne(ForumCategory::class, 'id', 'id_category');
    }
    /**
     * голосование
     */
    public function vote()
    {
        return $this->hasOne(ForumVote::class, 'id', 'id_vote');
    }
    /**
     * сообщения темы
     */
    public function messages()
    {
        return $this->hasMany(ForumMessage::class, 'id_theme');
    }
    /**
     * просмотры
     */
    public function views()
    {
        return $this->hasMany(ForumView::class, 'id_theme');
    }

   /*  public function views()
    {
        return $this->belongsToMany(ForumView::class, 'forum_views', 'id_theme', 'id_user');
    } */

    /**
     * данные пользователей написавших первое и последнее сообщение
     */
    public function getLastUsersAttribute(){
        return ($this->autor->id != $this->lastUser->id ? $this->autor->login . '/' . $this->lastUser->login : $this->autor->login);
    }
    /**
     * иконка темы
     */
    public function getIconAttribute(){
        $is_open = (int)($this->attributes['group_write'] <= $this->topic->group_write);
        return "forum.theme.{$this->attributes['top']}.{$is_open}";
    }
    /**
     * доступность темы ползователю
     */
    public function scopeGroup($query)
    {
        return $query->where('group_show', '<=', App::user()->group)
            ->whereHas('topic', function ($query) {
                $query->group();
            });
    }
    /**
     * темы с новыми сообщениями, с проверкой на доступность группы чтения
     */
    public function scopeLastPosts($query)
    {
        return $query->group()->where('updated_at', '>', Carbon::now()->subDay(7)->toDateTimeString())
            ->whereHas('topic', function ($query) {
                $query->where('theme_view', '1');
            });
    }
    /**
     * обновленные темы
     */
    public function scopeLastThemes($query)
    {
        return $query->group()->where('updated_at', '>', Carbon::now()->subDay(7)->toDateTimeString())
            ->whereHas('topic', function ($query) {
                $query->where('theme_view', '1');
            });
    }
}