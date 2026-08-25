# Translations

This directory is used for optional GNU gettext translation files for **goobd Admin Experience for YOURLS**.

Translation files must use the following filename format:

```text
goobd-admin-experience-LOCALE.mo
```

For example:

```text
goobd-admin-experience-bn_BD.mo
goobd-admin-experience-fr_FR.mo
```

The plugin automatically checks the active YOURLS locale and loads the matching translation file when one is available.

For example, if the active locale is `bn_BD`, the plugin will look for:

```text
goobd-admin-experience-bn_BD.mo
```

If no matching translation file is found, the plugin will continue to use its default English interface.

Only compiled `.mo` files are required at runtime.
