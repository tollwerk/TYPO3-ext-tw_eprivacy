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

Similar to the Fluid viewhelper, there's also a custom TypoScript condition (implemented as [Expression Language extension](https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/Conditions/Index.html)) named `ePrivacy` that you can use to test for the user's consent with particular subject identifiers.

```typo3_typoscript
# Test for a single subject
[ePrivacy("enable.webfonts") == true]
    # Add web fonts
[END]

# All these subjects must be allowed
[ePrivacy("google.gtm", "google._ga", "google._gid", "google._gat") == true]
    # Add Google Tag Manager resources
[END]
```
