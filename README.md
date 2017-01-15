# initRepositoryWithPhpUnit

Command to initialize PhpUnit configuration and folders hierarchy.

> :information_source: **[Yoanm Tests strategy](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md) compliant (see [there](./doc/YoanmTestsStrategy.md))**

> :warning: **Rely on** this [Composer implementation](https://github.com/yoanm/initRepositoryWithComposer) for autoloading

> :warning: **Requires** [PhpUnitExtended](https://github.com/yoanm/PhpUnitExtended)

 * [Install](#install)
 * [In the box](#in-the-box)
 * [Full PhpUnit configuration](#full-phpunit-configuration)
 * [Contributing](#contributing)

## Install
```bash
composer require --global yoanm/init-repository-with-phpunit
```

## In the box

### Command options

### For phpunit configuration options

  * `--stop-on-error [true]|false` (Default value is `true` if option not used)

    Will add `stopOnError="true|false"` to the phpunit configuration

  * `--stop-on-failure [true]|false` (Default value is `true` if option not used)

    Will add `stopOnFailure="true|false"` to the phpunit configuration

  * `--convert-errors-to-exception [true]|false` (Default value is `true` if option not used)

    Will add `convertErrorsToExceptions="true|false"` to the phpunit configuration

  * `--convert-notices-to-exception [true]|false` (Default value is `true` if option not used)

    Will add `convertNoticesToExceptions="true|false"` to the phpunit configuration

  * `--convert-warnings-to-exception [true]|false` (Default value is `true` if option not used)

    Will add `convertWarningsToExceptions="true|false"` to the phpunit configuration

  * `--be-strict-about-output-during-test [true]|false` (Default value is `true` if option not used)

    Will add `beStrictAboutOutputDuringTests="true|false"` to the phpunit configuration

  * `--be-strict-about-tests-that-do-not-test-anything [true]|false` (Default value is `true` if option not used)

    Will add `beStrictAboutTestsThatDoNotTestAnything="true|false"` to the phpunit configuration

  * `--be-strict-about-changes-to-global-state [true]|false` (Default value is `true` if option not used)

    Will add `beStrictAboutChangesToGlobalState="true|false"` to the phpunit configuration

  * `--backup-globals [true]|false` (Default value is `true` if option not used)

    Will add `backupGlobals="true|false"` to the phpunit configuration

  * `--backup-static-attributes [true]|false` (Default value is `true` if option not used)

    Will add `backupStaticAttributes="true|false"` to the phpunit configuration

  * `--bootstrap bootstrap-path` 

    Could be a relative path (root path will be the phpunit configuration file location) or an absolute path

    Will add `bootstrap="bootstrap-path"` to the phpunit configuration

  * `--colors [true]|false` (Default value is `true` if option not used)

    Will add `colors="true|false"` to the phpunit configuration

  * `--process-isolation [true]|false` (Default value is `false` if option not used)

    Will add `processIsolation="true|false"` to the phpunit configuration

### Listeners

  * `--listener-class "Fully\Qualified\Namespace\To\ListenerClass"` *Multiple listeners allowed*

    Will append  following node `<listener class="Fully\Qualified\Namespace\To\ListenerClass"/>` into the `<listeners>` node of phpunit configuration

### Filter

  * `--filter-whitelist-directory path` *Multiple whitelist directories allowed*

    Could be a relative path (root path will be the phpunit configuration file location) or an absolute path

    Will append  following node `<directory>src</directory>` into the `<filter>` -> `<whitelist>` node of phpunit configuration

  * `--filter-whitelist-file path` *Multiple whitelist files allowed*

    Could be a relative path (root path will be the phpunit configuration file location) or an absolute path

    Will append  following node `<file>src</file>` into the `<filter>` -> `<whitelist>` node of phpunit configuration

### Test suites

  * `--test-suite-directory "[suiteName#]path"` *Multiple suite directories allowed*

    If `suiteName` is not provided, directory node will be appended into a default test suite named "default"

    Could be a relative path (root path will be the phpunit configuration file location) or an absolute path

    Will append  following node `<directory>src</directory>` into the `<testsuites>` -> `<testsuite name="suiteName">` node of phpunit configuration

  * `--test-suite-file "[suiteName#]path"` *Multiple suite files allowed*

    If `suiteName` is not provided, file node will be appended into a default test suite named "default"

    Could be a relative path (root path will be the phpunit configuration file location) or an absolute path

    Will append  following node `<directory>src</directory>` into the `<testsuites>` -> `<testsuite name="suiteName">` node of phpunit configuration


## Full PhpUnit configuration
```xml
<?xml version="1.0" encoding="UTF-8"?>

<phpunit
  stopOnError="true"
  stopOnFailure="true"
  convertErrorsToExceptions="true"
  convertNoticesToExceptions="true"
  convertWarningsToExceptions="true"
  beStrictAboutOutputDuringTests="true"
  beStrictAboutTestsThatDoNotTestAnything="true"
  beStrictAboutChangesToGlobalState="true"
  backupGlobals="true"
  backupStaticAttributes="true"
  forceCoversAnnotation="true"
  bootstrap="vendor/autoload.php"
  colors="true"
  processIsolation="false"
>
  <listeners>
        <listener class="Fully\Qualified\Namespace\To\ListenerClass"/>
        <listener class="Fully\Qualified\Namespace\To\SecondListenerClass"/>
  </listeners>

  <testsuites>
      <testsuite name="first-suite">
          <directory>directory_1</directory>
          <directory>directory_2</directory>
          <file>file_1</file>
          <file>file_2</file>
      </testsuite>
      <testsuite name="second-suite">
          <directory>directory_3</directory>
          <directory>directory_4</directory>
          <file>file_3</file>
          <file>file_4</file>
      </testsuite>
  </testsuites>

  <filter>
    <whitelist>
      <directory>directory_5</directory>
      <file>file_5</file>
    </whitelist>
  </filter>
</phpunit>
```
 
## Contributing
See [contributing note](./CONTRIBUTING.md)

