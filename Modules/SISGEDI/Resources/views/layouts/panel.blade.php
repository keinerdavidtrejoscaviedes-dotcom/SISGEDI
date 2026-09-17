<!DOCTYPE html>
<html lang="es">
@include('sisgedi::layouts.partials.head')

<body class="bg-[#f0f4f8]">

    <div class="flex min-h-screen">
        @include('sisgedi::layouts.partials.sidebar-panel')

        <div class="flex-1 flex flex-col min-w-0">
            @include('sisgedi::layouts.partials.topbar-panel')

            <main class="flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    @include('sisgedi::layouts.partials.scripts')
    @yield('script')

</body>
</html>
