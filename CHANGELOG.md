# Change Log

## 1.1.0 - 2025-08-08

### Added

- Added getImage() function to Image as shortcut to getDriver()-\>getImage().
- Added close() function to Image as shortcut to setHandle(null).
- Added more image param override options for each driver image format.
- Driver now extends AbstractDriver utility class.

### Fixed

- Fixed PNG compression level option in Imagick driver.

## 1.0.1 - 2023-07-15

### Fixed

- Fixed missing semicolon in use statement of Imagick GrayscaleFunction.

## 1.0.0 - 2023-04-22

Initial release.
