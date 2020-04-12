NelmioFormGetParameterPluginBundle
==================================

This bundle extends NelmioApiDocBundle by providing ability to auto-register simple forms items as GET parameters.

Installation
------------

Install with composer:

```
composer require sandronimus/nelmio-form-get-parameter-plugin-bundle
```

How To Use
----------

Add annotation to your controller action:

```
@FormGetParameter(formType=SomeFormType::class)
```

Be aware only simple one-level forms supported.
