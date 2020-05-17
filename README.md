CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Configuration
 * Maintainers


INTRODUCTION
------------

This module provides a PCA address Webform element which integrates
with Loqate (previously PCA/Addressy) address lookup:

 * For a full description of the module, visit the project page:
   https://www.drupal.org/project/loqate
 * To submit bug reports and feature suggestions or to track changes, either use
   the issue tracker on Drupal.org or GitHub:
   - https://www.drupal.org/project/issues/loqate
   - https://github.com/drupal-modules/loqate


REQUIREMENTS
------------

This module requires the following outside of Drupal core:

 * Key - https://www.drupal.org/project/key
 * Address (required by `pca_address`) - https://www.drupal.org/project/address
 * Webform (required by `pca_webform`) - https://www.drupal.org/project/webform
 * a Loqate API key -
   https://www.loqate.com/resources/support/setup-guides/advanced-setup-guide/#creating_a_key


INSTALLATION
------------

 * Install the Loqate module as you would normally install a contributed
   Drupal module. Visit [Installing Drupal 8 Modules](https://www.drupal.org/node/1897420) for further
   information.


CONFIGURATION
-------------

 1. Navigate to Administration > Extend and enable the module.
 2. Once you have a Loqate account, login and create a New Service. Hit
    "Create" under API Key to generate a new general purpose key that will give
    you access to all services.
 3. Navigate to Administration > Configuration > System > Keys and create a new
    key entity with the API key from Loqate.
 4. Navigate to Administration > Configuration > Web services > Loqate API
    for configurations.
 5. Choose the key entity for the Loqate API key and save the configuration.


MAINTAINERS
-----------

 * Rakesh James (rakesh.gectcr) - https://www.drupal.org/u/rakeshgectcr
 * Ali Hover (a.hover) - https://www.drupal.org/u/ahover
 * Sang Lostrie (baikho) - https://www.drupal.org/u/baikho

### Supporting organizations:

 * [Access](https://www.drupal.org/access) - Development and maintenance
 * [CTI Digital](https://www.drupal.org/cti-digital) - Development
