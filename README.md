App Manager
============

User interface for Yii configuration. Allows to manage components, extensions, modules.

The main goal of this extension is to utilize documentation, default options and many other things created 
with the source code, but usually not presented in a convenient form for day-to-day operations. For example,
when need to configue a newly added extension and switch between its source code and Yii configue for proper
setup. App Manager eliminates such cases by providing abilities to configue and see useful comments in one place.
Also it decreases posibility of misconfiguration by providing some basic validation rules. 

Not the last goal of actual extension is to show **all** available for configuration properties. Lots of them
usually hidden deeply inside sources (or by inheritance tree) and can be hardly recognizable.

##Requirements
- Tested in Yii 1.12, but should work starting from Yii 1.10.
- Application /config/main.php should be writable by a server (if not, only browsing will be available).

##Installation
Extract the archive under /protected/modules/appManager.
Add "appManager" under the configue "modules" section.

##Usage
Manager accessible as a usual module through /index.php?r=appManager (or /appManager).

There are two main sections:
- **App** (specific for current web application components).
- **Core** (Yii framework components).

For every section are available listing of components, detailed view and update form.
For **App** there is a specific page **Settings** that contains main options.  

Actions that can be performed with components:
- **Activation** (adding to the configue).
- **Deactivation**.
- **Update** (changing properties).
- **Restore** (returns to default settings).

**NOTICE**: it's recommended to backup the original configue before performing any of
mentioned actions.