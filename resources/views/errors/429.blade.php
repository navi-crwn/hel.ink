@extends('layouts.app')

@section('title', 'Too Many Requests')

@section('content')
    <div class="mx-auto max-w-lg py-20 text-center">
        <h1 class="text-4xl font-bold text-rose-600 mb-4">429: Too Many Requests</h1>
        <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">You've hit the rate limit for creating shortlinks. This helps keep HEL.ink stable for everyone.</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Please wait a minute and try again. If you need higher limits, <a href="{{ route('register') }}" class="text-blue-600 hover:underline">create an account</a> for more features and quota.</p>
        <a href="{{ url()->previous() }}" class="inline-block rounded-full bg-blue-600 px-6 py-2 text-white font-semibold hover:bg-blue-500">Go back</a>
    </div>
@endsection
