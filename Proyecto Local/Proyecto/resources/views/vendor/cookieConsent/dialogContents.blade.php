<div class="alert alert-info text-center js-cookie-consent cookie-consent col-12">

    <span class="cookie-consent__message col-md-auto col-12">
        {!! trans('cookieConsent::texts.message') !!}
    </span>
    <div class="col-md-auto col-12 d-inline-block">
        <button class="js-cookie-consent-agree cookie-consent__agree btn btn-info">
            {{ trans('cookieConsent::texts.agree') }}
        </button>
        <button class="js-cookie-consent-modify cookie-consent__modify btn btn-info">
            {{ trans('cookieConsent::texts.modify') }}
        </button>
    </div>
</div>
