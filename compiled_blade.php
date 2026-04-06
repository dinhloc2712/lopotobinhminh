<?php $__env->startSection('meta_title', $post->meta_title ?: $post->title); ?>
<?php $__env->startSection('meta_description', $post->meta_description); ?>
<?php $__env->startSection('meta_keywords', $post->meta_keywords); ?>
<?php $__env->startSection('meta_image', $post->thumbnail ? asset($post->thumbnail) : null); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $stickyOffset = 0;
    ?>
    <?php $__currentLoopData = $post->blocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $content = $block->content;
        ?>
        <section id="block-<?php echo e($block->id); ?>"
            class="block-section block-<?php echo e($block->type); ?> <?php echo e($block->type !== 'header' ? 'reveal' : 'sticky-top'); ?>"
            style="<?php echo e($block->style); ?>;
                    <?php if($block->type === 'header'): ?> z-index: <?php echo e(1020 - $loop->index); ?>; 
                        top: <?php echo e($stickyOffset); ?>px; <?php endif; ?>">
            <?php if(!in_array($block->type, ['header', 'footer'])): ?>
                <?php if ($__env->exists('posts.partials.blocks.shared_title', ['content' => $content])) echo $__env->make('posts.partials.blocks.shared_title', ['content' => $content], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if ($__env->exists('posts.partials.blocks.' . $block->type, ['content' => $content])) echo $__env->make('posts.partials.blocks.' . $block->type, ['content' => $content], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </section>

        <?php
            if ($block->type === 'header') {
                $stickyOffset += 70; // Cộng dồn chiều cao min-h của các header
            }
        ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var offcanvasElements = document.querySelectorAll('.offcanvas');
            offcanvasElements.forEach(function(el) {
                el.addEventListener('show.bs.offcanvas', function() {
                    var section = this.closest('section.block-header');
                    if (section) {
                        section.dataset.originalZIndex = section.style.zIndex;
                        section.style.setProperty('z-index', '1060', 'important');
                    }
                });
                el.addEventListener('hidden.bs.offcanvas', function() {
                    var section = this.closest('section.block-header');
                    if (section) {
                        if (section.dataset.originalZIndex) {
                            section.style.zIndex = section.dataset.originalZIndex;
                        } else {
                            section.style.removeProperty('z-index');
                        }
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>