<x-mail::message>
# Verify Your Email Address

Thank you for registering with {{ config('app.name') }}! Please verify your email address by clicking the button below.

<x-mail::button :url="$url">
Verify Email Address
</x-mail::button>

This verification link will expire in 60 minutes.

If you did not create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
