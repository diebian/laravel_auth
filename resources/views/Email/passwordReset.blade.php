@component('mail::message')
{{ trans('auth.password_change_title') }}

<p>
    {{ trans('auth.password_click_link') }}
</p>

@component('mail::button', ['url' => config('app.frontend').'/response-password-reset?token='.$token])
    {{ trans('auth.button_reset_password') }}
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
