<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostBlock extends Model
{
    protected $fillable = ['post_id', 'type', 'content', 'order'];

    protected $casts = [
        'content' => 'array'
    ];

    public function getStyleAttribute()
    {
        $content = $this->content;
        $bg_color = $content['bg_color'] ?? null;
        $bg_opacity = $content['bg_opacity'] ?? 1;
        $bg_image = $content['bg_image'] ?? null;
        $text_color = $content['text_color'] ?? null;
        $font_size = $content['font_size'] ?? null;
        $font_family = $content['font_family'] ?? null;
        $padding_top = $content['padding_top'] ?? null;
        $padding_bottom = $content['padding_y'] ?? null;
        $padding_left = $content['padding_left'] ?? null;
        $padding_right = $content['padding_right'] ?? null;

        $blockStyles = [];

        if ($bg_color) $blockStyles[] = "background-color: {$bg_color}";
        if ($text_color) $blockStyles[] = "color: {$text_color}";
        if ($font_family) $blockStyles[] = "font-family: {$font_family}";
        if ($font_size) $blockStyles[] = "font-size: {$font_size}px";
        if ($padding_top !== null && $padding_top !== '') $blockStyles[] = "padding-top: {$padding_top}px";
        if ($padding_bottom !== null && $padding_bottom !== '') $blockStyles[] = "padding-bottom: {$padding_bottom}px";
        if ($padding_left !== null && $padding_left !== '') $blockStyles[] = "padding-left: {$padding_left}px";
        if ($padding_right !== null && $padding_right !== '') $blockStyles[] = "padding-right: {$padding_right}px";

        if ($bg_image) {
            if ($bg_color && strpos($bg_color, '#') === 0 && strlen($bg_color) == 7) {
                [$r, $g, $b] = sscanf($bg_color, '#%02x%02x%02x');
                $overlay_opacity = 1 - $bg_opacity;
                $overlay = "rgba($r, $g, $b, $overlay_opacity)";
                $blockStyles[] = "background-image: linear-gradient($overlay, $overlay), url('{$bg_image}')";
            } else {
                $blockStyles[] = "background-image: url('{$bg_image}')";
                if ($bg_opacity < 1) {
                    $blockStyles[] = "opacity: {$bg_opacity}";
                }
            }
            $blockStyles[] = 'background-size: cover';
            $blockStyles[] = 'background-position: center';
        }

        return implode('; ', $blockStyles);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
