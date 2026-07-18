<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        @stack('styles')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="book-open" :href="route('books.index')" :current="request()->routeIs('books.*')" wire:navigate>
                        {{ __('Studien') }}
                    </flux:sidebar.item>
                    @unless (auth()->user()?->isAdmin())
                        <flux:sidebar.item icon="microphone" :href="route('voice.practice')" :current="request()->routeIs('voice.practice')" wire:navigate>
                            {{ __('Sprechübung') }}
                        </flux:sidebar.item>
                    @endunless
                    <flux:sidebar.item icon="credit-card" :href="route('billing')" :current="request()->routeIs('billing')" wire:navigate>
                        {{ __('Billing') }}
                    </flux:sidebar.item>
                    @unless (auth()->user()?->isAdmin())
                        <flux:sidebar.item icon="play-circle" :href="route('dashboard') . '?filter=free'" :current="request()->routeIs('dashboard') && request()->query('filter') === 'free'" wire:navigate>
                            {{ __('Free') }}
                        </flux:sidebar.item>
                    @endunless
                    <flux:sidebar.item icon="book-open" :href="route('user.guide')" :current="request()->routeIs('user.guide')" wire:navigate>
                        {{ __('User Guide') }}
                    </flux:sidebar.item>
                    @if (auth()->user()?->isAdmin())
                        <flux:sidebar.item icon="academic-cap" :href="route('admin.guide')" :current="request()->routeIs('admin.guide')" wire:navigate>
                            {{ __('Admin-Guide') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="chart-bar" :href="route('student.performance')" :current="request()->routeIs('student.performance')" wire:navigate>
                            {{ __('Student Performance') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="academic-cap" :href="route('admin.books')" :current="request()->routeIs('admin.books')" wire:navigate>
                            {{ __('Bücher') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="envelope" :href="route('admin.email-scheduler')" :current="request()->routeIs('admin.email-scheduler')" wire:navigate>
                            {{ __('E-Mail-Planung') }}
                        </flux:sidebar.item>
                        @if (auth()->user()->isOwner())
                            <flux:sidebar.item icon="currency-dollar" :href="route('admin.finance')" :current="request()->routeIs('admin.finance')" wire:navigate>
                                {{ __('Finance') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="users" :href="route('owner.panel')" :current="request()->routeIs('owner.panel')" wire:navigate>
                                {{ __('Owner Panel') }}
                            </flux:sidebar.item>
                        @endif
                    @endif
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            @auth
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()?->name" />
            @else
                <div class="hidden lg:block p-4 border-t border-zinc-200 dark:border-zinc-700">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-zinc-700 dark:text-zinc-300 hover:underline">Anmelden</a>
                </div>
            @endauth
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            @auth
                <flux:dropdown position="top" align="end">
                    <flux:profile
                        :initials="auth()->user()?->initials()"
                        icon-trailing="chevron-down"
                    />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <flux:avatar
                                        :name="auth()->user()?->name"
                                        :initials="auth()->user()?->initials()"
                                    />

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <flux:heading class="truncate">{{ auth()->user()?->name }}</flux:heading>
                                        <flux:text class="truncate">{{ auth()->user()?->email }}</flux:text>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                {{ __('Settings') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer"
                                data-test="logout-button"
                            >
                                {{ __('Log out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <a href="{{ route('login') }}" class="text-sm font-bold text-zinc-700 dark:text-zinc-300 hover:underline">Anmelden</a>
            @endauth
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
