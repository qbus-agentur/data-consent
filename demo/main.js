import DataConsent from '../src/index';

var consent = new DataConsent({
    banner: false,
    functional: false
});

consent.isAccepted().then(function(state) {
    console.log(JSON.stringify(state));
});
consent.isAccepted('essential').then(function(type) {
    console.log('Essential cookies have been enabled');
});
consent.isAccepted('marketing').then(function(state) {
    console.log('Marketing is enabled');
});

consent.launch();
window.consent = consent;

Array.prototype.forEach.call(
    document.querySelectorAll('.open-data-consent-dialog'),
    function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            consent.forceOpen();
        });
    }
);


locationHashChanged = function() {
    var url = window.location.hash.substr(1)
    if (url) {
        if (!/^https?:\/\//i.test(url)) {
            url = 'https://' + url;
        }
        document.querySelector('iframe.demo-iframe').setAttribute('src', url);
    } else {
        document.querySelector('iframe.demo-iframe').removeAttribute('src');
    }
}
window.addEventListener("hashchange", locationHashChanged);
locationHashChanged();
