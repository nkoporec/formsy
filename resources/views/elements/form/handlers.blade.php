<div>
    <tabs bg="bg-formsydark">
        <tab title="Sendgrid">
            @include('handlers.sendgrid')
        </tab>
        <tab title="Gmail">
            @include('handlers.gmail')
        </tab>
        <tab title="Sheets">
            @include('handlers.gsheet')
        </tab>
        <tab title="Mailchimp">
            @include('handlers.mailchimp')
        </tab>
    </tabs>
</div>
