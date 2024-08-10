<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Profile')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <?php if(Laravel\Fortify\Features::canUpdateProfileInformation()): ?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.update-profile-information-form');

$__html = app('livewire')->mount($__name, $__params, 'lw-380101111-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                <?php if (isset($component)) { $__componentOriginal7b32e2c8c86a088fa824ad9246edeeea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-border','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-border'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea)): ?>
<?php $attributes = $__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea; ?>
<?php unset($__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b32e2c8c86a088fa824ad9246edeeea)): ?>
<?php $component = $__componentOriginal7b32e2c8c86a088fa824ad9246edeeea; ?>
<?php unset($__componentOriginal7b32e2c8c86a088fa824ad9246edeeea); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if(Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords())): ?>
                <div class="mt-10 sm:mt-0">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.update-password-form');

$__html = app('livewire')->mount($__name, $__params, 'lw-380101111-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </div>

                <?php if (isset($component)) { $__componentOriginal7b32e2c8c86a088fa824ad9246edeeea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-border','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-border'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea)): ?>
<?php $attributes = $__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea; ?>
<?php unset($__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b32e2c8c86a088fa824ad9246edeeea)): ?>
<?php $component = $__componentOriginal7b32e2c8c86a088fa824ad9246edeeea; ?>
<?php unset($__componentOriginal7b32e2c8c86a088fa824ad9246edeeea); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if(Laravel\Fortify\Features::canManageTwoFactorAuthentication()): ?>
                <div class="mt-10 sm:mt-0">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.two-factor-authentication-form');

$__html = app('livewire')->mount($__name, $__params, 'lw-380101111-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </div>

                <?php if (isset($component)) { $__componentOriginal7b32e2c8c86a088fa824ad9246edeeea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-border','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-border'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea)): ?>
<?php $attributes = $__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea; ?>
<?php unset($__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b32e2c8c86a088fa824ad9246edeeea)): ?>
<?php $component = $__componentOriginal7b32e2c8c86a088fa824ad9246edeeea; ?>
<?php unset($__componentOriginal7b32e2c8c86a088fa824ad9246edeeea); ?>
<?php endif; ?>
            <?php endif; ?>

            <div class="mt-10 sm:mt-0">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.logout-other-browser-sessions-form');

$__html = app('livewire')->mount($__name, $__params, 'lw-380101111-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>

            <?php if(Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures()): ?>
                <?php if (isset($component)) { $__componentOriginal7b32e2c8c86a088fa824ad9246edeeea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-border','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-border'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea)): ?>
<?php $attributes = $__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea; ?>
<?php unset($__attributesOriginal7b32e2c8c86a088fa824ad9246edeeea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b32e2c8c86a088fa824ad9246edeeea)): ?>
<?php $component = $__componentOriginal7b32e2c8c86a088fa824ad9246edeeea; ?>
<?php unset($__componentOriginal7b32e2c8c86a088fa824ad9246edeeea); ?>
<?php endif; ?>

                <div class="mt-10 sm:mt-0">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('profile.delete-user-form');

$__html = app('livewire')->mount($__name, $__params, 'lw-380101111-4', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/profile/show.blade.php ENDPATH**/ ?>