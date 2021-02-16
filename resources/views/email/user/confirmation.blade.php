<div style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
    <h2>Email confirmation from {{ config('app.name') }}</h2>
    <hr>
    <h3>Hallo {{ $name }}</h3>

    <p>
        Please confirmation your email address
    </p>
    <p>{!! nl2br($btnConfirm) !!}</p>
    <p>
        if button doesn't work, try this link
    </p>
    <p>{!! nl2br($url) !!}</p>
</div>