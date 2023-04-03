# Project Documentation: nbbib.lib.unb.ca

## Local Development Procedures
A simple ```dockworker start-over``` is enough to spin up a local development instance.

Some quick notes:
* The configured theme is ```nbbib_lib_unb_ca```, and all changes should be made to it. Its location in the repository is ```/themes/custom```.
* The theme ```custom``` inherits from ```bootstrap4```.
* There is also a minor-customization admin theme named ```nbbib_admin```.
* The teme ```nbbib_admin``` inherits from ```seven```.  
* Once deployed locally, any changes to the _themes_ or _assets_ can then be updated with the usual: ```dockworker theme:build-all```

## Data Overview
NBBIB uses a combination of Drupal content structures and custom entities. All custom entities are defined and configured in module *yabrm*. Other content structures include:
* Static Content Page (content type/node) — For static pages.
* Contributor (Drupal Paragraph) — A relationship structure connecting contributors and roles to their assigned references.
* NBBIB Archives (taxonomy) — Controlled vocabulary to store reference archives.
* NBBIB Locations (taxonomy) — Controlled vocabulary to store reference locations.
* NBBIB Residences (taxonomy) — Controlled vocabulary to store contributor New Brunswick residence locations.
* YABRM Contributor Roles (taxonomy) — Controlled vocabulary to store contributor roles (author, editor, etc).
* YABRM Reference Topics (taxonomy) — Controlled vocabulary to store reference topic tags.

## YABRM Module Data
YABRM stands for Yet Another Bibliographic Reference Module. There is little reusability to YABRM, however, and the module has many co-dependencies with NBBIB by now. YABRM largely defines custom entities and their associated Drupal elements. Field display for all entities is exposed to Drupal GUI, but the field configuration is hard-coded into all class definitions but ContribArchival. The following entities are defined (names are largely self-explanatory in the bibliography context):
* Collection (class BibliographicCollection) — A collection grouping bibliographic references.
* Contributor (class BibliographicContributor) — Can be an author, editor, illustrator, etc.
* Contributor Archival (class ContribArchival) — Additional data to extend some notable contributor records. Unlike every other entity on this list, most Contributor Archival fields are defined through Drupal configuration instead of hard-coded into the class definition itself. This was done as an experimental feature to ease maintenance work.  
* Bibliographic Reference (class BibliographicReference) — Parent class of all reference entities. Fields defined here are inherited by all reference types.
* Book (class BookReference) — Mostly defines specific fields used for books.
* Book Section (class BookSection) — Mostly defines specific fields used for book sections.
* Journal Article (class JournalArticleReference) — Mostly defines specific fields used for journal articles.
* Thesis (class ThesisReference) — Mostly defines specific fields used for theses.

## Module Overview
* context_branding: Offers a custom branding block which renders the site title as a no-link H1 header on homepage only.
* cv_util: Provides utility scripts for maintenance tasks. E.g. rm_contribs.php removes all contributors from the Drupal database.
* instance_initial_content: Contains the initial site data migration, originally from an older version of the site.
* nbbib_core: Contais all custom functionality extending Drupal capabilities. All hooks pertainig to data behaviour during ingestion and storage, as well as what data is available for display, should be found in the .module file here.
* yabrm: The previously-mentioned module defining custom data entities.