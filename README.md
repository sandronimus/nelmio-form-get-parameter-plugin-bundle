NelmioFormGetParameterPluginBundle
==================================

This bundle extends NelmioApiDocBundle by providing ability to auto-register forms as GET parameters.

Installation
------------

Install with composer:

```
composer require sandronimus/nelmio-form-get-parameter-plugin-bundle
```

Add `Sandronimus\NelmioFormGetParameterPluginBundle\NelmioFormGetParameterPluginBundle` to your bundles.

How To Use
----------

Add attribute to your controller action:

```
#[FormGetParameter(SomeFormType::class)]
```

And you will get whole form mapped as GET parameters in API documentation.
