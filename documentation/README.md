# Project Documentation: nbbib.lib.unb.ca

## Local Development Procedures
A simple ```dockworker start-over``` is enough to spin up a local development instance.

Some quick notes:
* The configured theme is ```nbbib_lib_unb_ca```, and all changes should be made to it. Its location in the repository is ```/themes/custom```.
* The theme ```custom``` inherits from ```bootstrap4```.
* There is also a minor-customization admin theme named ```nbbib_admin```.
* The teme ```nbbib_admin``` inherits from ```seven```.  
* Once deployed locally, any changes to the _themes_ or _assets_ can then be updated with the usual: ```dockworker theme:build-all```