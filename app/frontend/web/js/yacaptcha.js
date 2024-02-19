function callback(token) {
  const contactFormTokenInput = document.getElementById('contactform-token');
  const submitButton = document.getElementById('smartcaptcha-demo-submit');

  contactFormTokenInput.value = token;
  if (token) {
    submitButton.removeAttribute('disabled');
  } else {
    submitButton.setAttribute('disabled', '1');
  }
}

function smartCaptchaInit() {
  if (!window.smartCaptcha) {
    return;
  }
  window.smartCaptcha.render('captcha-container', {
    sitekey: 'ysc1_uSeMNobmkLqkrZOgCwINpFfKphN8FLJKIw6Ep2rg9e745874',
    callback: callback,
  });
}

function smartCaptchaReset() {
  if (window.smartCaptcha) {
    window.smartCaptcha.reset();
  }
}

function smartCaptchaGetResponse() {
  if (!window.smartCaptcha) {
    return;
  }
  var response = window.smartCaptcha.getResponse();
  alert(response);
}
