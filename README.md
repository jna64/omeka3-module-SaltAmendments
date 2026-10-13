Saltaire Collection Amendments (module for Omeka S)
====================================================

[SaltAmendments] is a module for [Omeka S] that deals with various amendments
to the Omeka Core and added Modules for use by the Saltaire Collection

Amendments:

 1. Adds Google Analytics paying attention to user cookie preferences


Installation
------------

This module requires the module EUCookieBar to be installed. It must be installed
first

See general end user documentation for [installing a module].

* From the zip

Download the last release [SaltAmendments.zip] from the list of releases, and
uncompress it in the `modules` directory.

* From the source and for development

If the module was installed from the source, rename the name of the folder of
the module to `SaltAmendments`.


Quick start
-----------

After installation, Site Settings inlcudes a new field for 'Google Analytics Tag'
in the 'Privacy' section. Add your Analytics tag here.

 Note that Google Analytics is only enabled if:

- A public page is being viewed (i.e. administration pages are not tracked)
- A message has been entered in the 'Privacy' section of Site Settings
- A tag code has been entered in the 'Privacy' section of Site Settings


Warning
-------

Use it at your own risk.

Always backup your files and your databases before installation.


License
-------

This module is distributed under the GNU General Public License, version 3 9 ([GNU/GPL]). The 
full text of this license is given in the LICENSE file.

Copyright
---------

* Copyright Jonathan Ainsworth, 2026 (see [jna64] on GitHub)

[SaltAmendments]: https://gitlab.com/jna64/SaltAmendments
[Omeka S]: https://omeka.org/s
[plugin EUCookieBar]: https://github.com/digihum/omeka-plugin-eucookiebar
[EUCookieBar.zip]: https://gitlab.com/jna64/-/releases
[installing a module]: https://omeka.org/s/docs/user-manual/modules/#installing-modules
[GNU/GPL]: https://www.gnu.org/licenses/gpl-3.0.html
[jna64]: https://gitlab.com/jna64 "J Ainsworth"
