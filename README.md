# tollwerk ePrivacy Consent Manager

## Fluid ViewHelper

The `<eprivacy:consent>` viewhelper is a specialized condition viewhelper that enables you to test for the user's consent with particular subject identifiers. Examples:

```html
<!-- Test for a single subject identfier -->
<eprivacy:consent identifier="google.analytics.ga">
    <f:then>Google Analytics is allowed</f:then>
    <f:else>Google Analytics is not allowed</f:else>
</eprivacy:consent>

<!-- Test for multiple subject identfiers -->
<eprivacy:consent identifier="{0: 'eprivacy.consent', 1: 'google.analytics.ga'}">
    <f:then>Google Analytics is allowed</f:then>
    <f:else>Google Analytics is not allowed</f:else>
</eprivacy:consent>
```

**ATTENTION:** As the result depends on the users current consent settings, it is essential to ensure that the viewhelper is used in an **uncached environment / template**!

## TypoScript condition

Similar to the Fluid viewhelper, there's also a custom **userFunc** TypoScript condition that you can use to test for the user's consent with particular subject identifiers.

```typo3_typoscript
# Test for a single subject
[userFunc = user_ePrivacy("enable.webfonts")]
    # Add web fonts
[END]

# All these subjects must be allowed
[userFunc = user_ePrivacy("google.gtm", "google._ga", "google._gid", "google._gat")]
    # Add Google Tag Manager resources
[END]
```

It's important to know that TypoScript conditions are evaluated rather early in the life cycle of a request — even before any update of the user consent. That said, remember to perform a redirect immediately after each update of the user's choice so that TypoScript condition based accommodations become effective early on.
