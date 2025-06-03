<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>mysqltest</title>

    <!-- Fonts -->
    <link
        rel="preconnect"
        href="https://fonts.bunny.net"
    >
    <link
        href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
        rel="stylesheet"
    />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 grow">
        <div class="flex flex-col items-center gap-20">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex gap-6 items-baseline text-xl sm:text-2xl lg:text-3xl">
                <div class="flex flex-col items-end gap-5">
                    <div>Database records:</div>
                    <div>Latest record:</div>
                </div>
                <div class="flex flex-col items-start gap-5">
                    <div class="font-semibold">{{ $count }}</div>
                    <div class="font-semibold">{{ $latest }}</div>
                </div>
            </div>
            <div>
                <button
                    class="bg-gray-800 dark:bg-gray-100 text-gray-100 dark:text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-900 dark:hover:bg-gray-200 cursor-pointer"
                    onclick="window.location.reload()"
                >
                    Refresh
                </button>
            </div>
        </div>
    </div>

</body>

</html>
