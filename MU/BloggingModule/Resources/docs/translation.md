# TRANSLATION INSTRUCTIONS

To create a new translation follow the steps below:

1. First install the module like described in the `install.md` file.
2. Open a console and navigate to the Zikula root directory.
3. Execute this command replacing `en` by your desired locale code:

`php -dmemory_limit=2G bin/console translation:extract en --bundle=MUBloggingModule --enable-extractor=jms_i18n_routing --output-format=po`

You can also use multiple locales at once, for example `de fr es`.

4. Translate the resulting `.po` files in `modules/MU/BloggingModule/Resources/translations/` using your favourite Gettext tooling.

Note you can even include custom views in `app/Resources/MUBloggingModule/views/` and JavaScript files in `app/Resources/MUBloggingModule/public/js/` like this:

`php -dmemory_limit=2G bin/console translation:extract en --bundle=MUBloggingModule --enable-extractor=jms_i18n_routing --output-format=po --dir=./modules/MU/BloggingModule --dir=./app/Resources/MUBloggingModule`

For questions and other remarks visit our homepage https://homepages-mit-zikula.de.

Michael Ueberschaer (info@homepages-mit-zikula.de)
https://homepages-mit-zikula.de
