# tollwerk ePrivacy Consent Manager

The tollwerk ePrivacy Consent Manager ("ePrivacy") provides a clean way to manage cookies inside the TYPO3 backend.

## Features

* Also works without any javascript!
* All cookies are blocked by default, no matter if they are set client-side or server-side.
* As TYPO3 editor you can register cookies you want to be manageable by the user.
* Provides a frontend plugin as TYPO3 content element where users can give or revoke consent for registered cookies.
* Provides TypoScript conditions and Fluid ViewHelpers to check for cookie consent.
* As TYPO3 editor you can set access options for pages and content elements so that they are only rendered if the user gave consent to the required cookies.

## Not included

* This extension comes without any CSS and Javascript.
* There is no "cookie consent banner" or any other overlay that appears when the user visits your page for the first time.
You have to provide this yourself. See "Cookie banner".

## Installation

### Install extension with composer

```
composer install tollwerk/tw-eprivacy
```

### Include TypoScript template

It is necessary to include the TypoScript provided by this extension. Select your root page and edit the TypoScript root template
and add the "tw_privacy" template.

![](/Docs/Installation/Assets/include_typoscript_template.jpg)

### Create all required pages

The extension needs the following pages inside your page tree.

* A page containing the ePrivacy frontend plugin. On this page, create a new content element by selecting "Plugins" inside the content wizzard and choose "ePrivacy Consent Manager".
* A page containing the data privacy disclaimer.
* A page containing the imprint and other legal information. This page will be linked.
* A folder for all ePrivacy records. Alternatively, you can use the page with the ePrivacy plugin for storing your records.

![](/Docs/Installation/Assets/pages_and_plugin.jpg)

### Set TypoScript constants

Open the TYPO3 module `Web > Template`, select your root page in the page tree, select "Constant Editor" and the ePrivacy extension.

![](/Docs/Installation/Assets/constant_editor.jpg)

The following options are required for everything to work. All other options have default values.

* **Default Storage PID** is the page UID where all ePrivacy records are stored. Only records of cookies (and other releated things)
stored there will be found and used by the eprivacy plugin.

* **Imprint Page (ID)** is the UID of the imprint page.

* **Privacy Policy Page (ID)** is the UID of the data privacy page.

* **Plugin Page (ID)** is the UID of the page that contains the eprivacy frontend plugin.



## For editors

Editors can set the required consent for content elements individually inside the TYPO3 backend.
The field "Needs cookie consent" can be found on inside the "access"-tab of each content element.

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

## Cookie banner

```html
<!-- The `action` does not have to open a specific page, so you can just use your start page. -->
<form method="post" action="/?tx_twruag_cookiebanner[action]=cookie&tx_twruag_cookiebanner[controller]=Banner">

    <!-- Hidden redirect link for returning to the current page. -->
    <input type="hidden"
           name="cookieConsentBacklink"
           value="https://example.org/">

    <!-- Accept all cookies and return to current page. -->
    <button type="submit"
            name="tx_twruag_cookiebanner[accept]"
            value="2">
        Select all
    </button>

    <!-- Accept only necessary cookies and return to the current page. -->
    <button type="submit"
            name="tx_twruag_cookiebanner[accept]"
            value="1">
        Only technical
    </button>

    <!-- Open the page with the ePrivacy frontend plugin -->
    <a href="/cookie-consent">
        Learn more
    </a>
</form>
```
