<div style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
    <h2>Reset Password FROM {{ config('app.name') }}</h2>
    <hr>
    <p>
        Please Click button bellow to reset your password
    </p>
    <p>{!! nl2br($btnConfirm) !!}</p>
    <p>
        if button doesn't work, try this link
    </p>
    <p>{!! nl2br($url) !!}</p>
</div>