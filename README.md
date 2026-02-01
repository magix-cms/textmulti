Textmulti
=========

Plugin Textmulti (texte multiple) for Magix CMS 3

### version 

[![release](https://img.shields.io/github/release/magix-cms/textmulti.svg)](https://github.com/magix-cms/textmulti/releases/latest)
![License](https://img.shields.io/github/license/magix-cms/geminiai.svg)
![PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-blue.svg)

## Installation
 * Décompresser l'archive dans le dossier "plugins" de magix cms
 * Connectez-vous dans l'administration de votre site internet 
 * Cliquer sur l'onglet plugins du menu déroulant pour sélectionner textmulti.
 * Une fois dans le plugin, laisser faire l'auto installation
 * Il ne reste que la configuration du plugin pour correspondre avec vos données.

## Upgrade
 * Supprimer l'ancien plugin
 * Envoyer les nouveaux fichiers
 * Connectez-vous dans l'administration de votre site internet 
 * Cliquer sur l'onglet plugins du menu déroulant pour sélectionner textmulti.
 * Une fois dans le plugin, laisser faire l'auto update

### MISE A JOUR
La mise à jour du plugin est à effectuer en remplaçant le dossier du plugin par la nouvelle version
et de se connecter à l'administration de celui-ci pour faire la mise à jour des tables SQL.

### Ajouter dans index.tpl (pages) la ligne suivante

```smarty
{textmulti_data controller='pages' root="pages" id_module=$pages.id}
{include file="textmulti/brick/textmulti.tpl"}
````

<pre>

This file is a plugin of Magix CMS.
Magix CMS, a CMS optimized for SEO

Copyright (C) 2008 - 2018 magix-cms.com support[at]magix-cms[point]com

AUTHORS :

 * Gerits Aurelien (Author - Developer) aurelien[at]magix-cms[point]com


Redistributions of files must retain the above copyright notice.
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see .

####DISCLAIMER

Do not edit or add to this file if you wish to upgrade magixcms to newer
versions in the future. If you wish to customize magixcms for your
needs please refer to magix-cms.com for more information.

</pre>
