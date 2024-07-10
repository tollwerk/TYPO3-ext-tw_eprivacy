# tollwerk ePrivacy Consent Manager

The tollwerk ePrivacy Consent Manager ("ePrivacy") provides a clean way to manage cookies inside the TYPO3 backend.

## Features

* Works without any javascript!
* All cookies are blocked by default, no matter if they are set client-side or server-side.
* As TYPO3 editor you can register cookies you want to be manageable by the user.
* Provides a frontend plugin as TYPO3 content element where users can give or revoke consent for registered cookies.
* Provides TypoScript conditions and Fluid ViewHelpers to check for cookie consent.
* As TYPO3 editor you can set access options for content elements so that they are only rendered if the user gave
  consent to the required cookies.
  existing TYPO3 hooks for content rendering.

## Not included

* This extension comes without any CSS and Javascript.

## Supported languages

* English
* German
* Spanish
* French
* Italian

## Installation

### Install extension with composer

```
composer require tollwerk/tw-eprivacy
```

### Include TypoScript template

It is necessary to include the TypoScript provided by this extension. Select your root page, edit the TypoScript root template
and add the "tw_privacy" template.

### Create all required pages

The extension needs the following pages inside your page tree.

* A page containing the ePrivacy frontend plugin. On this page, create a new content element by selecting "Plugins" inside the content wizard and choose "ePrivacy Consent Manager".
* A page containing the data privacy disclaimer.
* A page containing the imprint and other legal information. This page will be linked.
* A folder for all ePrivacy records. Alternatively, you can use the page with the ePrivacy plugin for storing your records.

### Set TypoScript constants

Open the TYPO3 module `Web > Template`, select your root page in the page tree, select "Constant Editor" and the ePrivacy extension.

The following options are required for everything to work. All other options have default values.

* **Default Storage PID** `plugin.tx_tweprivacy_eprivacy.persistence.storagePid` is the page UID where all ePrivacy records are stored.

* **Imprint Page (ID)** `plugin.tx_tweprivacy_eprivacy.settings.imprint` is the UID of the imprint page.

* **Privacy Policy Page (ID)** `plugin.tx_tweprivacy_eprivacy.settings.privacy` is the UID of the data privacy page.

* **Plugin Page (ID)** `plugin.tx_tweprivacy_eprivacy.settings.pluginPid` is the UID of the page that contains the eprivacy frontend plugin.

### Create cookie records

You should create cookie records for all cookies that are used by your website. Cookies that do not have a corresponding
cookie record will be blocked by ePrivacy. There are two types of records:


#### ePrivacy Subject Type

Each cookie record must belong to a so-called subject type. Subject types are used to group cookie records together and
provide basic information about all of them. Typicall subject types can be "Essential", "Marketing", "Statistics" etc.
A subject type has the following properties:

* **Visible:** Enables / disables all cookie records assigned to this subject type.
* **Title:** Human readable title like "Essential cookies", "Marketing" etc.
* **Description:** Human readable description for all cookies of this type.
* **Needs consent:** If set, the user can choose to give consent or revoke it for  all cookies of this type.
If not set, the cookies still are visible with their titles and descriptions but can not be revoked by the user.

#### ePrivacy Subject

This is a single cookie record. You need at least one cookie 'eprivacy_consent' for remembering what other cookies
the user accepted or revoked. For this you can use the values of the screenshot below.

![](/Docs/Installation/Assets/cookie_eprivacy.jpg)

A cookie record has the following properties:

* **Visible:** Enalbe / disable this record.
* **Public:** If not set, will not be shown in the frontend plugin.
* **Title:** Human readable title.
* **Mode:** Can be "Cookie" (default) or "Set". Sets can be used to group cookies together so that the user can accept or revoke all of them at once.
* **Type:** The ePrivacy Subject Type. **Important:** This type must have "Needs consent" disabled, otherwise the user consent can not be saved correctly. See "ePrivacy Subject" above.
* **Identifier:** Use this to check for consent with the given ViewHelper or TypoScript conditions.
* **Provider:** Where does this cookie come from? Something like "Google", "TYPO3", "Company XY"..
* **Public name:** The actual name of the cookie.
* **Belongs to set:** All cookies belonging to the same set are updated together if the user accepts/revokes the entire set.
* **Lifetime:** Cookie lifetime in seconds.
* **Expires with session:** If set, the cookie will expire when the browser session ends. It's up to the browser to decide on what occasions, like closing the Window, closing a tab etc.
* **Purpose:** A hopefully easy to understand description of the purpose of this cookie.
* **Purpose Short Description:** The short description of the purpose. Will be used when a content element is not shown due to missing consent. See 'For editors' below.

## For editors

Editors can set the required consent for content elements individually inside the TYPO3 backend.
The field "Needs cookie consent" can be found on inside the "access"-tab of each content element.

## For developers

### Overwring Fluid Content Default Layout

For showing or hiding content elements based on cookie consent (see "For editors" above), this Extension overwrites the default Fluid Layout for content elements by setting
`lib.contentElement.layoutRootPaths.1 = EXT:tw_eprivacy/Resources/Private/Layouts/ContentElements/`. If your own extension needs to overwrite the default layout as well, please use 
the file _Resources/Private/Layouts/ContentElements/Default.html` of this extension as basis for all further changes. Otherwise content elements will always be shown, even if there
is missing consent for some of the required cookies!

### Fluid ViewHelpers

#### eprivacy:consent

The `&lt;eprivacy:consent>` viewhelper is a specialized condition viewhelper that enables you to test for the user's consent with particular subject identifiers. Examples:

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

**ATTENTION:** ~~As the result depends on the users current consent settings, it is essential to ensure that the
viewhelper is used in an **uncached environment / template**!~~ With the latest update everything should work in cached
environments as well. If there are any problems, don't hesitate to open an issue.


#### eprivacy:getCookie

Returns a cookie as an array if found by its name.

```html
<!-- Show the "eprivacy_consent" cookie data. -->
<f:debug>{eprivacy:getCookie(name: 'eprivacy_consent')}</f:debug>

<!-- Check the "eprivacy_consent" cookie. -->
<f:if condition="{eprivacy:getCookie(name: 'eprivacy_consent')}">
    <f:else>
        <!-- Show something (like a consent dialog) if the cookie is not set. -->
    </f:else>
</f:if>
```


### TypoScript condition

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

### Cookie consent dialog

This extension provides a dialog that is shown when a user visits your page for the first time. This dialog will only
be shown when the Cookie "eprivacy_consent" is not set. The dialog contains the following buttons and links:

* **Accept all:** Accepts all (registered, see "ePrivacy Subject") cookies and refreshes the current page.
* **Accept necessary only:** Only accepts (registered, see "ePrivacy Subject") cookies marked as necessary,
revokes all others, refreshes the current pages.
* **Learn more:** Opens the page with the ePrivacy plugin.
* Additionally, there are links to the imprint and data privacy pages.

![](/Docs/Installation/Assets/dialog.jpg)

You can disable this dialog inside the constant editor, see `plugin.tx_tweprivacy_eprivacy.settings.showDialog`.

#### Render the dialog by hand

When using `plugin.tx_tweprivacy_eprivacy.settings.showDialog` inside the constant editor, the dialog will be
included by the following TypoScript code:

```typoscript
# Include the cookie consent dialog.
[{$plugin.tx_tweprivacy_eprivacy.settings.showDialog}]
    page.2 < lib.ePrivacyDialog
[GLOBAL]
```

If `page.2` is already occupied, or, for some reason, you want to render the dialog anywhere else, you can do this by disabling
`plugin.tx_tweprivacy_eprivacy.settings.showDialog` and including `lib.ePrivacyDialog` by yourself.

Please note that for accessibility reasons you should place the dialog as first content inside the `<body>`-tag.
