@php
    $bg_color = $content['bg_color'] ?? null;
    $bg_opacity = $content['bg_opacity'] ?? 1;
    $bg_image = $content['bg_image'] ?? null;
    $text_color = $content['text_color'] ?? null;
    $font_size = $content['font_size'] ?? null;
    $font_family = $content['font_family'] ?? null;
    // Padding
    $padding_top = $content['padding_top'] ?? null;
    $padding_bottom = $content['padding_y'] ?? null;
    $padding_left = $content['padding_left'] ?? null;
    $padding_right = $content['padding_right'] ?? null;

    $blockStyles = [];

    // Tính toán mã màu nền + Opacity (nếu có)
    $colorStr = null;
    if ($bg_color) {
        if (strpos($bg_color, '#') === 0 && strlen($bg_color) == 7) {
            list($r, $g, $b) = sscanf($bg_color, "#%02x%02x%02x");
            $colorStr = "rgba($r, $g, $b, $bg_opacity)";
        } else {
            $colorStr = $bg_color;
        }
    }

    // Xử lý logic gộp Màu nền Overlay và Ảnh nền
    if ($bg_image && $colorStr) {
        // Nếu có cả màu nền và ảnh -> Màu nền sẽ làm lớp phủ (overlay) chèn lên ảnh
        $blockStyles[] = "background-image: linear-gradient({$colorStr}, {$colorStr}), url('{$bg_image}')";
        $blockStyles[] = "background-size: cover";
        $blockStyles[] = "background-position: center";
    } elseif ($bg_image) {
        // Chỉ có ảnh nền
        $blockStyles[] = "background-image: url('{$bg_image}')";
        $blockStyles[] = "background-size: cover";
        $blockStyles[] = "background-position: center";
    } elseif ($colorStr) {
        // Chỉ có màu nền
        $blockStyles[] = "background-color: {$colorStr}";
    }

    // Text color
    if ($text_color) {
        $blockStyles[] = "color: {$text_color}";
    }

    // Font Family
    if ($font_family) {
        $blockStyles[] = "font-family: {$font_family}";
    }

    // Font Size
    if ($font_size) {
        $blockStyles[] = "font-size: {$font_size}px";
    }

    if ($padding_top !== null && $padding_top !== '') {
        $blockStyles[] = "padding-top: {$padding_top}px";
    } elseif ($padding_bottom !== null && $padding_bottom !== '') {
        $blockStyles[] = "padding-top: {$padding_bottom}px";
    }

    if ($padding_bottom !== null && $padding_bottom !== '') {
        $blockStyles[] = "padding-bottom: {$padding_bottom}px";
    }

    if ($padding_left !== null && $padding_left !== '') {
        $blockStyles[] = "padding-left: {$padding_left}px";
    }

    if ($padding_right !== null && $padding_right !== '') {
        $blockStyles[] = "padding-right: {$padding_right}px";
    }


    $commonBlockStyle = implode('; ', $blockStyles);
@endphp
